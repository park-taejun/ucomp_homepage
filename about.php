<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "1";

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
<title>유컴패니온 : U:COMPANION ABOUT</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="description" content="유컴패니온 회사소개 유컴패니온은 우리가 갖춘 기술력과 인성으로 오늘도 고객에게 어떻게 행복을 전달할 것인가를 끊임없이 고민하고 노력합니다.">
<meta content="유컴패니온" name="keywords" />
<meta property="og:type" content="website"> 
<meta property="og:title" content="유컴패니온 회사소개">
<meta property="og:description" content="유컴패니온 회사소개 유컴패니온은 우리가 갖춘 기술력과 인성으로 오늘도 고객에게 어떻게 행복을 전달할 것인가를 끊임없이 고민하고 노력합니다.">
<meta property="og:url" content="http://www.ucomp.co.kr/about">
<link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="css/reset.css" />
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/jquery_ui.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/modernizr-2.8.3-respond-1.4.2.min.js"></script>


<script src="js/swiper.min.js"></script>
<?
	require "./_common/google_analytics.php";
?>
</head>
<body id="about">
<div id="wrap">
	<?
		require "./_common/front.header.php";
	?>

	<!-- S: midarea -->
	<div class="midarea">
		<div class="contentsarea" id="contents">
			<div class="about">
				<section class="viewContent0">
					<span></span>
				</section>
				<section class="viewContent1">
					<span></span>
				</section>
				<section class="viewContent2">
					<span></span>
				</section>
				<section class="viewContent3">
					<span></span>
				</section>
				<section class="viewContent4">
					<span></span>
				</section>
			</div>
			<button type="button" class="btn-top" title="TOP">TOP</button>
		</div>
	</div>
	<!-- //E: midarea -->

	<div class="sub-footer"> <!-- 2020.03.23 변경 -->
		<div class="address"><strong>U:COMPANION<br />37-9, Samseong-ro 119-gil,<br />Gangnam-gu, Seoul</strong></div>
		<div class="info">
			<dl class="tel">
				<dt><span>TEL</span></dt>
				<dd>
					<ul>
						<li><span>대표전화</span><strong>070.5030.5830</strong></li>
						<li><span>기타문의</span><strong>070.5030.5831~8</strong></li>
					</ul>
				</dd>
			</dl>
			<dl class="contact">
				<dt><span>PROJECT CONTACT</span></dt>
				<dd>
					<ul>
						<li><a href="mailto:ucomp_contact@ucomp.co.kr"><strong>ucomp_contact@ucomp.co.kr</strong></a></li>
					</ul>
				</dd>
			</dl>
		</div>
		<p class="copyrights">ⓒ U:COMPANION. ALL RIGHT RESERVED</p>
	</div>
</div>

<script src="js/common_ui.js"></script>
<script src="js/front_ui.js"></script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
