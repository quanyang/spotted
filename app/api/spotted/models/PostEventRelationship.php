<?php

namespace relive\models;

class PostEventRelationship extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posteventrelationships';
	protected  $primaryKey = 'relation_id';
	protected $fillable = array('event_id', 'post_id', 'isFiltered');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function post() {
		return $this->belongsTo('relive\models\Post','post_id','post_id');
	}

	public function event() {
		return $this->belongsTo('relive\models\Event','event_id','event_id');
	}
}