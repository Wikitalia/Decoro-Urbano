$(document).ready(function(){



//Main Navigation
					$('#main_nav > li > ul').hide(); // Hide all subnavigation
							$('#main_nav > li > a.current').parent().children("ul").show(); // Show current subnavigation	
								
							$('#main_nav > li > a[href="#"]').click( // Click!
								function() {
									$(this).parent().siblings().children("a").removeClass('current'); // Remove .current class from all tabs
									$(this).addClass('current'); // Add class .current
									$(this).parent().siblings().children("ul").fadeOut(100); // Hide all subnavigation
									$(this).parent().children("ul").fadeIn(100); // Show current subnavigation
									return false;
						}
					);

// Jump Menu
					$('.jump_menu').hover(function(){
									$('.jump_menu_btn').toggleClass('active');
									$("ul.jump_menu_list").slideDown(200);
						}, function(){
									$('.jump_menu_btn').toggleClass('active');
									$(".jump_menu_list").hide();
					});
					
 

				

//Close button:
					$(".close_notification").click( 
									function () {
										$(this).hide();
										$(this).parent().fadeTo('fast', 0, function () { 
											$(this).slideUp('fast');
										});
										return false;
									}
					);
			
//Calendar
/*
					$('#date').jCal({
									day:			new Date( (new Date()).setMonth( (new Date()).getMonth() + 2 ) ),
									days:			1,
									showMonths:		1,
									drawBack:		function () {
											return false;
										},
									monthSelect:	false,
									sDate:			new Date(),
									dCheck:			function (day) {
											if (day.getDay() != 6)
												return 'day';
											else
												return 'invday';
										},
									callback:function (day, days) {
											$('#calTwoDays').val( days );
											$('#calTwoResult').append('<div style="clear:both; font-size:7pt;">' + days + ' days starting ' +
												( day.getMonth() + 1 ) + '/' + day.getDate() + '/' + day.getFullYear() + '</div>');
											return true;
										}
						});
*/						
					
					
// Expose | Any element with a class of .expose will expose when clicked
						$(".expose").click(function() {
											// perform exposing for the clicked element
											$(this).expose({ });
							
						});

//Modal on page load
/*
						// select the overlay element - and "make it an overlay"
						$("#facebox").overlay({
							// custom top position
										top: 260,
										// some mask tweaks suitable for facebox-looking dialogs
										mask: {
											// you might also consider a "transparent" color for the mask
											color: '#fff',
											// load mask a little faster
											loadSpeed: 200,
											// very transparent
											opacity: 0.8
										},
										// disable this for modal dialog-type of overlays
										closeOnClick: true,
										// load it immediately after the construction
										load: true
						});
*/						
						
// Modal on click
/*
						$("a[rel]").overlay({
										
										// disable this for modal dialog-type of overlays
										closeOnClick: true,
										mask: {
											color: '#fff',
											loadSpeed: 200,
											opacity: 0.8
										}
						});
  */

/*
//Tooltip 
						$("[title]").tooltip({						 //will make a tooltip of all elements having a title property
											 opacity: 0.8,
											 effect: 'slide',
											 predelay: 200,
											 delay: 10,
											 offset:[5, 0]
											 })
									.dynamic({bottom: { direction: 'down', bounce: true}   //made it dynamic so it will show on bottom if there isnt space on the top
						});
*/						

				
//Expandable Tables 
			
						$(".expandable tr:odd").addClass("odd");
						$(".expandable tr:not(.odd)").hide();
						$(".expandable tr:not(.odd)").addClass("grid_dropdown");
						$(".expandable tr:first-child").show();
						$(".expandable tr:first-child").removeClass("grid_dropdown");
						$(".expandable tr.active").click(function(){
											$(this).toggleClass(".odd");
						});
						
						$(".expandable tr.odd").click(function(){
											$(this).toggleClass("active");
											$(this).next("tr").toggle();
											$(this).find(".toggle").toggleClass("collapse");
						});
						
// Data Table
						$('#data_table').dataTable({
							"aaSorting": [[ 2, "desc" ]]
						});			
			
			
//Tabs in box header 
						$("ul.sub_nav").tabs("div.panes > div", {effect: 'fade'});
			
//Vertical Navigation	
						$("ul.vertical_nav").tabs("div.panes_vertical> div", {effect: 'fade'});
						$("ul.vertical_nav").bind("onClick", function() {
							google.maps.event.trigger(map, 'resize');
							centra_mappa();
						})
			
//Accordion
						$("#accordion").tabs("#accordion div.pane", {tabs: 'h2', effect: 'slide'});
			
			

			
/*			
// Create Graph using Visualize
						$('.graph').visualize({
											  type: 'bar',
											  width: '620px',
											  height: '330px',
											  colors: ['#5a88e4','#8cacc6','#2652b4', '#dddddd']
											  });
						$('.graph').hide();

//Wysiwyg
			 			$('.wysiwyg').wysiwyg();

*/		
});
