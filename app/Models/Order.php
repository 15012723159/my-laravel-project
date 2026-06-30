<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $table = 'orders'; // ShardingSphere 逻辑表名
    protected $primaryKey = 'order_id';
    public $incrementing = false; // 使用分布式ID
    protected $fillable = [ 'member_id', 'amount' ];
    protected $casts = [ 'created_at' => 'datetime:Y-m-d H:i:s', ];
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            $order->order_id = Str::ulid();
        });
    }
}
