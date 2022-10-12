<?php

namespace Tests\Feature\Todo;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function create_todo_item()
    {
        //Arrange
        /** @var User $user */
        $user = User::factory()->createOne();
        $assignedTo = User::factory()->createOne();

        $this->actingAs($user);

        //Act
        $request = $this->post(route('todo.store'), [
            'title' => 'Todo item',
            'description' => 'Todo item description',
            'assignedTo' => $assignedTo->id
        ]);

        //Assert
        // 1. Garantir que o usuário seja redirecionado para a pagina de todos os to-dos
        $request->assertRedirect(route('todo.index'));

        //Garantir que o todo foi criado no banco de dados corretamente
        $this->assertDatabaseHas('todos', [
            'title' => 'Todo item',
            'description' => 'Todo item description',
            'assigned_to_id' => $assignedTo->id
        ]);
    }

    /** @test */
    public function add_a_file_to_the_todo_item()
    {
        Storage::fake('public');

        //Arrange
        /** @var User $user */
        $user = User::factory()->createOne();
        $assignedTo = User::factory()->createOne();

        $this->actingAs($user);

        //Act
        $request = $this->post(route('todo.store'), [
            'title' => 'Todo item',
            'description' => 'Todo item description',
            'assignedTo' => $assignedTo->id,
            'file' => UploadedFile::fake()->image('image1.png'),
        ]);

        //Assert
        //Check if file was uploaded
        Storage::disk('public')->assertExists('todo/image1.png');
    }
}
