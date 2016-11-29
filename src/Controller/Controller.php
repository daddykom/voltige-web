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
	

	static function all( $req, $res, $args ) {
		$result = CModel::find('all');
		$ret = [];
		foreach( $result as $CModel ){
			$ret[] = $CModel->to_array();
		}
		return $res->withJson( $ret );
	}
	static function get( $req, $res, $args ) {
		$CModel = CModel::find($args['id'] );
		if( $CModel ){
			return $res->withJson( $CModel->to_array() );
		}
		return $res->withJson( [] );
	}
	
	static function delete( $req, $res, $args ) {
		$CModel = CModel::find( $args['id'] );
		$CModel->delete();
		return $res->withJson( $CModel );
	}
	static function post( $req, $res, $args ) {
		$PostCModel = $req->getParsedBody();
		if( $id = $PostCModel['id'] ){
			$CModel = CModel::find($id);
			if( !count($CModel) ) return $res->withStatus(404);
			$CModel->update_attributes( $PostCModel );
		}
		else{
			$CModel = new CModel($PostCModel);
			$CModel->save();
		}
	
		$aCModel = self::toArrayModel( $CModel );
		return $res->withJson($aCModel);
	}
	
}