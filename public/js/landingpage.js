 $(document).ready(function(){
  
        /**** Jpicker << *****/
        var LiveCallbackElement = $('#Live'),
        LiveCallbackButton = $('#LiveButton');  // you don't want it searching this on every live callback!!!
//        $('#Callbacks').jPicker(
//        {},
//        function(color, context){
//            var all = color.val('all');
//            //  alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
//            $('#Commit').css(
//            {
//                backgroundColor: all && '#' + all.hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
//            var hex = color.val('hex');
//            LiveCallbackElement.css(
//            {
//                backgroundColor: hex && '#' + hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            //alert('"Cancel" Button Clicked');
//        });
        
        ////////////////////////////////*** FONT COlOR /////////////
//        var fontelement = $('LiveFNT');
//        
//        $('#CallbacksFont').jPicker(
//        {},
//        function(color, context){
//            var all = color.val('all');
//            //  alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
//            $('#Commit').css(
//            {
//                backgroundColor: all && '#' + all.hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
//            var hex = color.val('hex');
//            //$('#Live h1').css(
//            $('#Live').css(
//            {
//                color: hex && '#' + hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            //alert('"Cancel" Button Clicked');
//        });
//       
        ////////////////////////////////*** FONT COlOR /////////////
        
//        $('#LiveButton').click(
//        function(){
//            $.jPicker.List[0].color.active.val('hex', 'e2ddcf', this);
//        });
//        
        // #################################### body
        
//        var LiveCallbackElement1 = $('#Livebody'),
//        LiveCallbackButton = $('#LiveButton');  // you don't want it searching this on every live callback!!!
//        $('#Callbacksbody').jPicker(
//        {},
//        function(color, context){
//            var all = color.val('all');
//            // alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
//            $('#Commit').css(
//            {
//                backgroundColor: all && '#' + all.hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
//            var hex = color.val('hex');
//            LiveCallbackElement1.css(
//            {
//                backgroundColor: hex && '#' + hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            //alert('"Cancel" Button Clicked');
//        });   
        
        ////////////////////////////////*** FONT COlOR /////////////
        var fontelement = $('LiveFNT');
        
        $('#Callbacksbodyfont').jPicker(
        {},
        function(color, context){
            var all = color.val('all');
            //  alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
            $('#Commit').css(
            {
                backgroundColor: all && '#' + all.hex || 'transparent'
            }); // prevent IE from throwing exception if hex is empty
        },
        function(color, context){
            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
            var hex = color.val('hex');
            //$('#Livebody h1').css( 
//             $('#Livebody .theme-survey-set-block p').css( 
//            {
//                color: hex && '#' + hex || 'transparent'
//            });
            $('#Live').css( 
            {
                color: hex && '#' + hex || 'transparent'
            }); // prevent IE from throwing exception if hex is empty
        },
        function(color, context){
            //alert('"Cancel" Button Clicked');
        });
        
        ////////////////////////////////*** FONT COlOR /////////////
        
        $('#LiveButton').click(
        function(){
            $.jPicker.List[0].color.active.val('hex', 'e2ddcf', this);
        });
        
        //############################## footer
        
//        var LiveCallbackElement2 = $('#Livefooter'),
//        LiveCallbackButton = $('#LiveButton');  // you don't want it searching this on every live callback!!!
//        $('#Callbacksfooter').jPicker(
//        {},
//        function(color, context){
//            var all = color.val('all');
//            // alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
//            $('#Commit').css(
//            {
//                backgroundColor: all && '#' + all.hex || 'transparent',
//                Color: all && '#' + all.hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
//            var hex = color.val('hex');
//            LiveCallbackElement2.css(
//            {
//                backgroundColor: hex && '#' + hex || 'transparent'
//            }); // prevent IE from throwing exception if hex is empty
//        },
//        function(color, context){
//            //alert('"Cancel" Button Clicked');
//        });  
        
        ////////////////////////////////*** FONT COlOR /////////////
        var fontelement = $('LiveFNT');
        
        $('#Callbacksfooterfont').jPicker(
        {},
        function(color, context){
            var all = color.val('all');
            //  alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
            $('#Commit').css(
            {
                backgroundColor: all && '#' + all.hex || 'transparent'
            }); // prevent IE from throwing exception if hex is empty
        },
        function(color, context)  {
            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
            var hex = color.val('hex');
           $('#Live2').css(
            {
                color: hex && '#' + hex || 'transparent'
            }); // prevent IE from throwing exception if hex is empty
        },
        function(color, context){
            //alert('"Cancel" Button Clicked');
        });
       
        ////////////////////////////////*** FONT COlOR /////////////
        
        $('#LiveButton').click(
        function(){
            $.jPicker.List[0].color.active.val('hex', 'e2ddcf', this);
        });
        
        //Search Banner<<
          var fontelement = $('LiveFNT');
        
        $('#CallbacksSearchfont').jPicker(
        {},
        function(color, context){
            var all = color.val('all');
            //  alert('Color chosen - hex: ' + (all && '#' + all.hex || 'none') + ' - alpha: ' + (all && all.a + '%' || 'none'));
            $('#Commit').css(
            {
                backgroundColor: all && '#' + all.hex || 'transparent'
            }); // prevent IE from throwing exception if hex is empty
        },
        function(color, context)  {
            if (context == LiveCallbackButton.get(0)) alert('Color set from button');
            var hex = color.val('hex');
           $('#Live2').css(
            {
                color: hex && '#' + hex || 'transparent'
            }); // prevent IE from throwing exception if hex is empty
        },
        function(color, context){
            //alert('"Cancel" Button Clicked');
        });
       
        ////////////////////////////////*** FONT COlOR /////////////
        
        $('#LiveButton').click(
        function(){
            $.jPicker.List[0].color.active.val('hex', 'e2ddcf', this);
        });
        //Search Banner>>
        /*** Jpicker >> *****/
});
