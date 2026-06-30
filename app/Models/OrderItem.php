<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    protected $table = 'order_items'; // ShardingSphere 逻辑表名
    protected $primaryKey = 'order_item_id';
    public $incrementing = false;
    protected $fillable = [ 'order_id', 'member_id', 'product_name', 'quantity', 'amount', ];
    protected $casts = [ 'created_at' => 'datetime:Y-m-d H:i:s', ];
    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = $value->timezone('UTC');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($orderItem) {
            $orderItem->order_item_id = Str::ulid();
        });
    }
}
