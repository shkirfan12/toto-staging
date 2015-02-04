$(document).ready(function() {
    
     /* $('#saveTheme').click(function() {  
       // var Callbacks           = $('#Callbacks').val();
      //  var Callbacksbody       = $('#Callbacksbody').val();
     //   var Callbacksfooter     = $('#Callbacksfooter').val();
    //    var CallbacksFont       = $('#CallbacksFont').val();
     var hiddenPresetId       = $('#hidden_presetId').val();
      var copy1Text       = $('#copy1_text').val();
      var copy2Text       = $('#copy2_text').val();
        var Callbacksbodyfont   = $('#Callbacksbodyfont').val();
        var Callbacksfooterfont = $('#Callbacksfooterfont').val();
      //  alert(Callbacksbodyfont+'---'+Callbacksfooterfont+'---'+copy1Text+'---'+copy2Text);  return false;
        var action   = "/admin/landing-page/themesave";
        var senddata = "Callbacksbodyfont="+Callbacksbodyfont+"&Callbacksfooterfont="+Callbacksfooterfont+"&copy1Text="+copy1Text+"&copy2Text="+copy2Text;
        var response = getajaxResponse(action, senddata); 
        if(response){
            alert("Theme is applied !");
            window.location = "/admin/landing-page/index/id/"+hiddenPresetId;
        }
    });*/
    $('#saveTheme').click(function() {  
       var hiddenPresetId       = $('#hidden_presetId').val();
        var copy1Text       = $('#copy1_text').val();
        var copy2Text       = $('#copy2_text').val();
        var startSearchContent = $('#start_search_content').val();
        var Callbacksbodyfont   = $('#Callbacksbodyfont').val();
        var Callbacksfooterfont = $('#Callbacksfooterfont').val();
        var CallbacksSearchfont = $('#CallbacksSearchfont').val();
         // alert(Callbacksbodyfont+'---'+Callbacksfooterfont+'---'+copy1Text+'---'+copy2Text+'---'+startSearchContent);  return false;
         $.ajax({
            type: "POST",
            url: "/admin/landing-page/themesave",
            data: {
                Callbacksbodyfont :Callbacksbodyfont , 
                Callbacksfooterfont :Callbacksfooterfont , 
                CallbacksSearchfont :CallbacksSearchfont , 
                copy1Text :copy1Text , 
                copy2Text : copy2Text,
                startSearchContent : startSearchContent
            },
            success: function(data){ //alert(data); return false;
                alert("Your changes have been successfully saved.");
                window.location = "/admin/landing-page/index/id/"+hiddenPresetId;
            },
            error: function(x,e){
                alert("error occur");
            }
        });
    });
     $('#resetTheme').click(function() {  
          var action   = "/admin/theme/reset-theme";
          var senddata = "reset=reset";
          var response = getajaxResponse(action, senddata); 
          if(response){ 
            alert("Theme reset succesfully!");
            window.location = "/admin/theme";
          }
     });
});
