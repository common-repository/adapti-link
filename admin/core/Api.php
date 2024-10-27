<?php 
require_once(__DIR__ . '/Link.php');

class Adapti_Api{
	public static $ajax = 'page_versions';

	public static function ajaxVersionLink($id){
		return admin_url( 'admin-ajax.php' ) . '?action=' . self::$ajax . '&page_id=' . $id;
	}

	private static function headerValue($key, $value){
		$str = $key . ':';
		if(is_array($value)){
			foreach($value as $key => $val){
				$str .= $key.'='.urlencode($val).';';
			}
			$str = substr($str, 0, -1);
		}
		else{
			$str .= $value;
		}
		return $str;
	}

	private static function buildContext($opt){
		$context = [
			'http' => [
				'method' => array_key_exists('method', $opt) ? $opt['method'] : 'GET',
				'header' => self::headerValue('Referer', adapti_url()),
			]
		];

		if($context['http']['method'] != 'GET'){
			$context['http']['content'] = http_build_query($opt['data']);
		}

		return $context;
	}

	public static function get($path, $opt = []){
		if(!isset($opt['data'])) $opt['data'] = [];
		$opt['data']['user_link'] = $_COOKIE['adapti_link'];
		$opt['data']['token'] = get_option('adapti_config_token');
        
		$context = self::buildContext($opt);

		$stream = stream_context_create($context);

		if($context['http']['method'] == 'GET'){
			$path .= '?' . http_build_query($opt['data']);
		}
        $url = Adapti_plug_Link::backapiroute('/api/' . $path);
		$content = @file_get_contents($url, false, $stream);
		$headers = $http_response_header;
        
//        echo "<pre>";
//        print_r(json_decode($content));
//        echo "</pre>";
        
		if(substr($headers[0], 9, 3) == '200'){
			return $content;
		}
		else{
			return "Connection Error";
		}		
	}
}

 ?>