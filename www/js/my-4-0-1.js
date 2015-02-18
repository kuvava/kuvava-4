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
	
	$('a.al').click(function(){
		alert("Přímý odkaz na daný komentář vypadá takto:\r\n" + $(this).attr('href') + "\r\nPo odkliknutí tohoto upozornění na něj přejdete. Odkaz tedy budete vidět v adresním řádku prohlížeče a budete jej moci odtamtud zkopírovat, když jej tam označíte a stisknete kombinaci kláves CTRL + c a potom jej můžete kamkoliv vložit kombinací kláves CTRL + v");
	});
	
	$('textarea').autosize({callback: function(){ah1();}});
});
