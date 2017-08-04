<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Report
 * @package App
 *
 * @property int $id
 * @property string name
 * @property string class_name
 * @property array|\stdClass $configuration
 */
class Report extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'class_name', 'configuration'];

    public function setConfigurationAttribute($value)
    {
        $this->attributes['configuration'] = json_encode($value);
    }

    public function getConfigurationAttribute()
    {
        if (isset($this->attributes['configuration'])) {
            return json_decode($this->attributes['configuration']);
        }
        return [];
    }
}
