<?php
namespace App\Controller;
use Code;
use CodeCmd;
use CodeCmdText;

class Codes extends Controller{
	
	static function search( $req, $res, $args ){
		$conditions = self::searchPrepare( $req->getParsedBody() );
		
		$codes = Code::find('all', array('conditions' => $conditions ) );
		$ret = [];
		foreach($codes as $Code){
			$aCode = $Code->to_array();
			$aCode['password'] = '';
			$ret[] = $aCode;
		}
		return $res->withJson( $ret );
	}
		
	static function get( $req, $res, $args ) {
		$Code = Code::find($args['code_id'] );
		if( $Code ){
			return $res->withJson( $Code->to_array() );
		}
		return $res->withJson( [] );
	} 
	static function delete( $req, $res, $args ) {
		$Code = Code::find($args['code_id'] );
		$Code->delete();
		return $res->withJson( $Code );
	} 
	static function post( $req, $res, $args ) {
		$PostCode = $req->getParsedBody();
		fb($PostCode);
		if( $code_id = $PostCode['id'] ){
			$Code = Code::find($code_id);
			if( !count($Code) ) return $res->withStatus(404);
			$Code->update_attributes( $PostCode );
		}
		else{
			$Code = new Code($PostCode);
			$Code->save();
		}
		
		$aCode = self::toArrayModel( $Code );
		return $res->withJson($aCode);
	} 
	
	static function search_cmd( $req, $res, $args ){
		$conditions = self::searchPrepare( $req->getParsedBody() );
		
		$code_cmds = CodeCmd::find('all', array('conditions' => $conditions ) );
		$ret = [];
		foreach($code_cmds as $CodeCmd){
			$aCodeCmd = $CodeCmd->to_array();
			$ret[] = $aCodeCmd;
		}
		return $res->withJson( $ret );
	}
		
	static function search_text( $req, $res, $args ){
		$search = $req->getParsedBody();
		$search['is_deleted'] = '0';
		$conditions = self::searchPrepare( $search );
		$join = 'join code_cmds on code_cmds.id = code_cmd_texts.code_cmd_id';
		$code_texts = CodeCmdText::find('all', array('joins'=>$join, 'conditions' => $conditions, 'order'=>'cmd,text' ) );
		$ret = [];
		foreach($code_texts as $CodeCmdText){
			$aCodeCmdText = $CodeCmdText->to_array();
			$aCodeCmdText['code_cmd'] = $CodeCmdText->code_cmd->to_array();
			$ret[] = $aCodeCmdText;
		}
		return $res->withJson( $ret );
	}
		
	static function get_cmd( $req, $res, $args ) {
		$CodeCmd = CodeCmd::find($args['code_cmd_id'] );
		if( $CodeCmd ){
			return $res->withJson( $CodeCmd->to_array() );
		}
		return $res->withJson( [] );
	} 
	
	static function get_cmd_text( $req, $res, $args ) {
		$CodeCmdText = CodeCmdText::first(array('conditions'=>array('id = ?', $args['code_cmd_text_id'] ) ) );
		if( $CodeCmdText ){
			return $res->withJson( $CodeCmdText->to_array() );
		}
		return $res->withJson( [] );
	} 
	
	static function delete_cmd( $req, $res, $args ) {
		$CodeCmd = CodeCmd::find($args['code_cmd_id'] );
		$CodeCmd->delete();
		return $res->withJson( $CodeCmd );
	} 
	
	static function post_cmd_text( $req, $res, $args ) {
		$PostCodeCmdText = $req->getParsedBody();
		if( $code_cmd_text_id = $PostCodeCmdText['id'] ){
			$CodeCmdText = CodeCmdText::first(array('conditions'=>array('id = ?', $code_cmd_text_id ) ) );
			fb($CodeCmdText);
			if( !$CodeCmdText || $CodeCmdText->is_deleted ) return $res->withStatus(404);
			$CodeCmdText->update_attributes( $PostCodeCmdText );
		}
		else{
			$CodeCmdText = new CodeCmdText($PostCodeCmdText);
			$CodeCmdText->save();
		}
		$aCodeCmdText = self::toArrayModel( $CodeCmdText );
		return $res->withJson($aCodeCmdText);
	} 
	
	static function post_cmd( $req, $res, $args ) {
		$PostCodeCmd = $req->getParsedBody();
		if( $code_cmd_id = $PostCodeCmd['id'] ){
			$CodeCmd = CodeCmd::find($code_cmd_id);
			if( !count($CodeCmd) ) return $res->withStatus(404);
			$CodeCmd->update_attributes( $PostCodeCmd );
		}
		else{
			$CodeCmd = new CodeCmd($PostCodeCmd);
			$CodeCmd->save();
		}
		
		$CodeCmdText = CodeCmdText::find('first', array('conditions'=>array('code_cmd_id = ?', $CodeCmd->id) ) );
		if( !$CodeCmdText ){
			$CodeCmdText = new CodeCmdText( array( 'code_cmd_id'=>$CodeCmd->id, 'text'=>$CodeCmd->description ) );
			$CodeCmdText->save();
		}
		$aCodeCmd = self::toArrayModel( $CodeCmd );
		return $res->withJson($aCodeCmd);
	} 
	
	static function get_codes( $req, $res, $args ){
		$codetexts = Code::getCodes( $args['codename']);
		$acodes = [];
		foreach( $codetexts as $CodeCmdText ) $acodes[] = $CodeCmdText->to_array();
		return $res->withJson( $acodes);
	}
	static function get_code_cmds( $req, $res, $args ){
		$Code = Code::find( $args['code_id']);
		$codecmds = $Code->code_cmds;
		$acodes = [];
		foreach( $codecmds as $CodeCmd ) $acodes[] = $CodeCmd->to_array();
		return $res->withJson( $acodes);
	}
}
