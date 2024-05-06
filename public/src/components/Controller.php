<?php 
namespace components;

class Controller {
    
    public function render($path, $param = []) {
        if (strpos($path, DIRECTORY_SEPARATOR) == 0){
            $dir = App::$app->getWebRootSrcDir() . DIRECTORY_SEPARATOR . $path;
        } else {
            $dir = App::$app->getWebRootSrcDir() . DIRECTORY_SEPARATOR 
                . "views" . DIRECTORY_SEPARATOR . $path;
        }
        extract($param);
        require $dir . '.php';
    }
}

