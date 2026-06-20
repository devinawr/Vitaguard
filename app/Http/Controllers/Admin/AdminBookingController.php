<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $bookings = Booking::with(['member.user', 'doctor.user', 'schedule'])
            ->when($request->filled('search'), function ($query) {
                $query->whereHas('member.user', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                })->orWhereHas('doctor.user', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
            })
            ->when($request->filled('status'), function ($query) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $booking->load(['member.user', 'doctor.user', 'schedule', 'consultation']);
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Quickly confirm a pending booking.
     */
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.show', $booking)
                ->with('error', 'Booking ini tidak dalam status pending.');
        }

        $booking->update(['status' => 'confirmed']);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking berhasil dikonfirmasi.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $booking->update($validated);

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Status booking berhasil diperbarui.');
    }
}
