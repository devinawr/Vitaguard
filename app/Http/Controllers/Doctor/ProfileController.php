<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403, 'Profil dokter tidak ditemukan.');
        }

        return view('doctor.profile', compact('doctor'));
    }

    public function update(Request $request)
    {
        $doctor = auth()->user()->doctor;

        if (!$doctor) {
            abort(403);
        }

        $request->validate([
            'name'             => ['required', 'string', 'max:255'],
            'phone'            => ['nullable', 'string', 'max:20'],
            'bio'              => ['nullable', 'string', 'max:2000'],
            'experience_years' => ['nullable', 'integer', 'min:0', 'max:60'],
            'date_of_birth'    => ['nullable', 'date', 'before:today'],
            'gender'           => ['nullable', 'in:Man,Woman'],
            'photo'            => ['nullable', 'image', 'max:2048'],
        ]);

        auth()->user()->update(['name' => $request->name]);

        $data = $request->only(['phone', 'bio', 'experience_years', 'date_of_birth', 'gender']);

        if ($request->hasFile('photo')) {
            if ($doctor->photo) {
                Storage::disk('public')->delete($doctor->photo);
            }
            $data['photo'] = $request->file('photo')->store('doctors', 'public');
        }

        $doctor->update($data);

        return redirect()->route('doctor.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
