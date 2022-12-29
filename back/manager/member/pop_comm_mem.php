<?session_start();?>
<?
# =============================================================================
# File Name    : pop_comm_mem.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2012.05.22
# Modify Date  : 
#	Copyright : Copyright @기린그림 Corp. All Rights Reserved.
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
	require "../../../_classes/biz/member/member.php";
	require "../../../_classes/community/ccommunity/community.php";
	require "../../../_classes/community/cadmin/admin.php";

#====================================================================
# Request Parameter
#====================================================================

	$comm_no = Trim($comm_no);
	$mode = Trim($mode);
	
	$arr_rs = selectCcommunity($conn, $comm_no);

	$comm_name = trim($arr_rs[0]["COMM_NAME"]);

	if($mode == "I") {

		$use_tf	= "Y";
		//$mem_type = "90";

		$row_cnt = count($mem_type);

		for ($k = 0; $k < $row_cnt; $k++) {
			
			$tmp_is_chk = $is_chk[$k];
			$tmp_mem_no = $mem_no[$k];
			$tmp_mem_type = $mem_type[$k];

			//echo $tmp_is_chk."<br>";
			//echo $tmp_mem_no."<br>";
			//echo $tmp_mem_type."<br>";

			if (($tmp_is_chk == "Y") && ($tmp_mem_type <> "")) {
				$result = insertCommunityMember($conn, $comm_no, $tmp_mem_no, $tmp_mem_type, $use_tf, $s_com_adm_no);
			}
			//
		}
	}

#====================================================================
# Declare variables
#====================================================================

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
		$nPageSize = 100;
	}

	$nPageBlock	= 10;

#====================================================================
# Get Result set from stored procedure
#====================================================================

	$use_tf = "Y";
	$del_tf = "N";

	$nListCnt =totalCntMember($conn, '', $email_tf, $use_tf, $del_tf, $search_field, $search_str);
	
	$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

	if ((int)($nTotalPage) < (int)($nPage)) {
		$nPage = $nTotalPage;
	}

	if ($search_str) {
		$arr_rs_mem = listMember($conn, '', $email_tf, $use_tf, $del_tf, $search_field, $search_str, $nPage, $nPageSize);
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$s_comm_name?> 관리자 로그인</title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<script type="text/javascript" src="../js/common.js"></script>

<script type="text/javascript" >
	// 조회 버튼 클릭 시 
	function js_search() {
		var frm = document.frm;

		if (frm.search_str.value.length >=2) {
		
			frm.nPage.value = "1";
			frm.method = "get";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		} else {
			alert("검색어는 2자 이상 입력하세요");
		}
	}
	
	function js_mem_reg() {
		var frm = document.frm;
		var chk_cnt = 0;

		check=document.getElementsByName("chk[]");
		is_check=document.getElementsByName("is_chk[]");

		for (i=0;i<check.length;i++) {
			if(check.item(i).checked==true) {
				chk_cnt++;
			}
		}
		if (chk_cnt == 0) {
			alert("선택 하신 회원이 없습니다.");
		} else {

			if (check.length == 1) {
				//if (check.checked==true) {
					//alert("check");
					frm['is_chk[]'].value ="Y";
				//}
			} else {
				for (i=0;i<check.length;i++) {
					if(check.item(i).checked==true) {
						frm['is_chk[]'][i].value = "Y";
					}
				}
			}

			frm.mode.value = "I";
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}

	function js_del_adm(seq_no) {
		var frm = document.frm;
		bDelOK = confirm('선택하신 관리자를 삭제 하시겠습니까?');
		if (bDelOK) {
			frm.mode.value = "D";
			frm.seq_no.value = seq_no;
			frm.target = "";
			frm.action = "<?=$_SERVER[PHP_SELF]?>";
			frm.submit();
		}
	}
	
	<?
		if ($result) {
	?>
		opener.document.location = "member_list.php?menu_cd=<?=$menu_cd?>";
	<?
		}
	?>
</script>
</head>
<body id="popup_order">

<form name="frm" method="post" action="javascript:check_data();">
<input type="hidden" name='mode' value=''>
<input type="hidden" name='seq_no' value=''>
<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
<input type="hidden" name="nPage" value="<?=$nPage?>">
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">
<input type="hidden" name='comm_no' value='<?=$comm_no?>'>

<div id="popupwrap_order">
	<h1><?=$p_menu_name?></h1>
	<div id="postsch_code">
		<div class="addr_inp">
			<div class="sp10"></div>
			<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
				<colgroup>
					<col width="20%">
					<col width="80%">
				</colgroup>
				<thead>
				<tr>
					<th>커뮤니티 명</th>
					<td>
						<?=$comm_name?>
					</td>
				</tr>
				</thead>
			</table>
		</div>
		<h2> 회원 등록</h2>
		<div class="addr_inp">
			<div class="btnright">
				<select name="search_field" style="width:84px;">
					<option value="MEM_NM" <? if ($search_field == "MEM_NM") echo "selected"; ?> >이름</option>
					<option value="MEM_ID" <? if ($search_field == "MEM_ID") echo "selected"; ?> >아이디</option>
					<option value="MEM_NICK" <? if ($search_field == "MEM_NICK") echo "selected"; ?> >닉네임</option>
				</select>&nbsp;
				<input type="text" value="<?=$search_str?>" name="search_str" size="15" class="txt" />
				<a href="javascript:js_search();"><img src="/manager/images/admin/btn_search_post.gif" alt="검색"/></a>
				<a href="javascript:js_mem_reg();"><img src="/manager/images/admin/btn_regist_02.gif" alt="회원등록"/></a>
			</div>
			<div class="category_choice">
				&nbsp;
			</div>
			<br>
			<table cellpadding="0" class="rowstable" cellspacing="0" border="0" width="100%">
				<colgroup>
					<col width="5%" />
					<col width="20%" />
					<col width="10%" />
					<col width="10%" />
					<col width="10%" />
					<col width="15%" />
					<col width="15%" />
					<col width="15%" />
				</colgroup>
				<thead>
					<tr>
						<th>&nbsp;</th>
						<th>회원구분</th>
						<th>당원여부</th>
						<th>회원명</th>
						<th>아이디</th>
						<th>닉네임</th>
						<th>연락처</th>
						<th class="end">생년월일</th>
					</tr>
				</thead>
				<tbody>
				<?

					if (sizeof($arr_rs_mem) > 0) {
						
						for ($j = 0 ; $j < sizeof($arr_rs_mem); $j++) {
				
							$MEM_NO					= trim($arr_rs_mem[$j]["MEM_NO"]);
							$MEM_TYPE				= trim($arr_rs_mem[$j]["MEM_TYPE"]);
							$MEM_ID					= trim($arr_rs_mem[$j]["MEM_ID"]);
							$MEM_NM					= trim($arr_rs_mem[$j]["MEM_NM"]);
							$MEM_NICK				= trim($arr_rs_mem[$j]["MEM_NICK"]);
							$HPHONE					= trim($arr_rs_mem[$j]["HPHONE"]);
							$BIRTH_DATE			= trim($arr_rs_mem[$j]["BIRTH_DATE"]);
							$REG_DATE				= trim($arr_rs_mem[$j]["REG_DATE"]);

							$REG_DATE = date("Y-m-d",strtotime($REG_DATE));
							
				?>
				<tr>
					<td class="sort">
						<input type="checkbox" name="chk[]" value="Y">
					</td>
					<td>
						<?= makeSelectBoxWithCondition($conn, "COMM_RIGHT" ,"mem_type[]" ,"350px" , "회원구분선택" , "", $mem_type, "AND DCODE > 20");?>
						<input type="hidden" name="mem_no[]" value="<?=$MEM_NO?>">
						<input type="hidden" name="is_chk[]" value="">
					</td>
					<td><?=$MEM_TYPE?></td>
					<td><?=$MEM_NM?></td>
					<td><?=$MEM_ID?></td>
					<td><?=$MEM_NICK?></td>
					<td><?=$HPHONE?></td>
					<td><?=$BIRTH_DATE?></td>
				</tr>
				<?
						}
					} else {
				?>
				<tr align="center" bgcolor="#FFFFFF">
					<td height="40" colspan="12">조건에 맞는 회원이 없습니다. 검색을 통해 등록하실 회원을 조회하시기 바랍니다.</td>
				</tr>
				<?
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
	<br />
	<br />
</div>
<iframe src="" name="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
</form>
</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>