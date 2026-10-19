<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWaitlistEntryRequest;
use App\Models\WaitlistEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WaitlistController extends Controller
{
    /**
     * Show the waitlist page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Waitlist', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Add someone to the waitlist.
     */
    public function store(StoreWaitlistEntryRequest $request): RedirectResponse
    {
        WaitlistEntry::create($request->validated());

        return to_route('waitlist.create')->with(
            'status',
            __('You are on the waitlist. We will be in touch.'),
        );
    }
}
