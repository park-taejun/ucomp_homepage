<?
	$arr_rs_brochurefile = getBrochureFile($conn);
	$file_no = trim($arr_rs_brochurefile[0]["FILE_NO"]);
?>
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
				<li class="gnb-item current" title="선택 됨">
					<a class="gnb-name" href="/story/USR.005">Story</a>
					<ul class="lnb-list">
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