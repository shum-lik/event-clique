// Global vars

var simple_fbwall_logged_in = false;
var simple_fbwall_payload = {};
var simple_fbwall_boxy = null;
var simple_fbwall_error_state = false;

// Init
$(function() {
	// Create our boxy dialog
	simple_fbwall_boxy = new Boxy( '', {title: "Post to your Facebook Wall", modal: true, show: false});
	simple_fbwall_boxy.boxy.attr( 'id', 'simple-fbwall-boxy' );

	// Add the fb-root element for the Facebook API
	$('body').append('<div id="fb-root"></div>');

	// Build the markup for our boxy dialog
	$('body').append('<div id="simple_fbwall_dialog"><div id="simple_fbwall_dialog_auth"></div><div id="simple_fbwall_dialog_noauth"></div></div>');
	$('div#simple_fbwall_dialog_noauth').append('<p>'+Drupal.settings.simple_fbwall.login_msg+'</p><p><fb:login-button autologoutlink="true" perms="publish_stream"></fb:login-button></p>');
	$('div#simple_fbwall_dialog_auth').append('<div id="simple_fbwall_image"></div><div id="simple_fbwall_form_wrapper"><form id="simple_fbwall_form"><p>The message you are posting to your facebook wall can be edited below</p><textarea id="simple_fbwall_message" rows="6" cols="40"></textarea><br /><input type="submit" id="simple_fbwall_submit" class="simple_fbwall_button" name="simple_fbwall_submit" value="Share" /></form></div>');
	$('div#simple_fbwall_dialog').append('<div id="simple_fbwall_loading"><img src="/'+Drupal.settings.simple_fbwall.module_path+'ajax-loader.gif" alt="AJAX Loading" /></div>');
	$('div#simple_fbwall_dialog').append('<div id="simple_fbwall_success">'+Drupal.settings.simple_fbwall.success_msg+'</div>');
	$('div#simple_fbwall_dialog').append('<div id="simple_fbwall_error">An error occured while posting to your facebook wall. Please refresh the page and try again.</div>');
	simple_fbwall_boxy.setContent( $('div#simple_fbwall_dialog') );

	// Bind to the submit event of the simple facebook wall post form (the one that shows in the boxy dialog)
	$('form#simple_fbwall_form').bind( 'submit', simple_fbwall_submit );

	// Include the facebook API
	(function() {
		var e = document.createElement('script');
		e.type = 'text/javascript';
		e.src = document.location.protocol +
			'//connect.facebook.net/en_US/all.js';
		e.async = true;
		document.getElementById('fb-root').appendChild(e);
	}());

	// Bind to the submit event of the simple facebook wall post button form (the one that triggers the boxy dialog to appear)
	$('form.simple_fbwall_button_form').bind( 'submit', simple_fbwall_button_submit );
});

// Shows the AJAX throbber
function simple_fbwall_showLoading( )
{
	$('div#simple_fbwall_dialog').children( ).hide( );
	$('div#simple_fbwall_loading').show( );
}

// Hides the AJAX throbber and shows what matches the selector argument
function simple_fbwall_hideLoading( selector )
{
	$('div#simple_fbwall_loading').hide( );
	$(selector).show( );
}

// Submit handler for the simple facebook wall post form (the one that shows in the boxy dialog)
function simple_fbwall_submit( e )
{
	simple_fbwall_payload.message = $('textarea#simple_fbwall_message').val( );
	simple_fbwall_publish( );

	// Prevent the form from actually submitting
	return false;
}

/**
 * This function checks to see if the user is logged in or if the facebook authentication is in an
 * error state and shows the appropriate content accordingly
 */
function simple_fbwall_update_dialog( )
{
	if( simple_fbwall_error_state )
	{
		$('div#simple_fbwall_dialog').children( ).hide( );
		$('div#simple_fbwall_error').show( );
	}
	else
	{
		if( simple_fbwall_logged_in ) // Show the post form
		{
			$('div#simple_fbwall_dialog').children( ).hide( );
			$('div#simple_fbwall_dialog_auth').show( );
		}
		else // Show the login form
		{
			$('div#simple_fbwall_dialog').children( ).hide( );
			$('div#simple_fbwall_dialog_noauth').show( );
		}
	}
}

// Submit handler for the simple facebook wall post button form (the one that triggers the boxy dialog to appear)
function simple_fbwall_button_submit(e)
{
	var payload = {};
	payload.actions = [];
	var form = $(e.target);
	var inputs = form.find('input');

	// Look through all the inputs in the form and build our payload object using their values
	for( var i = 0; i < inputs.length; i++ )
	{
		var input = $(inputs[i]);
		var name = input.attr( 'name' );
		switch( name )
		{
			case 'message':
				payload.message = input.val();
				if( $('textarea#simple_fbwall_message').val( ) == '' )
					$('textarea#simple_fbwall_message').val( payload.message );
				break;
			case 'picture':
				payload.picture = input.val();
				$('div#simple_fbwall_image').html('<img src="'+payload.picture+'" />');
				break;
			case 'link':
				payload.link = input.val( );
				break;
			case 'name':
				payload.name = input.val( );
				break;
			case 'caption':
				payload.caption = input.val( );
				break;
			case 'description':
				payload.description = input.val( );
				break;
			default:
				if( name.match(/^action\[.*\]$/) && payload.actions.length < 1 )
				{
					var parts = input.val( ).split('|');
					payload.actions.push( {name: parts[0], link: parts[1]} );
				}
				break;
		}
	}

	// Set the payload
	simple_fbwall_payload = payload;

	// Update the dialog now that we have the payload object populated
	simple_fbwall_update_dialog( );

	// Center the boxy dialog after we set the content.
	simple_fbwall_boxy.center( );

	// Show the boxy dialog
	simple_fbwall_boxy.show( );

	// Prevent the form from actually submitting
	return false;
}

// Facebook API login event handler
function simple_fbwall_login(){
	FB.api('/me', function(response) {
		simple_fbwall_logged_in = true;
		simple_fbwall_update_dialog( );
	});
}

// Facebook API logout event handler
function simple_fbwall_logout(){
	simple_fbwall_logged_in = false;
}

// Facebook API wall post wrapper function
function simple_fbwall_publish( )
{
	simple_fbwall_showLoading( );
	FB.api('/me/feed', 'post', simple_fbwall_payload, function(response) {
		if (!response || response.error) {
			simple_fbwall_error_state = true;
			//alert('An error occured while posting to your facebook wall. Please refresh the page and try again.');
			simple_fbwall_hideLoading('div#simple_fbwall_error');
		} else {
			//alert('Post ID: ' + response.id);
			simple_fbwall_hideLoading('div#simple_fbwall_success');
		}
	});
}

// Facebook API init
window.fbAsyncInit = function() {
	FB.init({appId: Drupal.settings.simple_fbwall.appid, status: true, cookie: true, xfbml: true});

	/* All the events registered */
	FB.Event.subscribe('auth.login', function(response) {
		// do something with response
		simple_fbwall_login();
	});
	FB.Event.subscribe('auth.logout', function(response) {
		// do something with response
		simple_fbwall_logout();
	});

	FB.getLoginStatus(function(response) {
		if (response.session) {
			// logged in and connected user, someone you know
			simple_fbwall_login();
		}
		else
			simple_fbwall_logged_in = false;
	});
};
