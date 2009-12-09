/*****************************************************************/
/* Global variables container : dtsv aka Drag-To-Share Variables */
/*****************************************************************/
window.dtsv =
{ 
	title : document.title
};

var J = jQuery.noConflict();

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
			dtsv.images = J("img.dtse-img");
			dtsl.front.makeDraggable();
			
			dtsv.targets = J("#dtse-targets li");
			dtsl.front.makeDroppable();
		},


		// Create TootTip while overing images
		createTip : function(e) 
		{
			//create tool tip if it doesn't exist
			if(J("#dtse-tip").length === 0)
			{ 
				J("<div>")
				.html("<span>"+ dtsv.tipsLabel +"<\/span><span class='arrow'><\/span>")
				.attr("id", "dtse-tip").css({ left:e.pageX + 30, top:e.pageY - 16 })
				.appendTo("body").fadeIn(1200);
			}
		},


		
		// Loading Droppable targets into the DOM
		makeTargets : function()
		{
			var html = '<ul id="dtse-targets"><li id="twitter"><a href="http://twitter.com"><!-- --></a></li><li id="delicious"><a href="http://delicious.com"><!-- --></a></li><li id="facebook"><a href="http://www.facebook.com"><!-- --></a></li></ul>';
			J("body").append(html);
		},

		// Extract a 'dtse-post-X' from a multi class string, replace '-' by '-'
		extractClassForPermalink: function(string)
		{
			//Looking for permalink
			var reg = /(dtse-post-[0-9]+)/;
			string = string.match(reg);
			string = string[1].replace(/-/g, '_');
			return string;
		},
		
		// Activate drag feature on image
		makeDraggable : function()
		{					
			dtsv.images.draggable({
					
				  //create draggable helper
				  helper: function() {
				  
				  //wanna share permalink instead of current page
				  if(dtsv.sharePermalink === true)
				  {
					var cssClass = J(this).attr('class');
					cssClass = dtsl.front.extractClassForPermalink(cssClass);
					dtsv.currentUrl = eval('dtsv.'+cssClass+'_permalink');
					dtsv.title = eval('dtsv.'+cssClass+'_title');
				  }
				  
					return J("<div>").attr("id", "dtse-helper").html("<span>" + dtsv.title + "</span><img id='dtse-thumb' src='" + J(this).attr("src") + "'>").appendTo("body");
				  },
				  cursor: "pointer",
				  cursorAt: { left: 180, top: 53 },
				  zIndex: 99999,
				  
				  
				  //show overlay and targets
				  start: function() {
					J("<div>").attr("id", "dtse-overlay").css("opacity", 0.7).appendTo("body");
					J("#dtse-tip").remove();
					J(this).unbind("mouseenter");
					
					dtsv.targetsID = J("#dtse-targets");
					
					dtsv.targetsID.css("left", (J("body").width() / 2) - dtsv.targetsID.width() / 2);
					
					var targetHeight = dtsv.targetsID.height();
						
					// Position Middle
					if(dtsv.targetsPosition == 'middle') {
						
						var middle = (J(window).height() / 2 - targetHeight / 2);
						dtsv.targetsID.css("top", middle+'px').slideDown();
						
					}
					
					// Position Bottom
					if(dtsv.targetsPosition == 'bottom') {

						var bottom = (J(window).height() - (targetHeight *2) - 10);
						dtsv.targetsID.css("top", bottom+'px').slideDown();
						
					}
					
					// Position Top
					if((dtsv.targetsPosition == 'top') || (dtsv.targetsPosition === undefined)) {
						dtsv.targetsID.slideDown();
					}					
										
				  },
  
				  //remove targets and overlay
				  stop: function() {
					dtsv.targetsID.slideUp();
					J("span.dtse-share", dtsv.targetsID).remove();
					J("#dtse-overlay").remove();
					J(this).bind("mouseenter", dtsl.front.createTip);
				  }
			})
			.bind("mouseenter", dtsl.front.createTip)
			.mousemove(function(e) {	
				J("#dtse-tip").css({ left:e.pageX + 30, top:e.pageY - 16 });
			})
			.mouseleave(function() {
				J("#dtse-tip").remove();
			});		
		},


		
		// Activate drop feature on targets
		makeDroppable : function()
		{
			dtsv.targets.droppable({
				tolerance: "pointer",
				
				//show info when over target
				over: function() {
					J("span.dtse-share", "#dtse-targets").remove();
					J("<span>").addClass("dtse-share").text(dtsv.targetsLabel + ' ' + J(this).attr("id")).addClass("active").appendTo(J(this)).fadeIn();
				},
				drop: function() {
					var id = J(this).attr("id");
					var currentUrl = dtsv.currentUrl;
					
					//wanna share current page instead of permalink
					if(dtsv.sharePermalink === false) 
					{
						currentUrl = window.location.href;
					}
					
					baseUrl = J(this).find("a").attr("href");

					if (id.indexOf("twitter") != -1) {
					  dtsl.front.shareOnTwitter(baseUrl, currentUrl);
					} else if (id.indexOf("delicious") != -1) {
					  window.location.href = baseUrl + "/save?url=" + currentUrl + "&title=" + dtsv.title;
					} else if (id.indexOf("facebook") != -1) {
					  window.location.href = baseUrl + "/sharer.php?u=" + currentUrl + "&t=" + dtsv.title;
					}
				}
			});
		},

		
		shareOnTwitter : function(baseUrl, longUrl)
		{		
			jQuery.ajax({
				type: "GET",
				url: dtsv.root+'lib/dtse.ajax.php',
				data: 'longurl='+longUrl,
				dataType: 'text',
				success: function(response){
					//console.log(response);
					window.location.href = baseUrl + '/home?status=' + dtsv.title + ': ' + response;
				},
				error: function(XMLHttpRequest, textStatus, errorThrown){
					//console.log('error');
					window.location.href = baseUrl + '/home?status=' + dtsv.title + ': ' + longUrl;
				}
			});
		}
		
	}
};