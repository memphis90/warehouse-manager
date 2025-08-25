<?php

//todo da sistemare

it('can create admin user with correct permissions', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        expect($admin->hasRole('admin'))->toBeTrue()
            ->and($admin->can('manage.items'))->toBeTrue()
            ->and($admin->can('approve.requests'))->toBeTrue()
            ->and($admin->isAdmin())->toBeTrue();
    });
    
    it('user has limited permissions', function () {
        $user = User::factory()->create();
        $user->assignRole('user');
        
        expect($user->can('create.requests'))->toBeTrue()
            ->and($user->can('manage.items'))->toBeFalse()
            ->and($user->can('approve.requests'))->toBeFalse();
    });
    
    it('roles exist in database', function () {
        expect(\Spatie\Permission\Models\Role::where('name', 'user')->exists())->toBeTrue()
            ->and(\Spatie\Permission\Models\Role::where('name', 'admin')->exists())->toBeTrue();
        
        $userRole = \Spatie\Permission\Models\Role::findByName('user');
        $adminRole = \Spatie\Permission\Models\Role::findByName('admin');
        
        expect($userRole->permissions->count())->toBeGreaterThan(0)
            ->and($adminRole->permissions->count())->toBeGreaterThan(0);
    });

    it('user can only see own requests, admin sees all', function () {
        $admin = User::factory()->create();
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        
        $admin->assignRole('admin');
        $user1->assignRole('user');
        $user2->assignRole('user');
        
        // Crea richieste per utenti diversi
        Request::factory(2)->create(['user_id' => $user1->id]);
        Request::factory()->create(['user_id' => $user2->id]);
        
        // User1 vede solo le proprie (2)
        expect($user1->requests)->toHaveCount(2);
        
        // User2 vede solo la propria (1)
        expect($user2->requests)->toHaveCount(1);
        
        // Totale richieste nel sistema (3)
        expect(Request::count())->toBe(3);
        
        // Verifica permessi admin
        expect($admin->can('view.all.requests'))->toBeTrue();
    });