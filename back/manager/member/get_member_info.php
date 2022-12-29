<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
# =============================================================================
# File Name    : member_read.php
# Modlue       : 
# Writer       : GIRINGRIM 
# Create Date  : 2016-04-06
# Modify Date  : 
#	Copyright : Copyright @GIRINGRIM.Com. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "MB002"; // 메뉴마다 셋팅 해 주어야 합니다

#	$sPageRight_		= "Y";
#	$sPageRight_R		= "Y";
#	$sPageRight_I		= "Y";
#	$sPageRight_U		= "Y";
#	$sPageRight_D		= "Y";
#	$sPageRight_F		= "Y";	
	
#====================================================================
# common_header Check Session
#====================================================================
	require "../../_common/common_header.php"; 
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtilResize.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/member/member.php";
	require "../../_classes/biz/group/group.php";
	
	$m_no = trim($m_no);

	$arr_rs = selectMember($conn, $m_no);
	
	$rs_m_no						= trim($arr_rs[0]["M_NO"]); 
	$rs_m_id						= trim($arr_rs[0]["M_ID"]); 
	$rs_m_name					= trim($arr_rs[0]["M_NAME"]); 
	$rs_m_jumin					= trim($arr_rs[0]["M_JUMIN"]);
	$rs_m_email					= trim($arr_rs[0]["M_EMAIL"]); 
	$rs_m_password_q		= trim($arr_rs[0]["M_PASSWORD_Q"]); 
	$rs_m_password_a		= trim($arr_rs[0]["M_PASSWORD_A"]); 
	$rs_m_birth					= trim($arr_rs[0]["M_BIRTH"]); 
	$rs_m_tel						= trim($arr_rs[0]["M_TEL"]); 
	$rs_m_hp						= trim($arr_rs[0]["M_HP"]); 
	$rs_m_sex						= trim($arr_rs[0]["M_SEX"]); 
	$rs_m_zip1					= trim($arr_rs[0]["M_ZIP1"]); 
	$rs_m_addr1					= trim($arr_rs[0]["M_ADDR1"]); 
	$rs_m_addr2					= trim($arr_rs[0]["M_ADDR2"]); 
	$rs_sido						= trim($arr_rs[0]["SIDO"]); 
	$rs_sigungu					= trim($arr_rs[0]["SIGUNGU"]); 
	$rs_m_mailling			= trim($arr_rs[0]["M_MAILLING"]); 
	$rs_m_sms						= trim($arr_rs[0]["M_SMS"]); 
	$rs_m_nick_date			= trim($arr_rs[0]["M_NICK_DATE"]); 
	$rs_m_level					= trim($arr_rs[0]["M_LEVEL"]); 
	$rs_m_signature			= trim($arr_rs[0]["M_SIGNATURE"]); 
	
	$rs_job							= trim($arr_rs[0]["M_1"]);
	$rs_com_name				= trim($arr_rs[0]["M_2"]);
	$rs_party						= trim($arr_rs[0]["M_3"]);
	$rs_group						= trim($arr_rs[0]["M_4"]);

	$rs_is_pay					= trim($arr_rs[0]["M_5"]);
	$rs_pay_type				= trim($arr_rs[0]["M_6"]);
	$rs_cms_info01			= trim($arr_rs[0]["M_7"]);
	$rs_cms_info02			= trim($arr_rs[0]["M_8"]);
	$rs_cms_info03			= trim($arr_rs[0]["M_9"]);
	$rs_cms_info04			= trim($arr_rs[0]["M_10"]);
	$rs_cms_info05			= trim($arr_rs[0]["M_11"]);
	$rs_cms_amount			= trim($arr_rs[0]["M_12"]);

	$rs_cms_flag				= trim($arr_rs[0]["CMS_FLAG"]);
	$rs_send_flag				= trim($arr_rs[0]["SEND_FLAG"]);
	$rs_cms_result			= trim($arr_rs[0]["CMS_RESULT"]);
	$rs_cms_result_msg	= trim($arr_rs[0]["CMS_RESULT_MSG"]);

	$rs_m_organization	= trim($arr_rs[0]["M_ORGANIZATION"]);

	$rs_m_o_zip					= trim($arr_rs[0]["M_O_ZIP"]); 
	$rs_m_o_addr1				= trim($arr_rs[0]["M_O_ADDR1"]); 
	$rs_m_o_addr2				= trim($arr_rs[0]["M_O_ADDR2"]); 

	$strJumin = decrypt($key, $iv, $rs_m_jumin);
	$arrJumin = explode("-",$strJumin);

	$str_m_tel = decrypt($key, $iv, $rs_m_hp);
	$arr_m_tel = explode("-",$str_m_tel);

	$str_tel = decrypt($key, $iv, $rs_m_tel);
	$arr_tel = explode("-",$str_tel);
	
	$arr_m_email = explode("@",$rs_m_email);

	$rs_m_birth_yy = left($rs_m_birth,4);
	$rs_m_birth_mm = right(left($rs_m_birth,6),2);
	$rs_m_birth_dd = right($rs_m_birth,2);

	$rs_cms_info01 = decrypt($key, $iv, $rs_cms_info01);
	$rs_cms_info02 = decrypt($key, $iv, $rs_cms_info02);
	$rs_cms_info03 = decrypt($key, $iv, $rs_cms_info03);
	$rs_cms_info04 = decrypt($key, $iv, $rs_cms_info04);
	$rs_cms_info05 = decrypt($key, $iv, $rs_cms_info04);

	if ($rs_pay_type == "mobile") {
		$arr_pay_mtel			= explode("-", $rs_cms_info01);
		$rs_mobile_com		= $rs_cms_info02;
	}

	if ($rs_pay_type == "cms") {
		$rs_bank_code		= $rs_cms_info01;
		$rs_bank_no			= $rs_cms_info02;
		$rs_bank_name		= $rs_cms_info03;
	}

	if ($rs_pay_type == "card") {
		$rs_card_code = $rs_cms_info01;
		$rs_card_no		= $rs_cms_info02;
		$rs_card_yy		= $rs_cms_info03;
		$rs_card_mm		= $rs_cms_info04;
	}

	if($rs_m_organization){
		$arr_rs_Group = selectGroupAsGroupCD($conn, $rs_m_organization);
		$Group_nm		= trim($arr_rs_Group[0]["GROUP_NAME"]); 
	} else {
		$Group_nm = "없음";
	}

	if ($rs_m_sex == "M") {
		$str_sex = "남자";
	} else {
		$str_sex = "여자";
	}
	
	if ($rs_m_no <> "") {
?>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
					<tbody>
						<tr>
							<th>이름</td>
							<td><?= $rs_m_name ?></td>
							<th>주민등록번호</td>
							<td><?= $arrJumin[0] ?> -<?= $arrJumin[1] ?></td>
						</tr>

						<tr>
							<th>휴대폰번호</th>
							<td><?=$arr_m_tel[0]?> - <?=$arr_m_tel[1]?> - <?=$arr_m_tel[2]?></td>
							<th>연락처</th>
							<td><?=$arr_tel[0]?> - <?=$arr_tel[1]?> - <?=$arr_tel[2]?></td>
						</tr>

						<tr>
							<th>성별</th>
							<td>
								<? if ($rs_m_sex == "1") { echo "남"; } ?>
								<? if ($rs_m_sex == "2") { echo "여"; } ?>
							</td>

							<th>생년월일</th>
							<td>
								<?=$rs_m_birth_yy?> 년 <?=$rs_m_birth_mm?> 월 <?=$rs_m_birth_dd?> 일
							</td>
						</tr>
						<tr>
							<th>주소</th>
							<td colspan="3">
								[<?=$rs_m_zip1?>] <?=$rs_m_addr1?> <?=$rs_m_addr2?>
							</td>
						</tr>

						<tr>
							<th>소속당</th>
							<td>
								<?=getDcodeName($conn,"PARTY", $rs_party)?>
							</td>
							<th>조직 (지역)</th>
							<td>
								<?=$Group_nm?>
							</td>
						</tr>


						<tr>
							<th>지역</th>
							<td>
								<?=$rs_sido?>
							</td>
							<th>소속단체</th>
							<td>
								<?=$rs_group?>
							</td>
						</tr>

						<tr>
							<th>직장주소</th>
							<td colspan="3">
								[<?=$rs_m_o_zip?>] <?=$rs_m_o_addr1?> <?=$rs_m_o_addr2?>
							</td>
						</tr>

						<tr class="end">
							<th>당비약정여부</th>
							<td>
								<? if ($rs_is_pay == "Y") { ?>약정<? } ?>
								<? if ($rs_is_pay == "N") { ?>미약정<? } ?>
								&nbsp;
								<? if ($rs_pay_type == "cms") { ?>[CMS]<? } ?>
								<? if ($rs_pay_type == "card") { ?>[신용카드]<? } ?>
								<? if ($rs_pay_type == "mobile") { ?>[휴대폰]<? } ?>
								<? if ($rs_pay_type == "cash") { ?>[직접납부]<? } ?>
							</td>
							<th>약정당비</th>
							<td>
								<?=number_format($rs_cms_amount)?>
							</td>
						</tr>
					</table>

<?
	} else {
?>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
					<tbody>
						<tr>
							<th>이름</td>
							<td>&nbsp;</td>
							<th>주민등록번호</td>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<th>휴대폰번호</th>
							<td>&nbsp;</td>
							<th>연락처</th>
							<td>&nbsp;</td>
						</tr>

						<tr>
							<th>성별</th>
							<td>
								&nbsp;
							</td>

							<th>생년월일</th>
							<td>
								&nbsp; 년 &nbsp; 월 &nbsp; 일
							</td>
						</tr>
						<tr>
							<th>주소</th>
							<td colspan="3">
								&nbsp;
							</td>
						</tr>

						<tr>
							<th>소속당</th>
							<td>&nbsp;
							</td>
							<th>소속단체</th>
							<td>
								&nbsp;
							</td>
						</tr>

						<tr class="end">
							<th>당비약정여부</th>
							<td>
								&nbsp;
							</td>
							<th>약정당비</th>
							<td>
								&nbsp;
							</td>
						</tr>
					</table>
<?
	}

	mysql_close($conn);
?>