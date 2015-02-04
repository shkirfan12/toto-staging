  $().ready(function(){

               /* Search << */
               $("#searchProduct").click(function(){ 
                   $('.search-add-form').attr('onsubmit','');
                   $('#hdnSearch').attr('value','search');
                   $('.save').click();
               });
               /* $("#searchProduct").click(function(){ 
                    var tier1       = $('#tier1').val(); 
                    var tier2       = $('#tier2').val(); 
                    var tag1        = $('#tag1').val(); 
                    var tag2        = $('#tag2').val(); 
                    var productName = $('#product_name').val();
                    var productNo   = $('#product_number').val();
                    var cost        = $('#purchase_price').val();
                    
                    var action   = '/admin/product-management/ajaxsearch/';
                    var data     = 'tier1='+tier1+"&tier2="+tier2+"&tag1="+tag1+"&tag2="+tag2+"&productName="+productName+"&productNo="+productNo+"&cost="+cost; 
                    var response =  getajaxResponse(action,data);
                    $('.content-block').html(response);
                    
                });*/
                /* Search >> */
    
              //  $('select.styled').customSelect();
                $(".batchUpload").fancybox({
                    maxWidth	: 900,
                    maxHeight	: 700,
                    fitToView	: false,
                    width       : '35%',
                    height	: '56%',
                    autoSize	: false,
                    closeClick	: false,
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    'afterClose'  : function() {
                        window.location.reload();
                    }
                }); 
                
                $(".import").fancybox({
                    maxWidth	: 900,
                    maxHeight	: 700,
                    fitToView	: false,
                    width		: '35%',
                    height		: '56%',
                    autoSize	: false,
                    closeClick	: false,
                    openEffect	: 'none',
                    closeEffect	: 'none',
                    'afterClose'  : function() {
                        window.location.reload();
                    }
                }); 
                
                $('input.myClass').prettyCheckable({
                  color: 'red'
                });
                
                 $( "tr:odd" ).css( "background-color", "#f2f2f2" );
                 
                  SyntaxHighlighter.all();
                  
                 

            });
    function fnDelProduct(productId, img) {
        var msg = "Are you sure you want to delete this?";
        if (confirm(msg))
        {
            $.ajax({
                type: "GET",
                url: "/admin/product-management/ajax-product-del/productId/" + productId + "/img/" + img,
                success: function(data) {
                    if (data == 'Delete') {
                        location.reload();
                    }
                },
                error: function(event, request, settings) {
                    return false;
                }
            });

        }
    }
    function validation(){
        var errorCount  = 0;
        var alphaExp    = /^[a-zA-Z\s]+$/;
        var alphaNumExp = /^[a-zA-Z0-9\s]+$/;
        var numExp      = /^[0-9]+$/;
        
        $('#errors').html('');
        
        if(($('#tier1').val() == 'none')){
            errorCount++;
            $('#eTier1').attr('style','color:red');
        }else{
            $('#eTier1').attr('style','');
        }
        
      /*  if(($('#tier2').val() == 'none')){
            errorCount++;
            $('#eTier2').attr('style','color:red');
        }else{
            $('#eTier2').attr('style','');
        }*/
        
        if(($('#product_name').val() == '')){
            errorCount++;
             $('#ePname').attr('style','color:red');
        }else{
            $('#ePname').attr('style','');
        }
        
        if(($('#product_number').val() == '')){
            errorCount++;
            $('#ePno').attr('style','color:red');
        }else{
            $('#ePno').attr('style','');
        }
        
        if(($('#product_brand').val() == '')){
            errorCount++;
            $('#ePbrand').attr('style','color:red');
        }else{
            $('#ePbrand').attr('style','');
        }
        
        if(($('#purchase_price').val() == '')){
            errorCount++;
            $('#ePprice').attr('style','color:red');
        }else{
            $('#ePprice').attr('style','');
        }
        
        if(($('#installation_price').val() == '')){
            errorCount++;
            $('#eIprice').attr('style','color:red');
        }else{
            $('#eIprice').attr('style','');
        }
        
        if(($('#replacement_price').val() == '')){
            errorCount++;
           $('#eRcost').attr('style','color:red');
        }else{
            $('#eRcost').attr('style','');
        }
        
        if(($('#product_life_span').val() == '')){
            errorCount++;
            $('#eLspan').attr('style','color:red');
        }else{
            $('#eLspan').attr('style','');
        }
        
        if(($('#hdnProductImg').val() == '')){
            errorCount++;
            $('#eAddImg').attr('style','color:red');
        }else{
            $('#eAddImg').attr('style','');
        }
        
        if(errorCount == 0){
            return true;
        }else{
            return false;
        }
    }
    
      function baselineValidation(){
        var errorCount  = 0;
        var alphaExp    = /^[a-zA-Z\s]+$/;
        var alphaNumExp = /^[a-zA-Z0-9\s]+$/;
        var numExp      = /^[0-9]+$/;
        
        $('#errors').html('');
        
       /* if(($('#tier1').val() == 'none')){
            errorCount++;
            $('#eTier1').attr('style','color:red');
        }else{
            $('#eTier1').attr('style','');
        }
        
        if(($('#tier2').val() == 'none')){
            errorCount++;
            $('#eTier2').attr('style','color:red');
        }else{
            $('#eTier2').attr('style','');
        }*/
        
        if(($('#product_name').val() == '')){
            errorCount++;
             $('#ePname').attr('style','color:red');
        }else{
            $('#ePname').attr('style','');
        }
        
        if(($('#product_number').val() == '')){
            errorCount++;
            $('#ePno').attr('style','color:red');
        }else{
            $('#ePno').attr('style','');
        }
        
        /*if(($('#product_brand').val() == '')){
            errorCount++;
            $('#ePbrand').attr('style','color:red');
        }else{
            $('#ePbrand').attr('style','');
        }*/
        
        if(($('#purchase_price').val() == '')){
            errorCount++;
            $('#ePprice').attr('style','color:red');
        }else{
            $('#ePprice').attr('style','');
        }
        
      /*  if(($('#installation_price').val() == '')){
            errorCount++;
            $('#eIprice').attr('style','color:red');
        }else{
            $('#eIprice').attr('style','');
        }
        
        if(($('#replacement_price').val() == '')){
            errorCount++;
           $('#eRcost').attr('style','color:red');
        }else{
            $('#eRcost').attr('style','');
        }
        
        if(($('#product_life_span').val() == '')){
            errorCount++;
            $('#eLspan').attr('style','color:red');
        }else{
            $('#eLspan').attr('style','');
        }
        
        if(($('#hdnProductImg').val() == '')){
            errorCount++;
            $('#eAddImg').attr('style','color:red');
        }else{
            $('#eAddImg').attr('style','');
        }*/
        
        if(errorCount == 0){
            return true;
        }else{
            return false;
        }
    }

