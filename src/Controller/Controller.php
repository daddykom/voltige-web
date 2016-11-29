<?php
namespace App\Controller;

class Controller{
	static function toArrayModel( $Model ){
		$aModel = $Model->to_array();
		if( $Model->is_valid() ) $aModel['_errors'] = array();
		else $aModel['_errors'] = $Model->errors->get_raw_errors();
		return $aModel;
	}
	
	static function searchPrepare( $search ){
		if( !$search ) $search = [];
		$q = '1=1';
		$conditions = [];
		foreach( $search as $name=>$val ){
			if( $val == '' ) continue;
			$q .= " and $name like ?";
			$conditions[] = str_replace( '*', '%', "$val*" );
		}
		array_unshift( $conditions, $q );
		return $conditions;
	}
}