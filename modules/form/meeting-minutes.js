


// fire function on document ready
$(function(){
		
	// fire this function now, and after any new multiple instance templates
	addMultipleInstanceRefire(
		{
			pageload: true,
			addBefore:true,
			addEnd:   true,
			reorder:  false,
			remove:   false
		}, 
		// perform this function at events defined above 
		function () {
			// class added to shrink-on-blur elements which have not been
			// initiated yet
			var initialClass = "shrink-on-blur";
			var mtiClass = 'multipleTemplateInstance';
			var mtiInitiated = mtiClass + '-initiated';
			var addedClass = initialClass + "-added";
			var activeClass = initialClass+'-active';
			var reducedHeight = "50px";
			var focusInstance = "focus-multipleTemplateInstance";
						
			// setup un-initiated multipleTemplateInstance elements
			$('.'+mtiClass).not('.'+mtiInitiated).each(function(index,mtiElem){
	
				var $mti = $(mtiElem);
				$mti.addClass(mtiInitiated);
				
				// bind focus events to form elements inside this mti
				$mti.find("input, textarea, select").focus(function(){
	
					if ( $mti.hasClass(focusInstance) )
						return true; // this instance already has focus; no further action
				
					$mti.trigger("getFocusStart");
					
					// parent mti is not the focus instance (if there is one)
					// remove focus class as req'd
					$('.'+focusInstance).removeClass(focusInstance).trigger("loseFocus");

					// make the parent mti the focus instance
					$mti.addClass(focusInstance);
				
					// find all active shrink-on-blur elements (i.e. not 
					// currently shrunk), but...
					var $prevFocus = $('.'+activeClass)
						
						// ...remove any shrink-on-blur within the parent
						// mti that is now the focus instance
						.not("."+focusInstance+' .'+addedClass);
						
					var growNewFocus = function () {
						// animate to previous full height, then set height to auto
						// (can't animate to "auto"). Note: this may look funny if 
						// window is resized between blur/focus cycles
						$mti.find('.'+addedClass).each(function(i,sobElem){
							var $sob = $(sobElem);
							
							$sob.animate(
								{ height : $sob.attr("full-height") },
								400,
								function(){
									//set back to auto when animate complete
									$sob.height("auto").addClass(activeClass);
								}
							);
						});
						$mti.trigger("getFocusEnd");

					};
					
					// if any prev focus fields, shrink them then grow new field(s)
					if ($prevFocus.size() > 0) {
						$prevFocus
						
							// remove active class
							.removeClass(activeClass)
							
							// set full-height attribute for each field to its
							// current height
							.each(function(i,e){
								$e = $(e);
								$e.attr("full-height", $e.height());
							})
							
							// animate the shrinking of the field
							.animate(
								{ height : reducedHeight }, // attrs to animate
								600, // duration of animation
								function () { // call when animation complete
									growNewFocus();
								}
							);
					}
					else {
						growNewFocus();
					}					

				});
				
				$mti.find('.meeting-topic-full-text')
					.each(function(i,textareaElem) {
						var synopsis = synopsize(textareaElem);
						$(textareaElem).before(
							$('<div class="synopsis-wrapper mti-hide-on-blur" style="display:none;">')
								.append('<strong>Synopsis: </strong>')
								.append(
									$('<span class="synopsis-text"></span></div>').html(synopsis)
								)
						);
					})
					.keyup(function(){
						var syn = $(this).parent().find('.synopsis-text').html(synopsize(this));
					});
				
				$mti
					.on("getFocusStart",function(){
					})
					.on("getFocusEnd",function(){
						$(this).find(".mti-hide-on-blur").fadeIn( "slow" );
					})
					.on("loseFocus",function(){
						$(this).find(".mti-hide-on-blur").fadeOut( "slow" );
					});
			
			});
			
			// pre-shrink all un-initiated shrink-on-blur fields
			$('.'+mtiClass+' .'+initialClass).each(function(index,sobElem){
			
				$sob = $(sobElem); // sob = shrink-on-blur
			
				// record the current full height of the textbox for the sake of
				// animation later (you can't animate to a height of "auto")
				$sob.attr("full-height", $sob.height());

				$sob
				
					// remove the initiation class so future calls don't re-add this
					.removeClass(initialClass)
					
					// keep a class on these elements for later use if required
					.addClass(addedClass)
						
					// set the current height to the reduced height
					.height(reducedHeight);
				
			});
		}
	);
	
	var synopsize = function(textarea){
		maxLength = 500;
		var firstLine = $(textarea).val().split('\n')[0];
		var fullLength = firstLine.length;
		if ( fullLength > maxLength ) {
			firstLine = firstLine.substring(0,maxLength);
			var lastSpace = firstLine.lastIndexOf(' ');
			firstLine = firstLine.substring(0,lastSpace);
			return firstLine 
				+ ' ...<br /><span style="color:red;">'
				+ maxLength + ' character max exceeded ('
				+ fullLength + ' used)</span>';
		}
		else {
			var remain = maxLength - firstLine.length;	
			if (firstLine.length == 0)
				firstLine = "No synopsis entered. Enter text in the first line of the field below.";
			return firstLine
				+ '<br /><span style="color:red;">'
				+ remain + ' characters remaining ('
				+ maxLength + ' max)</span>';
		}
	};

});





/*

window.noLinkInSemanticFormsMessages = [
	"You may not use links in this field. Please remove all square braces.",
	"No, seriously. You can't use links here.",
	"Really? You're going to keep trying to put square braces in this box?",
	"This isn't going to work.",
	"Please stop.",
	"I said please.",
	"Okay, one more time: you cannot use square brackets...so no [ or ]",
	"I'm not talking to you anymore",
	"[no response]",
	"Ha ha look: I can use square brackets",
	"[no response]"
];

// this will add wikilink warnings to all input elements without them
addMultipleInstanceRefire(function () {
    
	// find all elements that need checking, and remove those that already
	// have it...then added it to them
	$(".no-links-allowed").unbind("blur").blur(function(ev){
	
		// if the field has a [ or a ]
		if($(ev.target).val().search(/\[/) != -1 || $(ev.target).val().search(/]/) != -1) {
			
			var messages = window.noLinkInSemanticFormsMessages;
			
			// inform user that the cannot use links in the field
			// alert("You may not use links in this field. Please remove all square braces.");
			var count = $(".validation-error-message").size();
			if( ! messages[count])
				count = messages.length - 1;
			
			$(ev.target).after("<div class='validation-error-message'>" + messages[count] + "</div>");
			
			// send focus back to the field so they can edit the contents
			$(ev.target).focus();
			
			$(ev.target).addClass("validation-error");
		}
		else {
			$(ev.target).removeClass("validation-error");
			$(".validation-error-message").remove();
		}
	});
});
    
    
// this adds collapsible content to properly marked templates. Use Template:Collapsible
addMultipleInstanceRefire(function(){

	$(".montalvo-collapsible").each(function(index,element){

		var collapseText = $(element).attr("data-collapsetext") || "Collapse";
		var expandText = $(element).attr("data-expandtext") || "Expand";
		var buttonText;

		// if no <a> tags within collapsible, then it hasn't been setup yet
		// this only performed the first time on each collapsible
		if ( ! $(element).find(".collapsible-trigger").size() ) {
					
			if ($(element).hasClass("mw-collapsed")) {
				$(element).children(".mw-collapsible-content").first().hide();
				buttonText = expandText;
			}
			else {
				$(element).children(".mw-collapsible-content").first().show();
				buttonText = collapseText;
			}
			
			// if there is a pre-trigger element, insert the trigger after it
			// otherwise, insert the trigger as the first element (prepend)
			if( $(element).find(".pre-trigger").size() )
				$(element).find(".pre-trigger").after("<a href='#' class='collapsible-trigger'>" + buttonText + "</a>");
			else
					$(element).prepend("<a href='#' class='collapsible-trigger'>" + buttonText + "</a>");
			
		}

		$(element).find("a.collapsible-trigger").unbind("click").click(function(ev){
			
			if( $(ev.target).parent().hasClass("mw-collapsed") ) {
				$(ev.target).parent().find(".mw-collapsible-content").first().slideDown("slow");

				// change button to collapse
				$(ev.target).text(collapseText);
			}
			else {
				$(ev.target).parent().find(".mw-collapsible-content").first().slideUp("slow");
			
				// change button to expand
				$(ev.target).text(expandText);
			}

			$(ev.target).parent().toggleClass("mw-collapsed");
			
			return false;
		
		});

	});
	
});

// adds a counter to text fields limited to 255 characters
addMultipleInstanceRefire(function(){

	$(".character-limit-255").each(function(index,element){

		$(element).removeClass(".character-limit-255").addClass(".character-limit-255-added");

		var counter = $("<span></span>").css({
			"text-align" : "right"
		});
		
		$(element).keyup(function(ev){
			
			var chars = $(this).val().length;
			
			counter.html(chars + " of 255 characters");
			
			var css;
			if (chars < 200) {
				css = {
					color : "black",
					"font-weight" : "normal"
				}
			}
			else {
				css = {
					color : "red",
					"font-weight" : "bold"
				}
			}
			
			counter.css(css);
			
		}).blur(function(){
			if( $(this).val().length < 255 ) {
				counter.hide();
			}
		}).focus(function(){
			counter.show();
		}).after(counter);

	});

});

*/