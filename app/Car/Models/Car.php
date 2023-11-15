<?php

namespace App\Car\Models;

use App\Car\DriversCommon\CarDriverFactory;
use App\Car\DriversCommon\Features\BaseFeature\BaseFeatureContract;
use App\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{

    protected $fillable = [
        'user_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function getIsBusyAttribute(): bool {
        return $this->user_id !== null;
    }

}
