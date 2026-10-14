<?php

namespace App\Http\Controllers\Admin;

use App\SystemUpdate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SystemUpdateController extends Controller
{
    public function index()
    {
        $updates = SystemUpdate::latest()->paginate(20);
        return view('admin.updates.index', compact('updates'));
    }

    public function create()
    {
        return view('admin.updates.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|in:feature,improvement,fix,content',
        ]);

        SystemUpdate::create([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'type'         => $data['type'],
            'is_published' => $request->has('is_published'),
            'published_at' => $request->has('is_published') ? now() : null,
            'created_by'   => Auth::id(),
        ]);

        return redirect()->route('admin.updates.index')
            ->with('success', 'เพิ่มรายการอัพเดทแล้ว');
    }

    public function edit(SystemUpdate $update)
    {
        return view('admin.updates.form', compact('update'));
    }

    public function update(Request $request, SystemUpdate $update)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type'        => 'required|in:feature,improvement,fix,content',
        ]);

        $isPublished = $request->has('is_published');

        $update->update([
            'title'        => $data['title'],
            'description'  => $data['description'] ?? null,
            'type'         => $data['type'],
            'is_published' => $isPublished,
            'published_at' => ($isPublished && !$update->published_at) ? now() : $update->published_at,
        ]);

        return redirect()->route('admin.updates.index')
            ->with('success', 'แก้ไขรายการอัพเดทแล้ว');
    }

    public function destroy(SystemUpdate $update)
    {
        $update->delete();
        return back()->with('success', 'ลบรายการแล้ว');
    }

    public function toggle(SystemUpdate $update)
    {
        $update->update([
            'is_published' => !$update->is_published,
            'published_at' => !$update->is_published ? now() : $update->published_at,
        ]);
        return back()->with('success', $update->is_published ? 'เผยแพร่แล้ว' : 'ซ่อนแล้ว');
    }
}
