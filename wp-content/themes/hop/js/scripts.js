// DOM Ready
$(function() {
	
$('.mutagSlider li').hover(function(e){
		var p=$(this).data('play');
		if($('#'+p+'')[0]){
			$('#'+p+'')[0].play();
		}
	},
	function(e){
		var p=$(this).data('play');
		if($('#'+p+'')[0]){
			$('#'+p+'')[0].pause();
			$('#'+p+'')[0].currentTime = 0; 
		}
	});
	
	
	///ajax section
	
	$('.next').on('click',this,function(){
			 id=$('.imgSingelPostCon').attr('cId');
		//	alert(id);
				nextPost(id);
			}
		);	
	
	$('.previous').on('click',this,function(){
			 id=$('.imgSingelPostCon').attr('cId');
			//alert(id);
				prePost(id);
			}
		);	

});



///////////////////functions
function nextPost(id){
			$.post(ajax_object.ajaxurl, {
			id:id,
			action: 'next_post',
			}
			, function(data) {
				var obj = jQuery.parseJSON(data);
				var id=obj.postid;
				var src=obj.src;
				if(id=='0' || src==false){
					
					alert('no next post');
				}else{
					//alert(obj.id);
					$('#imgSolo').attr('src',src);
					$('.imgSingelPostCon').attr('cId',obj.postid);
				}
			});		
		
	
}

function prePost(id){
			$.post(ajax_object.ajaxurl, {
			id:id,
			action:'pre_post',
			}
			, function(data) {
				var obj = jQuery.parseJSON(data);
				var id=obj.postid;
				var src=obj.src;
				if(id=='0' || src==false){
						alert('no previous post');
				}else{
					//alert(obj.id);
					$('#imgSolo').attr('src',src);
					$('.imgSingelPostCon').attr("cId",obj.postid);
				}
			});		
		
	
}