<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class AdminConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $consultations = Consultation::with(['booking.member.user', 'booking.doctor.user', 'messages'])
            ->when($request->filled('search'), function ($query) {
                $query->whereHas('booking.member.user', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                })->orWhereHas('booking.doctor.user', function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%');
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10);

        return view('admin.consultations.index', compact('consultations'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Consultation $consultation)
    {
        $consultation->load([
            'booking.member.user',
            'booking.doctor.user',
            'messages.sender'
        ]);
        return view('admin.consultations.show', compact('consultation'));
    }
}
