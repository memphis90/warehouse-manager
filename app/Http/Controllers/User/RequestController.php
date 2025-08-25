<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Facades\RequestFacade as RequestFacade;
use App\Models\Item;
use App\Models\RequestType;
use App\Models\RequestStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('User/Requests/Index', [
            'requests' =>RequestFacade::where('user_id', auth()->id())
                ->with(['status', 'requestType', 'requestItems.item'])
                ->latest()
                ->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('User/Requests/Create', [
            'items' => Item::with('category')->where('status', 'available')->get(),
            'request_types' => RequestType::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $newRequest)
    {
        $validated = $newRequest->validate([        
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',    
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.id' => 'nullable|integer|exists:items,id', // ora opzionale per nuovi items
            'items.*.needed_from' => 'required|date|after_or_equal:today',
            'items.*.needed_to' => 'required|date|after_or_equal:items.*.needed_from',

            'items.*.name' => 'nullable|string|max:255', // per nuovi items
            'items.*.description' => 'nullable|string',
            'items.*.category_id' => 'nullable|integer|exists:categories,id',
        ]);

        DB::beginTransaction();
        try {
            // Determina il tipo di richiesta basato sul contenuto
            $hasNewItems = collect($validated['items'])->some(fn($item) => empty($item['id']));
            $requestTypeId = $hasNewItems ? 
                RequestType::where('name', 'new_item')->first()?->id ?? 2 : 
                RequestType::where('name', 'existing_item')->first()?->id ?? 1;

            $request = RequestFacade::create([
                'user_id' => auth()->id(),
                'request_type_id' => $requestTypeId,
                'status_id' => RequestStatus::where('name', 'pending')->first()->id,
                'notes' => $validated['notes'],
                'requested_at' => now(),
            ]);

       foreach ($validated['items'] as $itemData) {

            if (!empty($itemData['id'])) {
                // Item esistente - recupera il nome dal database
                $existingItem = \App\Models\Item::find($itemData['id']);
                $item_id = $existingItem->id;
                $name = $existingItem->name;

            } else {
            // Nuovo item da creare
                $item_id = null;
                $name = $itemData['name']; 
            }

           

               $request->requestItems()->create([
                    'item_id' => $item_id,
                    'request_id' => $request->id,
                    'name' => $name,
                    'quantity' => $itemData['quantity'],
                    'needed_from' => $itemData['needed_from'],
                    'needed_to' => $itemData['needed_to'],
                ]);
        }
            
            DB::commit();

            return redirect()->route('user.requests.index')
                ->with('success', 'Request created successfully');

        } catch (\Exception $e) {
            DB::rollback();
            
            // Log dell'errore per debug
            \Log::error('Error creating request: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'validated_data' => $validated,
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Failed to create request. Please try again.'])
                         ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(\App\Models\Request $request)
    {
        // Verifica che la richiesta appartenga all'utente loggato
        if ($request->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('User/Requests/Show', [
            'request' => $request->load(['status', 'requestType', 'requestItems.item', 'admin'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}