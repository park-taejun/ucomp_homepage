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
	require "../_classes/biz/team/team.php";	
	
	$p_team_no					= $_POST['team_no']!=''?$_POST['team_no']:$_GET['team_no'];
	
	$arr_rs_team 		= listTeamAll($conn); 
	$arr_rs_top 		= listTeamTop($conn);
	
	$P_TEAM_NO_TOP		= trim($arr_rs_top[0]["TEAM_NO"]);
	$P_TEAM_NM_TOP		= trim($arr_rs_top[0]["TEAM_NM"]);
	$P_TEAM_IMG_TOP		= trim($arr_rs_top[0]["TEAM_IMG"]);
	$P_TEAM_CONTENTS_TOP		= trim($arr_rs_top[0]["TEAM_CONTENTS"]);	 
	$P_TEAM_CONTENTS_TOP_IMG = $P_TEAM_IMG_TOP.",".$P_TEAM_CONTENTS_TOP;
	
	echo $P_TEAM_CONTENTS_TOP_IMG;
	 
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
	<script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>
<body>
<form id="frm" name="frm" method="post" action="javascript:js_search();"  >
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
						<li class="gnb-item">
							<a class="gnb-name" href="/business/USR.003">Business</a> 
							<ul class="lnb-list">
								<li class="lnb-item"><a class="lnb-name" href="/business/USR.003_1">Portfolio</a></li>
							</ul>
						</li>
						<li class="gnb-item current" title="선택 됨">
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
		<div class="page-body page-people page-intro">
			<!-- local -->
			<div id="local">
				<!-- local-head -->
				<div class="local-head">
					<h2 class="local-subject" id="localSubject"><span class="local-name">PEOPLE</span></h2>
					<p class="local-subtitle"><span class="wbr">People of</span> <span class="wbr">U:COMPANION</span></p> 
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
									<li class="navi-item swiper-slide current" title="현재 선택된 항목"><a class="navi-text" href="#companyCulture">Company Culture</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#ourTeam">Our Team</a></li>
									<li class="navi-item swiper-slide"><a class="navi-text" href="#jobPosting">Job Posting</a></li>
								</ul>
							</div>
						</div>
						<!-- //content-navi -->
						<!-- content-body -->
						<div class="content-body">
							<!-- section -->
							<div class="section module-a style-a type-a company-culture" id="companyCulture" data-bui-animation="type-1">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Company Culture</span></h3>
										<p class="section-summary">주도 + 자율 + 존중 = <strong class="em normal-01">행복</strong></p>
									</div>
									<div class="section-body">
										<!-- data-display -->
										<div class="data-display company-culture">
											<ul class="data-list">
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-subject"><span class="data-name">유연한 문화</span></p>
															<p class="data-summary"><span class="wbr">주도적인 업무 수행을 통해</span> <span class="wbr">만족스러운 성취감을 이끌어냅니다.</span></p>
														</div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-subject"><span class="data-name">자율적인 업무 환경</span></p>
															<p class="data-summary"><span class="wbr">사용자의 편의성과 즐거움을 극대화 할 수 있는</span> <span class="wbr">창의적인 방법을 고민하고 연구합니다.</span></p>
														</div>
													</div>
												</li>
												<li class="data-item">
													<div class="data-wrap">
														<div class="data-head">
															<p class="data-subject"><span class="data-name">존중하는 태도</span></p>
															<p class="data-summary"><span class="wbr">사람과 사람 사이의 예의를 중시하며</span> <span class="wbr">동료를 존중합니다.</span></p>
														</div>
													</div>
												</li>
											</ul>
										</div>
										<!-- //data-display -->

										<div class="subsection module-b style-b type-a">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name"><strong class="em normal-01">WORK</strong> WITH LIFE</span></h4>
												</div>
												<div class="subsection-body">
													<!-- data-display -->
													<div class="data-display module-a type-c style-c">
														<ul class="data-list">
															<li class="data-item flexible-work">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">유연근무제</span></div>
																	<div class="data-body"><span class="wbr">원하는 시간에 효율적으로!</span> <span class="wbr">시차 출퇴근제 실행</span></div>
																</div>
															</li>
															<li class="data-item smart-day">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">스마트데이</span></div>
																	<div class="data-body"><span class="wbr">원하는 장소에서 일하는</span> <span class="wbr">스마트데이 도입</span></div>
																</div>
															</li>
															<li class="data-item refresh-vacation">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">리프레쉬 휴가</span></div>
																	<div class="data-body"><span class="wbr">장기근속자를 위한</span> <span class="wbr">1개월 유급 휴가 제공</span></div>
																</div>
															</li>
															<li class="data-item education-support">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">교육 지원</span></div>
																	<div class="data-body"><span class="wbr">업무 관련 교육비 및</span> <span class="wbr">팀 내 도서 구입비 지원</span></div>
																</div>
															</li>
															<li class="data-item childcare-support">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">육아 지원</span></div>
																	<div class="data-body"><span class="wbr">육아 휴직 및 육아</span> <span class="wbr">단축 근무 제도</span></div>
																</div>
															</li>
															<li class="data-item condolences-support">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">경조사 지원</span></div>
																	<div class="data-body"><span class="wbr">경조사비 및</span> <span class="wbr">경조휴가 지원</span></div>
																</div>
															</li>
															<li class="data-item reward-system">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">포상 제도</span></div>
																	<div class="data-body"><span class="wbr">우수사원 및 장기근속자</span> <span class="wbr">포상 제도</span></div>
																</div>
															</li>
															<li class="data-item happy-lunch">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">해피 런치</span></div>
																	<div class="data-body"><span class="wbr">금요일 점심시간은</span> <span class="wbr">1시간 45분 제공</span></div>
																</div>
															</li>
															<li class="data-item happy-birthday">
																<div class="data-wrap">
																	<div class="data-head"><span class="data-name">생일 축하</span></div>
																	<div class="data-body"><span class="wbr">생일 당사자</span> <span class="wbr">케이크 발송</span></div>
																</div>
															</li>
														</ul>
													</div>
													<!-- //data-display -->
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>		
							<!-- //section -->
							
							<!-- section -->
							<div class="section module-a style-a type-a our-team" id="ourTeam" data-bui-animation="type-1">

								<div class="section-wrap" id="id_team_img" style="--background-image: url(../../upload_data/team/<?=$P_TEAM_IMG_TOP?>);">
								
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Our Team</span></h3>
										<p class="section-summary">유컴패니온의 <strong class="em normal-01">전문가들</strong></p>
									</div>
									<div class="section-body">
										<!-- dropdown -->
										<div class="dropdown module-a style-a">
											<div class="dropdown-wrap">
												<div class="dropdown-head">
													<p id="dropdown-subject" class="dropdown-subject" tabindex="0" data-bui-tab-selected="<?=$P_TEAM_NM_TOP?>" onclick="js_team();"><span class="dropdown-name"><?=$P_TEAM_NM_TOP?></span></p>
												</div>
												<div class="dropdown-body" style="display:none">
													<ul class="navi-list" id="navi_list">
														<? 										
															if (sizeof($arr_rs_team) > 0) {
																for ($j = 0 ; $j < sizeof($arr_rs_team); $j++) { 
																	$P_TEAM_NO				= trim($arr_rs_team[$j]["TEAM_NO"]);
																	$P_TEAM_NM				= trim($arr_rs_team[$j]["TEAM_NM"]);
																	$P_TEAM_IMG				= trim($arr_rs_team[$j]["TEAM_IMG"]);
																	$P_TEAM_CONTENTS		= trim($arr_rs_team[$j]["TEAM_CONTENTS"]);
																	$P_TEAM_CONTENTS_IMG = $P_TEAM_IMG."|".$P_TEAM_CONTENTS;
														?>
														<li class="navi-item" value="<?=$P_TEAM_NO?>"><a class="navi-text"  href="javascript:void(0)" onClick="js_select_team('<?=$P_TEAM_NO?>');"><?=$P_TEAM_NM?></a></li>
														<?
																}
															}
														?>
													</ul>
												</div>
											</div>
										</div>
										<!-- //dropdown -->										
										
										<input type="hidden" name="team_nm" value="<?=$P_TEAM_NM_TOP?>">
										<input type="hidden" name="team_contents" value="<?=$P_TEAM_CONTENTS_TOP?>">
										<input type="hidden" name="team_img" value="<?=$P_TEAM_IMG_TOP?>">
										 
										<p class="para"><?=$P_TEAM_CONTENTS_TOP?></p>
									</div>
								</div>


							</div>
							<!-- //section -->

							<!-- section -->
							<div class="section module-a style-b type-a job-posting" id="jobPosting">
								<div class="section-wrap">
									<div class="section-head">
										<h3 class="section-subject"><span class="section-name">Job Posting</span></h3>
									</div>
									<div class="section-body">
										<div class="subsection module-a style-b type-a ideal-talent" data-bui-animation="type-1">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<p class="subsection-caption">JOB POSTING</p>
													<h4 class="subsection-subject"><span class="subsection-name">인재상</span></h4>
												</div>
												<div class="subsection-body">
													<!-- data-display -->
													<div class="data-display module-b style-c">
														<ol class="data-list">
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><p class="data-subject"><span class="data-name">열정적인 인재</span></p></div>
																	<div class="data-body">열정적으로 일하고 최고의 성과를 추구합니다.</div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><p class="data-subject"><span class="data-name">적극적인 인재</span></p></div>
																	<div class="data-body">신뢰를 바탕으로 관리 당하지 않고 스스로 관리합니다.</div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><p class="data-subject"><span class="data-name">예의바른 인재</span></p></div>
																	<div class="data-body">사람 사이에 무엇이 중요한지 알고 있습니다.</div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><p class="data-subject"><span class="data-name">생각하는 인재</span></p></div>
																	<div class="data-body">늘 고민하고, 학습하며, 발전합니다.</div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><p class="data-subject"><span class="data-name">이타적인 인재</span></p></div>
																	<div class="data-body">모든 기회에 감사할 줄 압니다.</div>
																</div>
															</li>
															<li class="data-item">
																<div class="data-wrap">
																	<div class="data-head"><p class="data-subject"><span class="data-name">아름다운 인재</span></p></div>
																	<div class="data-body">자신에게 이로우며, 아름답습니다.</div>
																</div>
															</li>
														</ol>
													</div>
													<!-- //data-display -->
												</div>
											</div>
										</div>
										<div class="subsection module-a style-b type-a job-posting" data-bui-animation="type-1">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">채용 공고</span></h4>
												</div>
												<div class="subsection-body">
													<div class="info-board module-b style-b">
														<div class="board-list">
															<div class="board-item">
																<div class="board-wrap">
																	<div class="board-head">
																		<img src="../../assets/images/people/logo_saramin.png" alt="saramin" />
																	</div>
																	<div class="board-util">
																		<!-- button-display -->
																		<div class="button-display module-a style-a type-a">
																			<span class="button-area">
																				<a class="btn module-b style-c type-line normal-01 large-2x symbol-rtl-small-arrow-right" href="https://www.saramin.co.kr/zf_user/company-info/view-inner-recruit?csn=WTlBL2VuQ3d0ZlZSSmorU0dlaXdPdz09" onclick="window.open(this.href, '_blank'); return false;" target="_blank"><span class="btn-text">채용 공고 바로가기</span></a>
																			</span>
																		</div>
																		<!-- //button-display -->
																	</div>
																</div>
															</div>
															<div class="board-item">
																<div class="board-wrap">
																	<div class="board-head">
																		<img src="../../assets/images/people/logo_jobkorea.png" alt="jobkorea" />
																	</div>
																	<div class="board-util">
																		<!-- button-display -->
																		<div class="button-display module-a style-a type-a">
																			<span class="button-area">
																				<a class="btn module-b style-c type-line normal-01 large-2x symbol-rtl-small-arrow-right" href="https://www.jobkorea.co.kr/Recruit/Co_Read/Recruit/C/ucomp8741" onclick="window.open(this.href, '_blank'); return false;" target="_blank"><span class="btn-text">채용 공고 바로가기</span></a>
																			</span>
																		</div>
																		<!-- //button-display -->
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="subsection module-a style-b type-a recruitment-procedure" data-bui-animation="type-1">
											<div class="subsection-wrap">
												<div class="subsection-head">
													<h4 class="subsection-subject"><span class="subsection-name">채용 절차</span></h4>
												</div>
												<div class="subsection-body">
													<div class="order-display recruitment-process">
														<ol class="order-list">
															<li class="order-item">
																<div class="order-wrap">
																	<div class="data-head"><span class="data-name">서류 전형</span></div>
																	<div class="data-body"><span class="wbr">채용 공고를 통한</span> <span class="wbr">서류 접수</span></div>
																</div>
															</li>
															<li class="order-item">
																<div class="order-wrap">
																	<div class="data-head"><span class="data-name">실무자 면접</span></div>
																	<div class="data-body"><span class="wbr">직무에 맞는</span> <span class="wbr">자격과 역량 검토</span></div>
																</div>
															</li>
															<li class="order-item">
																<div class="order-wrap">
																	<div class="data-head"><span class="data-name">임원 면접</span></div>
																	<div class="data-body"><span class="wbr">열정, 가치관, 인성 및</span> <span class="wbr">동기 적합성 판단</span></div>
																</div>
															</li>
															<li class="order-item">
																<div class="order-wrap">
																	<div class="data-head"><span class="data-name">최종 합격</span></div>
																	<div class="data-body"><span class="wbr">최종 합격 통보</span></div>
																</div>
															</li>
														</ol>
														<ul class="data-list module-a type-c normal-03 small-2x">
															<li class="data-item">서류 합격자에 한해 순차적으로 면접을 진행합니다.</li>
															<li class="data-item">상기 채용 절차는 모집 분야별로 상이할 수 있습니다.</li>
														</ul>
													</div>
												</div>
											</div>
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
</form>
</body>
</html>
<script type="text/javascript">


	function js_team(){
		$('.dropdown-body').css('display','block');
	}
	
	function js_select_team(team_no) {
		
		var request = $.ajax({
			url:"ajax-team.php",
			type:"POST",
			data:{team_no:team_no},
			dataType:"html"
		});
			
		request.done(function(data) {
			$("#ourTeam").html(data);
		});
	}
</script>
 
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>