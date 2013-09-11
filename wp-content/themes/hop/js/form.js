// JavaScript Document
$(document).ready(function(e) {
	$('.addimgform').submit(function(e) {
		var ceck=false;
		var valid=false;
		//checkbox
			$('input[type="checkbox"]').each(function(index, element) {
				if($('.iampernt').is(':checked')){
					$('.iam').removeClass('act');
					ceck=true;
				}else{
					$('.iam').addClass('act');
					ceck=false;
				}
				
				if($('.trume').is(':checked')){
						$('.tarmUse').removeClass('act');
						ceck=true;
				}else{
					$('.tarmUse').addClass('act');
					ceck=false;
				}
			});
			
		//input
			if($('input.req[type="email"]')!=''){
				alert('mail');
			}
			
			$('input.req[type="text"]').each(function(index, element) {
				if($(this).val()==''){
					$(this).addClass('act');
				}
			});
				
					
		if(valid==false){
			e.preventDefault();
			return  false;
		}
	});
});