<?php 
namespace components;

use components\Request;
use components\exceptions\NotFoundHttpException;
use components\Db;

class App {
    
    static public $app;
    
    public Db $db;
    
    //TODO implements ServerRequestInterface
    protected  Request $request;
    
       
    function __construct($param = [], $db = null) 
    {
        try {
            $this->db = $db ?? new Db($param['db']);
            
        } catch (\Throwable $e) {
            print "Error: " . $e->getMessage();
            throw $e;
        }
    }
    
    public static function getInstance($param)
    {
        if (is_null(self::$app)) {
            self::$app = new self($param);
        }
        return self::$app;
    }
    
    
    
    public function run(): void
    {
        //$response = $this->handle();
        $this->handle();

    }

    public function handle(?Request $request = null)
    {
        try {
            if (!$request) {
                $request = new Request();
            }
            $this->request = $request;
            //TODO Response return
            $result = $this->runAction($route, $params);
        } catch (\Throwable $e) {
            //TODO catch all
            print "Error: " . $e->getMessage();
            throw $e;
        }
        
        return $result;
    }

    
    public function runAction() {
        //TODO resolve other controllers
        $class = \controllers\MainController::class;
        $method = basename($this->request->getPathInfo()) ?: 'default';
        $args = $this->request->getQueryParams();

        if (!$method || !method_exists($class, $method)) {
            throw new NotFoundHttpException();
        }
        call_user_func(array(new $class, $method), $args);
    }
    
    
    public function getRequest() {
        return $this->request;
    }
    
    
    public function getWebRootDir() {
        return dirname(__DIR__, 2);
    }
    
    public function getWebRootSrcDir() {
        return $this->getWebRootDir() . DIRECTORY_SEPARATOR . 'src';
    }
    
}



spl_autoload_register(function ($className, $srcDir = 'src')
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    
    require "$srcDir/" . $fileName;
});


function dd($d)
{
    echo '<pre>';
    print_r($d); 
    exit;
}

