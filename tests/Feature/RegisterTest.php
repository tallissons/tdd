<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function register_new_user()
    {
        //Arrang

        //Act
        $return = $this->post(route('register'), [
            'name' => 'User Teste',
            'email' => 'user@email.com',
            'email_confirmation' => 'user@email.com',
            'password' => 'uma senha Qualquer'
        ]);

        //Assert
        $return->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'User Teste',
            'email' => 'user@email.com'
        ]);

        $user = User::whereEmail('user@email.com')->firstOrFail();

        $this->assertTrue(
            Hash::check('uma senha Qualquer', $user->password),
            'Checking if password was saved an it is encrypted.'
        );

        $this->assertAuthenticatedAs($user);

    }

    /** @test */
    public function name_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'name' => __('validation.required', ['attribute' => 'name']),
            ]);
    }

    /** @test */
    public function name_max_255_characters()
    {
        $this->post(route('register'), [
            'name' => str_repeat('a', 256)
        ])
            ->assertSessionHasErrors([
                'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            ]);
    }

    /** @test */
    public function email_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'email' => __('validation.required', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_valid()
    {
        $this->post(route('register'), [
            'email' => 'invalid-email'
        ])
            ->assertSessionHasErrors([
                'email' => __('validation.email', ['attribute' => 'email']),
            ]);
    }

    /** @test */
    public function email_unique()
    {
        //Arrang
        User::factory()->create(['email' => 'some@email.com']);


        //Act
        $return = $this->post(route('register'), [
            'email' => 'some@email.com'
        ]);


        //Assert
        $return->assertSessionHasErrors([
            'email' => __('validation.unique', ['attribute' => 'email']),
        ]);
    }

    /** @test */
    public function email_confirmed()
    {
        //Arrang


        //Act
        $this->post(route('register'), [
            'email' => 'some@email.com',
            'email_confirmation' => ''

        ])->assertSessionHasErrors([//Assert
            'email' => __('validation.confirmed', ['attribute' => 'email']),
        ]);
    }

    /** @test */
    public function password_required()
    {
        $this->post(route('register'), [])
            ->assertSessionHasErrors([
                'password' => __('validation.required', ['attribute' => 'password']),
            ]);
    }

    /** @test */
    public function password_should_have_at_least_1_uppercase()
    {
        $this->post(route('register'), [
            'password' => 'password-without-uppercase'
        ])
            ->assertSessionHasErrors([
                'password' => 'The password must contain at least one uppercase and one lowercase letter.',
            ]);
    }

}
