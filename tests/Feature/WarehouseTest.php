<?php
//todo da sistemare

use App\Models\{User, Item, Request};

  it('user can register and login', function () {
        // Registrazione
        $userData = [
            'name' => 'Test Lara',
            'email' => 'test@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];
        
        $registerResponse = $this->post('/register', $userData);
        $registerResponse->assertRedirect('/dashboard');
        
        // Logout per testare login
        $this->post('/logout');
        
        // Login
        $loginResponse = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'password123'
        ]);
        
        $loginResponse->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    });

    it('user cannot login with wrong password', function () {
        $user = User::factory()->create([
            'email' => 'test@test.com',
            'password' => bcrypt('correct-password')
        ]);
        
        $response = $this->post('/login', [
            'email' => 'test@test.com',
            'password' => 'wrong-password'
        ]);
        
        $response->assertSessionHasErrors();
        $this->assertGuest();
    });
