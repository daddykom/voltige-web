<?php
namespace App\Controller;
use User;

class Users extends Controller{
	
	static function search( $req, $res, $args ){
		$conditions = self::searchPrepare( $req->getParsedBody() );
		
		$users = User::find('all', array('conditions' => $conditions ) );
		$ret = [];
		foreach($users as $User){
			$aUser = $User->to_array();
			$aUser['password'] = '';
			$ret[] = $aUser;
		}
		return $res->withJson( $ret );
	}
		
	static function get( $req, $res, $args ) {
		$User = User::find($args['user_id'] );
		if( $User ){
			$User->password = '';
			return $res->withJson( $User->to_array() );
		}
		return $res->withJson( [] );
	} 
	static function delete( $req, $res, $args ) {
		$User = User::find($args['user_id'] );
		$User->delete();
		return $res->withJson( $User );
	} 
	static function post( $req, $res, $args ) {
		$PostUser = $req->getParsedBody();
		if( $user_id = $PostUser['id'] ){
			$User = User::find($user_id);
			if( !count($User) ) return $res->withStatus(404);
			if( !$PostUser['password'] ) unset($PostUser['password']);
			$User->update_attributes( $PostUser );
		}
		else{
			$User = new User($PostUser);
			$User->save();
		}
		
		$aUser = self::toArrayModel( $User );
		$aUser['password'] = '';
		return $res->withJson($aUser);
	} 
}
