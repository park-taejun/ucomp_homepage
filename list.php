<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "2";

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

	$con_p_type						= $_POST['con_p_type']!=''?$_POST['con_p_type']:$_GET['con_p_type'];
	$con_p_category				= $_POST['con_p_category']!=''?$_POST['con_p_category']:$_GET['con_p_category'];

	$con_del_tf = "N";
	$con_use_tf = "Y";
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
		$nPageSize = 100;
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
<title>유컴패니온</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="description" content="유컴패니온">
<meta content="유컴패니온" name="keywords" />
<link rel="icon" type="image/x-icon" href="/images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="css/reset.css" />
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/jquery_ui.js"></script>
<script type="text/javascript" src="js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script type="text/javascript" src="js/slick.js"></script>
</head>

<body id="project">

<div id="wrap">

<?
	require "./_common/front.header.php";
?>
	<!-- S: midarea -->
	<div class="midarea">
		<div class="leftarea">
			<h2 class="tm-only">PROJECT<br />EXPERIENCE</h2>
			<ul>
				<li <? if ($con_p_type == "") echo "class='on'"; ?>><a href="list.php">ALL</a></li>
				<li <? if ($con_p_type == "C") echo "class='on'"; ?>><a href="list.php?con_p_type=C">CONSTRUCTION</a></li>
				<li <? if ($con_p_type == "M") echo "class='on'"; ?>><a href="list.php?con_p_type=M">MAINTENANCE</a></li>
			</ul>
		</div>
		<div class="contentsarea" id="contents">
			<!-- .scroll-down -->
		<div class="scroll-down pt-only">
				<strong>SCROLL DOWN</strong>
				<div class="pic">
					<img src="images/arr_scroll_down.png" alt="">
				</div>
		</div>
			<div class="project-gallery">
				<div class="project-box">
					<ul class="project-list">
						
					<?
						if (sizeof($arr_rs) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								/*
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
								*/

								if ($j == -1) { // 비주얼 영역 누출 안함
					?>
						<li class="visual">
							<a href="read.php?p_no=<?=trim($arr_rs[$j]["P_NO"])?>">

								<!-- 2020.03.24 pc, 모바일 분기	 -->
								<div class="pic pt-only">
									<img src="/upload_data/portfolio/<?=trim($arr_rs[$j]["P_IMG01"])?>" alt="">
								</div>
								<div class="pic mobile-only">
									<img src="/upload_data/portfolio/<?=trim($arr_rs[$j]["P_IMG03"])?>" alt="">
								</div>
								<!-- 2020.03.24 pc, 모바일 분기	 -->

								<strong>
									<!-- THE H PREMIUM -->
									<!-- 두 줄로 떨어질 경우 -->
									<?=nl2br(trim($arr_rs[$j]["P_NAME01"]))?>
									<!--KOREA FOREST<br />SERVICE WEBZINE<br />OPEN-->
								</strong>
							</a>
						</li>
					<?
								} else {
					?>
						<li>
							<a href="read.php?p_no=<?=trim($arr_rs[$j]["P_NO"])?>">
								<div class="pic">
									<img src="/upload_data/portfolio/<?=trim($arr_rs[$j]["P_IMG03"])?>" alt="">
								</div>
								<strong><?=SetStringFromDB($arr_rs[$j]["P_NAME02"])?></strong>
							</a>
							<?
								$j = $j + 1;
								if (trim($arr_rs[$j]["P_NO"]) <> "") {
							?>
							<a href="read.php?p_no=<?=trim($arr_rs[$j]["P_NO"])?>" class="long-img">
								<div class="pic">
									<img src="/upload_data/portfolio/<?=trim($arr_rs[$j]["P_IMG03"])?>" alt="">
								</div>
								<strong><?=SetStringFromDB($arr_rs[$j]["P_NAME02"])?></strong>
							</a>
							<?
								}
							?>
						</li>
					<?
								}
							}
						}
					?>
<!--
						<li>
							<a href="#">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>비앤빛 밝은세상안과</h2>
							</a>
							<a href="#" class="long-img">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>EBS MATH</h2>
							</a>
						</li>

						<li>
							<a href="#">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>바이오일레븐</h2>
							</a>
							<a href="#" class="long-img">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>비상교육 모바일</h2>
							</a>
						</li>


						<li>
							<a href="#">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>베어크리크 골프 클럽</h2>
							</a>
							<a href="#" class="long-img">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>신한 오픈 API 플랫폼</h2>
							</a>
						</li>
						<li>
							<a href="#">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>OUR 365</h2>
							</a>
							<a href="#" class="long-img">
								<div class="pic">
									<img src="images/@thum_img_visual.jpg" alt="">
								</div>
								<h2>비상교육 모바일</h2>
							</a>
						</li>
-->

					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- //E: midarea -->
</div>

<script type="text/javascript" src="js/common_ui.js"></script>
<!-- S:2020.04.01 스크립트 수정 -->
<script>
	var projectInit = function () {
		this.dc = document;
		this.body = this.dc.body;
		this.project = this.dc.querySelector('.project-list');
		this.target = this.project.querySelectorAll('a');

		this.handler();
	}
	projectInit.prototype = {
		handler : function () {
			var me = this;
			
			window.addEventListener('scroll', function() {
				me.scrollTop = window.scrollY || document.querySelector('html').scrollTop;
				me._scrollFadeInOut();
				me._sectionChk();
			});

			document.addEventListener('DOMContentLoaded', function() {
				me.scrollTop = window.scrollY || document.querySelector('html').scrollTop;
				me._sectionChk();
			});
		},

		_scrollFadeInOut : function() {
			var _scrollDown = this.dc.querySelector(".scroll-down");
			var _header = this.dc.querySelector(".header");
			var _fadePoint = _header.offsetHeight;

			if(this.scrollTop != 0 && (this.scrollTop > _fadePoint)) {
					_scrollDown.classList.add('on');
				} else {
					_scrollDown.classList.remove('on');
				}
		},

		_classOnOff : function(idx) {
			var _prevIndex = -1;
			this.target[idx].classList.add("active");
			_prevIndex = idx; 
		},

		_sectionChk : function(idx) {
			var me = this;
			var idx = 0;
			var _wH = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

			for(var i = 0; i < this.target.length; i++) {
				var _projectTop = window.pageYOffset + this.target[i].getBoundingClientRect().top - _wH; // 절대 위치 구한 후, 기준점을 아래로 변경
				var _projectH = this.target[i].offsetHeight / 2;

				if(me.scrollTop >= _projectTop + _projectH) {
					idx = i;
				}
				me._classOnOff(idx);
			}
		}
	}
	//ES5 prototype
var project = new projectInit(); //ES5 init
</script>
<!-- E://2020.04.01 스크립트 수정 -->
<script type="text/javascript">
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