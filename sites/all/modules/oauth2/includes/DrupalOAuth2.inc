<?php
// $Id: DrupalOAuth2.inc,v 1.1 2011/01/25 09:27:08 hswong3i Exp $

/**
 * @file
 * Drupal OAuth2 Implementation
 */

class DrupalOAuth2 extends OAuth2 {

  /**
   * Overrides OAuth2::__construct().
   */
  public function __construct($config = array()) {
    global $user;

    parent::__construct($config);

    // devel_execute_block_form also submit execute code in $_REQUEST['code'].
    if (!isset($_REQUEST['form_id']) || $_REQUEST['form_id'] !== 'devel_execute_block_form') {
      $result = NULL;

      // Hack to corresponding user if oauth_token provided.
      if (isset($_REQUEST['oauth_token'])) {
        $result = $this->getAccessToken($_REQUEST['oauth_token']);
      }
      // Hack to corresponding user if code provided.
      elseif (isset($_REQUEST['code'])) {
        $result = $this->getAuthCode($_REQUEST['code']);
      }
      // Hack to corresponding user if refresh_token provided.
      elseif (isset($_REQUEST['refresh_token'])) {
        $result = $this->getRefreshToken($_REQUEST['refresh_token']);
      }

      if ($result) {
        $this->loadUserCredentials($result['uid'], $result['name'], $result['sid']);
      }
    }
  }

  /**
   * Load the user based on uid and name, assign with corresponding session;
   * or create dummy user on-the-fly.
   *
   * @param $uid
   *   The user ID.
   * @param $name
   *   The user name.
   * @param $sid
   *   The target session ID to be set with.
   *
   * @return
   *   The user object after successful login.
   */
  protected function loadUserCredentials($uid, $name, $sid) {
    global $user;

    if (isset($uid) && isset($name) && !($account = user_load($uid))) {
      // We hack and create a dummy user placeholder if it is exist in
      // token tables but not yet a real user account, typically
      // happened when server running in proxy mode. Can't use
      // drupal_write_record() as uid is serial.
      db_query("INSERT INTO {users} (uid, name, created, access, login, status) VALUES (%d, '%s', %d, %d, %d, 1)", $uid, $name, time(), time(), time());
      $account = user_load($uid);
    }
    $user = $account;

    // Some client/servers, like XMLRPC, do not handle cookies, so
    // imitate it to make sess_read() function try to look for user,
    // instead of just loading anonymous user :).
    $session_name = session_name();
    if (!isset($_COOKIE[$session_name])) {
      $_COOKIE[$session_name] = $sid;
    }

    session_id($sid);
    return $user;
  }

  /**
   * Verify authorized scopes for end-user.
   *
   * @param $client_id
   *   The client identifier to be check with.
   * @param $required_scope
   *   The required scope within current request.
   * @param $uid
   *   The user ID.
   *
   * @return
   *   A list of all extended scopes besides authorized scopes of end-user.
   */
  public function verifyAuthorizedScopes($client_id, $required_scope, $uid) {
    $authorized_scope = $this->getAuthorizedScopes($client_id, $uid);
    $hidden_scope = $this->getHiddenScopes();

    if (!is_array($required_scope))
      $required_scope = explode(" ", $required_scope);
    $required_scope = array_unique(array_merge($required_scope, $hidden_scope));

    $scopes = $this->checkExtendedPermissions($required_scope, $authorized_scope);

    $extended_permissions = array();
    foreach ($scopes as $scope) {
      $stmt = db_query("SELECT os.nid, os.scope_id, os.hidden, os.bypass FROM {node} AS n INNER JOIN {oauth2_scopes} AS os ON n.vid = os.vid WHERE os.scope_id = '%s' AND n.status = 1", $scope);
      $extended_permissions[] = db_fetch_array($stmt);
    }

    return array_filter($extended_permissions);
  }

  /**
   * Get all hidden scopes that should be automatically included within blank
   * scope request.
   *
   * @return
   *   A list for all hidden scopes.
   */
  public function getHiddenScopes() {
    $stmt = db_query("SELECT os.scope_id FROM {node} AS n INNER JOIN {oauth2_scopes} AS os ON n.vid = os.vid WHERE os.hidden = %d AND n.status = 1", 1);
    $scopes = array();
    while ($result = db_fetch_array($stmt)) {
      $scopes[] = $result['scope_id'];
    }
    return $scopes;
  }

  /**
   * Get all authorized scopes for end-user.
   *
   * @param $client_id
   *   The client identifier to be check with.
   * @param $uid
   *   The user ID.
   *
   * @return
   *   A list for all authorized scopes for this end-user.
   */
  public function getAuthorizedScopes($client_id, $uid) {
    $stmt = db_query("SELECT scope_id FROM {oauth2_authorize} WHERE client_id = '%s' AND uid = %d", $client_id, $uid);
    $scopes = array();
    while ($result = db_fetch_array($stmt)) {
      $scopes[] = $result['scope_id'];
    }
    return $scopes;
  }

  /**
   * Set authorized scopes for end-user.
   *
   * @param $client_id
   *   The client identifier to be authorize.
   * @param $scopes
   *   The scopes as a list of space-delimited strings.
   * @param $uid
   *   The user ID.
   */
  public function setAuthorizedScopes($client_id, $scopes, $uid) {
    $scope_ids = explode(" ", $scopes);
    foreach ($scope_ids as $scope_id) {
      db_query("INSERT INTO {oauth2_authorize} (client_id, uid, scope_id) VALUES ('%s', %d, '%s')", $client_id, $uid, $scope_id);
    }
  }

  /**
   * Unset authorized scopes for end-user.
   *
   * @param $client_id
   *   The client identifier to be unauthorize.
   * @param $scopes
   *   The scopes as a list of space-delimited strings.
   * @param $uid
   *   The user id.
   */
  public function unsetAuthorizedScopes($client_id, $scopes, $uid) {
    $scope_ids = explode(" ", $scopes);
    foreach ($scope_ids as $scope_id) {
      db_query("DELETE FROM {oauth2_authorize} WHERE client_id = '%s' AND uid = %d AND scope_id = '%s'", $client_id, $uid, $scope_id);
    }
  }

  /**
   * Check if any extended permissions besides authorized scopes.
   *
   * @param $required_scope
   *   The target required scope within current request.
   * @param $authorized_scope
   *   Scopes that already authorized by end-user.
   *
   * @return
   *   A list with all extended permissions.
   */
  private function checkExtendedPermissions($required_scope, $authorized_scope) {
    // The required scope should match or be a subset of the available scope
    if (!is_array($required_scope))
      $required_scope = explode(" ", $required_scope);

    if (!is_array($authorized_scope))
      $authorized_scope = explode(" ", $authorized_scope);

    return array_filter(array_diff($required_scope, $authorized_scope));
  }

  /**
   * Expires all OAuth2.0 related tokens based on sid.
   *
   * @param $sid
   *   Session ID to be expires with.
   *
   * @return
   *   TRUE if successful, and FALSE if it isn't.
   */
  public function expireSession($sid) {
    // Purge tokens based on oauth_token if provided.
    if (isset($_REQUEST['oauth_token']) && !empty($_REQUEST['oauth_token'])) {
      $result = $this->getAccessToken($_REQUEST['oauth_token']);
      if ($result) {
        $sid = $result['sid'];
      }
    }

    // Purge tokens if session found.
    if ($sid) {
      if (!isset($_GET['redirect_uri'])) {
        // We hack $_REQUEST['redirect_uri'] so handle it custom logout.
        $result = db_fetch_array(db_query("SELECT oc.redirect_uri
          FROM {oauth2_access_tokens} AS oat
            INNER JOIN {oauth2_clients} AS oc ON oat.client_id = oc.client_id
            INNER JOIN {node} AS n ON oc.vid = n.vid
          WHERE oat.sid = '%s' AND n.status = 1", $sid));
        $_GET['redirect_uri'] = $result['redirect_uri'];
      }

      // Expirse all tokens base on this session_id.
      db_query("DELETE FROM {oauth2_access_tokens} WHERE sid = '%s'", $sid);
      db_query("DELETE FROM {oauth2_auth_codes} WHERE sid = '%s'", $sid);
      db_query("DELETE FROM {oauth2_refresh_tokens} WHERE sid = '%s'", $sid);

      // Also manually destroy user session.
      if (session_id($sid) != $sid) {
        session_start();
        session_id($sid);
      }

      return session_destroy();
    }

    return FALSE;
  }

  /**
   * Implements OAuth2::checkClientCredentials().
   */
  public function checkClientCredentials($client_id, $client_secret = NULL) {
    $stmt = db_query("SELECT oc.client_secret FROM {node} AS n INNER JOIN {oauth2_clients} AS oc ON n.vid = oc.vid WHERE oc.client_id = '%s' AND n.status = 1", $client_id);
    $result = db_fetch_array($stmt);
    return ($client_secret !== NULL && $result['client_secret'] == $client_secret) ? TRUE : FALSE;
  }

  /**
   * Implements OAuth2::getRedirectUri().
   */
  public function getRedirectUri($client_id) {
    $stmt = db_query("SELECT oc.redirect_uri FROM {node} AS n INNER JOIN {oauth2_clients} AS oc ON n.vid = oc.vid WHERE oc.client_id = '%s' AND n.status = 1", $client_id);
    $result = db_fetch_array($stmt);
    return $result['redirect_uri'] !== FALSE ? $result['redirect_uri'] : NULL;
  }

  /**
   * Implements OAuth2::getAccessToken().
   */
  public function getAccessToken($oauth_token) {
    $stmt = db_query("SELECT oat.client_id, oat.sid, oat.expires, oat.scope, u.uid, u.name
      FROM {oauth2_access_tokens} AS oat
        INNER JOIN {users} AS u ON oat.uid = u.uid
      WHERE oat.oauth_token = '%s'", $oauth_token);
    return db_fetch_array($stmt);
  }

  /**
   * Implements OAuth2::setAccessToken().
   */
  public function setAccessToken($oauth_token, $client_id, $expires, $scope = '', $uid = 0, $sid = '') {
    global $user;
    $uid = ($uid == 0 && $user->uid != 0) ? $user->uid : $uid;
    $sid = ($sid == '' && session_id()) ? session_id() : $sid;
    return db_query("INSERT INTO {oauth2_access_tokens} (oauth_token, client_id, expires, scope, uid, sid) VALUES ('%s', '%s', %d, '%s', %d, '%s')", $oauth_token, $client_id, $expires, $scope, $uid, $sid);
  }

  /**
   * Overrides OAuth2::getSupportedGrantTypes().
   */
  protected function getSupportedGrantTypes() {
    return array(
      OAUTH2_GRANT_TYPE_AUTH_CODE,
      OAUTH2_GRANT_TYPE_USER_CREDENTIALS,
      OAUTH2_GRANT_TYPE_REFRESH_TOKEN
    );
  }

  /**
   * Overrides OAuth2::getSupportedScopes().
   */
  public function getSupportedScopes() {
    $stmt = db_query("SELECT os.scope_id FROM {node} AS n INNER JOIN {oauth2_scopes} AS os ON n.vid = os.vid WHERE n.status = 1");
    $scope_ids = array();
    while ($result = db_fetch_array($stmt)) {
      $scope_ids[] = $result['scope_id'];
    }
    return $scope_ids;
  }

  /**
   * Overrides OAuth2::getAuthCode().
   */
  public function getAuthCode($code) {
    $stmt = db_query("SELECT oac.client_id, oac.sid, oac.redirect_uri, oac.expires, oac.scope, u.uid, u.name
      FROM {oauth2_auth_codes} AS oac
        INNER JOIN {users} AS u ON oac.uid = u.uid
      WHERE oac.code = '%s'", $code);
    return db_fetch_array($stmt);
  }

  /**
   * Overrides OAuth2::setAuthCode().
   */
  public function setAuthCode($code, $client_id, $redirect_uri, $expires, $scope = '', $uid = 0, $sid = '') {
    global $user;
    $uid = ($uid == 0 && $user->uid != 0) ? $user->uid : $uid;
    $sid = ($sid == '' && session_id()) ? session_id() : $sid;
    return db_query("INSERT INTO {oauth2_auth_codes} (code, client_id, redirect_uri, expires, scope, uid, sid) VALUES ('%s', '%s', '%s', %d, '%s', %d, '%s')", $code, $client_id, $redirect_uri, $expires, $scope, $uid, $sid);
  }

  /**
   * Overrides OAuth2::checkUserCredentials().
   */
  public function checkUserCredentials($client_id, $username, $password) {
    // Clone from user_service_login().
    $account = user_authenticate(array('name' => $username, 'pass' => $password));

    $result = NULL;
    if ($account->uid) {
      // Regenerate the session ID to prevent against session fixation attacks.
      sess_regenerate();
      $array = array();
      user_module_invoke('login', $array, $account);

      $result = array(
        'uid' => $account->uid,
        'name' => $account->name,
        'sid' => session_id(),
        'scope' => trim(implode(' ', $this->getAuthorizedScopes($client_id, $account->uid))),
      );
    }

    return $result ? $result : FALSE;
  }

  /**
   * Overrides OAuth2::getRefreshToken().
   */
  public function getRefreshToken($refresh_token) {
    $stmt = db_query("SELECT ort.client_id, ort.sid, ort.expires, ort.scope, u.uid, u.name
      FROM {oauth2_refresh_tokens} AS ort
        INNER JOIN {users} AS u ON ort.uid = u.uid
      WHERE ort.refresh_token ='%s'", $refresh_token);
    return db_fetch_array($stmt);
  }

  /**
   * Overrides OAuth2::setRefreshToken().
   */
  public function setRefreshToken($refresh_token, $client_id, $expires, $scope = '', $uid = 0, $sid = '') {
    global $user;
    $uid = ($uid == 0 && $user->uid != 0) ? $user->uid : $uid;
    $sid = ($sid == '' && session_id()) ? session_id() : $sid;
    return db_query("INSERT INTO {oauth2_refresh_tokens} (refresh_token, client_id, expires, scope, uid, sid) VALUES ('%s', '%s', %d, '%s', %d, '%s')", $refresh_token, $client_id, $expires, $scope, $uid, $sid);
  }

  /**
   * Overrides OAuth2::unsetRefreshToken().
   */
  public function unsetRefreshToken($refresh_token) {
    return db_query("DELETE FROM {oauth2_refresh_tokens} WHERE refresh_token = '%s'", $refresh_token);
  }
}
