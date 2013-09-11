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
	
	$('.showAll').on('click',this,function(){
		$('.bigImgCon li').show().removeClass();
		wFixGall();
	});
	
	latterS();

	$('.heb').on('click',this,function(){
		$('#searchEN,#searchAR').hide();
		$('#searchHE').show();
		$('.eng ,.arb').removeClass('active');
		$('.heb').addClass('active');
		
		
	});
	$('.eng').on('click',this,function(){
		$('#searchHE,#searchAR').hide();
		$('#searchEN').show();
		$('.heb ,.arb').removeClass('active');
		$('.eng').addClass('active');
	});
	$('.arb').on('click',this,function(){
		$('#searchHE,#searchEN').hide();
		$('#searchAR').show();
		$('.heb ,.eng').removeClass('active');
		$('.arb').addClass('active');
	});


});///////dom ready



///////////////////functions
function nextPost(id){
			
			$('.mascLoder').show();
			
			$.post(ajax_object.ajaxurl, {
			id:id,
			action: 'next_post'
			},
			 function(data) {
				var obj = jQuery.parseJSON(data);
				var id=obj.postid;
				var src=obj.src;
				if(id=='0' || src==false){
					$('.mascLoder').hide();
					$('.circule.right').hide();
					alert('no next post');
				}else{
					//alert(obj.id);
					$('.circule.right').show();
					$('.circule.left').show();
					$('#imgSolo').attr('src',src);
					$('.mascLoder').hide();
					$('.imgSingelPostCon').attr('cId',obj.postid);
					$('.writer').text(obj.writer);
				}
			});		
		
	
}

function prePost(id){
			$('.mascLoder').show();
	
			$.post(ajax_object.ajaxurl, {
			id:id,
			action:'pre_post',
			}
			, function(data) {
				var obj = jQuery.parseJSON(data);
				var id=obj.postid;
				var src=obj.src;
				if(id=='0' || src==false){
						$('.mascLoder').hide();
						$('.circule.left').hide();
						alert('no previous post');
				}else{
					$('.circule.right').show();
					$('.circule.left').show();
					//alert(obj.id);
					$('#imgSolo').attr('src',src);
					$('.mascLoder').hide();
					$('.imgSingelPostCon').attr("cId",obj.postid);
					$('.writer').text(obj.writer);
				}
			});		
		
	
}
///////////////gallery func
function wFixGall(){
	w=Math.ceil( $('.bigImgCon li').length/6);
	W=w*790;
	$('.bigImgCon').css({'left':'0px'});
	if(W<790){
		$('.bigImgCon').css({'width':'790px'});
	}else{
		$('.bigImgCon').css({'width':W+'px'});
	}
	$('.bigImgCon').attr('location',0);
	if($('.bigImgCon li').length<=6){
		$('.circule.right.gallery').hide();
		$('.circule.left.gallery').hide();
	}else{
		$('.circule.left.gallery').hide();
		$('.circule.right.gallery').show();
	}
}

function wFixGallS(){
	w=Math.round($('.bigImgCon li.show').length/6);
	$('.bigImgCon').css({'width':'790px'});
	W=w*790;
	if(W<790){
		$('.bigImgCon').css({'width':'790px'});
	}else{
		$('.bigImgCon').css({'width':W+'px'});
	}
	$('.bigImgCon').attr('location',0);
	if($('.bigImgCon li.show').length<=6){
		$('.circule.right.gallery').hide();
		$('.circule.left.gallery').hide();
	}else{
		$('.circule.right.gallery').show();
		$('.circule.left.gallery').hide();
	}
}

function nextGall(){
	if($('.bigImgCon li').hasClass('show')){
		local=Math.ceil($('.bigImgCon li.show').length/6);
	}else{
		local=Math.ceil( $('.bigImgCon li').length/6);
	}
	pos=$('.bigImgCon').attr('location');
	loc=$('.bigImgCon').offset().left;
	
	total=loc+790;
	//alert(loc);
	parseInt(pos);
	if((pos+1)!=0){
		$('.circule.left.gallery').show();
	}
	
	if(pos<local){
		pos++;
		$('.bigImgCon').attr('location',pos);
		gotoleft=pos*790;
		$('.bigImgCon').css('left',gotoleft+'px');
		
			if(pos==(local-1)){
				$('.circule.right.gallery').hide();
			}
		
	}else{
		$('.circule.left.gallery').hide();
	}	
	
}


function backGall(){
	if($('.bigImgCon li').hasClass('show')){
		local=Math.ceil($('.bigImgCon li.show').length/6);
	}else{
		local=Math.ceil( $('.bigImgCon li').length/6);
	}
	
	pos=$('.bigImgCon').attr('location');
	loc=$('.bigImgCon').offset().left;
	
	total=loc+790;
	//alert(loc);
	parseInt(pos);
	if(pos<=local){
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

function latterS(){
	$('.letter').on('click',this,function(){
		$('.letter').removeClass('active');
		$(this).addClass('active');
	});
	
	
	
	$('.magnefier,.letter').on('click',this,function(e){
		if(e.target.className.indexOf('letter')!=-1){
			value=$(this).text().toLowerCase();
		}else{
			value=$('.inputGall').val().toLowerCase();
		}
		
		
		if(value==''){
			return;
		}else{
			$('.bigImgCon li').removeClass();
			$('.mainNeme').each(function(index, element) {
				cVal=$(this).text().toLowerCase();
				
				if(cVal.indexOf(value) !=-1 && cVal.charAt(0)==value.charAt(0) ){
					$(this).show();
					$(this).parents('li').show().addClass('show');
					$('.bigImgCon').css('left','0px');
					
				}else{
					$(this).parents('li').hide().addClass('hide');
					
				}
			wFixGallS();
			});
		}
		
		if($('.bigImgCon li.show').length<=6){
			$('.circule.right.gallery').hide();
			$('.circule.left.gallery').hide();
		}else{
			$('.circule.right.gallery').show();
		}
	});
}

function resetS(){
	
	$('.circule.right.gallery').hide();
	$('.circule.left.gallery').hide();
}

