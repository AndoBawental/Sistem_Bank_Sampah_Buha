<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    /**
     * Menampilkan daftar role
     */
    public function index()
    {
        // Cek manual tanpa policy
        if (!Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }
        
        $roles = Role::with(['permissions', 'users'])->get();
        
        return view('dashboard.admin.roles.index', compact('roles'));
    }
}