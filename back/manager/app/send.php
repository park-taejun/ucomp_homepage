<?session_start();?>
<?
# =============================================================================
# File Name    : admin_write.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2009.05.21
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "AP001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/util/AES2.php";
	require "../../_classes/biz/admin/admin.php";
	
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$key						= $_POST['key']!=''?$_POST['key']:$_GET['key'];
	$message				= $_POST['message']!=''?$_POST['message']:$_GET['message'];
	$title					= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	//$scheduleAt			= $_POST['scheduleAt']!=''?$_POST['scheduleAt']:$_GET['scheduleAt'];

	$reg_date_ymd		= $_POST['reg_date_ymd']!=''?$_POST['reg_date_ymd']:$_GET['reg_date_ymd'];
	$reg_date_hour	= $_POST['reg_date_hour']!=''?$_POST['reg_date_hour']:$_GET['reg_date_hour'];
	$reg_date_min		= $_POST['reg_date_min']!=''?$_POST['reg_date_min']:$_GET['reg_date_min'];
	
	//echo $key;

	if ($mode == "S") {

		date_default_timezone_set('Asia/Seoul'); 
		$offset = 0;
		$limit = 100;
		$curl = curl_init();

		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw'));
		curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7/' . $key);
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($curl);
		
		$message = json_decode($output);
		$message->scheduleAt = date('Y-m-d H:i:s',strtotime($message->scheduleAt)); // UTC 를 로컬 시간으로 변경 합니다.
		
		//echo $message->scheduleAt;
		
		$reg_date_ymd		= left($message->scheduleAt, 10);
		$reg_date_hour	= substr($message->scheduleAt, 11,2);
		$reg_date_min		= substr($message->scheduleAt, 14,2);
		
		$rs_message			= $message->message;
		$rs_title				= $message->title;
	}


	if ($reg_date_ymd == "") {
		$reg_date_ymd = date("Y-m-d",strtotime("0 day"));
	} 

	if ($reg_date_hour == "") {
		$reg_date_hour = date("H",strtotime("0 day"));
	} 

	if ($reg_date_min == "") {
		$reg_date_min = date("i",strtotime("0 day"));
	} 

	$scheduleAt = $reg_date_ymd." ".$reg_date_hour.":".$reg_date_min.":00";

	if ($mode == "I") { 

		date_default_timezone_set('Asia/Seoul'); 
		$offset = 0;
		$limit = 100;
		$curl = curl_init();

		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw','Content-Type:application/json'));
		curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7');
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


		$postData = array(
			"channel" => array(
				"default"   // 채널명 ( 고정 )
			),
			"msgtype" => "text",		// 메시지 종류
			"message" => $message,			 // 내용
			"title" => $title,				// 제목
			"data" => array(
			"menu" => ""		// 이동할 메뉴 없을 경우 비워두세요.
			),
			"scheduleAt" => $scheduleAt  // 예약 시간 ( 비워서 보낼 경우 즉시 발송 합니다. )
		);

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));

		$output = curl_exec($curl);

		if ($output === FALSE) {
			echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
		} else {
			$response = json_decode($output);
			if($response->status) {
				//echo "success";
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
</head>
<script>
	alert("메시지를 등록 하였습니다.");
	opener.js_reload();
	self.close();
</script>
<?	
				mysql_close($conn);
				exit;
			} else {
				echo $response->error;
			}
		}
	}


	if ($mode == "U") { 

		date_default_timezone_set('Asia/Seoul'); 
		$offset = 0;
		$limit = 100;
		$messageId = $key;
		$curl = curl_init();

		curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw','Content-Type:application/json'));
		curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7/' . $messageId);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$postData = array(
			"channel" => array(
				"default"   // 채널명 ( 고정 )
			),
			"msgtype" => "text",		// 메시지 종류
			"message" => $message,			 // 내용
			"title" => $title,				// 제목
			"data" => array(
			"menu" => ""		// 이동할 메뉴 없을 경우 비워두세요.
			),
			"scheduleAt" => $scheduleAt  // 예약 시간 ( 비워서 보낼 경우 즉시 발송 합니다. )
		);
		
		/*
		$postData = [
			"channel" => [
				"default"   // 채널명 ( 고정 )
			],
			"msgtype" => "text",        // 메시지 종류
			"message" => "text message",    // 내용
			"title" => "title2222", // 제목
			"data" => [
				"menu" => "/menu.php"   // 이동할 메뉴 없을 경우 비워두세요.
				],
			"scheduleAt" => "2018-06-10 23:00:00"  // 예약 시간
		];
		*/

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
		
		$output = curl_exec($curl);
		if ($output === FALSE) {
			echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
		} else {
			$response = json_decode($output);
			if($response->status) {

				//echo "success";
?>
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
</head>
<script>
	alert("메시지를 수정 하였습니다.");
	opener.js_reload();
	self.close();
</script>
<?	
				mysql_close($conn);
				exit;

			} else {
				echo $response->error;
			}
		}

	}



?>
<!-- Top Menu 시작 -->
<!DOCTYPE html PUBLIC "-//W3C//dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$g_charset?>" />
<title><?=$g_title?></title>
<link rel="stylesheet" href="../css/admin.css" type="text/css" />
<style type="text/css">
	html { overflow:hidden; }
	body,div,p,img,span,input,label,a{padding:0; margin:0;}
	img{border:0;}

	body {
	margin-left: 0px;
	margin-top: 0px;
 }
</style>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.19/themes/base/jquery-ui.css" type="text/css" media="all" />
<link href="../css/jquery-ui.css" type="text/css" media="all" rel="stylesheet"  />
<script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="../js/jquery.ui.core.js"></script>
<script type="text/javascript" src="../js/jquery.ui.widget.js"></script>
<script type="text/javascript" src="../js/jquery.ui.datepicker.js"></script>
<script language="javascript" type="text/javascript">

$(document).ready(function() {
	$(".date").datepicker({
		dateFormat: "yy-mm-dd"
		,minDate: new Date(2013, 4-1, 15)	//(연, 월-1, 일)
	//,maxDate: new Date(2012, 9-1, 14)	//(연, 월-1, 일)
	});
});

function js_send() {
	
	var frm = document.frm;
	
	if (frm.message.value.trim() == "") {
		alert("메시지는 필수 항목 입니다.");
		return;
	}

	frm.mode.value = "I";
	
	frm.action = "send.php";
	frm.method = "post";
	frm.target = "";
	frm.submit();
}

function js_modify_send() {
	
	var frm = document.frm;
	
	if (frm.message.value.trim() == "") {
		alert("메시지는 필수 항목 입니다.");
		return;
	}

	frm.mode.value = "U";
	
	frm.action = "send.php";
	frm.method = "post";
	frm.target = "";
	frm.submit();
}
</script>


</head>
<body id="popup_code">


<form name="frm" method="post">
<input type="hidden" name="mode" value="">
<input type="hidden" name="key" value="<?=$key?>">
<div id="popupwrap_code">
	<? if ($mode == "S") { ?>
	<h1>메시지 수정</h1>
	<div id="postsch_code">
		<h2>* 전송하실 메시지를 수정 합니다.</h2>
	<? } else { ?>
	<h1>메시지 등록</h1>
	<div id="postsch_code">
		<h2>* 전송하실 메시지를 등록 합니다.</h2>
	<? }  ?>
		<div class="addr_inp">

		<table cellpadding="0" cellspacing="0" width="95%">
			<tr>
				<td>
					<table cellpadding="0" cellspacing="0" border="0" width="100%" class="colstable">
		
						<colgroup>
							<col width="20%">
							<col width="80%">
						</colgroup>
						<tr>
							<th>예약일</th>
							<td>
								<input type="text" name="reg_date_ymd" value="<?=$reg_date_ymd?>" class="date" style="width:120px;" readonly="1">
								<?= makeSelectBox($conn,"TIME","reg_date_hour","125","","",$reg_date_hour)?>
								<select name="reg_date_min">
								<?
									for ($i = 0 ; $i < 60 ;$i++) {
										$str_min = right("0".$i,2);
								?>
									<option value= "<?=$str_min?>"  <? if ($reg_date_min == $str_min) { ?> selected<? } ?>  ><?=$str_min?> 분</option>
								<?
									}
								?>
								</select>
							</td>
						</tr>
						<tr>
							<th>메시지</th>
							<td>
								<input type="text" name="message" value="<?= $rs_message?>" style="width:90%;" class="txt" />
							</td>
						</tr>
						<tr>
							<th>제목</th>
							<td colspan="3">
								<input type="text" name="title" value="<?= $rs_title?>" style="width:90%;" class="txt" />
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		</div>
		
		<div class="btn">
				<? if ($mode == "S" ) {?>
					<? if ($sPageRight_U == "Y") {?>
			<a href="javascript:js_modify_send();"><img src="../images/admin/btn_modify.gif" alt="수정" /></a>
					<? } ?>
				<? } else {?>
					<? if ($sPageRight_I == "Y") {?>
			<!--<a href="javascript:js_send();" class="btn_type6">보내기</a>-->
			<a href="javascript:js_send();"><img src="../images/admin/btn_regist_02.gif" alt="등록" /></a>
					<? } ?>
				<? }?>
				<? if ($menu_no <> "") {?>
					<? if ($sPageRight_D == "Y") {?>
			<a href="javascript:js_delete();"><img src="../images/admin/btn_delete.gif" alt="삭제" /></a>
					<? } ?>
				<? }?>

		</div>

	</div>
</form>
</body>
</html>
<?
#=====================================================================
# DB Close
#=====================================================================
	mysql_close($conn);
?>