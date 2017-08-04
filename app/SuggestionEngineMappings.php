<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuggestionEngineMappings extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suggestionenginemappings';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['category_id', 'modelName', 'model'];

    public function getModelAttribute()
    {
        return (isset($this->attributes['model'])) ? unserialize($this->attributes['model']) : null;
    }

    public function setModelAttribute($value)
    {
        $this->attributes['model'] = serialize($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
