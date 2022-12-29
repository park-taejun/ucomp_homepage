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
	require "../_classes/biz/story/story.php";
	   
#===============================================================
# Get Search list count
#===============================================================
	$story_no = $_POST['story_no']!=''?$_POST['story_no']:$_GET['story_no'];
	$story_gubun = $_POST['story_gubun']!=''?$_POST['story_gubun']:$_GET['story_gubun'];
	
	$arr_rs = listStoryAll($conn, $story_no, $story_gubun  );


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
				<ul>
					<? 
						if (sizeof($arr_rs) > 0) {						
							$P_STORY_NO				= trim($arr_rs[0]["STORY_NO"]);
							$P_STORY_TYPE			= trim($arr_rs[0]["STORY_TYPE"]);
							$P_STORY_NM				= trim($arr_rs[0]["STORY_NM"]);
							$P_REG_DATE				= trim($arr_rs[0]["REGDATE"]);														
							$P_FILE_NM				= trim($arr_rs[0]["FILE_NM"]);	
							$P_CONTENTS				= trim($arr_rs[0]["CONTENTS"]);	  
							$P_STORY_IMG			= trim($arr_rs[0]["STORY_IMG"]);	  
							$P_STORY_REAL_IMG		= trim($arr_rs[0]["STORY_REAL_IMG"]);	  							
					?>  
						<li> 
							<div class="main-imgbox">							
								<span class="pt-only"><?=$P_STORY_TYPE?></span>
								<span class="pt-only"><?=$P_STORY_NM?>&nbsp;<?=$P_REG_DATE?> </span>								 
								<div style="float:left;width:70%;">
									<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" readonly><?=$P_CONTENTS?></textarea>
								</div>
								<span class="bg-transparent pt-only"><img src="../images/bg_mainvisual.gif" alt=""></span>
							<span class="bg-transparent mobile-only"><img src="../images/bg_mainvisual_m.gif" alt=""></span>							
								<span class="pt-only"><img src="../upload_data/story/<?=$P_STORY_IMG?>" alt="" /></span>
							</div>	 
						</li>  
					<?
						}
					?> 
				</ul> 
				<ul>
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {							
							$P_STORY_NO				= trim($arr_rs[$j]["STORY_NO"]);
							$P_STORY_TYPE			= trim($arr_rs[$j]["STORY_TYPE"]);
							$P_STORY_NM				= trim($arr_rs[$j]["STORY_NM"]);
							$P_REG_DATE				= trim($arr_rs[$j]["REGDATE"]);							
							$P_FILE_NM				= trim($arr_rs[$j]["FILE_NM"]);	
							$P_CONTENTS				= trim($arr_rs[$j]["CONTENTS"]);	  
							$P_STORY_IMG			= trim($arr_rs[$j]["STORY_IMG"]);	  
							$P_STORY_REAL_IMG		= trim($arr_rs[$j]["STORY_REAL_IMG"]);	

				?>
					<li class="sd-<?=($j + 1)?>">						
						<div class="main-imgbox"> 							
							<span class="bg-transparent pt-only"><img src="../images/bg_mainvisual.gif" alt=""></span>
							<span class="bg-transparent mobile-only"><img src="../images/bg_mainvisual_m.gif" alt=""></span>							
							<span class="pt-only"><img src="../upload_data/story/<?=$P_FILE_NM?>" alt="" /></span>
							<span class="mobile-only"><img src="../upload_data/story/<?=$P_FILE_NM?>" alt="" /></span>								
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
