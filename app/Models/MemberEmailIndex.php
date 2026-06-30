<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberEmailIndex extends Model
{
    protected $table = 'member_email_index';
    protected $primaryKey = 'email';
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [ 'email', 'member_id', 'shard_index', ];
}
