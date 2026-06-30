<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkPrinterModel extends Model
{
    protected $connection = 'workerbench';
    protected $table = 'cmf_work_printer';
}
