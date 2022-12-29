<?session_start();?>
<?
	extract($_POST);
	extract($_GET);
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

#==============================================================================
# Confirm right
#==============================================================================
	$menu_right = "EV002"; // 메뉴마다 셋팅 해 주어야 합니다

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
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/event/event.php";
	
	$mode = trim($mode);
	$seq_no = trim($seq_no);


	if ($mode == "SEND") {
		
		$arr_rs = selectEvent($conn, $seq_no);

		$rs_title	= trim($arr_rs[0]["TITLE"]); 
		$rs_all_flag	= trim($arr_rs[0]["ALL_FLAG"]); 

		$callback = "16667905";
		$stat = "0";
			
		$text = $rs_title;

		$arr_sms = sendSms($conn, $seq_no, $rs_all_flag);
		
		if (sizeof($arr_sms) > 0) {

			for($j = 0 ; $j < sizeof($arr_sms); $j++) {
				$rs_name	= trim($arr_sms[$j]["MEM_NM"]); 
				$rs_hp		= trim($arr_sms[$j]["HP"]); 
				
				$rs_title = str_replace("#NAME", $rs_name, $rs_title);
				$rs_hp = str_replace("-", "", $rs_hp);

				$str = $rs_title;
		
				$buf_k = 0;
				$buf_e = 0;
				for($i=0,$maxi=strlen($str); $i<$maxi; $i++) {
					if (ord($str[$i])< 128) {
						$buf_e++;
					}else{
						$buf_k++;
					}
				}
		
				$total_nick = $buf_k + $buf_e;
		
				if ($total_nick > 80) {
					//LMS
					$msg_type = "3";
				} else {
					//SMS
					$msg_type = "1";
				}

				$query = "insert into msg_queue (msg_type, dstaddr, callback, stat, subject,text,request_time) values 
									('$msg_type','$rs_hp','$callback','$stat','','$rs_title',now() ) ";
				
				mysql_query($query,$conn);

			}
		}
		
		$query = "update TBL_EVENT set USE_TF ='N' WHERE SEQ_NO = '$seq_no' ";
		mysql_query($query,$conn);
		

	}
#====================================================================
# DB Close
#====================================================================
	$url = "event_list.php";

	alert("설문이 전송 되었습니다.",$url);

	mysql_close($conn);
?>
