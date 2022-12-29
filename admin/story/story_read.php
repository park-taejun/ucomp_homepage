<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');
# =============================================================================
# File Name    : story_read.php
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
	$menu_right = "CO003"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/biz/story/story.php";

	$mm_subtree	 = "4"; 
#====================================================================
# DML Process
#====================================================================
	
	$arr_rs = listClickCnt($conn, $story_no);
	
	if ($mode == "S") {
		$arr_rs = selectStory($conn, $story_no);
		
		$rs_story_type				= trim($arr_rs[0]["STORY_TYPE"]); 
		$rs_story_nm				= trim($arr_rs[0]["STORY_NM"]);
		$rs_reg_date				= trim($arr_rs[0]["REG_DATE"]);
		$rs_up_date					= trim($arr_rs[0]["UP_DATE"]);
		$rs_click_cnt				= trim($arr_rs[0]["CLICK_CNT"]);
		$rs_use_tf					= trim($arr_rs[0]["USE_TF"]); 
		$rs_main_tf					= trim($arr_rs[0]["MAIN_TF"]); 
		$rs_contents				= trim($arr_rs[0]["CONTENTS"]); 
		$rs_story_img				= trim($arr_rs[0]["STORY_IMG"]); 
		$rs_story_real_img			= trim($arr_rs[0]["STORY_REAL_IMG"]); 		
		$rs_overview_img				= trim($arr_rs[0]["OVERVIEW_IMG"]); 
		$rs_overview_real_img			= trim($arr_rs[0]["OVERVIEW_REAL_IMG"]); 		
		$rs_story_no				= trim($arr_rs[0]["STORY_NO"]); 
		$rs_disp_seq				= trim($arr_rs[0]["DISP_SEQ"]); 
		 
		if ($rs_use_tf == "Y") {
			$STR_USE_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_USE_TF = "<font color='red'>사용 안함</font>";
		}
		
		if ($rs_main_tf == "Y") {
			$STR_MAIN_TF = "<font color='navy'>사용</font>";
		} else {
			$STR_MAIN_TF = "<font color='red'>사용 안함</font>";
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
		frm.action = "story_list.php";
		frm.submit();
	}

	function js_modify() {
		var frm = document.frm;

		frm.mode.value = "S";
		frm.method = "post";
		frm.action = "story_modify.php";
		frm.submit();
	}
	
	function js_imgDel(story_no, img_gubun){
		 
		var frm = document.frm;
		
		frm.img_gubun.value = img_gubun;
		
		bDelOK = confirm('선택하신 이미지를 삭제 하시겠습니까?');
			
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.target = "";
			frm.action = "story_ImgModify.php";
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
<input type="hidden" name="story_no" value="<?=$rs_story_no?>" />
<input type="hidden" name="img_gubun"  />
<input type="hidden" name="mode" value="" />
<input type="hidden" name="nPage" value="<?=$nPage?>" />
<input type="hidden" name="nPageSize" value="<?=$nPageSize?>" />

<input type="hidden" name="con_eq_type" value="<?=$con_eq_type?>">
<input type="hidden" name="con_eq_user" value="<?=$con_eq_user?>">
<input type="hidden" name="con_eq_state" value="<?=$con_eq_state?>">
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
								<th>스토리 타입</th>
								<td colspan="5" class="subject">
									<?= getDcodeName($conn, "STORY_TYPE", $rs_story_type); ?>
								</td>								 
							</tr> 
							<tr>
								<th>제목</th>
								<td colspan="5" class="subject"><?=$rs_story_nm?></td>								
							</tr> 
							<tr>
								<th>순번</th>
								<td colspan="5" class="subject"><?=$rs_disp_seq?></td>
							</tr>
							<tr>
								<th>조회수</th>
								<td colspan="5" class="subject"><?=$rs_click_cnt?></td>
							</tr>
							<tr>
								<th>사용여부</th>
								<td colspan="5" class="subject"><?=$STR_USE_TF?></td>
							</tr>
							<tr>
								<th>메인여부</th>
								<td colspan="5" class="subject"><?=$STR_MAIN_TF?></td>
							</tr>
							<tr>
								<th>본문</th>
								<td colspan="5">
									<div style="float:left;width:70%;">
										<textarea style="width:100%; height:205px" name="p_contents" id="p_contents" readonly><?=$rs_contents?></textarea>
									</div>
								</td>
							</tr> 
							<tr>
								<th>썸네일<br>(586*450)</th>
								<td colspan="5" class="subject">
									<!--<?=$rs_story_real_img?><br />-->
									<?
									if (strlen($rs_story_real_img) > 3) {
									?>
									
										<img src="/upload_data/story/<?=$rs_story_img?>" width="200" height="200">
										<input type="hidden" name="old_story_img" value="<?=$rs_story_img?>">
										<!--<img src='/admin/images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' onclick="js_imgDel('<?=$story_no?>','1');" />파일삭제-->
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
							<?								
								$arr_rs_file	= listStoryFile($conn, $rs_story_no);
							?>
							<?
								if (sizeof($arr_rs_file) > 0) {
									for ($j=0 ; $j < sizeof($arr_rs_file); $j++) {
										$RS_FILE_NO			= trim($arr_rs_file[$j]["FILE_NO"]);
										$RS_FILE_NM			= trim($arr_rs_file[$j]["FILE_NM"]);
										$RS_FILE_RNM		= trim($arr_rs_file[$j]["FILE_RNM"]);
							?>
										<tr>
											<th scope="row">
												 
												첨부파일_<?echo $j;?>
												<!--
												<img src="../images/btn_plus.gif" alt="파일추가"  style="cursor:pointer" class="btn_add_file" />
												<img src='../images/btn_minus.gif' alt='파일삭제'  style='cursor:pointer' class='btn_del_file' />
												-->
											</th>
											<td colspan="5">
												<img src="/upload_data/story/<?=$RS_FILE_NM?>" width="310" >									
												<!--<input type="file" size="40%" name="file_name[]">-->
												<input type="hidden" name="old_file_name[]" value="<?=$RS_FILE_NM?>">
												<input type="hidden" name="old_file_rname[]" value="<?=$RS_FILE_RNM?>">
											</td>
										</tr>
							<?
									}
								} else { 
							?>
										<tr>
											<th scope="row">첨부파일</th>
											<td colspan="5">
												<!--<input type="file" size="40%" name="file_name[]">-->
												<input type="hidden" name="old_file_name[]" value="">
												<input type="hidden" name="old_file_rname[]" value="">
											</td>
										</tr>
							<?
								}
							?>							
						</tbody>
					</table>
				</div>
				<div class="btnright">
				<? if ($rs_story_no <> "" ) {?>
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