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
  <body <?php if($node_type): ?>class="<?php print $node_type ?>"<?php elseif($is_front): ?>class="front"<?php endif ?>>
    <div id="bodyBar"></div>
    <div id="container">
	   <div id="wrapper">
         <?php /*Main Header Region*/ ?>
         <div id="header">
            <div id="header-left">
			    <?php print $header_left ?>
            </div>
            <?php /*Start Right Header Region*/ ?>
            <div id="header-right">
                <?php print $header_right ?>
            </div>
            <?php /*End of Right Header Region*/ ?>
			<div class="clear"></div>
        </div>
        <?php /*End of Header Region*/ ?>
		<div id="banner">
		    <?php if($banner): print ($banner); else:?><a href="<?php print base_path(); ?>" border="0"><img src="<?php print base_path().path_to_theme() ?>/img/Header-EC.png" title="EventClique" alt="EventClique"></a><?php endif ?>
		</div>
		
		<div id="primary-links">
        	<?php print $navigation ?>
         <div class="clear"></div>
            </div>
     	<?php if($tabs): ?><div id="tabs-primary"><ul><?php print $tabs ?></ul></div><?php endif ?>
        <?php if($tabs2): ?><div id="tabs-secondary"><ul><?php print $tabs2 ?></ul></div><?php endif ?>
		
        <?php /*Start Content Region */?>
		 <div style="width:640px;float:left;position:relative;border-right:1px solid #E3E3E3;" >
            <?php 
             /*  Start printing Block Left
             **  if Block Left exists or if page is not the homepage
             */
             if(!$is_front && $block_left):?>
		     <div id="block-left">        
		       <?php print $block_left; ?>
             <div class="clear"></div>
             </div>
            <?php endif 
            /*
            ** End of Block Left
            */
            ?>
            <?php /*Start Main Content area */?>
            
            <?php /* Event Info & Event Comment Tabs*/ ?>
            <div id="tabWrap" style="margin: 0 0 0 10px;overflow:hidden;">
            	<dl style="overflow:hidden;">
					<dd style="float:left;margin-left:18px;"><a class="topRounded" title="Information" href="<?php print base_path().$node->path; ?>">Information</a></dd>
					<dd style="float:left;margin-left:0px;"><a class="topRounded select" title="Comments" href="<?php print base_path().$node->path; ?>/comments">Comments</a></dd>
					<div style="clear:left;"></div>
				</dl>            
            </div>
            
            <?php /* Event Details Content */ ?>
            <div style="margin-left:20px;margin-top:-12px;float: left;width:600px;border:1px solid #C4C4C4;" class="allRounded">
               <div>
                   
		           <?php if($block_middle): ?>
  		           <div id="block-middle">
		           <?php if($is_front): ?>
           			 <?php if($search): ?><div id="search-box"><?php print $search ?></div><?php endif ?>
			       <?php endif ?>
	  	           <?php print $block_middle; ?>
		           </div>
		           <?php endif ?>
				   <?php if($show_messages && $messages): ?><?php print $messages ?><?php endif ?>
				  
                   <?php print $content; ?>
                   <?php print $comment_form; ?>
                 </div>
                 <div class="clear"></div>
               </div>
               <div class="clear"></div>
		    </div>
            <?php /*End of Main Content area */?>
            <?php /*Start Right Block area */?>
            <div style="float: right;padding: 0 20px;width: 298px;">
               <?php print $block_right ?>
               <div class="clear"></div>
            </div>
            <?php /*End of Right Block area */?>
	   <div class="clear"></div>
	  </div>
	  <div id="footer"><?php print $custom_footer ?></div>
	  <div class="clear"></div>
    </div>
	
	  <?php print $closure ?>
  </body>
</html>
