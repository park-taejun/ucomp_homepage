<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "0";

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
	require "../_classes/biz/partner/partner.php";   

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

	$nListCnt = totalAllCntPartner($conn, $use_tf, $del_tf, $search_field, $search_str);
							  
	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
 
	$arr_rs = listAllPartner($conn, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);


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
<link type="text/css" rel="stylesheet" href="../css/reset.css" />
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery_ui.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script src="../js/swiper.min.js"></script>
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
				<ul class="swiper-wrapper">
<?					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) { 

							$P_PARTNER_NO			= trim($arr_rs[$j]["PARTNER_NO"]);
							$P_PARTNER_NM			= trim($arr_rs[$j]["PARTNER_NM"]);
							$P_DOWN_IMG				= trim($arr_rs[$j]["DOWN_IMG"]);
							$P_DOWN_REAL_IMG		= trim($arr_rs[$j]["DOWN_REAL_IMG"]);
							$P_UP_IMG				= trim($arr_rs[$j]["UP_IMG"]);
							$P_UP_REAL_IMG			= trim($arr_rs[$j]["UP_REAL_IMG"]);
?>
					<li>						
						<div class="photo">
							<img src="../upload_data/partner/<?=$P_DOWN_IMG?>" onmouseover="this.src='/upload_data/partner/<?=$P_UP_IMG?>'" onmouseout="this.src='/upload_data/partner/<?=$P_DOWN_IMG?>'" />
						</div>
					</li>
<?						
						} 
					} 

?> 
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
<img src="images/newhouse_2022.png" alt="유컴패니온 사옥이전 안내" usemap="#ex" border="0">
<map name="ex">
<area shape="rect" coords="466,12,508,54" href="javascript:$('#newhouse').hide()">
<area shape="rect" coords="0,610,520,674" href="http://www.ucomp.co.kr/contact" target="_blank">
</map>
</div>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
