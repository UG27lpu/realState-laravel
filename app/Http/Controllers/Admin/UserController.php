<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $role = $request->input('role');
        $q    = trim((string) $request->input('q', ''));

        $users = User::query()
            ->when($role, fn ($qb) => $qb->role($role))
            ->when($q !== '', fn ($qb) => $qb->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            }))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'role'  => $role,
            'q'     => $q,
            'roles' => array_combine(
                array_map(fn ($r) => $r->value, Role::cases()),
                array_map(fn ($r) => $r->label(), Role::cases()),
            ),
        ]);
    }

    public function toggleActive(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('status', $user->is_active ? 'Account reactivated.' : 'Account deactivated.');
    }
}
