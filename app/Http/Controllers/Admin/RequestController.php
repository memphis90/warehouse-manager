<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\RequestStatus;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
     public function index()
    {
        return Inertia::render('Admin/Requests/Index', [
            'requests' => Request::with(['user', 'status', 'requestType', 'requestItems.item'])
                ->latest()
                ->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
        {
            return Inertia::render('Admin/Requests/Show', [
                'request' => $request->load(['user', 'status', 'requestType', 'requestItems.item', 'admin'])
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
    public function update(HttpRequest $httpRequest, Request $request)
        {
            $validated = $httpRequest->validate([
                'status_id' => 'required|exists:request_statuses,id',
                'notes' => 'nullable|string',
            ]);

            $validated['admin_id'] = auth()->id();
            if ($validated['status_id'] != $request->status_id) {
                $validated['approved_at'] = now();
            }

            $request->update($validated);

            return redirect()->route('admin.requests.index')->with('success', 'Request updated successfully');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
