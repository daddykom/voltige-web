<?php
namespace App\Controller;
use Show;
use Category;

class Shows extends Controller{
	
	static function actual( $req, $res, $args ){
		
		$Show = Show::first(['conditions'=>['from_dt >= current_date'], 'order'=>'from_dt' ]);
		if( !$Show ) return $res->withJson([]);
		return $res->withJson($Show->to_array());
	}
	static function search( $req, $res, $args ){
		$conditions = self::searchPrepare( $req->getParsedBody() );
		
		$shows = Show::find('all', array('conditions' => $conditions ) );
		$ret = [];
		foreach($shows as $Show){
			$aShow = $Show->to_array();
			$ret[] = $aShow;
		}
		return $res->withJson( $ret );
	}
	
	static function get( $req, $res, $args ) {
		$Show = Show::find($args['show_id'] );
		if( $Show ){
			return $res->withJson( $Show->to_array() );
		}
		return $res->withJson( [] );
	} 
	static function delete( $req, $res, $args ) {
		$Show = Show::find($args['show_id'] );
		$Show->delete();
		return $res->withJson( $Show );
	} 
	static function post( $req, $res, $args ) {
		$PostShow = $req->getParsedBody();
		
		if( $show_id = $PostShow['id'] ){
			$Show = Show::find($show_id);
			if( !count($Show) ) return $res->withStatus(404);
			$Show->update_attributes( $PostShow );
		}
		else{
			$Show = new Show($PostShow);
			$Show->save();
		}
		
		$aShow = self::toArrayModel( $Show );
		return $res->withJson($aShow);
	} 
	
}
