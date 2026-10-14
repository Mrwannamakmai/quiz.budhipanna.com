<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyAttempt extends Model
{
    protected $fillable = [
        'user_id', 'challenge_date', 'score', 'total',
        'passed', 'answers', 'streak_at',
    ];

    protected $casts = [
        'challenge_date' => 'date',
        'passed'         => 'boolean',
        'score'          => 'integer',
        'total'          => 'integer',
        'streak_at'      => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getScorePercentAttribute(): int
    {
        return $this->total > 0 ? (int) round($this->score / $this->total * 100) : 0;
    }

    public function getAnswersDecodedAttribute(): array
    {
        return json_decode($this->answers, true) ?? [];
    }
}
