<?php

namespace relive\controllers;

class HashtagController extends Controller {

	public function __construct() {
	}

	public static function getEventWithHashtag($hashtag) {
		$app = \Slim\Slim::getInstance();

		if (is_null($hashtag)||strlen($hashtag)>255||empty($hashtag)) {
        	$app->render(400, ['Status' => 'Invalid input.' ]);
        	return;
        }

		$hashtag = \relive\models\Hashtag::where('hashtag',$hashtag)->get();

		if (count($hashtag)>0) {
			$hashtagobj = $hashtag->first()->toArray();
			echo json_encode($hashtagobj, JSON_UNESCAPED_SLASHES);
		} else {
			$app->render(404, ['Status','Hashtag not found.']);
		}
	}
}