<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * ค่าตั้งค่าระบบแบบ key/value (ตาราง settings)
 * ใช้ผ่าน App\Support\Settings\Settings::get('group.key') แทนการเรียก model ตรง ๆ
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];
}
