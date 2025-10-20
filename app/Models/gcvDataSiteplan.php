<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class GcvDataSiteplan extends Model
{
    use HasFactory, SoftDeletes, HasRoles;



    protected $fillable = [
        "siteplan",
        "kavling",
        "type",
        "terbangun",
        "luas",
        "keterangan",
        "team_id",
        "user_id"

    ];

 public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
{
    static::creating(function ($model) {
        if (! $model->user_id) {
            $model->user_id = filament()->auth()->id();
        }

        if (! $model->team_id) {
            $model->team_id = filament()->getTenant()?->id;
        }
    });
}
}
