<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletHistory extends Model
{
    protected $table = 'wallet_history';
    protected $fillable = [];
    protected $guarded = [];
    use HasFactory;
}
