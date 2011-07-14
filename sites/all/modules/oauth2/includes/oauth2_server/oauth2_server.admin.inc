<?php
// $Id: oauth2_server.admin.inc,v 1.1 2011/01/25 09:27:08 hswong3i Exp $

/**
 * @file
 * OAuth2.0 Server Identifer administration interface.
 */

/**
 * Administration overview for oauth2_server.
 */
function oauth2_server_admin_callback() {
  $header = array(
    array('data' => t('Server Name'), 'field' => 'n.title'),
    array('data' => t('Server ID'), 'field' => 'osrv.server_id'),
    array('data' => t('Published'), 'field' => 'n.status'),
    array('data' => t('Updated'), 'field' => 'n.changed', 'sort' => 'desc'),
    array('data' => t('Operations'), 'colspan' => 2),
  );

  $result = db_query("SELECT n.nid, n.title, osrv.server_id, n.status, n.changed FROM {node} AS n INNER JOIN {oauth2_servers} AS osrv ON n.vid = osrv.vid". tablesort_sql($header));

  $rows = array();
  while ($node = db_fetch_object($result)) {
    $rows[] = array(
      l($node->title, 'node/'. $node->nid),
      $node->server_id,
      $node->status ? t('Yes') : t('No'),
      format_interval(time() - $node->changed),
      l(t('edit'), 'node/'. $node->nid .'/edit', array('query' => array('destination' => 'admin/build/oauth2/oauth2_server'))),
      l(t('delete'), 'node/'. $node->nid .'/delete', array('query' => array('destination' => 'admin/build/oauth2/oauth2_server'))),
    );
  }

  if (empty($rows)) {
    $rows[] = array(array('data' => '<em>'. t('There are currently no OAuth2.0 Server Identifer.') .'</em>', 'colspan' => 6));
  }

  return theme('table', $header, $rows);
}
