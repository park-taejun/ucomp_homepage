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
	
	$cnt 					= $_POST['cnt']!=''?$_POST['cnt']:$_GET['cnt'];
	
	
	echo " cnt : ".$cnt."<br />";
	

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
<html lang="ko">
<head>
	<meta charset="utf-8" />
	<title>??????????????? ????????????</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
	<meta name="format-detection" content="telephone=no, date=no, address=no, email=no" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="keywords" content="ucompanion" />
	<link rel="shortcut icon" href="../assets/images/app/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="../assets/css/module.global.css" />
	<link rel="stylesheet" type="text/css" href="../assets/css/layout.front.css" />
	<link rel="stylesheet" type="text/css" href="../assets/css/page.front.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.1/js/swiper.min.js"></script>
	<script src="../assets/js/gsap.min.js"></script>
	<script src="../assets/js/ScrollTrigger.min.js"></script>
	<script src="../assets/js/ScrollToPlugin.min.js"></script>
	<script src="../assets/js/swiper-bundle.min.js"></script>
	<script src="../assets/js/bui.js"></script>
	<script src="../assets/js/bui.fullpage.js"></script>
	<script src="../assets/js/bui.template.js" defer></script>
	<script type="text/javascript" src="/admin/js/jquery-1.11.2.min.js"></script>
	<script type="text/javascript" src="/admin/js/jquery_ui.js"></script>
	<script type="text/javascript" src="/admin/js/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="/admin/js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
	<script type="text/javascript" src="/admin/js/common.js"></script>
<!--	<script src="../js/js-load.js"></script>-->
	<link type="text/css" rel="stylesheet" href="../css/js-load.css" media="screen" />
	<!--<link type="text/css" rel="stylesheet" href="../css/js-news.css" media="screen" />-->
	
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
	
</head>
<script> 
	function js_list(story_div) {
		alert(story_div);
		var frm = document.frm;
		
		frm.story_div.value = story_div;
		frm.method == "post";
		frm.action = "/story/USR.005_3.php";
		frm.submit();
		
	}
</script> 
<form id="frm" name="frm" method="post" action="javascript:js_search();"  >

<body>
<div id="wrap">
	<!-- page -->
	<div id="page">
			<!-- page-skip -->
	<div id="skip" class="page-skip">
		<a class="skip-item" href="#content">?????? ????????????</a>
	</div>
	<!-- //page-skip -->
	<!-- page-head -->
	<div class="page-head" id="header">
		<h1 class="page-subject" id="pageSubject">
			<a class="page-name" href="../USR.001">
				<svg width="28" height="28" viewBox=" 0 0 28 28" focusable="false" xmlns="http://www.w3.org/2000/svg">
					<title>???????????????</title>
					<path fill-rule="evenodd" clip-rule="evenodd" d="M12.3274 18.6828C12.3274 20.5984 11.3883 21.5543 9.51139 21.5543H9.51014C7.63323 21.5543 6.69415 20.5971 6.69415 18.6828V2H2V18.6828C2 20.9398 2.68388 22.7226 4.0529 24.0351C5.42067 25.3463 7.26866 26.0013 9.51014 26.0013C11.7516 26.0013 13.5644 25.3463 14.9473 24.0351C16.3288 22.7239 17.0215 20.9398 17.0215 18.6828V2H12.3274V18.6828ZM25.3689 6.35847C24.9478 5.91972 24.4311 5.69971 23.8226 5.69971V5.70097H23.7799C23.184 5.70097 22.6724 5.92098 22.2449 6.35974C21.8162 6.79849 21.6025 7.30552 21.6025 7.92383C21.6025 8.54213 21.8162 9.06307 22.2449 9.48792C22.6724 9.9115 23.1991 10.1239 23.8226 10.1239C24.4462 10.1239 24.9654 9.91908 25.379 9.50941C25.7926 9.09974 26 8.59776 26 7.96555C26 7.33334 25.7901 6.79723 25.3689 6.35847ZM25.3739 16.4916C24.9553 16.0591 24.4462 15.8442 23.8453 15.8442H23.8013C23.1991 15.8442 22.6824 16.0692 22.25 16.5143C21.8162 16.9594 21.6013 17.5183 21.6013 18.11C21.6013 18.7018 21.8175 19.2075 22.25 19.6324C22.6824 20.056 23.2154 20.2684 23.8453 20.2684C24.4462 20.2684 24.9553 20.0496 25.3739 19.6109C25.7913 19.1721 26.0013 18.6423 26.0013 18.024C26.0013 17.4335 25.7913 16.9227 25.3739 16.4916Z"/>
				</svg>
			</a>
		</h1>
		<!-- page-navi -->
		<?
			require "../_common/header.php";
		?>
		<!-- //page-navi -->

		<!-- page-util -->
		<!-- <div class="page-util">
			<span class="button-area">
				<a class="btn ir-download" href="#" download="" ><span class="btn-text">Download IR</span></a>
			</span>
		</div> -->
		<!-- //page-util -->

		<!-- page-control -->
		<div class="page-control">
			<span class="button-area">
				<a class="btn goto-page-navi" href="#pageNavigation" data-bui-toggle-button="pageNavigation">
					<svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg">
						<title>????????????</title>
						<line class="ob-01" x1="16.67%" y1="50%" x2="83.33%" y2="50%" />
						<line class="ob-02" x1="16.67%" y1="50%" x2="83.33%" y2="50%" />
						<line class="ob-03" x1="16.67%" y1="50%" x2="83.33%" y2="50%" />
					</svg>
				</a>
			</span>
		</div>
		<!-- //page-control -->
	</div>
	<!-- //page-head -->


	<hr />
		<!-- page-body -->
		<div class="page-body page-story page-intro">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-subject" id="localSubject"><span class="local-name">STORY</span></h2>
					<p class="local-subtitle"><span class="wbr">Story is</span> <span class="wbr">U:NLIMITED</span></p> 
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-navi -->
						<div class="content-navi">  
							<div class="section-head">
								<p class="section-subject"><span class="section-name">??????</span></p>
							</div>
							<div class="section-body swiper">
								<ul class="navi-list swiper-wrapper">
									<li class="navi-item swiper-slide current" title="?????? ????????? ??????"><a class="navi-text" href="#ustory">U:Story</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#news">News</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#newsletter">Newsletter</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#report">Report</a></li>
								</ul>
							</div>
						</div>
						<!-- //content-navi -->
						<!-- content-body -->
						<div class="content-body">
							<input type="text" name="story_div" value="">
							<!-- section -->
							<div class="section module-a style-a type-a ustory" id="ustory" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">U:Story</span></h3>
										<p class="section-summary"><span class="wbr">?????????????????? <span class="em">????????? ??????</span></span></p>
									</div>
									<div class="section-body">
										<!-- posts-display -->
										<div class="posts-display module-a style-a" id="js_ustory" >											
											<ul class="posts-list">
											<? 										
												if (sizeof($arr_rs_ustory) > 0) {
													for ($j = 0 ; $j < sizeof($arr_rs_ustory); $j++) { 
														$P_STORY_NO				= trim($arr_rs_ustory[$j]["STORY_NO"]);
														$P_STORY_IMG			= trim($arr_rs_ustory[$j]["STORY_IMG"]);
														$P_STORY_REAL_IMG		= trim($arr_rs_ustory[$j]["STORY_REAL_IMG"]);
														$P_STORY_NM				= trim($arr_rs_ustory[$j]["STORY_NM"]);
														$P_REG_DATE				= trim($arr_rs_ustory[$j]["REGDATE"]);
														$P_FILE_NM				= trim($arr_rs_ustory[$j]["FILE_NM"]);							
											?>			
												<!--<li class="posts-item">-->
												<!--<li class="lists__item js-load">-->
												<li class="posts-item js-ustory">
													<div class="posts-wrap">
														<div class="posts-figure">
															<a class="posts-thumbnail" href="#">
																<a class="data-name" href="USR.005_1?story_no=<?=$P_STORY_NO?>&story_gubun=v"><img class="image" src="../../upload_data/story/<?=$P_STORY_IMG?>" alt="" /></a>
															</a>
														</div>
														<div class="posts-inform">
															<div class="posts-head">
																<p class="posts-subject"><span class="posts-name"><?=$P_STORY_NM?></span></p>
															</div>
															<div class="posts-data">
																<p class="data-list">
																	<span class="data-item date">
																		<span class="head">?????????</span>
																		<span class="body"><?=$P_REG_DATE?></span>
																	</span>
																</p>
															</div>
														</div>
													</div>
												</li>
											<?
													} 
												} 
											?>
											</ul>											
											<div class="button-display module-a style-a type-c" id="js_btn_ustory">												
												<span class="button-area">
													<button class="btn module-b style-c type-fill normal-00 large-2x symbol-rtl-chevron-down" type="button" onclick="javascript:js_list('UStory');" ><span class="btn-text">MORE1</span></button>
												</span>
											</div>
										</div>
										<!-- //posts-display -->
									</div>
								</div>
							</div>
							<!-- //section -->
							<!-- section -->
							<div class="section module-a style-a type-a news" id="news" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">News</span></h3>
										<p class="section-summary"><span class="wbr">?????????????????? <span class="em">????????????</span></span></p>
									</div>
									<div class="section-body">
										<!-- posts-display -->
										<div class="posts-display module-a style-a" id="js_news">
											<ul class="posts-list">
											<? 										
												if (sizeof($arr_rs_news) > 0) {
														for ($j = 0 ; $j < sizeof($arr_rs_news); $j++) { 
															$P_STORY_NO				= trim($arr_rs_news[$j]["STORY_NO"]);
															$P_STORY_IMG			= trim($arr_rs_news[$j]["STORY_IMG"]);
															$P_STORY_REAL_IMG		= trim($arr_rs_news[$j]["STORY_REAL_IMG"]);
															$P_STORY_NM				= trim($arr_rs_news[$j]["STORY_NM"]);
															$P_REG_DATE				= trim($arr_rs_news[$j]["REGDATE"]);
															$P_FILE_NM				= trim($arr_rs_news[$j]["FILE_NM"]);							
											?>	
												<li class="posts-item js-new">
													<div class="posts-wrap">
														<div class="posts-figure">
															<a class="posts-thumbnail" href="#">
																<a class="data-name" href="USR.005_1?story_no=<?=$P_STORY_NO?>&story_gubun=v"><img class="image" src="../../upload_data/story/<?=$P_STORY_IMG?>" alt="" /></a>
															</a>
														</div>
														<div class="posts-inform">
															<div class="posts-head">
																<p class="posts-subject"><span class="posts-name"><?=$P_STORY_NM?></span></p>
															</div>
															<div class="posts-data">
																<p class="data-list">
																	<span class="data-item date">
																		<span class="head">?????????</span>
																		<span class="body"><?=$P_REG_DATE?></span>
																	</span>
																</p>
															</div>
														</div>
													</div>
												</li>
											<?
													} 
												} 
											?>
											</ul>
											<div class="button-display module-a style-a type-c" id="js_btn_news">												
												<span class="button-area">
													<button class="btn module-b style-c type-fill normal-00 large-2x symbol-rtl-chevron-down" type="button" ><span class="btn-text"><a href="javascript:;" class="button_news">MORE</a></span></button>
												</span>
											</div>
										</div>
										<!-- //posts-display -->
									</div>
								</div>
							</div>
							<!-- //section -->
							<!-- section -->
							<div class="section module-a style-a type-a newsletter" id="newsletter" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Newsletter</span></h3>
										<p class="section-summary"><span class="wbr">??????????????? <span class="em">????????????</span></span></p>
									</div>
									<div class="section-body">
										<!-- posts-display -->
										<div class="posts-display module-a style-a"  id="js_newsletter">
											<ul class="posts-list">
											<? 										
												if (sizeof($arr_rs_newsletter) > 0) {
													for ($j = 0 ; $j < sizeof($arr_rs_newsletter); $j++) { 
														$P_STORY_NO				= trim($arr_rs_newsletter[$j]["STORY_NO"]);
														$P_STORY_IMG			= trim($arr_rs_newsletter[$j]["STORY_IMG"]);
														$P_STORY_REAL_IMG		= trim($arr_rs_newsletter[$j]["STORY_REAL_IMG"]);
														$P_STORY_NM				= trim($arr_rs_newsletter[$j]["STORY_NM"]);
														$P_REG_DATE				= trim($arr_rs_newsletter[$j]["REGDATE"]);
														$P_FILE_NM				= trim($arr_rs_newsletter[$j]["FILE_NM"]);							
											?>	
												<li class="posts-item  js-newsletter">
													<div class="posts-wrap">
														<div class="posts-figure">
															<a class="posts-thumbnail" href="#">
																<a class="data-name" href="USR.005_1?story_no=<?=$P_STORY_NO?>&story_gubun=v"><img class="image" src="../../upload_data/story/<?=$P_STORY_IMG?>" onclick="js_view('<?=$P_STORY_NO?>','Newsletter')"; alt="" /></a>
															</a>
														</div>
														<div class="posts-inform">
															<div class="posts-head">
																<p class="posts-subject"><span class="posts-name"><?=$P_STORY_NM?></span></p>
															</div>
															<div class="posts-data">
																<p class="data-list">
																	<span class="data-item date">
																		<span class="head">?????????</span>
																		<span class="body"><?=$P_REG_DATE?></span>
																	</span>
																</p>
															</div>
														</div>
													</div>
												</li>
											<?
													} 
												} 
											?>
											</ul>
											<div class="button-display module-a style-a type-c"  id="js_btn_newsletter">
												<span class="button-area">
													<button class="btn module-b style-c type-fill normal-00 large-2x symbol-rtl-chevron-down" type="button"><span class="btn-text"><a href="javascript:;" class="button_newsletter">MORE</a></span></button>
												</span>
											</div>
										</div>
										<!-- //posts-display -->
									</div>
								</div>
							</div>
							<!-- //section -->
							<!-- section -->
							<div class="section module-a style-a type-a report" id="report" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Report</span></h3>
										<p class="section-summary"><span class="wbr">??????????????? <span class="em">???????????????</span></span></p>
									</div>
									<input type="file" accept="image/*;capture=camera"/>
									
									<div class="section-body">
										<!-- posts-display -->
										<div class="posts-display module-a style-a" id="js_report">
											<ul class="posts-list">
											<? 										
												if (sizeof($arr_rs_report) > 0) {
													for ($j = 0 ; $j < sizeof($arr_rs_report); $j++) { 
														$P_STORY_NO				= trim($arr_rs_report[$j]["STORY_NO"]);
														$P_STORY_IMG			= trim($arr_rs_report[$j]["STORY_IMG"]);
														$P_STORY_REAL_IMG		= trim($arr_rs_report[$j]["STORY_REAL_IMG"]);
														$P_STORY_NM				= trim($arr_rs_report[$j]["STORY_NM"]);
														$P_REG_DATE				= trim($arr_rs_report[$j]["REGDATE"]);
														$P_FILE_NM				= trim($arr_rs_report[$j]["FILE_NM"]);							
											?>		
												<li class="posts-item js-report">
													<div class="posts-wrap">
														<div class="posts-figure">
															<a class="posts-thumbnail" href="#">
																<a class="data-name" href="USR.005_1?story_no=<?=$P_STORY_NO?>&story_gubun=v"><img class="image" src="../../upload_data/story/<?=$P_STORY_IMG?>" onclick="js_view('<?=$P_STORY_NO?>','Report')"; alt="" /></a>
															</a>
														</div>
														<div class="posts-inform">
															<div class="posts-head">
																<p class="posts-subject"><span class="posts-name"><?=$P_STORY_NM?></span></p>
															</div>
															<div class="posts-data">
																<p class="data-list">
																	<span class="data-item date">
																		<span class="head">?????????</span>
																		<span class="body"><?=$P_REG_DATE?></span>
																	</span>
																</p>
															</div>
														</div>
													</div>
												</li>
											<?
													} 
												} 
											?>
											</ul>
											<div class="button-display module-a style-a type-c" id="js_btn_report">
												<span class="button-area">
													<button class="btn module-b style-c type-line accent-02 large-2x symbol-rtl-chevron-down" type="button"><span class="btn-text"><a href="javascript:;" class="button_report">MORE</a></span></button>
													
												</span>
											</div>
										</div>
										<!-- //posts-display -->
									</div>
								</div>
							</div>
							<!-- //section -->
						</div>
						<!-- //content-body -->
					</div>
					<!-- //content -->
				</div>
				<!-- //local-body -->
			</div>
			<!-- //local -->
		</div>
		<!-- //page-body -->
				<hr />
		<!-- page-foot -->
		<?
			require "../_common/footer.php";
		?>
		<!-- //page-foot -->
	</div>
	<!-- //page -->
</div>
</body>
</form>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?> 
<script>
	$(window).on('load', function () {
		
		ustory_load('#js_ustory', '2');
		$("#js_btn_ustory .button_ustory").on("click", function () {		
			ustory_load('#js_ustory', '2', '#js_btn_ustory');
		})
		
		news_load('#js_news', '2');
		$("#js_btn_news .button_news").on("click", function () {
			news_load('#js_news', '2', '#js_btn_news');
		})
		
		newsletter_load('#js_newsletter', '2');
		$("#js_btn_newsletter .button_newsletter").on("click", function () {
			newsletter_load('#js_newsletter', '2', '#js_btn_newsletter');
		})
		
		report_load('#js_report', '2');
		$("#js_btn_report .button_report").on("click", function () {
			report_load('#js_report', '2', '#js_btn_report');
		})
		
	});
	 
	function ustory_load(ustory_id, ustory_cnt, ustory_btn) {	
		var ustory_list = ustory_id + " .js-ustory:not(.active)";
		var ustory_length = $(ustory_list).length;
		var ustory_total_cnt;
		 
		if (ustory_cnt < ustory_length) {
			ustory_total_cnt = ustory_cnt;
		} else {
			ustory_total_cnt = ustory_length;
			$('.button_ustory').hide()
			$('.button-display').hide()			
		}
		$(ustory_list + ":lt(" + ustory_total_cnt + ")").addClass("active");
	} 
	 
	function news_load(news_id, news_cnt, news_btn) {
		var news_list = news_id + " .js-new:not(.active)";			
		var news_length = $(news_list).length;
		var news_total_cnt;
		 
		if (news_cnt < news_length) {
			news_total_cnt = news_cnt;
		} else {
			news_total_cnt = news_length;
			$('.button_news').hide()
		}
		$(news_list + ":lt(" + news_total_cnt + ")").addClass("active");
	}
	
	function newsletter_load(newsletter_id, newsletter_cnt, newsletter_btn) {
		var newsletter_list = newsletter_id + " .js-newsletter:not(.active)";			
		var newsletter_length = $(newsletter_list).length;
		var newsletter_total_cnt;
		 
		if (newsletter_cnt < newsletter_length) {
			newsletter_total_cnt = newsletter_cnt;
		} else {
			newsletter_total_cnt = newsletter_length;
			$('.button_newsletter').hide()
		}
		$(newsletter_list + ":lt(" + newsletter_total_cnt + ")").addClass("active");
	}
	
	function report_load(report_id, report_cnt, report_btn) {
		var report_list = report_id + " .js-report:not(.active)";			
		var report_length = $(report_list).length;
		var report_total_cnt;
		 
		if (report_cnt < report_length) {
			report_total_cnt = report_cnt;
		} else {
			report_total_cnt = report_length;
			$('.button_report').hide()
		}
		$(report_list + ":lt(" + report_total_cnt + ")").addClass("active");
	}
</script> 