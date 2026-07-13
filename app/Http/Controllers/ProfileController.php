<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|max:255',
            'nip' => 'nullable|max:50',
            'position' => 'nullable|max:100',
            'department' => 'nullable|max:100',
            'phone' => 'nullable|max:20',
            'address' => 'nullable|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'signature' => 'nullable|string',
        ]);

        // Hapus foto
            if ($request->delete_photo == "1") {

                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                $user->photo = null;
            }
            // Hapus Signature
            if ($request->delete_signature == "1") {

                if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                    Storage::disk('public')->delete($user->signature);
                }

                $user->signature = null;
            }

            // Upload foto baru
            if ($request->hasFile('photo')) {

                // Hapus foto lama
                if (!empty($user->photo) && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }

                // Simpan foto baru
                $path = $request->file('photo')->store('profiles', 'public');

                $user->photo = $path;
            }

        // Simpan Signature
        if ($request->filled('signature') && $request->delete_signature != "1") {
            if ($user->signature && Storage::disk('public')->exists($user->signature)) {
                Storage::disk('public')->delete($user->signature);
            }
            $image = $request->signature;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $fileName = 'signatures/' . uniqid() . '.png';
            Storage::disk('public')->put(
                $fileName,
                base64_decode($image)
            );
            $user->signature = $fileName;
        }
        $user->name = $request->name;
        $user->nip = $request->nip;
        $user->position = $request->position;
        $user->department = $request->department;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->save();
        Auth::setUser($user);
        Auth::setUser($user->fresh());
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        }
}
