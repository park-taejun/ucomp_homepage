<?session_start();?>
<?
header("Content-Type: text/html; charset=UTF-8"); 
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

#====================================================================
# Request Parameter
#====================================================================

	$mm_subtree	 = "7";

	#List Parameter
	$nPage				= trim($nPage);
	$nPageSize			= trim($nPageSize);

	$search_field		= trim($search_field);
	$search_str			= trim($search_str);

	$mode 				= trim($mode);
	$m_no				= trim($m_no);
	
	$result = false;
#====================================================================
# DML Process
#====================================================================


	if ($mode == "U") {

		$modify_memo = "";

		$arr_rs = selectMember($conn, $m_no);

		$rs_m_no						= trim($arr_rs[0]["M_NO"]); 
		$rs_m_name					= trim($arr_rs[0]["M_NAME"]); 
		$rs_m_jumin					= trim($arr_rs[0]["M_JUMIN"]);
		$rs_m_email					= trim($arr_rs[0]["M_EMAIL"]); 
		$rs_m_birth					= trim($arr_rs[0]["M_BIRTH"]); 
		$rs_m_tel						= trim($arr_rs[0]["M_TEL"]); 
		$rs_m_hp						= trim($arr_rs[0]["M_HP"]); 
		$rs_m_zip1					= trim($arr_rs[0]["M_ZIP1"]); 
		$rs_m_addr1					= trim($arr_rs[0]["M_ADDR1"]); 
		$rs_m_addr2					= trim($arr_rs[0]["M_ADDR2"]); 
		$rs_sido						= trim($arr_rs[0]["SIDO"]); 
		$rs_sigungu					= trim($arr_rs[0]["SIGUNGU"]); 
		$rs_job							= trim($arr_rs[0]["M_1"]);
		$rs_com_name				= trim($arr_rs[0]["M_2"]);
		$rs_party						= trim($arr_rs[0]["M_3"]);
		$rs_group						= trim($arr_rs[0]["M_4"]);

		$rs_m_sms						= trim($arr_rs[0]["M_SMS"]); 
		$rs_m_mailling			= trim($arr_rs[0]["M_MAILLING"]); 

		$rs_m_jumin = decrypt($key, $iv, $rs_m_jumin);
		$rs_m_tel		= decrypt($key, $iv, $rs_m_tel);
		$rs_m_hp		= decrypt($key, $iv, $rs_m_hp);

		$rs_cms_flag		= trim($arr_rs[0]["CMS_FLAG"]);
		$rs_send_flag		= trim($arr_rs[0]["SEND_FLAG"]);
		$rs_cms_result	= trim($arr_rs[0]["CMS_RESULT"]);

		$rs_m_5					= trim($arr_rs[0]["M_5"]);
		$rs_m_6					= trim($arr_rs[0]["M_6"]);
		$rs_m_7					= trim($arr_rs[0]["M_7"]);
		$rs_m_8					= trim($arr_rs[0]["M_8"]);
		$rs_m_9					= trim($arr_rs[0]["M_9"]);
		$rs_m_10				= trim($arr_rs[0]["M_10"]);
		$rs_m_11				= trim($arr_rs[0]["M_11"]);
		$rs_m_12				= trim($arr_rs[0]["M_12"]);
		$rs_req_day			= trim($arr_rs[0]["REQ_DAY"]);

		$rs_o_m_zip					= trim($arr_rs[0]["M_O_ZIP"]); 
		$rs_o_m_addr1					= trim($arr_rs[0]["M_O_ADDR1"]); 
		$rs_o_m_addr2					= trim($arr_rs[0]["M_O_ADDR2"]); 

		$rs_m_organization					= trim($arr_rs[0]["M_ORGANIZATION"]); 
		$rs_m_13		= trim($arr_rs[0]["M_13"]);
		$rs_cms_birth		= trim($arr_rs[0]["M_CMS_BIRTH"]);

		$rs_cms_birth_yy = left($rs_cms_birth,4);
		$rs_cms_birth_mm = right(left($rs_cms_birth,6),2);
		$rs_cms_birth_dd = right($rs_cms_birth,2);

		$dec_m_7 = decrypt($key, $iv, $rs_m_7);
		$dec_m_8 = decrypt($key, $iv, $rs_m_8);
		$dec_m_9 = decrypt($key, $iv, $rs_m_9);
		$dec_m_10 = decrypt($key, $iv, $rs_m_10);
		$dec_m_11 = decrypt($key, $iv, $rs_m_11);

		if ($cms_amount=="etc"){
			$cms_amount=$m_13;
			$m_13="etc";
		}

		$old_pay_info_str = "";

		if ($rs_m_5 == "Y") {
			
			if ($rs_m_6 == "cms") {
				$old_pay_info_str = "은행명 : ".getDcodeName($conn, "BANK_CODE", $dec_m_7)." 계좌번호 : ".$dec_m_8." 예금주 : ".$dec_m_9." 생년월일 : ".$rs_cms_birth;
			}

			if ($rs_m_6 == "card") {
				$old_pay_info_str = "카드사 : ".getDcodeName($conn, "CARD_CODE", $dec_m_7)." 카드번호 : ".$dec_m_8." 유효기간 : ".$dec_m_9.$dec_m_10." 카드소유주 이름 : ".$dec_m_11." 생년월일 : ".$rs_cms_birth;
			}

			if ($rs_m_6 == "mobile") {
				$old_pay_info_str = "통신사 : ".getDcodeName($conn, "MOBILE_COM", $dec_m_8)." 전화번호 : ".$dec_m_7;
			}

			if ($rs_m_6 == "cash") {
				$old_pay_info_str = "직접납부";
			}


		} else {
			$old_pay_info_str = "미약정";
		}

		$old_str = $rs_m_5.$rs_m_6.$rs_m_7.$rs_m_8.$rs_m_9.$rs_m_10.$rs_m_11;

		$chk_flag = "0";

		if (($rs_cms_flag == "N") && ($rs_send_flag == "0")) {
			$chk_flag = "1";
		}

		if (($rs_cms_flag == "U") && ($rs_send_flag == "0")) {
			$chk_flag = "1";
		}

	#====================================================================
		$savedir1 = $g_physical_path."upload_data/member";
	#====================================================================

		switch ($flag) {

			case "insert" :
				$m_signature = upload($_FILES[file_nm], $savedir1, "300", array('jpg'));

				if ($m_signature <> "") {
					$img_link = $g_site_url."/upload_data/member/".$m_signature;
					create_thumbnail($img_link, $g_physical_path."upload_data/member_resize/".$m_signature,"480");
				}

				// 동의서 파일 등록
				$str_id = right("00000000".$m_no, 8);
				if ($m_signature <> "") {
					system("cp /home/httpd/html/upload_data/member_resize/".$m_signature." /home/payinfo-thebill/data/send/30040111.".$str_id.".1.jpg");
				}

				break;
			case "keep" :
				$m_signature	= $old_file_nm;

				$str_id = right("00000000".$m_no, 8);
				if ($m_signature <> "") {
					system("cp /home/httpd/html/upload_data/member_resize/".$m_signature." /home/payinfo-thebill/data/send/30040111.".$str_id.".1.jpg");
				}
				
			break;
			case "delete" :
				$m_signature		= "";
				break;
			case "update" :
				
				$m_signature = upload($_FILES[file_nm], $savedir1, "300", array('jpg'));
				if ($m_signature <> "") {
					$img_link = $g_site_url."/upload_data/member/".$m_signature;
					//echo $img_link;
					//echo $g_physical_path."upload_data/member_resize/";
					create_thumbnail($img_link, $g_physical_path."upload_data/member_resize/".$m_signature,"480");
				}

				// 동의서 파일 등록
				$str_id = right("00000000".$m_no, 8);
				if ($m_signature <> "") {
					system("cp /home/httpd/html/upload_data/member_resize/".$m_signature." /home/payinfo-thebill/data/send/30040111.".$str_id.".1.jpg");
				}


				break;
		}

		$new_nick_date = "";

		//echo "뒷자리". $m_reg02;

		$en_m_password	=  encrypt($key, $iv, $m_reg02);
		$str_m_jumin		= $m_reg01."-".$m_reg02;
		$en_m_jumin			=  encrypt($key, $iv, $str_m_jumin);
		$str_tel				= $tel_01."-".$tel_02."-".$tel_03;
		$en_tel					=  encrypt($key, $iv, $str_tel);
		$str_mtel				= $mtel_01."-".$mtel_02."-".$mtel_03;
		$en_mtel				=  encrypt($key, $iv, $str_mtel);

		$en_cms_info01	=  encrypt($key, $iv, $cms_info01);
		$en_cms_info02	=  encrypt($key, $iv, $cms_info02);
		$en_cms_info03	=  encrypt($key, $iv, $cms_info03);
		$en_cms_info04	=  encrypt($key, $iv, $cms_info04);
		$en_cms_info05	=  encrypt($key, $iv, $cms_info05);

		if ($is_pay <> "Y") {
			$is_pay					= "N";
			$en_cms_info01	= "";
			$en_cms_info02	= "";
			$en_cms_info03	= "";
			$en_cms_info04	= "";
			$en_cms_info05	= "";
		}

		if($card_birth<>""){
			$c_birth=$card_birth;
		}

		$new_pay_info_str = "";

		if ($is_pay == "Y") {
			
			if ($pay_type == "cms") {
				$new_pay_info_str = "은행명 : ".getDcodeName($conn, "BANK_CODE", $cms_info01)." 계좌번호 : ".$cms_info02." 예금주 : ".$cms_info03." 생년월일 : ".$c_birth;
			}

			if ($pay_type == "card") {
				$new_pay_info_str = "카드사 : ".getDcodeName($conn, "CARD_CODE", $cms_info01)." 카드번호 : ".$cms_info02." 유효기간 : ".$cms_info03.$cms_info04." 카드소유주 이름 : ".$cms_info05." 생년월일 : ".$c_birth;
			}

			if ($pay_type == "mobile") {
				$new_pay_info_str = "통신사 : ".getDcodeName($conn, "MOBILE_COM", $cms_info02)." 전화번호 : ".$cms_info01;
			}

			if ($pay_type == "cash") {
				$new_pay_info_str = "직접납부";
			}

		} else {
			$new_pay_info_str = "미약정";
		}

		$new_str = $is_pay.$pay_type.$en_cms_info01.$en_cms_info02.$en_cms_info03.$en_cms_info04.$en_cms_info05;

		//if (($m_email_01 <> "") && ($m_email_01 <> "")) {
			$m_email = $m_email_01."@".$m_email_02;
		//} else {
			
		//}

	
		
		//--------------------------------------------------------------------------------------------------------------------------
		// 2016년 4월5일 업데이트 히스토리 저장 Start
		//--------------------------------------------------------------------------------------------------------------------------
		//  수정 내용 확인
		if ($rs_m_name <> $m_name) $modify_memo = $modify_memo."이름 : ".$rs_m_name." -> ".$m_name."\n";
		if ($rs_m_jumin <> $str_m_jumin) $modify_memo = $modify_memo."주민등록번호 : ".$rs_m_jumin." -> ".$str_m_jumin."\n";
		if ($rs_m_hp <> $str_mtel) $modify_memo = $modify_memo."휴대전화번호 : ".$rs_m_hp." -> ".$str_mtel."\n";
		if ($rs_m_tel <> $str_tel) $modify_memo = $modify_memo."연락처 : ".$rs_m_tel." -> ".$str_tel."\n";
		if ($rs_m_email <> $m_email) $modify_memo = $modify_memo."이메일 : ".$rs_m_email." -> ".$m_email."\n";
		if ($rs_m_birth <> $m_birth) $modify_memo = $modify_memo."생년월일 : ".$rs_m_birth." -> ".$m_birth."\n";
		if ($rs_m_zip1 <> $postcode) $modify_memo = $modify_memo."우편번호변경 : ".$rs_m_zip1." -> ".$postcode."\n";
		if ($rs_m_addr1 <> $address) $modify_memo = $modify_memo."주소변경 : ".$rs_m_addr1." -> ".$address."\n";
		if ($rs_m_addr2 <> $address2) $modify_memo = $modify_memo."세부주소변경 : ".$rs_m_addr2." -> ".$address2."\n";
		if ($rs_party <> $party) $modify_memo = $modify_memo."소속당변경 : ".$rs_party." -> ".$party."\n";
		if ($rs_job <> $job) $modify_memo = $modify_memo."직업변경 : ".$rs_job." -> ".$job."\n";
		if ($rs_com_name <> $com_name) $modify_memo = $modify_memo."직장명변경 : ".$rs_com_name." -> ".$com_name."\n";
		if ($rs_group <> $group) $modify_memo = $modify_memo."소속단체변경 : ".$rs_group." -> ".$group."\n";
		
		if ($rs_o_m_zip <> $o_postcode) $modify_memo = $modify_memo."직장우편번호변경 : ".$rs_o_m_zip." -> ".$o_postcode."\n";
		if ($rs_o_m_addr1 <> $o_address) $modify_memo = $modify_memo."직장주소변경 : ".$rs_o_m_addr1." -> ".$o_address."\n";
		if ($rs_o_m_addr2 <> $o_address2) $modify_memo = $modify_memo."직장세부주소변경 : ".$rs_o_m_addr2." -> ".$o_address2."\n";

		if ($rs_m_organization <> $group_cd) $modify_memo = $modify_memo."조직변경 : ".$rs_m_organization." -> ".$group_cd."\n";
		if ($rs_m_13 <> $m_13) $modify_memo = $modify_memo."약정금액 기타여부변경 : ".$rs_m_13." -> ".$m_13."\n";
		
		if ($pay_type == "card") {
			if ($dec_m_11 <> $cms_info05) $modify_memo = $modify_memo."카드소유주 이름 변경 : ".$dec_m_11." -> ".$cms_info05."\n";
			if ($rs_cms_birth <> $c_birth) $modify_memo = $modify_memo."card 생년월일 변경 : ".$rs_cms_birth." -> ".$c_birth."\n";
		}

		if ($pay_type == "cms") {
			if ($rs_cms_birth <> $c_birth) $modify_memo = $modify_memo."CMS 생년월일 변경 : ".$rs_cms_birth." -> ".$c_birth."\n";
		}

		if ($rs_send_flag == "1") { 

		} else { 
			if ($rs_m_5 <> $is_pay) $modify_memo = $modify_memo."당비납부여부변경 : ".$rs_m_5." -> ".$is_pay."\n";
			if ($rs_m_6 <> $pay_type) $modify_memo = $modify_memo."당비납부 방법변경 : ".$rs_m_6." -> ".$pay_type."\n";
		
			// 걸제 정보 수정
			if ($old_str <> $new_str) {
				$modify_memo = $modify_memo."납부정보 : ".$old_pay_info_str." -> ".$new_pay_info_str."\n";
			}
		}

		if ($rs_m_12 <> $cms_amount) $modify_memo = $modify_memo."약정액 변경 : ".$rs_m_12." -> ".$cms_amount."\n";
		if ($rs_req_day <> $cms_pay_day) $modify_memo = $modify_memo."당비출금일 변경 : ".$rs_req_day." -> ".$cms_pay_day."\n";

		$en_modify_memo	=  encrypt($key, $iv, $modify_memo);

		if ($modify_memo <> "") {
			$in_result = insertModifyHistoryMember($conn, $rs_m_no, $en_modify_memo, $s_adm_no);
		}

		//--------------------------------------------------------------------------------------------------------------------------
		// 2016년 4월5일 업데이트 히스토리 저장 End
		//--------------------------------------------------------------------------------------------------------------------------

		if ($rs_send_flag == "1") { 

			$arr_data = array("M_NAME"=>$m_name,
												"M_JUMIN"=>$en_m_jumin,
												"M_SEX"=>$m_sex,
												"M_BIRTH"=>$m_birth,
												"M_LEVEL"=>$m_level,
												"M_EMAIL"=>$m_email,
												"M_MAILLING"=>$m_mailling,
												"M_TEL"=>$en_tel,
												"M_HP"=>$en_mtel,
												"M_SMS"=>$m_sms,
												"M_ZIP1"=>$postcode,
												"M_ADDR1"=>$address,
												"M_ADDR2"=>$address2,
												"M_O_ZIP"=>$o_postcode,
												"M_O_ADDR1"=>$o_address,
												"M_O_ADDR2"=>$o_address2,
												"M_ORGANIZATION"=>$group_cd,
												"M_SIGNATURE"=>$m_signature,
												"M_PROFILE"=>$m_profile,
												"M_MEMO"=>$m_memo,
												"M_1"=>$job,
												"M_2"=>$com_name,
												"M_3"=>$party,
												"M_4"=>$group,
												"M_12"=>$cms_amount,
												"M_13"=>$m_13,
												"M_CMS_BIRTH"=>$c_birth,
												"REQ_DAY"=>$cms_pay_day,
												"SIDO"=>$sido,
												"SIGUNGU"=>$sigungu
												);

			$result =  updateMember($conn, $arr_data, $m_reg02, $new_nick_date, $m_level, $m_id);

		} else {

			$arr_data = array("M_NAME"=>$m_name,
												"M_JUMIN"=>$en_m_jumin,
												"M_SEX"=>$m_sex,
												"M_BIRTH"=>$m_birth,
												"M_LEVEL"=>$m_level,
												"M_EMAIL"=>$m_email,
												"M_MAILLING"=>$m_mailling,
												"M_TEL"=>$en_tel,
												"M_HP"=>$en_mtel,
												"M_SMS"=>$m_sms,
												"M_ZIP1"=>$postcode,
												"M_ADDR1"=>$address,
												"M_ADDR2"=>$address2,
												"M_O_ZIP"=>$o_postcode,
												"M_O_ADDR1"=>$o_address,
												"M_O_ADDR2"=>$o_address2,
												"M_ORGANIZATION"=>$group_cd,
												"M_SIGNATURE"=>$m_signature,
												"M_PROFILE"=>$m_profile,
												"M_MEMO"=>$m_memo,
												"M_1"=>$job,
												"M_2"=>$com_name,
												"M_3"=>$party,
												"M_4"=>$group,
												"M_5"=>$is_pay,
												"M_6"=>$pay_type,
												"M_7"=>$en_cms_info01,
												"M_8"=>$en_cms_info02,
												"M_9"=>$en_cms_info03,
												"M_10"=>$en_cms_info04,
												"M_11"=>$en_cms_info05,
												"M_12"=>$cms_amount,
												"M_13"=>$m_13,
												"M_CMS_BIRTH"=>$c_birth,
												"REQ_DAY"=>$cms_pay_day,
												"SIDO"=>$sido,
												"SIGUNGU"=>$sigungu
												);

			$result =  updateMember($conn, $arr_data, $m_reg02, $new_nick_date, $m_level, $m_id);
			
			if ($old_str <> $new_str) {

				if ($rs_cms_flag == "R") {
					$arr_data_flag = array("CMS_FLAG"=>"U",
														"SEND_FLAG"=>"0",
														"CMS_RESULT"=>""
														);
					$result =  updateMember($conn, $arr_data_flag, $m_reg02, $new_nick_date, $m_level, $m_id);
				}

				if ($rs_cms_flag == "N") {
					$arr_data_flag = array("CMS_FLAG"=>"N",
														"SEND_FLAG"=>"0",
														"CMS_RESULT"=>""
														);
					$result =  updateMember($conn, $arr_data_flag, $m_reg02, $new_nick_date, $m_level, $m_id);
				}

			}

		}

		$arr_data_pay = array("M_NO"=>$m_no,
											"IS_PAY"=>$is_pay,
											"PAY_TYPE"=>$pay_type,
											"M_7"=>$en_cms_info01,
											"M_8"=>$en_cms_info02,
											"M_9"=>$en_cms_info03,
											"M_10"=>$en_cms_info04,
											"M_11"=>$en_cms_info05,
											"M_12"=>$cms_amount,
											"REQ_DAY"=>$cms_pay_day,
											"chk_flag"=>"1");

		$result = insertMemberPayHistory($conn, $arr_data_pay);

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당원정보 수정 (당원 번호 : ".$m_no.")", "Update");
	}

	if ($mode == "D") {
		
		if ($m_memo) {
			$arr_data = array("M_MEMO"=>$m_memo);
			$result =  updateMember($conn, $arr_data, $m_reg02, $new_nick_date, $m_level, $m_id);
		}

		$result = quitMember($conn, $m_id);
		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당원정보 수정 (당원 번호 : ".$m_id.")", "Delete");
	}

	if ($mode == "S") {

		$arr_rs = selectMember($conn, $m_no);
		
		$rs_m_no						= trim($arr_rs[0]["M_NO"]); 
		$rs_m_id						= trim($arr_rs[0]["M_ID"]); 
		$rs_m_name					= trim($arr_rs[0]["M_NAME"]); 
		$rs_m_jumin					= trim($arr_rs[0]["M_JUMIN"]);
		$rs_m_email					= trim($arr_rs[0]["M_EMAIL"]); 
		$rs_m_password			= trim($arr_rs[0]["M_PASSWORD"]); 
		 
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
		$rs_m_memo					= trim($arr_rs[0]["M_MEMO"]); 

		$rs_m_sms						= trim($arr_rs[0]["M_SMS"]); 
		$rs_m_mailling			= trim($arr_rs[0]["M_MAILLING"]); 

		$rs_o_m_zip					= trim($arr_rs[0]["M_O_ZIP"]); 
		$rs_o_m_addr1					= trim($arr_rs[0]["M_O_ADDR1"]); 
		$rs_o_m_addr2					= trim($arr_rs[0]["M_O_ADDR2"]); 

		$rs_m_organization					= trim($arr_rs[0]["M_ORGANIZATION"]); 
		$rs_m_online_flag					= trim($arr_rs[0]["M_ONLINE_FLAG"]); 

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
		$rs_m_13		= trim($arr_rs[0]["M_13"]);

		$rs_cms_birth		= trim($arr_rs[0]["M_CMS_BIRTH"]);

		if ($rs_cms_birth == "") $rs_cms_birth = $rs_m_birth;

		$rs_cms_birth_yy = left($rs_cms_birth,4);
		$rs_cms_birth_mm = right(left($rs_cms_birth,6),2);
		$rs_cms_birth_dd = right($rs_cms_birth,2);


		$rs_cms_flag				= trim($arr_rs[0]["CMS_FLAG"]);
		$rs_send_flag				= trim($arr_rs[0]["SEND_FLAG"]);
		$rs_cms_result			= trim($arr_rs[0]["CMS_RESULT"]);
		$rs_cms_result_msg	= trim($arr_rs[0]["CMS_RESULT_MSG"]);
		$rs_req_day					= trim($arr_rs[0]["REQ_DAY"]);

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
		$rs_cms_info05 = decrypt($key, $iv, $rs_cms_info05);

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
			$rs_card_code		= $rs_cms_info01;
			$rs_card_no			= $rs_cms_info02;
			$rs_card_yy			= $rs_cms_info03;
			$rs_card_mm			= $rs_cms_info04;
			$rs_card_name		= $rs_cms_info05;
		}

		if ($rs_m_sex == "M") {
			$str_sex = "남자";
		} else {
			$str_sex = "여자";
		}
		
		if($rs_m_organization){
			if(strlen($rs_m_organization) == 3){
				$group_cd_01=$rs_m_organization;
			}
			if(strlen($rs_m_organization) == 6){
				$group_cd_01=substr($rs_m_organization,0,3);
				$group_cd_02=substr($rs_m_organization,0,6);
			}
			if(strlen($rs_m_organization) == 9){
				$group_cd_01=substr($rs_m_organization,0,3);
				$group_cd_02=substr($rs_m_organization,0,6);
				$group_cd_03=substr($rs_m_organization,0,9);
			}

			if(strlen($rs_m_organization) == 12){
				$group_cd_01=substr($rs_m_organization,0,3);
				$group_cd_02=substr($rs_m_organization,0,6);
				$group_cd_03=substr($rs_m_organization,0,9);
				$group_cd_04=substr($rs_m_organization,0,12);
			}

			if(strlen($rs_m_organization) == 15){
				$group_cd_01=substr($rs_m_organization,0,3);
				$group_cd_02=substr($rs_m_organization,0,6);
				$group_cd_03=substr($rs_m_organization,0,9);
				$group_cd_04=substr($rs_m_organization,0,12);
				$group_cd_05=substr($rs_m_organization,0,15);
			}
		}


		// 지역 관련
		//echo strlen(stristr($rs_sido, "서울"));
		//echo "<br>".$rs_sido;

		if (strlen(stristr($rs_sido, "서울")) > 0) $rs_sido = "서울";
		if (strlen(stristr($rs_sido, "경기")) > 0) $rs_sido = "경기";
		if (strlen(stristr($rs_sido, "강원")) > 0) $rs_sido = "강원";
		if (strlen(stristr($rs_sido, "울산")) > 0) $rs_sido = "울산";
		if (strlen(stristr($rs_sido, "대구")) > 0) $rs_sido = "대구";
		if (strlen(stristr($rs_sido, "부산")) > 0) $rs_sido = "부산";
		if (strlen(stristr($rs_sido, "대전")) > 0) $rs_sido = "대전";
		if (strlen(stristr($rs_sido, "제주")) > 0) $rs_sido = "제주특별자치도";
		if (strlen(stristr($rs_sido, "세종")) > 0) $rs_sido = "세종특별자치시";
		if (strlen(stristr($rs_sido, "인천")) > 0) $rs_sido = "인천";
		if (strlen(stristr($rs_sido, "경상북도")) > 0) $rs_sido = "경북";
		if (strlen(stristr($rs_sido, "경상남도")) > 0) $rs_sido = "경남";
		if (strlen(stristr($rs_sido, "전라북도")) > 0) $rs_sido = "전북";
		if (strlen(stristr($rs_sido, "전라남도")) > 0) $rs_sido = "전남";
		if (strlen(stristr($rs_sido, "충청북도")) > 0) $rs_sido = "충북";
		if (strlen(stristr($rs_sido, "충청남도")) > 0) $rs_sido = "충남";
		if (strlen(stristr($rs_sido, "경북")) > 0) $rs_sido = "경북";
		if (strlen(stristr($rs_sido, "경남")) > 0) $rs_sido = "경남";
		if (strlen(stristr($rs_sido, "전북")) > 0) $rs_sido = "전북";
		if (strlen(stristr($rs_sido, "전남")) > 0) $rs_sido = "전남";
		if (strlen(stristr($rs_sido, "충북")) > 0) $rs_sido = "충북";
		if (strlen(stristr($rs_sido, "충남")) > 0) $rs_sido = "충남";

		//echo "<br>".$rs_sido;

		$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "당원정보 조회 (당원 번호 : ".$m_no.")", "Read");

	}


	if (($result)&&($mode!="D")) {
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&m_no=".$m_no."&mode=S&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&start_date=".$start_date."&end_date=".$end_date."&Ngroup_cd=".$Ngroup_cd;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "member_modify.php<?=$strParam?>";
</script>
<?
		exit;
	}elseif(($result)&&($mode=="D")){
		$strParam = $strParam."?nPage=".$nPage."&nPageSize=".$nPageSize."&search_field=".$search_field."&search_str=".$search_str."&order_field=".$order_field."&order_str=".$order_str."&m_no=".$m_no."&mode=S&sel_area_cd=".$sel_area_cd."&sel_pay_type=".$sel_pay_type."&sel_party=".$sel_party."&is_agree=".$is_agree."&start_date=".$start_date."&end_date=".$end_date."&Ngroup_cd=".$Ngroup_cd;
?>	
<script language="javascript">
		alert('정상 처리 되었습니다.');
		document.location.href = "member_list.php<?=$strParam?>";
</script>
<?
		exit;
	}
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="<?=$g_charset?>">
<title><?=$g_title?></title>
<link href="../css/common.css" rel="stylesheet" />
<link href="../js/jquery-ui.min.css" rel="stylesheet" />
<link href="../css/jquery-ui.css" type="text/css" media="all" rel="stylesheet"  />
<script src="../js/jquery-1.11.2.min.js"></script>
<script src="../js/jquery-ui.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script src="../js/common.js"></script>
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

<script language="javascript">
	
	function js_delete() {
		
		bDelOK = confirm('정말 탈당처리 하시겠습니까?');//정말 삭제 하시겠습니까?
		
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.method = "post";
			frm.target = "";
			frm.action = "member_modify.php";
			frm.submit();
		} else {
			return;
		}
	}

	function check_dup() {

		var frm = document.frm;
		var m_no = "<?=$rs_m_no?>";

		if (frm.m_name.value.trim() == "") {
			 alert("이름을 입력해주십시오.");
			frm.m_name.focus();
			return;
		}

		if (frm.m_reg01.value.trim() == "") {
			 alert("주민등록번호를 입력해주십시오.");
			frm.m_reg01.focus();
			return;
		}

		if (frm.m_reg02.value.trim() == "") {
			 alert("주민등록번호를 입력해주십시오.");
			frm.m_reg02.focus();
			return;
		}

		var m_name = frm.m_name.value.trim();
		var m_reg01 = frm.m_reg01.value.trim();
		var m_reg02 = frm.m_reg02.value.trim();

		var request = $.ajax({
			url:"/manager/member/dup_modify_member_dml.php",
			type:"POST",
			data:{m_no:m_no, m_reg01:m_reg01, m_reg02:m_reg02},
			dataType:"html"
		});
		
		request.done(function(msg) {

			if (msg == "T") {
				js_check_data();
			} else if (msg == "DUP") {
				alert("해당 주민번호로 등록된 당원 정보가 있습니다.");
				return;
			}
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});

	}


	function js_check_data() {

		var frm = document.frm;
		var m_no = "<?=$rs_m_no?>";
		
		frm.mode.value = "U";

		if (frm.m_name.value.trim() == "") {
			 alert("이름을 입력해주십시오.");
			frm.m_name.focus();
			return;
		}

		if (frm.m_reg01.value.trim() == "") {
			 alert("주민등록번호를 입력해주십시오.");
			frm.m_reg01.focus();
			return;
		}

		if (frm.m_reg02.value.trim() == "") {
			 alert("주민등록번호를 입력해주십시오.");
			frm.m_reg02.focus();
			return;
		}

		if (frm.mtel_02.value.trim() == "") {
			 alert("휴대폰번호를 입력해주십시오.");
			frm.mtel_02.focus();
			return;
		}

		if (frm.mtel_03.value.trim() == "") {
			 alert("휴대폰번호를 입력해주십시오.");
			frm.mtel_03.focus();
			return;
		}

		if (frm.sel_year.value.trim() == "") {
			alert("년을 선택해주십시오.");
			frm.sel_year.focus();
			return;
		}

		if (frm.sel_month.value.trim() == "") {
			alert("월을 선택해주십시오.");
			frm.sel_month.focus();
			return;
		}

		if (frm.sel_day.value.trim() == "") {
			alert("일을 선택해주십시오.");
			frm.sel_day.focus();
			return;
		}

		frm.m_birth.value = frm.sel_year.value+""+frm.sel_month.value+""+frm.sel_day.value;

		if (document.frm.rd_m_sex == null) {
		} else {
			if (frm.rd_m_sex[0].checked == true) {
				frm.m_sex.value = "1";
			} else {
				frm.m_sex.value = "2";
			}
		}

		var party = $('input:radio[name=party]:checked').val();
		var job = $("#job").val();
		var com_name = $("#com_name").val();
		var group = $("#group").val();

		var m_sms = $('input:radio[name=m_sms]:checked').val();
		var m_mailling = $('input:radio[name=m_mailling]:checked').val();

		if (party == null) {
			alert("소속정당을 선택하세요.\n없을 경우 없음을 선택하세요.");
			return;
		}

		//if(party!="없음"){
		//	if(frm.group_cd.value==""){
		//		alert('조직을 선택해주세요.');
		//		return;
		//	}
		//}

		var is_pay = $('input:radio[name=rd_is_pay]:checked').val();
		var pay_type = $('input:radio[name=rd_pay_type]:checked').val();
		var cms_mobile = $("#pay_mtel_01").val()+"-"+$("#pay_mtel_02").val()+"-"+$("#pay_mtel_03").val();
		var cms_info01 = "";
		var cms_info02 = "";
		var cms_info03 = "";
		var cms_info04 = "";
		var cms_info05 = "";
		var cms_amount = $('input:radio[name=cms_amount]:checked').val();

		if (is_pay == "Y") {

			if (pay_type == null) {
				alert("결제방식을 선택 하세요.");
				return;
			}

			if (pay_type == "mobile") {
			
				cms_info01 = cms_mobile;
				cms_info02 = $('input:radio[name=mobile_com]:checked').val();

				if ($("#pay_mtel_02").val().trim() == "") {
					alert("전화번호를 입력하세요.");
					$("#pay_mtel_02").focus();
					return;
				}

				if ($("#pay_mtel_03").val().trim() == "") {
					alert("전화번호를 입력하세요.");
					$("#pay_mtel_03").focus();
					return;
				}

				if (cms_info02 == null) {
					alert("통신사를 선택하세요.");
					return;
				}
			}

			if (pay_type == "cms") {

				cms_info01 = $("#bank_code").val();
				cms_info02 = $("#bank_no").val();
				cms_info03 = $("#bank_name").val();

				if (cms_info01 == "") {
					alert("은행을 선택하세요.");
					$("#bank_code").focus();
					return;
				}

				if (cms_info02 == "") {
					alert("계좌번호를 입력하세요.");
					$("#bank_no").focus();
					return;
				}

				if (cms_info03 == "") {
					alert("예금주를 입력하세요.");
					$("#bank_name").focus();
					return;
				}

				if (frm.c_sel_year.value.trim() == "") {
					alert("년을 선택해주십시오.");
					frm.c_sel_year.focus();
					return;
				}

				if (frm.c_sel_month.value.trim() == "") {
					alert("월을 선택해주십시오.");
					frm.c_sel_month.focus();
					return;
				}

				if (frm.c_sel_day.value.trim() == "") {
					alert("일을 선택해주십시오.");
					frm.c_sel_day.focus();
					return;
				}

				frm.c_birth.value = frm.c_sel_year.value+""+frm.c_sel_month.value+""+frm.c_sel_day.value;

			}

			if (pay_type == "card") {

				cms_info01 = $("#card_code").val();
				cms_info02 = $("#card_no").val();
				cms_info03 = $("#card_yy").val();
				cms_info04 = $("#card_mm").val();
				cms_info05 = $("#card_name").val();

				if (cms_info01 == "") {
					alert("카드사를 선택하세요.");
					$("#card_code").focus();
					return;
				}

				if (cms_info02 == "") {
					alert("카드번호를 선택하세요.");
					$("#card_no").focus();
					return;
				}

				if (cms_info03 == "") {
					alert("유효기간 년을 선택하세요.");
					$("#card_yy").focus();
					return;
				}

				if (cms_info04 == "") {
					alert("유효기간 월을 선택하세요.");
					$("#card_mm").focus();
					return;
				}

				if (cms_info05 == "") {
					alert("카드 소유주 이름을 입력하세요.");
					$("#card_name").focus();
					return;
				}

				if (frm.card_sel_year.value.trim() == "") {
					alert("년을 선택해주십시오.");
					frm.card_sel_year.focus();
					return;
				}

				if (frm.card_sel_month.value.trim() == "") {
					alert("월을 선택해주십시오.");
					frm.card_sel_month.focus();
					return;
				}

				if (frm.card_sel_day.value.trim() == "") {
					alert("일을 선택해주십시오.");
					frm.card_sel_day.focus();
					return;
				}

				frm.card_birth.value = frm.card_sel_year.value+""+frm.card_sel_month.value+""+frm.card_sel_day.value;

			}

			if (cms_amount == null) {
				alert("당비약정금액을 선택하세요.");
				return;
			}else{
				if (cms_amount == "etc") {
					if (frm.m_13.value.trim() == "") {
						alert("약정금액을 입력해주세요.");
						frm.m_13.focus();
						return;
					}
				}
			}
			
			frm.is_pay.value		 = is_pay;
			frm.pay_type.value	 = pay_type;
			frm.cms_info01.value = cms_info01;
			frm.cms_info02.value = cms_info02;
			frm.cms_info03.value = cms_info03;
			frm.cms_info04.value = cms_info04;
			frm.cms_info05.value = cms_info05;
			frm.cms_amount.value = cms_amount;

		}
		
		frm.target = "";
		frm.action = "../member/member_modify.php";
		frm.submit();

	}

	function js_sel_email() {
		var frm = document.frm;
		frm.m_email_02.value = frm.sel_email.value;
	}

	function js_list() {
		var frm = document.frm;
		
		frm.method = "get";
		frm.target = "";
		frm.action = "member_list.php";
		frm.submit();
	}

	function sample6_execDaumPostcode(str) {
		new daum.Postcode({
			oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			if(str=="o"){
			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('o_postcode').value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById('o_address').value = fullAddr;
			// 커서를 상세주소 필드로 이동한다.
			document.getElementById('o_address2').focus();
			}else{
			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('postcode').value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById('address').value = fullAddr;
			document.getElementById('sido').value = data.sido;
			document.getElementById('sigungu').value = data.sigungu;

			// 커서를 상세주소 필드로 이동한다.
			document.getElementById('address2').focus();
			}
		}
		}).open();
	}


	function js_party(group_cd_01, group_cd_02, group_cd_03, group_cd_04, group_cd_05) {
		
		var frm = document.frm;
		var party_val = "";

		for (i=0 ; i < frm.party.length ; i++) {
			if (frm.party[i].checked == true) {
				party_val = frm.party[i].value;
			}
		}

		if (party_val=="") {
			$("#add_group").hide();
		} else {
		
			var request = $.ajax({
				url:"/_common/get_next_group.php",
				type:"POST",
				data:{party_val:party_val, group_cd_01:group_cd_01, group_cd_02:group_cd_02, group_cd_03:group_cd_03, group_cd_04:group_cd_04, group_cd_05:group_cd_05},
				dataType:"html"
			});

			request.done(function(msg) {
				//alert(msg);
				$("#group_div").html(msg);
			});

			request.fail(function(jqXHR, textStatus) {
				alert("Request failed : " +textStatus);
				return false;
			});

			$("#add_group").show();
		}

		$("#group_cd").val("");
		if ((group_cd_01 != "") && (group_cd_01 != null)) $("#group_cd").val(group_cd_01);
		if ((group_cd_02 != "") && (group_cd_02 != null)) $("#group_cd").val(group_cd_02);
		if ((group_cd_03 != "") && (group_cd_03 != null)) $("#group_cd").val(group_cd_03);
		if ((group_cd_04 != "") && (group_cd_04 != null)) $("#group_cd").val(group_cd_04);
		//if ((group_cd_05 != "") && (group_cd_05 != null)) $("#group_cd").val(group_cd_05);

	}

	function js_pay_type(pay_type) {

		$("#mobile_01").hide();
		$("#mobile_02").hide();
		$("#cms_01").hide();
		$("#cms_02").hide();
		$("#cms_03").hide();
		$("#cms_04").hide();
		$("#card_01").hide();
		$("#card_02").hide();
		$("#card_03").hide();
		$("#card_04").hide();
		$("#card_05").hide();

		if (pay_type == "mobile") {
			$("#mobile_01").show();
			$("#mobile_02").show();
		}

		if (pay_type == "cms") {
			$("#cms_01").show();
			$("#cms_02").show();
			$("#cms_03").show();
			$("#cms_04").show();
		}

		if (pay_type == "card") {
			$("#card_01").show();
			$("#card_02").show();
			$("#card_03").show();
			$("#card_04").show();
			$("#card_05").show();
		}

	}

	$(document).ready(function() {

		js_pay_type("<?=$rs_pay_type?>");

		$(document).on("change","#group_cd_01", function(){
			//document.frm.group_cd.value=$("#group_cd_01").val();
			js_party($("#group_cd_01").val(), '');
		});

		$(document).on("change","#group_cd_02", function(){
			//document.frm.group_cd.value=$("#group_cd_02").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val());
		});

		$(document).on("change","#group_cd_03", function(){
			//document.frm.group_cd.value=$("#group_cd_03").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val());
		});

		$(document).on("change","#group_cd_04", function(){
			//document.frm.group_cd.value=$("#group_cd_04").val();
			js_party($("#group_cd_01").val(), $("#group_cd_02").val(), $("#group_cd_03").val(), $("#group_cd_04").val());
		});

		$(document).on("change","#group_cd_05", function(){
			document.frm.group_cd.value=$("#group_cd_05").val();
		});


		var cache = {};

		$("#group_name").autocomplete({
			source: function( request, response ) {
				var term = request.term;
				if ( term in cache ) {
					response(cache[term]);
					return;
				}

				var party_val = "";

				for (i=0 ; i < frm.party.length ; i++) {
					if (frm.party[i].checked == true) {
						party_val = frm.party[i].value;
					}
				}

				$.getJSON( "/_common/get_group_list.php?party_val="+encodeURIComponent(party_val)+"&str="+ encodeURIComponent($("#group_name").val()), request, function( data, status, xhr ) {
					cache[term] = data;
					response(data);
				});
			},
			minLength: 2,
			select: function( event, ui) {
				
				$("#group_cd").val(ui.item.group_cd);
				$("#group_name").val(ui.item.group_name);
				


				//set_member_info(ui.item.m_no);
				//alert(ui.item.m_name);
				//alert(ui.item.label);
				//$(".supplyer").val(ui.item.value);
				//$("input[name=con_cate_03]").val(ui.item.id);
			}
			
			}).bind( "blur", function( event ) {
			
				if ($("#group_cd").val() == "") {
					alert("조직명으로 검색 후 포함될 조직을 클릭 하세요.");
					return;
				}
		});


	});


	function js_fileView(obj) {
	
		var frm = document.frm;
		if (obj.selectedIndex == 2) {
			document.getElementById("file_change").style.display = "inline";
		} else {
			document.getElementById("file_change").style.display = "none";
		}
	}

	function chk_file_size() {
		var frm = document.frm;

		if (frm.file_nm.value != "") {
			frm.target = "ifr_hidden";
			frm.action = "chk_file_size.php";
			frm.submit();
		}

	}


	function js_get_history_list(type, cnt, m_no) {
		
		if (type == "modify") {
			if (cnt == "ALL") {
				$("#btn_history_all").hide();
				$("#btn_history_5").show();
			} else {
				$("#btn_history_all").show();
				$("#btn_history_5").hide();
			}
		} else {
			if (cnt == "ALL") {
				$("#btn_payment_all").hide();
				$("#btn_payment_5").show();
			} else {
				$("#btn_payment_all").show();
				$("#btn_payment_5").hide();
			}
		}

		var mode = type;
		var request = $.ajax({
			url:"/manager/member/ajax_member_history.php",
			type:"POST",
			data:{mode:mode,cnt:cnt,m_no:m_no},
			dataType:"html"
		});

		request.done(function(msg) {
			
			if (type == "modify") {
				$("#modify_list").html(msg);
			} else {
				$("#payment_list").html(msg);
			}

			//alert(msg);
		});

		request.fail(function(jqXHR, textStatus) {
			alert("Request failed : " +textStatus);
			return false;
		});
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
		<form name="frm" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="" >
		<input type="hidden" name="m_no" value="<?= $m_no ?>">
		<input type="hidden" name="search_field" value="<?= $search_field ?>">
		<input type="hidden" name="search_str" value="<?= $search_str ?>">

		<input type="hidden" name="order_field" value="<?= $order_field ?>">
		<input type="hidden" name="order_str" value="<?= $order_str ?>">
		

		<input type="hidden" name="nPage" value="<?=$nPage?>">
		<input type="hidden" name="nPageSize" value="<?=$nPageSize?>">

		<input type="hidden" name="sel_area_cd" value="<?=$sel_area_cd?>">
		<input type="hidden" name="sel_pay_type" value="<?=$sel_pay_type?>">

		<input type="hidden" name="sel_party" value="<?=$sel_party?>">
		<input type="hidden" name="is_agree" value="<?=$is_agree?>">
		<input type="hidden" name="start_date" value="<?=$start_date?>">
		<input type="hidden" name="end_date" value="<?=$end_date?>">

		<input type="hidden" name="menu_cd" value="<?=$menu_cd?>">
		<input type="hidden" name="use_tf" value="<?=$rs_use_tf?>">
		<input type="hidden" name="m_id_enabled" value="" id="m_id_enabled">
		<input type="hidden" name="m_nick_enabled" value="" id="m_nick_enabled">
		<input type="hidden" name="m_email_enabled" value="" id="m_email_enabled">

		<input type="hidden" name="Ngroup_cd" value="<?=$Ngroup_cd?>">
		<input type="hidden" name="online_flag" value="<?=$online_flag?>">

		<h3 class="conTitle"><?=$p_menu_name?></h3>
			<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
				<caption>게시판 생성</caption>
					<colgroup>
						<col width="20%" />
						<col width="80%" />
					</colgroup>
					<tbody>
						<tr>
							<th>아이디</td>
							<td>
								<?= $rs_m_id?>
								<input type="hidden" name="m_id" id="m_id" value="<?=$rs_m_id?>" maxLength="20">
							</td>
						</tr>
						<tr>
							<th>이름</td>
							<td>
								<input type="Text" name="m_name" value="<?= $rs_m_name ?>" style="width:95px;" class="txt">
							</td>
						</tr>
						<tr>
							<th>주민등록번호</td>
							<td>
								<input type="Text" name="m_reg01" value="<?= $arrJumin[0] ?>" style="width:95px;" maxlength="6" class="txt" onKeyUp="js_next_obj(6, 'm_reg01', 'm_reg02')"> -
								<input type="Text" name="m_reg02" value="<?= $arrJumin[1] ?>" style="width:95px;" maxlength="7" class="txt" onKeyUp="js_next_obj(7, 'm_reg02', 'mtel_01')">
							</td>
						</tr>

						<tr>
							<th>휴대폰번호</th>
							<td>
								<?=makeSelectBox($conn, "MOBILE","mtel_01","80","","",$arr_m_tel[0])?> -
								<input type="text" id="mtel_02" name="mtel_02" maxlength="4" style="width:80px" value="<?=$arr_m_tel[1]?>" onKeyUp="js_next_obj(4, 'mtel_02', 'mtel_03')"> - 
								<input type="text" id="mtel_03" name="mtel_03" maxlength="4" style="width:80px" value="<?=$arr_m_tel[2]?>" onKeyUp="js_next_obj(4, 'mtel_03', 'tel_01')">

								&nbsp;&nbsp;
								SMS 수신동의 :&nbsp;&nbsp;
								<input type="radio" class="radio" name="m_sms" value="1" <? if ($rs_m_sms == "1") { ?> checked <? } ?>> 동의함
								<input type="radio" class="radio" name="m_sms" value="0" <? if ($rs_m_sms == "0") { ?> checked <? } ?>> 동의안함

							</td>
						</tr>

						<tr>
							<th>연락처</th>
							<td>
								<?=makeSelectBox($conn, "TEL","tel_01","80","","",$arr_tel[0])?> -
								<input type="text" id="tel_02" name="tel_02" maxlength="4" style="width:80px" value="<?=$arr_tel[1]?>" onKeyUp="js_next_obj(4, 'tel_02', 'tel_03')"> - 
								<input type="text" id="tel_03" name="tel_03" maxlength="4" style="width:80px" value="<?=$arr_tel[2]?>" onKeyUp="js_next_obj(4, 'tel_03', 'm_email_01')">
							</td>
						</tr>

						<tr>
							<th>이메일</th>
							<td>
								<input type="hidden" name='old_email' id="old_email" value='<?=$rs_m_email?>'>
								<input type="text" name="m_email_01" id="m_email_01" value="<?=$arr_m_email[0]?>" onchange="frm.m_email_enabled.value='';" style="width:100px;"/> @ 
								<input type="text" name="m_email_02" id="m_email_02" value="<?=$arr_m_email[1]?>" onchange="frm.m_email_enabled.value='';" style="width:100px;"/>
								<?= makeSelectBoxOnChange($conn,"EMAIL","sel_email","150","직접입력","",$arr_m_email[1])?>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="hidden" name ="m_email" id="m_email" value="<?=$rs_m_email?>">
								&nbsp;&nbsp;
								Email 수신동의 :&nbsp;&nbsp;
								<input type="radio" class="radio" name="m_mailling" value="1" <? if ($rs_m_mailling == "1") { ?> checked <? } ?>> 동의함
								<input type="radio" class="radio" name="m_mailling" value="0" <? if ($rs_m_mailling == "0") { ?> checked <? } ?>> 동의안함
							</td>
						</tr>

						<tr>
							<th>생년월일</th>
							<td>
								<select name="sel_year" style="width:100px;">
								<option value="">년</option>
									<?
										$end_yy = date("Y",strtotime("0 day"));

										for ($k = $end_yy; $k > ($end_yy-110) ; $k--) {
											if ($rs_m_birth_yy == $k) {
									?>
								<option value="<?=$k?>" selected><?=$k?></option>
									<?
											} else { 
									?>
								<option value="<?=$k?>"><?=$k?></option>
									<?
											}
										}
									?>
							</select> 
							<?= makeSelectBox($conn,"MONTH","sel_month","100","월","",$rs_m_birth_mm)?>
							<?= makeSelectBox($conn,"DAY","sel_day","100","일","",$rs_m_birth_dd)?>
							<input type="hidden" name="m_birth" value="">
							</td>
						</tr>

						<tr>
							<th>성별</th>
							<td>
								<input type="radio" name="rd_m_sex" value="1" class="radio" <? if ($rs_m_sex == "1") { echo "checked"; } ?> /> 남
								<input type="radio" name="rd_m_sex" value="2" class="radio" <? if ($rs_m_sex == "2") { echo "checked"; } ?> /> 여
								<input type="hidden" name="m_sex" value="">
							</td>
						</tr>

						<tr>
							<th>주소</th>
							<td>
								<input type="text" id="postcode" name="postcode" value="<?=$rs_m_zip1?>" style="width:60px" readonly="1"> 
								<a href="javascript:void(0)" class="btn gray_post" onclick="sample6_execDaumPostcode('j');">주소검색</a><br />
								<div class="sp5"></div>
								<input type="text" id="address" name="address" style="width:300px" value="<?=$rs_m_addr1?>">
								<input type="text" id="address2" name="address2" style="width:300px" value="<?=$rs_m_addr2?>">
								<div class="sp5"></div>
								<em>* 자택주소는 주민등록상 주소지로 상세주소까지 기재하여야 합니다.</em>
							</td>
						</tr>
						
						<tr>
							<th>소속 지역</th>
							<td>
								<?= makeSelectBox($conn,"AREA_CD","sido","150","소속지역선택","",$rs_sido)?>
								<!--<input type="text" style="width:150px" id="sido" name="sido" value="<?=$rs_sido?>"> -->
								<input type="hidden" style="width:150px" id="sigungu" name="sigungu" value="<?=$rs_sigungu?>">
							</td>
						</tr>

						<tr>
							<th>소속당</th>
							<td>
								<?=makeRadioBoxOnClick($conn,"PARTY","party", $rs_party)?>
							</td>
						</tr>

						<tr id="add_group">
							<th>조직</th>
							<td>
								<div class="sp5"></div>
								<div id="group_div"><select name="group_cd_01" id="group_cd_01" style="width:200px;"></select></div>
								<div class="sp10"></div>
								<input type="text" name="group_name" id="group_name" class="txt" style="width:500px;"/>
								<font color='red'><b>조직코드</b></font>  <input type="text" name="group_cd" id="group_cd" class="txt" style="width:100px;" value="<?=$rs_m_organization?>"/>
								<div class="sp5"></div>
							</td>
						</tr>

						<tr>
							<th>직업</th>
							<td><input type="text" name="job" value="<?=$rs_job?>" class="txt" style="width:200px;"/></td>
						</tr>

						<tr>
							<th>직장명</th>
							<td><input type="text" name="com_name" value="<?=$rs_com_name?>" class="txt" style="width:200px;"/></td>
						</tr>

						<tr>
							<th>직장주소</th>
							<td>
								<input type="text" id="o_postcode" name="o_postcode" style="width:60px" readonly="1" value="<?=$rs_o_m_zip?>"> 
								<a href="javascript:void(0)" class="btn gray_post" onclick="sample6_execDaumPostcode('o');">주소검색</a><br />
								<div class="sp5"></div>
								<input type="text" id="o_address" name="o_address" style="width:300px" value="<?=$rs_o_m_addr1?>">
								<input type="text" id="o_address2" name="o_address2" style="width:300px" value="<?=$rs_o_m_addr2?>">
								<div class="sp5"></div>
							</td>
						</tr>

						<tr>
							<th>소속단체</th>
							<td><input type="text" name="group" value="<?=$rs_group?>" class="txt" style="width:200px;"/></td>
						</tr>

						<tr>
							<th>당원권한</th>
							<td>
								
								<select name="m_level" style="width:60px;">
								<?
									for($i=1;$i<11;$i++){
									?>
										<option value="<?=$i?>" <?if($rs_m_level==$i){?>selected<?}?>><?=$i?></option>
									<?
										}
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>CMS 등록 구분</th>
							<td>
								<? if ($rs_cms_flag == "N") { ?>신규<? } ?>
								<? if ($rs_cms_flag == "U") { ?>수정<? } ?>
								<? if ($rs_cms_flag == "D") { ?>삭제<? } ?>
								<? if ($rs_cms_flag == "R") { ?>등록<? } ?>
								<? if ($rs_cms_flag == "F") { ?>오류<? } ?>
								&nbsp;&nbsp;
								<? if ($rs_send_flag == "0") { ?><? } ?>
								<? if ($rs_send_flag == "1") { ?><font color="red">심사중</font><? } ?>
							</td>
						</tr>
						<tr>
							<th>CMS 심사 결과</th>
							<td>
								<?=$rs_cms_result_msg?>
							</td>
						</tr>
						<tr  class="end">
							<th>관리자메모</td>
							<td colspan="3">
								<textarea id="m_memo" name="m_memo" style="width:80%;"><?=$rs_m_memo?></textarea>
							</td>
						</tr>
					</table>

		<input type="hidden" name="is_pay" value="">
		<input type="hidden" name="pay_type" value="">
		<input type="hidden" name="cms_info01" value="">
		<input type="hidden" name="cms_info02" value="">
		<input type="hidden" name="cms_info03" value="">
		<input type="hidden" name="cms_info04" value="">
		<input type="hidden" name="cms_info05" value="">
		<input type="hidden" name="cms_amount" value="">

					<h3 class="conTitle">당비 정보</h3>
					<div class="expArea">
					<p class="fRight">
						<? if ($rs_send_flag == "1") { ?>
						<font color="red"><b>* CMS 심사승인 중인 당원 입니다. 심사 기간중 당비 정보는 수정 하실 수 없습니다. 당비 정보를 수정 하셔도 반영 되지 않습니다. (약정금액 수정 가능)</b></font>
						<? } else { ?>
							<? if ($rs_cms_flag == "R") { ?>
								<font color="red"><b>* CMS 카드 출금일 (25일) 휴대전화 (15일)</b></font><br/>
								<font color="red"><b>* 출금일이 영업일 기준 최소 4일 전일 경우만 수정 승인 처리 하셔야 합니다.</b></font>
							<? } ?>
						<? } ?>
					</p>
					</div>
					<table summary="이곳에서 게시판을 등록, 수정, 삭제하실 수 있습니다" class="bbsWrite">
						<caption>게시판 생성</caption>
						<colgroup>
							<col width="20%" />
							<col width="80%" />
						</colgroup>
						<tr>
							<th>당비납부 여부</th>
							<td>
								<input type="radio" name="rd_is_pay" value="Y" class="radio" <? if ($rs_is_pay == "Y") { echo "checked";} ?> /> 약정함&nbsp;&nbsp;&nbsp;
								<input type="radio" name="rd_is_pay" value="N" class="radio" <? if ($rs_is_pay == "N") { echo "checked";} ?> /> 약정 안함
							</td>
						</tr>
						<tr>
							<th>당비납부 방법</th>
							<td>
								<!--<input type="radio" name="rd_pay_type" value="mobile" class="radio" onClick="js_pay_type('mobile')" <? if ($rs_pay_type == "mobile") { echo "checked";} ?> /> 휴대폰&nbsp;&nbsp;&nbsp;-->
								<input type="radio" name="rd_pay_type" value="cms" class="radio" onClick="js_pay_type('cms')" <? if ($rs_pay_type == "cms") { echo "checked";} ?>/> CMS&nbsp;&nbsp;&nbsp;
								<input type="radio" name="rd_pay_type" value="card" class="radio" onClick="js_pay_type('card')"  <? if ($rs_pay_type == "card") { echo "checked";} ?> /> 신용카드&nbsp;&nbsp;&nbsp;
								<input type="radio" name="rd_pay_type" value="cash" class="radio" onClick="js_pay_type('cash')"  <? if ($rs_pay_type == "cash") { echo "checked";} ?> /> 직접납부&nbsp;&nbsp;&nbsp;
							</td>
						</tr>

						<tr id="mobile_01" style="display:none">
							<th>휴대폰 번호</th>
							<td>
								<?=makeSelectBox($conn, "MOBILE","pay_mtel_01","80","","",$arr_pay_mtel[0])?> -
								<input type="text" id="pay_mtel_02" name="pay_mtel_02" maxlength="4" style="width:80px" value="<?=$arr_pay_mtel[1]?>"> - 
								<input type="text" id="pay_mtel_03" name="pay_mtel_03" maxlength="4" style="width:80px" value="<?=$arr_pay_mtel[2]?>">
							</td>
						</tr>
						<tr id="mobile_02" style="display:none">
							<th>통신사</th>
							<td>
								<?=makeRadioBox($conn,"MOBILE_COM","mobile_com", $rs_mobile_com)?>
							</td>
						</tr>

						<tr id="cms_01" style="display:none">
							<th>은행명</th>
							<td>
								<?=makeSelectBox($conn, "BANK_CODE","bank_code","200","은행을 선택하세요.","",$rs_bank_code)?>
							</td>
						</tr>
						<tr id="cms_02" style="display:none">
							<th>계좌번호</th>
							<td>
								<input type="text" id="bank_no" name="bank_no" style="width:200px" onkeyup="return isNumber(this)" value="<?=$rs_bank_no?>">
							</td>
						</tr>
						<tr id="cms_03" style="display:none">
							<th>예금주</th>
							<td>
								<input type="text" id="bank_name" name="bank_name" style="width:200px" value="<?=$rs_bank_name?>">
							</td>
						</tr>
						<tr id="cms_04" style="display:none">
							<th>생년월일</th>
							<td>
								<select name="c_sel_year" style="width:100px;">
									<option value="">년</option>
										<?
											$end_yy = date("Y",strtotime("0 day"));

											for ($k = $end_yy; $k > ($end_yy-110) ; $k--) {
										?>
									<option value="<?=$k?>" <?if($rs_cms_birth_yy==$k){?>selected<?}?>><?=$k?></option>
										<?
											}
										?>
								</select> 
								<?= makeSelectBox($conn,"MONTH","c_sel_month","100","월","",$rs_cms_birth_mm)?>
								<?= makeSelectBox($conn,"DAY","c_sel_day","100","일","",$rs_cms_birth_dd)?>
								<input type="hidden" name="c_birth" value="">
							</td>
						</tr>

						<tr id="card_01" style="display:none">
							<th>카드사</th>
							<td>
								<?=makeSelectBox($conn, "CARD_CODE","card_code","200","카드사을 선택하세요.","",$rs_card_code)?>
							</td>
						</tr>
						<tr id="card_02" style="display:none">
							<th>카드번호</th>
							<td>
								<input type="text" id="card_no" name="card_no" style="width:200px" onkeyup="return isNumber(this)" value="<?=$rs_card_no?>">
							</td>
						</tr>
						<tr id="card_03" style="display:none">
							<th>유효기간</th>
							<td>
								<input type="text" id="card_yy" name="card_yy" style="width:20px" maxlength="2" onkeyup="return isNumber(this)" value="<?=$rs_card_yy?>"> 년
								<input type="text" id="card_mm" name="card_mm" style="width:20px" maxlength="2" onkeyup="return isNumber(this)" value="<?=$rs_card_mm?>"> 월
							</td>
						</tr>
						<tr id="card_04" style="display:none">
							<th>카드소유주 이름</th>
							<td>
								<input type="text" id="card_name" name="card_name" style="width:200px" value="<?=$rs_card_name?>">
							</td>
						</tr>

						<tr id="card_05" style="display:none">
							<th>생년월일</th>
							<td>
								<select name="card_sel_year" style="width:100px;">
									<option value="">년</option>
										<?
											$end_yy = date("Y",strtotime("0 day"));

											for ($k = $end_yy; $k > ($end_yy-110) ; $k--) {
										?>
									<option value="<?=$k?>" <?if($rs_cms_birth_yy==$k){?>selected<?}?>><?=$k?></option>
										<?
											}
										?>
								</select> 
								<?= makeSelectBox($conn,"MONTH","card_sel_month","100","월","",$rs_cms_birth_mm)?>
								<?= makeSelectBox($conn,"DAY","card_sel_day","100","일","",$rs_cms_birth_dd)?>
								<input type="hidden" name="card_birth" value="">
							</td>
						</tr>

						<tr>
							<th>약정금액</th>
							<td>
								<?=makeRadioBox($conn,"CMS_AMOUNT","cms_amount", $rs_cms_amount)?>
								<div class="sp10"></div>
								<input type = 'radio' style='width:15px' class='chk' name= 'cms_amount' value='etc' <?if($rs_m_13=="etc"){?>checked<?}?>> 기타 : <input type="text" name="m_13" style="width:80px" <?if($rs_m_13=="etc"){?>value="<?=$rs_cms_amount?>"<?}?>> 원
							</td>
						</tr>

						<tr>
							<th>당비출금일</th>
							<td>
								<?=makeRadioBox($conn,"CMS_PAY_DAY","cms_pay_day", $rs_req_day)?>
							</td>
						</tr>

						<tr class="end">
							<th>동의서</th>
							<td>
								<?
									if (strlen($rs_m_signature) > 3) {
								?>
								<a href="/_common/new_download_file.php?menu=member&m_no=<?= $m_no ?>"><?=$rs_m_signature?></a>&nbsp;&nbsp;
								<select name="flag" style="width:70px;" onchange="javascript:js_fileView(this)">
									<option value="keep">유지</option>
									<option value="delete">삭제</option>
									<option value="update">수정</option>
								</select>
								<input type="hidden" name="old_file_nm" value="<?= $rs_m_signature?>">
								<div id="file_change" style="display:none;">
									<input type="file" id="file_nm" name="file_nm" style="width:60%;border:none" onChange="chk_file_size()"/><br />
									<font color="red">* 2 MB 이하로 등록해 주시시 바랍니다.</font>
								</div>
								<?
									} else {
								?>
								<input type="file" name="file_nm" style="width:60%;border:none" onChange="chk_file_size()">
								<input TYPE="hidden" name="flag" value="insert">
								<?
									}
								?>
							</td>
						</tr>
					</table>


					<div class="btnArea">
						<ul class="fRight">
							<? 
								if ($s_adm_no == $rs_reg_adm || $sPageRight_I == "Y") {
									echo '<li><a href="javascript:check_dup();"><img src="../images/btn/btn_ok.gif" alt="확인" /></a></li>';
									if ($m_no <> "") {
										echo '<li><a href="javascript:js_delete();"><img src="../images/btn/quit_btn.gif" alt="탈당" /></a></li>';
 									}
								}
							?>
							<li><a href="javascript:js_list();"><img src="../images/btn/btn_list.gif" alt="목록" /></a></li>
						</ul>
					</div>
					
		</form>
		<div class="sp10"></div>


		<form id="bbsList">

		<?
			$arr_rs_history = listModifyHistoryMember($conn, $m_no, "5");
			
			if (sizeof($arr_rs_history) > 0) {
		?>
					<h3 class="conTitle">당원 수정 이력</h3>

					<div class="expArea">
					<p class="fRight">
						<input type="button" name="aa" id="btn_history_all" value=" 전체 보기 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;" onclick="js_get_history_list('modify','ALL','<?=$m_no?>');">
						<input type="button" name="aa" id="btn_history_5" value=" 최근 5 건 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;display:none" onclick="js_get_history_list('modify','5','<?=$m_no?>');">
					</p>
					</div>

					<table class="secBtm">

						<colgroup>
							<col width="8%" />
							<col width="72%" />
							<col width="8%" />
							<col width="12%" />
						</colgroup>

						<thead>
						<tr>
							<th>순서</th>
							<th>변경 내용</th>
							<th>변경 관리자</th>
							<th>변경일</th>
						</tr>
						</thead>
						<tbody id="modify_list">
						<?
							for ($j = 0 ; $j < sizeof($arr_rs_history); $j++) {
								
								$M_MEMO				= trim($arr_rs_history[$j]["M_MEMO"]);
								$ADM_NAME			= trim($arr_rs_history[$j]["ADM_NAME"]);
								$UP_DATE			= trim($arr_rs_history[$j]["UP_DATE"]);
								
								$DEC_M_MEMO		= decrypt($key, $iv, $M_MEMO);
						?>
						<tr>
							<td><?=sizeof($arr_rs_history) - $j?></td>
							<td style="text-align:left"><?=nl2br($DEC_M_MEMO)?></td>
							<td><?=$ADM_NAME?></td>
							<td><?=$UP_DATE?></td>
						</tr>
						<?
							}
						?>
						</tbody>
					</table>
		<?
			}
		?>

		<?
			$arr_rs_pay_history = listPaymentHistoryMember($conn, $m_no, "5");
			
			if (sizeof($arr_rs_pay_history) > 0) {
		?>
					<h3 class="conTitle">당비 납부 정보</h3>

					<div class="expArea">
					<p class="fRight">
						<input type="button" name="aa" id="btn_payment_all" value=" 전체 보기 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;" onclick="js_get_history_list('payment','ALL','<?=$m_no?>');">
						<input type="button" name="aa" id="btn_payment_5" value=" 최근 5 건 " style="cursor:pointer;border:1px solid #a6a6a6;height:25px;display:none" onclick="js_get_history_list('payment','5','<?=$m_no?>');">
					</p>
					</div>

					<table class="secBtm">

						<colgroup>
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
							<col width="10%" />
						</colgroup>

						<thead>
						<tr>
							<th>순서</th>
							<th>신청년월</th>
							<th>납부방법</th>
							<th>약정당비</th>
							<th>납부당비</th>
							<th>출금일</th>
							<th>출수수료</th>
							<th>출금상태</th>
							<th>처리결과</th>
							<th>출금신청일</th>
						</tr>
						</thead>
						<tbody id="payment_list">
						<?
							for ($j = 0 ; $j < sizeof($arr_rs_pay_history); $j++) {
								
								$M_NO							= trim($arr_rs_pay_history[$j]["M_NO"]);
								$M_ID							= trim($arr_rs_pay_history[$j]["M_ID"]);
								$M_NAME						= trim($arr_rs_pay_history[$j]["M_NAME"]);
								$SIDO							= trim($arr_rs_pay_history[$j]["SIDO"]);
								$PAY_YYYY					= trim($arr_rs_pay_history[$j]["PAY_YYYY"]);
								$PAY_MM						= trim($arr_rs_pay_history[$j]["PAY_MM"]);
								$PAY_TYPE					= trim($arr_rs_pay_history[$j]["PAY_TYPE"]);
								$CMS_AMOUNT				= trim($arr_rs_pay_history[$j]["CMS_AMOUNT"]);
								$RES_CMS_AMOUNT		= trim($arr_rs_pay_history[$j]["RES_CMS_AMOUNT"]);
								$RES_PAY_DATE			= trim($arr_rs_pay_history[$j]["RES_PAY_DATE"]);
								$RES_PAY_DATE			= trim($arr_rs_pay_history[$j]["RES_PAY_DATE"]);
								$CMS_CHARGE 			= trim($arr_rs_pay_history[$j]["CMS_CHARGE"]);
								$RES_PAY_NO				= trim($arr_rs_pay_history[$j]["RES_PAY_NO"]);
								$PAY_RESULT				= trim($arr_rs_pay_history[$j]["PAY_RESULT"]);
								$PAY_RESULT_CODE	= trim($arr_rs_pay_history[$j]["PAY_RESULT_CODE"]);
								$PAY_RESULT_MSG		= trim($arr_rs_pay_history[$j]["PAY_RESULT_MSG"]);
								$SEND_FLAG				= trim($arr_rs_pay_history[$j]["SEND_FLAG"]);
								$REG_DATE					= trim($arr_rs_pay_history[$j]["REG_DATE"]);

								if ($PAY_TYPE == "B") $str_pay_type = "CMS";
								if ($PAY_TYPE == "C") $str_pay_type = "신용카드";
								if ($PAY_TYPE == "H") $str_pay_type = "휴대폰";
								if ($PAY_TYPE == "D") $str_pay_type = "직접납부";

								if ($PAY_RESULT == "Y") {
									$str_color = "navy";
								} else {
									$str_color = "red";
								}
								

						?>
						<tr>
							<td><?=sizeof($arr_rs_pay_history) - $j?></td>
							<td><?=$PAY_YYYY?>-<?=$PAY_MM?></td>
							<td><?=$str_pay_type?></td>
							<td style="text-align:right;padding-right:20px"><?=number_format($CMS_AMOUNT)?></td>
							<td style="text-align:right;padding-right:20px"><?=number_format($RES_CMS_AMOUNT)?></td>
							<td><?=$RES_PAY_DATE?></td>
							<td style="text-align:right;padding-right:20px"><?=number_format($CMS_CHARGE)?></td>
							<td><font color="<?=$str_color?>"><?=$PAY_RESULT?></font></td>
							<td><font color="<?=$str_color?>"><?=$PAY_RESULT_MSG?></font></td>
							<td><?=substr($REG_DATE ,0,10)?></td>
						</tr>
						<?
							}
						?>
						</tbody>
					</table>
		<?
			}
		?>


		</form>

				</section>

<iframe src="" name="ifr_hidden" id="ifr_hidden" frameborder="no" width="0" height="0" marginwidth="0" marginheight="0" border="0"></iframe>
	</section>
</section>
</div><!--wrapper-->
<a href="javascript:check_dup();"></a>
</body>
</html>

<?
	if (($rs_m_organization) || ($rs_party)) {
?>
	<script type="text/javascript">
	<!--
		js_party('<?=$group_cd_01?>', '<?=$group_cd_02?>', '<?=$group_cd_03?>', '<?=$group_cd_04?>', '<?=$group_cd_05?>');
	//-->
	</script>
	<?
}
?>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>