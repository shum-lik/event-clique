<div id="siginFormWrap">
	<blockquote id="signinSection">
		<h2>Sign In</h2>
        <?php print drupal_render($rendered) ?>
	</blockquote><!--/signinSection-->
		<!-- <span class="separator">or</span> -->
	<!--<blockquote id="signinUsingSection">
		<h2>Sign In Using</h2>
        <?php print $alternate 
        ?>
		<div id="fb-root"></div>
		<script src="http://connect.facebook.net/en_US/all.js"></script>
		<script>
         FB.init({ 
            appId:'168774496512279', cookie:true, 
            status:true, xfbml:true 
         });
		</script> -->
		<!-- <fb:login-button autologoutlink="true" size="medium" background="white" length="short" perms="email,user_checkins"></fb:login-button> -->
	<!--</blockquote>--><!--/signinUsingSection-->
	
	<!--<blockquote id="promoterSection">
    	<img alt="Promoter" src="<?php print base_path().path_to_theme() ?>/img/layout/promoterIcon.png">
    	<p>Not a registered Promoter?</p>
    	<a title="Sign Up here" href="promoter.html">Sign Up here</a>
    </blockquote>--><!--/promoterSection-->
</div>
