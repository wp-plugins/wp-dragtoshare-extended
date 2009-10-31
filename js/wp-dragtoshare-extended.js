﻿/*****************************************************************/
/* Global variables container : dtsv aka Drag-To-Share Variables */
/*****************************************************************/
window.$dtsv =
{ 
	title : document.title
}

/*****************************************************************/
/**** Global library object : dtsl aka Drag-To-Share Library *****/
/*****************************************************************/

var dtsl = 
{
	// Front office functions
	front:
	{
		// Launch everything for the front office...
		init: function() 
		{
			dtsl.front.makeTargets();
			$dtsv.images = jQuery("img.dtse-img");
			dtsl.front.makeDraggable($dtsv.images);
			
			$dtsv.targets = jQuery("#dtse-targets li");
			dtsl.front.makeDroppable($dtsv.targets);			
		},


		
		// Create TootTip while overing images
		createTip : function(e) 
		{
			//create tool tip if it doesn't exist
			if(jQuery("#dtse-tip").length === 0)
			{ 
				jQuery("<div>")
				.html("<span>"+ $dtsv.tipsLabel +"<\/span><span class='arrow'><\/span>")
				.attr("id", "dtse-tip").css({ left:e.pageX + 30, top:e.pageY - 16 })
				.appendTo("body").fadeIn(1800);
			}
		},


		
		// Loading Droppable targets into the DOM
		makeTargets : function()
		{
			var html = '<ul id="dtse-targets"><li id="twitter"><a href="http://twitter.com"><!-- --></a></li><li id="delicious"><a href="http://delicious.com"><!-- --></a></li><li id="facebook"><a href="http://www.facebook.com"><!-- --></a></li></ul>';			
			jQuery("body").append(html);
		},


		
		// Activate drag feature on image
		makeDraggable : function(id)
		{
			id.draggable({
					
				  //create draggable helper
				  helper: function() {
					return jQuery("<div>").attr("id", "dtse-helper").html("<span>" + $dtsv.title + "</span><img id='dtse-thumb' src='" + jQuery(this).attr("src") + "'>").appendTo("body");
				  },
				  cursor: "pointer",
				  cursorAt: { left: 180, top: 53 },
				  zIndex: 99999,
				  
				  
				  //show overlay and targets
				  start: function() {
					jQuery("<div>").attr("id", "dtse-overlay").css("opacity", 0.7).appendTo("body");
					jQuery("#dtse-tip").remove();
					jQuery(this).unbind("mouseenter");
					jQuery("#dtse-targets").css("left", (jQuery("body").width() / 2) - jQuery("#dtse-targets").width() / 2).slideDown();
				  },
				  
				  
				  //remove targets and overlay
				  stop: function() {
					jQuery("#dtse-targets").slideUp();
					jQuery("span.dtse-share", "#dtse-targets").remove();
					jQuery("#dtse-overlay").remove();
					jQuery(this).bind("mouseenter", dtsl.front.createTip);
				  }
			})
			.bind("mouseenter", dtsl.front.createTip)
			.mousemove(function(e) {	
				jQuery("#dtse-tip").css({ left:e.pageX + 30, top:e.pageY - 16 });
			})
			.mouseleave(function() {
				jQuery("#dtse-tip").remove();
			});		
		},


		
		// Activate drop feature on targets
		makeDroppable : function(id)
		{
			id.droppable({
				tolerance: "pointer",
				
				//show info when over target
				over: function() {
					jQuery("span.dtse-share", "#dtse-targets").remove();
					jQuery("<span>").addClass("dtse-share").text($dtsv.targetsLabel + ' ' + jQuery(this).attr("id")).addClass("active").appendTo(jQuery(this)).fadeIn();
				},
				drop: function() {
					var id = jQuery(this).attr("id"),
					  currentUrl = window.location.href,
					baseUrl = jQuery(this).find("a").attr("href");

					if (id.indexOf("twitter") != -1) {
					  window.location.href = baseUrl + "/home?status=" + $dtsv.title + ": " + currentUrl;
					} else if (id.indexOf("delicious") != -1) {
					  window.location.href = baseUrl + "/save?url=" + currentUrl + "&title=" + $dtsv.title;
					} else if (id.indexOf("facebook") != -1) {
					  window.location.href = baseUrl + "/sharer.php?u=" + currentUrl + "&t=" + $dtsv.title;
					}
				}
			});
		}
		
	},


	
	//Backoffice functions
	back:
	{
		// Launch everything for the back office...
		init: function()
		{
			console.log('dtse backoffice launched');
		}
	}
}