<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mailing extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;

    public const STATUS_NEW = 'NEW';
    public const STATUS_FAIL = 'FAIL';
    public const STATUS_GOOD = 'GOOD';
}
