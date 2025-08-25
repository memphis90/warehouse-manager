<?php

//todo da sistemare

 it('can create item with all fields', function () {
        $item = Item::factory()->create([
            'name' => 'MacBook Pro M3',
            'category_id' => '5',
            'description' => 'Latest Apple laptop',
            'status' => 'available',
            'quantity' => 2
        ]);
        
        expect($item->name)->toBe('MacBook Pro M3')
            ->and($item->category_id)->toBe('5')
            ->and($item->status)->toBe('available')
            ->and($item->quantity)->toBe(2);
            
        // Verifica nel database
        $this->assertDatabaseHas('items', [
            'name' => 'MacBook Pro M3',
            'category_id' => '5'
        ]);
    });
    
    it('item is available by default', function () {
        $item = Item::factory()->create();
        expect($item->available)->toBeTrue();
    });
    
    it('can create unavailable item', function () {
        $item = Item::factory()->unavailable()->create();
        expect($item->available)->toBeFalse();
    });
    
    it('item availability for period works correctly', function () {
        $item = Item::factory()->create();
        
        // Senza richieste = sempre disponibile
        expect($item->isAvailableForPeriod('2024-12-01', '2024-12-10'))->toBeTrue();
        
        // Con richiesta approvata
        Request::factory()->approved()->create([
            'item_id' => $item->id,
            'start_date' => '2024-12-01',
            'end_date' => '2024-12-10'
        ]);
        
        // Periodo sovrapposto = non disponibile
        expect($item->isAvailableForPeriod('2024-12-05', '2024-12-15'))->toBeFalse();
        
        // Periodo libero = disponibile
        expect($item->isAvailableForPeriod('2024-12-15', '2024-12-25'))->toBeTrue();
    });