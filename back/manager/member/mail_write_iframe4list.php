<?session_start();?>
<?
header('Content-Type: text/html; charset=utf-8');
# =============================================================================
# File Name    : member_list.php
# Modlue       : 
# Writer       : GIRINGRIM 
# Create Date  : 2011.01.19
# Modify Date  : 
#	Copyright : Copyright @GIRINGRIM.Com. All Rights Reserved.
# =============================================================================


#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$sPageMenu_CD = trim($menu_cd); // 메뉴마다 셋팅 해 주어야 합니다

#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../../_common/config.php";
	require "../../../_classes/community/util/util.php";
	require "../../../_classes/community/etc/etc.php";
	require "../../../_classes/community/cadmin/admin.php";


	if ($mode == "D") {
		$row_cnt = count($chk);
		
		for ($k = 0; $k < $row_cnt; $k++) {
		
			$tmp_m_no = $chk[$k];

			//$result = deleteMember($conn, $tmp_m_no, "@관리자삭제@", $s_adm_no);
		}
	}

#====================================================================
# Request Parameter
#====================================================================

	$mm_subtree	 = "7";

	#List Parameter
	$nPage			= trim($nPage);
	$nPageSize	= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);
	
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
		$nPageSize = 10;
	}

	$nPageBlock	= 10;
	//$use_tf = "Y";
	$del_tf = "N";
	if(trim($con_mem_type)=="")$con_mem_type = "";
#===============================================================
# Get Search list count
#===============================================================

	$nListCnt =totalCntCommunityMember ($conn, (int)$comm_no, $con_mem_type, $use_tf, $del_tf, $search_field, $search_str);

	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}
	
	//echo $nPage;
	$arr_rs = listCommunityMember($conn, (int)$comm_no, $con_mem_type, $con_use_tf, $con_del_tf, $search_field, $search_str, $order_field, $order_str, $nPage, $nPageSize);

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; chaset=<?=$g_chrset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="/manager/css/admin.css" type="text/css" />
 <style type="text/css">
body#admin { width: 500px; min-width: 500px; }
table td.contentarea { padding: 25px 0 0 10px; }
.paging { width: 500px; }
.bottom_search { height: 51px; }
</style>
<script language="javascript" type="text/javascript" src="../js/common.js"></script>

<script language="javascript">

	function js_write() {
		//document.location.href = "member_write.php";
	}

	function js_setview(rn, nm, mail) {

		parent.js_setview(nm, mail);
		
	}

	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;
		
		frm.nPage.value = "1";
		frm.method = "get";
		frm.action = "<?=$_SERVER[PHP_SELF]?>";
		frm.submit();
	}




</script>
</head>

<body id="admin" style="background-image:url()">

<form name="frm" method="post" action="javascript:js_search();">
<input type="hidden" name="rn" value="">
<input type="hidden" name="m_no" value="">
<input type="hidden" name="confirm_tf" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSizexxxxx" value="<?=$nPageSize?>">
<input type="hidden" name="con_mem_type" value="<?=$con_mem_type?>">

<div id="adminwrap" style="background-image:url();width:500px">

<?
	#====================================================================
	# common top_area
	#====================================================================

	//require "../../_common/top_area.php";
?>

	<table width="100%" cellpadding="0" cellspacing="0" style="width:500px">
	<colgroup>
		<col width="180" />
		<col width="*" />
	</colgroup>
	<tr>
		<td class="leftarea" style="display:none;background-image:url()">
<?
	#====================================================================
	# common left_area
	#====================================================================

	//require "../../_common/left_area.php";
?>


		</td>
		<td class="contentarea">

      <!-- S: mwidthwrap -->
      <div id="mwidthwrap">

        <h2><?=$mem_type?> 목록</h2>
        <table cellpadding="0" cellspacing="0" class="rowstable" style="width:490px">
					<colgroup>
						<col width="10%" />
						<col width="20%" />
						<col width="20%" />
						<col width="20%" />
						<col width="30%" />
					</colgroup>
						<tr>
							<th scope="col">NO.</th>
							<th scope="col">성명</th>
							<th scope="col">아이디</th>
							<th scope="col">닉네임</th>
							<th class="end" scope="col">이메일</th>
						</tr>
							<?
								$nCnt = 0;
								
								if (sizeof($arr_rs) > 0) {
									
									for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
																				
										$rn							= trim($arr_rs[$j]["rn"]);
										$MEM_NO						= trim($arr_rs[$j]["MEM_NO"]);
										$MEM_NM						= trim($arr_rs[$j]["MEM_NM"]);
										$MEM_ID						= trim($arr_rs[$j]["MEM_ID"]);
										$MEM_TYPE					= trim($arr_rs[$j]["MEM_TYPE"]);
										$MEM_NICK					= trim($arr_rs[$j]["MEM_NICK"]);
										$EMAIL						= trim($arr_rs[$j]["EMAIL1"])."@".trim($arr_rs[$j]["EMAIL2"]);
										$SOSOK						= trim($arr_rs[$j]["SOSOK"]);
										$HPHONE						= trim($arr_rs[$j]["HPHONE"]);
										$REGDT						= trim($arr_rs[$j]["REG_DATE"]);
										$USE_TF 					= trim($arr_rs[$j]["USE_TF"]);
										$USE_TF_STR = "일시중지";
										if($USE_TF == "Y")$USE_TF_STR = "사용";

										$REG_DATE = date("Y-m-d",strtotime($REGDT));
							
							?>
								<tr>
									<td class="filedown"><?= $rn ?></td>
									<td><?= $MEM_NM ?></td>
									<td><a href="javascript:js_setview('<?= $rn ?>','<?=$MEM_NM?>','<?=$EMAIL?>');"><?= $MEM_ID ?></a></td>
									<td><?= $MEM_NICK ?></td>
									<td><?= $EMAIL ?></td>
								</tr>
							<?			
									}
								} else { 
							?> 
								<tr>
									<td height="50" align="center" colspan="5">데이터가 없습니다. </td>
								</tr>
							<? 
								}
							?>
						</table>
					<!-- --------------------- 페이지 처리 화면 START -------------------------->
					<?
						# ==========================================================================
						#  페이징 처리
						# ==========================================================================
						if (sizeof($arr_rs) > 0) {
							#$search_field		= trim($search_field);
							#$search_str			= trim($search_str);
							$strParam = $strParam."&mem_type=".$mem_type."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str;

					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
					<?
						}
					?>
					<!-- --------------------- 페이지 처리 화면 END -------------------------->
				<br />
				<div class="bottom_search" style="width:400px">
					<select name="nPageSize" style="width:144px;">
						<option value="10" <? if ($nPageSize == "10") echo "selected"; ?> >페이지당 10 개씩</option>
						<option value="20" <? if ($nPageSize == "20") echo "selected"; ?> >페이지당 20 개씩</option>
						<option value="30" <? if ($nPageSize == "30") echo "selected"; ?> >페이지당 30 개씩</option>
						<option value="40" <? if ($nPageSize == "40") echo "selected"; ?> >페이지당 40 개씩</option>
						<option value="50" <? if ($nPageSize == "50") echo "selected"; ?> >페이지당 50 개씩</option>
						<option value="100" <? if ($nPageSize == "100") echo "selected"; ?> >페이지당 100 개씩</option>
						<option value="200" <? if ($nPageSize == "200") echo "selected"; ?> >페이지당 200 개씩</option>
						<option value="300" <? if ($nPageSize == "300") echo "selected"; ?> >페이지당 300 개씩</option>
						<option value="400" <? if ($nPageSize == "400") echo "selected"; ?> >페이지당 400 개씩</option>
						<option value="500" <? if ($nPageSize == "500") echo "selected"; ?> >페이지당 500 개씩</option>

						<option value="1000" <? if ($nPageSize == "1000") echo "selected"; ?> >페이지당 1000 개씩</option>
						<option value="2000" <? if ($nPageSize == "2000") echo "selected"; ?> >페이지당 2000 개씩</option>
						<option value="3000" <? if ($nPageSize == "3000") echo "selected"; ?> >페이지당 3000 개씩</option>
						<option value="4000" <? if ($nPageSize == "4000") echo "selected"; ?> >페이지당 4000 개씩</option>
						<option value="5000" <? if ($nPageSize == "5000") echo "selected"; ?> >페이지당 5000 개씩</option>
					</select>
					<select name="search_field" style="width:124px;">
						
						<option value="ALL" <? if ($search_field == "ALL") echo "selected"; ?> >통합검색</option>
						<option value="A.MEM_NM" <? if ($search_field == "A.MEM_NM") echo "selected"; ?> >성명</option>
						<option value="A.MEM_NICK" <? if ($search_field == "A.MEM_NICK") echo "selected"; ?> >닉네임</option>
						<option value="A.MEM_ID" <? if ($search_field == "A.MEM_ID") echo "selected"; ?> >아이디</option>
						<option value="A.EMAIL" <? if ($search_field == "A.EMAIL") echo "selected"; ?> >이메일</option>
						<option value="A.ZIPCODE" <? if ($search_field == "A.ZIPCODE") echo "selected"; ?> >우편번호(-포함)</option>
						<option value="A.ADDR" <? if ($search_field == "A.ADDR") echo "selected"; ?> >주소</option>
						<option value="A.OZIPCODE" <? if ($search_field == "A.OZIPCODE") echo "selected"; ?> >직장우편번호(-포함)</option>
						<option value="A.OADDR" <? if ($search_field == "A.OADDR") echo "selected"; ?> >직장주소</option>
					</select>
					<input type="text" value="<?=$search_str?>" name="search_str" class="txt" style="width:230px"/>
					<a href="javascript:js_search();"><img src="../images/admin/btn_search.gif" class="sch" alt="Search" /></a>
				</div>      
			</div>
			<!-- // E: mwidthwrap -->

		</td>
	</tr>
	<tr>
		<td colspan="2" height="70"><div class="copyright"><!--img src="../images/admin/copyright.gif" alt="" /--></div></td>
	</tr>
	</table>
</div>
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
