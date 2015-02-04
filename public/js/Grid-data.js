
			(function(window, document, undefined) {
				"use strict";
				
				var gridColSortTypes = ["string", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "number", "string", "string", "string"], 
				    gridColAlign = [];
				
				var onColumnSort = function(newIndexOrder, columnIndex, lastColumnIndex) {
					var offset = (this.options.allowSelections && this.options.showSelectionColumn) ? 1 : 0, 
					    doc = document;
					
					if (columnIndex !== lastColumnIndex) {
						if (lastColumnIndex > -1) {
							doc.getElementById("demoHdr" + (lastColumnIndex - offset)).parentNode.style.backgroundColor = "";
						}
						doc.getElementById("demoHdr" + (columnIndex - offset)).parentNode.style.backgroundColor = "#fff";
					}
				};
				
				var onResizeGrid = function(newWidth, newHeight) {
					var demoDivStyle = document.getElementById("demoDiv").style;
					demoDivStyle.width = newWidth + "px";
					demoDivStyle.height = newHeight + "px";
				};
				
				for (var i=0, col; col=gridColSortTypes[i]; i++) {
					gridColAlign[i] = (col === "number") ? "right" : "left";
				}
				
				var myGrid = new Grid("demoGrid", {
				    	srcType : "dom", 
				    	srcData : "demoTable", 
				    	allowGridResize : true, 
				    	allowColumnResize : true, 
				    	allowClientSideSorting : false, 
				    	allowSelections : true, 
				    	allowMultipleSelections : true, 
				    	showSelectionColumn : true, 
				    	onColumnSort : onColumnSort, 
				    	onResizeGrid : onResizeGrid, 
				    	colAlign : gridColAlign, 
				    	colBGColors : ["#fafafa"], 
				    	colSortTypes : gridColSortTypes, 
				    	fixedCols : 2
				    });
			})(this, this.document);
			
			
			
					$( "#demoGrid .g_BodyStatic .g_Cl .g_C, #demoGrid .g_BodyFixed .g_Cl .g_C" ).each(function(index){
					var rowid = $(this).attr('class').split(' ')[2];
					if(parseInt(rowid.substr(3))%2 == 0){
					$(this).addClass('oddRow');
					}
					});
					
					$(document).ready(function(){
						$(".hideRow table tr").css({"border-top":"#fff 5px solid","border-bottom":"#fff 5px solid"});
 						$("table tr:odd").css({"background-color":"#b9babc"});
						 $("table tr:even").css("background-color","#d2d3d4");
						 $("table tr:last").css("border-bottom","none");
						 $("table tr:first").css("border-top","none");
						 $(".oddRow:even").addClass("bg_light02");	
					});
					
					$("#demoDiv").on('click', function(e) {
						var top = $("#demoDiv").offset().top - $('#header').height();
						e.preventDefault();
						$('html, body').animate({ 
							scrollTop: top
						}, 200);
					});
					
					$( "#demoGrid .g_BodyStatic .g_Cl .g_C table" ).each(function(index){
						$(this).parent('.g_C').addClass('hideRow');						
					});
					
					$( "#demoGrid .g_BodyFixed .g_Cl .g_C table" ).each(function(index){
						if(index==0){
							$(this).parent('.g_C').addClass('hideRow');
						}else{
							$(this).parent('.g_C').addClass('hideRow').css("border","medium none");
						}
												
					});
					
					$('#demoGrid .g_BodyFixed .g_C').click(function(){
						if($(this).children('span').text() == '+')
							$(this).children('span').text('-');	
						else 
							$(this).children('span').text('+');	
					var rowid = $(this).attr('class').split(' ')[2];
					var nextRow = parseInt(rowid.substr(3)) + 1;
					var nextClass = '.g_R'+nextRow;
					if($('#demoGrid .g_BodyFixed ' + nextClass).children('table').length > 0) {
						$('#demoGrid .g_BodyFixed ' + nextClass).slideToggle(400);
						$('#demoGrid .g_BodyStatic ' + nextClass).slideToggle(400);
					}
					});
					
//					$(document).ready(function(){
//						$(".radio, .radio1").dgStyle();
//						$(".checkbox").dgStyle();
//					});
					
					   $(document).ready(function(){
						  $('#toggle-arrow2').click(function() {
							$('.toggle-box2').slideToggle('slow', function() {
								});
							 });
						});
		
					 $(document).ready(function(){
						$('#toggle-arrow1').click(function() {
							$('.toggle-box').slideToggle('slow', function() {

								});
	 						 });
						});