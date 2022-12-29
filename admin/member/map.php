<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$menu_right = "VA001"; // 메뉴마다 셋팅 해 주어야 합니다.
#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/admin/admin.php";


	function listAdminAddr($db) {

		$query = "SELECT ADM_ID, ADM_NO, ADM_NAME, ADM_ADDR FROM TBL_ADMIN_INFO A WHERE DEL_TF = 'N' AND USE_TF = 'Y' AND ADM_ADDR <> '' ";
		

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	$g_kakao_map_api_key = "918a79122697d239c0c8655ff1cc190f";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>U: 유컴패니온 인트라넷</title>
</head>
<body style="margin: 0;">
<div id="map" style="width:100%;height:950px;"></div>
<script type="text/javascript" src="/manager/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=<?=$g_kakao_map_api_key?>&libraries=services"></script>

<style>
.customoverlay {position:relative;bottom:60px;border-radius:6px;border: 2px solid #ccc;border-bottom:2px solid #ddd;float:left;}
.customoverlay:nth-of-type(n) {border:0; box-shadow:0px 0px 0px #fff;}
.customoverlay .title {display:block;text-align:center;background:#fff;margin-right:5px;padding:2px 15px;font-size:12px;font-weight:bold;}
.customoverlay:after {content:'';position:absolute;margin-left:-12px;left:50%;bottom:-12px;width:22px;height:12px;background:url('https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/vertex_white.png')}
</style>

<script>

	$(document).ready(function(){
		
		let windowWidth = window.innerWidth;
		let windowHeight = window.innerHeight;
		
		$("#map").css("height",windowHeight);

	});


	var mapContainer = document.getElementById('map'), // 지도를 표시할 div 
			mapOption = {
				center: new kakao.maps.LatLng(33.450701, 126.570667), // 지도의 중심좌표
				level: 8 // 지도의 확대 레벨
	};  

	// 지도를 생성합니다    
	var map = new kakao.maps.Map(mapContainer, mapOption); 

	// 주소-좌표 변환 객체를 생성합니다
	var geocoder = new kakao.maps.services.Geocoder();

	// 주소로 좌표를 검색합니다 
<?
	$arr_rs = listAdminAddr($conn);

	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$ADM_ID						= trim($arr_rs[$j]["ADM_ID"]);
			$ADM_NO						= trim($arr_rs[$j]["ADM_NO"]);
			$ADM_NAME					= trim($arr_rs[$j]["ADM_NAME"]);
			$ADM_ADDR					= trim($arr_rs[$j]["ADM_ADDR"]);
?>

	geocoder.addressSearch('<?=$ADM_ADDR?>', function(result, status) {

		// 정상적으로 검색이 완료됐으면 
		if (status === kakao.maps.services.Status.OK) {

			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new kakao.maps.Marker({
				map: map,
				position: coords
			});

			marker.setMap(map);

			var content = '<div class="customoverlay"><span class="title"><?=$ADM_NAME?></span></div>';

			// 커스텀 오버레이를 생성합니다
			var customOverlay = new kakao.maps.CustomOverlay({
				map: map,
				position: coords,
				content: content
			});
			
			// 인포윈도우로 장소에 대한 설명을 표시합니다
			//var infowindow = new kakao.maps.InfoWindow({
			//	content: '<div class="customoverlay"><span class="title"><?=$ADM_NAME?></span></div>'
			//});
			//infowindow.open(map, marker);

			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			//map.setCenter(coords);
		}
	});
<?
		}
	}
?>

	geocoder.addressSearch('서울특별시 강남구 삼성동 26-24', function(result, status) {

		// 정상적으로 검색이 완료됐으면 
		if (status === kakao.maps.services.Status.OK) {

			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new kakao.maps.Marker({
				map: map,
				position: coords
			});

			// 인포윈도우로 장소에 대한 설명을 표시합니다
			var infowindow = new kakao.maps.InfoWindow({
				content: '<div style="width:150px;text-align:center;padding:3px 0;font-weight:bold;">본사사무실</div>'
			});
			infowindow.open(map, marker);

			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			//map.setCenter(coords);
		}
	});

	geocoder.addressSearch('서울 종로구 당주동 5', function(result, status) {

		// 정상적으로 검색이 완료됐으면 
		if (status === kakao.maps.services.Status.OK) {

			var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

			// 결과값으로 받은 위치를 마커로 표시합니다
			var marker = new kakao.maps.Marker({
				map: map,
				position: coords
			});

			// 인포윈도우로 장소에 대한 설명을 표시합니다
			var infowindow = new kakao.maps.InfoWindow({
				content: '<div style="width:150px;text-align:center;padding:3px 0;font-weight:bold;">광화문사무실</div>'
			});
			infowindow.open(map, marker);

			// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
			map.setCenter(coords);
		}
	});


</script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>