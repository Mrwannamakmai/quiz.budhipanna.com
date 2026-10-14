<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Laravel 5.8 ส่งพารามิเตอร์ของ route เป็น array แบบมี key (เช่น exam_set)
     * บน PHP 8 จะถูกตีความเป็น named arguments แล้ว error "Unknown named parameter"
     * จึงส่งเป็นลำดับตำแหน่งแทน (ทำงานเหมือนเดิมบน PHP 7)
     */
    public function callAction($method, $parameters)
    {
        return call_user_func_array([$this, $method], array_values($parameters));
    }
}
