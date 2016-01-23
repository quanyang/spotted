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
            

            if (!InputValidator::isValidStringInput($description,5000,0)|| !InputValidator::isValidStringInput($number,10,8) || !preg_match("/^[0-9]{8,10}$/",$number) ) {
                $app->render(400, ['Status' => 'Invalid input.' ]);
                return;
            }

            $job  = \parser\models\Job::where('id','=',$job_id)->first();
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