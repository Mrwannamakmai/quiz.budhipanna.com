<?php

namespace App\Support\Settings;

/**
 * ทะเบียนค่าตั้งค่า — แต่ละโมดูลลงทะเบียนกลุ่ม (group) และฟิลด์ของตัวเอง
 * หน้า แอดมิน > ตั้งค่าระบบ จะสร้างฟอร์มจากทะเบียนนี้อัตโนมัติ
 *
 * ชนิดฟิลด์ (type): text, textarea, number, boolean, select, time
 */
class SettingsRegistry
{
    /** @var array<string, array> */
    protected $groups = [];

    /** @var array<string, array> key => field definition */
    protected $fields = [];

    /**
     * @param string $group   รหัสกลุ่ม เช่น 'attendance'
     * @param array  $meta    ['label' => 'เช็คชื่อ', 'icon' => 'fas fa-user-check', 'order' => 20, 'description' => '...']
     * @param array  $fields  [['key' => 'attendance.enabled', 'label' => '...', 'type' => 'boolean', 'default' => true], ...]
     */
    public function group($group, array $meta, array $fields)
    {
        $this->groups[$group] = array_merge([
            'key'         => $group,
            'label'       => $group,
            'icon'        => 'fas fa-cog',
            'order'       => 100,
            'description' => null,
        ], $meta, ['key' => $group]);

        foreach ($fields as $field) {
            $field = array_merge([
                'type'    => 'text',
                'default' => null,
                'help'    => null,
                'options' => [],
                'min'     => null,
                'max'     => null,
                'suffix'  => null,
            ], $field, ['group' => $group]);
            $this->fields[$field['key']] = $field;
        }

        return $this;
    }

    public function groups()
    {
        $groups = $this->groups;
        uasort($groups, function ($a, $b) { return $a['order'] <=> $b['order']; });
        return $groups;
    }

    public function hasGroup($group)
    {
        return isset($this->groups[$group]);
    }

    public function fieldsOf($group)
    {
        return array_filter($this->fields, function ($f) use ($group) { return $f['group'] === $group; });
    }

    public function field($key)
    {
        return $this->fields[$key] ?? null;
    }

    public function defaultOf($key)
    {
        return isset($this->fields[$key]) ? $this->fields[$key]['default'] : null;
    }

    /** แปลงค่าที่เก็บเป็นข้อความ ให้เป็นชนิดตามที่ลงทะเบียนไว้ */
    public function cast($key, $value)
    {
        $field = $this->field($key);
        if (!$field || $value === null) {
            return $value;
        }
        switch ($field['type']) {
            case 'boolean':
                return in_array($value, [true, 1, '1', 'true', 'on', 'yes'], true);
            case 'number':
                return is_numeric($value) ? $value + 0 : $field['default'];
            default:
                return (string) $value;
        }
    }

    /** กฎ validate ของฟิลด์ในกลุ่ม สำหรับหน้า settings */
    public function rulesFor($group)
    {
        $rules = [];
        foreach ($this->fieldsOf($group) as $key => $f) {
            $name = $this->inputName($key);
            switch ($f['type']) {
                case 'boolean':
                    $r = ['nullable', 'in:0,1'];
                    break;
                case 'number':
                    $r = ['required', 'numeric'];
                    if ($f['min'] !== null) $r[] = 'min:' . $f['min'];
                    if ($f['max'] !== null) $r[] = 'max:' . $f['max'];
                    break;
                case 'select':
                    $r = ['required', 'in:' . implode(',', array_keys($f['options']))];
                    break;
                case 'time':
                    $r = ['nullable', 'date_format:H:i'];
                    break;
                default:
                    $r = ['nullable', 'string', 'max:' . ($f['type'] === 'textarea' ? 5000 : 255)];
            }
            $rules[$name] = $r;
        }
        return $rules;
    }

    /** ชื่อ input ในฟอร์ม (จุดใน key ใช้ใน name ไม่ได้ เพราะ Laravel ตีความเป็น array) */
    public function inputName($key)
    {
        return str_replace('.', '__', $key);
    }
}
