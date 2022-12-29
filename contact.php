<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "4";

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "./_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "./_common/config.php";
	require "./_classes/com/util/Util.php";
	require "./_classes/com/util/ImgUtil.php";
	require "./_classes/com/util/ImgUtilResize.php";
	require "./_classes/com/etc/etc.php";

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<meta name="msvalidate.01" content="D3D1C99AF64E85DB61B385661327885B" />
<meta name="robots" content="index, follow">
<title>유컴패니온 : U:COMPANION CONTACT</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="description" content="유컴패니온 문의 CONTACT ">
<meta content="유컴패니온" name="keywords" />
<meta property="og:type" content="website"> 
<meta property="og:title" content="유컴패니온 문의 CONTACT">
<meta property="og:description" content="유컴패니온 CONTACT">
<meta property="og:url" content="http://www.ucomp.co.kr/contact">
<link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="css/reset.css" />
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/jquery_ui.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script type="text/javascript" src="js/slick.js"></script>
</head>

<body>

<div id="wrap">

<?
	require "./_common/front.header.php";
?>

	<!-- S: midarea -->
	<div class="midarea">
		<div class="leftarea">
		</div>
		<div class="contentsarea" id="contents">
			<div class="our-contact">
				<h3 class="tm-only">CONTACT</h3>
				<strong>CONTACT</strong>
				<div class="address">
					<dl>
						<dt>ADDRESS</dt>
						<dd><span>서울특별시 강남구 삼성동 26-24 <br class="tm-only">유컴패니온</span></dd><!-- 2020.03.23 -->
					</dl>
					<dl>
						<dt>E-MAIL</dt>
						<dd><span>ucomp_contact@ucomp.co.kr</span></dd>
					</dl>
					<dl>
						<dt>TEL</dt>
						<dd><span>070.5030.5830</span></dd>
					</dl>
					<dl>
						<dt>FAX</dt>
						<dd><span>070.7545.1710</span></dd>
					</dl>
				</div>
				<div id="map">
				<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3164.7080732799104!2d127.04658061517058!3d37.514802834847096!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x357ca472f076e3f3%3A0xc5acfe824ec9d8c6!2z7ISc7Jq47Yq567OE7IucIOqwleuCqOq1rCDsgrzshLHrj5kgMjYtMjQ!5e0!3m2!1sko!2skr!4v1654156712019!5m2!1sko!2skr" width="100%" height="600px" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe></div>
				<!--<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3165.9918524991954!2d127.04312735100797!3d37.48451863647933!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x357ca6b229f53ced%3A0x613a0f4ec0ab0e04!2z7ISc7Jq47Yq567OE7IucIOqwleuCqOq1rCDrj4Tqs6Ey64-ZIDQyMC0xNQ!5e0!3m2!1sko!2skr!4v1584668425525!5m2!1sko!2skr" width="100%" height="600px" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe></div>-->
			</div>
		</div>
	</div>
	<!-- //E: midarea -->

</div>

<script type="text/javascript" src="js/common_ui.js"></script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=eedcaece671ee4ace585857c81efed55"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>