<?php
// $Id: services_oauth2.inc,v 1.1 2011/01/25 09:27:08 hswong3i Exp $

/**
 * @file
 *  The implementation of the OAuth2.0 authentication scheme
 */

/**
 * Authenticates a call using OAuth2.0 to verify the request.
 *
 * @param $settings
  *  The settings for the authentication module.
 * @param $method
 *  The method that's being called
 * @param $args
 *  The arguments that are being used to call the method
 *
 * @return
 *  Returns nothing, or a error message if authentication fails
 */
function _services_oauth2_authenticate_call($settings, $method, $args) {
  // Remove all oauth_token from args.
  foreach ($args as $key => $arg) {
    if (is_array($args[$key]) && isset($args[$key]['oauth_token'])) {
      unset($args[$key]['oauth_token']);
    }
    elseif (is_object($args[$key]) && isset($args[$key]->oauth_token)) {
      unset($args[$key]->oauth_token);
    }
  }

  oauth2_get_server()->verifyAccessToken();
}

function _services_oauth2_security_settings() {
  return array();
}

function _services_oauth2_controller_settings($settings, $controller, $endpoint, $class, $name) {
  return array();
}
