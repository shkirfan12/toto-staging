//Global variables


function getajaxResponse(action, senddata)
{		//alert(senddata);
	var resp;
		resp = $.ajax({
		 	type: "POST",
		 	 url: action,
		 	 cache: false,
		 	 global: false,
		 	data: senddata,
                        //contentType: "application/json",
		 	dataType: "text",
		 	async:false,
		 	success:function(result){
	    		return result;
	  	}
  		}).responseText;
  		return resp;
}

function getajaxResponseByGet(action, senddata)
{		//alert(senddata);
	var resp;
		resp = $.ajax({
		 	type: "GET",
		 	 url: action,
		 	 cache: false,
		 	 global: false,
		 	data: senddata,
                       // contentType: "application/json",
		 	dataType: "html",
		 	async:false,
		 	success:function(result){
	    		return result;
	  	}
  		}).responseText;
  		return resp;
}

function getajaxResponseJSON(action, senddata)
{      
    var resp;
        resp = $.ajax({
             type: "POST",
              url: action,
              cache: false,
              global: false,
             data: senddata,
             dataType: "json",
             async:false,
             success:function(result){
                return result;
          }
          }).responseText;
          return resp;
}
