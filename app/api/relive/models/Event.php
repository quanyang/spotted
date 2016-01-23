<?php

namespace relive\models;

class Event extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'events';
	protected  $primaryKey = 'event_id';
	protected $fillable = array('eventName');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $appends = ['hashtags'];
	protected $hidden = array('rankPoints','eventhashtagrelationship','posteventrelationship');


	public function eventhashtagrelationship() {
		return $this->hasMany('relive\models\EventHashtagRelationship','event_id','event_id');
	}

	/*
	public function posteventrelationship() {
		return $this->hasMany('relive\models\PostEventRelationship','event_id','event_id');
	}*/

	public function getHashtagsAttribute() {
		$hashtags = [];
		foreach($this->eventhashtagrelationship as $relationship) {
			array_push($hashtags, $relationship->hashtag->hashtag);
		}
		return $hashtags;
	}

	public function getPostsAttribute() {
		/*
		$posts = \relive\models\Post::whereIn('post_id', function($query) {
			$query->select('post_id')->from('posteventrelationships')->where('event_id','=',$this->event_id)->where('isFiltered',False);
		})->orderBy('datetime','desc')->offset(0)->limit(15)->get();
		*/

		/*
		select * from `posts` where `post_id` in (SELECT post_id from posteventrelationships where event_id = 36) order by `datetime` desc limit 15 offset 0;

		select * from `posts` inner join (SELECT post_id from posteventrelationships where event_id = 36) filter on `filter`.`post_id` = `posts`.`post_id` order by `datetime` desc limit 15 offset 0;

		select * from `posts` inner join `posteventrelationships` on `posteventrelationships`.`post_id` = `posts`.`post_id` where `event_id` = 36 order by `datetime` desc limit 15 offset 0;
		*/

		//$posts = \relive\models\Post::join('posteventrelationships','posteventrelationships.post_id','=','posts.post_id')->where('event_id','=',$this->event_id)->orderBy('datetime','desc')->offset(0)->limit(15)->get();
		//return $posts;
	}
}