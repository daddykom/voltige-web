<?php
namespace App\Controller;
use Subscription, SubscriptionBeing, Member;

class Subscriptions extends Controller{

	static function longesub_all( $req, $res, $args ){
		$longesubs = [];
		$members = \SubscriptionBeing::all([
				'conditions'=>['subscription_beings.show_id = ? and role = "LA" ', $args['show_id']],
				'joins'=>'join members on members.id = subscription_beings.role_foreign_id',
				'select'=>'members.*, subscription_id'
		]);
		foreach( $members as $Member) $longesubs[$Member->subscription_id][] = Member::stripArray( $Member->to_array() );
		$subscriptions = Subscription::all([
				'conditions'=>['subscription_beings.show_id = ? and role = ? and description != ""', $args['show_id'], 'M'],
				'joins'=>'join subscription_beings on subscription_id = subscriptions.id join members on members.id = subscription_beings.role_foreign_id',
				'select'=>'subscription_beings.*, subscriptions.*, members.*'
		]);
		$ret = [];
		foreach( $subscriptions as $Subscription){
			$aSubscription = $Subscription->to_array();
			if( array_key_exists( $Subscription->subscription_id, $longesubs ) ) $aSubscription['longesubs'] = $longesubs[$Subscription->subscription_id];
			if( array_key_exists( $Subscription->subscription_id, $longesubs ) ) fb($aSubscription);
			$ret[] = $aSubscription;
		}
		return $res->withJson( $ret );
	}
	
	static function longsub_get_approx( $req, $res, $args ){
		$names = explode(' ', $args['description'] );
		$pre = [];
		$condition = [];
		foreach( $names as $name ){
			$pre[] = 'name like ?';
			$pre[] = 'prename like ?';
			$condition[] = "%$name%";
			$condition[] = "%$name%";
		}
		array_unshift( $condition, join(' or ', $pre ) );
		$approx_members = Member::all([
				'conditions'=>$condition,
				'order'=>'name,prename'
		]);
			
		$ret = [];
		foreach( $approx_members as $Member ) $ret[] = $Member->to_array();
		return $res->withJson( $ret );
	}
	
	
	static function search( $req, $res, $args ){
		$conditions = self::searchPrepare( $req->getParsedBody() );
		$conditions[0] .= ' and role = "M"';
		$joins = 'join categories on categories.id = subscriptions.category_id
					join subscription_beings on subscription_id = subscriptions.id
					join members on members.id = subscription_beings.role_foreign_id';
		$select = 'subscriptions.*, subscription_beings.*, members.*, categories.*';
		$order = 'club, name';
		
		$subscriptions = Subscription::find('all', array('conditions' => $conditions, 'joins'=>$joins, 'select'=>$select, 'order'=>$order ) );
		$ret = [];
		foreach($subscriptions as $Subscription){
			$aSubscription = $Subscription->to_array();
			$ret[] = $aSubscription;
		}
		return $res->withJson( $ret );
	}
		
	static function get( $req, $res, $args ){
		$Subscription =  \Subscription::find($args['subscription_id']);
		$aSubscription = $Subscription->to_array();
		$aSubscription['Category'] = $Subscription->category->to_array();
		$aSubscription['subscription_beings'] = [];
		foreach( $Subscription->subscription_beings as $SubscriptionBeing ){
			$aSubscriptionBeing = $SubscriptionBeing->to_array();
			if( $SubscriptionBeing->member ) $aSubscriptionBeing['Member'] = $SubscriptionBeing->member->to_array();
			if( $SubscriptionBeing->horse ) $aSubscriptionBeing['Horse'] = $SubscriptionBeing->horse->to_array();
			$aSubscription['subscription_beings'][$SubscriptionBeing->role][] = $aSubscriptionBeing;
		}
		return $res->withJson( $aSubscription );
	}
		
	static function delete( $req, $res, $args ) {
		$Subscription = Subscription::find($args['subscription_id'] );
		$Subscription->delete();
		return $res->withJson( $Subscription );
	} 
	static function post( $req, $res, $args ) {
		$PostSubscription = $req->getParsedBody();
		if( $subscription_id = $PostSubscription['id'] ){
			$Subscription = Subscription::find($subscription_id);
			if( !count($Subscription) ) return $res->withStatus(404);
			$Subscription->update_attributes( $PostSubscription );
		}
		else{
			$Subscription = new Subscription($PostSubscription);
			$Subscription->save();
		}
		
		$aSubscription = self::toArrayModel( $Subscription );
		return $res->withJson($aSubscription);
	} 
	static function post_member( $req, $res, $args ) {
		
		
		// Transactin Closure
		$PostSubscription = $req->getParsedBody();
		$members = $PostSubscription['longesubs'];
		fb($PostSubscription);
		
		$actmembers = [];
		foreach( \SubscriptionBeing::all([
				'conditions'=>['subscription_id = ? and role = "LA"', $PostSubscription['subscription_id']], 
				'select'=>'distinct role_foreign_id'] ) as $SubscriptionBeing ) $actmembers[] = $SubscriptionBeing->role_foreign_id;

		\Subscription::transaction(function() use ( &$PostSubscription, &$members, $actmembers ){
			foreach( $members as $key=>$aMember ){
				$aMember = \Member::stripArray($aMember);
				$Member = new \Member($aMember);
				if( !$Member->id ){
					$Member->fn_no = 0;
					$Member->show_id = $PostSubscription['show_id'];
					$Member->save(false);
				}
				$i = array_search( $Member->id, $actmembers );
				if( ( $i !== false ) ) unset($actmembers[$i]);
				else{	
					$SubscriptionBeing = new \SubscriptionBeing( \SubscriptionBeing::stripArray( $PostSubscription) );
					$SubscriptionBeing->id = 0;
					$SubscriptionBeing->role_foreign_id = $Member->id;
					$SubscriptionBeing->role = 'LA';
					$SubscriptionBeing->save(false);
					fb($SubscriptionBeing);
				}
				$members[$key] = $Member->to_array();
			}
			if( count($actmembers) ) \Subscription::delete_all( 
					['conditions' =>['subscription_id = ? and category_id = ? and role_foreign_id in (?) and role = "LA"',
							$PostSubscription['subscription_id'], $PostSubscription['category_id'], $actmembers ]]);
			
			
			fb(\Subscription::connection()->last_query);
			$PostSubscription['TestVAR'] = 'Charley';
			$PostSubscription['longesubs'] = $members;
			return true;
		});
		fb($PostSubscription);
		return $res->withJson($PostSubscription);
	} 
	
	static function updateSubscriptionBeings( $req, $res, $args ){
		$records = $req->getParsedBody();
		\SubscriptionBeing::transaction(function() use ( &$records ){
			foreach( $records as $aSubscriptionBeing ){
				$SubscriptionBeing = SubscriptionBeing::find($aSubscriptionBeing['id']);
				$SubscriptionBeing->set_attributes( $SubscriptionBeing::stripArray( $aSubscriptionBeing ) );
				$SubscriptionBeing->save( false );
			}
			return true;
		});
		return self::get( $req, $res, ['subscription_id'=>$records[0]['subscription_id']] );
	}
}
