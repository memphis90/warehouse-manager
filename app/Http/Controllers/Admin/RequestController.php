<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Request;
use App\Models\Item;
use App\Models\RequestStatus;
use Illuminate\Http\Request as HttpRequest;
use Inertia\Inertia;
use App\Models\RequestType;

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
                ->paginate(10),
            'requestStatuses' => RequestStatus::all(),
            'requestTypes' => RequestType::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show(Request $request)
        {
            return Inertia::render('Admin/Requests/Show', [
                'request' => $request->load(['user', 'status', 'requestType', 'requestItems.item', 'admin'])
            ]);
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
