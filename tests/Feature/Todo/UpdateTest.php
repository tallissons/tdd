<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function update_todo_item()
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne();

        $this->actingAs($user);

        $this->put(route('todo.update', $todo), [
            'title' => 'Updated Todo',
            'description' => 'Updated Todo description',
            'assignedTo' => $user->id
        ])->assertRedirect(route('todo.index'));

        $todo->refresh();

        $this->assertEquals('Updated Todo', $todo->title);
        $this->assertEquals('Updated Todo description', $todo->description);
    }
}
