<?php 

class Adapti_Human{

	private static $messages = [
		'init_msg' => 'Please, follow the installation steps of <a href="{{url}}">Adapti</a> to enable personalization on your website.',

		'best_version' => 'Displaying best version according to',
		'add_version' => 'Please, add a tagged version to allow best version display.',
		'delete_version' => 'Are you sure to delete this version ?',
		
		'tag_miss' => 'A value is missing for the tag',
		'tag_default' => 'No tags.',

		'delete_tooltip' => 'Delete the version',
		'edit_tooltip' => 'Edit the version',
		'edit_last' => 'Edit last version',

		'adaptations' =>  '{{nb}}',
		'rule_name' => 'Adapted Page - <a href="{{url}}">{{name}}</a>',
		'credits' => '{{nb}}'
	];

	public static function buildOperators($operators){
		$str = '';
		$index = 0;
		if($operators){
			foreach($operators as $type => $category){
				foreach($category as $label => $ok){
					$index ++;

					$str .= ucfirst(__($label));
					if($type == 'tastes'){
						$str .= ' '.ucfirst(__($type));
					}

					$str .= ', ';
				}
			}

			$str = self::clean($str, $index);
		}
		if(count($operators) > 0){
			$str = self::msg('best_version') . ' ' . $str;
		}
		else{
			$str = self::msg('add_version');
		}

		return $str;
	}

	public static function buildTags($tags){
		$str = '';

		if(count($tags) > 0){
			$index = 0;
			foreach($tags as $tag){
				$index ++;

				$str .= ucfirst(__($tag->value));

				$str .= ', ';
			}

			$str = self::clean($str, $index);
		}
		else{
			$str = self::msg('tag_default');
		}


		return $str;
	}

	private static function clean($str, $parts){
		$str = substr($str, 0, -2);
		if($parts > 1){
			$find = ', ';
			$replace = ' & ';
			$result = preg_replace(strrev("/$find/"),strrev($replace),strrev($str),1);
			$str = strrev($result);
		}
		return $str;
	}

	public static function msg($label, $vars = []){
		$msg = __(self::$messages[$label], $label);
		foreach($vars as $key => $value){
			$msg = str_replace('{{' . $key . '}}', $value, $msg);
		}
		return $msg;
	}
}

 ?>