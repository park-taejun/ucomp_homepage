<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
# =============================================================================
# File Name    : maintext_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2018-07-11
# Modify Date  : 
#	Copyright : Copyright @UCOM Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$menu_right = "CS003"; // 메뉴마다 셋팅 해 주어야 합니다

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

	#List Parameter
	$seq_no					= $_POST['seq_no']!=''?$_POST['seq_no']:$_GET['seq_no'];
	$text_type			= $_POST['text_type']!=''?$_POST['text_type']:$_GET['text_type'];
	$sel_text_lang	= $_POST['sel_text_lang']!=''?$_POST['sel_text_lang']:$_GET['sel_text_lang'];

	$text_title			= $_POST['text_title']!=''?$_POST['text_title']:$_GET['text_title'];
	$text_desc			= $_POST['text_desc']!=''?$_POST['text_desc']:$_GET['text_desc'];
	$text_sub				= $_POST['text_sub']!=''?$_POST['text_sub']:$_GET['text_sub'];
	$text_url				= $_POST['text_url']!=''?$_POST['text_url']:$_GET['text_url'];

	$seq_no						= trim($seq_no);
	$text_type				= trim($text_type);
	$sel_text_lang		= trim($sel_text_lang);

	$text_title				= trim($text_title);
	$text_desc				= trim($text_desc);
	$text_sub					= trim($text_sub);
	$text_url					= trim($text_url);

	if ($sel_text_lang == "") $sel_text_lang= "KOR";

	if (empty($text_type)) {
		$text_type = "MAIN";
	}

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/maintext/maintext.php";
	
	//$arr_rs = getSiteInfo($conn, $site_no);
	
#====================================================================
# Request Parameter
#====================================================================

	$mode					= trim($mode);
	$nPage				= trim($nPage);
	$nPageSize		= trim($nPageSize);
	$search_field	= trim($search_field);
	$search_str		= trim($search_str);

#====================================================================
# DML Process
#====================================================================

	if ($mode == "I") {

		$text_title = SetStringToDB($text_title);
		$text_desc	= SetStringToDB($text_desc);
		$text_sub		= SetStringToDB($text_sub);

		$disp_seq = "0";

		// 웹진 등록
		$arr_data = array("SITE_NO"=>$g_site_no,
											"TEXT_LANG"=>$sel_text_lang,
											"TEXT_TITLE"=>$text_title,
											"TEXT_DESC"=>$text_desc,
											"TEXT_SUB"=>$text_sub,
											"TEXT_URL"=>$text_url,
											"URL_TYPE"=>$url_type,
											"DISP_SEQ"=>$disp_seq,
											"USE_TF"=>$use_tf,
											"REG_ADM"=>$_SESSION['s_adm_no']
											);

		$result = insertMaintext($conn, $arr_data);

		if ($result) {
?>	
<script language="javascript">
		//location.href =  '<?=$_SERVER[PHP_SELF]?>?banner_type=<?=$banner_type?>';
</script>
<?
			//exit;
		}
	}

	if ($mode == "U") {

		$arr_data = array("TEXT_LANG"=>$sel_text_lang,
											"TEXT_TITLE"=>$text_title,
											"TEXT_DESC"=>$text_desc,
											"TEXT_SUB"=>$text_sub,
											"TEXT_URL"=>$text_url,
											"URL_TYPE"=>$url_type,
											"USE_TF"=>$use_tf,
											"UP_ADM"=>$_SESSION['s_adm_no']
											);

		$result = updateMaintext($conn, $arr_data, $seq_no);

		if ($result) {
?>	
<script language="javascript">
	location.href =  '<?=$_SERVER[PHP_SELF]?>?text_type=<?=$banner_type?>&menu_cd=<?=$menu_cd?>';
</script>
<?
			exit;
		}
	}

	if ($mode == "O") {
		
		
		$row_cnt = count($seq_no);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_no = $seq_no[$k];

			$result = updateOrderMaintext($conn, $k, $g_site_no, $tmp_no);
		
		}
	}

	if ($mode == "S") {

		$arr_rs = selectMaintext($conn, $g_site_no, $seq_no);
		
		$rs_seq_no					= trim($arr_rs[0]["SEQ_NO"]);  
		$rs_text_lang				= trim($arr_rs[0]["TEXT_LANG"]); 
		$rs_text_title			= trim($arr_rs[0]["TEXT_TITLE"]); 
		$rs_text_desc				= trim($arr_rs[0]["TEXT_DESC"]); 
		$rs_text_sub				= trim($arr_rs[0]["TEXT_SUB"]); 
		$rs_text_url				= trim($arr_rs[0]["TEXT_URL"]); 
		$rs_url_type				= trim($arr_rs[0]["URL_TYPE"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_del_tf					= trim($arr_rs[0]["DEL_TF"]); 

	}

	$del_tf = "N";
	$use_tf = "";
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
		$nPageSize = 100;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntMaintext($conn, $g_site_no, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = ($nListCnt - 1) / $nPageSize + 1 ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	$nPage		 = 1;
	$nPageSize = 100;

	$arr_rs = listMaintext($conn, $g_site_no, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$s_comm_name?> 관리자 로그인</title>
<link href="../css/common.css" rel="stylesheet" />

<!--[if IE]>
<script>
document.createElement("header");
document.createElement("footer");
document.createElement("aside");
document.createElement("nav");
document.createElement("article");
document.createElement("section");
document.createElement("figure");
document.createElement("figcaption");
document.createElement("legend");
document.createElement("time");
</script>
<![endif]-->
<!--[if IE]> 
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<script type="text/javascript" src="../js/common.js"></script>
<script type="text/javascript">

function js_save() {

	var frm = document.frm;
	var seq_no = "<?= $seq_no ?>";

	if (seq_no == "") {
		alert('메인퀵링크관리는 추가 하실 수 없는 메뉴 입니다.');
		return ;
	}
	
	frm.text_title.value = frm.text_title.value.trim();
	frm.text_desc.value = frm.text_desc.value.trim();
	frm.text_sub.value = frm.text_sub.value.trim();
	
	
	if (isNull(frm.text_title.value)) {
		alert('제목을 입력해주세요.');
		frm.text_title.focus();
		return ;		
	}

	if (isNull(frm.text_desc.value)) {
		alert('설명을 입력해주세요.');
		frm.text_desc.focus();
		return ;		
	}

	if (isNull(frm.text_sub.value)) {
		alert('부제목을 입력해주세요.');
		frm.text_sub.focus();
		return ;		
	}

	if (document.frm.rd_use_tf == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_use_tf[0].checked == true) {
			frm.use_tf.value = "Y";
		} else {
			frm.use_tf.value = "N";
		}
	}

	if (document.frm.rd_url_type == null) {
		//alert(document.frm.rd_use_tf);
	} else {
		if (frm.rd_url_type[0].checked == true) {
			frm.url_type.value = "Y";
		} else {
			frm.url_type.value = "N";
		}
	}

	if (isNull(seq_no)) {
		frm.mode.value = "I";
	} else {
		frm.mode.value = "U";
		frm.seq_no.value = frm.seq_no.value;
	}

	frm.target = "";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

function js_view(rn, seq) {

	var frm = document.frm;
		
	frm.seq_no.value = seq;
	frm.mode.value = "S";
	frm.target = "";
	frm.method = "get";
	frm.action = "<?=$_SERVER[PHP_SELF]?>";
	frm.submit();

}

var preid = -1;

function js_up(n) {
	
	preid = parseInt(n);

	if (preid > 1) {
		

		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid-1].innerHTML;

		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid-1].cells;

		for(var j=0 ; j < cells1.length; j++) {
			
			if (j != 1) {
				var temp = cells2[j].innerHTML;

				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;

				var tempCode = document.frm.seq_text_no[preid-2].value;
			
				document.frm.seq_text_no[preid-2].value = document.frm.seq_text_no[preid-1].value;
				document.frm.seq_text_no[preid-1].value = tempCode;
			}
		}
		
		//preid = preid - 1;
		js_change_order();

	} else {
		alert("가장 상위에 있습니다. ");
	}
}


function js_down(n) {

	preid = parseInt(n);

	//alert(preid_plus);

	if (preid < document.getElementById("t").rows.length-1) {
		
		temp1 = document.getElementById("t").rows[preid].innerHTML;
		temp2 = document.getElementById("t").rows[preid+1].innerHTML;
		
		var cells1 = document.getElementById("t").rows[preid].cells;
		var cells2 = document.getElementById("t").rows[preid+1].cells;
		
		for(var j=0 ; j < cells1.length; j++) {

			if (j != 1) {
				var temp = cells2[j].innerHTML;

			
				cells2[j].innerHTML =cells1[j].innerHTML;
				cells1[j].innerHTML = temp;
	
				var tempCode = document.frm.seq_text_no[preid-1].value;
				document.frm.seq_text_no[preid-1].value = document.frm.seq_text_no[preid].value;
				document.frm.seq_text_no[preid].value = tempCode;
			}
		}
		
		//preid = preid + 1;	
		js_change_order();
	} else{
		alert("가장 하위에 있습니다. ");
	}
}

function js_change_order() {
	
	if(document.getElementById("t").rows.length < 2) {
		alert("순서를 저장할 메뉴가 없습니다");//순서를 저장할 메뉴가 없습니다");
		return;
	}

	document.frm.mode.value = "O";
	document.frm.target = "ifr_hidden";
	document.frm.action = "maintext_order_dml.php";
	document.frm.submit();

}

</script>
</head>

<body>

<div class="wrapper">
<section id="container">	

<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>

	<section class="conRight">

<?
	#====================================================================
	# common top_area
	#====================================================================

	require "../../_common/top_area.php";
?>
		<div class="conTit">
			<h2><?=$p_parent_menu_name?></h2>
		</div>
		<section class="conBox">

<form id="bbsList" name="frm" method="post">
<input type="hidden" name="rn" value="" />
<input type="hidden" name="seq_no" value="<?=$seq_no?>" />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
					
					<p style="float:right; padding-right:3%; padding-top:25px;">
						<input type="hidden" name="sel_text_lang" id="sel_text_lang" value="KOR">
					</p>

					<table summary="이곳에서 메인 비주얼을 관리하실 수 있습니다" class="secTop">
					<caption><?=$p_menu_name?></caption>
					<tbody>
						<tr>
							<th class="long" style="width:120px"><label for="text_title">제목</label></th>
							<td>
								<input type="text" name="text_title" id="text_title"  value="<?=$rs_text_title?>" class="wfull" />
							</td>
						</tr>

						<tr>
							<th class="long" style="width:120px"><label for="text_desc">설명</label></th>
							<td>
								<input type="text" name="text_desc" id="text_desc"  value="<?=$rs_text_desc?>" class="wfull" />
							</td>
						</tr>

						<tr>
							<th class="long" style="width:120px"><label for="text_sub">부제목</label></th>
							<td>
								<input type="text" name="text_sub" id="text_sub"  value="<?=$rs_text_sub?>" class="wfull" />
							</td>
						</tr>

						<tr>
							<th class="long"><label for="bannerLink">링크주소</label></th>
							<td><input type="text" name="text_url" id="text_url" value="<?=$rs_text_url?>" class="wfull" /></td>
						</tr>

						<tr>
							<th class="long">링크방식</th>
							<td>
								<input type="radio" id="blank" name="rd_url_type" value="Y" <? if (($rs_url_type =="Y") || ($rs_url_type =="")) echo "checked"; ?> class="radio" /><label for="banLink">새창</label>
								<input type="radio" id="own" name="rd_url_type" value="N" <? if ($rs_url_type =="N") echo "checked"; ?> class="radio" /><label for="banLink">자기창</label>
								<input type="hidden" name="url_type" value="<?= $rs_url_type ?>">
							</td>
							</tr>
							<tr class="last">
								<th class="long">공개여부</th>
								<td>
									<input type="radio" id="all" name="rd_use_tf" value="Y" <? if (($rs_use_tf =="Y") || ($rs_use_tf =="")) echo "checked"; ?> class="radio" /><label for="all">공개</label>
									<input type="radio" id="secret" name="rd_use_tf" value="N" <? if ($rs_use_tf =="N") echo "checked"; ?> class="radio" /><label for="secret">비공개</label>
									<input type="hidden" name="use_tf" value="<?= $rs_use_tf ?>">
								</td>
							</tr>
						</tbody>
					</table>

					<div class="btnArea">
						<ul class="fRight">
							<li><a href="javascript:js_save();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>
							<li><a href="javascript:document.frm.reset();"><img src="../images/btn/btn_cancel.gif" alt="취소" /></a></li>
						</ul>
					</div>

					<table summary="이곳에서 메인 페이지의 배너를 관리하실 수 있습니다" class="secBtm" id='t'>
						<thead>
							<tr>
								<th class="num">순서</th>
								<th class="moveIcon">&nbsp;</th>
								<th class="tit">제목</th>
								<th class="visual">설명</th>
								<th class="visual">부제목</th>
								<th class="visual">링크</th>
							</tr>
						</thead>
						<tbody>
						<?
								
							if (sizeof($arr_rs) > 0) {
											
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									$rn								= trim($arr_rs[$j]["rn"]);
									$SEQ_NO						= trim($arr_rs[$j]["SEQ_NO"]);
									$TEXT_TITLE				= trim($arr_rs[$j]["TEXT_TITLE"]);
									$TEXT_DESC				= trim($arr_rs[$j]["TEXT_DESC"]);
									$TEXT_SUB					= trim($arr_rs[$j]["TEXT_SUB"]);
									$TEXT_URL					= trim($arr_rs[$j]["TEXT_URL"]);
									
									$USE_TF						= trim($arr_rs[$j]["USE_TF"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);

									if ($USE_TF == "Y") {
										$STR_USE_TF = "<font color='blue'>공개</font>";
									} else {
										$STR_USE_TF = "<font color='red'>비공개</font>";
									}

									$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
						?>
							<tr <? if ($j == (sizeof($arr_rs) -1)) echo "class='last'"; ?> >
								<td class="num"><!--<input type="checkbox" name="chk[]" value="<?=$SEQ_NO?>">--> <?=$j+1?></td>
								<td class="moveIcon">
									<ul>
										<li><a href="javascript:js_up('<?=($j+1)?>');"><img src="../images/btn/btn_up.gif" alt="up" /></a></li>
										<li><a href="javascript:js_down('<?=($j+1)?>');"><img src="../images/btn/btn_down.gif" alt="down" /></a></li>
									</ul>
								</td>
								<td class="tit">
									<a href="javascript:js_view('<?=$rn?>','<?=$SEQ_NO?>');"><?=$TEXT_TITLE?></a>
									<input type="hidden" name="seq_text_no" value="<?=$SEQ_NO?>">
									<input type="hidden" name="text_seq_no[]" value="<?=$SEQ_NO?>">
								</td>
								<td><?=$TEXT_DESC?></td>
								<td><?=$TEXT_SUB?></td>
								<td><a href="<?=$TEXT_URL?>" target="_blank">링크</a></td>
							</tr>
						<?
								}
							} else { 
						?>
							<tr>
								<td height="50" align="center" colspan="7">데이터가 없습니다. </td>
							</tr>
				<? 
					}
				?>
						</tbody>
				</table>
			</fieldset>
			</form>
		</section>
	</section>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</section>
</div><!--wrapper-->
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
