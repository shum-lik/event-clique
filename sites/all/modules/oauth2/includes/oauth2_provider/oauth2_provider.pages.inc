<?php
// $Id: oauth2_provider.pages.inc,v 1.1 2011/01/25 09:27:08 hswong3i Exp $

/**
 * @file
 * OAuth2.0 Provider Support page callbacks.
 */

/**
 * Menu callback for authorization endpoint.
 */
function oauth2_provider_authorize_form() {
  global $user;
  $form = array();

  // Redirect anonymous user to login page, and come back later.
  if ($user->uid == 0) {
    $params = $_GET;
    unset($params['q']);
    $params['destination'] = 'oauth2/authorize';
    drupal_goto('user', $params);
  }

  $auth_params = oauth2_get_server()->getAuthorizeParams();

  $extened_permissions = oauth2_get_server()->verifyAuthorizedScopes($auth_params['client_id'], $auth_params['scope'], $user->uid);
  $auth_scope_ids = is_array($auth_params['scope']) ? explode(' ', $auth_params['scope']) : array($auth_params['scope']);

  // All scopes are already authorized by this user.
  if (count($extened_permissions) == 0) {
    oauth2_get_server()->finishClientAuthorization(TRUE, $auth_params);
  }

  $request = 0;
  $scope_ids = array();
  foreach ($extened_permissions as $permission) {
    if ($permission['bypass'] == 0) {
      $node = node_load($permission['nid']);
      $form['scope_'. $permission['scope_id']]['#value'] = theme('oauth2_provider_scope', $node);
      $request++;
    }
    $scope_ids[] = $permission['scope_id'];
  }

  $auth_params['scope'] = array_unique(array_merge($auth_scope_ids, $scope_ids));
  $auth_params['scope'] = trim(implode(' ', $auth_params['scope']));

  if ($request == 0) {
    oauth2_get_server()->setAuthorizedScopes($auth_params['client_id'], $auth_params['scope'], $user->uid);
    oauth2_get_server()->finishClientAuthorization(TRUE, $auth_params);
  }

  foreach ($auth_params as $k => $v) {
    $form[$k] = array(
      '#type' => 'hidden',
      '#value' => $v,
    );
  }

  $form['message'] = array(
    '#value' => t('<p>Do you authorize the app to do its thing?</p>'),
  );

  $form['submit'] = array(
    '#type'  => 'submit',
    '#value' => t('Yep'),
  );
  $form['reset'] = array(
    '#type'  => 'submit',
    '#value' => t('Nope'),
  );

  return $form;
}

/**
 * Submit function for authorization endpoint form.
 */
function oauth2_provider_authorize_form_submit($form, &$form_state) {
  global $user;
  $auth_params = $form_state['values'];

  if ($auth_params['op'] == t('Yep')) {
    oauth2_get_server()->setAuthorizedScopes($auth_params['client_id'], $auth_params['scope'], $user->uid);
    oauth2_get_server()->finishClientAuthorization(TRUE, $auth_params);
  }
  else {
    drupal_goto($auth_params['redirect_uri']);
  }
}

/**
 * Menu callback for token endpoint.
 */
function oauth2_provider_access_token_callback() {
  oauth2_get_server()->grantAccessToken();
}
