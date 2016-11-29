<?php

class Code extends Model{
	static $has_many = array(
			array('code_cmds')
	);
	static function getCmd($id){
		$CodeCmdText = CodeCmdText::first(array('conditions'=>array('id = ?', $id ) ) );
		return $CodeCmdText->code_cmd->cmd;
	}
	static function getCodes( $codename ){
		$join = 'join code_cmds on code_cmds.code_id = codes.id join code_cmd_texts on code_cmd_texts.code_cmd_id = code_cmds.id';
		$sel = 'code_cmd_texts.*';
		$condition = array('codes.codename = ?',$codename );
		$codes = self::all(array('joins' => $join, 'select'=>$sel, 'conditions'=>$condition));
		return $codes;
	}
}