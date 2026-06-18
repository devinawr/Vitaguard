<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $members = Member::query()
            ->with('user')
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->whereHas('user', function ($userQuery) use ($request) {
                    $userQuery->where('name', 'like', "%{$request->search}%")
                        ->orWhere('email', 'like', "%{$request->search}%");
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 10));

        return response()->json($members);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'member')
                    ->whereNull('deleted_at')),
                'unique:members,user_id',
            ],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['Woman', 'Man'])],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string'],
            'blood_type' => ['nullable', Rule::in(['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'weight' => ['nullable', 'integer', 'min:1', 'max:500'],
            'height' => ['nullable', 'integer', 'min:30', 'max:300'],
        ]);

        $member = Member::create($validated);

        return response()->json([
            'message' => 'Data member berhasil dibuat.',
            'data' => $member->load('user'),
        ], 201);
    }

    public function show(Member $member): JsonResponse
    {
        return response()->json([
            'data' => $member->load(['user', 'bookings.doctor.user', 'bookings.consultation']),
        ]);
    }

    public function update(Request $request, Member $member): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => [
                'sometimes',
                'required',
                Rule::exists('users', 'id')->where(fn ($query) => $query
                    ->where('role', 'member')
                    ->whereNull('deleted_at')),
                Rule::unique('members', 'user_id')->ignore($member->id),
            ],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', Rule::in(['Woman', 'Man'])],
            'phone' => ['nullable', 'string', 'max:15'],
            'address' => ['nullable', 'string'],
            'blood_type' => ['nullable', Rule::in(['A', 'B', 'AB', 'O', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])],
            'weight' => ['nullable', 'integer', 'min:1', 'max:500'],
            'height' => ['nullable', 'integer', 'min:30', 'max:300'],
        ]);

        $member->update($validated);

        return response()->json([
            'message' => 'Data member berhasil diperbarui.',
            'data' => $member->fresh()->load('user'),
        ]);
    }

    public function destroy(Member $member): JsonResponse
    {
        $member->delete();

        return response()->json([
            'message' => 'Data member berhasil dihapus secara soft delete.',
        ]);
    }
}
