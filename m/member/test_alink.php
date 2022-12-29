<script type="text/javascript" src="/manager/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/manager/js/jquery_ui.js"></script>
<script type="text/javascript" src="/manager/js/jquery.easing.1.3.js"></script>
<script>
	$(window).scroll(function() {
		alert('aa');
	});
</script>


<?for ($i=0; $i <100;$i++){?>
	<h1>Scroll</h1>
<?}?>

<a href="javascript:call()">click here</a>
<div id="call" style="display:none">
	<a href="tel:010-8523-3707">calling</a>
</div>
<script>
function call(){
	document.getElementById("call").style.display="block";
}
</script>