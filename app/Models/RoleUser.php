<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Cache;

/**
 * @property RoleObject $object
 * @property Workshop $workshop
 * @property integer|null $object_id
 * @property integer|null $workshop_id
 */
class RoleUser extends Pivot
{
    protected $fillable = ['workshop_id', 'object_id'];

    protected $with = ['workshop', 'object'];

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class);
    }
    public function object(): BelongsTo
    {
        return $this->belongsTo(RoleObject::class);
    }

    public function getTranslatedNameAttribute(): string
    {
        if ($this->object_id) {
            return Cache::remember($this->object_id.'_object_translated_name', 86400, function () {
                return $this->object->translatedName;
            });
        }
        if ($this->workshop_id) {
            return Cache::remember($this->workshop_id.'_workshop_name', 86400, function () {
                return $this->workshop->name;
            });
        }
        return '';
    }
}
