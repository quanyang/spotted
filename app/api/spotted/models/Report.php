<?php

namespace spotted\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression as raw;

class Report extends Model
{
	public $timestamps = true;
/**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $appends = [];    
    protected $hidden = [];

    public function imagesrelationship() {
        return $this->hasMany('spotted\models\Image','report_id','id');
    }

}
