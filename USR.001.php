<?session_start();?>
<?
	//header("x-xss-Protection:0");
//header('Content-Type: text/html; charset=UTF-8');

	$depth_01 = "0";

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "_common/config.php";
	require "_classes/com/util/Util.php";
	require "_classes/com/util/ImgUtil.php";
	require "_classes/com/util/ImgUtilResize.php";
	require "_classes/com/etc/etc.php";
	require "_classes/biz/banner/banner.php"; 
	require "_classes/biz/story/story.php";   	

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

	$arr_rs_banner = listMainBanner($conn);
	$arr_rs_story = listStoryImgAll($conn);
	

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
	<link rel="shortcut icon" href="assets/images/app/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="assets/css/module.global.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/layout.front.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/page.main.css" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/4.5.1/js/swiper.min.js"></script>
	<script src="assets/js/gsap.min.js"></script>
	<script src="assets/js/ScrollTrigger.min.js"></script>
	<script src="assets/js/ScrollToPlugin.min.js"></script>
	<script src="assets/js/swiper-bundle.min.js"></script>
	<script src="assets/js/bui.js"></script>
	<script src="assets/js/bui.fullpage.js"></script>
	<script src="assets/js/bui.template.js" defer></script>
	
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
			<a class="page-name" href="#">
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
							<li class="gnb-item">
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
		<div class="page-body page-main">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-subject" id="localSubject"><span class="local-name">유컴패니온</span></h2>
					<div class="button-display module-a style-a type-a">
						<span class="button-area">
							<a class="btn module-b style-c type-line accent-01 large-2x transform-size rtl-small-arrow-right" href="#"><span class="btn-text"><span class="wbr">VIEW MORE</span></span></a>
						</span>
					</div>
				</div>
				<!-- //local-head -->
				<!-- local-body -->
				<div class="local-body">
					<!-- content -->
					<div id="content">
						<!-- content-body -->
						<div class="content-body">
							<!-- display-board -->
							<div class="display-board billboard swiper" id="page-0" data-bui-full-page="page-0">
								<div class="board-list swiper-wrapper">
									<?
									if (sizeof($arr_rs_banner) > 0) {
										for ($j = 0 ; $j < sizeof($arr_rs_banner); $j++) { 
											$BANNER_NM				= trim($arr_rs_banner[$j]["BANNER_NM"]);											
											$BANNER_IMG				= SetStringFromDB($arr_rs_banner[$j]["BANNER_IMG"]); 
											$BANNER_REAL_IMG		= SetStringFromDB($arr_rs_banner[$j]["BANNER_REAL_IMG"]); 
											$TITLE_NM				= trim($arr_rs_banner[$j]["TITLE_NM"]);
											$SUB_TITLE_NM			= trim($arr_rs_banner[$j]["SUB_TITLE_NM"]);
											$BANNER_URL				= trim($arr_rs_banner[$j]["BANNER_URL"]);							
									?>
									<div class="board-item swiper-slide attr-temp-01" style="--background-image: url(../../upload_data/banner/<?=$BANNER_IMG?>);">
										<div class="board-wrap">
											<div class="board-head">
												<p class="board-subject">
													<span class="board-name"><span class="wbr"><?=$TITLE_NM?></span> <span class="wbr"><?=$SUB_TITLE_NM?></span></span>
												</p>
												<? if ( $BANNER_URL != "") { ?>
												<div class="button-display module-a style-a type-a">
													<span class="button-area">
														<a class="btn module-b style-c type-line accent-01 large-2x transform-size rtl-small-arrow-right" href="http://<?=$BANNER_URL?>" target="_blank"><span class="btn-text"><span class="wbr">VIEW MORE</span></span></a>
													</span>
												</div>
												<? } ?>
											</div>
										</div>
									</div>
									<!--
									<div class="board-item swiper-slide attr-temp-01" style="--background-image: url(../../assets/images/@temp/main_bg_01.png);">
										<div class="board-wrap">
											<div class="board-head">
												<p class="board-subject">
													<span class="board-name"><span class="wbr">Be With</span> <span class="wbr">U:S</span></span>
												</p>
												<div class="button-display module-a style-a type-a">
													<span class="button-area">
														<a class="btn module-b style-c type-line accent-01 large-2x transform-size rtl-small-arrow-right" href="#"><span class="btn-text"><span class="wbr">VIEW MORE</span></span></a>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="board-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_bg_02.png);">
										<div class="board-wrap">
											<div class="board-head">
												<p class="board-subject">
													<span class="board-name"><span class="wbr">New Office for</span> <span class="wbr">U:COMPANION</span></span>
												</p>
												<div class="button-display module-a style-a type-a">
													<span class="button-area">
														<a class="btn module-b style-c type-line accent-01 large-2x transform-size rtl-small-arrow-right" href="#"><span class="btn-text"><span class="wbr">VIEW MORE</span></span></a>
													</span>
												</div>
											</div>
										</div>
									</div>
									<div class="board-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_bg_03.png);">
										<div class="board-wrap">
											<div class="board-head">
												<p class="board-subject">
													<span class="board-name"><span class="wbr">We Need</span> <span class="wbr">YOU:</span></span>
												</p>
												<div class="button-display module-a style-a type-a">
													<span class="button-area">
														<a class="btn module-b style-c type-line accent-01 large-2x transform-size rtl-small-arrow-right" href="#"><span class="btn-text"><span class="wbr">VIEW MORE</span></span></a>
													</span>
												</div>
											</div>
										</div>
									</div>
									-->
								<?
										}
									}
								?>
								</div>
								<div class="swiper-control">
									<span class="btn control" aria-pressed="false" aria-label="재생 일시정지"></span>
								</div>
								<div class="swiper-progressbar"></div>
								<div class="swiper-pagination"></div>
							</div>
							<!-- //display-board -->

							<!-- section -->
							<div class="section about-business">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">About &amp; Business</span></h3>
									</div>
									<div class="section-body">
										<!-- subsection -->
										<div class="subsection module-c who-we-are" id="page-1" data-bui-full-page="page-1" data-bui-animation="type-1">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">WHO WE ARE</span></h4>
													<p class="subsection-summary"><span class="wbr">유컴패니온은</span> <span class="wbr"><strong class="em normal-00">기술, 인재, 역량</strong>을 갖춘</span> <span class="wbr">전문 IT 기업입니다.</span></p>
												</div>
												<div class="subsection-body">
													<p class="para"><span class="wbr">우리가 가진 자원으로</span> <span class="wbr">더 편한 세상을 만들어가기 위해</span> <span class="wbr">유컴패니온은 항상 고민하고 연구합니다.</span></p>
													<p class="para"><span class="wbr">신뢰할 수 있는 IT 파트너로써</span> <span class="wbr">유컴패니온은 노력을 멈추지 않습니다.</span></p>
												</div>
												<div class="subsection-util">
													<div class="button-display module-a style-a type-a">
														<span class="button-area">
															<a class="btn module-b style-c type-fill accent-03 large-2x transform-size rtl-small-arrow-right" href="/about/USR.002"><span class="btn-text"><span class="wbr">VIEW MORE</span></span></a>
														</span>
													</div>
												</div>
											</div>
										</div>	
										<!-- //subsection -->
										<!-- subsection -->
										<div class="subsection module-c what-we-do" id="page-2" data-bui-full-page="page-2" data-bui-animation="type-1">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">WHAT WE DO</span></h4>
													<p class="subsection-summary"><span class="wbr">우리가 가진 자원을 바탕으로</span> <span class="wbr"><strong class="em normal-00">다각적 상품을 제공</strong>합니다.</span></p>
												</div>
												<div class="subsection-body">
													<div class="data-display service">
														<!-- data-list -->
														<ul class="data-list">
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">Room25</span></div>
																	<div class="data-body"><span class="wbr">빅데이터 기반의 개인화 맞춤형</span> <span class="wbr">룸 식당 추천 서비스</span></div>
																	<div class="data-util"><a class="data-more" href="/business/USR.003#room25"><span class="more-text">자세히 보기</span></a></div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">U:Seller</span></div>
																	<div class="data-body"><span class="wbr">소규모 온라인 셀러를 위한</span> <span class="wbr">인보이스 관리 서비스</span></div>
																	<div class="data-util"><a class="data-more" href="/business/USR.003#useller"><span class="more-text">자세히 보기</span></a></div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">SI/SM</span></div>
																	<div class="data-body"><span class="wbr">더 나은 비즈니스를 원하는 사업체를 위한</span> <span class="wbr">시스템 구축/관리</span></div>
																	<div class="data-util"><a class="data-more" href="/business/USR.003#siSm"><span class="more-text">자세히 보기</span></a></div>
																</div>
															</li>
														</ul>
														<!-- //data-list -->
													</div>
												</div>
											</div>
										</div>	
										<!-- //subsection -->
									</div>
								</div>
							</div>
							<!-- //section -->

							<!-- section -->
							<div class="section people-story" data-bui-full-page="page-3" id="page-3">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">People &amp; Story</span></h3>
									</div>
									<div class="section-body">
										<!-- subsection -->
										<div class="subsection module-c how-we-work">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">HOW WE WORK</span></h4>
													<p class="subsection-summary"><span class="wbr">우리는 단순히 좋은 상품만을</span> <span class="wbr">고민하지 않습니다.</span></p>
												</div>
												<div class="subsection-body">
													<p class="para"><span class="wbr">어떤 문화가 더 <strong class="em normal-00">창의적인 결과</strong>를 만들어내는지</span> <span class="wbr">어떤 환경이 더 <span class="em normal-00">주도적인 사고</span>를 이끌어내는지</span></p>
													<p class="para large">우리는 한 발짝 더 나아가 생각합니다.</p>
												</div>
												<div class="subsection-util">
													<div class="button-display module-a style-a type-a">
														<span class="button-area">
															<a class="btn module-b style-c type-fill accent-03 large-2x transform-size rtl-small-arrow-right" href="#"><span class="btn-text"><span class="wbr">VIEW MORE</span></span></a>
														</span>
													</div>
												</div>
												<div class="subsection-side">
													<div class="slogan"></div>
												</div>
											</div>
										</div>	
										<!-- //subsection -->

										<!-- subsection -->
										<div class="subsection module-c our-story">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">OUR STORY</span></h4>
													<p class="subsection-summary"><span class="wbr">우리의 이야기를</span> <span class="wbr">둘러보세요.</span></p>
												</div>
												<div class="subsection-body">
													<div class="data-display module-c style-b swiper" id="ourStoryDisplay">
														<ul class="data-list swiper-wrapper">
															<?					
																if (sizeof($arr_rs_story) > 0) {
																	for ($j = 0 ; $j < sizeof($arr_rs_story); $j++) { 

																	$P_STORY_NO				= trim($arr_rs_story[$j]["STORY_NO"]);
																	$P_STORY_TYPE			= trim($arr_rs_story[$j]["STORY_TYPE"]);
																	$P_STORY_NM				= trim($arr_rs_story[$j]["STORY_NM"]);
																	$P_REG_DATE				= trim($arr_rs_story[$j]["REG_DATE"]);
																	$P_CONTENTS				= trim($arr_rs_story[$j]["CONTENTS"]);
																	$P_STORY_IMG			= trim($arr_rs_story[$j]["STORY_IMG"]);
																	$P_STORY_REAL_IMG		= trim($arr_rs_story[$j]["STORY_REAL_IMG"]);
															?>  
															<li class="data-item swiper-slide" style="--background-image: url(../../upload_data/story/<?=$P_STORY_IMG?>);">
																<div class="data-wrap">
																	<div class="data-head">
																		<p class="data-caption"><?=$P_STORY_TYPE?></p>
																		<p class="data-subject"><a class="data-name" href="story/USR.005_1?story_no=<?=$P_STORY_NO?>&story_gubun=v"><span class="wbr">(주)유컴패니온</span> <span class="wbr"><?=$P_STORY_NM?></span></a></p>
																	</div>
																</div>
															</li>
															<!--
															<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_story_01.png);">
																<div class="data-wrap">
																	<div class="data-head">
																		<p class="data-caption">News</p>
																		<p class="data-subject"><a class="data-name" href="#"><span class="wbr">(주)유컴패니온</span> <span class="wbr">서울형 강소기업 선정</span></a></p>
																	</div>
																</div>
															</li>
															<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_story_02.png);">
																<div class="data-wrap">
																	<div class="data-head">
																		<p class="data-caption">U:Story</p>
																		<p class="data-subject"><a class="data-name" href="#"><span class="wbr">유컴패니온의</span> <span class="wbr">신사옥을 소개합니다.</span></a></p>
																	</div>
																</div>
															</li>
															<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_story_03.png);">
																<div class="data-wrap">
																	<div class="data-head">
																		<p class="data-caption">Awards</p>
																		<p class="data-subject"><a class="data-name" href="#"><span class="wbr">웹 어워드 코리아 2021</span> <span class="wbr">4관왕 수상</span></a></p>
																	</div>
																</div>
															</li>
															<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_story_04.png);">
																<div class="data-wrap">
																	<div class="data-head">
																		<p class="data-caption">Newsletter</p>
																		<p class="data-subject"><a class="data-name" href="#"><span class="wbr">9월</span> <span class="wbr">유컴패니온 뉴스레터</span></a></p>
																	</div>
																</div>
															</li>
															<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_story_05.png);">
																<div class="data-wrap">
																	<div class="data-head">
																		<p class="data-caption">Newsletter</p>
																		<p class="data-subject"><a class="data-name" href="#"><span class="wbr">10월</span> <span class="wbr">유컴패니온 뉴스레터</span></a></p>
																	</div>
																</div>
															</li>
															<li class="data-item swiper-slide" style="--background-image: url(../../assets/images/@temp/main_story_06.png);">
																<div class="data-wrap">
																	<div class="data-head">
																		<p class="data-caption">Report</p>
																		<p class="data-subject"><a class="data-name" href="#"><span class="wbr">2022년 상반기</span> <span class="wbr">실적 보고서</span></a></p>
																	</div>
																</div>
															</li>
															-->
															<?						
																	} 
																} 
															?> 
														</ul>
													</div>
												</div>
											</div>
										</div>	
										<!-- //subsection -->
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
			require "_common/footer.php";
		?>
		<!-- //page-foot -->
	</div>
	<!-- //page -->
</div>
</body>
</html>