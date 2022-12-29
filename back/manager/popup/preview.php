<?session_start();?>
<?
# =============================================================================
# File Name    : index.php
# Modlue       :
# Writer       : Park Chan Ho
# Create Date  : 2013.06.11
# Modify Date  :
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/page/page.php";
	require "../../_classes/biz/board/board.php";

#====================================================================
# common_header Check Session
#====================================================================
	$rs_page_lang = "KOR";
	require "../../_common/common_front_header.php";

	$arr_rs_board = selectMainBoard($conn, "1");

	$B_CODE_01 	= trim($arr_rs_board[0]["B_CODE_01"]);
	$B_CODE_02 	= trim($arr_rs_board[0]["B_CODE_02"]);
	$B_CODE_03 	= trim($arr_rs_board[0]["B_CODE_03"]);
	$B_CODE_04 	= trim($arr_rs_board[0]["B_CODE_04"]);
	$B_CODE_05 	= trim($arr_rs_board[0]["B_CODE_05"]);
	$B_CODE_06 	= trim($arr_rs_board[0]["B_CODE_06"]);

	$arr_main_board = array($B_CODE_01,$B_CODE_02,$B_CODE_03,$B_CODE_04,$B_CODE_05,$B_CODE_06);

?>
<section id="container">
	<section id="containerInner">
		<div class="mainWrap">
			<div class="wrap">

				<!-- layout_pop -->
				<div style="position:relative;width:1000px;margin:0 auto;">
				<?
					$pop_no					= $_POST['pop_no']!=''?$_POST['pop_no']:$_GET['pop_no'];


					$arr_rs = selectPopup($conn, $pop_no);

					$rs_pop_no					= trim($arr_rs[0]["POP_NO"]);
					$rs_size_w					= trim($arr_rs[0]["SIZE_W"]);
					$rs_size_h					= SetStringFromDB($arr_rs[0]["SIZE_H"]);
					$rs_title						= SetStringFromDB($arr_rs[0]["TITLE"]);

					$rs_top						= SetStringFromDB($arr_rs[0]["TOP"]);
					$rs_left_						= SetStringFromDB($arr_rs[0]["LEFT_"]);
					$rs_scrollbars				= SetStringFromDB($arr_rs[0]["SCROLLBARS"]);
					$rs_title				= SetStringFromDB($arr_rs[0]["TITLE"]);
					$rs_contents					= SetStringFromDB($arr_rs[0]["CONTENTS"]);
					$rs_s_date					= SetStringFromDB($arr_rs[0]["S_DATE"]);
					$rs_s_time					= SetStringFromDB($arr_rs[0]["S_TIME"]);
					$rs_e_date					= SetStringFromDB($arr_rs[0]["E_DATE"]);
					$rs_e_time					= SetStringFromDB($arr_rs[0]["E_TIME"]);
					$rs_use_tf					= trim($arr_rs[0]["USE_TF"]);
					$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]);
					$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);

					preg_match_all('/<img .*?src=["|\']([^"|\']+)/is', $rs_contents, $IMG_SRC);

					$IMGSIZE_ARR = getimagesize($IMG_SRC[1][0]);

					if(($IMGSIZE_ARR[0] != "")&&($IMGSIZE_ARR[0] != null)){
						$SIZE_W = $IMGSIZE_ARR[0];
						$SIZE_H = $IMGSIZE_ARR[1];
					}else{
						$SIZE_W = $rs_size_w;
						$SIZE_H = $rs_size_h;
					}
				?>

					<div class="layout_pop<?=$rs_pop_no?>" id="popup<?=$rs_pop_no?>" style="Z-INDEX:999;margin-left:<?=$rs_left_?>px; TOP:<?=$rs_top?>px; width:<?=$SIZE_W?>px;POSITION:absolute; height:<?=$SIZE_H?>px;">
						<?=$rs_contents?>
						<div class="pop_close<?=$rs_pop_no?>" style="position:absolute;left:<?=$SIZE_W-49?>px; top:0px;"><a href="#"><img src="../../images/img/btn_close.png" alt="레이어팝업 닫기버튼" /></a></div>
					</div>

					<script language="JavaScript">
						$(function(){
							$(".pop_close<?=$POP_NO?>").click(function(){
								$(".layout_pop<?=$POP_NO?>").hide();
								return false;
							});
						});
					</script>

				</div>
				<!-- //layout_pop -->

				<?
					$arr_rs = getMainBoardList($conn, $arr_main_board[0], "1");

					$arr_board_info = getBoardPageInfo($conn, $rs_page_lang, $arr_main_board[0], "4");

					$board_nm				= trim($arr_board_info[0]["PAGE_NAME"]);
					$board_link			= trim($arr_board_info[0]["PAGE_URL"]);

				?>
				<div class="mainBox_bbs type1 fl">
					<h3><?=$board_nm?> <a href="<?=$board_link?>"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							$no_file_info = "Y";
							require "../../_common/board/get_list_item.php";

							if ($j == 0) {
								if ($THUMB_IMG) {
									$str_img = "<img src='".$g_base_dir."/upload_data/board/simg/".$THUMB_IMG."' width='213' height='135' alt='본문이미지' />";
								} else {
									$str_img = "<img src='../../images/common/main_no_img01.jpg' width='213' height='135' alt='본문이미지' />";
								}
				?>
					<p>
						<a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$str_img?></a>
					</p>
					<dl>
						<dt><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><span><?=$TITLE?></span></a></dt>
						<dd><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$str_main_contents?></a></dd>
				<?
							} else {
				?>
						<dd><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$TITLE?> - <?=$str_main_contents?></a></dd>
					</dl>
				<?
							}
						}
					}
				?>
				</div>

				<div class="mainBox_bbs type2 fr">
					<div class="banner">
						<div class="flexslider">
							<ul class="slides">

							<?
								$arr_footer_banner = getCommonBannerList($conn, "MAIN");

								if (sizeof($arr_footer_banner) > 0) {
									for ($j = 0 ; $j < sizeof($arr_footer_banner); $j++) {
										$BANNER_IMG			= trim($arr_footer_banner[$j]["BANNER_IMG"]);
										$BANNER_URL			= trim($arr_footer_banner[$j]["BANNER_URL"]);
										$URL_TYPE				= trim($arr_footer_banner[$j]["URL_TYPE"]);
										$BANNER_NM			= SetStringFromDB($arr_footer_banner[$j]["BANNER_NM"]);

										if ($URL_TYPE == "Y") {
											$str_target = "target=\"_blank\"";
										} else {
											$str_target = "target=\"_self\"";
											$str_target = "";
										}

							?>
							<li><a href="<?=$BANNER_URL?>" <?=$str_target?>><img src="<?=$g_base_dir?>/upload_data/banner/<?=$BANNER_IMG?>" width="142" height="46" alt="<?=$BANNER_NM?>"/></a></li>
							<?
									}
								}
							?>
							</ul>
						</div>
					</div>
				</div>

				<div class="fl">

				<?
					$arr_rs = getMainBoardList($conn, $arr_main_board[1], "7");

					$arr_board_info = getBoardPageInfo($conn, $rs_page_lang, $arr_main_board[1], "4");

					$board_nm				= trim($arr_board_info[0]["PAGE_NAME"]);
					$board_link			= trim($arr_board_info[0]["PAGE_URL"]);

				?>
					<div class="mainBox_bbs type3">
						<h3><?=$board_nm?> <a href="<?=$board_link?>"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							$no_file_info = "Y";
							require "../../_common/board/get_list_item.php";
							if ($j == 0) {

								if ($THUMB_IMG) {
									$str_img = "<img src='".$g_base_dir."/upload_data/board/simg/".$THUMB_IMG."' width='280' height='169' alt='본문이미지' />";
								} else {
									$str_img = "<img src='../../images/common/main_no_img02.jpg' width='280' height='169' alt='본문이미지' />";
								}

				?>
							<span><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$str_img?></a></span>
							<ul class="mainBox_list">
				<?
							}
				?>
							<li><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$TITLE?></a></li>
				<?
							}
						}
				?>
						</ul>
					</div>

				<?
					$arr_rs = getMainBoardList($conn, $arr_main_board[2], "5");

					$arr_board_info = getBoardPageInfo($conn, $rs_page_lang, $arr_main_board[2], "4");

					$board_nm				= trim($arr_board_info[0]["PAGE_NAME"]);
					$board_link			= trim($arr_board_info[0]["PAGE_URL"]);

				?>

					<div class="mainBox_bbs type4">
						<h3><?=$board_nm?> <a href="<?=$board_link?>"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
						<ul class="mainBox_list">
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							$no_file_info = "Y";
							require "../../_common/board/get_list_item.php";
				?>
							<li><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$TITLE?></a></li>
				<?
							}
						}
				?>
						</ul>
					</div>
				</div>

				<?
					$arr_rs = getMainBoardList($conn, $arr_main_board[3], "5");

					$arr_board_info = getBoardPageInfo($conn, $rs_page_lang, $arr_main_board[3], "4");

					$board_nm				= trim($arr_board_info[0]["PAGE_NAME"]);
					$board_link			= trim($arr_board_info[0]["PAGE_URL"]);

				?>
				<div class="fl">
					<div class="mainBox_bbs type5">
						<h3><?=$board_nm?> <a href="<?=$board_link?>"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
						<ul class="mainBox_list">
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							$no_file_info = "Y";
							require "../../_common/board/get_list_item.php";
				?>
							<li><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$TITLE?></a></li>
				<?
							}
						}
				?>
						</ul>
					</div>

				<?
					$arr_rs = getMainBoardList($conn, $arr_main_board[4], "5");

					$arr_board_info = getBoardPageInfo($conn, $rs_page_lang, $arr_main_board[4], "4");

					$board_nm				= trim($arr_board_info[0]["PAGE_NAME"]);
					$board_link			= trim($arr_board_info[0]["PAGE_URL"]);

				?>
					<div class="mainBox_bbs type6">
						<h3><?=$board_nm?> <a href="<?=$board_link?>"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
						<ul class="mainBox_list">
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							$no_file_info = "Y";
							require "../../_common/board/get_list_item.php";
				?>
							<li><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$TITLE?></a></li>
				<?
							}
						}
				?>
						</ul>
					</div>

				<?
					$arr_rs = getMainBoardList($conn, $arr_main_board[5], "5");

					$arr_board_info = getBoardPageInfo($conn, $rs_page_lang, $arr_main_board[5], "4");

					$board_nm				= trim($arr_board_info[0]["PAGE_NAME"]);
					$board_link			= trim($arr_board_info[0]["PAGE_URL"]);

				?>
					<div class="mainBox_bbs type7">
						<h3><?=$board_nm?> <a href="<?=$board_link?>"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
						<ul class="mainBox_list">
				<?
					if (sizeof($arr_rs) > 0) {
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
							$no_file_info = "Y";
							require "../../_common/board/get_list_item.php";
				?>
							<li><a href="<?=$board_link?>&m=read&bn=<?=$B_NO?>"><?=$TITLE?></a></li>
				<?
							}
						}
				?>
						</ul>
					</div>
				</div>

				<div class="fr">

					<div class="mainBox_bbs type8">
					<?
						$arr_footer_banner = getCommonBannerList($conn, "right");

						if (sizeof($arr_footer_banner) > 0) {
							for ($j = 0 ; $j < sizeof($arr_footer_banner); $j++) {
								$BANNER_IMG			= trim($arr_footer_banner[$j]["BANNER_IMG"]);
								$BANNER_URL			= trim($arr_footer_banner[$j]["BANNER_URL"]);
								$URL_TYPE				= trim($arr_footer_banner[$j]["URL_TYPE"]);
								$BANNER_NM			= SetStringFromDB($arr_footer_banner[$j]["BANNER_NM"]);

								if ($URL_TYPE == "Y") {
									$str_target = "target=\"_blank\"";
								} else {
									$str_target = "target=\"_self\"";
									$str_target = "";
								}

					?>
						<a href="<?=$BANNER_URL?>" <?=$str_target?>><img src="<?=$g_base_dir?>/upload_data/banner/<?=$BANNER_IMG?>" width="200" height="205" alt="<?=$BANNER_NM?>"/></a>
					<?
							}
						}
					?>
					</div>

					<?
						$arr_jibu_board = array('B_1_26','B_1_31','B_1_36','B_1_41','B_1_46');
					?>
					<div class="mainBox_bbs type9">
						<h3>지방본부소식 <a href="/home2014/pages/?p=54&b=b_1_26&bg=se" id="jibu_link"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
						<div>
							<a href="javascript:void(0);" onClick="js_jibu('se','0');" id="alink_0" class="act">서울</a>
							<a href="javascript:void(0);" onClick="js_jibu('bu','1');" id="alink_1">부산</a>
							<a href="javascript:void(0);" onClick="js_jibu('de','2');" id="alink_2">대전</a>
							<a href="javascript:void(0);" onClick="js_jibu('ye','3');" id="alink_3">영주</a>
							<a href="javascript:void(0);" onClick="js_jibu('su','4');" id="alink_4">호남</a>
						</div>
						<ul class="mainBox_list" id="jibu_board_list">
							<!--
							<li><a href="#">폭염을 넘는 열차조합원들의 분노 폭염을 넘는 열차조합원들의 분노 폭염을 넘는 열차조합원들의 분노</a></li>
							<li><a href="#">폭염을 넘는 열차조합원들의 분노</a></li>
							<li><a href="#">폭염을 넘는 열차조합원들의 분노</a></li>
							<li><a href="#">폭염을 넘는 열차조합원들의 분노</a></li>
							<li><a href="#">폭염을 넘는 열차조합원들의 분노</a></li>
							-->
						</ul>
					</div>
				</div>


			</div>
		</div>
		<div class="wrap">
			<div class="boxWrap">
				<div class="mainBox">

					<h3>사진 <a href="/home2014/pages/?p=30&b=b_1_16"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
					<?
						$arr_rs = getMainBoardList($conn, "b_1_16", "3");

						if (sizeof($arr_rs) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								$B_NO						= trim($arr_rs[$j]["B_NO"]);
								$TITLE					= SetStringFromDB($arr_rs[$j]["TITLE"]);
								$TITLE					= check_html($TITLE);
								$RS_THUMB_IMG		= trim($arr_rs[$j]["THUMB_IMG"]);

								$str_path_img = $g_base_dir."/upload_data/board/simg/".$RS_THUMB_IMG;
								if ($j == 0) {
					?>
					<div>
						<p><span id="img_title"><?=$TITLE?></span></p>
						<img id="img_area" src="<?=$str_path_img?>"/>
					</div>
					<ul>
						<li><a href="javascript:vod(0);" class="bwWrapper" onMouseover="js_change_over('<?=$TITLE?>','<?=$str_path_img?>');"><img  src="<?=$str_path_img?>" width="55" height="55" alt="" /></a></li>
					<?
								} else {
					?>
						<li><a href="javascript:vod(0);" class="bwWrapper" onMouseover="js_change_over('<?=$TITLE?>','<?=$str_path_img?>');"><img  src="<?=$str_path_img?>" width="55" height="55" alt="" /></a></li>
					<?
								}
							}
						}
					?>
					</ul>

				</div>
				<div class="mainBox">
					<h3>모바일 사진 <a href="/home2014/pages/?p=31&mo=sms"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
					<?
						$arr_rs = getMainMoSmsList($conn, "3");

						if (sizeof($arr_rs) > 0) {
							for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
								$TXT					= SetStringFromDB($arr_rs[$j]["TXT"]);
								$TXT					= check_html($TXT);
								$RS_THUMB_IMG		= trim($arr_rs[$j]["THUMB_IMG"]);

								$str_path_img = $g_base_dir."/upload_data/board/simg/".$RS_THUMB_IMG;
								if ($j == 0) {
					?>
					<div>
						<p><span id="img_title2"><?=$TXT?></span></p>
						<img id="img_area2" src="<?=$str_path_img?>"/>
					</div>
					<ul>
						<li><a href="javascript:vod(0);" class="bwWrapper" onMouseover="js_change_over2('<?=$TXT?>','<?=$str_path_img?>');"><img  src="<?=$str_path_img?>" width="55" height="55" alt="" /></a></li>
					<?
								} else {
					?>
						<li><a href="javascript:vod(0);" class="bwWrapper" onMouseover="js_change_over2('<?=$TXT?>','<?=$str_path_img?>');"><img  src="<?=$str_path_img?>" width="55" height="55" alt="" /></a></li>
					<?
								}
							}
						}
					?>
					</ul>
				</div>
				<div class="mainBox">
					<?
						$arr_rs = getMainBoardList($conn, "b_1_15", "1");

						$HOMEPAGE		= trim($arr_rs[0]["HOMEPAGE"]);
						$CATE_03		= trim($arr_rs[0]["CATE_03"]);

						if($HOMEPAGE){
							if(strpos($HOMEPAGE,"youtu.be") !== false){
								$temp_youtobe01=explode("/" ,$HOMEPAGE);
								$temp_youtobe01_count=count($temp_youtobe01);
								$play_url=$temp_youtobe01[$temp_youtobe01_count-1]."?wmode=opaque";
							}
						}

						if($HOMEPAGE){
							if(strpos($HOMEPAGE,"www.youtube.com") !== false){
								$temp_youtobe01=explode("?v=" ,$HOMEPAGE);

								$play_url=str_replace("&feature=","?feature=",$temp_youtobe01[1]."&wmode=opaque");
							}
						}

						if ($play_url) {
							$str_movie_frm = "<iframe width='300' height='180' src='http://www.youtube.com/embed/".$play_url."' frameborder='0' allowfullscreen></iframe>";
						} else {

							if ($CATE_03) {
								$str_cate_03 = str_replace("width","width='300' idth",$CATE_03);
								$str_cate_03 = str_replace("height","height='180' eight",$str_cate_03);
								$str_movie_frm = $str_cate_03;
							}
						}

					?>
					<h3>동영상 <a href="/home2014/pages/?p=29&b=b_1_15"><img src="../../images/btn/btn_more.gif" alt="더보기" /></a></h3>
					<?=$str_movie_frm?>

				</div>
				<div class="mainBox" id="blog_box">
					<!--
					<dl>
						<dt><a href="#" target="_blank" title="새창으로 이동">전철노의 블로그 전철노의 블로그 전철노의 블로그 전철노의 블로그</a></dt>
						<dd><a href="#" target="_blank" title="새창으로 이동">철도안전 확보 노조탄압 중단확보 노조탄압 중단 철도안전 확보 노조탄압 중단확보 노조탄압 중단</a></dd>
						<dt><a href="#" target="_blank" title="새창으로 이동">전철노의 블로그</a></dt>
						<dd><a href="#" target="_blank" title="새창으로 이동">철도안전 확보 노조탄압 중단확보 노조탄압 중단</a></dd>
						<dt><a href="#" target="_blank" title="새창으로 이동">전철노의 블로그</a></dt>
						<dd><a href="#" target="_blank" title="새창으로 이동">철도안전 확보 노조탄압 중단확보 노조탄압 중단</a></dd>
						<dt><a href="#" target="_blank" title="새창으로 이동">전철노의 블로그</a></dt>
						<dd><a href="#" target="_blank" title="새창으로 이동">철도안전 확보 노조탄압 중단확보 노조탄압 중단</a></dd>
						<dt><a href="#" target="_blank" title="새창으로 이동">전철노의 블로그</a></dt>
						<dd><a href="#" target="_blank" title="새창으로 이동">철도안전 확보 노조탄압 중단확보 노조탄압 중단</a></dd>
						<dt><a href="#" target="_blank" title="새창으로 이동">전철노의 블로그</a></dt>
						<dd><a href="#" target="_blank" title="새창으로 이동">철도안전 확보 노조탄압 중단확보 노조탄압 중단</a></dd>
					</dl>
					-->
				</div>
			</div>

			<div class="mainBox_tweet">
				<h3>트위터 <a href="https://twitter.com/krwu7788" target="_blank"><img src="../../images/bu/ic_tweet.gif" alt="" /></a></h3>
				<div class="tweetWrap">
					<a class="twitter-timeline" href="https://twitter.com/krwu7788" data-widget-id="531734591682256897"></a>
					<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
					</script>
				</div>
			</div>

			<div class="sp10"></div>
		</div>
	</section>
</section>

<script>

	$(document).ready(function(){
		js_jibu_board_list('se');
		js_blog_list();
	});

	function js_jibu_board_list(id) {
		$.get("./jibu.board.list.php",
			{jibu:id},
			function(data){
				data = decodeURIComponent(data);
				$("#jibu_board_list").html(data);
			}
		);
	}

	function js_blog_list() {
		$.get("../../rss/read_rss.php",
			{},
			function(data){
				data = decodeURIComponent(data);
				$("#blog_box").html(data);
			}
		);
	}

	function js_jibu(id,idx) {

		if (id == "se") {
			$("#jibu_link").attr("href","/home2014/pages/?p=54&b=b_1_26&bg=se");
		}

		if (id == "bu") {
			$("#jibu_link").attr("href","/home2014/pages/?p=60&b=b_1_31&bg=bu");
		}

		if (id == "de") {
			$("#jibu_link").attr("href","/home2014/pages/?p=66&b=b_1_36&bg=de");
		}

		if (id == "ye") {
			$("#jibu_link").attr("href","/home2014/pages/?p=72&b=b_1_41&bg=ye");
		}

		if (id == "su") {
			$("#jibu_link").attr("href","/home2014/pages/?p=78&b=b_1_46&bg=su");
		}

		for (i = 0 ; i < 5; i++) {
			var obj = "#alink_"+i;
			$(obj).removeClass("act");
		}

		var obj = "#alink_"+idx;
		$(obj).addClass("act");

		js_jibu_board_list(id);
	}

	function js_change_over(title, img_src) {
		$("#img_title").html(title);
		$("#img_area").attr("src", img_src);
	}

	function js_change_over2(title, img_src) {
		$("#img_title2").html(title);
		$("#img_area2").attr("src", img_src);
	}

	// 팝업 COOKIE 체크
	function pop_getCookie( name ) {
		var nameOfCookie = name + "=";
		var x = 0;
		while ( x <= document.cookie.length ) {

		var y = (x+nameOfCookie.length);

		if ( document.cookie.substring( x, y ) == nameOfCookie ) {
			if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
				endOfCookie = document.cookie.length;
				return unescape( document.cookie.substring( y, endOfCookie ) );
			}
			x = document.cookie.indexOf( " ", x ) + 1;
			if ( x == 0 )
				break;
		}
		return "";
	}

</script>


<!-- POPUP -->
<?
$query_pop_cnt ="SELECT COUNT(*) CNT FROM TBL_POPUP WHERE 1 = 1 AND USE_TF='Y' AND CATE_02='Y'";

$result_pop_cnt			= mysql_query($query_pop_cnt,$conn);
$rows_pop_cnt				= mysql_fetch_array($result_pop_cnt);
$record_pop_cnt			= $rows_pop_cnt[0];
$total_pop_cnt			= $record_pop_cnt;

$query_pop = "SELECT POP_NO, CATE_01, CATE_02, SIZE_W, SIZE_H, TOP, LEFT_, SCROLLBARS, TITLE, CONTENTS, S_DATE, S_TIME, E_DATE, E_TIME,
					USE_TF, DEL_TF, REG_ADM, REG_DATE FROM TBL_POPUP WHERE USE_TF = 'Y' AND CATE_02 = 'Y'";
$query_pop .= " ORDER BY REG_DATE desc";

$result_pop = mysql_query($query_pop,$conn);

$record_pop = array();

if ($result_pop <> "") {
	for($i=0;$i < mysql_num_rows($result_pop);$i++) {
		$record_pop[$i] = sql_result_array($result_pop,$i);

		$POP_NO					= trim($record_pop[$i]["POP_NO"]);
		$CATE_01				= trim($record_pop[$i]["CATE_01"]);
		$CATE_02				= trim($record_pop[$i]["CATE_02"]);
		$TITLE					= SetStringFromDB($record_pop[$i]["TITLE"]);
		$SIZE_W					= trim($record_pop[$i]["SIZE_W"]);
		$SIZE_H					= trim($record_pop[$i]["SIZE_H"]);
		$TOP						= trim($record_pop[$i]["TOP"]);
		$LEFT_					= trim($record_pop[$i]["LEFT_"]);
		$S_DATE					= trim($record_pop[$i]["S_DATE"]);
		$S_TIME					= trim($record_pop[$i]["S_TIME"]);
		$E_DATE					= trim($record_pop[$i]["E_DATE"]);
		$E_TIME					= trim($record_pop[$i]["E_TIME"]);
		$SCROLLBARS			= trim($record_pop[$i]["SCROLLBARS"]);
		$CONTENTS				= SetStringFromDB($record_pop[$i]["CONTENTS"]);
		$USE_TF					= trim($record_pop[$i]["USE_TF"]);
		$REG_DATE				= trim($record_pop[$i]["REG_DATE"]);
		$REG_DATE				= date("Y-m-d",strtotime($REG_DATE));

		preg_match_all('/<img .*?src=["|\']([^"|\']+)/is', $CONTENTS, $IMG_SRC);

		$IMGSIZE_ARR = getimagesize($IMG_SRC[1][0]);

		if(($IMGSIZE_ARR[0] != 0)&&($IMGSIZE_ARR[0] != "")){
			$SIZE_W = $IMGSIZE_ARR[0];
			$SIZE_H = $IMGSIZE_ARR[1];
			$SIZE_H = $SIZE_H+36;
		}

		if($TOP == ""){
			$TOP = 0;
		}

		if($LEFT_ == ""){
			$LEFT_ = 0;
		}

		if ($SCROLLBARS == "Y") {
			$SCROLLBARS = "yes";
		}else{
			$SCROLLBARS = "no";
		}

		//echo $S_DATE." ".$S_TIME;

?>

<? if($CATE_01 == "Y"){ //타이머기능 사용

		if((date("Y-m-d H:i:s") >= $S_DATE." ".$S_TIME)&&(date("Y-m-d H:i:s") < $E_DATE." ".$E_TIME)){?>

		<script type="text/javascript">
			if ( pop_getCookie( "pop_<?=$POP_NO?>" ) != "done" ) {
				window.open('popup.php?pop_no=<?=$POP_NO?>','<?=$POP_NO?>','width=<?=$SIZE_W?>,height=<?=$SIZE_H?>,top=<?=$TOP?>,left=<?=$LEFT_?>,scrollbars=<?=$SCROLLBARS?>');
			}
		</script>
<?
		}
	}else{ //타이머기능 사용 안함 ?>

		<script type="text/javascript">
			if ( pop_getCookie( "pop_<?=$POP_NO?>" ) != "done" ) {
				window.open('popup.php?pop_no=<?=$POP_NO?>','<?=$POP_NO?>','width=<?=$SIZE_W?>,height=<?=$SIZE_H?>,top=<?=$TOP?>,left=<?=$LEFT_?>,scrollbars=<?=$SCROLLBARS?>');
			}
		</script>

<? }
	}
}
?>
<!-- POPUP -->

<?
#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/front_bottom_area.php";
?>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>