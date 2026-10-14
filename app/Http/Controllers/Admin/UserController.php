<?php

namespace App\Http\Controllers\Admin;

use App\Classroom;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('classroom');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users      = $query->latest()->paginate(30);
        $classrooms = Classroom::active()->get();
        return view('admin.users.index', compact('users', 'classrooms'));
    }

    public function create()
    {
        $classrooms = Classroom::active()->get();
        return view('admin.users.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6|confirmed',
            'role'         => 'required|in:superadmin,boss,teacher,student',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'สร้างผู้ใช้สำเร็จ');
    }

    public function edit(User $user)
    {
        $classrooms = Classroom::active()->get();
        return view('admin.users.edit', compact('user', 'classrooms'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'password'     => 'nullable|string|min:6|confirmed',
            'role'         => 'required|in:superadmin,boss,teacher,student',
            'classroom_id' => 'nullable|exists:classrooms,id',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'แก้ไขผู้ใช้สำเร็จ');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'ลบผู้ใช้สำเร็จ');
    }
}
