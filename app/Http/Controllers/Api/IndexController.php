<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Member;
use App\Models\MemberEmailIndex;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class IndexController extends Controller
{
    public function parse($ulid)
    {
        try {
            $parsedDate = Carbon::createFromId(strtoupper($ulid));
            return sprintf('%s_%s', (new Order)->getTable(), $parsedDate->format('Ym'));
        } catch (\Exception $e) {
            return false;
        }
    }

    public function index()
    {
        DB::connection()->enableQueryLog();

        DB::transaction(function () {
            for ($i = 0; $i < 2; $i++) {
                $member = Member::create([
                    'name' => 'name_'.rand(1,100000),
                    'email' => rand(1,100000).'5235@qq.com',
                    'email_verified_at' => now(),
                    'password' => bcrypt('password'),
                    'remember_token' => Str::random(10),
                ]);

                MemberEmailIndex::create([
                    'email' => $member->email,
                    'member_id' => $member->member_id,
                    'shard_index' => $member->member_id % 19,
                ]);

                // 测试更新操作
                Member::where('member_id', $member->member_id)->update([
                    'name' => $member->name . 'xxx'
                ]);
            }
        });

        // 获取测试数据（最近2个会员）
        $members = Member::query()->orderBy('created_at', 'desc')->limit(2)->get();

        // 随机选择一个有效的会员ID
        $validMemberIds = Member::pluck('member_id')->toArray();
        if (empty($validMemberIds)) {
            // 极端情况：无会员时创建一个
            $fallbackMember = Member::create([
                'name' => 'Fallback Member',
                'email' => 'fallback@test.com',
                'password' => bcrypt('password'),
            ]);
            $targetMemberId = $fallbackMember->member_id;
        } else {
            $targetMemberId = $validMemberIds[array_rand($validMemberIds)];
        }

        // 创建订单（使用有效的会员ID）
        $order = Order::create([
            'member_id' => $targetMemberId,
            'amount' => 100,
        ]);

        // 创建订单项
        OrderItem::create([
            'order_id' => $order->order_id,
            'member_id' => $targetMemberId,
            'product_name' => 'Test Product',
            'quantity' => 1,
            'amount' => 100,
        ]);

        // 解析ULID（测试分片表名）
        $parseUlid = $this->parse($order->order_id);

        $startTime = now()->subMonth();
        $endTime = now();

        $orders = Order::where('member_id', $targetMemberId)
            ->whereBetween('created_at', [$startTime, $endTime])
            ->with(['items' => function ($query) use ($startTime, $endTime) {
                $query->whereBetween('created_at', [$startTime, $endTime]);
            }])
            ->orderBy('created_at', 'desc')
            ->paginate(1);

        // 关联会员信息（手动拼装，避免跨分片关联）
        $orderMemberIds = $orders->pluck('member_id')->unique()->toArray();
        $orderMembers = Member::whereIn('member_id', $orderMemberIds)->get(); // 按分片键查询，确保能命中

        $orders->each(function ($order) use ($orderMembers) {
            $order->member = $orderMembers->firstWhere('member_id', $order->member_id);
        });

        return response()->json([
            'parseUlid' => $parseUlid,
            'orders' => $orders,
            'test_members' => $members,
            'used_member_id' => $targetMemberId,
            'sql_logs' => DB::getQueryLog(),
        ]);
    }
}
