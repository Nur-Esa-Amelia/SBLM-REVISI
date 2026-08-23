<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        if ($user->role === 'admin_p2mp') {
            $layout = 'adminp2mp.layouts.app';
        } elseif ($user->role === 'admin_prodi' || $user->role === 'kaprodi') {
            $layout = 'adminprodi.layouts.app';
        } else {
            $layout = 'dosen.layouts.app';
        }

        return view('profile', [
            'layout' => $layout,
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
