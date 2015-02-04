/*function validateUser()
{ 
    var txtusr = document.getElementById('txtusername').value;
    var txtpass = document.getElementById('txtpassword').value;
    if(txtusr =="" || txtpass =="")
      {
      alert ("Please enter the username or password");
      return false;
      }
    
}

function validatePassword()
{
    var txtId = document.getElementById('uid').value;
    var txtpass1 = document.getElementById('txtnewpass').value;
    var txtpass2 = document.getElementById('txtconfpass').value;
    if(txtpass1 != txtpass2 ){
        
            alert("Password doesn't match")
            return false;
        }
        else if(txtpass1=="" || txtpass2==""){
            alert("Password cannot be blank")
            return false;
        }
        else{
            var action = "/admin/user-management/resetpassword";
            var sendata = "uid=" +txtId+"&pass="+txtpass1;
            var resp = getajaxResponse(action, sendata);
            alert("Password has been successfully updated!");
            
            alert("Sending mail, please wait...");
            action = "/admin/user-management/sendmail";
            sendata = "email=" +txtId+"&pass="+password;
            var mailRes = getajaxResponse(action, sendata);
            
            window.location = "/admin/user-management/manageuser/uid/"+txtId;
        }
}

// Validation for Adding/Editing a new class
function validateNewClass()
{
    
    var frm =  document.getElementById('frmnewclass');
     
     if (document.getElementById("txtprogname").value == "") {
            alert("Please Enter Program Name");
            document.getElementById("txtprogname").focus();
            return false;
        }
     else if (document.getElementById("txtcounties").value == "") {
            alert("Please Enter Counties");
            document.getElementById("txtcounties").focus();
            return false;
        }
     else if (document.getElementById("txtcity").value == "") {
            alert("Please Enter City");
            document.getElementById("txtcity").focus();
            return false;
        }
     else if (document.getElementById("txtstartdate").value == "") {
            alert("Please Enter Class Start Date");
            document.getElementById("txtstartdate").focus();
            return false;
        }
     else if (document.getElementById("txtenddate").value == "") {
            alert("Please Enter Class End Date");
            document.getElementById("txtenddate").focus();
            return false;
        }
     else if (document.getElementById("txtstarttime").value == "") {
            alert("Please Enter Start Time");
            document.getElementById("txtstarttime").focus();
            return false;
        }
     else if (document.getElementById("txtendtime").value == "") {
            alert("Please Enter End Time");
            document.getElementById("txtendtime").focus();
            return false;
        }
    else
    
      {
          frm.Submit();
          return true;
      }
       
}

// cancel action on new class page
function cancelClass()
{
    window.location.href = "/admin/class-management/classdb";
}

//validation on Advance search box
function validateAdvanceSearch()
{
    var frm =  document.getElementById('frmAdvanceSearch');
    var keyword = document.getElementById("txtSearchkeyword").value;
    var start = document.getElementById("txtstart").value;
    var end = document.getElementById("txtend").value;
    var loc = document.getElementById("txtLocation").value;
    
    if (document.getElementById("txtSearchkeyword").value == "") {
            alert("Please enter the Keyword");
            document.getElementById("txtSearchkeyword").focus();
            return false;
        }
   
    else if (document.getElementById("txtstart").value == "") {
            alert("Please enter the start date");
            document.getElementById("txtstart").focus();
            return false;
        }
    else if (document.getElementById("txtend").value == "") {
            alert("Please enter the end date");
            document.getElementById("txtend").focus();
            return false;
        }
    else if (document.getElementById("txtLocation").value == "") {
            alert("Please select the location");
            document.getElementById("txtLocation").focus();
            return false;
        }
    else
        {
            
            parent.$.fn.colorbox.close();
            
        }
}

function resetAll()
{
    var elements = document.getElementsByTagName("input");
    
    for (var ii=0; ii < elements.length; ii++) 
    {
      if (elements[ii].type == "text") 
      {
        elements[ii].value = "";
      }
      if (elements[ii].type == "checkbox") 
      {
        elements[ii].checked = false;
      }
    }
}*/
function getajaxResponse(action, senddata)
{		//alert(senddata);
	var resp;
		resp = $.ajax({
		 	 type: "POST",
		 	 url: action,
		 	 cache: false,
		 	 global: false,
		 	 data: senddata,
		 	 dataType: "html",
		 	 async:false,
		 	 success:function(result){
	    	 return result;
	  	}
  		}).responseText;
                return resp;
}


 /*function searchTable(inputVal)
{
    var table = $('#upcoming_table,#completed_table');
    table.find('tr').each(function(index, row)
    {
        var allCells = $(row).find('td');
        if(allCells.length > 0)
        {
            var found = false;
            allCells.each(function(index, td)
            {
                    var regExp = new RegExp(inputVal, 'i');
                    if(regExp.test($(td).text()))
                    {
                            found = true;
                            return false;
                    }
            });
            if(found == true)$(row).show();else $(row).hide();
        }
    });
}
 function searchTableUser(inputVal, table , className)
{ //alert(inputVal+'--'+table+'--'+className); return false;
    var table = $(table);
    var ii = parseInt("0");
    table.find('tr').each(function(index, row)
    {
        var allCells = $(row).find('td');
        if(allCells.length > 0)
        {
            var found = false;
            allCells.each(function(index, td)
            {
                    var regExp = new RegExp(inputVal, 'i');
                    if(regExp.test($(td).text()))
                    {
                            found = true;
                            return false;
                    }
            });
            if(found == true){
                ii++;
               // alert(ii);
                $(row).show();
            }else {
                $(row).hide();
           }
        }
    });
    $("."+className+"").html(ii);
}*/


