			<div class="pagenavi only-mobile">
				<ul>
					<li class="depth1">
						<? if ($depth01 == "1") {?>
						<a href="/webzine_list.php" class="holder">소식공간</a>
						<? } ?>
						<? if ($depth01 == "2") {?>
						<a href="/remindpicture.php" class="holder">참여공간</a>
						<? } ?>
						<? if ($depth01 == "3") {?>
						<a href="/eventlist.php" class="holder">응모&당첨자 확인</a>
						<? } ?>
						<? if ($depth01 == "4") {?>
						<a href="/app.php" class="holder">자료공간</a>
						<? } ?>
						<ul>
							<li><a href="webzine_list.php">소식공간</a></li>
							<li><a href="/remindpicture.php">참여공간</a></li>
							<li><a href="/eventlist.php">응모&당첨자 확인</a></li>
							<li><a href="/app.php">자료공간</a></li>
						</ul>
					</li>

					<? if ($depth01 == "1") {?>
					<li  class="depth2">
						<? if ($depth02 == "1") {?>
						<a href="#" class="holder">전체 보기</a>
						<? } ?>
						<? if ($depth02 == "2") {?>
						<a href="#" class="holder">Company & Industry</a>
						<? } ?>
						<? if ($depth02 == "3") {?>
						<a href="#" class="holder">Life & Culture</a>
						<? } ?>
						<ul>
							<li><a href="/webzine_list.php">전체 보기</a></li>
							<li><a href="/webzine_list.php?b_code=B_1_1">Company & Industry</a></li>
							<li><a href="/webzine_list.php?b_code=B_1_2">Life & Culture</a></li>
						</ul>
					</li>
					<? } ?>

					<? if ($depth01 == "2") {?>
					<li  class="depth2">
						<? if ($depth02 == "1") {?>
						<a href="#" class="holder">Photo Time</a>
						<? } ?>
						<? if ($depth02 == "2") {?>
						<a href="#" class="holder">2019 트렌드 이벤트</a>
						<? } ?>
						<? if ($depth02 == "3") {?>
						<a href="#" class="holder">독자의견 등록</a>
						<? } ?>
						<ul>
							<li><a href="/photo.php">Photo Time</a></li>
							<li><a href="/remindpicture.php">2019 트렌드 이벤트</a></li>
							<li><a href="/reader.php">독자의견 등록</a></li>
						</ul>
					</li>
					<? } ?>

					<? if ($depth01 == "3") {?>
					<li  class="depth2">
						<? if ($depth02 == "1") {?>
						<a href="#" class="holder">진행중인 이벤트</a>
						<? } ?>
						<ul>
							<li><a href="/eventlist.php">진행중인 이벤트</a></li>
						</ul>
					</li>
					<? } ?>


					<? if ($depth01 == "4") {?>
					<li  class="depth2">
						<? if ($depth02 == "1") {?>
						<a href="#" class="holder">APP 다운로드</a>
						<? } ?>
						<? if ($depth02 == "2") {?>
						<a href="#" class="holder">사보다운로드</a>
						<? } ?>
						<ul>
							<li><a href="/app.php">APP 다운로드</a></li>
							<li><a href="/magazine.php">사보다운로드</a></li>
						</ul>
					</li>
					<? } ?>

				</ul>
			</div>

			<? if (($depth01 == "1") && ($depth02 == "1")) {?>

			<div class="tabbox">
				<a href="#" class="holder only-mobile">전체 보기</a><!-- 2018.06.25 전체 보기 추가 -->
				<ul>
					<li class="tab_0 on"><a href="webzine_list.php">전체 보기</a></li><!-- 2018.06.25 전체 보기 추가 -->
					<li class="tab_1"><a href="webzine_list.php?b_code=B_1_1">Company & Industry</a></li>
					<li class="tab_2"><a href="webzine_list.php?b_code=B_1_2">Life & Culture</a></li>
				</ul>
			</div>

			<? } ?>

			<? if (($depth01 == "1") && ($depth02 == "2")) {?>
			<div class="tabbox">
				<?
					if ($cate == "") {
						$str_cate = "전체 보기";
					} else {
						$str_cate = str_replace("^"," & ", $cate);
					}
				?>
				<a href="#" class="holder only-mobile"><?=$str_cate?></a>
				<ul>
					<li class="tab_0 <? if ($cate == "") { ?>on<? } ?>"><a href="webzine_list.php?b_code=B_1_1">전체 보기</a></li>
				<?
					if (sizeof($arr_cate01) > 0 ) {
						for ($i = 0; $i < sizeof($arr_cate01) ; $i++) {
							$str_cate01 = str_replace("^"," & ", $arr_cate01[$i]);
				?>
					<li class="tab_1 <? if ($cate == $arr_cate01[$i]) { ?>on<? } ?>"><a href="webzine_list.php?b_code=B_1_1&cate=<?=$arr_cate01[$i]?>"><?=$str_cate01?></a></li>
				<?
						}
					}
				?>
				</ul>
			</div>
			<? } ?>

			<? if (($depth01 == "1") && ($depth02 == "3")) {?>
			<div class="tabbox">
				<?
					if ($cate == "") {
						$str_cate = "전체 보기";
					} else {
						$str_cate = str_replace("^"," & ", $cate);
					}
				?>
				<a href="#" class="holder only-mobile"><?=$str_cate?></a>
				<ul>
					<li class="tab_0 <? if ($cate == "") { ?>on<? } ?>"><a href="webzine_list.php?b_code=B_1_2">전체 보기</a></li>
				<?
					if (sizeof($arr_cate02) > 0 ) {
						for ($i = 0; $i < sizeof($arr_cate02) ; $i++) {
							$str_cate02 = str_replace("^"," & ",$arr_cate02[$i]);
				?>
					<li class="tab_1 <? if ($cate == $arr_cate02[$i]) { ?>on<? } ?>"><a href="webzine_list.php?b_code=B_1_2&cate=<?=$arr_cate02[$i]?>"><?=$str_cate02?></a></li>
				<?
						}
					}
				?>
				</ul>
			</div>
			<? } ?>