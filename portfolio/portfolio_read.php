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

	$p_no								= $_POST['p_no']!=''?$_POST['p_no']:$_GET['p_no'];
	$con_p_yyyy					= $_POST['con_p_yyyy']!=''?$_POST['con_p_yyyy']:$_GET['con_p_yyyy'];
	$con_p_mm						= $_POST['con_p_mm']!=''?$_POST['con_p_mm']:$_GET['con_p_mm'];
	$con_p_category			= $_POST['con_p_category']!=''?$_POST['con_p_category']:$_GET['con_p_category'];
	$con_p_type					= $_POST['con_p_type']!=''?$_POST['con_p_type']:$_GET['con_p_type'];

	$arr_rs = selectPortfolio($conn, $p_no);

	$rs_p_no								= trim($arr_rs[0]["P_NO"]); 
	$rs_p_name01						= SetStringFromDB($arr_rs[0]["P_NAME01"]); 
	$rs_p_name02						= SetStringFromDB($arr_rs[0]["P_NAME02"]); 
	$rs_p_yyyy							= trim($arr_rs[0]["P_YYYY"]); 
	$rs_p_mm								= trim($arr_rs[0]["P_MM"]); 
	$rs_p_category					= trim($arr_rs[0]["P_CATEGORY"]); 
	$rs_p_type							= trim($arr_rs[0]["P_TYPE"]); 
	$rs_p_client						= SetStringFromDB($arr_rs[0]["P_CLIENT"]); 
	$rs_p_contents					= SetStringFromDB($arr_rs[0]["P_CONTENTS"]); 
	$rs_p_img01							= trim($arr_rs[0]["P_IMG01"]); 
	$rs_p_img02							= trim($arr_rs[0]["P_IMG02"]); 
	$rs_p_img03							= trim($arr_rs[0]["P_IMG03"]); 
	$rs_p_img04							= trim($arr_rs[0]["P_IMG04"]); 
	$rs_prize_files					= trim($arr_rs[0]["PRIZE_FILES"]); 
	$rs_hit_cnt							= trim($arr_rs[0]["HIT_CNT"]); 
	$rs_keyword							= trim($arr_rs[0]["KEYWORD"]); 
	$rs_link01							= trim($arr_rs[0]["LINK01"]); 
	$rs_link02							= trim($arr_rs[0]["LINK02"]); 
	$rs_use_tf							= trim($arr_rs[0]["USE_TF"]); 
	$rs_txt_color						= trim($arr_rs[0]["TXT_COLOR"]); 

	$rs_link01 = str_replace("http://","",$rs_link01);
	$rs_link01 = str_replace("https://","",$rs_link01);
	
	$arr_rs_file = listPortfolioFile($conn, $p_no);
	$arr_rs_prize = listPortfolioPrize($conn, $p_no);

	$con_use_tf = "Y";
	$con_del_tf = "N";
	$con_top_tf = "Y";

	$arr_post_rs = selectPostPortfolio($conn, $p_no, $rs_p_yyyy, $rs_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);
	
	if (sizeof($arr_post_rs) > 0) {
		$rs_post_p_no			= trim($arr_post_rs[0]["P_NO"]); 
	}

	$arr_pre_rs = selectPrePortfolio($conn, $p_no, $rs_p_yyyy, $rs_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);

	if (sizeof($arr_pre_rs) > 0) {
		$rs_pre_p_no			= trim($arr_pre_rs[0]["P_NO"]); 
	}


	if ($nPage <> "") {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

/*
	if ($nPageSize <> "") {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 7;
	}
*/

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt = totalCntPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str);
	
	$nPageSize = $nListCnt;

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs_list = listPortfolio($conn, $con_p_yyyy, $con_p_mm, $con_p_category, $con_p_type, $con_top_tf, $con_main_tf, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);
	
	$this_num = "";

	if (sizeof($arr_rs_list) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs_list); $j++) {
			$rs_p_no						= trim($arr_rs_list[$j]["P_NO"]);
			if ($p_no == $rs_p_no) {
				$this_num = ($j+1);
			}
		}
	}

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<meta name="msvalidate.01" content="D3D1C99AF64E85DB61B385661327885B" />
<meta name="robots" content="index, follow">
<title>유컴패니온</title>
<meta content="유컴패니온" name="keywords" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="description" content="유컴패니온">
<meta content="유컴패니온" name="keywords" />
<link rel="icon" type="image/x-icon" href="../images/favicon.ico" />
<link type="text/css" rel="stylesheet" href="../css/reset.css" />
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="../js/jquery_ui.js"></script>
<script type="text/javascript" src="../js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="../js/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<script type="text/javascript" src="../js/slick.js"></script>
</head>

<body id="projectView">

<div id="wrap">

<?
	require "../_common/front.header.php";
?>

	<!-- S: midarea -->
	<div class="midarea">
		<div class="leftarea">
			<p class="category"><button type="button" onclick="location.href='list_thumb?con_p_type=<?=$con_p_type?>'"></button></p>
			<p class="btnset">
				<? if ($rs_pre_p_no == "") {?>
				<button type="button" class="btn-prev" onclick="alert('다음글이 없습니다.');">다음</button>
				<? } else { ?>
				<button type="button" class="btn-prev" onclick="location.href='read?p_no=<?=$rs_pre_p_no?>&con_p_type=<?=$con_p_type?>'">다음</button>
				<? } ?>
				
				<? if ($rs_post_p_no == "") {?>
				<button type="button" class="btn-next" onclick="alert('이전글이 없습니다.');">이전</button>
				<? } else { ?>
				<button type="button" class="btn-next" onclick="location.href='read?p_no=<?=$rs_post_p_no?>&con_p_type=<?=$con_p_type?>'">이전</button>
				<? } ?>
			</p>
		</div>
		<div class="contentsarea" id="contents">
			<div class="project-view">
				<div class="pc-only">
					<h2><?=nl2br($rs_p_name01)?></h2>
					<div class="clearfix">
						<h3><?=$rs_p_name02?></h3><!-- 2020.04.03 한글 타이틀 추가 -->
						<ul class="info">
							<li><strong>Client.</strong><span><?=$rs_p_client?></span></li>
							<li><strong>Date.</strong><span><?=$rs_p_yyyy?> <?=getDcodeName($conn, "MONTH",$rs_p_mm)?></span></li>
							<li><strong>Site URL.</strong><span><a href="http://<?=$rs_link01?>" target="_blank" ><?=$rs_link01?></a></span></li>
						</ul>
					</div>

					<? if (sizeof($arr_rs_prize) > 0) {  ?>
					<ul class="award"><!-- 2020.04.01 어워드 영역 변경-->
						<?
								for ($k=0 ; $k < sizeof($arr_rs_prize) ; $k++) { 
									$PRIZE_NM				= trim($arr_rs_prize[$k]["PRIZE_NM"]);
									$PRIZE_SUB_NM		= trim($arr_rs_prize[$k]["PRIZE_SUB_NM"]);
									$RS_FILE_NM			= trim($arr_rs_prize[$k]["FILE_NM"]);
									$RS_FILE_RNM		= trim($arr_rs_prize[$k]["FILE_RNM"]);
						?>
						<li><span><img src="../upload_data/portfolio/<?=$RS_FILE_NM?>" alt=""></span><strong><?=$PRIZE_NM?><br><?=$PRIZE_SUB_NM?></strong></li><!-- 2020.04.03 어워드 타이틀 추가 -->
						<?
								}
						?>
					</ul>
					<? } ?>

				</div>
				<div class="subvisual">
					<p class="pt-only"><img src="../upload_data/portfolio/<?=$rs_p_img04?>" alt="" /></p>
					<p class="mobile-only"><img src="../upload_data/portfolio/<?=$rs_p_img02?>" alt="" /></p>
					
					<strong class="pagecount"><sub><?=$this_num?></sub><span>/ <?=$nListCnt?></span></strong>
				</div>
				<div class="tm-only">
					<h2><?=nl2br($rs_p_name01)?></h2>
					<div class="clearfix">
						<h3><?=$rs_p_name02?></h3><!-- 2020.04.03 한글 타이틀 추가 -->
						<ul class="info">
							<li><strong>Client.</strong><span><?=$rs_p_client?></span></li>
							<li><strong>Date.</strong><span><?=$rs_p_yyyy?> <?=getDcodeName($conn, "MONTH",$rs_p_mm)?></span></li>
							<li><strong>Site URL.</strong><span><a href="http://<?=$rs_link01?>"  target="_blank" ><?=$rs_link01?></a></span></li>
						</ul>
					</div>
					<? if (sizeof($arr_rs_prize) > 0) {  ?>
					<ul class="award"><!-- 2020.04.01 어워드 영역 변경-->
						<?
								//$arr_prize_files = explode("^", $rs_prize_files);
								for ($k=0 ; $k < sizeof($arr_rs_prize) ; $k++) { 
									$PRIZE_NM				= trim($arr_rs_prize[$k]["PRIZE_NM"]);
									$PRIZE_SUB_NM		= trim($arr_rs_prize[$k]["PRIZE_SUB_NM"]);
									$RS_FILE_NM			= trim($arr_rs_prize[$k]["FILE_NM"]);
									$RS_FILE_RNM		= trim($arr_rs_prize[$k]["FILE_RNM"]);
						?>
						<li><span><img src="../upload_data/portfolio/<?=$RS_FILE_NM?>" alt=""></span><strong><?=$PRIZE_NM?><br><?=$PRIZE_SUB_NM?></strong></li><!-- 2020.04.03 어워드 타이틀 추가 -->
						<?
								}
						?>
					</ul>
					<? } ?>
				</div>
				<dl class="overview">
					<dt>OVERVIEW</dt>
					<dd>
						<?=nl2br($rs_p_contents)?>
					</dd>
				</dl>
				<div class="full_box">
					<div class="overview-img">
						<?
							if (sizeof($arr_rs_file) > 0) {
								for ($j=0 ; $j < sizeof($arr_rs_file) ; $j++) {
									$RS_FILE_NO			= trim($arr_rs_file[$j]["FILE_NO"]);
									$RS_FILE_NM			= trim($arr_rs_file[$j]["FILE_NM"]);
									$RS_FILE_RNM		= trim($arr_rs_file[$j]["FILE_RNM"]);
							?>
						<p><img src="../upload_data/portfolio/<?=$RS_FILE_NM?>" alt="<?=$rs_p_name02?> 상세이미지" /></p>
						<?
								}
							}
						?>
						<!--
						<p><img src="images/img_theh_01_02.jpg" alt="the h 상세이미지" /></p>
						<p><img src="images/img_theh_01_03.jpg" alt="the h 상세이미지" /></p>
						<p><img src="images/img_theh_01_04.jpg" alt="the h 상세이미지" /></p>
						-->
					</div>
				</div>
    </div>
		<button type="button" class="btn-top" title="TOP">TOP</button>
    </div>
	</div>
	<!-- //E: midarea -->

	<div class="sub-footer"> <!-- 2020.03.23 변경 -->
		<div class="address"><strong>U:COMPANION, 3F, 4F,<br />Deokyoon Building, Nonhyeon-ro 28-gil,<br />Gangnam-gu, Seoul</strong></div>
		<div class="info">
			<dl class="tel">
				<dt><span>TEL</span></dt>
				<dd>
					<ul>
						<li><span>대표전화</span><strong>070.5030.5830</strong></li>
						<li><span>기타문의</span><strong>070.5030.5831~8</strong></li>
					</ul>
				</dd>
			</dl>
			<dl class="contact">
				<dt><span>PROJECT CONTACT</span></dt>
				<dd>
					<ul>
						<li><a href="mailto:ucomp_contact@ucomp.co.kr"><strong>ucomp_contact@ucomp.co.kr</strong></a></li>
					</ul>
				</dd>
			</dl>
		</div>
		<p class="copyrights">ⓒ U:COMPANION. ALL RIGHT RESERVED</p>
	</div>

</div>

<script type="text/javascript" src="../js/common_ui.js"></script>

<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-28115000-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' :'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>