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

	$p_no								= $_POST['p_no']!=''?$_POST['p_no']:$_GET['p_no'];
	$con_p_yyyy					= $_POST['con_p_yyyy']!=''?$_POST['con_p_yyyy']:$_GET['con_p_yyyy'];
	$con_p_mm						= $_POST['con_p_mm']!=''?$_POST['con_p_mm']:$_GET['con_p_mm'];
	$con_p_category			= $_POST['con_p_category']!=''?$_POST['con_p_category']:$_GET['con_p_category'];
	$con_p_type					= $_POST['con_p_type']!=''?$_POST['con_p_type']:$_GET['con_p_type'];

	$arr_rs = selectPortfolio($conn, $p_no);

	$rs_p_no							= trim($arr_rs[0]["P_NO"]); 
	$rs_p_name01						= SetStringFromDB($arr_rs[0]["P_NAME01"]); 
	$rs_p_name02						= SetStringFromDB($arr_rs[0]["P_NAME02"]); 
	$rs_p_yyyy							= trim($arr_rs[0]["P_YYYY"]); 
	$rs_p_mm							= trim($arr_rs[0]["P_MM"]); 
	$rs_p_category						= trim($arr_rs[0]["P_CATEGORY"]); 
	$rs_p_type							= trim($arr_rs[0]["P_TYPE"]); 
	$rs_p_client						= SetStringFromDB($arr_rs[0]["P_CLIENT"]); 
	$rs_p_contents						= SetStringFromDB($arr_rs[0]["P_CONTENTS"]); 
	$rs_p_img01							= trim($arr_rs[0]["P_IMG01"]); 
	$rs_p_img02							= trim($arr_rs[0]["P_IMG02"]); 
	$rs_p_img03							= trim($arr_rs[0]["P_IMG03"]); 
	$rs_p_img04							= trim($arr_rs[0]["P_IMG04"]); 
	$rs_prize_files						= trim($arr_rs[0]["PRIZE_FILES"]); 
	$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
	$rs_keyword							= trim($arr_rs[0]["KEYWORD"]); 
	$rs_link01							= trim($arr_rs[0]["LINK01"]); 
	$rs_link02							= trim($arr_rs[0]["LINK02"]); 
	$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
	$rs_txt_color						= trim($arr_rs[0]["TXT_COLOR"]); 

	$rs_link01 = str_replace("http://","",$rs_link01);
	$rs_link01 = str_replace("https://","",$rs_link01);
	
	$arr_rs_file = listPortfolioFile($conn, $p_no);
	$arr_rs_prize = listPortfolioPrize($conn, $p_no);

	$con_use_tf = "Y";
	$con_del_tf = "N";
	$con_top_tf = "Y";

	$arr_post_rs = selectPostPortfolio($conn, $p_no, $rs_p_yyyy, $rs_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);
	
	if (sizeof($arr_post_rs) > 0) {
		$rs_post_p_no			= trim($arr_post_rs[0]["P_NO"]); 
	}

	$arr_pre_rs = selectPrePortfolio($conn, $p_no, $rs_p_yyyy, $rs_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);

	if (sizeof($arr_pre_rs) > 0) {
		$rs_pre_p_no			= trim($arr_pre_rs[0]["P_NO"]); 
	}


	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

/*
	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 7;
	}
*/

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt = totalCntPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);
	
	$nPageSize = $nListCnt;

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs_list = listPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);
	
	$this_num = "";

	if (sizeof($arr_rs_list) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs_list); $j++) {
			$rs_p_no						= trim($arr_rs_list[$j]["P_NO"]);
			if ($p_no == $rs_p_no) {
				$this_num = ($j+1);
			}
		}
	}
	
	$arr_rs_brochurefile = getBrochureFile($conn);
	$file_no = trim($arr_rs_brochurefile[0]["FILE_NO"]);

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
	
</head>
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
		<div class="page-navi" id="pageNavigation" data-bui-toggle="pageNavigation">
			<div class="section-wrap">
				<div class="section-head">
					<p class="section-subject"><span class="section-name">??????</span></p>
				</div>
				<div class="section-body">
					<ul class="gnb-list">
						<li class="gnb-item">
							<a class="gnb-name" href="/about/USR.002">About</a> 
							<!-- <ul class="lnb-list">
								<li class="lnb-item current"><a class="lnb-name" href="#">????????????</a></li>
							</ul> -->
						</li>
						<li class="gnb-item current title="?????? ???">
							<a class="gnb-name" href="/business/USR.003">Business</a> 
							<ul class="lnb-list">
								<li class="lnb-item"><a class="lnb-name" href="/business/USR.003_1">Portfolio</a></li>
							</ul>
						</li>
						<li class="gnb-item">
							<a class="gnb-name" href="/people/USR.004">People</a>
							<!-- <ul class="lnb-list">
								<li class="lnb-item"><a class="lnb-name" href="#">????????????</a></li>
							</ul> -->
						</li>
						<li class="gnb-item">
							<a class="gnb-name" href="/story/USR.005">Story</a>
							<ul class="lnb-list">
								<li class="lnb-item"><a class="lnb-name" href="/story/USR.005_3?story_div=U:Story">U:Story</a></li>
								<li class="lnb-item"><a class="lnb-name" href="/story/USR.005_3?story_div=News">News</a></li>
								<li class="lnb-item"><a class="lnb-name" href="/story/USR.005_3?story_div=Newsletter">Newsletter</a></li>
								<!--<li class="lnb-item"><a class="lnb-name" href="/story/USR.005_3?story_div=Report">Report</a></li>-->
								<li class="lnb-item"><a class="lnb-name" href="/story/USR.005_2">Awards</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<div class="section-util">
					<span class="button-area">
						<a class="btn inquiry" href="#"><span class="btn-text">?????? &middot; ??????</span></a>
					</span>
					<span class="button-area">
						<!--<a class="btn ir-download" href="#" download><span class="btn-text"><span class="wbr">Download</span> <span class="wbr">IR</span></span></a>-->
						<a class="btn ir-download" href="/_common/new_download_file.php?menu=brochure&file_no=<?=$file_no?>"><span class="btn-text"><span class="wbr">Download</span> <span class="wbr">IR</span></span></a>
						<!-- <a class="btn module-b style-c type-line accent-01 large-1x transform-size" href="#" download=""><span class="btn-text"><span class="wbr">Download</span> <span class="wbr">IR</span></a> -->
					</span>
				</div>
			</div>
		</div>
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
		<div class="page-body page-business page-portfolio-view">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-subject" id="localSubject"><span class="local-name">BUSINESS</span></h2>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-path -->
						<div class="content-path">
							<!-- section-->
							<div class="section breadcrumb">
								<div class="section-wrap">
									<div class="section-head">
										<p class="section-subject"><strong class="section-name">????????????</strong></p>
									</div>
									<div class="section-body">
										<!-- data-display -->
										<div class="data-display">
											<div class="data-list">
												<div class="data-item">
													<div class="data-wrap">
														<div class="data-head"><a class="data-name" href="#">MAIN</a></div>
													</div>
												</div>
												<div class="data-item">
													<div class="data-wrap">
														<div class="data-head"><a class="data-name" href="#">BUSINESS</a></div>
													</div>
												</div>
												<div class="data-item">
													<div class="data-wrap">
														<div class="data-head"><a class="data-name" href="#">Portfolio</a></div>
													</div>
												</div>
												<div class="data-item">
													<div class="data-wrap">
														<div class="data-head"><a class="data-name" href="#"><?=$rs_p_name01?></a></div>
													</div>
												</div>
											</div>
										</div>
										<!-- //data-display -->
									</div>
								</div>
							</div>
							<!-- section-->
						</div>
						<!-- //content-path -->
						<!-- content-body -->
						<div class="content-body">
							<div class="section" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Portfolio</span></h3>
									</div>  
									<div class="section-body">
										<!-- posts-read -->
										<div class="posts-read module-b style-a">
											<div class="posts-wrap">
												<div class="posts-inform" style="--background-image: url(../../upload_data/portfolio/<?=$rs_p_img03?>);">
													<div class="posts-head">
														<h4 class="posts-subject"><span class="posts-name"><?=$rs_p_name01?></span></h4>
														<p class="posts-summary"><?=$rs_p_name02?></p>
													</div>
													<div class="posts-data">
														<ul class="data-list">
															<li class="data-item">
																<span class="head">Client.</span>
																<span class="body"><?=$rs_p_client?></span>
															</li>
															<li class="data-item">
																<span class="head">Date.</span>
																<span class="body"><?=$rs_p_yyyy?> <?=getDcodeName($conn, "MONTH",$rs_p_mm)?></span>
															</li>
															<li class="data-item">
																<span class="head">Site URL.</span>
																<span class="body"><a class="text" href="http://<?=$rs_link01?>"><?=$rs_link01?></a></span>
															</li>
														</ul>
													</div>
													<div class="posts-body">
														<div class="portfolio-description">
															<p class="portfolio-subject">OVER VIEW</p>
															<div class="portfolio-summary">
																<?=nl2br($rs_p_contents)?>
															</div>
														</div>
													</div>
												</div>
												<div class="posts-detail">
												<?
													if (sizeof($arr_rs_file) > 0) {
														for ($j=0 ; $j < sizeof($arr_rs_file) ; $j++) {
															$RS_FILE_NO			= trim($arr_rs_file[$j]["FILE_NO"]);
															$RS_FILE_NM			= trim($arr_rs_file[$j]["FILE_NM"]);
															$RS_FILE_RNM		= trim($arr_rs_file[$j]["FILE_RNM"]);
												?>
													<img class="image" src="../../upload_data/portfolio/<?=$RS_FILE_NM?>" alt=" " /><br />
												<?
														}
													}
												?>
												</div>
											</div>
										</div>
										<!-- //posts-read -->

										<!-- pagination -->
										<div class="pagination module-a style-a type-c">
											<span class="pagination-util">
												<span class="button-area">
													<button type="button" class="btn prev"><span class="btn-text">??????</span></button>
												</span>
											</span>
											<span class="pagination-data">
												<span class="pagination-current">1</span>
												<span class="pagination-sign">/</span>
												<span class="pagination-total">30</span>
											</span>
											<span class="pagination-util">
												<span class="button-area">
													<button type="button" class="btn next"><span class="btn-text">??????</span></button>
												</span>
											</span>
											<span class="pagination-util">
												<span class="button-area">
													<a class="btn list" href="#"><span class="btn-text">??????</span></a>
												</span>
											</span>
										</div>
										<!-- //pagination -->
									</div>
								</div>
							</div>
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
</html>