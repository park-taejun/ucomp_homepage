<?session_start();?>
<?
# =============================================================================
# File Name    : board_list.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2011.06.01
# Modify Date  : 
#	Copyright    : Copyright @기린그림 Corp. All Rights Reserved.
# =============================================================================

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$menu_right = "AP001"; // 메뉴마다 셋팅 해 주어야 합니다

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
	
	$mode						= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$key						= $_POST['key']!=''?$_POST['key']:$_GET['key'];
	$nPage					= $_POST['nPage']!=''?$_POST['nPage']:$_GET['nPage'];

	//echo $key;

	if ($mode == "D") {

		date_default_timezone_set('Asia/Seoul'); 
		//$offset = 0;
		//$limit = 100;
		$messageId = $key;
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); 
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw'));
		curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7/' . $messageId);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($curl, CURLOPT_FAILONERROR, true);
		curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$output = curl_exec($curl);
		
		if ($output === FALSE) {
			echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
		}
	}

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

<script>

	function js_send_message() {

		var url = "send.php";
		NewWindow(url, '보내기', '600', '353', 'NO');
	}


	function js_modify_message(key) {
		var url = "send.php?mode=S&key="+key;
		NewWindow(url, '보내기', '600', '353', 'NO');
	}



	function js_reload() {
		document.location = "list.php";
	}

	function js_delete_message(key) {

		var frm = document.frm;
		bDelOK = confirm('메시지를 삭제 하시겠습니까?');
		if (bDelOK==true) {
			frm.mode.value = "D";
			frm.key.value = key;
			frm.action = "list.php";
			frm.method = "post";
			frm.target = "";
			frm.submit();
		}
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

<form id="bbsList" name="frm" method="post" >
<input type="hidden" name="mode" value="">
<input type="hidden" name="key" value="">
			<fieldset>
				<legend class="conTitle"><?=$p_menu_name?>&nbsp;&nbsp;&nbsp;&nbsp;</legend>
				<div class="sp0"></div>
<?
	$nPageSize = 10;
	$nPageBlock	= 10;

	if ($nPage <> "" && $nPageSize <> 0) {
		$nPage = (int)($nPage);
	} else {
		$nPage = 1;
	}

	if ($nPage < 2) {
		$offset_val = 0;
	} else {
		$offset_val = $nPageSize * ($nPage-1);
	}

	
	
	//echo $nPage;

	date_default_timezone_set('Asia/Seoul'); 
	$offset = $offset_val;
	$limit = $nPageSize;
	$curl = curl_init();

	curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0); 
	curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);

	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-API-KEY: TBTczEe7fMIZkEOoAWrr4e1LuNJfi9Cw'));
	curl_setopt($curl, CURLOPT_URL, 'https://api.itsbee.io/v1/message/63d4a745-38da-4c3f-ad74-30f1fb296ff7?offset='.$offset.'&limit='.$limit);
	curl_setopt($curl, CURLOPT_FAILONERROR, true);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($curl);

	if ($output === FALSE) {
		echo 'An error has occurred: ' . curl_error($curl) . PHP_EOL;
	} else {

	//echo $output;

	$messages = json_decode($output);

	$total_Cnt =  $messages->total;  //  전체 갯수

	$nTotalPage = (int)(($total_Cnt - 1) / $nPageSize + 1) ;

?>
					<div class="expArea">
						<ul class="fLeft">
							<li class="total">총 <?=number_format($total_Cnt)?>건</li>
						</ul>

						<p class="fRight">
							<a href="javascript:js_send_message();" class="btn_type6">보내기</a>
						</p>
					</div>

				<table summary="이곳에서 <?=$p_menu_name?> 관리하실 수 있습니다">
					<caption><?=$p_menu_name?></caption>
					<colgroup>
						<col width="10%" /> <!-- 몽고키값 -->
						<col width="11%" />	<!-- 생성일 -->
						<col width="20%" /> <!-- 키값 -->
						<!--<col width="5%" />--> <!-- 채널명 -->
						<col width="15%" /> <!-- 제목 -->
						<col width="20%" /> <!-- 메시지 -->
						<!--<col width="10%" />--> <!-- 설명 -->
						<col width="9%" /> <!-- 예약시간 -->
						<!--<col width="10%" />--> <!-- data -->
						<col width="5%" /> <!-- 상태 -->
						<col width="5%" /> <!-- 수정 -->
						<col width="5%" /> <!-- 삭제 -->
					</colgroup>
					<thead>
					<tr>
						<th scope="col">몽고키값</th>
						<th scope="col">생성일</th>
						<th scope="col">키값</th>
						<!--<th scope="col">채널명</th>-->
						<th scope="col">제목</th>
						<th scope="col">메시지</th>
						<!--<th scope="col">설명</th>-->
						<th scope="col">예약시간</th>
						<!--<th scope="col">data</th>-->
						<th scope="col">상태</th>
						<th scope="col">수정</th>
						<th scope="col">삭제</th>
					</tr>
					</thead>
					<tbody>
<?

	foreach($messages->data as $message) {
		
		$message->scheduleAt = date('Y-m-d H:i:s',strtotime($message->scheduleAt)); // UTC 를 로컬 시간으로 변경 합니다.

		//for ($i = 0 ; $i < sizeof($messages) ; $i++) {

		$message->createdAt = date('Y-m-d H:i:s',strtotime($message->createdAt)); // UTC 를 로컬 시간으로 변경 합니다.
			
			if ($message->status == "1") {
				$str_message = "성공";
			} else if ($message->status == "2") {
				$str_message = "실패";
			} else if ($message->status == "3") {
				$str_message = "진행중";
			}
?>
		<tr> 
			<td><?=$message->_id?></td>
			<td><?=$message->createdAt?></td>
			<td class="tit"><?=$message->id?></td>
			<!--<td><?=$message->channel?></td>-->
			<td class="tit"><?=$message->title?></td>
			<td class="tit"><?=$message->message?></td>
			<!--<td class="tit"><?=$message->description?></td>-->
			<td><?=$message->scheduleAt?></td>
			<!--<td><?=$message->data?></td>-->
			<td><?=$str_message?></td>
			<td><a href="javascript:js_modify_message('<?=$message->id?>');" class="btn_type5">수정</a></td>
			<td><a href="javascript:js_delete_message('<?=$message->id?>');" class="btn_type5">삭제</a></td>
		</tr>
<?
		//}
				
//		echo "<br>";
//		echo "<br>";
//		print_r($message);
	}
}
/*
    나머지 값은 무시하셔도 됩니다.
    [_id] => 몽고키값
    [createdAt] => 생성일
    [id] => 키값
    [channel] => 채널명
    [message] => 메시지
    [title] => 제목
    [description] => 설명
    [scheduleAt] => 예약시간 (UTC 기준)
    [data] => Array
        (
            [0] => stdClass Object
                (
                    [menu] => /menu.php // 이동할 메뉴명
                )

        )
    [status] => 상태 ( 1: 성공, 2: 실패, 3: 진행중 )
*/
?>
					</tbody>
				</table>
				<br><br>
				<div id="bbspgno">
					<?
						//$nTotalPage = $total_Cnt;
					?>
					<?= Image_PageList($_SERVER[PHP_SELF],$nPage,$nTotalPage,$nPageBlock,$strParam) ?>
				</div>
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