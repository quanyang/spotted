<?php

namespace spotted;

use spotted\models;
use Slim\Slim;
use SlimJson\Middleware;
/**
 * Class App
 *
 * Main class of the REST API
 * @package relive
 */
class App {
    /**
     *  Construct a new App instance
     */
    public function __construct() {
        $this->app = new Slim();
        $this->startSession();
        $this->setupMiddleWare();
        $this->addDefaultRoutes();
    }

    /**
     *  Run the App instance
     */
    public function run() {
      $this->app->run();
    }

    private $app;

    private function startSession() {
        if(!session_id()) {
            session_start();
            date_default_timezone_set('Asia/Singapore');
        }
    }

    private function setupMiddleWare() {
        $this->app->contentType('application/json');
        $this->app->add(new Middleware(array(
            'json.status' => false,
            'json.override_error' => true,
            'json.override_notfound' => true
        )));
    }

    private function addDefaultRoutes() {
        $app = $this->app;
        $app->response->headers->set('Access-Control-Allow-Origin', '*');

        //  http://relive.space/api
        $app->group('', function() use ($app) {

            //  GET: /api
            $app->get('/', function() use ($app) {
                $app->render(200, ['Status' => 'Running']);
            });

            /*

            $app->group('/hashtag', function() use ($app) {
                //get events related to hashtag
                $app->get('/:hashtag', 'relive\controllers\HashtagController::getEventWithHashtag');
            });            
            $app->group('/event', function() use ($app) {
                // Get /api/event{?startAt,limit,orderBy}
                $app->get('', 'relive\controllers\EventController::getEvents');
                // Post /api/event
                $app->post('', 'relive\controllers\EventController::create');
                //  Get /api/event/indexes
                $app->get('/indexes', 'relive\controllers\EventController::getSearchIndexes');
                //  Get /api/event/recent
                $app->get('/recent', 'relive\controllers\EventController::getRecentEvents');
                //  Get /api/event/trending
                $app->get('/trending', 'relive\controllers\EventController::getTrendingEvents');
                // Route /api/event/:event_id
                $app->group('/:event_id', function() use ($app) {
                    //  Get /api/event/:event_id
                    $app->get('', 'relive\controllers\EventController::getEventWithId');
                    // Route /api/event/:event_id/post
                    $app->group('/post', function() use ($app) {
                        // Get {startAt,orderBy}
                        $app->get('', 'relive\controllers\EventController::getPostsForEvent');
                    });
                    // Route /api/event/:event_id/report
                    $app->group('/report', function() use ($app) {
                        // Post
                        $app->post('', 'relive\controllers\EventController::reportPostFromEvent');
                    });
                    // Route /api/event/:event_id/hashtag
                    $app->group('/hashtag', function() use ($app) {
                        // Get
                        $app->get('', 'relive\controllers\EventController::getHashtagforEvent');
                        // Post
                        $app->post('', 'relive\controllers\EventController::addHashtagToEvent');
                        // Delete
                        //$app->delete('/:hashtag',);
                    });
                });
                
            });*/
        });
    }
}
