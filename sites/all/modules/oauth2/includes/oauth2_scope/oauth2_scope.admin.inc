<?php
// $Id: oauth2_scope.admin.inc,v 1.1 2011/01/25 09:27:08 hswong3i Exp $

/**
 * @file
 * OAuth2.0 Scope Identifer administration interface.
 */

/**
 * Administration overview for oauth2_scope.
 */
function oauth2_scope_admin_callback() {
  $header = array(
    array('data' => t('Scope name'), 'field' => 'n.title'),
    array('data' => t('Scope ID'), 'field' => 'os.scope_id'),
    array('data' => t('Hidded'), 'field' => 'os.hidden'),
    array('data' => t('Bypass'), 'field' => 'os.bypass'),
    array('data' => t('Required'), 'field' => 'os.required'),
    array('data' => t('Published'), 'field' => 'n.status'),
    array('data' => t('Updated'), 'field' => 'n.changed', 'sort' => 'desc'),
    array('data' => t('Operations'), 'colspan' => 2),
  );

  $result = db_query("SELECT n.nid, n.title, os.scope_id, os.hidden, os.bypass, os.required, n.status, n.changed FROM {node} AS n INNER JOIN {oauth2_scopes} AS os ON n.vid = os.vid". tablesort_sql($header));

  $rows = array();
  while ($node = db_fetch_object($result)) {
    $rows[] = array(
      l($node->title, 'node/'. $node->nid),
      $node->scope_id,
      $node->hidden ? t('Yes') : t('No'),
      $node->bypass ? t('Yes') : t('No'),
      $node->required ? t('Yes') : t('No'),
      $node->status ? t('Yes') : t('No'),
      format_interval(time() - $node->changed),
      l(t('edit'), 'node/'. $node->nid .'/edit', array('query' => array('destination' => 'admin/build/oauth2/oauth2_scope'))),
      l(t('delete'), 'node/'. $node->nid .'/delete', array('query' => array('destination' => 'admin/build/oauth2/oauth2_scope'))),
    );
  }

  if (empty($rows)) {
    $rows[] = array(array('data' => '<em>'. t('There are currently no OAuth2.0 Scope.') .'</em>', 'colspan' => 9));
  }

  return theme('table', $header, $rows);
}
