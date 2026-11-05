<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends AdminBaseController
{
    public function index(Request $request)
    {
        $userQuery = User::withCount('orders')->withSum('orders', 'total_amount')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $userQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('role', 'like', "%{$q}%");
            });
        }

        $users = $userQuery->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:customer,admin',
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => strtolower($validated['email']),
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna \"{$validated['name']}\" ({$validated['role']}) berhasil ditambahkan.");
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'         => 'required|in:customer,admin',
            'new_password' => 'nullable|string|min:6',
        ]);

        if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat mencabut hak akses Administrator dari akun Anda sendiri demi keamanan sistem.');
        }

        $data = [
            'name'  => $validated['name'],
            'email' => strtolower($validated['email']),
            'role'  => $validated['role'],
        ];

        if (!empty($validated['new_password'])) {
            $data['password'] = Hash::make($validated['new_password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna \"{$user->name}\" berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Administrator yang sedang Anda gunakan.');
        }

        if ($user->orders()->exists()) {
            return redirect()->route('admin.users.index')
                ->with('error', "Pengguna \"{$user->name}\" memiliki riwayat pesanan pelanggan. Demi integritas audit keuangan, pengguna ini tidak dapat dihapus.");
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Akun pengguna \"{$name}\" berhasil dihapus.");
    }
}
