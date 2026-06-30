<?php
/**
 * @Notes:
 * @Date: 2026/2/27
 * @Time: 14:23
 * @Interface StrController
 * @return
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

/**
 * User: qinfuxing
 * Date: 2026/2/27
 * Time: 14:23
 */
class StrController extends Controller
{
    public function after(){
        $slice = Str::after('This is my name', 'This is');
        var_dump($slice);
    }

    public function camel(){
       $str = Str::camel('foo   bar');
       var_dump($str);

        $excerpt = Str::excerpt('This is my name', 'my', [
            'radius' => 3
        ]);

        var_dump($excerpt);


        $headline = Str::headline('steve-jobs');
        var_dump($headline);

        $truncated = Str::limit('The quick brown fox jumps over the lazy dog', 20);
        var_dump($truncated);

       $str =  Str::mask('433122199101070516', '*', -12, 8);

       var_dump($str);
    }
}
