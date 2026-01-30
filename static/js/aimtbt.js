// JavaScript Document


var shownMenu;
var t;

function tbtInit(){
		
	 $("span.mapExtLink").html("<a href=\"javascript:void(0)\" class=\"mapExpand\">More</a> ");
	 $("span.mapExtLink").bind("click", function(){
		if(typeof(shownMenu) != "undefined")
		{
			clearTimeout(t);
			$(shownMenu).children("span").hide();
		}
      	$(this).next("span.mapMenuWrap").children("span").slideToggle("fast");
		shownMenu = $(this).next("span.mapMenuWrap");
     	});
	 
	 var hideMenu = function() {
		 if(typeof(shownMenu) != "undefined")
		{
			$(shownMenu).children("span").hide();
			showMenu = undefined;
		}
	 }
	 
	 $("span.mapExtLink").children("a").bind("blur", function(){
		t=setTimeout(hideMenu, 200);
      	
     	});
	 
	 //$("span.mapMenuDrop").children("a").bind("click", function() { alert("click"); });
	 
	 //Make map links a drop menu
	 $("span.mapMenu").attr("class", "mapMenuDrop");
	 $("span.mapMenuDrop").hide();
	 
}
	
	
$(document).ready(tbtInit);