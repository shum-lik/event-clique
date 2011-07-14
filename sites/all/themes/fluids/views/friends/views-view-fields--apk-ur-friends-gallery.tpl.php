<?php
  $account = user_load($fields['uid']->content);
  
  if (module_exists('imagecache_profiles')) {
    $alt = t("@user's picture", array('@user' => $fields['realname']->content));
    $title = t("View @user's profile.", array('@user' => $fields['realname']->content));
    
    $user_picture = l(theme('imagecache', 'user_pictures_friends_gallery', $account->picture, $alt, $title), 'user/' . $account->uid, array('html' => TRUE));
  }
  
  $shorten_realname = substr($fields['realname']->content, 0, 5) . (strlen($fields['realname']->content) > 5 ? '...' : '');
?>

<?php print $user_picture; ?>
<p><?php print l($shorten_realname, 'user/' . $account->uid, array('attributes' => array('title' => $fields['realname']->content))); ?></p>
