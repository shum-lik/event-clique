<?php
// $Id: friendconnect.inc,v 1.1.2.2 2010/01/20 02:55:23 davereid Exp $

function friendconnect_add_login() {
  global $user;

  $settings = array(
    'join_url' => url('friendconnect/join'),
    'logout_url' => url('logout'),
    'clean_url' => variable_get('clean_url', 0),
    'current_uid' => $user->uid,
    'current_fcid' => isset($user->friendconnect_id) ? $user->friendconnect_id : 0,
    'current_url' => isset($_REQUEST['destination']) ? $_REQUEST['destination'] : $_GET['q'],
  );
  drupal_add_js(array('friendconnect' => $settings), 'setting');
  drupal_add_js('FC_LoadFriendConnect();', 'inline', 'footer');
}

function friendconnect_add_socialbar() {
  $socialbar = friendconnect_var('socialbar');
  if ($socialbar['enabled']) {
    $comments = friendconnect_var('comments');
    $settings = array(
      'socialbar' => array(
        'settings' => array(
          'showWall' => $comments['enabled'] ? 'true' : 'false',
          'scope' => $comments['scope'],
          'allowAnonymousPost' => $comments['anonymous'] ? 'true' : 'false',
        ),
        'style' => array(
          'POSITION' => $socialbar['position'],
          'DEFAULT_COMMENT_TEXT' => $comments['default_text'],
          'HEADER_TEXT' => $comments['header_text'],
        ),
      ),
    );
    drupal_add_js(array('friendconnect' => $settings), 'setting');
    drupal_add_js('FC_LoadSocialBar();', 'inline', 'footer');
  }
}

/**
 * Local account login, create account if it does not exist.
 */
function friendconnect_join() {
  global $user;

  // if already logged in, sorry we cannot proceed!
  if ($user->uid) {
    drupal_set_message(t('You are already logged in to this site! We currently support only auto-creation of new accounts. Linking existing accounts to Google Friend Connect is not supported at this time.'), 'error');
    return drupal_access_denied();
  }

  // we need 'id' and 'name' always!
  if (!isset($_GET['fcid']) || !isset($_GET['fcname'])) {
    drupal_set_message(t('Required Google Friend Connect arguments not provided.'), 'error');
    return drupal_access_denied();
  }

  // default return to home page
  $_GET += array(
    'fcto' => url(),
  );

  watchdog('friendconnect', 'ID: @id<br />Name: @name<br />@Image: @image<br />To: @to', array('@id' => $_GET['fcid'], '@name' => $_GET['fcname'], '@image' => $_GET['fcimage'], '@to' => $_GET['fcto']), WATCHDOG_DEBUG);

  // login the old/new visitor into the local site
  $uname = friendconnect_local_user($_GET['fcid'], $_GET['fcname']);
  $status = friendconnect_register_user($_GET['fcid'], $uname, $_GET['fcimage']);
  if ($status != 0) return $status;

  // update visitor profile image
  //friendconnect_local_image($user->uid, $_GET['fcimage']);

  // take the user back to the original page
  drupal_goto($_GET['fcto']);
}

/**
 * Fetch an unique username for the given friendconnect id.
 */
function friendconnect_local_user($fcid, $fcname) {
  // if previously registered, reuse local account
  if ($uid = db_result(db_query("SELECT uid FROM {friendconnect} WHERE fcid = '%s'", $fcid))) {
    return db_result(db_query("SELECT name FROM {users} WHERE uid = %d", $uid));  // this should be the valid mapped user name
  }

  // create unique username for new visitor (no atomicity guarantees whatsoever)
  $existing_names = array('googlefriendconnect');
  $new_name = drupal_strtolower(str_replace(' ', '', $fcname));
  $query = db_query("SELECT name FROM {users} WHERE LOWER(name) LIKE '%s%%'", $new_name);
  while ($existing_name = db_result($query)) {
    $existing_names[] = $name;
  }

  $uname = $new_name;
  $counter = 0;
  while (in_array($uname, $existing_names)) {
    $counter = $counter + 1;
    $uname = $new_name . '_' . $counter;
  }
  return $uname;
}

/**
 * Register the unique username for the given friendconnect id.
 */
function friendconnect_register_user($fcid, $uname, $picture = '') {
  global $user;

  // let us try logging in the user now ..
  if (user_external_login_register($uname, 'friendconnect') != NULL) {
    drupal_set_message(t('Auto-login as user @user failed! Please try again later.', array('@user' => $uname)), 'error');
    return drupal_access_denied();
  }

  // register new username with friend connect id
  if (db_result(db_query("SELECT 1 FROM {friendconnect} WHERE fcid = '%s'", $fcid))) {
    $result = db_query("INSERT INTO {friendconnect} (fcid, uid, picture, creation_time) VALUES('%s', %d, '%s', %d)", $fcid, $user->uid, $picture, time());
  }

  return 0;  // all okay indicator
}

/**
 * Update the profile image for the user.
 */
/*function friendconnect_local_image($uid, $image) {
  $result = drupal_http_request($image);
  if ($result->code == 200 && $result->headers['Content-Type'] == 'image/png') {
    $dst = file_directory_path();
    if (variable_get('user_pictures_path', ''))
      $dst .= '/' . variable_get('user_pictures_path', '');
    $filename = 'fc_' . $uid . '.png';
    $dst .= '/' . $filename;
    $status = file_save_data($result->data, $dst, FILE_EXISTS_REPLACE);

    // ignore if image fetch/save fails, we can try again during next login
    if ($status != '0') {
      $query = 'UPDATE {users} SET picture="%s" WHERE uid=%d';
      $queryResult = db_query($query, $status, $uid);
    }
  }
}*/
