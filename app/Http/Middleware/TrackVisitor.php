<?php

namespace App\Http\Middleware;

use App\VisitorLog;
use Closure;

class TrackVisitor
{
    // URL prefixes to skip tracking
    const SKIP_PREFIXES = [
        '/admin',
        '/login', '/logout', '/register', '/password',
        '/_debugbar', '/horizon',
    ];

    // Static extensions to skip
    const SKIP_EXTENSIONS = [
        'css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico',
        'woff', 'woff2', 'ttf', 'eot', 'map',
    ];

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->method() !== 'GET') {
            return $response;
        }

        $path = $request->path();

        foreach (self::SKIP_PREFIXES as $prefix) {
            if (strpos('/' . $path, $prefix) === 0) {
                return $response;
            }
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if ($ext && in_array($ext, self::SKIP_EXTENSIONS)) {
            return $response;
        }

        if (VisitorLog::isBot($request->userAgent())) {
            return $response;
        }

        try {
            VisitorLog::record($request);
        } catch (\Exception $e) {
            // ไม่ให้ tracking error กระทบ response
        }

        return $response;
    }
}
