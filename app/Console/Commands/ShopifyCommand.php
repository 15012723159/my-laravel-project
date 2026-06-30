<?php

namespace App\Console\Commands;

use App\Exceptions\ApiException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class ShopifyCommand extends Command
{
    protected $signature = 'shopify:select
                            {--sku=* : sku}
                            {--site= : 站点}';

    protected $description = '根据sku查询库存信息';

    public function handle()
    {
        $sku = array_filter($this->option('sku'));
        $site = $this->option('site');


        if (empty($sku)) {
            $this->error('请至少指定一个 --sku');

            return self::FAILURE;
        }
        var_dump($site);
        $store_url = config('shopify_deposit_discount')[$site]['store_url'] ?? '';
        $x_shopify_access_token = config('shopify_deposit_discount')[$site]['x_shopify_access_token'] ?? '';

        if (empty($store_url) || empty($x_shopify_access_token)) {
            var_dump('store_url or x_shopify_access_token is empty');
            return self::FAILURE;
        }

        // 支持多个 SKU，逗号分隔
        if (!is_array($sku)){
            $skuList = array_unique(array_map('trim', explode(',', $sku)));
        }else{
            $skuList = $sku;
        }

        $skuQueries = array_map(function ($s) {
            return 'sku:' . $s;
        }, $skuList);
        $skuQueryString = implode(' OR ', $skuQueries);
        $firstCount = count($skuList) * 12;

        $url = $store_url . '/admin/api/2025-04/graphql.json';
        var_dump($url);
        $query = 'query {
            productVariants(first: ' . $firstCount . ', query: "' . $skuQueryString . '") {
                edges {
                    node {
                        id
                        sku
                        title
                        price
                        inventoryQuantity
                        product {
                            id
                            title
                        }
                    }
                }
            }
        }';

        $data = [
            'query' => $query,
        ];

        $headers = [
            'X-Shopify-Access-Token' => $x_shopify_access_token,
            'Content-Type' => 'application/json',
        ];

        $response = Http::withHeaders($headers)
            ->withOptions([
                'verify' => false,  // 关闭 SSL 证书验证
            ])
            ->post($url, $data)->throw()->json();

        if (!empty($response['errors'])) {
            Log::channel('api')->error('Shopify GraphQL error', $response['errors']);
            throw new ApiException('Shopify GraphQL query failed');
        }

        $edges = $response['data']['productVariants']['edges'] ?? [];
        Log::channel('api')->info('Shopify GraphQL query success', $edges[0]);
        if (empty($edges)) {
            return [];
        }

        $result = [];
        foreach ($edges as $edge) {
            $variant = $edge['node'] ?? [];
            if (empty($variant)) {
                continue;
            }
            $result[] = [
                'variant_id' => $variant['id'] ?? '',
                'sku' => $variant['sku'] ?? '',
                'variant_title' => $variant['title'] ?? '',
                'price' => $variant['price'] ?? '',
                'inventory_quantity' => $variant['inventoryQuantity'] ?? 0,
                'product_id' => $variant['product']['id'] ?? '',
                'product_title' => $variant['product']['title'] ?? '',
            ];
        }
        var_dump($result);

        return self::SUCCESS;
    }
}
