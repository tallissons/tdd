<?php

namespace Tests\Feature\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    /** @test */
    public function delete_todo_item()
    {
        /** @var User $user */
        $user = User::factory()->createOne();
        $todo = Todo::factory()->createOne(['assigned_to_id' => $user->id]);

        $this->actingAs($user);

        $this->delete(route('todo.delete', $todo))
            ->assertRedirect(route('todo.index'));

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id
        ]);
    }

    /** @test */
    public function only_user_delete_todo_item()
    {
        /** @var User $user1 */
        $user1 = User::factory()->createOne();
        $todoUser1 = Todo::factory()->createOne(['assigned_to_id' => $user1->id]);

        /** @var User $user2 */
        $user2 = User::factory()->createOne();

        $this->actingAs($user2);
        $this->delete(route('todo.delete', $todoUser1))->assertForbidden();

        $this->assertDatabaseHas('todos', [
            'id' => $todoUser1->id
        ]);

        $this->actingAs($user1);
        $this->delete(route('todo.delete', $todoUser1))->assertRedirect(route('todo.index'));

        $this->assertDatabaseMissing('todos', [
            'id' => $todoUser1->id
        ]);
    }
}
