<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemUpdate extends Model
{
    protected $fillable = [
        'title', 'description', 'type', 'is_published', 'published_at', 'created_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public static $types = [
        'feature'     => ['label' => 'ฟีเจอร์ใหม่',  'color' => '#16a34a', 'bg' => '#dcfce7', 'icon' => 'fa-star'],
        'improvement' => ['label' => 'ปรับปรุง',       'color' => '#2563eb', 'bg' => '#dbeafe', 'icon' => 'fa-arrow-up'],
        'fix'         => ['label' => 'แก้ไขบัค',      'color' => '#d97706', 'bg' => '#fef3c7', 'icon' => 'fa-wrench'],
        'content'     => ['label' => 'เนื้อหาใหม่',   'color' => '#7c3aed', 'bg' => '#ede9fe', 'icon' => 'fa-book'],
    ];

    public function getTypeMeta(): array
    {
        return self::$types[$this->type] ?? self::$types['feature'];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
