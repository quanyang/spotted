<?php

namespace relive\models;

class Report extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reports';
	protected  $primaryKey = 'report_id';
	protected $fillable = array('report_time', 'post_id');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function post() {
		return $this->belongsTo('relive\models\Post','post_id','post_id');
	}
}