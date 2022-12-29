<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "1";

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/util/ImgUtil.php";
	require "../_classes/com/util/ImgUtilResize.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/banner/banner.php";
	
	
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

	$use_tf = "Y";
	$del_tf = "N";
	
	$nListCnt = totalCntBanner($conn, $banner_type, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}   
	
	$arr_rs = listBanner($conn, $banner_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);


?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title>유컴패니온</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/reset_20200420.css" />
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery_ui.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script src="../js/slick.js"></script>
<script src="../js/cascading.js"></script>
<?
	require "../_common/google_analytics.php";
?>

</head>

<body id="main">

<div id="wrap">

<?
	require "../_common/front.header.php";
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
							$P_BANNER_NO				= trim($arr_rs[$j]["BANNER_NO"]);
							$P_BANNER_NM				= trim($arr_rs[$j]["BANNER_NM"]);
							$P_BANNER_IMG				= trim($arr_rs[$j]["BANNER_IMG"]);
							$P_BANNER_REAL_IMG			= trim($arr_rs[$j]["BANNER_REAL_IMG"]);
							$P_BANNER_URL				= trim($arr_rs[$j]["BANNER_URL"]);
							$P_TITLE_NM					= trim($arr_rs[$j]["TITLE_NM"]);
							$P_SUB_TITLE_NM				= trim($arr_rs[$j]["SUB_TITLE_NM"]);
							$P_DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);

				?>
					<li class="sd-<?=($j + 1)?>">						
						<div class="main-imgbox">
							<span class="bg-transparent pt-only"><img src="../images/bg_mainvisual.gif" alt=""></span>
							<span class="bg-transparent mobile-only"><img src="../images/bg_mainvisual_m.gif" alt=""></span>							
							<span class="pt-only"><img src="../upload_data/banner/<?=$P_BANNER_IMG?>" alt="" /></span>
							<span class="mobile-only"><img src="../upload_data/banner/<?=$P_BANNER_REAL_IMG?>" alt="" /></span>								
						</div>						
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
								$P_BANNER_NO				= trim($arr_rs[$j]["BANNER_NO"]);
								$P_BANNER_NM				= trim($arr_rs[$j]["BANNER_NM"]);
								$P_BANNER_IMG				= trim($arr_rs[$j]["BANNER_IMG"]);
								$P_BANNER_REAL_IMG			= trim($arr_rs[$j]["BANNER_REAL_IMG"]);
								$P_BANNER_URL				= trim($arr_rs[$j]["BANNER_URL"]);
								$P_TITLE_NM					= trim($arr_rs[$j]["TITLE_NM"]);
								$P_SUB_TITLE_NM				= trim($arr_rs[$j]["SUB_TITLE_NM"]);
								$P_DISP_SEQ					= trim($arr_rs[$j]["DISP_SEQ"]);  
					?> 
						
						<li class="sd-<?=($j + 1)?>">
							<!--
							<div class="main-imgbox">
								<span class="pt-only"><?=$P_BANNER_NM?></span>
							</div>  
							-->
							<div class="main-imgbox">							
								<span class="pt-only"><?=$P_BANNER_NM?></span>
								<span class="pt-only"><?=$P_TITLE_NM?><br /><?=$P_SUB_TITLE_NM?></span>								
								<? if ($P_BANNER_URL != "") { ?>
									<span class="pt-only"><a href=""><img src="../upload_data/banner/arrow.PNG" onclick="window.open('<?=$P_BANNER_URL?>')" alt="" /></a></span>
								<? } else { ?>	
									<span class="pt-only"></span>
								<? } ?>
							</div>	 
						</li>  
					<?
							}
						}
					?> 
				</ul>
				<ul>
					<div class="midarea">
						<table> 						
							<tr> 	
<?
								if (sizeof($arr_rs) > 0) {
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {	
										$P_BANNER_NO				= trim($arr_rs[$j]["BANNER_NO"]);
										$P_BANNER_URL				= trim($arr_rs[$j]["BANNER_URL"]);
										if ( $P_BANNER_URL != "" ) {
?>
											<td><a href="#"  onclick="window.open('<?=$P_BANNER_URL?>')" >A<?=$j?></a>&nbsp;</td>
<? 
										} else { 
?>
											<td>A<?=$j?>&nbsp;</td>  
								<? 		
										} 	
									}
								} 
	?> 
							</tr>  							
						</table>	
					</div>
				</ul> 
			</div>
		</div>
	</div>
	<!-- //E: midarea -->

	 
</div>

<script src="../js/common_ui.js"></script>
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
			autoplaySpeed:1000,
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
