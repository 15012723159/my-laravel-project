<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use stdClass;

trait Response
{

    /**
     * 成功响应
     * @param $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function success($data, int $statusCode = 200): JsonResponse
    {
        return self::responseJson(0, 'success', $data, $statusCode);
    }

    /**
     * 失败响应
     *
     * @param string $errMsg
     * @param int $errorCode
     * @param int $statusCode
     * @return JsonResponse
     */
    public function fail(string $errMsg = '', int $errorCode = 1, int $statusCode = 200): JsonResponse
    {
        if (blank($errMsg)) {
            $errMsg = 'fail';
        }

        return self::responseJson($errorCode, $errMsg, null, $statusCode);
    }

    protected static function responseJson($code, $message, $data, $statusCode): JsonResponse
    {

        if (is_null($data)) {
            $data = new stdClass();
        }

        $data = [
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ];

        if (app()->environment(['local'])) {
            $data['time'] = microtime(true) - LARAVEL_START;
        }

        return response()->json($data, $statusCode);
    }

    /**
     * 认证不通过
     *
     * @param string $errMsg
     * @param int $errorCode
     * @param int $statusCode
     * @return JsonResponse
     */
    public function authFail(string $errMsg = 'access_token认证失败', int $errorCode = -1, int $statusCode = 401): JsonResponse
    {
        return self::responseJson($errorCode, $errMsg, [], $statusCode);
    }
}
