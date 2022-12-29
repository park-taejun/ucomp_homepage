<?
	$addr = trim($addr);
	$name = trim($name);
	$lat = trim($lat);
	$lng = trim($lng);
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

	$(document).ready(function() {
		initialize();
	});

	function initialize() {

		var lat_val = "<?=$lat?>";
		var lng_val = "<?=$lng?>";

		var myLatlng = new google.maps.LatLng(lat_val,lng_val);

		var myOptions = {
			zoom: 16,
			center: myLatlng,
			mapTypeId: google.maps.MapTypeId.ROADMAP
		}

		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

		//map.setCenter(myLatlng);

		var contentString = "<?=$name?><br /><?=$addr?>";

		var infowindow = new google.maps.InfoWindow({
			content: contentString 
		});
		
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map,
			title: "<?=$addr?>"
		});

		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
	}
</script>
</head>
<body>
<div id="map_canvas" style="width: 757px; height: 449px;"></div>
</body>
</html>