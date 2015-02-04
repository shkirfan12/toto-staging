
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


    $.validator.addMethod(
        "hearPgmNone",
        function(value, element) {
            if (element.value == "none"){
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


    /* Account Validation << */
    $('#contact-form').validate({
        
        rules: {
            txtfname: {
                required: true
            },
            txtlname: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            confirm_email: {
                equalTo:"#email"
            },
            password:{
                required:true
            },
            confirm_password:{
                equalTo:"#password"
            },
            address: {
                minlength: 2,
                required: true
            },
            city: {
                minlength: 2,
                required: true
            },
            states: {
                selectNone: true
            },
            zip: {
                minlength: 5,
                required: true
            },
            ssn: {
                minlength: 4,
                required: true
            },
            emergency_contact_name:{
                required: true
            },
            hearPgm:{
                hearPgmNone: true
            },
            relationship:{
                required: true
            },
            daytime_phone:{
                required: true
            },
            /* Provider My-Account << */
            txtPgmName: {
                required: true
            },
            txtDirectorName: {
                required: true
            },
            address1: {
                required: true
            },
             txtWebSite: {
                required: true
            },
             txtFax: {
                required: true
            },
             currentEnrol: {
                required: true
            },
             txtStartLicense: {
                required: true
            },
            /* Provider My-Account >> */
           
            messages: {
            }
        },
       
        highlight: function(label) {
        },
        success: function(label) {
        }
    });
    /* Account Validation >> */

    /* Education Validation << */
    $('#educationFrm').validate({
        errorPlacement: $.datepicker.errorPlacement, 
        rules: {
            sch_name1:{
                required: true
            },
            city1:{
                required: true
            },
            state_id1:{
                required: true
            },
            country_id1: {
                selectEmpty: true
            },
            received1: {
                selectNone: true
            },
            date1:{
                required: true,
                dpDate: true ,
                dpCompareDate: ['before', '#to1'] 
            },
            to1:{
                required: true,
                dpDate: true ,
                dpCompareDate: {after: '#date1'} 
            },
            date2:{
                dpDate: true ,
                dpCompareDate: ['before', '#to2'] 
            }, 
            to2: { 
                dpDate: true ,
                dpCompareDate: {'after': '#date2'} 
            }, 
            date3: { 
                dpDate: true ,
                dpCompareDate: ['before', '#to3'] 
            }, 
            to3: { 
                dpDate: true ,
                dpCompareDate: {'after': '#date3'} 
            }, 
            date4: { 
                dpDate: true ,
                dpCompareDate: ['before', '#to4'] 
            }, 
            to4: { 
                dpDate: true ,
                dpCompareDate: {'after': '#date4'} 
            }, 
            messages: {
            }
        },

        highlight: function(label) {
       },
        success: function(label) {
        }
    });
    /* Education Validataion >> */

    /* Emp History << */
    $('#empHistoryFrm').validate({
        errorPlacement: $.datepicker.errorPlacement, 
        rules: {
            eh_name1:{
                required: true
            },
            eh_add1:{
                required: true
            },
            eh_job_duties1:{
                required: true
            },
            eh_supervisor1: {
               required: true
            },
            eh_phone1:{
                required: true
            },
            eh_reason_leaving1:{
                //required: true
                required: function(element) {
                    return ($("input[name='checkbox']:checked").length == 0);
                }
                
//                http://stackoverflow.com/questions/10943530/jquery-form-validation-field-required-if-checkbox-selected
//                required: {       
//      depends: function(eh_currentaly_emp1) {
//        return $("input[name='checkbox']:checked").length == 0
//      }
            },
            eh_date1: { 
                required: true,
                dpDate: true ,
                dpCompareDate: ['before', '#eh_to1'] 
            }, 
            eh_to1: { 
                required: true,
                dpDate: true ,
                dpCompareDate: {after: '#eh_date1'} 
            }, 
            eh_date2: { 
                dpDate: true ,
                dpCompareDate: ['before', '#eh_to2'] 
            }, 
            eh_to2: { 
                dpDate: true ,
                dpCompareDate: {after: '#eh_date2'} 
            }, 
            eh_date3: { 
                dpDate: true ,
                dpCompareDate: ['before', '#eh_to3'] 
            }, 
            eh_to3: { 
                dpDate: true ,
                dpCompareDate: {after: '#eh_date3'} 
            }, 
            messages: {
            }
        },

        highlight: function(label) {
        },
        success: function(label) {
        }
    });
/* Emp History >> */

/* Reference  Validation << */
  $('#referenceFrm').validate({
       rules: {
            ref_name1:{
                required: true
            },
            ref_name2:{
                required: true
            },
            ref_name3:{
                required: true
            },
            ref_phone1: {
               required: true
            },
            ref_phone2:{
                required: true
            },
            ref_phone3:{
                required: true
            },
            ref_add1: { 
                required: true
             }, 
            ref_add2: { 
                required: true
            }, 
            ref_add3: { 
               required: true
            }, 
            ref_occupation1: { 
                required: true
            }, 
            ref_occupation2: { 
                required: true
            }, 
            ref_occupation3: { 
               required: true
            },
            ref_relation1: { 
                required: true
            }, 
            ref_relation2: { 
               required: true
            }, 
            ref_relation3: { 
               required: true
            }, 
            messages: {
            ref_name1: 'Please enter a date after the previous value'
            }
        },
      highlight: function(label) {
        },
        success: function(label) {
        }
   });
/* Reference Validation >> */

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
             userType: {
                selectNone: true
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
}); // end document.ready