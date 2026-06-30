<?php

namespace App\Console\Commands;

use App\Models\WorkPrinterModel;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class SendPrintFarmReportCommand extends Command
{
    protected $signature = 'printer:farm-report
                            {--printer=* : 打印机唯一 ID}
                            {--machine_type= : 机器类型}
                            {--bulk_id= : 批量任务 ID}
                            {--bulk_name= : 批量任务名称}';

    protected $description = '向 Anycubic MQTT topic 发送 print farm上报消息';

    public function handle(): int
    {
        $printers = array_filter($this->option('printer'));
        $bulk_id = $this->option('bulk_id');
        $machine_type = $this->option('machine_type');
        $bulk_name = $this->option('bulk_name') ?: '';

        if (empty($printers)) {
            $this->error('请至少指定一个 --printer');

            return self::FAILURE;
        }

        if ($bulk_id === null || $bulk_id === '') {
            $this->error('请指定 --bulk_id');

            return self::FAILURE;
        }
        if ($machine_type === null || $machine_type === '') {
            $this->error('请指定 --machine_type');

            return self::FAILURE;
        }
        if ($bulk_name === null || $bulk_name === '') {
            $this->error('请指定 --bulk_name');

            return self::FAILURE;
        }

        $client = new MqttClient(
            config('mqtt.host'),
            (int) config('mqtt.port'),
            config('mqtt.client_id')
        );

        $settings = (new ConnectionSettings())
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'));
        /*{
            "batch_number": 2,
        "farm_project_bulk_id": 124,
        "name": "kx-0.4-圆柱体(01)_PLA_0.2_19m24s",
        "slot_color_material_mismatch": [
            "左边的kx",
            "右边的kx"
        ],
        "offline_busy_printer": [
            "室内kx-1",
            "室内kx-2"
        ],
        "printers": [
            {
                "id": 1,
                "name": "左边kx",
                "machine_type": 20030
            }
        ]
    }*/
    $printers_list = WorkPrinterModel::query()->select(['id','machine_type','key','name','description','model'])->whereIn('key',$printers)->get()->toArray();

        try {
            $client->connect($settings, true);
            $flag = false;
            foreach ($printers as $printerId) {
                if ($flag) {
                    continue;
                }
                $topic = "anycubic/anycubicCloud/v1/printer/public/{$machine_type}/{$printerId}/farm/report";
                $msgId = Str::uuid()->toString();
                $payload = [
                    'action' => 'start',
                    'data' => [
                        'batch_number'=>2,
                        'farm_project_bulk_id'=>$bulk_id,
                        'name'=>$bulk_name,
                        'slot_color_material_mismatch'=>[
                            'test机器',
                            'test机器2'
                        ],
                        'offline_busy_printer'=>[
                            'test机器',
                            'test机器2'
                        ],
                        'printers'=>$printers_list
                        ],
                    'type' => 'farm',
                    'msgid' => $msgId,
                    'state' => 'failed',
                    'timestamp' => (int) round(microtime(true) * 1000),
                    'code' => 200,
                    'msg' => 'test',
                ];

                $client->publish($topic, json_encode($payload), MqttClient::QOS_AT_LEAST_ONCE);

                $this->info("已发送: printer={$printerId}, topic={$topic}, msgid={$msgId},payload=".json_encode($payload));
                $flag = true;
            }

            $client->disconnect();
        } catch (\Throwable $e) {
            $this->error('MQTT 发送失败: '.$e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}
