<?php
/**
 * @Notes:
 * @Date: 2025/12/31
 * @Time: 09:04
 * @Interface IndexController
 * @return
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

/**
 * User: qinfuxing
 * Date: 2025/12/31
 * Time: 09:04
 */
class IndexController extends Controller
{
    public function index(){
        return $this->success(['hello' => 'world']);
    }

    public function phpinfo(){
        echo phpinfo();
    }
}
