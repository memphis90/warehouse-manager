<?php

//todo da sistemare

 it('user can create request for existing item', function () {
        $user = User::factory()->create();
        $user->assignRole('user');
        $item = Item::factory()->create();
        
        $request = Request::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'type' => 'existing_item',
            'item_name' => $item->name,
            'reason' => 'Need for client presentation',
            'start_date' => '2024-12-01',
            'end_date' => '2024-12-10',
            'status' => 'pending'
        ]);
        
        expect($request->user_id)->toBe($user->id)
            ->and($request->item_id)->toBe($item->id)
            ->and($request->type)->toBe('existing_item')
            ->and($request->status)->toBe('pending');
            
        // Verifica relazioni
        expect($request->user->id)->toBe($user->id)
            ->and($request->item->id)->toBe($item->id);
    });
    
    it('user can create purchase request', function () {
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $request = Request::factory()->forPurchase()->create([
            'user_id' => $user->id,
            'item_name' => 'New MacBook Pro M4',
            'category' => 'electronics',
            'reason' => 'Need latest model for development',
            'type' => 'purchase_request'
        ]);
        
        expect($request->type)->toBe('purchase_request')
            ->and($request->item_id)->toBeNull()
            ->and($request->item_name)->toBe('New MacBook Pro M4')
            ->and($request->start_date)->toBeNull()
            ->and($request->end_date)->toBeNull();
    });
    
    it('admin can approve request', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $user = User::factory()->create();
        $user->assignRole('user');
        
        $request = Request::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);
        
        // Admin approva
        $request->update([
            'status' => 'approved',
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'admin_notes' => 'Approved for business use'
        ]);
        
        $request->refresh();
        
        expect($request->status)->toBe('approved')
            ->and($request->approved_by)->toBe($admin->id)
            ->and($request->approved_at)->not()->toBeNull()
            ->and($request->admin_notes)->toBe('Approved for business use');
            
        // Verifica relazione
        expect($request->approvedBy->id)->toBe($admin->id);
    });
    
    it('admin can reject request with notes', function () {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $request = Request::factory()->create(['status' => 'pending']);
        
        $request->update([
            'status' => 'rejected',
            'approved_by' => $admin->id,
            'approved_at' => now(),
            'admin_notes' => 'Item not available for requested period'
        ]);
        
        expect($request->fresh()->status)->toBe('rejected')
            ->and($request->admin_notes)->toContain('not available');
    });