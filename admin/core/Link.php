<?php 

class Adapti_plug_Link{
	private static $scheme = 'http';
    
    /*if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
      private static $scheme = 'https';
    }
    else {
      private static $scheme = 'http';
    }*/
    
	private static $host = 'www.adapti.me';

	private static $api = '/API/public';
	private static $core = '';
	private static $public = '/Public2';
	private static $user = '/Dashboard';
	private static $admin = '/Admin/web';

    function __construct() {
        if(isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
          $this->scheme = 'https';
        }
        else {
          $this->scheme = 'http';
        }
    }
        
	public static function route($path, $type){
		if(substr($path, 0, 1) != '/') $path = '/' . $path;
		return self::$scheme . '://' . self::$host . self::${$type} . $path;
	}
    
    public static function adminroute($path){
		if(substr($path, 0, 1) != '/') $path = '/' . $path;
		return self::$scheme . '://app.adapti.me/admin' . $path;
	}
    
    public static function apiroute($path){
		if(substr($path, 0, 1) != '/') $path = '/' . $path;
		return self::$scheme . '://api.adapti.me/public' . $path;
	}
    
    public static function backapiroute($path){
		if(substr($path, 0, 1) != '/') $path = '/' . $path;
        $url = self::$scheme . '://api.adapti.me/public' . $path;
		return $url;
	}
    
    public static function coreroute($path){
		if(substr($path, 0, 1) != '/') $path = '/' . $path;
        $url = self::$scheme . '://dev.adapti.me' . $path;
		return $url;
	}
    
    public static function cdnroute($path){
		if(substr($path, 0, 1) != '/') $path = '/' . $path;
        $url = self::$scheme . '://cdn.adapti.me' . $path;
		return $url;
	}
}

 ?>