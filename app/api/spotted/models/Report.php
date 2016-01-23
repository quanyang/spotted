<?php

namespace spotted\models;

use Illuminate\Database\Eloquent\Model;

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

    protected $geofields = array('location');
 
 
    public function setLocationAttribute($value) {
        $this->attributes['location'] = DB::raw("POINT($value)");
    }
 
    public function getLocationAttribute($value){
 
        $loc =  substr($value, 6);
        $loc = preg_replace('/[ ,]+/', ',', $loc, 1);
 
        return substr($loc,0,-1);
    }
 
    public function newQuery($excludeDeleted = true)
    {
        $raw='';
        foreach($this->geofields as $column){
            $raw .= ' astext('.$column.') as '.$column.' ';
        }
 
        return parent::newQuery($excludeDeleted)->addSelect('*',DB::raw($raw));
    }

    public function scopeDistance($query,$dist,$location)
    {
        return $query->whereRaw('st_distance(location,POINT('.$location.')) < '.$dist);
 
    }

}
