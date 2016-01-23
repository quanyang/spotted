<?php

namespace relive\models;

class Hashtag extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'hashtags';
	protected  $primaryKey = 'hashtag_id';
	protected $fillable = array('hashtag');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $appends = ['events','posts'];
	protected $hidden = array('eventhashtagrelationship','posthashtagrelationship');

	public function posthashtagrelationship() {
		return $this->hasMany('relive\models\PostHashtagRelationship','hashtag_id','hashtag_id');
	}

	public function eventhashtagrelationship() {
		return $this->hasMany('relive\models\EventHashtagRelationship','hashtag_id','hashtag_id');
	}

	public function getEventsAttribute() {
		$events = [];
		foreach($this->eventhashtagrelationship as $relationship) {
			array_push($events, $relationship->event);
		}
		return $events;
	}

	public function getPostsAttribute() {
		$posts = [];

		foreach($this->posthashtagrelationship as $relationship) {
			array_push($posts, $relationship->post);
		}
		return $posts;
	}

}