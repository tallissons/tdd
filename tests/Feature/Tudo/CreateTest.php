<?php

namespace Tests\Feature\Tudo;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
}
