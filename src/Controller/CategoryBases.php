<?php
namespace App\Controller;
use CategoryBase;
use Category;

class CategoryBases extends Controller{
	
	
	static function all( $req, $res, $args ) {
		$category_bases = CategoryBase::find('all');
		$ret = [];
		foreach( $category_bases as $CategoryBase ){
			$ret[] = $CategoryBase->to_array();
		}
		return $res->withJson( $ret );
	} 
	static function get( $req, $res, $args ) {
		$CategoryBase = CategoryBase::find($args['category_base_id'] );
		if( $CategoryBase ){
			return $res->withJson( $CategoryBase->to_array() );
		}
		return $res->withJson( [] );
	} 
	
	static function delete( $req, $res, $args ) {
		$CategoryBase = CategoryBase::find( $args['category_base_id'] );
		$CategoryBase->delete();
		return $res->withJson( $CategoryBase );
	} 
	static function post( $req, $res, $args ) {
		$PostCategoryBase = $req->getParsedBody();
		if( $show_id = $PostCategoryBase['id'] ){
			$CategoryBase = CategoryBase::find($show_id);
			if( !count($CategoryBase) ) return $res->withStatus(404);
			$CategoryBase->update_attributes( $PostCategoryBase );
		}
		else{
			$CategoryBase = new CategoryBase($PostCategoryBase);
			$CategoryBase->save();
		}
		
		$aCategoryBase = self::toArrayModel( $CategoryBase );
		return $res->withJson($aCategoryBase);
	} 
	
}
