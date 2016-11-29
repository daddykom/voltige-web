<?php
namespace App\Controller;
use Show, Category, Horse, Member, Subscription, SubscriptionBeing;

class Upload extends Controller{
	
	static function upload( $req, $res, $args ){
		$files = $req->getUploadedFiles();
		$Show = new Show();
		$errormsg = '';
		
		\Show::transaction(function() use ( &$Show, &$files, &$errormsg ){
			$database = [];
			$description = [
				'Show' => [ 
								'clubno'=>'VEREINSNR', 
								'organization'=>'VERANSTALTER', 
								'organizer'=>'AP', 
								'address'=>'ADRESSE', 
								'showno'=>'VERANSTALTUNGSNR', 
								'showcity'=>'VERANSTALTUNGORT', 
								'from_dt'=>'DATUM VON', 
								'to_dt'=>'DATUM BIS' 
							]
				,'Category' => [ 
								'no'=>'NR', 
								'test'=>'DURCHGANG', 
								'title'=>'TITEL', 
								'start_dt'=>'DATUM', 
								'type'=>'TYP', 
								'fee'=>'NENNGELD'
							]
				,'Horse' => [
								'name'=>'NAME', 
								'club'=>'VEREIN', 
								'owner'=>'BESITZER', 
								'age'=>'ALTER', 
								'sex'=>'GESCHLECHT', 
								'color'=>'FARBE', 
								'headno'=>'KOPFNUMMER', 
								'internalno'=>'EINTRAGUNGSNR' 
							] 
				,'Member' => [ 
								'fn_no'=>'FN-NR', 
								'name'=>'NAME', 
								'prename'=>'VORNAME', 
								'club'=>'VEREIN', 
								'sex'=>'GESCHLECHT', 
								'birthyear'=>'JAHRGANG', 
								'category'=>'KLASSE', 
								'armno'=>'ARMNR', 
								'description'=>'BEMERKUNG'
							]
				,'GM-Member' => [ 
								'fn_no'=>'FN-NR', 
								'name'=>'GRUPPE', 
								'club'=>'VEREIN', 
								'category'=>'KLASSE', 
								'description'=>'BEMERKUNG'
				]
				,'GMember' => [ 
								'fn_no'=>'ID', 
								'name'=>'N', 
								'prename'=>'V', 
								'birthyear'=>'J'
							]
				,'DMember' => [ 
								'fn_no'=>'FN-NR', 
								'name'=>'NAME', 
								'prename'=>'VORNAME', 
								'club'=>'VEREIN', 
								'armno'=>'ARMNR'
							]
				,'LMember' => [ 
								'fn_no'=>'FN-NR LONGE', 
								'name'=>'LONGE'
							]
				,'Subscription' => [ 
						'subscription_id'=>'TEILNEHMER ID',
						'category_id'=>'PRUEFUNG ID' 
								 ]
				,'SubscriptionBeing' => [ 
								'subscription_id'=>'TEILNEHMER ID',
								'horse_id'=>'PFERDE ID'
								 ]
			];
			 
			foreach( $files as $file ){
				if( $file->getError() !== UPLOAD_ERR_OK ) return $res->withStatus(400);
				$fp = fopen($file->file, 'r');
				if( !$fp ) return $res->withStatus(400);
				$end = true;
				$Show = new Show();
				$cnt = 0;
				while( ($data = fgetcsv($fp, 0, "|")) !== FALSE ) {	
					$cnt += 1;
					if( !$data[0] ){
						$end = true;
						continue;
					}
					if( $end ){
						$table = $data[0];
						$cnt = -2;
						$end = false;
						continue;
					}
					if( $cnt == -1 ) $header = self::prepHeader($data);
					if( $cnt < 0 ) continue;
					
					switch( $table ){
						case 'TURNIER':
							$database['Show'] = self::loadRecord( $description['Show'], $header, $data );
							break;
						case 'PRUEFUNGEN':
							$record = self::loadRecord( $description['Category'], $header, $data);
							$database['Category'][$data[0]] = $record;
							break;
						case 'PFERDE':
							$record = self::loadRecord($description['Horse'], $header, $data);
							$database['Horse'][$record['internalno']] = $record;
							break;
						case 'NENNUNGEN (EINZEL)':
							$record = self::loadRecord( $description['Subscription'], $header, $data);
							$subscription_id = $record['subscription_id'];
							$database['Subscription'][$subscription_id] = $record;  // Subscription
							$record = self::loadRecord( $description['LMember'], $header, $data);
							$database['LMember'][$record['fn_no']] = $record;  // Longe
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'L';
							$sbeing['role_foreign_id'] = $record['fn_no'];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'M';
							$sbeing['role_foreign_id'] = $database['MemberFnNo'][$subscription_id][0];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'H';
							$sbeing['role_foreign_id'] = $sbeing['horse_id'];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							break;
						case 'NENNUNGEN (DOPPEL)':
							$record = self::loadRecord( $description['Subscription'], $header, $data);
							$subscription_id = $record['subscription_id'];
							$database['Subscription'][$subscription_id] = $record;  // Subscription
							$record = self::loadRecord( $description['LMember'], $header, $data);
							$database['LMember'][$record['fn_no']] = $record;  // Longe
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'L';
							$sbeing['role_foreign_id'] = $record['fn_no'];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'D';
							$sbeing['role_foreign_id'] = $database['MemberFnNo'][$subscription_id][0];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'D';
							$sbeing['role_foreign_id'] = $database['MemberFnNo'][$subscription_id][1];
							$sbeing['pos'] = 2;
							$database['SubscriptionBeing'][] = $sbeing;
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'H';
							$sbeing['role_foreign_id'] = $sbeing['horse_id'];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							break;
						case 'NENNUNGEN (GRUPPEN)':
							$record = self::loadRecord( $description['Subscription'], $header, $data);
							$subscription_id = $record['subscription_id'];
							$database['Subscription'][$subscription_id] = $record;  // Subscription
							$record = self::loadRecord( $description['LMember'], $header, $data);
							$database['LMember'][$record['fn_no']] = $record;  // Longe
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'L';
							$sbeing['role_foreign_id'] = $record['fn_no'];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'M';
							$sbeing['role_foreign_id'] = $database['MemberFnNo'][$subscription_id][0];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
							$sbeing['role'] = 'H';
							$sbeing['role_foreign_id'] = $sbeing['horse_id'];
							$sbeing['pos'] = 1;
							$database['SubscriptionBeing'][] = $sbeing;
							
							for( $i = 1; $i <= 12; $i++ ){
								$record = self::loadRecord( $description['GMember'], $header, $data, "$i");
								if( !array_key_exists('fn_no', $record) || !$record['fn_no'] ) break;
								$database['GMember'][$record['fn_no']] = $record;  // Longe
								$sbeing = self::loadRecord( $description['SubscriptionBeing'], $header, $data);
								$sbeing['role'] = 'G';
								$sbeing['role_foreign_id'] = $record['fn_no'];
								$sbeing['pos'] = $i;
								$database['SubscriptionBeing'][] = $sbeing;
							}
							break;
						case 'TEILNEHMER (EINZEL)':
							$record = self::loadRecord($description['Member'], $header, $data);
							$database['Member'][$record['fn_no']] = $record;
							$database['MemberFnNo'][$data[0]] = [$record['fn_no']];
							break;
						case 'TEILNEHMER (GRUPPEN)': 
							$record = self::loadRecord($description['GM-Member'], $header, $data);
							$database['Member'][$data[0]] = $record;
							$database['MemberFnNo'][$data[0]] = [$data[0]];
							break;
						case 'TEILNEHMER (DOPPEL)':
							$temp = self::loadRecord($description['Member'], $header, $data );
							$category = $temp['category'];
							$record = self::loadRecord($description['DMember'], $header, $data, ' 1');
							$record['category'] = $category;
							if( array_key_exists( 'description', $temp ) ) $record['description'] = $temp['description'];
							$database['DMember'][$record['fn_no']] = $record;
							$database['MemberFnNo'][$data[0]] = [$record['fn_no']];
							$record = self::loadRecord($description['DMember'], $header, $data, ' 2');
							$record['category'] = $category;
							$database['DMember'][$record['fn_no']] = $record;
							$database['MemberFnNo'][$data[0]][1] = $record['fn_no'];
							break;
						default:
							break;
					}
				}
				fclose($fp);
			}
			$Show = Show::find_by_showno( $database['Show']['showno'] );
			if( $Show ){
				$errormsg = 'Dieser Turnier existiert schon. Sie müssen ihn zuerst löschen.';
				return true;
			}
			$Show = new Show($database['Show']);
			$Show->save();
			foreach( $database['Category'] as $key=>$aCategory ){
				$aCategory['show_id'] = $Show->id;
				$Category = new Category( $aCategory );
				$Category->save( false );
				$database['Category'][$key]['id'] = $Category->id;
			}
			foreach( $database['Horse'] as $key=>$aHorse ){
				$Horse = Horse::find_by_internalno( $aHorse['internalno'] );
				if( $Horse ) $Horse->set_attributes($aHorse);
				else $Horse = new Horse( $aHorse );
				$Horse->save( false );
				$database['Horse'][$key]['id'] = $Horse->id;
			}
			foreach( ['LMember','GMember','DMember','Member'] as $MemberKey ){
				foreach( $database[$MemberKey] as $key=>$aMember ){
					if( $aMember['fn_no'] ) $Member = Member::find_by_fn_no( $aMember['fn_no'] );
					else $Member = Member::first(['conditions'=>['name = ? and club = ?', $aMember['name'],$aMember['club']]]);
					
					if( $Member ){
						foreach( $aMember as $name=>$val ) if( $val===null ) unset( $aMember[$name] );
						$Member->set_attributes($aMember);
					}
					else $Member = new Member( $aMember );
					if( !array_key_exists('fn_no', $aMember) || !$aMember['fn_no'] ) $Member->show_id = $Show->id;
					else $Member->show_id = null;
					$Member->save(false);
					
					$database[$MemberKey][$key]['id'] = $Member->id;
				}
			}
			foreach( $database['Subscription'] as $key=>$aSubscription ){
				$aSubscription['show_id'] = $Show->id;
				$aSubscription['category_id'] = $database['Category'][$aSubscription['category_id']]['id'];
				$Subscription = new \Subscription( \Subscription::stripArray($aSubscription) );
				$Subscription->save(false);
				$database['Subscription'][$key]['id'] = $Subscription->id;
			}
			foreach( $database['SubscriptionBeing'] as $key=>$aSubscriptionBeing ){
				$aSubscriptionBeing['show_id'] = $Show->id;
				$aSubscriptionBeing['subscription_id'] = $database['Subscription'][$aSubscriptionBeing['subscription_id']]['id'];
				
				switch( $aSubscriptionBeing['role'] ){
					case 'H': 	$aSubscriptionBeing['role_foreign_id'] = $database['Horse'][$aSubscriptionBeing['role_foreign_id']]['id'];
								break;
					case 'G': 	$aSubscriptionBeing['role_foreign_id'] = $database['GMember'][$aSubscriptionBeing['role_foreign_id']]['id'];
								break;
					case 'L': 	$aSubscriptionBeing['role_foreign_id'] = $database['LMember'][$aSubscriptionBeing['role_foreign_id']]['id'];
								break;
					case 'D': 	$aMember = $database['DMember'][$aSubscriptionBeing['role_foreign_id']];
								$aSubscriptionBeing['role_foreign_id'] = $aMember['id'];
								$aSubscriptionBeing['role'] = 'M';
								$database['Subscription'][$aSubscriptionBeing['subscription_id']]['description'] = array_key_exists( 'description', $aMember ) ? $aMember['description'] : '';
								break;
					default: 	$aMember = $database['Member'][$aSubscriptionBeing['role_foreign_id']];
								$aSubscriptionBeing['role_foreign_id'] = $aMember['id'];
								$database['Subscription'][$aSubscriptionBeing['subscription_id']]['description'] = array_key_exists( 'description', $aMember ) ? $aMember['description'] : '';
				}
				$SubscriptionBeing = new SubscriptionBeing( SubscriptionBeing::stripArray( $aSubscriptionBeing ) );
				$SubscriptionBeing->save(false);
			}
			return true;
		});
		if( $errormsg ) return $res->withJson($errormsg)->withStatus(409);
			
		return $res->withJson($Show->to_array());
	}
	
	static function loadRecord( $recdesc, $header, $data, $postfix = '' ){
		$record = [];
		foreach( $recdesc as $key=>$field ){
			if( !array_key_exists( $field . $postfix, $header)) continue;
			$index =  $header[$field . $postfix];
			if( array_key_exists($index, $data) ) $record[$key] = $data[$index];
			//else $record[$key] = null;
		}
		return $record;
	}
	static function prepHeader( $header ){
		$ret = [];
		foreach( $header as $key=>$field ) $ret[$field] = $key;
		return $ret;
	}
}
