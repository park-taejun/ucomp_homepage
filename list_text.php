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

	//if ($nPage <> "") {
	//	$nPage = (int)($nPage);
	//} else {
	//	$nPage = 1;
	//}

	//if ($nPageSize <> "") {
	//	$nPageSize = (int)($nPageSize);
	//} else {
	//	$nPageSize = 100;
	//}

	//$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	//$nListCnt = totalCntPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);

	//$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	//if ((int)($nTotalPage) < (int)($nPage)) {
	//	$nPage = $nTotalPage;
	//}

	//$arr_rs = listPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

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
<meta property="og:url" content="http://www.ucomp.co.kr/list_text">
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
		<div class="contentsarea" id="contents">
			<div class="page-project list">
				<div class="title-area">
					<h2>PROJECT</h2>
					<ul class="sort-type">
						<li class="type-thumb">
							<a href="list_thumb" title="섬네일형 보기">
								<em>섬네일형</em>
							</a>
						</li>
						<li class="type-list on">
							<a href="list_text" title="리스트형 보기">
								<em>리스트형</em>
							</a>
						</li>
					</ul>
				</div>
				<ul class="projectbox">
						
					<?
						$arr_rs_yyyy = getPortfolioYear($conn);
						
						if (sizeof($arr_rs_yyyy) > 0) {
							
							for ($j = 0 ; $j < sizeof($arr_rs_yyyy); $j++) {

					?>
					<li class="list">
						<strong><?=trim($arr_rs_yyyy[$j]["P_YYYY"])?></strong>
						<div class="content">
							<ul>
								<?
									$arr_rs_portfolio = listPortfolioYear($conn, trim($arr_rs_yyyy[$j]["P_YYYY"]));
									
									if (sizeof($arr_rs_portfolio) > 0) {
										for ($k = 0 ; $k < sizeof($arr_rs_portfolio); $k++) {

								?>
								<li>
									<strong><?=SetStringFromDB($arr_rs_portfolio[$k]["P_CLIENT"])?></strong>
									<span><?=SetStringFromDB($arr_rs_portfolio[$k]["P_NAME02"])?></span>
								</li>
								<?
										}
									}
								?>
							</ul>
						</div>
					</li>
					<?
							}
						}
					?>

				</ul>
			</div>
			<button type="button" class="btn-top" title="TOP">TOP</button>
		</div>
	</div>
	<!-- //E: midarea -->
</div>

<script type="text/javascript" src="js/common_ui.js"></script>
<script>
	var projectInit = function () {
		this.dc = document;
		this.body = this.dc.body;
		this.project = this.dc.querySelector('.projectbox');
		this.target = this.project.querySelectorAll('li');

		this.handler();
	}
	projectInit.prototype = {
		handler : function () {
			var me = this;

			document.addEventListener('DOMContentLoaded', function() {
				me._accorEvt();
			});
		},
		_accorEvt : function() {
			$(".projectbox .list:first-child").addClass("on").find(".content").show();

			$(document).on("click", ".projectbox .list strong", function(){
				if(!$(this).parent().hasClass("on")){
					$(".projectbox .list").removeClass("on");
					$(".projectbox .list .content").stop(true, true).slideUp(300);

					$(this).parent().addClass("on");
					$(this).parent().find(".content").stop(true, true).slideDown(300);
				} else {
					$(this).parent().removeClass("on");
					$(this).parent().find(".content").stop(true, true).slideUp(300);
				}
			});
		},
	}
	//ES5 prototype
	var project = new projectInit(); //ES5 init
</script>
</body>
</html>
