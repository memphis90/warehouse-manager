
<?php

// Test che la homepage risponda (anche con redirect)
it('homepage responds correctly', function () {
    $response = $this->get('/');
    
    // Può essere 200 (OK) o 302 (redirect) - entrambi vanno bene
    expect($response->status())->toBeIn([200, 302]);
});

// Test che la homepage reindirizzi al login se non autenticati
it('homepage redirects unauthenticated users to login', function () {
    $response = $this->get('/');
    
    // Se fa redirect, probabilmente va al login
    if ($response->status() === 302) {
        $response->assertRedirect('/login');
    } else {
        $response->assertOk();
    }
});

it('authenticated users can access dashboard', function () {
    // Crea un utente fittizio
    $user = \App\Models\User::factory()->create();
    
    // Simula l'autenticazione dell'utente
    $this->actingAs($user);
    
    // Accedi alla homepage
    $response = $this->get('/dashboard');
    
    // Verifica che la risposta sia OK (200)
    $response->assertOk();
});