<?php

it('login page works', function () {
    $response = $this->get('/login');
    $response->assertOk();
});

it('register page works', function () {
    $response = $this->get('/register');
    $response->assertOk();
});

it('user can login with correct credentials', function () {
    // Crea un utente fittizio
    $user = \App\Models\User::factory()->create([
        'email' => 'test@test.com',
        'password' => bcrypt('password123'),
    ]);

    // Prova a fare il login con le credenziali corrette
    $response = $this->post('/login', [
        'email' => 'test@test.com',
        'password' => 'password123',
    ]);

    // Verifica che l'utente sia autenticato
    $this->assertAuthenticatedAs($user);
});

it('user cannot login with incorrect credentials', function () {
    // Prova a fare il login con credenziali errate
    $response = $this->post('/login', [
          'email' => 'test@test.com',
        'password' => bcrypt('password123')
         ]);

          $response = $this->post('/login', [
        'email' => 'test@test.com',
        'password' => 'wrong-password'
    ]);

    // Verifica che l'utente non sia autenticato
    $response->assertSessionHasErrors();
    $this->assertGuest();
});

