<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Article;
use App\Models\Booking;
use App\Models\Consultation;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_member'  => User::where('role', 'member')->count(),
            'total_dokter'  => Doctor::count(),
            'total_artikel' => Article::count(),
            'total_booking' => Booking::count(),

            'consultations_active' => Consultation::where('status', 'active')->count(),
            'consultations_closed' => Consultation::where('status', 'closed')->count(),

            'recent_bookings' => Booking::with(['member.user', 'doctor.user'])
                ->latest()->take(5)->get(),
            'recent_articles' => Article::with('author')
                ->latest()->take(5)->get(),

            'active_consultations' => Consultation::with([
                'booking.member.user',
                'booking.doctor.user'
            ])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get(),
        ];
        
        return view('admin.dashboard', compact('data'));
    }
}