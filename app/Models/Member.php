<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;

class Member extends Model
{
    protected $table = 'members'; // ShardingSphere 逻辑表名
    protected $primaryKey = 'member_id';
    public $incrementing = false;
    protected $fillable = [ 'name', 'email', 'password', ];
    protected $hidden = [ 'password', 'remember_token', ];
    protected $casts = [ 'created_at' => 'datetime:Y-m-d H:i:s', ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $timestamp = Date::now()->timestamp;
            $uuid = Str::uuid()->toString();
            $hex = str_replace('-', '', $uuid);
            $shortUuid = substr($hex, -8);
            $id = ($timestamp << 32) | hexdec($shortUuid);
            $model->member_id = $id;
        });
    }
}
