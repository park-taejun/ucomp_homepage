<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : partner_read.php
# Modlue       : 
# Writer       : Park Tae Jun
# Create Date  : 2022-11-14
# Modify Date  : 
#	Copyright    : Copyright @Ucom Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "PT001"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";

#====================================================================
# common_header Check Session
#====================================================================
	include "../../_common/common_header.php"; 

	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/partner/partner.php";

	$mm_subtree	 = "4";
#====================================================================
# DML Process
#====================================================================
	
	// $arr_rs = listClickCnt($conn, $story_no);
	
	if ($mode == "S") {
		$arr_rs = selectPartner($conn, $partner_no);
		
		$rs_partner_no				= trim($arr_rs[0]["PARTNER_NO"]); 
		$rs_partner_nm				= trim($arr_rs[0]["PARTNER_NM"]);
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
		$rs_up_date					= trim($arr_rs[0]["UP_DATE"]);		
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 		
		$rs_contents				= trim($arr_rs[0]["CONTENTS"]); 
		$rs_down_img				= trim($arr_rs[0]["DOWN_IMG"]); 
		$rs_down_real_img			= trim($arr_rs[0]["DOWN_REAL_IMG"]); 		
		$rs_up_img					= trim($arr_rs[0]["UP_IMG"]); 
		$rs_up_real_img				= trim($arr_rs[0]["UP_REAL_IMG"]);
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		$rs_portfolio_nm			= trim($arr_rs[0]["PORTFOLIO_NM"]);
		
		if ($rs_use_tf == "Y") {
			$STR_USE_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_USE_TF = "<font color='red'>사용 안함</font>";
		}
		 
		   
	}

?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title><?=$g_title_name?></title>
<meta content="<?=$g_title_name?>" name="keywords" />
<?
	require "../../_common/common_script.php";
?>
<script type="text/javascript">

	function js_list() {
		var frm = document.frm;
			
		frm.method = "post";
		frm.action = "partner_list.php";
		frm.submit();
	}

	function js_modify() {
		var frm = document.frm;

		frm.mode.value = "S";
		frm.method = "post";
		frm.action = "partner_modify.php";
		frm.submit();
	}
	
	function js_imgDel(partner_no, img_gubun){
		
		frm.img_gubun.value = img_gubun;
		
		var frm = document.frm;
		
		bDelOK = confirm('선택하신 이미지를 삭제 하시겠습니까?');
			
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "partner_ImgModify.php";
			frm.submit();
		}
		
	}

</script>
</head>
<body>

<div id="wrap">
<?
	#====================================================================
	# common left_area
	#====================================================================

	require "../../_common/left_area.php";
?>
	<!-- S: container -->
	<div class="container">
		<div class="contentsarea">
			<div class="menu-holiday">
				<h3><strong><?=$p_menu_name?></strong>
				</h3>

<form name="frm" method="post">
<input type="hidden" name="partner_no" value="<?=$rs_partner_no?>" />
<input type="hidden" name="img_gubun"  />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />
<input type="hidden" name="search_field" value="<?=$search_field?>">
<input type="hidden" name="search_str" value="<?=$search_str?>">

				<div class="boardlist search">
				
					<table>
						<colgroup>
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:23%" />
							<col style="width:10%" />
							<col style="width:24%" />
						</colgroup>
						<tbody>
							<tr>
								<th>제목</th>
								<td colspan="5" class="subject"><?=$rs_partner_nm?></td>								
							</tr> 
							<tr>
								<th>순번</th>
								<td colspan="5" class="subject"><?=$rs_disp_seq?></td>
							</tr>							
							<tr>
								<th>사용여부</th>
								<td colspan="5" class="subject"><?=$STR_USE_TF?></td>
							</tr>	
							<!--							
								<tr>
									<th>본문</th>
									<td colspan="5">
										<div style="float:left;width:70%;">
											<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" readonly><?=$rs_contents?></textarea>
										</div>
									</td>
								</tr> 
							-->
							<tr>
								<th>흑백 로고<br>(160*56)</th>
								<td colspan="5" class="subject">
									<!--<?=$rs_down_real_img?><br />-->
									<?
									if (strlen($rs_down_real_img) > 3) {
									?>
										<img src="/upload_data/partner/<?=$rs_down_img?>" width="200" height="200" onmouseover="this.src='/upload_data/partner/<?=$rs_up_img?>'" onmouseout="this.src='/upload_data/partner/<?=$rs_down_img?>'" >
										<input type="hidden" name="old_down_img" value="<?=$rs_down_img?>">
										<!--<img src='/admin/images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' onclick="js_imgDel('<?=$partner_no?>','1');" />이미지삭제-->
									<?
										} else {	
									?>	
										<!--
										<input type="file" size="40%" name="story_img">
										<input type="hidden" name="old_story_img" value="">
										-->
									<?
										}
									?>
								</td> 
							</tr>  
							<tr>
								<th>컬러 로고<br>(160*56)</th>
								<td colspan="5" class="subject">
									<!--<?=$rs_up_real_img?><br />-->
									<?
									if (strlen($rs_up_real_img) > 3) {
									?>
										<!--<img src="/upload_data/partner/<?=$rs_up_img?>" width="200" height="200" >-->
										<img src="/upload_data/partner/<?=$rs_up_img?>" width="200" height="200" onmouseover="this.src='/upload_data/partner/<?=$rs_down_img?>'" onmouseout="this.src='/upload_data/partner/<?=$rs_up_img?>'" >
										<input type="hidden" name="old_up_img" value="<?=$rs_up_img?>">
										<!--<img src='/admin/images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' onclick="js_imgDel('<?=$partner_no?>','2');" />이미지삭제-->
									<?
										} else {	
									?>	
										<!--
										<input type="file" size="40%" name="overview_img">
										<input type="hidden" name="old_overview_img" value="">
										-->
									<?
										}
									?>
								</td>  
							</tr>	
							<tr>
								<th>대표 포트폴리오</th>
								<td colspan="5" class="subject"><?=$rs_portfolio_nm?></td>								
							</tr> 							
						</tbody>
					</table>
				</div>
				<div class="btnright">
				<? if ($rs_partner_no <> "" ) {?>
					<? //if ($sPageRight_U == "Y") {?>
					<button type="button" class="btn-navy" onClick="js_modify();" style="width:100px">수정</button>
					<? //} ?>
				<? }?>
					<button type="button" class="btn-navy" onClick="js_list();" style="width:100px">목록</button>
				</div>
  
			<!-- // E: mwidthwrap -->

			</div>
		</div>
	</div>

	<!-- //S: footer -->
<?
	require "../../_common/common_footer.php";
?>
	<!-- //E: footer -->

</div>
  
<script type="text/javascript" src="/admin/js/common_ui.js"></script>
</body>
</html>

<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>