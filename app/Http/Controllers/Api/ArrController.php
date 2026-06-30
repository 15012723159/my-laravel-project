<?php
/**
 * @Notes:
 * @Date: 2026/2/27
 * @Time: 14:14
 * @Interface ArrController
 * @return
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * User: qinfuxing
 * Date: 2026/2/27
 * Time: 14:14
 */
class ArrController extends  Controller
{
    public function head(){
        $array = [100, 200, 300];

        $first = head($array);
        var_dump($first);
    }
}
