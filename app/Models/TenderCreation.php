<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderCreation extends Model
{
    use HasFactory;
    protected $fillable = ['customername','oraganization','tendertype','nitdate'];
}
