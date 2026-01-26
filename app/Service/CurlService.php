<?php
/**
 * @Notes:
 * @Date: 2026/1/26
 * @Time: 11:26
 * @Interface CurlService
 * @return
 */

namespace App\Service;

/**
 * User: qinfuxing
 * Date: 2026/1/26
 * Time: 11:26
 */
class CurlService extends BaseService
{

    protected string $serverName = '客服服务';
    protected $header = [];


    public function __construct(){
        $this->third_config['base_uri'] = env('IP_JSON_DOMAIN');
        $this->headers['Content-Type'] = 'application/json';
        //$this->headers['Authorization'] = 'Basic ' . base64_encode(self::APP_ID . '/token:' . self::APP_SECRET);
    }


}
