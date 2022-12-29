<?session_start();?>
<? 
//header("x-xss-Protection:0");
//header('Content-Type: text/html; charset=UTF-8');

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
						<li class="gnb-item current" title="선택 됨">
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
								<li class="lnb-item"><a class="lnb-name" href="/story/USR.005_3?story_div=UStory">U:Story</a></li>
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
		<div class="page-body page-about page-intro">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-subject" id="localSubject"><span class="local-name">About</span></h2>
					<p class="local-subtitle"><span class="wbr">About</span> <span class="wbr">U:S</span></p> 
					<p class="local-summary"><span class="wbr">유컴패니온의 전문성과 가능성으로</span> <span class="wbr">세상과 소통하는 방법</span></p>
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
									<li class="navi-item swiper-slide current" title="현재 선택된 항목"><a class="navi-text" href="#missionVision">Mission &amp; Vision</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#philosophy">Philosophy</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#history">History</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#partners">Partners</a></li>
								</ul>
							</div>
						</div>
						<!-- //content-navi -->
						<!-- content-body -->
						<div class="content-body">
							<!-- section -->
							<div class="section module-a style-b type-a misson-vision" id="missionVision" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Mission&amp;Vision</span></h3>
									</div>
									<div class="section-body">
										<div class="subsection module-a style-b type-a">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">Mission</span></h4>
													<p class="subsection-summary"><span class="wbr">끊임없는 고민과 연구를 통해</span> <span class="wbr"><strong class="em">편리한 생활을 만드는 기술</strong></span></p>
												</div>
											</div>
										</div>
										<div class="subsection module-a style-b type-a">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">Vision</span></h4>
													<p class="subsection-summary"><span class="wbr">실천하는 인재, 만족하는 고객과</span> <span class="wbr"><strong class="em">함께 성장하는 IT 파트너</strong></span></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- //section -->
							<!-- section -->
							<div class="section module-a style-a type-a philosophy">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Philosophy</span></h3>
									</div>
									<div class="section-body">
										<div class="subsection module-a style-a type-a value" data-bui-animation="type-1">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">Value</span></h4>
													<p class="subsection-summary"><span class="wbr">문화를 선도하는 IT 기업으로 나아가는</span> <span class="wbr">유컴패니온 성공의 씨앗</span></p>
												</div>
												<div class="subsection-body">
													<div class="data-display module-a type-c style-b">
														<ul class="data-list">
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">Expertise</span></div>
																	<div class="data-body"><span class="wbr">우리는 <span class="em">전문성</span>을 가진</span> <span class="wbr">자부심 있는 인재다.</span></div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">Expertise</span></div>
																	<div class="data-body"><span class="wbr">우리는 <span class="em">전문성</span>을 가진</span> <span class="wbr">자부심 있는 인재다.</span></div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">Expertise</span></div>
																	<div class="data-body"><span class="wbr">우리는 <span class="em">전문성</span>을 가진</span> <span class="wbr">자부심 있는 인재다.</span></div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">Expertise</span></div>
																	<div class="data-body"><span class="wbr">우리는 <span class="em">전문성</span>을 가진</span> <span class="wbr">자부심 있는 인재다.</span></div>
																</div>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>
										<div class="subsection module-a style-a type-a ceo" id="philosophy" data-bui-animation="type-1">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">사람중심경영</span></h4>
													<p class="subsection-summary"><span class="wbr">수평적 기업 문화와 유연한 사고로</span> <span class="wbr">사내 구성원과 고객이 행복으로 동행하는 성장</span></p>
												</div>
												<div class="subsection-body">
													<!-- info-board -->
													<figure class="info-board">
														<div class="board-wrap">
															<div class="board-head">
																<p class="board-subject"><span class="wbr">안녕하십니까?</span> <span class="wbr"><span class="em normal-01">(주)유컴패니온 대표이사 한수진입니다.</span></span></p>
															</div>
															<blockquote class="board-body">
																<p class="para"><span class="wbr">(주)유컴패니온은 2006년 설립하여 SI/SM 사업을 바탕으로</span> <span class="wbr">데이터 시각화, UI/UX 사용성 연구, Mobile, Web APP, Brand Design 등</span> <span class="wbr">서비스 구축 및 컨설팅 사업을 영위하는 기술혁신형 중소기업입니다.</span></p>
																<p class="para"><span class="wbr">사용자의 편의성을 고려한 인터페이스 개선 프로젝트를 지속적으로 수행하고 있으며</span> <span class="wbr">특히, 데이터 시각화와 비대면 화상 플랫폼 솔루션 개발에 주력하고 있습니다.</span></p>
																<p class="para"><span class="wbr">IT 산업은 지식과 기술을 동시에 가졌기 때문에 사회적으로 우리의 일이</span> <span class="wbr">	현실에 반영되고 영향을 끼치는데 큰 역할을 할 수 있다고 생각합니다.</span></p>
																<p class="para"><span class="wbr">이러한 생각들이 기업 창업에 바탕이 되어 유컴패니온은 </span> <span class="wbr">‘기술로 사람을 이롭게 하겠다는 의지’ 로 사회에 긍정적 영향력을</span> <span class="wbr">펼치는 회사로 성장하고자 합니다.</span></p>
															</blockquote>
															<figcaption class="board-side">(주)유컴패니온 대표이사 <cite class="em">한수진</cite></figcaption>
														</div>
													</figure>
													<!-- //info-board -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- //section -->
							<!-- section -->
							<div class="section module-a style-a type-a history" id="history" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">History</span></h3>
									</div>
									<div class="section-body">
										<div class="data-display">
											<ul class="data-list">
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-subject"><span class="data-name">2021</span></p>
															<p class="data-summary">기술과 혁신</p>
														</div>
														<div class="data-body">
															<div class="data-display">
																<!-- data-list -->
																<ul class="data-list">
																	<li class="data-item accent">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2021</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">마이데이터 기반 자산관리 앱 사용성 평가 논문 게재 (기업부설연구소)</li>
																					<li class="data-item">국토교통 데이터 생태계 조성을 위한 공동협의체 구성 참여</li>
																					<li class="data-item">중소벤처기업진흥공단 탄소중립 생활 확산을 위한 실천 결의 동참</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2020</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">웹어워드 코리아 2020 디자인 이노베이션 대상</li>
																					<li class="data-item">INNO BIZ인증_한국기술보증기금</li>
																					<li class="data-item">유컴패니온 광화문 지점 확장</li>
																					<li class="data-item">씨젠(주) 협력업체 등록</li>
																					<li class="data-item">현대건설 THE H 웹접근성 인증마크 획득</li>
																					<li class="data-item">농협은행(주) 협력업체 등록</li>
																					<li class="data-item">청년친화강소기업인증 (2020.3)</li>
																					<li class="data-item">기업부설연구소인증 (2020.3)</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																</ul>
																<!-- //data-list -->
															</div>
														</div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-subject"><span class="data-name">2006 - 2019</span></p>
															<p class="data-summary">지속적인 성장</p>
														</div>
														<div class="data-body">
															<div class="data-display">
																<!-- data-list -->
																<ul class="data-list">
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2019</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">나이스디앤비우수기술기업L3 인증</li>
																					<li class="data-item">현대건설 힐스테이트 브랜드 연간 유지보수 운영</li>
																					<li class="data-item">EBS수능 온라인 서비스 연간 운영</li>
																					<li class="data-item">SKT 사내컴 포탈 연간운영</li>
																					<li class="data-item">LG하우시스 사보 및 웹 매거진 연간 운영</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2018</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">여성가족부 가족친화우수기업 인증</li>
																					<li class="data-item">현대오토에버(주) 협력업체 등록</li>
																					<li class="data-item">신한DS 협력업체 등록</li>
																					<li class="data-item">힐스테이트 (현대건설/현대엔지니어링)웹접근성 인증마크 획득</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2017</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">쌍용정보통신(주) 협력업체 등록</li>
																					<li class="data-item">(주)LG하우시스 협력업체 등록</li>
																					<li class="data-item">현대건설 힐스테이트 브랜드 연간 유지보수</li>
																					<li class="data-item">EBS수능 온라인 서비스 연간 운영</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2016</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">금융결제원 핀테크 지원서비스 유지운영</li>
																					<li class="data-item">LIG시스템즈 협력업체 등록</li>
																					<li class="data-item">EBS수능 온라인서비스 운영업체 선정</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2015</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">한국통신사업자연합회 ‘스마트초이스’ 웹접근성 인증마크 획득</li>
																					<li class="data-item">환경인력개발원 웹접근성 인증마크 획득</li>
																					<li class="data-item">EBS2 개국설명회 프로모션 기획</li>
																					<li class="data-item">(주)효성 사내 캠페인 연간 운영</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2014</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">문화관광부 행복한기업 선정 2014 여가친화 기업 인증</li>
																					<li class="data-item">기업부설연구소 설립 벤처기업 인증-한국산업기술진흥협회 (2014.7)</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2013</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">쌍용정보통신(주) 협력업체 등록</li>
																					<li class="data-item">(주)LG하우시스 협력업체 등록</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2012</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">(주)유컴패니온 법인 전환/소프트웨어사업자 등록</li>
																					<li class="data-item">SK(주)협력업체 등록</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																	<li class="data-item">
																		<div class="data-wrap">
																			<div class="data-head"><span class="data-name">2006</span></div>
																			<div class="data-body">
																				<!-- data-list -->
																				<ul class="data-list">
																					<li class="data-item">유컴패니온 설립 (2006.8.9)</li>
																				</ul>
																				<!-- //data-list -->
																			</div>
																		</div>
																	</li>
																</ul>
																<!-- //data-list -->
															</div>
														</div>
													</div>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>	
							<!-- //section -->

							<!-- section -->
							<div class="section module-a style-a type-a partners" id="partners" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Partners</span></h3>
									</div>
									<div class="section-body">
										<div class="data-display partners">
											<!-- data-list -->
											<ul class="data-list">
												<?					
												if (sizeof($arr_rs) > 0) {
													for ($j = 0 ; $j < sizeof($arr_rs); $j++) { 
														$P_PARTNER_NO			= trim($arr_rs[$j]["PARTNER_NO"]);
														$P_PARTNER_NM			= trim($arr_rs[$j]["PARTNER_NM"]);
														$P_DOWN_IMG				= trim($arr_rs[$j]["DOWN_IMG"]);
														$P_DOWN_REAL_IMG		= trim($arr_rs[$j]["DOWN_REAL_IMG"]);
														$P_UP_IMG				= trim($arr_rs[$j]["UP_IMG"]);
														$P_UP_REAL_IMG			= trim($arr_rs[$j]["UP_REAL_IMG"]);
														$P_PORTFOLIO_NM			= trim($arr_rs[$j]["PORTFOLIO_NM"]);
												?>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../upload_data/partner/<?=$P_DOWN_IMG?>" onmouseover="this.src='/upload_data/partner/<?=$P_UP_IMG?>'" onmouseout="this.src='/upload_data/partner/<?=$P_DOWN_IMG?>'" alt="SK broadband" /></div>
														<div class="data-body"><p class="para"><?=$P_PORTFOLIO_NM?></p></div>
													</div>
												</li>
												<!--
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_skb.png" alt="SK broadband" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_skc.png" alt="SK 주식회사" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_skt.png" alt="SK telecome" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_skn.png" alt="SK 네트웍스" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_posco.png" alt="posco 포스코건설" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_shb.png" alt="신한은행" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_shds.png" alt="신한DS" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_ebs.png" alt="EBS" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_ktv.png" alt="KTV 국민방송" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_seegene.png" alt="Seegene" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_daekyo.png" alt="DAEKYO" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_ex.png" alt="한국도로공사" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_hyundai.png" alt="Hyundai Commercial" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_kist.png" alt="KIST 한국과학기술연구원" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_visang.png" alt="visang" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_itx.png" alt="HYOSUNG ITX" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_kidp.png" alt="한국디자인진흥원" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_read.png" alt="독서동아리지원센터" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_opht.png" alt="비앤빛 강남밝은세상안과의원" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head"><img src="../../assets/images/about/logo_hyosung.png" alt="HYOSUNG" /></div>
														<div class="data-body"><p class="para">2019 SK telecome 사내컴 포탈 Our365 사이트 개편 및 운영</p></div>
													</div>
												</li>
												-->
												<?
														}
													}
												?>	
											</ul>
											<!-- //data-list -->
										</div>
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