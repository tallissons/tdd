<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function invite_user()
    {

        /** Arrang */
        Mail::fake();

        //- Preciso de um usuario
        /** @var User $user */
        $user = User::factory()->create();

        //- Preciso estar logado
        $this->actingAs($user);



        /** Act */
        $this->post('invite', ['email' => 'novo@email.com']);



        /**Assert */
        //Email foi enviado, para pessoa certa
        Mail::assertSent(Invitation::class, function($email){
            return $email->hasTo('novo@email.com');
        });

        //Criou um convite no banco de dados
        $this->assertDatabaseHas('invites', ['email' => 'novo@email.com']);
    }
}
