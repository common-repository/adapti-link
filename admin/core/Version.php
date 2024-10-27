<?php 

class Adapti_Version{
	private $id;
	private $dad;
	private $versions;
	private $lastEdited;
	private $operators;

	public static $meta = [
		'version' => 'adapti_version_referer',
		'operators' => 'adapti_operators',
		'tag' => 'adapti_tags',
		'rule' => 'rule_id'
	];

	public function __construct($id = null){
		if(!isset($id)) $id = get_the_ID();
		$this->id = $id;
	}

	private function buildDad(){
		$dad = get_post_meta( $this->id, self::$meta['version'], true );

		if(empty($dad)){
			$dad = $this->id;
		}
		else{
			$dad = intval($dad);
		}

		$this->dad = $dad;
	}

	private function buildVersions(){
		global $wpdb;

		$before = [];
		$after = [];

		$before[] = get_post($this->get('dad'), 'ARRAY_A');

		$meta_key = self::$meta['version'];
		$versions = array_merge($before, $wpdb->get_results("SELECT post.* FROM wp_postmeta as meta INNER JOIN wp_posts as post ON meta.post_id = post.id WHERE meta.meta_key = '$meta_key' AND meta.meta_value = '$this->dad' AND post.post_status != 'trash'", ARRAY_A), $after);

		foreach ($versions as $index => $version) {
			if($index == 0){
				$versions[$index]['label'] = __('Initial');
			}
			else{
				require_once(__DIR__ . '/Human.php');

				$tags = json_decode(get_post_meta($version['ID'], self::$meta['tag'], true));
				$versions[$index]['label'] = Adapti_Human::buildTags($tags);
				$versions[$index]['tag_miss'] = $this->checkTags($tags);
				if(count($versions[$index]['tag_miss']) > 0){
					$versions[$index]['tag_miss_label'] = Adapti_Human::buildTags($versions[$index]['tag_miss']);
				}
			}
		}

		$this->versions = $versions;
	}

	private function buildLastEdited(){
		$versions = $this->get('versions');
		$last_edited = $versions[0];
		foreach($versions as $version){
			if($last_edited['post_modified'] < $version['post_modified']){
				$last_edited = $version;
			}
		}

		$this->lastEdited = $last_edited;
	}

	private function buildOperators(){
		$this->operators = get_post_meta($this->get('dad'), self::$meta['operators'], true );
	}

	private function buildRule_id(){
		$this->rule_id = get_post_meta($this->get('dad'), self::$meta['rule'], true );
	}

	public function get($var){
		if(!isset($this->$var)){
			$builder = 'build' . ucfirst($var);
			$this->$builder();
		}
		return $this->$var;
	}

	public function setRuleId($id){
		$this->setMeta($this->get('dad'), self::$meta['rule'], $id);
	}

    
	public function setVersion($version){
		$this->setMeta($this->id, self::$meta['version'], $version);
	}

    /************************************/
    /*****   SETTING CRITERIA       *****/
    /************************************/
	public function setTag($value){
		$this->setMeta($this->id, self::$meta['tag'], $value);
        
//        $versionId = $this->id;
//        $sendArray = [
//			'rule_id' => $this->get('rule_id'),
//            'type' => $operator['type'],
//            'category' => $operator['category'],
//            'ratio' => 1,
//            'methodtype' => "CRITERIA_SAVE"
//		];
	}

    /************************************/
    /*****   SETTING OPERATORS      *****/
    /************************************/
	public function setOperators(){
		$operators = [];
        /***** Getting all tags and finding operators *****/
		foreach($this->get('versions') as $version){
			$tags = json_decode(get_post_meta( $version['ID'], self::$meta['tag'], true ));
			if(count($tags) > 0){
				foreach($tags as $tag){
					if(!isset($operators[$tag->type])) $operators[$tag->type] = [];
					$operators[$tag->type][$tag->category] = true;
				}
			}
		}
        // Setting in WP meta
		$this->setMeta($this->dad, self::$meta['operators'], json_encode($operators));
        
        /***** Saving on API *****/
        foreach($operators as $type => $operator) {
            foreach($operator as $opcategory => $thing) {
                $finalOperator = [];
                $finalOperator['type'] = $type;
                $finalOperator['category'] = $opcategory;
                
                $response = json_decode(Adapti_Api::get('rule',[ 
                    'method' => "POST", 
                    'data' => $this->apiSerializeOperator($finalOperator) 
                ]));
            }
        }
        
	}
    
    /*********************************************/
    /*****   GETTING OPERATOR INFO TO SEND   *****/
    /*********************************************/
	public function apiSerializeOperator($operator){
		require_once(__DIR__ . '/Human.php');

		return [
			'rule_id' => $this->get('rule_id'),
            'type' => $operator['type'],
            'category' => $operator['category'],
            'ratio' => 1,
            'methodtype' => "OPERATOR_SAVE"
		];
	}

	public function setMeta($post_id, $meta_key, $meta_value){
		$old_value = get_post_meta( $post_id, $meta_key, true );

		if ( $meta_value && '' == $old_value )
			add_post_meta( $post_id, $meta_key, $meta_value, true );

		elseif ( $meta_value && $meta_value != $old_value )
			update_post_meta( $post_id, $meta_key, $meta_value );

		elseif ( '' == $meta_value && $old_value )
			delete_post_meta( $post_id, $meta_key, $old_value );
	}

	public function checkTags($versionTags){
		$version = [];

		if(isset($versionTags)){
			foreach($versionTags as $tag){
				$version[] = $tag->category;
			}
		}

		$miss = [];
		if($this->get('operators')){
			foreach(json_decode($this->get('operators')) as $dadType => $dadTag){
				foreach($dadTag as $tag => $valid){
					if(!in_array($tag, $version)){
						$missed = new stdClass;
						$missed->value = $tag;
						$miss[] = $missed;
					}
				}
			}
		}

		return $miss;
	}

    /************************************/
    /*****   SAVING RULE TO ADAPTI  *****/
    /************************************/
	public function apiSave(){
		require_once(__DIR__ . '/Api.php');

		if($this->get('rule_id')){
            // Edit a rule
			$method = 'PUT';
		}
		else {
            // Add a rule
			$method = 'POST';
		}

		$response = json_decode(Adapti_Api::get('rule', [ 'method' => $method, 'data' => $this->apiSerializeRule("CREATE_RULE") ]));

		if($response->created == true) {
			$this->setRuleId($response->id);
		}
	}
    
    /*****************************************/
    /*****   GETTING RULE INFO TO SEND   *****/
    /*****************************************/
	public function apiSerializeRule($methodtype = null){
		require_once(__DIR__ . '/Human.php');

		return [
			'name' => Adapti_Human::msg('rule_name', [ 
				'name' => get_the_title($this->get('dad')),
				'url' => get_page_link($this->get('dad'))
			]),
			'description' => Adapti_Human::buildOperators(json_decode($this->get('operators'), true)),
			'id' => $this->get('rule_id'),
			'page' => get_page_link($this->get('dad')),
			'content' => $this->get('dad'),
			'force' => true,
            'methodtype' => $methodtype,
            'sourcetype' => "WORDPRESS"
		];
	}
    
    /************************************/
    /*****   SAVING  VERSION        *****/
    /************************************/
    public function saveVersion($tags) {
        $versionName = "";
        $sendArray = [
			'rule_id' => $this->get('rule_id'),
            'name' => $versionName,
            'elementType' => "WORDPRESS_PAGE",
            'htmlCode' => $this->id, // Page id of version
            'tags' => $tags, // criterias
            'methodtype' => "VERSION_SAVE"
		];
        $response = json_decode(Adapti_Api::get('rule',[ 
                'method' => "POST", 
                'data' => $sendArray 
            ]));
    }
}

 ?>