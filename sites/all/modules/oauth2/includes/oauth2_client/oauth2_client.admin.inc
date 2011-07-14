<?php
// $Id: oauth2_client.admin.inc,v 1.1 2011/01/25 09:27:08 hswong3i Exp $

/**
 * @file
 * OAuth2.0 Client Identifer administration interface.
 */

/**
 * Administration overview for oauth2_client.
 */
function oauth2_client_admin_callback() {
  $header = array(
    array('data' => t('Application name'), 'field' => 'n.title'),
    array('data' => t('Application ID'), 'field' => 'oc.client_id'),
    array('data' => t('Application secret'), 'field' => 'oc.client_secret'),
    array('data' => t('Published'), 'field' => 'n.status'),
    array('data' => t('Updated'), 'field' => 'n.changed', 'sort' => 'desc'),
    array('data' => t('Operations'), 'colspan' => 2),
  );

  $result = db_query("SELECT n.nid, n.title, oc.client_id, oc.client_secret, n.status, n.changed FROM {node} AS n INNER JOIN {oauth2_clients} AS oc ON n.vid = oc.vid". tablesort_sql($header));

  $rows = array();
  while ($node = db_fetch_object($result)) {
    $rows[] = array(
      l($node->title, 'node/'. $node->nid),
      $node->client_id,
      $node->client_secret,
      $node->status ? t('Yes') : t('No'),
      format_interval(time() - $node->changed),
      l(t('edit'), 'node/'. $node->nid .'/edit', array('query' => array('destination' => 'admin/build/oauth2/oauth2_client'))),
      l(t('delete'), 'node/'. $node->nid .'/delete', array('query' => array('destination' => 'admin/build/oauth2/oauth2_client'))),
    );
  }

  if (empty($rows)) {
    $rows[] = array(array('data' => '<em>'. t('There are currently no OAuth2.0 Client Identifer.') .'</em>', 'colspan' => 7));
  }

  return theme('table', $header, $rows);
}
