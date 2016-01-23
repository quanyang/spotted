<?php

namespace relive\models;

class PostHashtagRelationship extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posthashtagrelationships';
	protected  $primaryKey = 'relation_id';
	protected $fillable = array('hashtag_id','post_id');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public function post() {
		return $this->belongsTo('relive\models\Post','post_id','post_id');
	}

	public function hashtag() {
		return $this->belongsTo('relive\models\Hashtag','hashtag_id','hashtag_id');
	}
}