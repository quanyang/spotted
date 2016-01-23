<?php

namespace relive\models;

class MediaURL extends \Illuminate\Database\Eloquent\Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'mediaurls';
	protected  $primaryKey = 'mediaurl_id';
	protected $fillable = array('media_id', 'mediaURL', 'width', 'height', 'sizes');
	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('mediaurl_id','media_id');	

	public function media() {
		return $this->belongsTo('relive\models\Media','media_id','media_id');
	}
}