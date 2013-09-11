// JavaScript Document
$(document).ready(function(e) {
	$('.addimgform').submit(function(e) {
		var ceck=false;
		var mail=false;
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
				
				email = $(this).val();
				filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
				if (filter.test(email.value)) {
				  mail=true;
				}else{
					mail=false;
				}
				
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
	
	
	
	
	
	
	
	
	
	
	

    $('#galId').on('change', function() {

        var cat = $('#galId :selected').data('slug');
        $('.req').removeAttr('required').removeClass('req');
        //alert(cat);
        $.post(ajax_object.ajaxurl, {
            action: 'cat_sel',
            catSel: cat,
            url: document.URL
        },
			function(data) {
			    req(data);
			});
    });

    $("#my_frame").submit(function(){
        $("#galSubmit").val("שולח...");
    });

    $('#my_frame').on('load', function() {
        //var iframeName = $(obj).attr("name");
        //console.log("load iframe:" + $(window[iframeName].document.getElementsByTagName("body")[0]).text());
        //var data = $(window[iframeName].document.getElementsByTagName("body")[0]).text()
        res = $("#my_frame").contents().find("#respons").text();
         if(res == 'ok') {
            //$('fieldset').fadeOut('fast', function() { $('.massegShow').html('התמונה והתוכן נוספו').fadeIn(); });
            //if succsess to send the form -
            $("#galSubmit").val("נשלח בהצלחה!");
            setTimeout(function() {
                $("#galSubmit").val("שלח");
            }, 5000);

        } else {
            alert('ישנה שגיאה אנא נסה שנית');
             $("#galSubmit").val("שלח");
        }
    });


    $("#file_upload").change(function(e) {
        var fileName = $(this).val();
        if((/\.(gif|jpg|jpeg|png)$/i).test(fileName)) {

            if(this.files && this.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    console.log(e.target.result);
                    //$("#add-competitor-imgFile").attr("src", e.target.result);
                    //smallImgAdded = true;
                    $(".file_upload").css("background-image", "url('" + e.target.result + "')");
                };

                reader.readAsDataURL(this.files[0]);
            }
        }
        else {
            alert("noValidFile");
        }
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


function imgUploaded(e,fileName){
    
      
       
}
	
	
});