<?

	$_arr_cate01 = getBcodeCate($conn, "B_1_1");
	$arr_cate01	= explode(";", $_arr_cate01);
	$_arr_cate02 = getBcodeCate($conn, "B_1_2");
	$arr_cate02	= explode(";", $_arr_cate02);

?>
	<div class="toparea">
		<div class="innerbox">
			<h1><a href="/">LG하우시스 인스토리</a></h1>
			<div class="topmenu">
				<button type="button" class="btn-category only-mobile" onclick="mobileLnbOpen()">모바일 메뉴</button>
				<h1 class="only-mobile"><a href="/">LG하우시스 인스토리</a></h1>
				<ul>
					<li class="menu_01">
						<!-- <a href="webzine_list.php?b_code=B_1_1&cate=<?=$arr_cate01[0]?>">소식공간</a> -->
						<a href="webzine_list.php">소식공간</a>
						<div class="submenu">
							<ul>
								<li>
									<a href="webzine_list.php">전체 보기</a><!-- 2018.06.25 전체 보기 추가 -->
								</li>
								<li>
									<!-- <a href="webzine_list.php?b_code=B_1_1&cate=<?=$arr_cate01[0]?>">Company</a> -->
									<a href="webzine_list.php?b_code=B_1_1">Company & Industry</a>
									<ul class="only-mobile">
										<?
											if (sizeof($arr_cate01) > 0 ) {
												for ($i = 0; $i < sizeof($arr_cate01) ; $i++) {
													$str_cate01 = str_replace("^"," & ", $arr_cate01[$i]);
										?>
										<li><a href="webzine_list.php?b_code=B_1_1&cate=<?=$arr_cate01[$i]?>"><?=$str_cate01?></a></li>
										<?
												}
											}
										?>
									</ul>
								</li>
								<li>
									<!-- <a href="webzine_list.php?b_code=B_1_2&cate=<?=$arr_cate02[0]?>">Culture & Trend</a> -->
									<a href="webzine_list.php?b_code=B_1_2">Life & Culture</a>
									<ul class="only-mobile">
										<?
											if (sizeof($arr_cate02) > 0 ) {
												for ($i = 0; $i < sizeof($arr_cate02) ; $i++) {
													$str_cate02 = str_replace("^"," & ",$arr_cate02[$i]);
										?>
										<li><a href="webzine_list.php?b_code=B_1_2&cate=<?=$arr_cate02[$i]?>"><?=$str_cate02?></a></li>
										<?
												}
											}
										?>
									</ul>
								</li>
							</ul>
						</div>
					</li>
					<li class="menu_02">
						<a href="/photo.php">참여공간</a>
						<div class="submenu">
							<ul>
								<li><a href="/photo.php">Photo Contest</a></li>
								<li><a href="/remindpicture.php">2019 트렌드 이벤트<!--네잎클로버 찾기 이벤트--></a></li><!-- 2018.08.20 이벤트 숨김 처리-->
								<li><a href="/reader.php">독자의견 등록</a></li>
							</ul>
						</div>
					</li>
					<li class="menu_03">
						<a href="/eventlist.php">응모&당첨자 확인</a>
						<div class="submenu">
							<ul>
								<li><a href="/eventlist.php">진행중인 이벤트</a></li>
							</ul>
						</div>
					</li>
					<li class="menu_04">
						<a href="/app.php">자료공간</a>
						<div class="submenu">
							<ul>
								<li><a href="/app.php">APP 다운로드</a></li>
								<li><a href="/magazine.php">사보다운로드</a></li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>