<?php

namespace relive\models;

class Provider extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'providers';
	protected  $primaryKey = 'provider_id';
	protected $fillable = array('providerName', 'providerSite');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function posts() {
		return $this->hasMany('relive\models\Post','provider_id','provider_id');
	}
}