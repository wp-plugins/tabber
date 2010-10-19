//tabber
/*jQuery(document).ready(function($){ 
$('#tab-title span').click(function(){
	$(this).addClass("selected").siblings().removeClass();
	$("#tab-content > ul").slideUp('1500').eq($('#tab-title span').index(this)).slideDown('1500');
});
});
*/
jQuery(document).ready(function($){ 
$('#tab-title span').mouseover(function(){ 
$(this).addClass("selected").siblings().removeClass(); 
$("#tab-content > ul").eq($('#tab-title span').index(this)).show().siblings().hide(); 
}); 
}); 
