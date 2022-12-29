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
	
	$arr_rs_brochurefile = getBrochureFile($conn);
	$file_no = trim($arr_rs_brochurefile[0]["FILE_NO"]);	
?>
<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8" />
	<title>유컴패니온 홈페이지</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" />
	<meta name="format-detection" content="telephone=no, date=no, address=no, email=no" />
	<meta http-equiv="X-UA-Compatible" content="ie=edge" />
	<meta name="keywords" content="ucompanion" />
	<meta content="유컴패니온" name="keywords" />
	<!--
	<meta property="og:type" content="website"> 
	<meta property="og:title" content="유컴패니온 회사소개">
	<meta property="og:description" content="유컴패니온 회사소개 유컴패니온은 우리가 갖춘 기술력과 인성으로 오늘도 고객에게 어떻게 행복을 전달할 것인가를 끊임없이 고민하고 노력합니다.">
	<meta property="og:url" content="http://admin.ucomp.co.kr/business/USR.003">
	-->
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
		<a class="skip-item" href="#content">본문 바로가기</a>
	</div>
	<!-- //page-skip -->
	<!-- page-head -->
	<div class="page-head" id="header">
		<h1 class="page-subject" id="pageSubject">
			<a class="page-name" href="../USR.001">
				<svg width="28" height="28" viewBox=" 0 0 28 28" focusable="false" xmlns="http://www.w3.org/2000/svg">
					<title>유컴패니온</title>
					<path fill-rule="evenodd" clip-rule="evenodd" d="M12.3274 18.6828C12.3274 20.5984 11.3883 21.5543 9.51139 21.5543H9.51014C7.63323 21.5543 6.69415 20.5971 6.69415 18.6828V2H2V18.6828C2 20.9398 2.68388 22.7226 4.0529 24.0351C5.42067 25.3463 7.26866 26.0013 9.51014 26.0013C11.7516 26.0013 13.5644 25.3463 14.9473 24.0351C16.3288 22.7239 17.0215 20.9398 17.0215 18.6828V2H12.3274V18.6828ZM25.3689 6.35847C24.9478 5.91972 24.4311 5.69971 23.8226 5.69971V5.70097H23.7799C23.184 5.70097 22.6724 5.92098 22.2449 6.35974C21.8162 6.79849 21.6025 7.30552 21.6025 7.92383C21.6025 8.54213 21.8162 9.06307 22.2449 9.48792C22.6724 9.9115 23.1991 10.1239 23.8226 10.1239C24.4462 10.1239 24.9654 9.91908 25.379 9.50941C25.7926 9.09974 26 8.59776 26 7.96555C26 7.33334 25.7901 6.79723 25.3689 6.35847ZM25.3739 16.4916C24.9553 16.0591 24.4462 15.8442 23.8453 15.8442H23.8013C23.1991 15.8442 22.6824 16.0692 22.25 16.5143C21.8162 16.9594 21.6013 17.5183 21.6013 18.11C21.6013 18.7018 21.8175 19.2075 22.25 19.6324C22.6824 20.056 23.2154 20.2684 23.8453 20.2684C24.4462 20.2684 24.9553 20.0496 25.3739 19.6109C25.7913 19.1721 26.0013 18.6423 26.0013 18.024C26.0013 17.4335 25.7913 16.9227 25.3739 16.4916Z"/>
				</svg>
			</a>
		</h1>
		<!-- page-navi -->
		<div class="page-navi" id="pageNavigation" data-bui-toggle="pageNavigation">
			<div class="section-wrap">
				<div class="section-head">
					<p class="section-subject"><span class="section-name">목차</span></p>
				</div>
				<div class="section-body">
					<ul class="gnb-list">
						<li class="gnb-item">
							<a class="gnb-name" href="/about/USR.002">About</a> 
							<!-- <ul class="lnb-list">
								<li class="lnb-item current"><a class="lnb-name" href="#">공지사항</a></li>
							</ul> -->
						</li>
						<li class="gnb-item current" title="선택 됨">
							<a class="gnb-name" href="/business/USR.003">Business</a> 
							<ul class="lnb-list">
								<li class="lnb-item"><a class="lnb-name" href="/business/USR.003_1">Portfolio</a></li>
							</ul>
						</li>
						<li class="gnb-item">
							<a class="gnb-name" href="/people/USR.004">People</a>
							<!-- <ul class="lnb-list">
								<li class="lnb-item"><a class="lnb-name" href="#">공지사항</a></li>
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
						<a class="btn inquiry" href="#"><span class="btn-text">견적 &middot; 문의</span></a>
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
						<title>전체메뉴</title>
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
		<div class="page-body page-business page-intro">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-subject" id="localSubject"><span class="local-name">BUSINESS</span></h2>
					<p class="local-subtitle"><span class="wbr">Our</span> <span class="wbr">BU:SINESS</span></p> 
					<p class="local-summary"><span class="wbr">유컴패니온의</span> <span class="wbr">기술을 활용하는 분야</span></p>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-navi -->
						<div class="content-navi">  
							<div class="section-head">
								<p class="section-subject"><span class="section-name">목차</span></p>
							</div>
							<div class="section-body swiper">
								<ul class="navi-list swiper-wrapper">
									<li class="navi-item swiper-slide current" title="현재 선택된 항목"><a class="navi-text" href="#room25">Room25</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#useller">U:Seller</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#siSm">SI/SM</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#portfolio">Portfolio</a></li>
								</ul>
							</div>
						</div>
						<!-- //content-navi -->
						<!-- content-body -->
						<div class="content-body">
							<!-- section -->
							<div class="section module-a style-a type-a room25" id="room25" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Room25</span></h3>
										<p class="section-summary"><span class="wbr">빅데이터 기반의 개인 맞춤화</span> <span class="wbr"><span class="em">룸 식당 전문 추천 서비스</span></span></p>
									</div>
									<div class="section-body">
										<!-- data-display -->
										<div class="data-display module-b style-b">
											<ul class="data-list">
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><p class="data-subject"><span class="data-name">FOR WHO</span></p></div>
														<div class="data-body"><span class="wbr">회식 장소 예약에 골머리를 앓는</span> <span class="wbr">평범한 직장인들을 위해!</span></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><p class="data-subject"><span class="data-name">DO WHAT</span></p></div>
														<div class="data-body"><span class="wbr">인원과 목적, 비용에 적합한</span> <span class="wbr">룸 식당만 전문 추천!</span></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><p class="data-subject"><span class="data-name">WHY ROOOM25</span></p></div>
														<div class="data-body"><span class="wbr">내가 바라던 룸 식당만 추천해주는</span> <span class="wbr">이런 서비스, 다른데는 없으니까!</span></div>
													</div>
												</li>
											</ul>
										</div>
										<!--// data-display -->
									</div>
									<div class="section-util">
										<!-- button-display -->
										<div class="button-display module-a style-a type-a">
											<span class="button-area">
												<a class="btn module-b style-c type-line normal-03 large-2x transform-size rtl-small-arrow-right" href="#"><span class="btn-text"><span class="wbr">VIEW MORE</span></a>
											</span>
										</div>
										<!-- //button-display -->
									</div>
								</div>
							</div>
							<!-- //section -->

							<!-- section -->
							<div class="section module-a style-a type-a useller" id="useller" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">U:Seller</span></h3>
										<p class="section-summary"><span class="wbr">소규모 온라인 셀러를 위한</span> <span class="wbr"><span class="em">인보이스 관리 서비스</span></span></p>
									</div>
									<div class="section-body">
										<!-- data-display -->
										<div class="data-display module-b style-b">
											<ul class="data-list">
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><p class="data-subject"><span class="data-name">FOR WHO</span></p></div>
														<div class="data-body"><span class="wbr">인스타, 트위터 등 SNS를 통한</span> <span class="wbr">소규모 판매 위주의 온라인 셀러!</span></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><p class="data-subject"><span class="data-name">DO WHAT</span></p></div>
														<div class="data-body"><span class="wbr">인보이스 발급부터 배송 현황까지</span> <span class="wbr">온라인 판매를 위한 탄탄한 기본 기능!</span></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><p class="data-subject"><span class="data-name">WHY U:SELLER</span></p></div>
														<div class="data-body"><span class="wbr">번거로운 홈페이지 구축 없이</span> <span class="wbr">유셀러 하나로 모든게 가능하니까!</span></div>
													</div>
												</li>
											</ul>
										</div>
										<!--// data-display -->										
									</div>
									<div class="section-util">
										<!-- button-display -->
										<div class="button-display module-a style-a type-a">
											<span class="button-area">
												<a class="btn module-b style-c type-line normal-03 large-2x transform-size rtl-small-arrow-right" href="#"><span class="btn-text"><span class="wbr">VIEW MORE</span></a>
											</span>
										</div>
										<!-- //button-display -->
									</div>
								</div>
							</div>
							<!-- //section -->

							<!-- section -->							
							<div class="section module-a style-b type-a si-sm" id="siSm" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">SI / SM</span></h3>
										<p class="section-summary">유컴패니온의 노하우가 담긴 웹&amp;앱 구축/운영</p>
									</div>
									<div class="section-util">
										<!-- button-display -->
										<div class="button-display module-a style-a type-a">
											<span class="button-area">
												<a class="btn module-b style-c type-line normal-03 large-2x rtl-small-arrow-right" href="/request/USR.006"><span class="btn-text"><span class="wbr">견적 &middot; 문의하기</span></a>
											</span>
										</div>
										<!-- //button-display -->
									</div>
								</div>
							</div>
							<!-- //section -->

							<!-- section -->
							<div class="section module-a style-b type-a portfolio" id="portfolio" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">portfolio</span></h3>
									</div>
									<div class="section-body">
										<!-- data-display -->
										<div class="data-display module-c style-a swiper PortfilioList">
											<ul class="data-list swiper-wrapper">
											<? 
												if (sizeof($arr_rs) > 0) {
													for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
														$P_NO					= trim($arr_rs[$j]["P_NO"]);
														$P_NAME01				= trim($arr_rs[$j]["P_NAME01"]);
														$P_NAME02				= SetStringFromDB($arr_rs[$j]["P_NAME02"]);
														$P_CATEGORY				= trim($arr_rs[$j]["P_CATEGORY"]);
														
														$arr_p_category = explode("||", $P_CATEGORY);
														$str_p_category = "";
												
														for ($h = 0 ; $h < sizeof($arr_p_category) ; $h++) {
															if ($str_p_category == "") { 
																$str_p_category = getDcodeName($conn, "PCATEGORY", str_replace("|","", $arr_p_category[$h]));
															} else {
																$str_p_category = $str_p_category ." | ".getDcodeName($conn, "PCATEGORY", str_replace("|","", $arr_p_category[$h]));
															}
														}
											?> 
												<li class="data-item swiper-slide" style="--background-image: url(../../upload_data/portfolio/<?=trim($arr_rs[$j]["P_IMG03"])?>);">
													<div class="data-wrap">
														<div class="data-head">
															<!--<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>-->
															<p class="data-caption"><span class="data-item"><?=$str_p_category?></span></p> 
															<p class="data-subject"><a class="data-name" href="USR.003_2?p_no=<?=trim($arr_rs[$j]["P_NO"])?>"><?=$P_NAME02?></a></p>
														</div>
													</div>
												</li>
												<!--
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_01.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#">LG Discovery Lab</a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_02.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#">SK 브로드밴드 B 다이렉트샵</a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_03.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">App</span></p>
															<p class="data-subject"><a class="data-name" href="#">롯데카드 마이데이타</a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_04.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#"><span class="wbr">삼성화재 구루미</span> <span class="wbr">비대면 상담 서비스</span></a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_05.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#"><span class="wbr">신한은행</span> <span class="wbr">차세대 온라인 교육 플랫폼</span></a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_06.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#"><span class="wbr">비상 마스터케이</span> <span class="wbr">한국어 화상수업</span></a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_07.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span><span class="data-item">App</span></p>
															<p class="data-subject"><a class="data-name" href="#"><span class="wbr">비상 마스터토픽</span> <span class="wbr">리뉴얼</span></a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_08.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#"><span class="wbr">키움저축은행</span> <span class="wbr">모바일 어플리케이션 구축 <span class="wbr">UI / UX 개발</span></span></a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_09.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#">씨젠 시각화 통계 구축</a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_10.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">Mobile</span></p>
															<p class="data-subject"><a class="data-name" href="#">농축협 자산관리 프로젝트</a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_11.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">Mobile</span><span class="data-item">App</span></p>
															<p class="data-subject"><a class="data-name" href="#"><span class="wbr">애큐온 저축은행</span> <span class="wbr">MOBILE APP 구축</span></a></p>
														</div>
													</div>
												</li>
												<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/business_portfolio_12.png);">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-caption"><span class="data-item">PC</span></p>
															<p class="data-subject"><a class="data-name" href="#"><span class="wbr">애큐온 저축은행</span> <span class="wbr">WEB 구축</span></a></p>
														</div>
													</div>
												</li>
												-->
											<?
													}
												}
											?>
											</ul>
											<div class="swiper-button-next"></div>
											<div class="swiper-button-prev"></div>
											<div class="swiper-scrollbar"></div>
										</div>
										<!--// data-display -->	

										<script>
											 var swiper = new Swiper(".PortfilioList", {
												slidesPerView : 'auto',
												spaceBetween: 32,
												navigation: {
													nextEl: ".swiper-button-next",
													prevEl: ".swiper-button-prev",
													},
												scrollbar: {
													el: ".swiper-scrollbar",
													hide: false,
												},
											});
										</script>
									</div>
									<div class="section-util">
										<!-- button-display -->
										<div class="button-display module-a style-a type-a">
											<span class="button-area">
												<a class="btn module-b style-c type-line normal-03 large-2x transform-size rtl-small-arrow-right" href="/business/USR.003_1"><span class="btn-text"><span class="wbr">VIEW ALL</span></a>
											</span>
										</div>
										<!-- //button-display -->
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
</html>