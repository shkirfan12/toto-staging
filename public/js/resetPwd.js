function printErrors(arrErrors)
{
    str = '';
    for(i=0; i < arrErrors.length; i++) {
        str += '* ' + arrErrors[i] + '<br/>' ;
    }
    document.getElementById('errors').style.display = 'block';
    document.getElementById('errors').innerHTML = str + '<br/>';
}

function printPassErrors(arrErrors)
{
    str = '';
    for(i=0; i < arrErrors.length; i++) {
      //  str += '* ' + arrErrors[i] + '<br/>' ;
         str += arrErrors[i];
    }
    alert(str);
  //  document.getElementById('passerrors').style.display = 'block';
  //  document.getElementById('passerrors').innerHTML = str + '<br/>';
}
function checkVal(o,n) {
    if ( o.val() == 0) {
        o.addClass('ui-state-error');
          alert(n + " can not be blank.");
      //  updateTips(n + " can not be blank.");
        return false;
    } else {
        return true;
    }
}
function checkLength(o,n,min,max)
{
    if ( o.val().length > max || o.val().length < min ) {
        o.addClass('ui-state-error');
           alert("Length of " + n + " must be between "+min+" and "+max);
      //  updateTips("Length of " + n + " must be between "+min+" and "+max);
        return false;
    } else {
        return true;
    }
}
function updateTips(t) {
    var tips = $(".emailError");
    $("#emailError")
    .text(t)
    .show()
    .addClass('ui-state-highlight');
    setTimeout(function() {
        tips.removeClass('ui-state-highlight', 1500);
    }, 500);
}
function getDialogButton( dialog_selector, button_name )
{
    var buttons = $( dialog_selector + ' .ui-dialog-buttonpane button' );
    for ( var i = 0; i < buttons.length; ++i )
    {
        var jButton = $( buttons[i] );
        if ( jButton.text() == button_name )
        {
            return jButton;
        }
    }
    return null;
}
function validatePassword()
{
    var bValid = true;
    bValid = bValid && checkVal($("#pwdOldPwd"),"Old Password");

    if(bValid && !checkLength($("#pwdNewPwd"),"New Password",8,16))
    {
         alert("New password needs to be between 8 and 16 characters");
     //   updateTips("New password needs to be between 8 and 16 characters");
        bValid =  false;
    }

    if($("#pwdNewPwd").val() != $("#pwdConfirmPwd").val()){
        alert('Passwords do not match');
        //updateTips('Passwords do not match');
        bValid = false;
    }
    return bValid;
}


function changePasswordNew()
{ 
    
    var email = $("#oldPwdError");
    var allFields = $([]).add(email);
    var tips = $(".emailError");
    
    if(validatePassword()){
                      
                        var pwdOldPwd = document.getElementById('pwdOldPwd').value;
                        var pwdNewPwd = document.getElementById('pwdNewPwd').value;
                        var pwdConfirmPwd = document.getElementById('pwdConfirmPwd').value;
//                       alert(pwdOldPwd);
//                         alert(pwdNewPwd);
//                           alert(pwdConfirmPwd);
                        var dataString = 'hidAction=changepwd&pwdOldPwd='+pwdOldPwd+'&pwdNewPwd='+pwdNewPwd+'&pwdConfirmPwd='+pwdConfirmPwd;
//                        alert(dataString);
//                        return false;
                        $.ajax({
                            type: 'POST',
                            url : "/my-profile/ajax-password",
                            data: dataString,
                            success : function(data) { 
                                if(data=='Success') {
                                    //$('#successMsg').show().text('Your password has been updated successfully. We have sent a confirmation to your email address.');
                                  alert('Your password has been updated successfully.');
                                    window.location="/project";
                                  //  $('#successMsg').show().text('Your password has been updated successfully.');
                                    /* Send Acknowlegment Mail to user << */
                                     $.ajax({
                                        type:"GET",
                                        url: "/my-profile/ajax-ack-reset-pwd/fname/"+$('#txtfname').val()+"/email/"+$('#email').val(),
                                        success: function(result) {
                                         //$('#successMsg').show().text(result);
                                        },
                                        error:function(event, request, settings){
                                            return false;
                                        }
                                    });
                                    /* Send Acknowlegment Mail to user >> */
                                }else if(data=='Error'){
                                        alert('Sorry we are unable to your change password at this moment !');
                               //     updateTips('Sorry we are unable to your change password at this moment !');
                                    return false;
                                }else if(data=='Email Failed'){
                                      alert('Your Password has been updated, but there was some error in sending Email !');
                                //    updateTips('Your Password has been updated, but there was some error in sending Email !');
                                    return false;
                                }else {
                                    arrErrors = eval(data);
                                    printPassErrors(arrErrors);
                                }
                            },
                            error:function(event, request, settings){
                                $('#emailError').test('There was some error in setting password.');
                                return false;
                            }
                        });
                    }
}
   
//
//function changePassword()
//{ 
//    var email = $("#oldPwdError");
//    var allFields = $([]).add(email);
//    var tips = $(".emailError");
//    $(function() {
//        $("#dialog").dialog("destroy");
//        $("#dialog-form").dialog({
//            autoOpen: false,
//            dialogClass : 'dialog1',
//            height: 327,
//            width: 280,
//            modal: true,
//            buttons: {
//                Cancel : function() {
//                    $(this).dialog('close');
//                },
//               'Continue ' : function() {
//                    if(validatePassword()){
//                        $("#emailError").removeClass('ui-state-error').hide();
//                        $("#loading")
//                        .ajaxStart(function(){
//                            $("#divMain").hide();
//                            $(this).show();
//                        })
//                        .ajaxComplete(function(){
//                            $("#loading").hide();
//                            setTimeout("$('#dialog-form').dialog('close');",4000);
//                        });
//
//                        $("#emailError").removeClass('ui-state-error').hide();
//                        var pwdOldPwd = document.getElementById('pwdOldPwd').value;
//                        var pwdNewPwd = document.getElementById('pwdNewPwd').value;
//                        var pwdConfirmPwd = document.getElementById('pwdConfirmPwd').value;
//                        var dataString = 'hidAction=changepwd&pwdOldPwd='+pwdOldPwd+'&pwdNewPwd='+pwdNewPwd+'&pwdConfirmPwd='+pwdConfirmPwd;
//                        var button = getDialogButton( '.dialog1', 'Continue' );
//                        if ( button )
//                            button.attr('disabled', 'disabled' ).addClass( 'ui-state-disabled' );
//                        $.ajax({
//                            type: 'POST',
//                            url : "/my-profile/ajax-password",
//                            data: dataString,
//                            success : function(data) { 
//                                if(data=='Success') {
//                                    //$('#successMsg').show().text('Your password has been updated successfully. We have sent a confirmation to your email address.');
//                                    $('#successMsg').show().text('Your password has been updated successfully.');
//                                    /* Send Acknowlegment Mail to user << */
//                                     $.ajax({
//                                        type:"GET",
//                                        url: "/my-profile/ajax-ack-reset-pwd/fname/"+$('#txtfname').val()+"/email/"+$('#email').val(),
//                                        success: function(result) {
//                                         //$('#successMsg').show().text(result);
//                                        },
//                                        error:function(event, request, settings){
//                                            return false;
//                                        }
//                                    });
//                                    /* Send Acknowlegment Mail to user >> */
//                                }else if(data=='Error'){
//                                    updateTips('Sorry we are unable to your change password at this moment !');
//                                    return false;
//                                }else if(data=='Email Failed'){
//                                    updateTips('Your Password has been updated, but there was some error in sending Email !');
//                                    return false;
//                                }else {
//                                    arrErrors = eval(data);
//                                    printPassErrors(arrErrors);
//                                }
//                            },
//                            error:function(event, request, settings){
//                                $('#emailError').test('There was some error in setting password.');
//                                return false;
//                            }
//                        });
//                    }
//                }
//            },
//            close: function() {
//                allFields.val('').removeClass('ui-state-error');
//            }
//        });
//
//        $('#dialog-form').html("");
//        $('#dialog-form').load('/my-profile/change-password').dialog('open');
//        var button = getDialogButton( '.dialog1', 'Continue' );
//        if ( button )
//        {
//            button.attr('disabled', '' ).removeClass( 'ui-state-disabled' );
//        }
//
//    });
//}
