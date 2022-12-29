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
<title>유컴패니온</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="css/reset_20200420.css" />
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/jquery_ui.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script src="js/slick.js"></script>
<script src="js/cascading.js"></script>
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
				<ul class="slideimg">
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

				?>
					<li class="sd-<?=($j + 1)?>">
						<a href="read.php?p_no=<?=$P_NO?>"> <!-- 2020.04.03 퍼블수정 -->
							<div class="main-imgbox">
								<span class="bg-transparent pt-only"><img src="images/bg_mainvisual.gif" alt=""></span>
								<span class="bg-transparent mobile-only"><img src="images/bg_mainvisual_m.gif" alt=""></span>							
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
				<ul class="slidetxt">
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
							$PRIZE_FILES		= trim($arr_rs[$j]["PRIZE_FILES"]);

							$arr_rs_prize = listPortfolioPrize($conn, $P_NO);

				?>

					<li class="sd-<?=($j + 1)?> <? if ($j == 0) echo "on" ?>">
						<div class="cascading-text cascading-text--flip" data-animated="data-animated">
							<?
								$P_NAME01 = nl2br($P_NAME01);
								$arr_title = explode("<br />", $P_NAME01);
							?>
							<?
								 
								for ($k=0 ; $k < strlen($arr_title[0]) ; $k++) {
									if (substr($arr_title[0], $k, 1) == " ") {
										echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
									} else {
										echo "<span class='cascading-text__letter tx--black'>".substr($arr_title[0], $k, 1)."</span>\n";
									}
								}
							 
							?>
							<?
						 
								for ($k=1 ; $k < sizeof($arr_title) ; $k++) {
									
									echo "<div class='interaction-box'>";
									echo "<div class='interaction-up'>";

									for ($h=0 ; $h < strlen(trim($arr_title[$k])) ; $h++) {

										if (substr(trim($arr_title[$k]), $h, 1) == " ") {
											echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;\n";
										} else {
											echo "<span class='tx--black'>".substr(trim($arr_title[$k]), $h, 1)."</span>\n";
										}
									}

									echo "</div>";
									echo "</div>";

								}
 
								if (sizeof($arr_rs_prize) > 0) {
							?>
								<div class="award-box" data-animated="data-animated">
							<?
							/*
									for ($k=0 ; $k < sizeof($arr_rs_prize) ; $k++) { 
										$RS_FILE_NM			= trim($arr_rs_prize[$k]["FILE_NM"]);
							?>
									<span><img src="/upload_data/portfolio/<?=$RS_FILE_NM?>" alt="어워드 아이콘"></span>
							<?
									}
									*/
							?>
								</div>
							<?
								}
							?>
						</div>
					</li>
				<?
						}
					}
				?>
				</ul>
				<div class="slick-dots-group"></div>
			</div>
		</div>
	</div>
	<!-- //E: midarea -->

	<div class="footer">
		<p>ⓒ  2020</p>
	</div>
</div>

<script src="js/common_ui.js"></script>
<script>
var main = function(){
	this.isMove = false;
	this.mainVisual = $(".mainvisual ul.slideimg");
	this.mainvisualSlide();
	this.afterChange();
	this.changeText(0);
	//this.WheelControll();
}

main.prototype = {
	mainvisualSlide: function(){
		var me= this;
		me.mainVisual.slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			centerPadding: '2rem',
			autoplay:true,
			autoplaySpeed:5000,
			arrows:false,
			dots:true,
			appendDots: $(".mainvisual .slick-dots-group"),
			touchMove: false,
			touchThreshold:10,
		});
	},
	afterChange : function (){
		var me = this;
		me.mainVisual.on('afterChange', function(event, slick, currentSlide, nextSlide){			
			$(".pagecount sub").text(currentSlide+1);
			me.changeText(currentSlide);
			var timer = setTimeout(function (){
				me.isMove = false;
				clearTimeout(timer);
			}, 700);
		});
	},
	changeText: function(currentSlide){
		$(".slidetxt li").removeClass("on");
		$(".slidetxt li").eq(currentSlide).addClass("on");
	},
	WheelControll: function(currentSlide){
		var me = this;
		$("body#main").on("mousewheel DOMMouseScroll",function(e){
			var listSize = me.mainVisual.find(".slick-slide").length -1;
			
			if(e.originalEvent.wheelDelta > 0) {
				if(currentSlide == 0){
					return false;
				}else{
					if(me.isMove){
						return;
					}
					me.mainVisual.slick('slickPrev');
					me.isMove = true;
				}
			}else{
				if(currentSlide == listSize){
					return false;
				}else{
					if(me.isMove){
						return;
					}
					me.mainVisual.slick('slickNext');
					me.isMove = true;
				}		
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
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
