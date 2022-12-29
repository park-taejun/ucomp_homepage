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
	require "../_classes/biz/story/story.php";

	$con_p_type					= $_POST['con_p_type']!=''?$_POST['con_p_type']:$_GET['con_p_type'];
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
	$nListCnt = totalCntStory($conn, $g_site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str);
							  
	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
 
	$arr_rs_ustory 		= listUStory($conn, $g_site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	$arr_rs_news 		= listNews($conn, $g_site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	$arr_rs_newsletter 	= listNewsletter($conn, $g_site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	$arr_rs_report 		= listReport($conn, $g_site_no, $story_type, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

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

<script type="text/javascript">
	function js_view(val , gubun) {
		 
		var frm = document.frm;
		frm.method = "post";
		frm.story_no.value = val;
		frm.story_gubun.value = gubun;
		frm.action = "story_read.php";
		frm.submit();
	 
	}
</script>
<form id="bbsList" name="frm" method="post" action="javascript:js_search();">
<body id="project">

<div id="wrap">

<?
	require "../_common/front.header.php";
?>
	
	<!-- S: midarea -->
	
	<div class="midarea">
		<div class="contentsarea" id="contents">
			<div class="about">
			<input type="hidden" name="story_no" value="">
			<input type="hidden" name="story_gubun" value="">
				<section>
					<span>
						<div class="midarea">
						
							<div class="maincontents" id="contents"> 
								<div class="mainvisual">				 
									<ul class="swiper-wrapper">
<? 										if (sizeof($arr_rs_ustory) > 0) {
											for ($j = 0 ; $j < sizeof($arr_rs_ustory); $j++) { 
												$P_STORY_NO				= trim($arr_rs_ustory[$j]["STORY_NO"]);
												$P_STORY_IMG			= trim($arr_rs_ustory[$j]["STORY_IMG"]);
												$P_STORY_REAL_IMG		= trim($arr_rs_ustory[$j]["STORY_REAL_IMG"]);
												$P_STORY_NM				= trim($arr_rs_ustory[$j]["STORY_NM"]);
												$P_REG_DATE				= trim($arr_rs_ustory[$j]["REG_DATE"]);
												$P_FILE_NM				= trim($arr_rs_ustory[$j]["FILE_NM"]);							
?>												 
												<li>						
													<div class="photo">
														<img src="../upload_data/story/<?=$P_STORY_IMG?>" onclick="js_view('<?=$P_STORY_NO?>','UStory')";/>
													</div> 	
													<br />
													<?=$P_STORY_NM?><br />
													<?=$P_REG_DATE?>														
												</li>												 
<?
											} 
										} 
?>   
									</ul>
								</div> 
							</div>  
						</div>
					</span>
				</section>
				<section>
					<span>
						<div class="midarea">
							<div class="maincontents" id="contents"> 
								<div class="mainvisual">				 
									<ul class="swiper-wrapper">
<? 										if (sizeof($arr_rs_news) > 0) {
											for ($j = 0 ; $j < sizeof($arr_rs_news); $j++) { 
												$P_STORY_NO				= trim($arr_rs_news[$j]["STORY_NO"]);
												$P_STORY_IMG			= trim($arr_rs_news[$j]["STORY_IMG"]);
												$P_STORY_REAL_IMG		= trim($arr_rs_news[$j]["STORY_REAL_IMG"]);
												$P_STORY_NM				= trim($arr_rs_news[$j]["STORY_NM"]);
												$P_REG_DATE				= trim($arr_rs_news[$j]["REG_DATE"]);
												$P_FILE_NM				= trim($arr_rs_news[$j]["FILE_NM"]);							
?>												 
												<li>						
													<div class="photo">
														<img src="../upload_data/story/<?=$P_STORY_IMG?>" onclick="js_view('<?=$P_STORY_NO?>','News')";/>
													</div> 	
													<br />
													<?=$P_STORY_NM?><br />
													<?=$P_REG_DATE?>														
												</li>												 
<?
										} 
									} 
?> 
									</ul>
								</div> 
							</div>  
						</div>
					</span>
				</section>
				<section>
					<span>
						<div class="midarea">
							<div class="maincontents" id="contents"> 
								<div class="mainvisual">				 
									<ul class="swiper-wrapper">
<? 										if (sizeof($arr_rs_newsletter) > 0) {
											for ($j = 0 ; $j < sizeof($arr_rs_newsletter); $j++) { 
												$P_STORY_NO				= trim($arr_rs_newsletter[$j]["STORY_NO"]);
												$P_STORY_IMG			= trim($arr_rs_newsletter[$j]["STORY_IMG"]);
												$P_STORY_REAL_IMG		= trim($arr_rs_newsletter[$j]["STORY_REAL_IMG"]);
												$P_STORY_NM				= trim($arr_rs_newsletter[$j]["STORY_NM"]);
												$P_REG_DATE				= trim($arr_rs_newsletter[$j]["REG_DATE"]);
												$P_FILE_NM				= trim($arr_rs_newsletter[$j]["FILE_NM"]);							
?>												 
												<li>						
													<div class="photo">
														<img src="../upload_data/story/<?=$P_STORY_IMG?>" onclick="js_view('<?=$P_STORY_NO?>','Newsletter')";/>
													</div> 	
													<br />
													<?=$P_STORY_NM?><br />
													<?=$P_REG_DATE?>														
												</li>												 
<?
										} 
									} 
?> 
									</ul>
								</div> 
							</div>  
						</div>
					</span>
				</section>
				<section>
					<span>
						<div class="midarea">
							<div class="maincontents" id="contents"> 
								<div class="mainvisual">				 
									<ul class="swiper-wrapper">
<? 										if (sizeof($arr_rs_report) > 0) {
											for ($j = 0 ; $j < sizeof($arr_rs_report); $j++) { 
												$P_STORY_NO				= trim($arr_rs_report[$j]["STORY_NO"]);
												$P_STORY_IMG			= trim($arr_rs_report[$j]["STORY_IMG"]);
												$P_STORY_REAL_IMG		= trim($arr_rs_report[$j]["STORY_REAL_IMG"]);
												$P_STORY_NM				= trim($arr_rs_report[$j]["STORY_NM"]);
												$P_REG_DATE				= trim($arr_rs_report[$j]["REG_DATE"]);
												$P_FILE_NM				= trim($arr_rs_report[$j]["FILE_NM"]);							
?>												 
												<li>						
													<div class="photo">
														<img src="../upload_data/story/<?=$P_STORY_IMG?>" onclick="js_view('<?=$P_STORY_NO?>','Report')";/>
													</div> 	
													<br />
													<?=$P_STORY_NM?><br />
													<?=$P_REG_DATE?>														
												</li>												 
<?
										} 
									} 
?> 
									</ul>
								</div> 
							</div>  
						</div>
					</span>
				</section> 
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
</form>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>