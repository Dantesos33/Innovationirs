<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminUsersController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::latest();

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                  => 'required|string|max:150',
            'email'                 => 'required|email|unique:admins,email',
            'role'                  => 'required|in:super_admin,admin,staff',
            'password'              => ['required', Password::min(8)->letters()->numbers()],
            'password_confirmation' => 'required|same:password',
            'is_active'             => 'boolean',
        ]);

        Admin::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'role'      => $data['role'],
            'password'  => Hash::make($data['password']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Admin user created successfully.');
    }

    public function edit(Admin $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, Admin $user)
    {
        $rules = [
            'name'      => 'required|string|max:150',
            'email'     => 'required|email|unique:admins,email,' . $user->id,
            'role'      => 'required|in:super_admin,admin,staff',
            'is_active' => 'boolean',
        ];

        if ($request->filled('password')) {
            $rules['password']              = ['required', Password::min(8)->letters()->numbers()];
            $rules['password_confirmation'] = 'required|same:password';
        }

        $data = $request->validate($rules);

        $updateData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'role'  => $data['role'],
        ];

        // Prevent self-deactivation
        if ($user->id !== Auth::guard('admin')->id()) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(Admin $user)
    {
        if ($user->id === Auth::guard('admin')->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Admin user deleted.');
    }

    public function toggle(Admin $user)
    {
        if ($user->id === Auth::guard('admin')->id()) {
            return response()->json(['error' => 'Cannot deactivate your own account.'], 422);
        }

        $user->update(['is_active' => ! $user->is_active]);

        return response()->json(['success' => true, 'is_active' => $user->is_active]);
    }
}
