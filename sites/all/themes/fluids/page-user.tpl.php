<?php
/**
 * @file
 * Theme implementation to display a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/garland.
 * - $is_front: TRUE if the current page is the front page. Used to toggle the mission statement.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE tag.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $classes: String of classes that can be used to style contextually through
 *   CSS. It should be placed within the <body> tag. When selecting through CSS
 *   it's recommended that you use the body tag, e.g., "body.front". It can be
 *   manipulated through the variable $classes_array from preprocess functions.
 *   The default values can be one or more of the following:
 *   - front: Page is the home page.
 *   - not-front: Page is not the home page.
 *   - logged-in: The current viewer is logged in.
 *   - not-logged-in: The current viewer is not logged in.
 *   - node-type-[node type]: When viewing a single node, the type of that node.
 *     For example, if the node is a "Blog entry" it would result in "node-type-blog".
 *     Note that the machine name will often be in a short form of the human readable label.
 *   - page-views: Page content is generated from Views. Note: a Views block
 *     will not cause this class to appear.
 *   - page-panels: Page content is generated from Panels. Note: a Panels block
 *     will not cause this class to appear.
 *   The following only apply with the default 'sidebar_first' and 'sidebar_second' block regions:
 *     - two-sidebars: When both sidebars have content.
 *     - no-sidebars: When no sidebar content exists.
 *     - one-sidebar and sidebar-first or sidebar-second: A combination of the
 *       two classes when only one of the two sidebars have content.
 * - $node: Full node object. Contains data that may not be safe. This is only
 *   available if the current page is on the node's primary url.
 * - $menu_item: (array) A page's menu item. This is only available if the
 *   current page is in the menu.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $mission: The text of the site mission, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been disabled.
 * - $primary_links (array): An array containing the Primary menu links for the
 *   site, if they have been configured.
 * - $secondary_links (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title: The page title, for use in the actual HTML content.
 * - $messages: HTML for status and error messages. Should be displayed prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the
 *   view and edit tabs when displaying a node).
 * - $help: Dynamic help text, mostly for admin pages.
 * - $content: The main content of the current page.
 * - $feed_icons: A string of all feed icons for the current page.
 *
 * Footer/closing data:
 * - $footer_message: The footer message as defined in the admin settings.
 * - $closure: Final closing markup from any modules that have altered the page.
 *   This variable should always be output last, after all other dynamic content.
 *
 * Helper variables:
 * - $classes_array: Array of html class attribute values. It is flattened
 *   into a string within the variable $classes.
 *
 * The following variables are deprecated and will be removed in Drupal 7:
 * - $body_classes: This variable has been renamed $classes in Drupal 7.
 */
?>
<?php
// $Id: page.tpl.php,v 1.18.2.1 2009/04/30 00:13:31 goba Exp $
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns:fb="http://www.facebook.com/2008/fbml"  xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
    <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
  </head>
  <body class="<?php print $body_classes; if($node) print ' page-node-' . $node->nid; if($recent) print ' recent'; ?>">
    <div id="bodyBar"></div>
    <div id="container">
      <div id="wrapper">
        <?php /*Main Header Region*/ ?>
        <div id="header"> 	
          <div id="header-left">
            <?php print $header_left ?>
          </div><!-- /#header-left -->
            
          <?php /*Start Right Header Region*/ ?>
          <div id="header-right">
            <?php print $header_right ?>
          </div><!-- /#header-right -->
          <?php /*End of Right Header Region*/ ?>
          
          <div class="clear"></div>         
        </div><!-- /#header -->
        <?php /*End of Header Region*/ ?>
         
        <div id="banner">
          <?php if($banner): print ($banner); else:?><a href="<?php print base_path(); ?>" border="0"><img src="<?php print base_path().path_to_theme() ?>/img/Header-EC.png" title="EventClique" alt="EventClique"></a><?php endif; ?>
        </div><!-- /#banner -->
         
        <?php /*Start Content Region */?>
        <div id="content">
          <?php 
            /*  Start printing Block Left
            **  if Block Left exists or if page is not the homepage
            */
            if(!$is_front && $block_left):
          ?>
            <div id="block-left">        
              <?php print $block_left; ?>
              <div class="clear"></div>
            </div><!-- /#block-left -->
          <?php endif;
            /*
            ** End of Block Left
            */
          ?>
          
          <?php /*Start Main Content area */?>
          <div id="main-content">
            <div id="content-area">
              <div id="primary-links">
                <?php print $navigation ?>
                <div class="clear"></div>
              </div><!-- /#primary-links -->
              
              <div id="block-middle">
                <?php print $block_middle; ?>
              </div><!-- /#block-middle -->
              
              <!--
              <div style="border-top:1px solid #ffa808; border-bottom:1px solid #ffa808; background-color:#ffdea1; padding:8px; margin-bottom:10px;">
                <p>Click <a href="<?php print base_path() ?>">here</a> to view what others say</p>
              </div>
              -->
              
              <h1 class="myclique">My Clique</h1>
              
              <h2 class="title"><?php print $title ?></h2>
              
              <?php if($show_messages && $messages): ?>
                <?php print $messages ?>
              <?php endif; ?>
              
              <!--
              <div class="tabs">
                <ul><?php print $tabs ?></ul>
              </div>
              <div class="tabs2">
                <ul><?php print $tabs2 ?></ul>
              </div>
              -->
              
              <?php if ($tabs): print '<div id="tabs-wrapper" class="clear-block">'; endif; ?>
              <?php if ($tabs): print '<ul class="tabs primary">' . $tabs .'</ul></div>'; endif; ?>
              
              <div class="content-wrapper">
                <?php if ($tabs2): print '<ul class="tabs secondary">'. $tabs2 .'</ul>'; endif; ?>
                <?php print $content ?>
              </div>
              <div class="clear"></div>
            </div><!-- /#content-area -->
            <div class="clear"></div>
          </div><!-- /#main-content -->
          <?php /*End of Main Content area */?>
          
          <?php /*Start Right Block area */?>
          <div id="block-right">
            <?php print $block_right ?>
            <div class="clear"></div>
          </div><!-- /#block-right -->
          <?php /*End of Right Block area */?>
          
          <div class="clear"></div>
        </div><!-- /#content -->
        <div class="clear"></div>
        
        <div id="footer">
          <?php print $custom_footer ?>
        </div><!-- /#footer -->
        
        <div class="clear"></div>
      </div><!-- /#wrapper -->
    </div><!-- /#container -->
	  
    <?php print $closure ?>
  </body>
</html>
