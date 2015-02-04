if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
 addEventListener('load', function() {
  setTimeout(hideAddressBar, 0);
 }, false);
 function hideAddressBar() {
 window.scrollTo(0, 0);
 }
 
 $(window).scroll(function() {
        if($(this).scrollTop() != 0) {
            $('#toTop').fadeIn();    
        } else {
            $('#toTop').fadeOut();
        }
    });

    $('#toTop').click(function() {
        $('body,html').animate({scrollTop:0},800);
    });
    
    
    
}


        
