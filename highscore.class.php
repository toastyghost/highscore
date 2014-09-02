<?php

class highscore {
		
	public function __construct() {}
		
	public function highscore() {}
	
	public function submit(
		$user_id = null,
		$song_id = null,
		$difficulty = null, 
		$score = null,
		$start_time = null,
		$end_time = null)
	{
		
		if(
			!is_null($user_id) &&
			!is_null($song_id) &&
			!is_null($difficulty) &&
			!is_null($score) &&
			!is_null($start_time) &&
			!is_null($end_time)
		) {
			define('DRUPAL_ROOT', '/home/khameleo/public_html/drupal');
			
			require(DRUPAL_ROOT . '/sites/all/themes/srwooly/teacher-codes.php');
			
			# TODO: Split these from single encoded parameter that includes date, then check that
			#		supplied date is today's or yesterday's before executing the full node build.
			#		(Split operation is the reverse of combine one from node-game template.)
			$user_id = teacher_code($user_id);
			$song_id = teacher_code($song_id);
			
			require(DRUPAL_ROOT . '/includes/bootstrap.inc');
			drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
			
			define('DRUPAL_ADMIN_UID', 1);
			define('DEFAULT_TIMEZONE', 'America/Chicago');
			
			$user = user_load($user_id);
			
			if (!empty($user)) {
				$node = new stdClass();
				$node->title = 'Score for ' . $user->name . ' @ ' . $start_time;
				$node->type = 'score';
				$node->language = LANGUAGE_NONE;
				$node->uid = DRUPAL_ADMIN_UID;
				$node->status = DRUPAL_BOOL_FALSE;
				$node->promote = DRUPAL_BOOL_FALSE;
				$node->comments = DRUPAL_COMMENTS_OFF;
				
				$node->field_user[$node->language][] = array(
					'uid' => $user_id,
					'access' => 1,
					'user' => $user
				);
				
				$node->field_song[$node->language][] = array(
					'nid' => $song_id,
					'access' => 1,
					'node' => node_load($song_id)
				);
				
				$node->field_score[$node->language][]['value'] = $score;
				$node->field_difficulty[$node->language][]['value'] = $difficulty;
				
				$start_time = strtotime($start_time);
				$end_time = strtotime($end_time);
				
				$node->field_time[$node->language][] = array(
					'value' => $start_time,
					'value2' => $end_time,
					'timezone' => DEFAULT_TIMEZONE,
					'data_type' => 'date',
					'db' => array(
						'value' => new DateObject($start_time, DEFAULT_TIMEZONE),
						'value2' => new DateObject($end_time, DEFAULT_TIMEZONE)
					)
				);
				
				node_save($node);
				
				if (!empty($node)) {
					return 'Successfully created node. (Variable method)';
				} else {
					return 'Successfully built node, but encountered a Drupal error when attempting to save. (Variable method)';
				}
			} else {
				return 'Unable to identify user. Score was not stored.';
			}
		} else {
			return 'All parameters are required. Score was not stored.';
		}
	}
	
	public function submit_array($postback = array()){
		
		if (!empty($postback)) {
			define('DRUPAL_ROOT', '/home/khameleo/public_html/drupal');
			require(DRUPAL_ROOT . '/includes/bootstrap.inc');
			drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
			
			define('DRUPAL_ADMIN_UID', 1);
			define('DEFAULT_TIMEZONE', 'America/Chicago');
			
			$user = user_load($postback['user_id']);
			
			if(!empty($user)){
				$node = new stdClass();
				$node->title = 'Score for ' . $user->name . ' @ ' . $postback['start_time'];
				$node->type = 'score';
				$node->language = LANGUAGE_NONE;
				$node->uid = DRUPAL_ADMIN_UID;
				$node->status = DRUPAL_BOOL_FALSE;
				$node->promote = DRUPAL_BOOL_FALSE;
				$node->comments = DRUPAL_COMMENTS_OFF;
				
				$node->field_user[$node->language][] = array(
					'uid' => $postback['user_id'],
					'access' => 1,
					'user' => $user
				);
				
				$node->field_song[$node->language][] = array(
					'nid' => $postback['song_id'],
					'access' => 1,
					'node' => node_load($postback['song_id'])
				);
				
				$node->field_score[$node->language][]['value'] = $postback['score'];
				$node->field_difficulty[$node->language][]['value'] = $postback['difficulty'];
				
				$start_time = strtotime($postback['start_time']);
				$end_time = strtotime($postback['end_time']);
				
				$node->field_time[$node->language][] = array(
					'value' => $start_time,
					'value2' => $end_time,
					'timezone' => DEFAULT_TIMEZONE,
					'data_type' => 'date',
					'db' => array(
						'value' => new DateObject($start_time, DEFAULT_TIMEZONE),
						'value2' => new DateObject($end_time, DEFAULT_TIMEZONE)
					)
				);
				
				if (node_save($node)) {
					return 'Successfully created node. (Array method)';
				}else{
					return 'Successfully built node, but encountered a Drupal error when attempting to save. (Array method)';
				}
			} else {
				return 'Unable to identify user. Score was not stored.';
			}
		} else {
			return 'Empty postback set. Score was not stored.';
		}
	}
}

?>