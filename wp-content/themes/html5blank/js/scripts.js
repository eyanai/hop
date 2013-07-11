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
	

});