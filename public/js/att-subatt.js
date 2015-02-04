    $().ready(function(){
        
//        $('input.myClass').prettyCheckable({
//            color: 'red'
//         });
    
      //  $('.attClass').prettyCheckable();
         $("#attTable").tablesorter({widgets: ['zebra']});
         $("#subattTable").tablesorter({widgets: ['zebra']});
         $("#attTier1").tablesorter({widgets: ['zebra']});
         $("#attTier2").tablesorter({widgets: ['zebra']});
         $("#attTag1").tablesorter({widgets: ['zebra']});
         $("#attTag2").tablesorter({widgets: ['zebra']});
         
        
       /*** table row : odd-even bgcolor << ***/ 
    //   $("#attTable tr:odd").css( "background-color", "#f2f2f2" );                 
     //  $("#subattTable tr:odd").css( "background-color", "#f2f2f2" ); 
       /*** table row : odd-even bgcolor >> ***/ 
       
       /*** chk display << ***/
       $("input[type=checkbox]").click(function(){ 
           
        var id   = $(this).attr('id');      
        var type = $(this).attr('class'); 
        var st = "";
        if ($(this).attr("checked") == "checked"){
            st = "y";
        }else{
            st = "n";
        }
        var action   = '/admin/attribute-management/att-display/';
        var data     = 'attId='+id+"&display="+st+"&type="+type; 
        var response =  getajaxResponse(action,data);
        window.location.reload();
        });
        /*** chk display >> ***/
        
        /*** Popup << ***/ 
        $(".addSubatt").fancybox({
               // title           : 'Create Application',
		maxWidth	: 900,
		maxHeight	: 700,
		fitToView	: false,
		width		: '35%',
		height		: '56%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
                 'afterClose'  : function() {
                //     window.location="/admin/index/manage";
                     window.location.reload();
                 }
                /*'onComplete' : function(){  
                    $('.apps_module').click(function(){
                        parent.$.fancybox.close();
                    });
                }*/
        }); 
        $(".addAtt").fancybox({
            	maxWidth	: 900,
		maxHeight	: 700,
		fitToView	: false,
		width		: '35%',
		height		: '56%',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
                 'afterClose'  : function() {
                     window.location.reload();
                 }/*,
                 'onComplete' : function(){  
                    $('.editSubmit').click(function(event,dataObject){ 
                        event.preventDefault();  
                        alert('here'); return false;
                        window.location.reload();
                    });
                },
                onClosed : function() {  
                    return false;  
                } */
        }); 
         /*** Popup >> ***/ 
     
    
    /*** table sort images << ***/
    //$("#attTable").tablesorter({ widgets: ['zebra'] }); 
   /*** table sort images >> ***/
   
//vidya : Master Tiers & Tags editable <<    
$("#masterTier1Name").click(function() { //Tier1
    $("#masterTier1Name").attr('style','color: #4D4D4D;');
    $("#masterTier1Name").keyup(function() {
        var masterTier1Name = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/product-management/ajax-update-master-names/masterTier1Name/"+masterTier1Name,
            success: function(data) { 
                 $("#masterTier1Name").attr('style','border:none;cursor:pointer;color: #4D4D4D;');
                  //location.reload(); 
            },
            error: function(event, request, settings) {
                return false;
            }
        });
    });
});
$("#masterTier2Name").click(function() {  //Tier2
    $("#masterTier2Name").attr('style','color: #4D4D4D;');
    $("#masterTier2Name").keyup(function() {
        var masterTier2Name = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/product-management/ajax-update-master-names/masterTier2Name/"+masterTier2Name,
            success: function(data) { 
                 $("#masterTier2Name").attr('style','border:none;cursor:pointer;color: #4D4D4D;');
                  //location.reload(); 
            },
            error: function(event, request, settings) {
                return false;
            }
        });
    });
});
$("#masterTier3Name").click(function() {  //Tier3
    $("#masterTier3Name").attr('style','color: #4D4D4D;');
    $("#masterTier3Name").keyup(function() {
        var masterTier3Name = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/product-management/ajax-update-master-names/masterTier3Name/"+masterTier3Name,
            success: function(data) { 
                 $("#masterTier3Name").attr('style','border:none;cursor:pointer;color: #4D4D4D;');
                  //location.reload(); 
            },
            error: function(event, request, settings) {
                return false;
            }
        });
    });
});
$("#masterTag1Name").click(function() { //Tag1
    $("#masterTag1Name").attr('style','color: #4D4D4D;');
    $("#masterTag1Name").keyup(function() {
        var masterTag1Name = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/product-management/ajax-update-master-names/masterTag1Name/"+masterTag1Name,
            success: function(data) { 
                 $("#masterTag1Name").attr('style','border:none;cursor:pointer;color: #4D4D4D;');
                  //location.reload(); 
            },
            error: function(event, request, settings) {
                return false;
            }
        });
    });
});
$("#masterTag2Name").click(function() { //Tag2
    $("#masterTag2Name").attr('style','color: #4D4D4D;');
    $("#masterTag2Name").keyup(function() {
        var masterTag2Name = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/product-management/ajax-update-master-names/masterTag2Name/"+masterTag2Name,
            success: function(data) { 
                 $("#masterTag2Name").attr('style','border:none;cursor:pointer;color: #4D4D4D;');
                  //location.reload(); 
            },
            error: function(event, request, settings) {
                return false;
            }
        });
    });
});
$("#masterTag3Name").click(function() { //Tag3
    $("#masterTag3Name").attr('style','color: #4D4D4D;');
    $("#masterTag3Name").keyup(function() {
        var masterTag3Name = $(this).val();
        $.ajax({
            type: "GET",
            url: "/admin/product-management/ajax-update-master-names/masterTag3Name/"+masterTag3Name,
            success: function(data) { 
                 $("#masterTag3Name").attr('style','border:none;cursor:pointer;color: #4D4D4D;');
                  //location.reload(); 
            },
            error: function(event, request, settings) {
                return false;
            }
        });
    });
});
// vidya : Master Tiers & Tags editable >>    
    });
    
    function fnDelSubAtt(id,type){
        if(type == 'att'){
            var msg = "Are you sure, you are delete this attribite? Deleting this attribute with delete all of its sub-attribute.";
        }else{
            var msg = "Are you sure, you are delete this sub-attribite?";
        }
        if(confirm(msg))
        {
            $.ajax({
                type:"GET",
                url: "/admin/attribute-management/ajax-att-del/id/"+id+"/type/"+type,
                success: function(data) {  
                    if(data == 'Delete'){
                        location.reload(); 
                    }
                },
                error:function(event, request, settings){
                    return false;
                }
            });
            
        }
    }
    function  fnFetchSubAtt(attId){
    $.ajax({
        type:"GET",
        url: "/admin/attribute-management/ajax-fetch-subatt/attId/"+attId,
        success: function(data) {  
            $('#subattTable').html(data);
            
            $("input[type=checkbox]").click(function(){ 
                var id   = $(this).attr('id'); 
                var type = $(this).attr('class'); 
                var st = "";
                if ($(this).attr("checked") == "checked"){
                    st = "y";
                }else{
                    st = "n";
                }
                var action   = '/admin/attribute-management/att-display/';
                var data     = 'attId='+id+"&display="+st+"&type="+type; 
                var response =  getajaxResponse(action,data);
            });
            
            
        },
        error:function(event, request, settings){
            return false;
        }
    });
}
