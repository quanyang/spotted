<?php

namespace relive\models;

class CrawlJob extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'crawljobs';
	protected  $primaryKey = 'crawler_id';
	protected $fillable = array('event_id');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function event() {
		return $this->belongsTo('relive\models\Event','event_id','event_id');
	}
}