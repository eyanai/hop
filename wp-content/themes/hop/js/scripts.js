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
	
	$('.nextSingel').on('click',this,function(){
			 id=$('.imgSingelPostCon').attr('cId');
		//	alert(id);
				nextPost(id);
			}
		);	
	
	$('.previousSingel').on('click',this,function(){
			 id=$('.imgSingelPostCon').attr('cId');
			//alert(id);
				prePost(id);
			}
		);	
		
		
///////////////gallery-big one
		wFixGall();
		$('.nextGall').on('click',this,function(){
			nextGall();
		});
		
		$('.previousGall').on('click',this,function(){
			backGall();
		});		


	$('.backSingel').on('click',function(e){
		e.preventDefault();
		window.history.back();
	
	});

});///////dom ready



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
					$('.writer').text(obj.writer);
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
					$('.writer').text(obj.writer);
				}
			});		
		
	
}
///////////////gallery func
function wFixGall(){
	w=$('.conGall').length;
	W=w*790;
	$('.bigImgCon').css({'width':W+'px'});
	$('.bigImgCon').attr('location',0);
	if(W<=790){
		$('.circule.right.gallery').hide();
		$('.circule.left.gallery').hide();
	}else{
		$('.circule.left.gallery').hide();
	}
}

function nextGall(){
	w=$('.conGall').length*790;
	
	loc=$('.conGall').offset().left;
	pos=$('.bigImgCon').attr('location');
	
	total=loc+790;
	//alert(loc);
	parseInt(pos);
	if(total<w){
		$('.circule.left.gallery').hide();
		pos++;
		$('.bigImgCon').attr('location',pos);
		gotoleft=pos*790;
		$('.bigImgCon').css('left',gotoleft+'px');
		$('.circule.left.gallery').show();
		if(w-total<790){
			$('.circule.right.gallery').hide();
		}
	}	
	
}


function backGall(){
	w=$('.conGall').length*790;
	
	loc=$('.conGall').offset().left;
	pos=$('.bigImgCon').attr('location');
	
	total=loc+790;
	//alert(loc);
	parseInt(pos);
	if(loc>790){
		$('.circule.right.gallery').show();
		pos--;
		$('.bigImgCon').attr('location',pos);
		gotoleft=pos*790;
		$('.bigImgCon').css('left',gotoleft+'px');
		$('.circule.left.gallery').show();
		if(pos==0){
		
			$('.circule.left.gallery').hide();
		}
	}else{
		$('.circule.left.gallery').hide();
	}	
	
		
}