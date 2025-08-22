<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\Item;
use App\Models\RequestType;
use App\Models\RequestStatus;
use Illuminate\Http\Request as HttpRequest;
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
            'requests' => auth()->user()
                ->requests()
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
            'request_types' => RequestType::all()
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(HttpRequest $httpRequest)
    {
        $validated = $httpRequest->validate([
            'request_type_id' => 'required|exists:request_types,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.needed_from' => 'required|date|after_or_equal:today',
            'items.*.needed_until' => 'required|date|after:items.*.needed_from',
            'items.*.notes' => 'nullable|string',
        ]);

       DB::beginTransaction();
        try {
            $request = Request::create([
                'user_id' => auth()->id(),
                'request_type_id' => $validated['request_type_id'],
                'status_id' => RequestStatus::where('name', 'pending')->first()->id,
                'notes' => $validated['notes'],
                'requested_at' => now(),
            ]);

            foreach ($validated['items'] as $item) {
                $request->requestItems()->create($item);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        return redirect()->route('user.requests.index')->with('success', 'Request created successfully');
    }

    /**
     * Display the specified resource.
     */
   public function show(Request $request)
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
