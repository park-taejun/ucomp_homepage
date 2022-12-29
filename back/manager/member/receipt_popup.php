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
	require "../../_classes/biz/payment/payment.php";

	if($m_no){
		
		$today_yy=date("Y");
		$today_mm=date("m");
		$today_dd=date("d");

		$arr_rs = selectMember($conn, $m_no);
		
		$rs_m_no						= trim($arr_rs[0]["M_NO"]); 
		$rs_m_id						= trim($arr_rs[0]["M_ID"]); 
		$rs_m_name					= trim($arr_rs[0]["M_NAME"]); 
		$rs_m_jumin					= trim($arr_rs[0]["M_JUMIN"]);
		$rs_m_zip1					= trim($arr_rs[0]["M_ZIP1"]); 
		$rs_m_addr1					= trim($arr_rs[0]["M_ADDR1"]); 
		$rs_m_addr2					= trim($arr_rs[0]["M_ADDR2"]); 

		$strJumin = decrypt($key, $iv, $rs_m_jumin);

			$con_del_tf = "N";
	
			if ($order_field == "")
				$order_field = "M_DATETIME";
			
			if ($order_str=="")
				$order_str = "DESC";

		#============================================================
		# Page process
		#============================================================

			if (($nPage <> "") && ($nPage <> 0)) {
				$nPage = (int)($nPage);
			} else {
				$nPage = 1;
			}

			if ($nPageSize <> "") {
				$nPageSize = (int)($nPageSize);
			} else {
				$nPageSize = 20000;
			}

			$nPageBlock	= 10;

			//$is_direct = "N";
			$search_field="M_ID";
			$search_str=$rs_m_id;

		#===============================================================
		# Get Search list count
		#===============================================================
			$nListCnt =totalCntCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $pay_reason, $Ngroup_cd, $p_seq_no, $search_field, $search_str);

			$nTotalPage = (int)(($nListCnt - 1) / $nPageSize + 1) ;

			if ((int)($nTotalPage) < (int)($nPage)) {
				$nPage = $nTotalPage;
			}
			
			//echo $nPage;
			$arr_rs = listCmsPayment($conn, $is_direct, $sel_pay_yyyy, $sel_pay_mm, $sel_pay_day, $sel_pay_type, $sel_area_cd, $sel_party, $sel_pay_state, $pay_reason, $Ngroup_cd, $p_seq_no, $search_field, $search_str, $nPage, $nPageSize, $nListCnt);

			$result_log = insertUserLog($conn, 'admin', $_SESSION['s_adm_id'], $_SERVER['REMOTE_ADDR'], "CMS 당비 영수증출력", "List");
			

	}else{
		?>
		<script type="text/javascript">
		<!--
			alert('잘못된 접근입니다. 처음부터 다시 시도해주세요.');
			self.close();
		//-->
		</script>
		<?
		die;
	}
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>:::</title>
<style>
/* 기부금 영수증 */
@import url("http://fonts.googleapis.com/earlyaccess/nanumgothic.css");
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td, figure { margin:0; padding:0;}
table { border-collapse:collapse; border-spacing:0;width:100%;}
body, table, th, td, select, textarea, dl, dt, dd, ul, li, button { font-family: 'Nanum Gothic', 'NanumGothic', '나눔고딕'; font-size: 11px; line-height:1.2em; color: #333; }
.contribution { clear:both;width:100%;}
.contribution p{ margin-bottom:10px; }
.contribution .contribution_tb { position:relative;width:100%;box-sizing:border-box;padding:10px 0 0; }
.contribution .contribution_tb h5 { font-size:18px;font-weight:bold;letter-spacing:10px;padding:5px 0;text-align:center; }
.contribution .contribution_tb > dl { position:absolute;top:10px;left:10px;width:200px;border:1px solid #666; }
.contribution .contribution_tb > dl > dt { float:left;border-right:1px solid #666;padding:7px 20px;margin-right:20px; }
.contribution .contribution_tb > dl > dd { padding:7px 20px; }
.contribution .contribution_tb table.table01 { margin-top:20px; }
.contribution .contribution_tb table.table01 th { padding:8px 0 8px 25px;font-size:12px;font-weight:bold;border-top:1px solid #666;text-align:left; }
.contribution .contribution_tb table.table01 td { padding:5px 20px;border-top:1px solid #666;border-left:1px solid #666; }
.contribution .contribution_tb table.table01 td.foot { border-top:none; }
.contribution .contribution_tb table.table01 td.fst { padding:5px 0;text-align:center; }
.contribution .contribution_tb table.table01 td:first-child { border-left:none; }
.contribution .contribution_tb table.table01 td.pdNone { padding:0 0; }
.contribution .contribution_tb table.table02 th { padding:5px 0 5px 0;background:none;font-size:11px;font-weight:bold;border-top:none;border-left:1px solid #666;text-align:center; }
.contribution .contribution_tb table.table02 td { padding:5px 20px;border-top:1px solid #666;border-left:1px solid #666;text-align:center; }
.contribution .contribution_tb table.table02 td.end { padding:15px 20px; }
.contribution .contribution_tb table.table02 th:first-child, .contribution .contribution_tb table.table02 td:first-child { border-left:none; }
.contribution .contribution_tb table.table02 td div.fl { text-align:left;margin:5px 0 15px 20px;line-height:2em; }
.contribution .contribution_tb table.table02 td div.fr { clear:both;text-align:right;margin:0 20px 0 0;line-height:2em; }
.contribution .contribution_tb table.table02 td div span { display:inline-block;width:80px;text-align:center; }
.contribution .contribution_tb table.table02 td div span.name { width:180px; }
.contribution .contribution_tb td input { border:1px solid #d6974e;color:#333; }
</style>
</head>

<body onload="window.print();">
<div class="contribution">
	<p>소득세법 시행규칙 [ 별지 제45호의2서식 ] (개정 2014.3.14)</p>
	<div class="contribution_tb">
		<dl>
			<dt>일련번호</dt>
			<dd></dd>
		</dl>
		<h5>기부금 영수증</h5>
		<table class="table01">
			<colgroup>
				<col width="15%" />
				<col width="35%" />
				<col width="15%" />
				<col width="35%" />
			</colgroup>
			<tr>
				<th colspan="4">1. 기부자</th>
			</tr>
			<tr>
				<td class="fst">성 명 (법인명)</td>
				<td><?=$rs_m_name?></td>
				<td class="fst">주민등록번호<br />
				(사업자등록번호)</td>
				<td><?=$strJumin?></td>
			</tr>
			<tr>
				<td class="fst">주 소 (소재지)</td>
				<td colspan="3"><?=$rs_m_zip1?> <?=$rs_m_addr1?> <?=$rs_m_addr2?></td>
			</tr>
			<tr>
				<th colspan="4">2. 기부금 단체</th>
			</tr>
			<tr>
				<td class="fst">단 체 명</td>
				<td>민중연합당</td>
				<td class="fst">사업자등록번호<br />
				(고유번호)</td>
				<td>140-82-79738</td>
			</tr>
			<tr>
				<td class="fst">소 재 지</td>
				<td>서울시 영등포구 국회대로 664, 한흥빌딩3층</td>
				<td class="fst">기부금공제대상<br />
				기부금단체 근거법령</td>
				<td></td>
			</tr>
			<tr>
				<th colspan="4">3. 기부금 모집처(언론기관 등)</th>
			</tr>
			<tr>
				<td class="fst">단 체 명</td>
				<td>민중연합당</td>
				<td class="fst">사업자등록번호</td>
				<td>140-82-79738</td>
			</tr>
			<tr>
				<td class="fst">소 재 지</td>
				<td colspan="3">07247 서울시 영등포구 국회대로 664, 한흥빌딩3층</td>
			</tr>
			<tr>
				<th colspan="4">4. 기부내용</th>
			</tr>
			<tr>
				<td colspan="4" class="pdNone">
					<table class="table02">

						<tr>
							<th rowspan="2">유형</th>
							<th rowspan="2">코드</th>
							<th rowspan="2">구분</th>
							<th rowspan="2">연월일</th>
							<th colspan="3" style="border-bottom:1px solid #444;">내용</th>
							<th rowspan="2">금액</th>
						</tr>
						<tr>
							<th style="border-left:1px solid #444;">품명</th>
							<th>수량</th>
							<th>단가</th>
						</tr>
						<?
							$min_rows=12-sizeof($arr_rs);
							if (sizeof($arr_rs) > 0) {
								
								$tot_amount=0;
								for ($j = 0 ; $j < sizeof($arr_rs); $j++) {
									
									//A.MEM_NO, A.MEM_TYPE AS M_MEM_TYPE, A.MEM_NM, A.MEM_NICK, 
									//A.MEM_ID, A.BIRTH_DATE, A.HPHONE, A.USE_TF, B.SEQ_NO, B.COMM_NO, B.MEM_TYPE

									$rn								= trim($arr_rs[$j]["rn"]);
									
									$SEQ_NO							= trim($arr_rs[$j]["SEQ_NO"]);
									$M_NO							= trim($arr_rs[$j]["M_NO"]);
									$M_ID							= trim($arr_rs[$j]["M_ID"]);
									$M_NAME						= trim($arr_rs[$j]["M_NAME"]);
									$SIDO							= trim($arr_rs[$j]["SIDO"]);
									$PAY_YYYY					= trim($arr_rs[$j]["PAY_YYYY"]);
									$PAY_MM						= trim($arr_rs[$j]["PAY_MM"]);
									$PAY_TYPE					= trim($arr_rs[$j]["PAY_TYPE"]);
									$CMS_AMOUNT				= trim($arr_rs[$j]["CMS_AMOUNT"]);
									$RES_CMS_AMOUNT		= trim($arr_rs[$j]["RES_CMS_AMOUNT"]);
									$RES_PAY_DATE			= trim($arr_rs[$j]["RES_PAY_DATE"]);
									$RES_PAY_DATE			= trim($arr_rs[$j]["RES_PAY_DATE"]);
									$CMS_CHARGE 			= trim($arr_rs[$j]["CMS_CHARGE"]);
									$RES_PAY_NO				= trim($arr_rs[$j]["RES_PAY_NO"]);
									$PAY_RESULT				= trim($arr_rs[$j]["PAY_RESULT"]);
									$PAY_RESULT_CODE	= trim($arr_rs[$j]["PAY_RESULT_CODE"]);
									$PAY_RESULT_MSG		= trim($arr_rs[$j]["PAY_RESULT_MSG"]);
									$SEND_FLAG				= trim($arr_rs[$j]["SEND_FLAG"]);
									$REG_DATE					= trim($arr_rs[$j]["REG_DATE"]);
									$M_BIRTH					= trim($arr_rs[$j]["M_BIRTH"]);
									$M_HP							= trim($arr_rs[$j]["M_HP"]);
									$PAY_REASON	= trim($arr_rs[$j]["PAY_REASON"]);

									$str_m_tel = decrypt($key, $iv, $M_HP);

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
							<td>정치자금</td>
							<td>20</td>
							<td>당비</td>
							<td><?=$PAY_YYYY?>/<?=$PAY_MM?></td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td><?=number_format($RES_CMS_AMOUNT)?></td>
						</tr>
						<?			
								$tot_amount=$tot_amount+$RES_CMS_AMOUNT;
								}
								for($i=1;$i <=$min_rows; $i++){
								?>
									<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
								<?
								}
							} else { 
								for($i=1;$i <13; $i++){
						?>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
						<? 
								}
							}
						?>
						<tr>
							<td colspan="7">계</td>
							<td><?=number_format($tot_amount)?></td>
						</tr>
						<tr>
							<td colspan="8">
								<div class="fl">「소득세법」 제34조, 「조세특례제한법」 제76조ㆍ제88조의4 및 「법인세법」 제24조에 따른 기부금을<br />
								위와 같이 기부하였음을 증명하여 주시기 바랍니다.</div>
								<div class="fr">
									<span><?=$today_yy?></span>년 <span><?=$today_mm?></span>월 <span><?=$today_dd?></span>일<br />
									신청인 <span class="name">&nbsp;</span> (서명 또는 인)
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="8" class="end">위와 같이 기부금을 기부하였음을 증명합니다.</td>
						</tr>
						<tr>
							<td colspan="8" class="foot">
								<div class="fr">
									<span><?=$today_yy?></span>년 <span><?=$today_mm?></span>월 <span><?=$today_dd?></span>일<br />
									기부금 수령인 <span class="name">&nbsp;</span> (서명 또는 인)
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>
</div>

</body>
</html>
<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);
?>
