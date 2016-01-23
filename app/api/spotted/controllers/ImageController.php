<?php

namespace spotted\controllers;

use \spotted\Models\Image;

/**
 * Class PhotoController
 *
 * Controller class for all retrieval and creation of food items`
 * @package spotted
 **/
class ImageController extends \spotted\controllers\Controller {

    const COMPRESSION_RATE = 100;

    public function __construct() {
    }

    public function uploadImage($host, $rawFiles){
        $app = \Slim\Slim::getInstance();
        //Check if upload is successful
        if ($this->isFileValid($rawFiles)) {

            $photoFile = $rawFiles['photoData'];
            $fileInfo = $this->createFileInfo($photoFile, $host);
            $imageFileType = $fileInfo['EXTENSION'];
            //Make sure filetype is image 
            $check = getimagesize($photoFile['tmp_name']);
            if (strcmp($fileInfo['TYPE'] , 'image') !== 0 && $check === false && 
                $imageFileType != "jpg" && $imageFileType != "png" 
                && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $app->render(415, ['Status' => 'Inappropriate file format']);
            } else {
                    //  Save to directory and db

                $result = $this->saveToFile($currUserId, $photoFile['tmp_name'], $fileInfo['MIME-TYPE'], $fileInfo['DIRECTORY'], $fileInfo['ROUTE']);
                if(is_null($result)) {
                    $app->render(500, array("Status" => "Unable to save file"));
                } else {
                    $app->render(200, array("Status" => "OK", "photoURL" => $result->photoUrl));
                }
            }

        } else {
            $app->render(400, ['Status' => 'File missing or not uploaded properly']);
        }

    }

    private function isFileValid($file) {
        return ( isset($file['photoData']) && $file['photoData']['error'] == UPLOAD_ERR_OK );
    }

    private function createFileInfo($photoFile, $host){
        $app = \Slim\Slim::getInstance();
        $info = array();
        $info['DIRECTORY'] = 'uploads/';
        $info['ROUTE'] = $host . $app->urlFor('photo') .'/';
        $info['MIME-TYPE'] = $photoFile['type'];
        list($type, $ext) = split('/', $info['MIME-TYPE']);
        $info['TYPE'] = $type;
        $info['EXTENSION'] = $ext;
        return $info;
    }

    public function downloadPhoto($uniqueId) {
        $app = \Slim\Slim::getInstance();

        if (empty($uniqueId) || is_null($uniqueId) || strlen($uniqueId) > 255 ) {
            $app->render(400, ['Status' => 'input is invalid.' ]);
            return;
        }

        $fileName = Image::getFileNameFromUniqueId($uniqueId);

        if (empty($fileName)) {
            //default
            return;
        }
        $dir = 'uploads/';
        $fileUri = $dir . $fileName;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $fileUri);
        finfo_close($finfo);
        if (file_exists($fileUri) && getimagesize($fileUri)) {
            $this->displayGraphicFile($app,$fileUri,$mime);
        } else {
           // $this->downloadPhoto('608aba97aabaabf9c136b781343caf4f');
        }
    }

    private function displayGraphicFile ($app,$graphicFileName, $fileType='jpeg') {
      $fileModTime = filemtime($graphicFileName);
      // Getting headers sent by the client.
      $headers = $this->getRequestHeaders();
      // Checking if the client is validating his cache and if it is current.
      if (isset($headers['If-Modified-Since']) && (strtotime($headers['If-Modified-Since']) == $fileModTime)) {

        // Client's cache IS current, so we just respond '304 Not Modified'.
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 304);
      } else {
        // Image not cached or cache outdated, we respond '200 OK' and output the image.
        header('Content-Disposition: inline; filename="'.basename($graphicFileName).'"');
        $app->response()->header("Content-Type", $fileType);
        header('Last-Modified: '.gmdate('D, d M Y H:i:s', $fileModTime).' GMT', true, 200);
        header('Content-transfer-encoding: binary');
        header('Content-length: '.filesize($graphicFileName));
        readfile($graphicFileName);
      }
    }

    // return the browser request header
    // use built in apache ftn when PHP built as module,
    // or query $_SERVER when cgi
    private function getRequestHeaders() {
      if (function_exists("apache_request_headers")) {
        if($headers = apache_request_headers()) {
          return $headers;
        }
      }
      $headers = array();
      // Grab the IF_MODIFIED_SINCE header
      if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
        $headers['If-Modified-Since'] = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
      }
      return $headers;
    }

    public function getFileNameFromUniqueId($uniqueId) {
        try {

            $Image = \spotted\models\Image::where('uniqueId','=',$uniqueId)->first();
            if (!$Image) {
                return null;
            }

            return $Image['fileName'];
        } catch (\Exception $e) {
            return null;
        }
    }

    private static function saveToDatabase($fileName, $route, $userId) {
        try {
            $db = \Db::getInstance();

            $req = $db->prepare('INSERT INTO Photo (`fileName`, `uniqueId`, `userId`) VALUES (:fileName, :uniqueId, :userId);');
            $success = false;
            $uniqueId = "";
            while (!$success) {
                $uniqueId = md5(uniqid("", true));
                $success = $req->execute(array(
                    'fileName' => $fileName,
                    'userId' => $userId,
                    'uniqueId' => $uniqueId
                    ));
            }

            $id = $db->lastInsertId();

            if ($id > 0 && $success) {
                return new PhotoModel($id, $route . $uniqueId, $userId);
            }
            return null;
        } catch (\PDOException $e) {
            return null;
        }
    }

    public static function saveToFile($owner, $data, $mime, $dir, $route) {
        $id = md5(uniqid("", true));
        list($mime, $ext) = split("/", $mime);
        $name = "img-" . $id . '.' . $ext;

    //make sure filename is unique
        while (file_exists($dir.$name)) {
            $id = md5(uniqid("", true));
            $name = "img-" . $id . '.' . $ext;
        }

        $compressPath =   PhotoModel::compress($data,$dir . $name);
        PhotoModel::createThumbnail(512,400,$compressPath,$compressPath);

        if (file_exists($compressPath)) {
            return PhotoModel::saveToDatabase($name, $route, $owner);
        } else {
            return null;
        }
    }

    private static function createThumbnail($new_width,$new_height,$uploadDir,$moveToDir)
    {
        $path = $uploadDir;

        $mime = getimagesize($path);

        if($mime['mime']=='image/png'){ $src_img = imagecreatefrompng($path); }
        if($mime['mime']=='image/jpg'){ $src_img = imagecreatefromjpeg($path); }
        if($mime['mime']=='image/jpeg'){ $src_img = imagecreatefromjpeg($path); }
        if($mime['mime']=='image/pjpeg'){ $src_img = imagecreatefromjpeg($path); }

        $old_x          =   imageSX($src_img);
        $old_y          =   imageSY($src_img);

        $thumb_w    =   $new_width;
        $thumb_h    =   $old_y*($new_width/$old_x);


        $dst_img        =   ImageCreateTrueColor($thumb_w,$thumb_h);

        imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y); 


    // New save location
        $new_thumb_loc = $moveToDir;

        if($mime['mime']=='image/png'){ $result = imagepng($dst_img,$new_thumb_loc,100); }
        if($mime['mime']=='image/jpg'){ $result = imagejpeg($dst_img,$new_thumb_loc,100); }
        if($mime['mime']=='image/jpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,100); }
        if($mime['mime']=='image/pjpeg'){ $result = imagejpeg($dst_img,$new_thumb_loc,100); }

        if ($thumb_h > $new_height) {
            $to_crop_array = array('x' =>0 , 'y' => (($thumb_h-$new_height)/2.0), 'width' => $new_width, 'height'=> $new_height);
            $dst_img = imagecrop($dst_img, $to_crop_array);
            $result = imagejpeg($dst_img,$new_thumb_loc,100);
        }

        imagedestroy($dst_img); 
        imagedestroy($src_img);

        return $result;
    }

    private static function compress($source,$dest) {
        $info = getimagesize($source); 
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source); 
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source); 
        } 

        imagejpeg($image, $dest, PhotoModel::COMPRESSION_RATE); 
        imagedestroy($image);
        return $dest;
    }

}
