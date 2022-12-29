	<?
		//
		$arr_rs_brochurefile = getBrochureFile($conn);
		$file_no = trim($arr_rs_brochurefile[0]["FILE_NO"]);
	?>
	<div class="header">
		<div class="toparea">
			<div class="innerbox">
				<h1>
					<a href="/">
						<svg version="1.1" id="UcompLogo" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 122.02 121.32" height="35" width="36" style="enable-background:new 0 0 122.02 121.32;" xml:space="preserve">
						<g><path class="st0" d="M38.19,98.84c9.54,0,14.32-4.84,14.32-14.52V0h23.87v84.33c0,11.41-3.52,20.43-10.54,27.05 c-7.03,6.63-16.25,9.94-27.64,9.94c-11.54,0-20.79-3.31-27.75-9.94C3.48,104.75,0,95.74,0,84.33V0h23.87v84.33 C23.87,94.01,28.64,98.84,38.19,98.84z"/><path class="st0" d="M110.95,18.7c3.1,0,5.72,1.12,7.86,3.33c2.14,2.22,3.21,4.93,3.21,8.12c0,3.13-1.05,5.73-3.16,7.8	c-2.1,2.07-4.74,3.11-7.92,3.11c-3.17,0-5.85-1.07-8.02-3.22c-2.18-2.14-3.27-4.78-3.27-7.91c0-3.05,1.09-5.69,3.27-7.9	c2.18-2.22,4.78-3.33,7.81-3.33H110.95z M111.07,69.98c3.05,0,5.64,1.09,7.77,3.27c2.12,2.18,3.19,4.76,3.19,7.74 c0,3.13-1.07,5.8-3.19,8.02c-2.13,2.22-4.72,3.33-7.77,3.33c-3.21,0-5.91-1.07-8.11-3.22c-2.2-2.14-3.3-4.71-3.3-7.69 c0-3.12,1.1-5.81,3.3-8.07c2.2-2.25,4.83-3.39,7.89-3.39H111.07z"/></g>
						</svg>
					</a>
				</h1>
				<ul>
					<li <? if ($depth_01 == "1") { ?>class="on"<? } ?>><a href="about">ABOUT</a></li>
					<li <? if ($depth_01 == "2") { ?>class="on"<? } ?>><a href="list_thumb">PROJECT</a></li>
					<li <? if ($depth_01 == "3") { ?>class="on"<? } ?>><a href="request">REQUEST</a></li>
					<li <? if ($depth_01 == "4") { ?>class="on"<? } ?>><a href="contact">CONTACT</a></li>
					<li class="brochure"><a href="/_common/new_download_file.php?menu=brochure&file_no=<?=$file_no?>">COMPANY BROCHURE</a></li>
				</ul>
			</div>
			<span class="btn-category btn-allmenu"><button type="button" class="btn-menu" title="모바일 전체메뉴 열기" onclick="allmenuToggle()"><i></i><i></i><i></i></button></span>
		</div>

		<div class="allmenu" data-animated="data-animated">
			<div class="allmenu_box">
				<div class="innerbox">
					<ul>
						<li <? if ($depth_01 == "1") { ?>class="on"<? } ?>><a href="about">ABOUT</a></li>
						<li <? if ($depth_01 == "2") { ?>class="on"<? } ?>><a href="list_thumb">PROJECT EXPERIENCE</a></li>
						<li <? if ($depth_01 == "3") { ?>class="on"<? } ?>><a href="request">PROJECT REQUEST</a></li>
						<li <? if ($depth_01 == "4") { ?>class="on"<? } ?>><a href="contact">CONTACT</a></li>
					</ul>
					<span><a href="/_common/new_download_file.php?menu=brochure&file_no=<?=$file_no?>">COMPANY BROCHURE</a></span>
					<strong><a href="/"><strong>U</strong>:COMPANION</a></strong>
					<div class="address">
						서울특별시 강남구 삼성동 26-24<br>유컴패니온<br>
						<a href="mailto:ucomp_contact@ucomp.co.kr">ucomp_contact@ucomp.co.kr</a><br>
						070.5030.5830 ㅣ 070.7545.1710
					</div>
				</div>
			</div>
		</div>
	</div>
