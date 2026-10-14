<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'exam_set_id', 'user_id', 'guest_name', 'issued_by',
        'certificate_no', 'score', 'issued_type', 'notes', 'issued_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'score'     => 'decimal:2',
    ];

    // Relationships
    public function examSet()
    {
        return $this->belongsTo(ExamSet::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // Helpers
    public function getRecipientNameAttribute(): string
    {
        return $this->guest_name ?? ($this->user->name ?? '-');
    }

    public static function generateCertificateNo(): string
    {
        do {
            $no = 'CERT-' . strtoupper(substr(md5(uniqid()), 0, 8));
        } while (self::where('certificate_no', $no)->exists());

        return $no;
    }
}
