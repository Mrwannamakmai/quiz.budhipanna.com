<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Settings\SettingsManager;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    protected $settings;

    public function __construct(SettingsManager $settings)
    {
        $this->settings = $settings;
    }

    public function index(Request $request)
    {
        $registry = $this->settings->registry();
        $groups   = $registry->groups();
        $active   = $request->query('group');
        if (!$active || !isset($groups[$active])) {
            $active = key($groups);
        }

        $fields = [];
        foreach ($registry->fieldsOf($active) as $key => $f) {
            $f['name']  = $registry->inputName($key);
            $f['value'] = $this->settings->get($key);
            $fields[]   = $f;
        }

        return view('admin.settings.index', [
            'groups' => $groups,
            'active' => $active,
            'fields' => $fields,
        ]);
    }

    public function update(Request $request, $group)
    {
        $registry = $this->settings->registry();
        abort_unless($registry->hasGroup($group), 404);

        $attributes = [];
        foreach ($registry->fieldsOf($group) as $key => $f) {
            $attributes[$registry->inputName($key)] = '"' . $f['label'] . '"';
        }
        $request->validate($registry->rulesFor($group), [], $attributes);

        $pairs = [];
        foreach ($registry->fieldsOf($group) as $key => $f) {
            $name = $registry->inputName($key);
            if ($f['type'] === 'boolean') {
                $pairs[$key] = $request->input($name) ? '1' : '0';
            } else {
                $pairs[$key] = $request->input($name);
            }
        }
        $this->settings->setMany($pairs);

        return redirect()->route('admin.settings.index', ['group' => $group])
            ->with('success', 'บันทึกการตั้งค่าเรียบร้อย');
    }
}
