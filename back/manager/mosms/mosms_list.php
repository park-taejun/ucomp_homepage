<?session_start();?>
<?
header("x-xss-Protection:0");
# =============================================================================
# File Name    : mosms_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2014.11.09
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "MO001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/mosms/mosms.php";

	$mode								= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$use_tf							= $_POST['use_tf']!=''?$_POST['use_tf']:$_GET['use_tf'];
	$nPage							= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];
	$nPageSize					= $_POST['nPageSize']!=''?$_POST['nPageSize']:$_GET['nPageSize'];
	$search_field				= $_POST['search_field']!=''?$_POST['search_field']:$_GET['search_field'];
	$search_str					= $_POST['search_str']!=''?$_POST['search_str']:$_GET['search_str'];
	//$con_group_no							= $_POST['con_group_no']!=''?$_POST['con_group_no']:$_GET['con_group_no'];

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$nPage			= SetStringToDB($nPage);
	$nPageSize		= SetStringToDB($nPageSize);
	$nPage			= trim($nPage);
	$nPageSize		= trim($nPageSize);

	$search_field		= SetStringToDB($search_field);
	$search_str			= SetStringToDB($search_str);
	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
	$use_tf				= SetStringToDB($use_tf);
	
	$del_tf = "N";
#============================================================
# Page process
#============================================================

	$result = insertMoTableToDb($conn);

	if ($mode == "T") {
		updateMoSmsUseTF($conn, $use_tf, (int)$seq_no);
	}

	if ($mode == "D") {

		// 삭제 권한 관련 입니다.
		$row_cnt = count($chk);
		for ($k = 0; $k < $row_cnt; $k++) {
			$tmp_seq_no = (int)$chk[$k];
			$result= deleteMoSms($conn, $s_adm_no, $tmp_seq_no);
		}
	}

	if ($nPage <> "" && $nPageSize <> 0) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPageSize <> "" && $nPageSize <> 0) {
		$nPageSize = (int)($nPageSize);
	} else {
		$nPageSize = 20;
	}

	$nPageBlock	= 10;

#===============================================================
# Get Search list count
#===============================================================
	$con_del_tf = "N";

	$nListCnt =totalCntMoSms($conn, $con_use_tf, $con_del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	$arr_rs = listMoSms($conn, $con_use_tf, $con_del_tf, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

	#echo sizeof($arr_rs);

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
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
<script type="text/javascript" src="../js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" >
	
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm_search;
		
		frm.nPage.value = "1";
		frm.method = "get";
		//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
		frm.action = "mosms_list.php";
		frm.submit();
	}

	function js_toggle(seq_no, use_tf) {
		var frm = document.frm_search;

		bDelOK = confirm('사용 여부를 변경 하시겠습니까?');
		
		if (bDelOK==true) {

			if (use_tf == "Y") {
				use_tf = "N";
			} else {
				use_tf = "Y";
			}

			frm.seq_no.value = seq_no;
			frm.use_tf.value = use_tf;
			frm.mode.value = "T";
			frm.target = "";
			//frm.action = "<?//=$_SERVER[PHP_SELF]?>";
			frm.action = "mosms_list.php";
			frm.submit();
		}
	}

	function js_delete() {
		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
	
		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
	
		if (chk_cnt == 0) {
			alert("선택 하신 자료가 없습니다.");
		} else {

			bDelOK = confirm('선택하신 자료를 삭제 하시겠습니까?');
		
			if (bDelOK==true) {
				frm.mode.value = "D";
				frm.target = "";
				frm.action = "<?=$_SERVER[PHP_SELF]?>";
				frm.submit();
			}
		}
	}

	$("img").fullsize(); 

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

		<form id="bbsList" name="frm">
			<input type="hidden" name="mode" value="">
			<input type="hidden" name="nPage" value="<?=(int)$nPage?>">
			<input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>">

			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?></legend>
				<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다">
					<caption><?=$p_menu_name?></caption>
					<colgroup>
						<col width="5%" />
						<col width="5%" />
						<col width="15%" />
						<col width="15%" />
						<col width="15%" />
						<col width="15%" />
						<col width="15%" />
						<col width="15%" />
					</colgroup>
					<thead>
					<tr>
						<th scope="col">&nbsp;</th>
						<th scope="col">번호</th>
						<th scope="col">사진</th>
						<th scope="col">발신휴대전화번호</th>
						<th scope="col">제목</th>
						<th scope="col">내용</th>
						<th scope="col">등록일</th>
						<th scope="col">사용여부</th>
					</tr>
				</thead>
				<tbody>

				<?
					$nCnt = 0;
					
					if (sizeof($arr_rs) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

							$rn						= trim($arr_rs[$j]["rn"]);
							$SEQ_NO				= trim($arr_rs[$j]["SEQ_NO"]);
							$THUMB_IMG		= trim($arr_rs[$j]["THUMB_IMG"]);
							$FILE_NM			= trim($arr_rs[$j]["FILE_NM"]);
							$OAADDR				= trim($arr_rs[$j]["OAADDR"]);
							$SUBJECT			= trim($arr_rs[$j]["SUBJECT"]);
							$TXT					= trim($arr_rs[$j]["TXT"]);
							$INSERT_TIME	= trim($arr_rs[$j]["INSERT_TIME"]);
							$USE_TF				= trim($arr_rs[$j]["USE_TF"]);
							$DEL_TF				= trim($arr_rs[$j]["DEL_TF"]);
							$REG_DATE			= trim($arr_rs[$j]["REG_DATE"]);

							if ($USE_TF == "Y") {
								$STR_USE_TF = "<font color='navy'>사용중</font>";
							} else {
								$STR_USE_TF = "<font color='red'>사용안함</font>";
							}
							
							$FILE_NM = str_replace("/data01/krwu/http","",$FILE_NM);
							//echo $FILE_NM;

							$INSERT_TIME = date("Y-m-d H:s:i",strtotime($INSERT_TIME));
				
				?>

						<tr>
							<td><input type="checkbox" name="chk[]" value="<?=$SEQ_NO?>"></td>
							<td><?=$rn?></td>
							<td><img src="<?=$g_base_dir?>/upload_data/board/simg/<?=$THUMB_IMG?>" width="120"></td>
							<td><?= $OAADDR?></td>
							<td><?=$SUBJECT?></td>
							<td><?= $TXT?></td>
							<td><?= $INSERT_TIME?></td>
							<td><a href="javascript:js_toggle('<?=$SEQ_NO?>','<?=$USE_TF?>');"><?= $STR_USE_TF ?></a></td>
						</tr>

				<?			
						}
					} else { 
				?> 
						<tr>
							<td align="center" height="50" colspan="7">데이터가 없습니다. </td>
						</tr>
				<? 
					}
				?>
					</tbody>
				</table>
			</fieldset>
			</form>
			<div class="btnArea">
				<ul class="fRight">
					<?	if ($sPageRight_D == "Y") { ?>
					<li><a href="javascript:js_delete();"><img src="../images/btn/btn_delete.gif" alt="삭제" /></a></li>
					<?  } ?>
				</ul>
			</div>

			<div id="bbspgno">
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
			</div>

<form id="searchBar" name="frm_search" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="seq_no" value="">
<input type="hidden" name="use_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="nPage" value="<?=(int)$nPage?>">
<input type="hidden" name="nPageSize" value="<?=(int)$nPageSize?>">

				<fieldset>
					<legend>검색창</legend>
					<select name="search_field" id="kind">
						<option value="OAADDR" <? if ($search_field == "OAADDR") echo "selected"; ?> >발신휴대전화번호</option>
						<option value="TXT" <? if ($search_field == "TXT") echo "selected"; ?> >내용</option>
					</select>
					<input type="text" value="<?=$search_str?>" name="search_str" class="txt" />
					<a href="javascript:js_search();"><img src="../images/admin/btn_search.gif" class="sch" alt="Search" /></a>
				</fieldset>
			</form>
		</section>
	</section>
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
