
$(document).ready(function(){
        
    $.validator.addMethod(
        "selectNone",
        function(value, element) {
            if (element.value == "none"){
                return false;
            }else{
                return true;
            }
        }, "Please select an option."
        );
            
      $.validator.addMethod(
        "selectEmpty",
        function(value, element) {
            if (element.value == "0"){
                return false;
            }else{
                return true;
            }
        }, "Please select an option."
        );       

         
    $.validator.addMethod("regex",function(value,element,regexp){
        var re= new RegExp(regexp);
        return this.optional(element) || re.test(value);
    },"The Username field may only contain alpha-numeric characters");

/* Landing Page login << */
    $('#loginAccountFrm').validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
                messages: {
            }
        },

        highlight: function(label) {
        },
        success: function(label) {
        }
    });
   /* Landing Page Login << */
   
      /* registration Validation << */
    $('#registrationFrm').validate({
        rules: {
            fname: {
                required: true
            },
            lname: {
                required: true
            },
            emailId: {
                required: true,
                email: true
            },
            password:{
                required:true
            },
            confirm_password:{
                equalTo:"#password"
            },
            messages: {
            }
        },
       
        highlight: function(label) {
        },
        success: function(label) {
        }
    });
    /* registration Validation >> */
    
}); // end document.ready