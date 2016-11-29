<?php
namespace App\Controller;
use Member;

class Members extends Controller{
	static function member_check( $req, $res, $args ){
		$PostMember = $req->getParsedBody();
		
		$Member = new Member($PostMember);
		$aMember = self::toArrayModel( $Member );
		return $res->withJson( $aMember );
	}
	
}
