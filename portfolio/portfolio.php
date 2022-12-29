<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "2";

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
	require "../_classes/biz/portfolio/portfolio.php";

	$con_p_type						= $_POST['con_p_type']!=''?$_POST['con_p_type']:$_GET['con_p_type'];
	$con_p_category				= $_POST['con_p_category']!=''?$_POST['con_p_category']:$_GET['con_p_category'];

	$con_del_tf = "N";
	$con_use_tf = "Y";
	$con_top_tf = "Y";
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
<meta name="robots" content="index, follow">
<title>유컴패니온 : U:COMPANION PROJECT</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="description" content="유컴패니온 PROJECT">
<meta content="유컴패니온" name="keywords" />
<meta property="og:type" content="website"> 
<meta property="og:title" content="유컴패니온 PROJECT">
<meta property="og:description" content="유컴패니온 PROJECT">
<meta property="og:url" content="http://www.ucomp.co.kr/list_thumb">
<link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/reset.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery_ui.js"></script>
<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script type="text/javascript" src="../js/slick.js"></script>
</head>
<body id="project">

<div id="wrap">

<?
	require "../_common/front.header.php";
?>

	<!-- S: midarea -->
	<div class="midarea">
		<div class="contentsarea" id="contents">
			<div class="page-project thumb">
				<div class="title-area">
					<h2>PROJECT</h2>
					<ul class="sort-type">
						<li class="type-thumb on">
							<a href="list_thumb" title="섬네일형 보기">
								<em>섬네일형</em>
							</a>
						</li>
						<li class="type-list">
							<a href="list_text" title="리스트형 보기">
								<em>리스트형</em>
							</a>
						</li>
					</ul>
				</div>
				<ul class="project-list">

					<?
						if (sizeof($arr_rs) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

								$p_category = trim($arr_rs[$j]["P_CATEGORY"]);
								$arr_p_category = explode("||", $p_category);

								$str_p_category = "";

								for ($h = 0 ; $h < sizeof($arr_p_category) ; $h++) {
									$str_p_category .= "<span>".getDcodeName($conn, "PCATEGORY", str_replace("|","", $arr_p_category[$h]))."</span>";
								}

					?> 
					<li> 
						<a href="/portfolio/portfolio_read?p_no=<?=trim($arr_rs[$j]["P_NO"])?>">
							<h3>
								<strong><?=SetStringFromDB($arr_rs[$j]["P_NAME02"])?></strong>
								<span class="type pc-only">
									<?=$str_p_category?>
									<!--
									<span>PC</span>
									<span>MOBILE</span>
									-->
								</span>
							</h3>
							<div class="pic">
								<img src="../upload_data/portfolio/<?=trim($arr_rs[$j]["P_IMG03"])?>" alt="">
							</div>
						</a>
					</li>
					<?
							}
						}
					?>
				</ul>
			</div>
			<div class="scroll-down pc-only">
					<strong>SCROLL DOWN</strong>
					<div class="pic">
						<img src="images/arr_scroll_down.png" alt="">
					</div>
			</div>
			<button type="button" class="btn-top" title="TOP">TOP</button>
		</div>
	</div>
	<!-- //E: midarea -->
</div>

<script type="text/javascript" src="../js/common_ui.js"></script>
<script>
	var projectInit = function () {
		this.dc = document;
		this.body = this.dc.body;
		this.project = this.dc.querySelector('.project-list');
		this.target = this.project.querySelectorAll('li');

		this.handler();
	}
	projectInit.prototype = {
		handler : function () {
			var me = this;

			document.addEventListener('DOMContentLoaded', function() {
				me.scrollTop = window.scrollY || document.querySelector('html').scrollTop;
				me._sectionChk();
			});

			window.addEventListener('scroll', function() {
				me.scrollTop = window.scrollY || document.querySelector('html').scrollTop;
				me._scrollFadeInOut();
				me._sectionChk();
			});

			window.addEventListener('resize', function() {
				me._sectionChk();
			});
		},

		_sectionChk : function() {
			var me = this;
			var n = 0;
			this.isTablet = this.body.className.indexOf("tablet") != -1;
			this.isMobile = this.body.className.indexOf("mobile") != -1;

			var _wH = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;

			for(i = 0; i < this.target.length; i++) {
				var _projectTop = window.pageYOffset + this.target[i].getBoundingClientRect().top - _wH;
				var _headerHeight = (this.target[i].offsetHeight / 2) - 300;

				if(me.scrollTop >= _projectTop - _headerHeight) {
					this.target[i].classList.add("active");

					if(!this.isMobile){
						var count = (this.isTablet) ? 1 : 2;
						if(n > count){
							n = 0;
						}
						n++;

						if(this.target[i].classList.contains("active")) {
							this.target[i].style.transitionDelay = (n / 4) + "s";
						}
					}
				}
			}
		},
		_scrollFadeInOut : function() {
			var _scrollDown = this.dc.querySelector(".scroll-down");
			var _header = this.dc.querySelector(".header");
			var _fadePoint = _header.offsetHeight / 2;

			if(this.scrollTop != 0 && (this.scrollTop > _fadePoint)) {
				_scrollDown.classList.add('on');
			} else {
				_scrollDown.classList.remove('on');
			}
		}
	}
	//ES5 prototype
	var project = new projectInit(); //ES5 init
</script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>