<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StripeConfig extends Model
{
    protected $guarded = [];
    protected $fillable = [];
    protected $table = "stripe_config";
    use HasFactory;
}
