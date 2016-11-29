<?php 
class Model extends ActiveRecord\Model{ 
	
	static function stripArray( $aModel ){
		$callModel = get_called_class();
		$Model = new $callModel();
		$attrs = array_keys($Model->attributes());
		
		foreach( $aModel as $key=>$value ) if( !in_array($key, $attrs) ) unset($aModel[$key]);
		return $aModel;
	}
}


