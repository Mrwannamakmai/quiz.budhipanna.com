<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'บันทึกข้อมูลโปรไฟล์เรียบร้อยแล้ว');
    }
}
