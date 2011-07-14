<?php
// $Id: page.tpl.php,v 1.18.2.1 2009/04/30 00:13:31 goba Exp $
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html  xmlns:fb="http://www.facebook.com/2008/fbml"  xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
  <head>
    <?php
  		//$facebook = new Facebook('bec548cf0cce46c32d15ec86e30552d4', '0c38a0ea6e2e5299be0accef51eee58e');
		//$facebook->require_frame();
		//$fb_user = $facebook->get_loggedin_user();
  	?>
    <?php print $head ?>
    <title><?php print $head_title ?></title>
    <?php print $styles ?>
    <?php print $scripts ?>
    <!--[if lt IE 7]>
      <?php print phptemplate_get_ie_styles(); ?>
    <![endif]-->
	<meta name="title" content="Asia Global Bellydance Events" />
	<meta name="description" content="1 This event raises the awareness of the history, culture & artistic aspect of Bellydance. It shares the spirit of Bellydance in not only in Eastern lands but also Asia. Belly dancers aim to share the passion of dance and celebrate  &quot; sisterhood &quot;  among women from all walks of life. 
It s about celebrating women of all races, cultures, body shapes & size, and also to add zest to womens lives so they can live with panache!
Singapore Street Festival in celebration of its 10th Anniversary Year, is bringing in International Belly Dance Masters from Italy, Taiwan and Korea to join us to perform in SSF special Gala show as its festival closing in 2011.
" /><meta http-equiv="content-type" content="text/html;charset=UTF-8" />
  </head>
  <body <?php if($node_type): ?>class="<?php print $node_type ?>"<?php elseif($is_front): ?>class="front"<?php endif ?>>
    <div id="bodyBar"></div>
    <div id="container">
	   <div id="wrapper">
         <?php /*Main Header Region*/ ?>
         <div id="header">
            <div id="header-left">
                <div id="logo">
        		   <?php
                    if ($logo || $site_title) {
                      print '<h1><a href="'. check_url($front_page) .'" title="'. $site_title .'">';
                      if ($logo) {
                        print '<img src="'. check_url($logo) .'" alt="'. $site_title .'" />';
                      }
                      print $site_html .'</a></h1>';
                    }
        		   ?>
                   <?php print $header_left ?>
               </div>		
            </div>
            <?php /*Start Right Header Region*/ ?>
            <div id="header-right">
                <?php print $header_right ?>
            </div>
            <?php /*End of Right Header Region*/ ?>
			<div class="clear"></div>
        </div>
         <?php /*End of Header Region*/ ?>
        <?php /*Start Content Region */?>
		 <div id="content">
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
            <div id="main-content" class="full <?php if(arg(2) == 'comments' || arg(3) == 'comments'):?>comments-page<?php endif ?>">
               <div id="content-area">
                   <div id="primary-links">
                      <?php print $navigation ?>
                      <div class="clear"></div>
                   </div>
     		       <?php if($tabs): ?><div id="tabs-primary"><ul><?php print $tabs ?></ul></div><?php endif ?>
             	   <?php if($tabs2): ?><div id="tabs-secondary"><ul><?php print $tabs2 ?></ul></div><?php endif ?>
		           <?php if($block_middle): ?>
  		           <div id="block-middle">
	  	             <?php print $block_middle; ?>
		               <?php if(!$is_front): ?>
               			 <?php if($search): ?><div id="search-box"><?php print $search ?></div><?php endif ?>
			           <?php endif ?>
		             <?php endif ?>
		             
					<?php
					
				
					
					
					
					$path = '';
					if($node->type=='events'){
					  $path = base_path().drupal_get_path_alias('node/'.arg(1));
					}
					else if($node->type=='sub_events'){
					  $path = base_path().drupal_get_path_alias('node/'.$node->nid);
					}
					else if(arg(0)=='events' && arg(2) == 'comments'){
					  $path = base_path().arg(0).arg(1);
					}
					?>
					<div id="tabHeadingsWrap">
						<div id="tabHeadings">
						  <ul>
							<li class="eventMain <?php if(arg(3)!='comments'):?>active<?php endif ?>" ><a href="http://www.singaporestreetfestival.com/">About SSF</a></li>
							<li class="eventReview">Reviews</a></li>
							<li class="eventTicket"><a href="http://www.ticketbooth.com.sg/EventsSearchResultByKeywords.ntuc?Keywords=Asia+Global+Bellydance">Get Ticket</a></li>
						  </ul>
						  <div class="clear"></div>
						</div>
						<div id="socialWrap">
						  <span class="share-label">Share</span>
						  <div id="sharebtn">		
						  <script>function fb_share() {u=location.href;t=document.title;window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(u)+'&t='+encodeURIComponent(t),'sharer','toolbar=0,status=0,width=626,height=436');return false;}</script>				  
						  <a target="_blank" href="http://www.facebook.com/sharer.php?u=<?php print $_SERVER['SERVER_NAME'].base_path()?>&t=Asia Global Bellydance Events" onclick="return fb_share()">&nbsp;&nbsp;&nbsp;</a>
						  
						  </div>
						  <div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>
                   </div>
				   <?php if(arg(3) == 'comments'):?>
				      <a class="eventBanner" title="Singapore Street Festival" href="http://www.ticketbooth.com.sg/EventsSearchResultByKeywords.ntuc?Keywords=Asia+Global+Bellydance">
					  <img alt="Street Festival" src="<?php print base_path().path_to_theme() ?>/img/layout/SSF_landing_comment_feed_banner2.jpg">
					  </a>
					 <?php endif ?>
				   <div class="subevent-content-wrapper <?php if(arg(2) == 'comments' || arg(3) == 'comments'):?>comments-page<?php endif ?>">
				   	  <h2>What Others Say</h2>				   	 
				   	  
                     <?php if($show_messages && $messages): ?><?php print $messages ?><?php endif ?>
				     <?php print $content ?>
				     <?php print $comment_form ?>
				     <div class="clear"></div>
				   </div>
				   <?php if($block_right):?>
				   <div id="block-right" class="<?php if(arg(2) == 'comments' || arg(3) == 'comments'):?>comments-page<?php endif ?>">
					 <?php print $block_right ?>
				   </div>
				   <?php endif ?>
                 <div class="clear"></div>
               </div>
               <div class="clear"></div>
		    </div>
            <?php /*End of Main Content area */?>
	   <div class="clear"></div>
	  </div>
	  <div class="clear"></div>
    </div>
	<div id="footer"><?php print $custom_footer ?></div>
	  <?php print $closure ?>
  </body>
</html> 
