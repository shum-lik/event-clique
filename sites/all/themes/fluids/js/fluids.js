$(document).ready(function(){
  $("#signInPopup").fancybox({
    'scrolling' : 'no',
    'titleShow' : false,
    'transitionIn' : 'none',
    'transitionOut' : 'none'
    }); 
  $(".popup").fancybox({
    'scrolling' : 'no',
    'titleShow' : false,
    'transitionIn' : 'none',
    'transitionOut' : 'none'
    }); 
  $("#commentSignInPopup").fancybox({
    'scrolling' : 'no',
    'titleShow' : false,
    'transitionIn' : 'none',
    'transitionOut' : 'none'
    });
  $('#block-right .block').corner();
  
  /* Featured Events */
	$('#featuredEventsWrap #slider').easySlider({
	auto: true,
	continuous: true,
	speed: 1000,
	pause: 2500
	}); 
  
  
  setCycle();
  setJCarousel();
  $('p#advanced a').click(function(){
    if($('.advancedToggle:visible').length>0){
      $('.advancedToggle').hide();
    }
    else{
      $('.advancedToggle').show();
    }
  });
  
  
  // event text area comment wording
  $("#comment-form").submit(function() {
      if ($("#edit-comment").val() == "Post your comment/review here") {
        return false;
      }
      else
      	return true;
    });

  $("#edit-comment").click(function() {
	$(this).css("color","#000000");
	if($(this).val() == "Post your comment/review here"){
		$(this).val("");
	}
  })
  $("#edit-comment").blur(function() {
	if($(this).val() == ""){
		$(this).val("Post your comment/review here");
		$(this).css("color","#c0c0c0");
	}
  }) 


});

function setCycle(){
	$('.jcycle-container').each(function(){
      $(this).cycle({fx: 'fade'});
	});
}

function setCycleControl(cycleName,controlName){
  $(controlName+' .jcycle-control img').each(function(i){
    $(this).click(function(){
	  $(cycleName+' .jcycle-container').cycle(i);
	});
  })
}

function setJCarousel(){
  if($('.jcarousel-container').length>0){
    $('.jcarousel-container').jcarousel({visible:5});
  }
}

function toggleSignIn(){

}

