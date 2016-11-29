<?php 
class Subscription extends Model{ 
	static $has_many = [ 
			['subscription_beings', 'order'=>'role, pos']
			];
	static $belongs_to = [['Category']];
	
}


