$(document).ready(function(e) {
	
$('#galId').on('change',function(){

			var cat=$('#galId :selected').data('slug');
			$('.req').removeAttr('required').removeClass('req');
			//alert(cat);
			$.post(ajax_object.ajaxurl, {
			action: 'cat_sel',
			catSel:cat,
			url:document.URL
		}, 
			function(data) {
				req(data);	
			});
	});
	
	$('#my_frame').on('load',function(){
		//var iframeName = $(obj).attr("name");
		//console.log("load iframe:" + $(window[iframeName].document.getElementsByTagName("body")[0]).text());
        //var data = $(window[iframeName].document.getElementsByTagName("body")[0]).text()
		res=$("#my_frame").contents().find("#respons").text();
		if(res=='ok')
		{
			$('fieldset').fadeOut('fast',function(){$('.massegShow').html('התמונה והתוכן נוספו').fadeIn();});
			
		}else{
			alert('ישנה שגיאה אנא נסה שנית');
		}
	});
	
	
	
});

function req(data){
	$('#show').html(data);
					need=jQuery.parseJSON(data);
					console.log(need);
					
					if(need.userName_fiede=='userName_fiede'){
						$('#username').addClass('req').attr('required','required');
					}
					if(need.userLastNmae__fiede=='userLastNmae__fiede'){
						$('#userlastname').addClass('req').attr('required','required');
					}
					if(need.school_field=='school_field'){
						$('#userSchool').addClass('req').attr('required','required');
					}
					if(need.userClass_field=='userClass_field'){
						$('#userCalss').addClass('req').attr('required','required');
					}
					if(need.age_fiede=='age_fiede'){
						$('#userage').addClass('req').attr('required','required');
					}
					if(need.parent_fiede=='parent_fiede'){
						$('#parentname').addClass('req').attr('required','required');
					}
					if(need.parentId_fiede=='parentId_fiede'){
						$('#parentid').addClass('req').attr('required','required');
					}if(need.phone_fiede=='phone_fiede'){
						$('#phone').addClass('req').attr('required','required');
					}
					if(need.userEmail_fiede=='userEmail_fiede'){
						$('#email').addClass('req').attr('required','required');
					}if(need.city_fiede=='city_fiede'){
						$('#city').addClass('req').attr('required','required');
					}if(need.street_fiede=='street_fiede'){
						$('#street').addClass('req').attr('required','required');
					}if(need.zipcode_fiede=='zipcode_fiede'){
						$('#zipcode').addClass('req').attr('required','required');
					}
					if(need.file_fiede=='file_fiede'){
						$('#file').addClass('req').attr('required','required');
					}
					if(need.fileType_fiede=='fileType_fiede'){
						$('#filetype').addClass('req').attr('required','required');
					}
					if(need.agree=='agree'){
						$('#agree').addClass('req').attr('required','required');
					}
					if(need.message_field=='message_field'){
						$('#message').addClass('req').attr('required','required');
					}
					
}