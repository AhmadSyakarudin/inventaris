<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role');

        $users = User::when($role && in_array($role, ['admin', 'staff']), function ($query) use ($role) {
            $query->where('role', $role);
        })->get();

        return view('users.index', compact('users', 'role'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat!');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,staff',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->role = $request->role;
        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diupdate!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    // Operator: Manage Staff Users
    public function staffIndex()
    {
        $users = User::where('role', 'staff')->get();
        return view('staff-users.index', compact('users'));
    }

    public function staffCreate()
    {
        return view('staff-users.create', ['user' => new User()]);
    }

    public function staffStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'staff',
        ]);

        return redirect()->route('staff-users.index')->with('success', 'Staff user berhasil dibuat!');
    }

    public function staffEdit(User $user)
    {
        abort_if($user->role !== 'staff', 403);
        return view('staff-users.edit', compact('user'));
    }

    public function staffUpdate(Request $request, User $user)
    {
        abort_if($user->role !== 'staff', 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        
        $user->save();

        return redirect()->route('staff-users.index')->with('success', 'Staff user berhasil diupdate!');
    }

    public function staffDestroy(User $user)
    {
        abort_if($user->role !== 'staff', 403);
        $user->delete();

        return redirect()->route('staff-users.index')->with('success', 'Staff user berhasil dihapus!');
    }
}