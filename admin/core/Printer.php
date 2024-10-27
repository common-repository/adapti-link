<?php 
require_once(__DIR__ . '/Api.php');

class Adapti_Printer{

	public static function content($v){
		
        /**** Setting Operators/Criteri ****/
        $strOperators = $v->get('operators');
        
		foreach(json_decode($strOperators) as $type => $value){
			foreach($value as $category => $ok){
				$operators[] = [ 'type' => $type, 'category' => $category ];
			}
		}
        
        /**** GET ORDERING ****/
        $sendData = [
                'source_type' => 'WORDPRESS',
				'content' => $v->get('id'),
				'operators' => json_encode($operators)
			];
		$content = Adapti_Api::get('content', [
			'method' => 'POST',
			'data' => $sendData
		]);

		$response = json_decode($content);
        
		if(count($response) > 0 && count($response[0]->content) > 0){
            /**** Print first version ****/
			return adapti_content_by_id(intval($response[0]->content[0]->data));
		}
		else{
			return get_the_content();
		}
	}

	public static function alert($msg = 'Something happened.', $type = 'updated', $msg_domain = null){
		if(!isset($msg_domain)) $msg_domain = strtolower(str_replace(' ', '_', $msg));
		
		require(__DIR__ . '/../partials/wp-alert.php');
	}
}

 ?>