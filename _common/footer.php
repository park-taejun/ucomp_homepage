<?
	$arr_rs_brochurefile = getBrochureFile($conn);
	$file_no = trim($arr_rs_brochurefile[0]["FILE_NO"]);
?>
<div class="page-foot" id="footer">
	<div class="section widget-toolbar">
		<div class="section-wrap">
			<div class="section-head">
				<h2 class="section-name">도구모음</h2>
			</div>
			<div class="section-body">
				<ul class="navi-list">
					<li class="navi-item goto-top"><a class="navi-text" href="#page"><svg width="24" height="24" viewBox="0 0 24 24" focusable="false" xmlns="http://www.w3.org/2000/svg"><title>맨위로</title><path d="M11.25 19.625V7.25L5.45 13.075L4.375 12L12 4.375L19.625 12L18.55 13.075L12.75 7.25V19.625H11.25Z"></path></svg></a></li>
				</ul>
			</div>
		</div>
	</div>

	<!-- info-board -->
	<div class="info-board module-c style-a type-a">
		<div class="board-wrap">
			<div class="board-head">
				<p class="board-caption">
					<svg width="174" height="24" viewBox=" 0 0 174 24" focusable="false" xmlns="http://www.w3.org/2000/svg">
						<title>유컴패니온</title>
						<path d="M8.58232 18.9749C10.2002 18.9749 11.0126 18.1563 11.0126 16.5122V2.21436H15.0642V16.5122C15.0642 18.4452 14.4687 19.9757 13.2742 21.1004C12.0797 22.2252 10.5169 22.7858 8.58232 22.7858C6.64775 22.7858 5.05396 22.2252 3.87325 21.1004C2.69254 19.9757 2.10046 18.4486 2.10046 16.5122V2.21436H6.15206V16.5122C6.15206 18.1528 6.961 18.9749 8.58232 18.9749Z"/>
						<path d="M20.93 6.34475C21.4567 6.34475 21.9007 6.53392 22.2656 6.90882C22.6271 7.28372 22.8095 7.74461 22.8095 8.2846C22.8095 8.8246 22.6305 9.25797 22.2725 9.6088C21.9145 9.95962 21.467 10.135 20.93 10.135C20.393 10.135 19.9386 9.95274 19.5668 9.58816C19.1951 9.22358 19.0126 8.77645 19.0126 8.24677C19.0126 7.71709 19.1951 7.28372 19.5668 6.90538C19.9352 6.53048 20.3792 6.34131 20.8921 6.34131H20.93V6.34475ZM20.9507 15.0397C21.4704 15.0397 21.9076 15.2254 22.2691 15.5935C22.6305 15.9615 22.8095 16.4017 22.8095 16.9073C22.8095 17.437 22.6271 17.891 22.2691 18.2659C21.9076 18.6408 21.467 18.83 20.9507 18.83C20.4068 18.83 19.9489 18.6477 19.5737 18.2831C19.202 17.9185 19.0161 17.4852 19.0161 16.9796C19.0161 16.474 19.202 15.9924 19.5737 15.6107C19.9455 15.2289 20.393 15.0363 20.9128 15.0363H20.9507V15.0397Z"/>
						<path d="M35.5442 22.1981C34.733 22.5887 33.9218 22.7858 33.1106 22.7858C31.4881 22.7858 30.0853 22.1629 28.9022 20.9173C27.719 19.6716 27.1239 17.0711 27.1239 13.1124C27.1239 8.81931 27.7155 5.9303 28.9022 4.44533C30.0853 2.96036 31.52 2.21436 33.2062 2.21436C33.8368 2.21436 34.6161 2.31992 35.5442 2.53106V4.99076C34.9278 4.74444 34.3504 4.62479 33.8084 4.62479C32.7457 4.62479 31.8388 5.15615 31.0843 6.21885C30.3298 7.28156 29.9543 9.3753 29.9543 12.5001C29.9543 14.3616 30.0358 15.8184 30.2022 16.8705C30.3652 17.9227 30.7478 18.7567 31.3394 19.3795C31.9345 20.0023 32.6819 20.312 33.5852 20.312C34.1166 20.312 34.7684 20.1431 35.5442 19.8088V22.1981Z"/>
						<path d="M43.1747 12.5423C43.1747 8.20702 43.6034 5.40247 44.4642 4.12511C45.3214 2.85127 46.7384 2.21436 48.708 2.21436C50.9645 2.21436 52.4629 2.92869 53.2033 4.35384C53.9472 5.77899 54.3191 8.34778 54.3191 12.0567C54.3191 15.3785 54.1527 17.6834 53.8197 18.9678C53.4867 20.2522 52.9128 21.1988 52.0981 21.804C51.2798 22.4093 50.171 22.7119 48.7717 22.7119C47.3725 22.7119 46.2212 22.4339 45.4525 21.8744C44.6838 21.3149 44.1099 20.4105 43.738 19.1543C43.3625 17.9016 43.1783 15.6952 43.1783 12.5388L43.1747 12.5423ZM51.4923 12.7006C51.4923 8.90376 51.2656 6.55666 50.8157 5.66287C50.3623 4.76907 49.6857 4.32217 48.7824 4.32217C47.7197 4.32217 46.9899 4.85704 46.5967 5.9303C46.2035 7.00356 46.0051 8.80875 46.0051 11.3529V12.4508C46.0051 15.287 46.1645 17.3561 46.4833 18.6581C46.8022 19.9601 47.5673 20.6111 48.7824 20.6111C49.5263 20.6111 50.1639 20.2451 50.6953 19.5132C51.2266 18.7813 51.4923 16.7579 51.4923 13.4396V12.7006Z"/>
						<path d="M75.9557 22.5326H73.4194L72.3815 9.75903H72.3177L69.629 22.5326H67.9428L65.3604 9.75903H65.2825L64.2198 22.5326H61.6834L63.6672 2.56641H66.0122L68.7895 16.561H68.8532L71.6588 2.56641H74.0358L75.9557 22.5326Z"/>
						<path d="M83.0716 2.56641H87.5209C90.4115 2.56641 91.8603 4.45253 91.8603 8.22477C91.8603 9.73086 91.6053 10.9132 91.0916 11.7753C90.578 12.6375 89.9403 13.1794 89.1752 13.4046C88.41 13.6298 87.2835 13.7529 85.7957 13.7741V22.5361H83.0681V2.56641H83.0716ZM85.7993 11.6627C86.9576 11.6627 87.783 11.434 88.2825 10.973C88.782 10.5156 89.0299 9.5901 89.0299 8.20366C89.0299 6.81722 88.8138 5.85656 88.3781 5.35688C87.9424 4.8572 87.0816 4.60736 85.7993 4.60736V11.6627Z"/>
						<path d="M107.734 22.5326H105.152L104.411 18.5527H100.16L99.4094 22.5326H96.827L101.152 2.56641H103.373L107.734 22.5326ZM104.054 16.547L102.282 7.03188H102.219L100.547 16.547H104.054Z"/>
						<path d="M124.746 22.5326H122.61L117.509 8.83004H117.445V22.5326H115.018V2.56641H117.179L122.295 16.2619L122.358 16.2795V2.56641H124.749V22.5326H124.746Z"/>
						<path d="M133.442 2.56641H136.169V22.5326H133.442V2.56641Z"/>
						<path d="M145.124 12.5423C145.124 8.20702 145.552 5.40247 146.413 4.12511C147.271 2.85127 148.687 2.21436 150.657 2.21436C152.914 2.21436 154.412 2.92869 155.152 4.35384C155.896 5.77899 156.268 8.34778 156.268 12.0567C156.268 15.3785 156.102 17.6834 155.769 18.9678C155.436 20.2522 154.862 21.1988 154.047 21.804C153.229 22.4093 152.12 22.7119 150.721 22.7119C149.322 22.7119 148.17 22.4339 147.402 21.8744C146.633 21.3149 146.059 20.4105 145.687 19.1543C145.312 17.9016 145.127 15.6952 145.127 12.5388L145.124 12.5423ZM153.441 12.7006C153.441 8.90376 153.215 6.55666 152.765 5.66287C152.311 4.76907 151.635 4.32217 150.731 4.32217C149.669 4.32217 148.939 4.85704 148.546 5.9303C148.153 7.00356 147.954 8.80875 147.954 11.3529V12.4508C147.954 15.287 148.114 17.3561 148.432 18.6581C148.751 19.9601 149.516 20.6111 150.731 20.6111C151.475 20.6111 152.113 20.2451 152.644 19.5132C153.176 18.7813 153.441 16.7579 153.441 13.4396V12.7006Z"/>
						<path d="M173.896 22.5326H171.76L166.659 8.83004H166.595V22.5326H164.168V2.56641H166.329L171.444 16.2619L171.508 16.2795V2.56641H173.899V22.5326H173.896Z"/>
					</svg>
				</p>
				<p class="board-subject"><span class="board-name"><span class="wbr">지금 유컴패니온과</span> <span class="wbr">함께 시작하세요.</span></span></p>
			</div>
			<div class="board-util">
				<!-- button-display -->
				<div class="button-display module-a style-a type-a">
					<span class="button-area">
						<a class="btn module-b style-c type-line normal-04 large-2x symbol-rtl-small-arrow-right" href="/request/USR.006"><span class="btn-text">Request</span></a>
						<a class="btn module-b style-c type-line normal-04 large-2x symbol-rtl-download" href="/_common/new_download_file.php?menu=brochure&file_no=<?=$file_no?>"><span class="btn-text">Download IR</span></a>
					</span>
				</div>
				<!-- //button-display -->
			</div>
		</div>
		<!-- <div class="board-side">
			asdfadsfsaf
		</div> -->
	</div>
	<!-- //info-board -->

	<div class="section reject-email">
		<div class="section-wrap">
			<div class="section-head">
				<h2 class="section-name">이메일거부</h2>
			</div>
			<div class="section-body">
				<a class="para" href="#rejectEmail" onclick="infoPopup.active('rejectEmail');" >이메일무단수집거부</a>
			</div>
		</div>
	</div>

	<!-- section -->
	<div class="section company-info">
		<div class="section-wrap">
			<div class="section-head">
				<h2 class="section-name">기업정보</h2>
			</div>
			<div class="section-body">
				<!-- data-display -->
				<div class="data-display">
					<!-- data-list -->
					<ul class="data-list">
						<li class="data-item business-number">
							<div class="data-wrap">
								<div class="data-head"><span class="data-name">사업자번호</span></div>	  
								<div class="data-body">211-88-79196</div>
							</div>
						</li>
						<li class="data-item ceo">
							<div class="data-wrap">
								<div class="data-head"><span class="data-name">대표자</span></div>	  
								<div class="data-body">한수진</div>
							</div>
						</li>
						<li class="data-item contact">
							<div class="data-wrap">
								<div class="data-head"><span class="data-name">CONTACT</span></div>	  
								<div class="data-body">
									<ul class="data-list">
										<li class="data-item"><abbr title="Telephone">T</abbr>070-5050-5888</li>
										<li class="data-item"><abbr title="Facsimile">F</abbr>070-7545-1710</li>
										<li class="data-item"><abbr title="Email">E</abbr>ucomp_contact@ucomp.co.kr</li>
									</ul>
								</div>
							</div>
						</li>
						<li class="data-item address">
							<div class="data-wrap">
								<div class="data-head"><span class="data-name">ADDRESS</span></div>	  
								<div class="data-body">서울특별시 강남구 삼성로 119길 37-9 유컴패니온</div>
							</div>
						</li>
					</ul>
					<!-- //data-list -->
				</div>
				<!-- //data-display -->
			</div>
		</div>
	</div>
	<!-- //section -->

	<p class="copyright">COPYRIGHT &copy; U : COMPANION. ALL RIGHT RESERVED</p>
	
</div>
<!-- info-popup -->
<div class="info-popup module-a style-a type-a" data-bui-toggle="infoPopup" id="rejectEmail">
	<div class="popup-page-body">
		<!-- popup-local -->
		<div class="popup-local">
			<!-- popup-local-head -->
			<div class="popup-local-head">
				<h2 class="popup-local-subject">
					<svg width="28" height="28" viewBox=" 0 0 28 28" focusable="false" xmlns="http://www.w3.org/2000/svg">
						<title>유컴패니온</title>
						<path d="M12.3274 18.6828C12.3274 20.5984 11.3883 21.5543 9.51139 21.5543H9.51014C7.63323 21.5543 6.69415 20.5971 6.69415 18.6828V2H2V18.6828C2 20.9398 2.68388 22.7226 4.0529 24.0351C5.42067 25.3463 7.26866 26.0013 9.51014 26.0013C11.7516 26.0013 13.5644 25.3463 14.9473 24.0351C16.3288 22.7239 17.0215 20.9398 17.0215 18.6828V2H12.3274V18.6828ZM25.3689 6.35847C24.9478 5.91972 24.4311 5.69971 23.8226 5.69971V5.70097H23.7799C23.184 5.70097 22.6724 5.92098 22.2449 6.35974C21.8162 6.79849 21.6025 7.30552 21.6025 7.92383C21.6025 8.54213 21.8162 9.06307 22.2449 9.48792C22.6724 9.9115 23.1991 10.1239 23.8226 10.1239C24.4462 10.1239 24.9654 9.91908 25.379 9.50941C25.7926 9.09974 26 8.59776 26 7.96555C26 7.33334 25.7901 6.79723 25.3689 6.35847ZM25.3739 16.4916C24.9553 16.0591 24.4462 15.8442 23.8453 15.8442H23.8013C23.1991 15.8442 22.6824 16.0692 22.25 16.5143C21.8162 16.9594 21.6013 17.5183 21.6013 18.11C21.6013 18.7018 21.8175 19.2075 22.25 19.6324C22.6824 20.056 23.2154 20.2684 23.8453 20.2684C24.4462 20.2684 24.9553 20.0496 25.3739 19.6109C25.7913 19.1721 26.0013 18.6423 26.0013 18.024C26.0013 17.4335 25.7913 16.9227 25.3739 16.4916Z"/>
					</svg>
				</h2>
			</div>
			<!-- //popup-local-head -->
			<!-- popup-local-body -->
			<div class="popup-local-body">
				<div class="popup-content">
					<!-- popup-content-body -->
					<div class="popup-content-body">
						<div class="data-display">
							<div class="data-list">
								<div class="data-item">
									<div class="data-wrap">
										<div class="data-head">
											<p class="data-subject"><span class="data-name">이메일무단수집거부</span></p>
										</div>
										<div class="data-body">
											본사이트에 게시되어 있는 이메일주소가 "이메일 주소 수집프로그램"이나 그 밖의 기술적인 장치를 사용하여 수집되는 것을 거부하며, 위반 시 "정보통신망 이용촉진 및 정보보호등에 관한 법률"에 의해 형사처벌됨을 유념하시기 바랍니다.
										</div>
										<p class="data-date">2022.12</p>
									</div>
								</div>
							</div>
						</div>
						<!-- button-display -->
						<div class="button-display module-a style-c type-c">
							<span class="button-area">
								<button class="btn module-b style-c type-fill accent-02 large-3x flex" type="button" onclick="infoPopup.inactive('rejectEmail');"><span class="btn-text">확인</span></button>
							</span>
						</div>
						<!-- //button-display -->
					</div>
					<!-- //popup-content-body -->
				</div>
			</div>
			<!-- //popup-local-body -->
		</div>
		<!-- //popup-local -->
	</div>
</div>
<!-- //info-popup -->