<?php
// $Id: DrupalOAuth2Client.inc,v 1.1 2011/01/25 09:27:08 hswong3i Exp $

/**
 * @file
 * Drupal OAuth2.0 client library.
 */

/**
 * Provides access to the Drupal OAuth2.0 Platform.
 *
 * @author Edison Wong <hswong3i@pantarei-design.com>
 */
class DrupalOAuth2Client extends OAuth2Client {

  /**
   * Get a Login URL for use with redirects. By default, full page redirect is
   * assumed. If you are using the generated URL with a window.open() call in
   * JavaScript, you can pass in display = popup as part of the $params.
   *
   * @param $params
   *   Provide custom parameters.
   *
   * @return
   *   The URL for the login flow.
   */
  public function getLoginUri($params = array()) {
    return $this->getUri(
      $this->getVariable('base_uri') . 'user',
      array_merge(array(
        'response_type' => 'code',
        'client_id' => $this->getVariable('client_id'),
        'redirect_uri' => $this->getCurrentUri(),
      ), $params)
    );
  }

  /**
   * Get a Logout URL suitable for use with redirects.
   *
   * @param $params
   *   Provide custom parameters.
   *
   * @return
   *   The URL for the logout flow.
   */
  public function getLogoutUri($params = array()) {
    return $this->getUri(
      $this->getVariable('base_uri') . 'logout',
      array_merge(array(
        'oauth_token' => $this->getAccessToken(),
        'redirect_uri' => $this->getCurrentUri(),
      ), $params)
    );
  }
}
