<?session_start();?>  
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "0";

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
	require "./_classes/biz/portfolio/portfolio.php";


	$con_del_tf		= "N";
	$con_use_tf		= "Y";
	$con_main_tf	= "Y";
#============================================================
# Page process
#============================================================

	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 10;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt = totalCntPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);


?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<meta name="msvalidate.01" content="D3D1C99AF64E85DB61B385661327885B" />
<meta name="robots" content="index, follow">
<title>유컴패니온 | U:COMPANION</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="description" content="유컴패니온 | U:COMPANION">
<meta content="유컴패니온" name="keywords" />
<meta property="og:type" content="website"> 
<meta property="og:title" content="유컴패니온">
<meta property="og:description" content="유컴패니온 | U:COMPANION 홈페이지">
<meta property="og:url" content="http://www.ucomp.co.kr/">
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

<body id="main">
<div id="wrap">

<?
	require "./_common/front.header.php";
?>
	<!-- S: midarea -->
	<div class="midarea">
		<div class="maincontents" id="contents">
			<div class="mainvisual">
				<ul class="swiper-wrapper">
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

							$rn							= trim($arr_rs[$j]["rn"]);
							$P_NO						= trim($arr_rs[$j]["P_NO"]);
							$P_NAME01				= trim($arr_rs[$j]["P_NAME01"]);
							$P_NAME02				= SetStringFromDB($arr_rs[$j]["P_NAME02"]);
							$P_YYYY					= trim($arr_rs[$j]["P_YYYY"]);
							$P_MM						= trim($arr_rs[$j]["P_MM"]);
							$P_CATEGORY			= trim($arr_rs[$j]["P_CATEGORY"]);
							$P_CLIENT				= SetStringFromDB($arr_rs[$j]["P_CLIENT"]);
							$P_IMG01				= trim($arr_rs[$j]["P_IMG01"]);
							$P_IMG02				= trim($arr_rs[$j]["P_IMG02"]);
							$P_IMG03				= trim($arr_rs[$j]["P_IMG03"]);
							$P_IMG04				= trim($arr_rs[$j]["P_IMG04"]);
							$LINK01					= trim($arr_rs[$j]["LINK01"]);
							$HIT_CNT				= trim($arr_rs[$j]["HIT_CNT"]);
							$USE_TF					= trim($arr_rs[$j]["USE_TF"]);
							$REG_DATE				= trim($arr_rs[$j]["REG_DATE"]);
							$UP_DATE				= trim($arr_rs[$j]["UP_DATE"]);
							$TXT_COLOR			= trim($arr_rs[$j]["TXT_COLOR"]);

							$arr_rs_prize = listPortfolioPrize($conn, $P_NO);

				?>
					<li class="mv-<?=($j + 1)?> swiper-slide">
						<a href="read.php?p_no=<?=$P_NO?>"> <!-- 2020.04.03 퍼블수정 -->
							<div class="cascading-text">
								<div class="interaction-flip">
									<?
										$P_NAME01 = nl2br($P_NAME01);
										$arr_title = explode("<br />", $P_NAME01);
									?>
									<?
										for ($k=0 ; $k < strlen($arr_title[0]) ; $k++) {
											if (substr($arr_title[0], $k, 1) == " ") {
												echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
											} else {
												echo "<span>".substr($arr_title[0], $k, 1)."</span>\n";
											}
										}
									?>
								</div>
								<?
									for ($k=1 ; $k < sizeof($arr_title) ; $k++) {

										echo "<div class='interaction-up'>";

										for ($h=0 ; $h < strlen(trim($arr_title[$k])) ; $h++) {

											if (substr(trim($arr_title[$k]), $h, 1) == " ") {
												echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
											} else {
												echo "<span>".substr(trim($arr_title[$k]), $h, 1)."</span>\n";
											}
										}

										echo "</div>";

									}

									if (sizeof($arr_rs_prize) > 0) {
								?>
									<div class="award-box interaction-up">
								<?
										for ($k=0 ; $k < sizeof($arr_rs_prize) ; $k++) {
											$RS_FILE_NM			= trim($arr_rs_prize[$k]["FILE_NM"]);
								?>
										<span><img src="/upload_data/portfolio/<?=$RS_FILE_NM?>" alt="어워드 아이콘"></span>
								<?
										}
								?>
									</div>
								<?
									}
								?>
							</div>
							<div class="main-imgbox">
								<span class="pt-only"><img src="/upload_data/portfolio/<?=$P_IMG01?>" alt="" /></span>
								<span class="mobile-only"><img src="/upload_data/portfolio/<?=$P_IMG02?>" alt="" /></span>
								<p style="color:<?=$TXT_COLOR?>"><strong><?=$P_NAME02?></strong><span><?=getDcodeName($conn, "MONTH",$P_MM)?> <?=$P_YYYY?></span></p>
							</div>
						</a>
					</li>
				<?
						}
					}
				?>
				</ul>
			</div>
			<div class="swiper-pagination"></div>
			<div class="footer"><p>ⓒ  2021</p></div>
		</div>
	</div>
	<!-- //E: midarea -->
</div>

<script src="js/common_ui.js"></script>
<script>
var main = function(){
	this.isMove = false;
	this.mainvisualSlide();
}

main.prototype = {
	mainvisualSlide: function(){
		var me= this;
        var mySwiper = new Swiper ('.mainvisual', {
            speed: 500,
            loop: true,
            slidesPerView: 'auto',
            observer: true,
            observeParents: true,
            observeSlideChildren: true ,
            pagination: {
                el: '.maincontents .swiper-pagination',
                type: 'bullets',
                clickable:true
            },
            autoplay: {
                delay: 5000
            },
            on: {
                slideChange: function(){
                    var video = $(this.slides[this.activeIndex]).find('video');
                    $(this.slides).find('video').each(function () {
                        $(this)[0].load();
                        $(this)[0].pause();
                    });
                    if(video.length > 0){
                        video[0].play();
                    }
                },
            }
        });
	}
}
var mainUI = new main();
</script>
<script>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28115000-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' :'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<div id="newhouse" style="position:absolute;top:0;left:0;z-index:100;border:solid 1px #cccccc;box-shadow: 0 0 10px 0 rgb(0 0 0 / 30%);">
	<span class="pt-only">
		<img src="images/newhouse_2022.png" alt="유컴패니온 사옥이전 안내" usemap="#ex" border="0">
		<map name="ex">
			<area shape="rect" coords="477,21,498,44" href="javascript:$('#newhouse').hide()">
			<area shape="rect" coords="0,610,520,674" href="http://www.ucomp.co.kr/contact" target="_blank">
		</map>
	</span>
	<span class="mobile-only">
		<img src="images/newhouse_2022_mobile.png" alt="유컴패니온 사옥이전 안내" usemap="#ex1" border="0">
		<map name="ex1">
			<area shape="rect" coords="318,22,339,43" href="javascript:$('#newhouse').hide()">
			<area shape="rect" coords="0,592,359,639" href="http://www.ucomp.co.kr/contact" target="_blank">
		</map>
	</span>
</div>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
