<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ip_address', 'url', 'method', 'user_id', 'user_agent', 'referrer', 'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record($request, $userId = null)
    {
        self::create([
            'ip_address' => $request->ip(),
            'url'        => substr($request->fullUrl(), 0, 1000),
            'method'     => $request->method(),
            'user_id'    => $userId ?? ($request->user() ? $request->user()->id : null),
            'user_agent' => substr($request->userAgent() ?? '', 0, 500),
            'referrer'   => substr($request->headers->get('referer') ?? '', 0, 1000),
            'visited_at' => now(),
        ]);
    }

    public static function isBot($userAgent)
    {
        if (!$userAgent) return false;
        $bots = ['bot', 'crawl', 'spider', 'slurp', 'feedfetcher', 'curl', 'wget',
                 'python', 'java/', 'libwww', 'scan', 'inspect', 'headless'];
        $ua = strtolower($userAgent);
        foreach ($bots as $b) {
            if (strpos($ua, $b) !== false) return true;
        }
        return false;
    }
}
