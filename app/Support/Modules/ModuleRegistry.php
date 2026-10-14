<?php

namespace App\Support\Modules;

use App\Support\Settings\Settings;

/**
 * ทะเบียนโมดูล + เมนูของโมดูล
 * layouts (teacher / student / admin) จะ @include('modules._menu', ['area' => ...])
 * เพื่อแสดงเมนูที่โมดูลลงทะเบียนไว้ โดยไม่ต้องแก้ layout ทุกครั้งที่เพิ่มโมดูล
 */
class ModuleRegistry
{
    protected $modules = [];
    protected $menus = [];

    public function add($key, array $meta = [])
    {
        $this->modules[$key] = array_merge(['key' => $key, 'label' => $key], $meta);
        return $this;
    }

    public function all()
    {
        return $this->modules;
    }

    public function enabled($key)
    {
        return isset($this->modules[$key]) && Settings::moduleEnabled($key);
    }

    /**
     * @param string $area  teacher | student | admin
     * @param array  $item  ['module' => 'attendance', 'label' => 'เช็คชื่อ', 'icon' => 'fas fa-user-check',
     *                       'route' => 'attendance.teacher.index', 'active' => 'attendance.teacher.*',
     *                       'roles' => ['teacher','superadmin'], 'section' => 'ห้องเรียน', 'order' => 10]
     */
    public function menu($area, array $item)
    {
        $this->menus[$area][] = array_merge(['roles' => null, 'section' => null, 'order' => 100, 'icon' => 'fas fa-circle'], $item);
        return $this;
    }

    public function menuFor($area, $user = null)
    {
        $items = array_filter($this->menus[$area] ?? [], function ($i) use ($user) {
            if (!empty($i['module']) && !$this->enabled($i['module'])) return false;
            if ($i['roles'] && $user && !in_array($user->role, $i['roles'], true)) return false;
            return true;
        });
        usort($items, function ($a, $b) { return $a['order'] <=> $b['order']; });
        return $items;
    }
}
