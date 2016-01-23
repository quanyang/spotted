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
	public static function newStrayReport() {
        $app = \Slim\Slim::getInstance();
        try {
            $allPostVars = $app->request->post();
            $characteristics =  @$allPostVars['characteristics']?@trim(htmlspecialchars($allPostVars['characteristics'], ENT_QUOTES, 'UTF-8')):NULL;
            $number = @$allPostVars['number']?@trim(htmlspecialchars($allPostVars['number'], ENT_QUOTES, 'UTF-8')):NULL;
            $frequency = @$allPostVars['frequency']?@trim(htmlspecialchars($allPostVars['number'], ENT_QUOTES, 'UTF-8')):0; // 0,1,2
            $category = @$allPostVars['category']?@trim(htmlspecialchars($allPostVars['category'], ENT_QUOTES, 'UTF-8')):0; //0,1,2,3 dog cat bird others
            $others = @$allPostVars['others']?@trim(htmlspecialchars($allPostVars['others'], ENT_QUOTES, 'UTF-8')):NULL;
            $fullName = @$allPostVars['fullName']?@trim(htmlspecialchars($allPostVars['fullName'], ENT_QUOTES, 'UTF-8')):NULL;
            $email = @$allPostVars['email']?@trim(htmlspecialchars($allPostVars['email'], ENT_QUOTES, 'UTF-8')):NULL;
            $longitude = @$allPostVars['longitude']?@trim(htmlspecialchars($allPostVars['longitude'], ENT_QUOTES, 'UTF-8')):NULL;
            $latitude = @$allPostVars['latitude']?@trim(htmlspecialchars($allPostVars['latitude'], ENT_QUOTES, 'UTF-8')):NULL;
            $image_id = @$allPostVars['image']?@trim(htmlspecialchars($allPostVars['image'], ENT_QUOTES, 'UTF-8')):NULL;
            $status = 0; // default
            $isLostReport = 0; //Stray Report

            if (!InputValidator::isValidStringInput($image_id,255,0) || !InputValidator::isValidStringInput($latitude,255,0)|| !InputValidator::isValidStringInput($longitude,255,0)|| !InputValidator::isValidStringInput($others,255,0)|| !InputValidator::isValidIntValBetween($category,0,3)|| !InputValidator::isValidIntValBetween($frequency,0,2) || !InputValidator::isValidStringInput($characteristics,5000,0)) {
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

            $report  = new \spotter\models\Report();
            $user = \parser\models\User::where('email','=',$_SESSION['email'])->first();

            $job_application = \parser\models\Application::where('job_id','=',$job->id)->where('user_id','=',$user->id)->first();

            if ($user && $job && !$job_application) {
                $job_application = new \parser\models\Application();
                $job_application->user_id = $user->id;
                $job_application->job_id = $job->id;
                $job_application->contact = $telephone;
                $job_application->resume_path = $resume_path;
                $job_application->save();
				shell_exec("/usr/bin/java -jar ../../parser.jar './resume-uploads/$resume_path' '$user->id' '$job->id' '$job_application->id' >/dev/null 2>/dev/null &");
                echo json_encode($job_application, JSON_UNESCAPED_SLASHES);
            } else {
                throw new \Exception('Error!');
            }

        } catch (\Exception $e) {
            $app->render(500, ['Status' => 'An error occurred.' ]);
        }
    }
}

?>