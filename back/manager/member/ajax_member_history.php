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

#====================================================================
# Request Parameter
#====================================================================

	#List Parameter
	$mode	= trim($mode);
	$cnt	= trim($cnt);
	$m_no	= trim($m_no);

	if ($mode == "modify") {

		$arr_rs_history = listModifyHistoryMember($conn, $m_no, $cnt);
		
		if (sizeof($arr_rs_history) > 0) {
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
		}

	}

	if ($mode == "payment") {

		$arr_rs_pay_history = listPaymentHistoryMember($conn, $m_no, $cnt);
		
		if (sizeof($arr_rs_pay_history) > 0) {

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
				$REFUND_AMOUNT		= trim($arr_rs_pay_history[$j]["REFUND_AMOUNT"]);

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
					<td style="text-align:right;padding-right:20px"><?=number_format($REFUND_AMOUNT)?></td>
					<td><font color="<?=$str_color?>"><?=$PAY_RESULT?></font></td>
					<td><font color="<?=$str_color?>"><?=$PAY_RESULT_MSG?></font></td>
					<td><?=substr($REG_DATE ,0,10)?></td>
				</tr>
			<?
			}
		}
	}

#====================================================================
# DML Process
#====================================================================

#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>