<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class SendPrintPauseReportCommand extends Command
{
    protected $signature = 'printer:pause-report
                            {--printer=* : 打印机唯一 ID}
                            {--taskid= : 任务 ID}
                            {--user-id= : 用户 ID，默认读 config}';

    protected $description = '向 Anycubic MQTT topic 发送 print pause 上报消息';

    public function handle(): int
    {
        $printers = array_filter($this->option('printer'));
        $taskId = $this->option('taskid');
        $userId = $this->option('user-id') ?: config('mqtt.user_id');

        if (empty($printers)) {
            $this->error('请至少指定一个 --printer');

            return self::FAILURE;
        }

        if ($taskId === null || $taskId === '') {
            $this->error('请指定 --taskid');

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

        try {
            $client->connect($settings, true);

            foreach ($printers as $printerId) {
                $topic = "anycubic/anycubicCloud/v1/printer/public/{$userId}/{$printerId}/print/report";
                $msgId = Str::uuid()->toString();
                $payload = [
                    'action' => 'pause',
                    'data' => ['taskid' => (int) $taskId],
                    'type' => 'print',
                    'msgid' => $msgId,
                    'state' => 'paused',
                    'timestamp' => (int) round(microtime(true) * 1000),
                    'code' => 11812,
                    'msg' => 'test',
                ];

                $client->publish($topic, json_encode($payload), MqttClient::QOS_AT_LEAST_ONCE);

                $this->info("已发送: printer={$printerId}, topic={$topic}, msgid={$msgId}");
            }

            $client->disconnect();
        } catch (\Throwable $e) {
            $this->error('MQTT 发送失败: '.$e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    }
}