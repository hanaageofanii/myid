<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GcvDataSiteplan extends Model
{
    use HasFactory, SoftDeletes, HasRoles;



    protected $fillable = [
        "siteplan",
        "kavling",
        "type",
        "terbangun",
        "luas",
        "keterangan","team_id"
    ];

 public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }}