<?php

namespace relive\models;

class Post extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';
	protected  $primaryKey = 'post_id';
	protected $fillable = array('datetime', 'postURL', 'author', 'caption', 'provider_id', 'rankPoints');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('rankPoints','provider_id','posteventrelationship','posthashtagrelationship');
	protected $appends = ['providerName','media','hashtags'];

	public function toArray() {
		$data = parent::toArray();
		if ($data['media']!=null) {
			$largest = $data['media']['data'][0];
			$medias=$data['media']['data'];
			foreach($medias as $media) {
				if ($media->width>$largest->width && $media->height>$largest->height) {
					$largest=$media;
				}
			}
			$data['media']['data'] = [$largest];
			return $data;
		} else {
			unset($data['media']);
			return $data;
		}
	}
	public function provider() {
		return $this->belongsTo('relive\models\Provider','provider_id','provider_id');
	}

	public function media() {
		return $this->hasOne('relive\models\Media','post_id','post_id');
	}

	public function posteventrelationship() {
		return $this->hasMany('relive\models\PostEventRelationship','post_id','post_id');
	}

	public function report() {
		return $this->hasMany('relive\models\Report', 'post_id', 'post_id');
	}

	public function posthashtagrelationship() {
		return $this->hasMany('relive\models\PostHashtagRelationship','post_id','post_id');
	}

	public function getProviderNameAttribute() {
		return $this->provider()->select('providerName')->first()->providerName;
	}

	public function getHashtagsAttribute() {
		$hashtags = [];
		foreach($this->posthashtagrelationship as $relationship) {
			array_push($hashtags, $relationship->hashtag->hashtag);
		}
		return $hashtags;
	}

	public function getMediaAttribute() {
		$media = $this->media()->first();
		return $media;
	}
}