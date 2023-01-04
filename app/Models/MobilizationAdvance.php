<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilizationAdvance extends Model
{
    use HasFactory;
    protected $fillable = ['mobAdvance','bankName','bankBranch','mobAdvMode','dateMobAdv','validUpto'];
}