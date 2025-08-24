<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Item;
use App\Models\Category;

class CreateItemCommand extends Command
{
    protected $signature = 'items:create 
                          {--name= : The name of the item}
                          {--description= : The description of the item}
                          {--quantity= : The quantity of the item}
                          {--category= : The category ID or name}';
                         

    protected $description = 'Create a new item interactively or with parameters';

    public function handle()
    {
        $this->info('Creating a new item...');
        $this->newLine();
        return $this->runInteractive();
    }


    private function runInteractive()
    {
        // Nome dell'item
        $name = $this->option('name') ?: $this->ask('Item name');
        
        if (empty($name)) {
            $this->error('Name is required!');
            return 1;
        }

        // Controlla se esiste già
        if (Item::where('name', $name)->exists()) {
            if (!$this->confirm("An item with name '{$name}' already exists. Continue anyway?")) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        // Descrizione
        $description = $this->option('description') ?: 
                      $this->ask('Description (optional)', '');

        // Quantità
        $quantity = $this->option('quantity');
        if (!$quantity) {
            do {
                $quantity = $this->ask('Quantity');
                if (!is_numeric($quantity) || $quantity < 0) {
                    $this->error('Please enter a valid positive number');
                    $quantity = null;
                }
            } while (!$quantity);
        }

        // Categoria
        $category = $this->handleCategorySelection();
        if (!$category) {
            return 1;
        }

        // Conferma finale
        $this->showSummary($name, $description, $quantity, $category);
        
        if (!$this->confirm('Create this item?', true)) {
            $this->info('Operation cancelled.');
            return 0;
        }

        return $this->createItem($name, $description, $quantity, $category->id);
    }

   
    private function handleCategorySelection()
    {
        $categoryInput = $this->option('category');
        
        if ($categoryInput) {
            // Prova prima con ID numerico
            if (is_numeric($categoryInput)) {
                $category = Category::find($categoryInput);
                if ($category) {
                    return $category;
                }
            }
            
            // Poi prova con il nome
            $category = Category::where('name', 'like', "%{$categoryInput}%")->first();
            if ($category) {
                return $category;
            }
            
            $this->error("Category '{$categoryInput}' not found");
        }

        // Mostra le categorie disponibili
        $categories = Category::orderBy('name')->get();
        
        if ($categories->isEmpty()) {
            $this->error('No categories found. Please create categories first.');
            return null;
        }

        $this->info('Available categories:');
        $categoryChoices = [];
        
        foreach ($categories as $index => $category) {
            $categoryChoices[$index] = $category->name;
            $this->line("  [{$index}] {$category->name}");
        }

        // Opzione per creare nuova categoria
        $categoryChoices['new'] = 'Create new category';
        $this->line("  [new] Create new category");

        $choice = $this->ask('Select category (number or "new")');

        if ($choice === 'new') {
            return $this->createNewCategory();
        }

        if (!isset($categoryChoices[$choice])) {
            $this->error('Invalid selection');
            return $this->handleCategorySelection();
        }

        return $categories[$choice];
    }

    private function createNewCategory()
    {
        $name = $this->ask('New category name');
        $description = $this->ask('Category description (optional)', '');

        if (Category::where('name', $name)->exists()) {
            $this->error("Category '{$name}' already exists");
            return $this->handleCategorySelection();
        }

        $category = Category::create([
            'name' => $name,
            'description' => $description,
        ]);

        $this->info("Category '{$name}' created successfully!");
        return $category;
    }

    private function showSummary($name, $description, $quantity, $category)
    {
        $this->newLine();
        $this->info('Item Summary:');
        $this->line("   Name: {$name}");
        $this->line("   Description: " . ($description ?: 'N/A'));
        $this->line("   Quantity: {$quantity}");
        $this->line("   Category: {$category->name}");
        $this->newLine();
    }

    private function createItem($name, $description, $quantity, $categoryId)
    {
        try {
            $item = Item::create([
                'name' => $name,
                'description' => $description,
                'quantity' => $quantity,
                'category_id' => $categoryId,
            ]);

            $this->info("Item '{$name}' created successfully!");
            $this->line("   ID: {$item->id}");
            $this->line("   Total items in inventory: " . Item::count());
            
            return 0;

        } catch (\Exception $e) {
            $this->error('Failed to create item: ' . $e->getMessage());
            return 1;
        }
    }
}