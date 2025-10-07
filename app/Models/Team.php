<?php

namespace App\Models;

use App\Filament\Resources\GcvDataTandaTerimaResource\Widgets\gcv_datatandaterimaStats;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\GcvDataSiteplan;
use App\Models\gcv_datatandaterima ;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function gcvDataSiteplans()
    {
        return $this->hasMany(GcvDataSiteplan::class);
    }

    public function dataTandaTerimas()
    {
        return $this->hasMany(gcv_datatandaterima::class);
    }
}
