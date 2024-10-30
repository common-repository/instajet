(function($){ 
	var $form = $('#search');

	$('#origin').autocomplete({
		source: lclstn.ajax_url,
		minLength: 3/*,
		select: function( event, ui ) {
			console.log( ui.item ?
				"Selected: " + ui.item.value + " aka " + ui.item.id :
				"Nothing selected, input was " + this.value );
		}*/
	});

	$('#destination').autocomplete({
		source: lclstn.ajax_url,
		minLength: 3/*,
		select: function( event, ui ) {
			console.log( ui.item ?
				"Selected: " + ui.item.value + " aka " + ui.item.id :
				"Nothing selected, input was " + this.value );
		}*/
	});

	$form.submit(function () {
		var error = false;
		$.each($form.find(".validate"),function(i,el){
			if($(el).val()==""){
				error = true;
				$(el).addClass("error");
			}
		});
		
		if (error) return false;
	});
	
	var dateToday = new Date();						
	$(".datepicker").datepicker({minDate: dateToday, dateFormat:"dd/mm/yy"});
				
	jQuery(".ij-aircraft-explore").click(function (e) {
		e.preventDefault();

		var el = jQuery(this);
		if (el.text() == el.data("text-swap")) {
			el.text(el.data("text-original"));
		} else {
			el.data("text-original", el.text());
			el.text(el.data("text-swap"));
		}

		jQuery(this).parents(".price-guide").find(".aircraft_selection").toggleClass("open");
	});
	
	jQuery(".ij-book-jet").on("click",function(){
		createModal();
	});
})(jQuery);

jQuery("body").on("submit","#ij-booking-form",function(){
	if(jQuery(this).hasClass("disabled")){
		return false;
	}
	var error = false;
	jQuery(this).find("input").each(function(i,el){
		if(jQuery(el).val()==""){
			error = true;
			jQuery(el).addClass("error");
		}
	});
	if(error) return false;				
});
			
function createModal(){
	var jets = [];
	
	var boxes = jQuery("#ij-results .pricewrap input:checked");
	jQuery.each(boxes,function(i,j){
		j = jQuery(j);
		jets.push({
			"class": j.data("class"),
			"jet": j.data("jet"),
			"id":j.data("id"),
		});
	});
	
	//console.log(jets);
	
	/*if(typeof(jets)=="undefined" || jets.length<1){
		jets = [
			{
				"class": "Super Light Jets",
				"jet":"Cessna Citation",
				"id":"",
			}
		];
	}*/
	
	//console.log(jets);
					
	var m = jQuery("<div/>",{"class":"ij-modal"});
	var o = jQuery("<div/>",{"class":"ij-modal-overlay"});
	var content = jQuery("<div/>",{"class":"ij-modal-content"}).html("<h2>Check Availability</h2>");
	
	var left = jQuery("<div/>",{"class":"ij-jets-list"}).html("<h5>Selected Jets</h5>");
	var right = jQuery("<div/>",{"class":"ij-booking-form"});
	
	var form = jQuery("<form/>",{"class":"ig-form","id":"ij-booking-form","method":"post"}).html('<input type="hidden" name="ij-method" value="book" /><label>Your Name</label><input type="text" name="user_details[name]" placeholder=""><label>Your Email Address</label><input type="email" name="user_details[email]" placeholder=""><label>Your Telephone Number</label><input type="text" name="user_details[telephone]" placeholder=""><button>Send Request</button>');
	
	var tmp = {};
	var has_jets = false;
	
	jQuery.each(jets,function(i,j){
		//console.log(j);
		tmp[j.class] = typeof(tmp[j.class])=="undefined" ? 1 : tmp[j.class]+1;	
		form.append('<input type="hidden" name="jets[]" value="'+j.id+'">');
		has_jets = true;
	});
	
	//console.log(tmp);
	
	
	if(!has_jets){
		left.append("<div class='ij-selected-jet'><strong>Please select at least one jet to continue.</strong></div>");
		form.addClass("disabled");
	} else {
					
		jQuery.each(tmp,function(i,j){
			left.append("<div class='ij-selected-jet'>"+i+" (x"+j+")</div>");
		});
	}
	
	right.html(form);
	
	content.append(left).append(right);
	
	//content.append();
	m.html(content);
				
	jQuery("body").append(o).append(m);
	
	setTimeout(function(){
		m.css("margin-top","-"+(m.outerHeight()/2)+"px").addClass("active");
		o.addClass("active");
	},50);
}	

jQuery("body").on("click",".ij-modal-overlay",function(){
	closeModal();
});

function closeModal(){
	jQuery(".ij-modal,.ij-modal-overlay").removeClass("active");
	setTimeout(function(){
		jQuery(".ij-modal,.ij-modal-overlay").remove();
	}, 300);
}