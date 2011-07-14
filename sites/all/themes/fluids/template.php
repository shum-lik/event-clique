<?php
// $Id: template.php,v 1.16.2.3 2010/05/11 09:41:22 goba Exp $

/**
 * Sets the body-tag class attribute.
 *
 * Adds 'sidebar-left', 'sidebar-right' or 'sidebars' classes as needed.
 */
function phptemplate_body_class($left, $right) {
  if ($left != '' && $right != '') {
    $class = 'sidebars';
  }
  else {
    if ($left != '') {
      $class = 'sidebar-left';
    }
    if ($right != '') {
      $class = 'sidebar-right';
    }
  }

  if (isset($class)) {
    print ' class="'. $class .'"';
  }
}

/**
 * Return a themed breadcrumb trail.
 *
 * @param $breadcrumb
 *   An array containing the breadcrumb links.
 * @return a string containing the breadcrumb output.
 */
function phptemplate_breadcrumb($breadcrumb) {
    return '<div class="breadcrumb">'. $breadcrumb .'</div>';
}

/**
 * Override or insert PHPTemplate variables into the templates.
 */
function phptemplate_preprocess_page(&$vars) {
 // if(arg(0) == 'logout'){drupal_goto('events/singapore-street-festival/shine-festival-official-launch/comments');}
  $vars['tabs2'] = menu_secondary_local_tasks();
  if(arg(0) == 'node' && is_numeric(arg(1)) && arg(3)==NULL){
    $res = db_query('select type from node where nid=%d',arg(1));
    $row = db_fetch_object($res);
    $vars['template_files'][] = 'page-node-'.$row->type;
     if($row->type == 'events'){
            if($vars['node']->field_event_logo[0]['filepath']!=NULL) $vars['logo'] = base_path().$vars['node']->field_event_logo[0]['filepath'];
     }
     if($row->type == 'sub_events'){
       $res2 = db_query('select filepath from files a inner join content_type_events b on b.field_event_logo_fid = a.fid  where b.nid = %d',$vars['node']->field_parent_event[0]['nid']);
       $row2 = db_fetch_object($res2);
	   if($row2->filepath!=NULL) $vars['logo'] = base_path().$row2->filepath;
     }
  }
  
// EVENT LISTING PAGE TEMPLATE
  if(arg(0) == 'events' && arg(1) == NULL){
  	
	$vars['template_files'][] = 'page-node-list-events';	  
  }
  
  // EVENT DETAILS COMMENTS PAGE
  if(arg(0) == 'events' && arg(2) == 'comments'){
  	
  	$vars['template_files'][] = 'page-node-event-comments';
    	
	$path = drupal_get_normal_path('events/'.arg(1));
	
	//echo $path;
	$v = explode('/',$path);
	
	
	$vars['node'] = node_load(array('nid'=>$v[1]));

	
	$res2 = db_query('select filepath from files a inner join content_type_events b on b.field_event_logo_fid = a.fid  where b.nid = %d',$vars['node']->field_parent_event[0]['nid']);
    $row2 = db_fetch_object($res2);
    if($row2->filepath!=NULL) $vars['logo'] = base_path().$row2->filepath;
	$form = drupal_get_form('comment_form',array('nid'=>$vars['node']->nid));
	$vars['comment_form'] = $form;

  }
  
  // USER REGISTER PAGE TEMPLATE
  if(arg(0)=='user' && arg(1)=='register'){
    $vars['template_files'][] = 'page-user-register';
  }

}

function phptemplate_preprocess_node($vars){
  if(arg(0)=='node' && is_numeric(arg(1))){
    $node = db_fetch_object(db_query('select title,type from node where nid = %d'),arg(1));
	if($node->type == 'page'){
	  $vars['node_title'] = '';
	}
	else{
	  $vars['node_title'] = t($node->title);
	}
  }
}

/**
 * Add a "Comments" heading above comments except on forum pages.
 */
function garland_preprocess_comment_wrapper(&$vars) {
  if ($vars['content'] && $vars['node']->type != 'forum') {
    $vars['content'] = '<h2 class="comments">'. t('Comments') .'</h2>'.  $vars['content'];
  }
  $vars['content'] = NULL;
}

/**
 * Returns the rendered local tasks. The default implementation renders
 * them as tabs. Overridden to split the secondary tasks.
 *
 * @ingroup themeable
 */
function phptemplate_menu_local_tasks() {
  return menu_primary_local_tasks();
}

/**
 * Returns the themed submitted-by string for the comment.
 */
function phptemplate_comment_submitted($comment) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $comment),
      '!datetime' => format_date($comment->timestamp)
    ));
}

/**
 * Returns the themed submitted-by string for the node.
 */
function phptemplate_node_submitted($node) {
  return t('!datetime — !username',
    array(
      '!username' => theme('username', $node),
      '!datetime' => format_date($node->created),
    ));
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function phptemplate_get_ie_styles() {
  global $language;

  $iecss = '<link type="text/css" rel="stylesheet" media="all" href="'. base_path() . path_to_theme() .'/fix-ie.css" />';
  if ($language->direction == LANGUAGE_RTL) {
    $iecss .= '<style type="text/css" media="all">@import "'. base_path() . path_to_theme() .'/fix-ie-rtl.css";</style>';
  }

  return $iecss;
}

function fluids_preprocess_search_block_form(&$variables) {
}


function fluids_form_element($element,$value){
  $t = get_t();

  $output = '<div class="form-item"';
  if (!empty($element['#id'])) {
    $output .= ' id="'. $element['#id'] .'-wrapper"';
  }
  $output .= ">\n";
  $required = !empty($element['#required']) ? '<span class="form-required" title="'. $t('This field is required.') .'">*</span>' : '';
  $additional_class = $element['#type'];
  if (!empty($element['#title'])) {
    $title = $element['#title'];
    if (!empty($element['#id'])) {
      $output .= ' <div class="label" id="label-'. $element['#id'] .'">'. $t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)) ."</div>\n";
    }
    else {
      $output .= ' <div class="label">'. $t('!title: !required', array('!title' => filter_xss_admin($title), '!required' => $required)) ."</div>\n";
    }
  }
  $output .= "<div class=\"form-element ".$additional_class."\">".$value."</div>\n";
  if (!empty($element['#description'])) {
    $output .= ' <div class="description">'. $element['#description'] ."</div>\n";
  }

  $output .= "<div class=\"clear\"></div></div>\n";
  return $output;  
}

/**
* Override the views_exposed_form variables
*/
// EXPOSED FORM FOR HEADER SEARCH BAR, 
//                  HOME PAGE SEARCH AND 
//                  EVENT RESULTS PAGE'S SEARCH BAR

function fluids_preprocess_views_exposed_form(&$vars, $hook) {

	//print_r($vars);
	//print_r($vars['form']['tid_1']['#options']);
	
	//print_r($vars);
	
	//print_r($vars['widgets']);

  if($vars['form']['#id'] == 'views-exposed-form-events-block-2'){
  	$vars['form']['submit']['#value'] = t('Search');
  	
  	// Rebuild the rendered version (submit button, rest remains unchanged)
    unset($vars['form']['submit']['#printed']);
    $vars['button'] = drupal_render($vars['form']['submit']);
  	
  }
  
if($vars['form']['#id'] == 'views-exposed-form-events-block-1'){
  	$vars['form']['submit']['#value'] = t('Search');
  	$vars['form']['tid_1']['title'] = t('Find event in: ');
  	
  	// Rebuild the rendered version (submit button, rest remains unchanged)
    unset($vars['form']['submit']['#printed']);
    $vars['button'] = drupal_render($vars['form']['submit']);
  	
  }
	
	
  if ($vars['form']['#id'] == 'views-exposed-form-events-page-1') {
	  
  	$vars['form']['tid_1']['#options']['All'] = t('This location');
  	
  	//var_dump($vars['form']['tid_1']);
  	
  	//var_dump($vars['form']['submit']);
  	//$form['form']['tid_1'] = 'select';
    //$form['exposed-element']['#options'] = array(
  	
  	
  	
    // Change the text on the submit button
    $vars['form']['submit']['#value'] = t('Search');
	$vars['form']['tid_1']['title'] = t('Find event in: ');
	//$vars['form']['tid_1']['#options']['All'] = t('This location');
	
	//$vars['form']['tid_1']['#options']['All'] = preg_replace('- Any -', 'This location', $vars['form']['tid_1']['#options']['All'] );
  	//$vars['form'] = drupal_render($vars['form']['tid_1']);
	
    // Rebuild the rendered version (submit button, rest remains unchanged)
    unset($vars['form']['submit']['#printed']);
    //unset($vars['form']['tid_1']['#printed']);
    
    //var_dump(drupal_render($vars['form']['tid_1']));
    
    //var_dump(drupal_render($vars['form']['submit']));
    $vars['button'] = drupal_render($vars['form']['submit']);
   
  }
}

function fluids_theme(){
  return array(
    'user_login_block' => array(
      'template' => 'user-login-block',
      'arguments' => array('form' => NULL),
    ),
    'user_register' => array(
      'arguments' => array('form' => NULL)
    ),
	'comment_form' => array(
	  'arguments' => array('form' => NULL)
	),
	'user_pass' => array(
	  'arguments' => array('form' => NULL)
	)
  );
}

function fluids_preprocess_user_login_block(&$variables) {

    $form = $variables['form'];
    $alternate['twitter_signin'] = $form['twitter_signin']; $form['twitter_signin']=NULL; 
    $alternate['fbconnect_button'] = $form['fbconnect_button']; $form['fbconnect_button'] = NULL;
    $alternate['fbconnect_button']['#description'] = NULL;
    $variables['alternate'] = drupal_render($alternate);

    //$submit = $form['submit'];
    //$form['submit'] = NULL; $form['links'] = NULL;
    //$rendered = drupal_render($form);
    //$links['links']['#value'] = '<div class="links">'.l('forgot password?','user/password').'</div>';
    //$rendered.= drupal_render($links);
    //$rendered.= drupal_render($submit);
	$form['#attributes'] = array('class' => 'popupForm');
	$form['links']['#value'] = '<div class="links">'.l('forgot password?','user/password').'<br/>'.l('sign up','user/register').'</div>';
	
	$form['links']['#weight'] = 99;
    $form['submit']['#weight'] = 99;
	$rendered = $form;
    $variables['rendered'] = $rendered;
}

function fluids_preprocess_user_pass(&$variables) {
  $variables['intro_text'] = t('This is my super awesome insane password form');
  $variables['form']['#action'].='?destination=password-sent';
  print_r($variables);
  $variables['rendered'] = drupal_render($variables['form']);
} 

function fluids_user_register($form){
  //echo '<pre>';print_r($form);echo'</pre>';
 
  $form['fullname'] = $form['Personal Information']['fullname'];
  $form['fullname']['#weight'] = -99;
  $form['name'] = $form['account']['name'];
  $form['mail'] = $form['account']['mail'];
  $form['conf_mail'] = $form['account']['conf_mail'];
  $form['pass'] = $form['account']['pass'];
  $form['submit']['#weight'] = 100;
  unset($form['Personal Information']);
  unset($form['account']);
  return drupal_render_form('user_register',$form);

}

function drupal_set_messages($msg = '',$type='error'){
   drupal_set_message($msg,$type);
}

function fluids_comment_form($form){
  unset($form['friendconnect']);
  unset($form['comment_filter']['comment']['#title']);
  unset($form['comment_filter']['format']);
  unset($form['_author']);
  unset($form['preview']);
  
  $form['comment_filter']['comment']['#attributes'] = array('style'=>'height:40px;width:559px;');
  $form['comment_filter']['comment']['#resizable'] = FALSE;
  $form['comment_filter']['comment']['#value'] = 'Post your comment/review here';
  global $user;
  if($user->uid==NULL){
    $form['submit'] = NULL;
	$form['links']['#weight'] = 99;
	$form['links']['#value'] = '<a id="commentSignInPopup" href="#user-login-form">Post</a>';
  }
  //$form['submit']['#value'] = 'Post';
  
  return drupal_render($form);
}

function fluids_form_alter($form_id,$form){
 echo $form_id;
}

function fluids_user_pass($form){
  $form['#action'].='?destination=password-sent';
  $form['#redirect'] = 'password-sent';
  return drupal_render_form('user_pass',$form);
}



















































































































































































































function phptemplate_heartbeat_comment($comment, $node_comment = FALSE, $last = FALSE) {
  $output = '';
  
  if ($last == TRUE) {
    $class = "heartbeat-comment-last";
  }
  else {
    $class = "";
  }
  
  $account = heartbeat_user_load($comment->uid);
  
  $output .= '<li class="heartbeat-comment '. $class .'" id="heartbeat-comment-' . ($node_comment ? $comment->cid : $comment->hcid) .'">';
  
  $avatar = '';
  $alt = t("@user's picture", array('@user' => module_exists('realname') ? realname_make_name($account) : $account->name));
  $title = t('View user profile.');
  
  if (module_exists('imagecache_profiles')) {
    $avatar = theme('imagecache', 'user_pictures_small', $account->picture, $alt, $title);
  }
  else {
    $avatar = theme('user_picture', $comment);
  }
  
  $output .= '<div class="float-left">';
  $output .= '<span class="avatar">' . l($avatar, 'user/' . $comment->uid, array('html' => TRUE)) . '</span>';
  $output .= '</div>'; //.float-left

  $account = heartbeat_user_load($comment->uid);
  
  $output .= '<div class="float-right">';
  $output .= '<div class="heartbeat-teaser">';
  $output .= l(module_exists('realname') ? realname_make_name($account) : $account->name, 'user/' . $comment->uid) . ' ';
  $output .= $comment->comment;
  $output .= '</div>'; //.heartbeat-teaser
  
  $comment_timestamp = strtotime(db_result(db_query("SELECT time FROM {heartbeat_comments} WHERE hcid = %d", $comment->hcid)));
  $output .= _theme_time_ago($comment_timestamp);
  
  $output .= '</div>'; //.float-right
  
  $output .= '<div class="clear"></div>';

  $output .= '<div class="heartbeat-comment-buttons">';
  
  // For node comments link to the standard Drupal comment deletion form under comment/delete/%
  // Only users who have the right permissions should see the delete link.
  // Permissions are provided by the "Comment Delete" module.
  global $user;
  $redirect_path = isset($_POST['path']) ? 'destination='. $_POST['path'] : drupal_get_destination();
  if ($node_comment) {
    if (user_access('delete any comment') || ($user->uid == $comment->uid && user_access('delete own comments'))) {
      $output .= l(t('Delete'), 'heartbeat/nodecomment/delete/'. $comment->cid, array('attributes' => array('class' => 'heartbeat-comment-delete'), 'query' => $redirect_path, 'alias' => TRUE));
    }
  }
  // For Heartbeat comments link to an own deletion form.
  // Only users who have the right permissions or are the commenting person should see the delete link.
  // Permissions are provided by Heartbeat itself ("administer heartbeat comments').
  elseif(user_access('administer heartbeat comments') || ($comment->uid && $user->uid && $comment->uid == $user->uid)) {
    $output .= l(t('Delete'), 'heartbeat/comment/delete/'. $comment->hcid, array('attributes' => array('class' => 'heartbeat-comment-delete'), 'query' => $redirect_path, 'alias' => TRUE));
  }

  $output .= '</div>'; //.heartbeat-comment-buttons
  $output .= '</li>';

  return $output;
}
