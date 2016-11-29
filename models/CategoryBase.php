<?php 
class CategoryBase extends Model{ 
	
	static $validates_uniqueness_of = array(
			array( 'short_description', 'message' => 'Diese Kategorie gibt es schon!' )
	);
	
	static $validates_presence_of = [
			['short_description', 'message' => 'Die Kategorie fehlt'],
			['minref', 'message' => 'Mindestanzahl Richter fehlt'],
	];
	
	static $validates_numericality_of = [
			['mingroup', 'greater_than_or_equal_to' => 0, 'message'=>'Muss 0 oder positiv sein!' ],
			['maxgroup', 'greater_than_or_equal_to' => 0, 'message'=>'Muss 0 oder positiv sein!' ],
			['minage', 'greater_than' => 0, 'message'=>'Muss grösser als 0 sein!' ],
			['maxage', 'greater_than_or_equal_to' => 0, 'message'=>'Muss 0 oder positiv sein!' ],
			['minref', 'greater_than' => 0, 'message'=>'Muss grösser als 0 sein!', ],
			['maxref', 'greater_than_or_equal_to' => 0, 'message'=>'Muss 0 oder positiv sein!' ],
	];
}


