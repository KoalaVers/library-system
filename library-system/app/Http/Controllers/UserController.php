<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('role')
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'petugas'])],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'petugas'])],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan pengguna lain.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role yang dipilih tidak valid.',
        ]);

        // Password hanya diperbarui jika diisi.
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $result = DB::transaction(function () use ($validated, $user) {

            // Mengunci data Admin selama proses perubahan berlangsung.
            $admins = User::where('role', 'admin')
                ->lockForUpdate()
                ->get();

            $user->refresh();

            // Admin tidak boleh mengubah role akunnya sendiri.
            if (
                $user->getKey() == Auth::id() &&
                $validated['role'] !== $user->role
            ) {
                return 'self_role_change';
            }

            // Pastikan selalu tersedia minimal satu Admin.
            if (
                $user->role === 'admin' &&
                $validated['role'] !== 'admin' &&
                $admins->count() <= 1
            ) {
                return 'last_admin';
            }

            $user->update($validated);

            return 'success';
        });

        if ($result === 'self_role_change') {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'Anda tidak dapat mengubah role akun sendiri.',
                ]);
        }

        if ($result === 'last_admin') {
            return back()
                ->withInput()
                ->withErrors([
                    'role' => 'Minimal harus ada satu akun Admin.',
                ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Admin tidak boleh menghapus akunnya sendiri.
        if ($user->getKey() == Auth::id()) {
            return back()->with(
                'error',
                'Anda tidak dapat menghapus akun sendiri.'
            );
        }

        $deleted = DB::transaction(function () use ($user) {

            // Mengunci data Admin selama proses penghapusan.
            $admins = User::where('role', 'admin')
                ->lockForUpdate()
                ->get();

            $user->refresh();

            // Admin terakhir tidak boleh dihapus.
            if (
                $user->role === 'admin' &&
                $admins->count() <= 1
            ) {
                return false;
            }

            $user->delete();

            return true;
        });

        if (!$deleted) {
            return back()->with(
                'error',
                'Admin terakhir tidak dapat dihapus.'
            );
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
