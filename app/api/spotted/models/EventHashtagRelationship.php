<?php

namespace relive\models;

class EventHashtagRelationship extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'eventhashtagrelationships';
	protected  $primaryKey = 'relation_id';
	protected $fillable = array('hashtag_id','event_id');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function hashtag() {
		return $this->belongsTo('relive\models\Hashtag','hashtag_id','hashtag_id');
	}

	public function event() {
		return $this->belongsTo('relive\models\Event','event_id','event_id');
	}
}