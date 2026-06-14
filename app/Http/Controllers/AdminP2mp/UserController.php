<?php

namespace App\Http\Controllers\AdminP2mp;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $role = $request->input('role');
        $prodiId = $request->input('prodi_id');

        $users = User::query()
            ->with('prodi')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($prodiId, function ($query, $prodiId) {
                $query->where('prodi_id', $prodiId);
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $prodis = Prodi::orderBy('nama_prodi')->get();
        $roles = [
            'admin_p2mp' => 'Admin P2MP',
            'admin_prodi' => 'Admin Prodi',
            'kaprodi' => 'Kaprodi',
            'dosen' => 'Dosen',
        ];

        return view('adminp2mp.users.index', compact('users', 'prodis', 'roles', 'search', 'role', 'prodiId'));
    }

    public function create()
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $roles = [
            'admin_p2mp' => 'Admin P2MP',
            'admin_prodi' => 'Admin Prodi',
            'kaprodi' => 'Kaprodi',
            'dosen' => 'Dosen',
        ];

        return view('adminp2mp.users.create', compact('prodis', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:admin_p2mp,admin_prodi,kaprodi,dosen'],
            'prodi_id' => ['required_unless:role,admin_p2mp', 'nullable', 'exists:prodi,id'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'prodi_id.required_unless' => 'Program Studi wajib dipilih untuk role selain Admin P2MP.',
            'prodi_id.exists' => 'Program Studi tidak valid.',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'prodi_id' => $request->role === 'admin_p2mp' ? null : $request->prodi_id,
        ];

        User::create($data);

        return redirect()->route('adminp2mp.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $prodis = Prodi::orderBy('nama_prodi')->get();
        $roles = [
            'admin_p2mp' => 'Admin P2MP',
            'admin_prodi' => 'Admin Prodi',
            'kaprodi' => 'Kaprodi',
            'dosen' => 'Dosen',
        ];

        return view('adminp2mp.users.edit', compact('user', 'prodis', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'role' => ['required', 'in:admin_p2mp,admin_prodi,kaprodi,dosen'],
            'prodi_id' => ['required_unless:role,admin_p2mp', 'nullable', 'exists:prodi,id'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh user lain.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
            'role.required' => 'Role wajib dipilih.',
            'prodi_id.required_unless' => 'Program Studi wajib dipilih untuk role selain Admin P2MP.',
            'prodi_id.exists' => 'Program Studi tidak valid.',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->prodi_id = $request->role === 'admin_p2mp' ? null : $request->prodi_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('adminp2mp.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah menghapus akun sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('adminp2mp.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('adminp2mp.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
