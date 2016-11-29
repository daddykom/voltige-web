<?php 
class Member extends Model{ 
	static $validates_presence_of = array(
		array('name', 'message' => 'Der Name fehlt')
		);
	
	public function validate(){
		if( $this->licence != 'G' && !$this->prename ) $this->errors->add('prename', 'Der Vorname fehlt');
	}
}


