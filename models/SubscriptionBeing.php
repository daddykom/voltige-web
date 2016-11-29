<?php 
class SubscriptionBeing extends Model{ 
	static $belongs_to = [
			['member', 'foreign_key'=>'role_foreign_id'],
			['horse', 'foreign_key'=>'role_foreign_id'],
	];
}


