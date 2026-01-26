<?php
namespace App\Service;

use App\Exceptions\ApiException;
use App\Library\Constant\GlobalContext;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use Throwable;
class BaseService{


    /**
     * @throws ApiException|GuzzleException
     */
    public function callThirdApi(string $uri, string $method, array $params, array $header = [], bool $isThrowException = false)
    {
        $response = '';
        if (!empty($header)) {
            $this->third_config['headers'] = $header;
            //$this->third_config['verify'] = false;
        }
        try {
            $cli = new Client($this->third_config);
            if ($method == 'get') {
                $query = !empty($params) ? http_build_query($params) : '';
                $response = $cli->get($uri . '?' . $query);

            } elseif ($method == 'post') {
                $query = !empty($params) ? ['body' => json_encode($params)] : [];
                $response = $cli->post($uri, $query);

            } elseif ($method == 'put') {
                $query = !empty($params) ? ['body' => json_encode($params)] : [];
                $response = $cli->put($uri, $query);
            }
        } catch (RequestException   $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
                // 解析 JSON 响应体
                $data = json_decode($body, true);
            } else {
                $body = $data = $e->getMessage();
                $statusCode = $e->getCode();
            }
            $this->writeApiErrorLog('调用【' . $this->serverName . '=>' . $uri . '】异常：' . $body, $params, $e);
            if ($isThrowException) {
                throw new ApiException(__('api.call_internal_api_error'));
            } else {
                if ($isThrowException === FALSE) {
                    return ['status' => $statusCode, 'params' => $params, 'message' => $data];
                }
                return [];
            }
        }
        $rspData = $response->getBody();
        $status = $response->getStatusCode();
        if (!in_array($status, [200, 201])) {
            $this->writeApiErrorLog('调用【' . $this->serverName . '=>' . $uri . '】返回错误', $params, new ApiException('返回:' . $rspData));
            if ($isThrowException === FALSE) {
                return ['status' => $status, 'params' => $params, 'message' => $rspData];
            }
            return [];
        }

        return json_decode($rspData, true);
    }


    /**
     * 写入记录Api
     * @param $message
     * @param $params
     * @param Throwable $e
     */
    public function writeApiErrorLog($message, $params, Throwable $e)
    {
        Log::channel('api')->error($message, [GlobalContext::REQUEST_ID => request_id(), 'params' => $params, 'message' => $e->getMessage(), 'code' => $e->getCode(), 'file' => $e->getFile(), 'line' => $e->getLine()]);
    }
}
