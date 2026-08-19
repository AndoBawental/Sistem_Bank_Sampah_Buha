<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * Menampilkan daftar user
     */
    public function index()
    {
        $this->authorize('viewAny', User::class);
        
        $users = User::with('roles')->latest()->paginate(10);
        return view('pages.admin.users.index', compact('users'));
    }

    /**
     * Form tambah user
     */
    public function create()
    {
        $this->authorize('create', User::class);
        
        $roles = Role::all();
        // PERBAIKAN: View yang benar untuk create user
        return view('pages.admin.users.create', compact('roles'));
    }

    /**
     * Simpan user baru
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Menampilkan detail user (optional)
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        
        return view('pages.admin.users.show', compact('user'));
    }

    /**
     * Form edit user
     */
    public function edit(User $user)
    {
        $this->authorize('update', $user);
        
        $roles = Role::all();
        $userRole = $user->roles->first()?->name;
        
        // PERBAIKAN: View yang benar untuk edit user dan kirim data user
        return view('pages.admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('update', $user);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
            'password' => 'nullable|min:6|confirmed'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        
        // Cegah hapus diri sendiri
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Reset password user
     */
    public function resetPassword(User $user)
    {
        $this->authorize('update', $user);
        
        $newPassword = 'password123';
        $user->update([
            'password' => Hash::make($newPassword)
        ]);

        return back()->with('success', "Password untuk {$user->name} direset ke: {$newPassword}");
    }
}