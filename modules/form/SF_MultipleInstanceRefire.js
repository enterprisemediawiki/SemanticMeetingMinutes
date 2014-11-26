window.SF_MultipleInstanceRefire_addEnd = [];
window.SF_MultipleInstanceRefire_addBefore = [];
window.addMultipleInstanceRefire = function(options, refireFn) {
	
	var opt = options ? options : {};
	var addEnd    = (opt.addEnd !== null) ? opt.addEnd : true, // default true
		addBefore = (opt.addBefore !== null) ? opt.addBefore : true, // default true
		remove    = (opt.remove !== null) ? opt.remove : false, // default false
		reorder   = (opt.reorder !== null) ? opt.reorder : false; // default false
	
	if (addEnd)
		window.SF_MultipleInstanceRefire_addEnd.push(refireFn);

	if (addBefore)
		window.SF_MultipleInstanceRefire_addBefore.push(refireFn);
	
	if (remove)
		console.log("not yet supported"); //window.SF_MultipleInstanceRefire_remove.push(refireFn);

	if (reorder)
		console.log("not yet supported"); //window.SF_MultipleInstanceRefire_reorder.push(refireFn);

	setTimeout(refireFn, 2000); // without delay some things aren't fully setup yet, even after DOM load
	
};

// after DOM loads apply functions to instance-adder, remover and reorder buttons
$(function(){

	var refire = function(group){
		return function(){
			for(var fn in group) {
				group[fn]();
			}
		};
	};
	
	var refireAddEnd    = refire(window.SF_MultipleInstanceRefire_addEnd);
	var refireAddBefore = refire(window.SF_MultipleInstanceRefire_addBefore);

	var addAboveHandler = function() {
		$(".multipleTemplateInstance .addAboveButton")
			.not('.addAboveButton-initiated')
			.addClass('addAboveButton-initiated')
			.click(function(){
				// setTimeout so functions happen after new instance added
				setTimeout(
					function() {
						addAboveHandler(); // adds click event to new instances
						refireAddBefore();
					},
					100
				);
				return true;
			});
	};

	
	// clicking the "add" button in a Semantic Form will not automatically
	// apply Javascript to new elements this re-fires certain functions so
	// they can apply themselves to new elements
	$(".multipleTemplateAdder").click(function(){
		// setTimeout so functions happen after new instance added
		setTimeout(
			function(){
				addAboveHandler();
				refireAddEnd();
			},
			100
		);
		return true;
	});
	
	addAboveHandler();
	
});