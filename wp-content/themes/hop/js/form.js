// JavaScript Document
$(document).ready(function(e) {
	$('.addimgform').submit(function(e) {
		var ceck=false;
		var ceckim=false;
		var mail=false;
		var agec=false;
		var valid=false;
		var inputc=false;
		var FORM_VALID=false;
		var messeg='אנא מלא שדות מסומנים';
		// DEFINE RETURN VALUES
var R_ELEGAL_INPUT = -1;
var R_NOT_VALID = -2;
var R_VALID = 1; 

function ValidateID(str)
{ 
   //INPUT VALIDATION

   // Just in case -> convert to string
   var IDnum = String(str);

   // Validate correct input
   if ((IDnum.length > 9) || (IDnum.length < 5))
      return R_ELEGAL_INPUT; 
   if (isNaN(IDnum))
      return R_ELEGAL_INPUT;

   // The number is too short - add leading 0000
   if (IDnum.length < 9)
   {
      while(IDnum.length < 9)
      {
         IDnum = '0' + IDnum;         
      }
   }

   // CHECK THE ID NUMBER
   var mone = 0, incNum;
   for (var i=0; i < 9; i++)
   {
      incNum = Number(IDnum.charAt(i));
      incNum *= (i%2)+1;
      if (incNum > 9)
         incNum -= 9;
      mone += incNum;
   }
   if (mone%10 == 0)
      return R_VALID;
   else
      return R_NOT_VALID;
}
		
		//checkbox
			$('input[type="checkbox"]').each(function(index, element) {
				if($('.iampernt').is(':checked')){
					$('.iam').removeClass('act');
					ceckim=true;
				}else{
					$('.iam').addClass('act');
					ceckim=false;
					messeg='אנא אשר שהינך ההורה';
				}
				
				if($('.trume').is(':checked')){
						$('.tarmUse').removeClass('act');
						ceck=true;
				}else{
					$('.tarmUse').addClass('act');
					ceck=false;
					messeg='אנא הסכם לתנאי השימוש';
				}
			});
			
		//input
			if($('#email').hasClass('req')){
				
				emailt = $('#email').val();
				var emailfilter = /(([a-zA-Z0-9\-?\.?]+)@(([a-zA-Z0-9\-_]+\.)+)([a-z]{2,3}))+$/;
				alert(emailfilter.test(emailt));
					if((emailt != "") && (emailfilter.test(emailt))) {
						mail=true;
					  $('input[type="email"]').removeClass('act');
					}else{
						mail=false;
						$('input[type="email"]').addClass('act');
						messeg='אנא מלא אימייל תקין';
					}
				}else{
					
				 mail=true;
			}
			
			$('input.req[type="text"]').each(function(index, element) {
				if($(this).val()==''){
					$(this).addClass('act need');
				}
				
				
				$('.need').each(function(index, element) {
					if($(this).val()!=''){
						$(this).removeClass('act need');
						
						$('input.req[type="text"]').each(function(index, element) {
							if($(this).hasClass('need')){
								inputc=false;
								messeg='אנא מלא שדות נדרשים';
							}else{
								inputc=true;
							}	
						});
					}
				});
			});
				
			if(!($('input[type="text"]').hasClass('req'))){
				inputc=true;
			}
			
			if($('#parentid').hasClass('req')){
				idval=$('#parentid').val();
				if(ValidateID(idval)==1){
					valid=true;
				}else{
					valid=false;
					messeg='תז בפורמט לא תקין';
				}
			}else{
				valid=true;
			}
			
			if($('#userage').hasClass('req')){//
			
				if($('#userage').val()!=''){
					$('.agelabel').removeClass('act');
					agec=true;
				}else{
					agec=false;
					messeg='אנא מלא תאריך לידה';
					$('.agelabel').addClass('req act');
				}
			}else{
				agec=true;
			}
					
	
		if(agec==true && valid==true && inputc==true && mail==true && ceck==true && ceckim==true){
				FORM_VALID=true;
				$('.alertmessg').hide();
			}else{
				$('.alertmessg').text(messeg).show();
				FORM_VALID=false;
		}
		
		
		if(FORM_VALID==false){
			e.preventDefault();
			return  false;
		}else{
			$("#galSubmit").val("שולח...");
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
				$('#username,#userlastname,#userage,#userSchool,textarea').val('');
            	$('input[type="checkbox"]').attr('checked', false);
				$('.file_upload').css('background-image','none');
            }, 5000);

        } else {
			switch (res){
				case '10':
					$('.alertmessg').text('פורמט קובץ לא נכון- שם בעיברית או סיומת').show();
				break;
				case '8':
					$('.alertmessg').text('הייתה בעייה בהעלאת הקובץ- אנא נסה שנית').show();
				break;
				case '2':
					$('.alertmessg').text('גודל הקובץ חורג מהמותר').show();
				break;
				case '4':
					$('.alertmessg').text('לא נבחר קובץ').show();
				break;
				case '1':
					$('.alertmessg').text('גודל הקובץ חורג מהמותר').show();
				break;
				default:
				$('.alertmessg').hide();
			}
			//alert('ישנה שגיאה אנא נסה שנית');
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
           $('.alertmessg').text('פורמט קובץ- לא נכון').show();
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