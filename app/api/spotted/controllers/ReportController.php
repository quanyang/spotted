<?php

namespace spotted\controllers;

use spotted\library\InputValidator;

/**
 * Class ReportController
 *
 * Controller class for all retrieval and creation of food items`
 * @package spotted
 **/
class ReportController extends Controller {


	public static function getAllLostReports() {
		$app = \Slim\Slim::getInstance();
        try {
            $reports = \spotted\models\Report::all();
            echo json_encode($reports, JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
        	print $e;
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
	}

	public static function getNearByLostReport() {
		$app = \Slim\Slim::getInstance();
        try {
            $getVars = $app->request->get();
            $longitude = @$getVars['longitude']?@trim(htmlspecialchars($getVars['longitude'], ENT_QUOTES, 'UTF-8')):NULL;
            $latitude = @$getVars['latitude']?@trim(htmlspecialchars($getVars['latitude'], ENT_QUOTES, 'UTF-8')):NULL;
            
            if ( !preg_match("/^-?\d{1,3}\.{1}\d*$/",$longitude) ||!preg_match("/^-?\d*\.{1}\d*$/", $latitude) || !InputValidator::isValidStringInput($latitude,255,0)|| !InputValidator::isValidStringInput($longitude,255,0)) {
            	$app->render(400, ['Status' => 'Invalid input.' ]);
                return;
            }
            $point = $latitude.",".$longitude;

            $distance = 2;
            echo json_encode($reports, JSON_UNESCAPED_SLASHES);
        } catch (\Exception $e) {
        	print $e;
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
	}

	public static function newStrayReport() {
        $app = \Slim\Slim::getInstance();
        try {
            $allPostVars = $app->request->post();
            $characteristics =  @$allPostVars['characteristics']?@trim(htmlspecialchars($allPostVars['characteristics'], ENT_QUOTES, 'UTF-8')):NULL;
            $number = @$allPostVars['number']?@trim(htmlspecialchars($allPostVars['number'], ENT_QUOTES, 'UTF-8')):NULL;
            $frequency = @$allPostVars['frequency']?@trim(htmlspecialchars($allPostVars['number'], ENT_QUOTES, 'UTF-8')):0; // 0,1,2
            $category = @$allPostVars['category']?@trim(htmlspecialchars($allPostVars['category'], ENT_QUOTES, 'UTF-8')):0; //0,1,2,3 dog cat bird others
            $others = @$allPostVars['others'] && $allPostVars['category'] == 3?@trim(htmlspecialchars($allPostVars['others'], ENT_QUOTES, 'UTF-8')):"";
            $fullName = @$allPostVars['fullName']?@trim(htmlspecialchars($allPostVars['fullName'], ENT_QUOTES, 'UTF-8')):NULL;
            $email = @$allPostVars['email']?@trim(htmlspecialchars($allPostVars['email'], ENT_QUOTES, 'UTF-8')):NULL;
            $longitude = @$allPostVars['longitude']?@trim(htmlspecialchars($allPostVars['longitude'], ENT_QUOTES, 'UTF-8')):NULL;
            $latitude = @$allPostVars['latitude']?@trim(htmlspecialchars($allPostVars['latitude'], ENT_QUOTES, 'UTF-8')):NULL;
            $image_id = @$allPostVars['image']?@trim(htmlspecialchars($allPostVars['image'], ENT_QUOTES, 'UTF-8')):NULL;
            $status = 0; // default
            $isLostReport = 0; //Stray Report

             if (!preg_match("/^-?\d{1,3}\.{1}\d*$/",$longitude) ||!preg_match("/^-?\d*\.{1}\d*$/", $latitude) ||!InputValidator::isValidStringInput($image_id,255,0) || !InputValidator::isValidStringInput($latitude,255,0)|| !InputValidator::isValidStringInput($longitude,255,0)||($category == 3 && !InputValidator::isValidStringInput($others,255,0) )|| !InputValidator::isValidStringInput($characteristics,5000,0)) {
                $app->render(400, ['Status' => 'Invalid input.' ]);
                return;
            }	

            if (!is_null($email) && empty($email) && !InputValidator::isValidStringInput($email,255,0) && !InputValidator::isValidEmail($email)) {
            	$app->render(400, ['Status' => 'Invalid input.' ]);
            	return;
            }

			if (!is_null($fullName) && empty($fullName) && !InputValidator::isValidStringInput($fullName,255,0) ) {
				$app->render(400, ['Status' => 'Invalid input.' ]);
				return;
			}    

			if (!is_null($number) && empty($number) && !InputValidator::isValidStringInput($number,10,8) || !preg_match("/^[0-9]{8,10}$/",$number) ) {
				$app->render(400, ['Status' => 'Invalid input.' ]);
				return;
			}      

			$freq = ["Once or twice","Often","Always"];
			$cate = ["Dog","Cat","Bird","Others"];

			if ($category == 4) {
				$category = $others;
			} else {
				$category = $cate[$category];
			}

            $report = new \spotted\models\Report();
            $report->is_lost = $isLostReport;
            $report->status = $status;
            $report->frequency = $freq[$frequency];
            $report->category = $category;
            $report->characteristics = $characteristics;
            $report->full_name = $fullName;
            $report->email = $email;
            $report->number = $number;
            $report->longitude = $longitude;
            $report->latitude = $latitude;
            $report->save();

            if ($report) {
            	$image = \spotted\models\Image::where('publicId','=',$image_id)->first();
				if ($image) {
            		$image->report_id = $report->id;
            		$image->save();
            	}
            	echo json_encode($report, JSON_UNESCAPED_SLASHES);
            } else {
				throw new \Exception('Error!');
            }

        } catch (\Exception $e) {
        	print $e;
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
    }


    public static function newLostReport() {
        $app = \Slim\Slim::getInstance();
        try {
            $allPostVars = $app->request->post();
            $characteristics =  @$allPostVars['characteristics']?@trim(htmlspecialchars($allPostVars['characteristics'], ENT_QUOTES, 'UTF-8')):NULL;
            $number = @$allPostVars['number']?@trim(htmlspecialchars($allPostVars['number'], ENT_QUOTES, 'UTF-8')):NULL;
            $frequency = @$allPostVars['frequency']?@trim(htmlspecialchars($allPostVars['number'], ENT_QUOTES, 'UTF-8')):0; // 0,1,2
            $category = @$allPostVars['category']?@trim(htmlspecialchars($allPostVars['category'], ENT_QUOTES, 'UTF-8')):0; //0,1,2,3 dog cat bird others
            $others = @$allPostVars['others'] && $allPostVars['category'] == 3?@trim(htmlspecialchars($allPostVars['others'], ENT_QUOTES, 'UTF-8')):"";
            $fullName = @$allPostVars['fullName']?@trim(htmlspecialchars($allPostVars['fullName'], ENT_QUOTES, 'UTF-8')):NULL;
            $email = @$allPostVars['email']?@trim(htmlspecialchars($allPostVars['email'], ENT_QUOTES, 'UTF-8')):NULL;
            $longitude = @$allPostVars['longitude']?@trim(htmlspecialchars($allPostVars['longitude'], ENT_QUOTES, 'UTF-8')):NULL;
            $latitude = @$allPostVars['latitude']?@trim(htmlspecialchars($allPostVars['latitude'], ENT_QUOTES, 'UTF-8')):NULL;
            $image_id = @$allPostVars['image']?@trim(htmlspecialchars($allPostVars['image'], ENT_QUOTES, 'UTF-8')):NULL;
            $pet_name = @$allPostVars['pet_name']?@trim(htmlspecialchars($allPostVars['pet_name'], ENT_QUOTES, 'UTF-8')):NULL;
            $status = 0; // default
            $isLostReport = 1; //Lost Report


            if (!preg_match("/^-?\d{1,3}\.{1}\d*$/",$longitude) ||!preg_match("/^-?\d*\.{1}\d*$/", $latitude)||!InputValidator::isValidStringInput($pet_name,255,0) ||!InputValidator::isValidStringInput($image_id,255,0) || !InputValidator::isValidStringInput($latitude,255,0)|| !InputValidator::isValidStringInput($longitude,255,0)||($category == 3 && !InputValidator::isValidStringInput($others,255,0) )|| !InputValidator::isValidStringInput($characteristics,5000,0)) {
                $app->render(400, ['Status' => 'Invalid input.' ]);
                return;
            }

            if ( !InputValidator::isValidStringInput($email,255,0) && !InputValidator::isValidEmail($email)) {
            	$app->render(400, ['Status' => 'Invalid input.' ]);
            	return;
            }

			if (!InputValidator::isValidStringInput($fullName,255,0) ) {
				$app->render(400, ['Status' => 'Invalid input.' ]);
				return;
			}    

			if (!InputValidator::isValidStringInput($number,10,8) || !preg_match("/^[0-9]{8,10}$/",$number) ) {
				$app->render(400, ['Status' => 'Invalid input.' ]);
				return;
			}      

			$freq = ["Once or twice","Often","Always"];
			$cate = ["Dog","Cat","Bird","Others"];
			$point = $latitude.",".$longitude;

			if ($category == 4) {
				$category = $others;
			} else {
				$category = $cate[$category];
			}

            $report = new \spotted\models\Report();
            $report->is_lost = $isLostReport;
            $report->status = $status;
            $report->frequency = $freq[$frequency];
            $report->category = $category;
            $report->characteristics = $characteristics;
            $report->full_name = $fullName;
            $report->email = $email;
            $report->number = $number;
            $report->pet_name = $pet_name;
            $report->longitude = $longitude;
            $report->latitude = $latitude;
            $report->save();

            if ($report) {
				$image = \spotted\models\Image::where('publicId','=',$image_id)->first();
				if ($image) {
            		$image->report_id = $report->id;
            		$image->save();
            	}
            	echo json_encode($report, JSON_UNESCAPED_SLASHES);
            } else {
				throw new \Exception('Error!');
            }

        } catch (\Exception $e) {
        	echo $e;
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
    }
}

?>