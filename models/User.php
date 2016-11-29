<?php 

class User extends Model{ 
	static $validates_uniqueness_of = array(
		array( 'userid', 'message' => 'Diesen Benutzer gibt es schon!' )
		);
	
	static $validates_presence_of = array(
		array('userid', 'message' => 'Die BenutzerId fehlt')
		,array('name', 'message' => 'Der Benutzername fehlt')
		,array('password', 'message' => 'Das Passwort fehlt')
		,array('group', 'message' => 'Die Benutzergruppe fehlt')
		);
}

