$(document).ready(function(){
	$('#madd1').click(function(){
			$('#madd2,#madd3').removeClass('act');
			$(this).toggleClass('act');
			$('#add2,#add3').hide();
			$('#add1').toggle();
			ah1();
			return false;
	});
	$('#madd2').click(function(){
			$('#madd1,#madd3').removeClass('act');
			$(this).toggleClass('act');
			$('#add1,#add3').hide();
			$('#add2').toggle();
			ah1();
			return false;
	});
	$('#madd3').click(function(){
			$('#madd1,#madd2').removeClass('act');
			$(this).toggleClass('act');
			$('#add1,#add2').hide();
			$('#add3').toggle();
			ah1();
			return false;
	});
});
