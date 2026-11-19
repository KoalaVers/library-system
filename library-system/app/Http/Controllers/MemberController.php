<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MemberController extends Controller
{
    public function index()
    {
        Gate::authorize('manage-members');

        $members = Member::latest()->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        Gate::authorize('manage-members');

        return view('members.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-members');

        $validated = $request->validate([
            'member_code' => 'required|string|max:255|unique:members,member_code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        Member::create($validated);

        return redirect()
            ->route('members.index')
            ->with('success', 'Data anggota berhasil ditambahkan!');
    }

    public function show(Member $member)
    {
        Gate::authorize('manage-members');

        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        Gate::authorize('manage-members');

        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        Gate::authorize('manage-members');

        $validated = $request->validate([
            'member_code' => 'required|string|max:255|unique:members,member_code,' . $member->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:members,email,' . $member->id,
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
        ]);

        $member->update($validated);

        return redirect()
            ->route('members.index')
            ->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function destroy(Member $member)
    {
        Gate::authorize('manage-members');

        // Lindungi anggota yang memiliki riwayat peminjaman.
        if ($member->borrowings()->exists()) {
            return redirect()
                ->route('members.index')
                ->with(
                    'error',
                    'Anggota tidak dapat dihapus karena memiliki riwayat peminjaman.'
                );
        }

        $member->delete();

        return redirect()
            ->route('members.index')
            ->with('success', 'Data anggota berhasil dihapus!');
    }
}
