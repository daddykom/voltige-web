<?php

class CodeCmd extends Model{
	static $belongs_to = array(array('code'));	
	static $has_many = array(
			array('code_cmd_text')
	);
}