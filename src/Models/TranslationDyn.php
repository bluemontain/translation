<?php

namespace BlueMountainTeam\Translation\Models;

use Illuminate\Database\Eloquent\Model;
use BlueMountainTeam\Translation\Models\Locale;

class TranslationDyn extends Model
{
    public $table = "translationsdyn";

    public $fillable = [
        'locale_id',
        'translationsdyn_id',
        'content',
        'model',
        'object_id',
        'field'
    ];

    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }

    /**
     * The original word or sentence to be translated
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function source()
    {
        return $this->belongsTo(TranslationDyn::class, 'translationsdyn_id', 'id');
    }


}