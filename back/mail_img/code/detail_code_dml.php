<?

	include "../admin_session_check.inc";
	include "../../dbconn.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	function isExist($sid, $spid)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_code_detail where dcode='$sid' and pcode = '$spid'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);

		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}

	function getMaxCode()  { 
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(dcode_no) CNT FROM tb_code_detail "; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;

		return $iNewid;
	
	}

	$sPcode = trim($pcode);
	$sDcode = trim($dcode);
	$sDcode_ext = trim($dcode_ext);
	$iDcode_no = trim($dcode_no);
	$sDcode_name = trim($dcode_name);
	#$sDcode_memo = trim($dcode_memo);

	$sView_chk = trim($view_chk);

	if ($mode == "add") {

		if(!isExist($sDcode, $sPcode) == 1) {
			
			$sMax = getMaxCode();

			$query = "insert into tb_code_detail (pcode, dcode, dcode_ext, dcode_no, dcode_name, dcode_seq, view_chk) values 
					  ('$sPcode', '$sDcode', '$sDcode_ext', '$sMax', '$sDcode_name', '0', 'N')";

			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "<script language=\"javascript\">\n
				alert('등록 되었습니다.');
				parent.frames[3].location = 'detail_code_list.php?pcode=$sPcode';
				</script>";
			exit;

		} else {
			echo "<script language=\"javascript\">\n
				alert('이미 등록된 코드값 입니다.');
				</script>";
			exit;
		}

	} else if ($mode == "mod") {

		$query = "update tb_code_detail set
								dcode_ext = '$sDcode_ext',
								dcode_name = '$sDcode_name'
		          where pcode = '$sPcode' and dcode_no = '$iDcode_no' ";
					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			parent.frames[3].location = 'detail_code_modify.php?page=$page&pcode=$sPcode&dcode_no=$iDcode_no' 
			</script>";
		exit;

	} else if ($mode == "del") {
		
		$query = "delete from tb_code_detail 
		          where pcode = '$sPcode' and dcode_no = '$iDcode_no'";

		mysql_query($query) or die("Query Error");
		
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'detail_code_list.php?pcode=$sPcode'
			</script>";
		exit;

	} else if ($mode == "chgView") {
		
		if ($sView_chk == "Y") {
			$sView_chk = "N";
		} else {
			$sView_chk = "Y";		
		}
		
		$query = "update tb_code_detail set
								view_chk = '$sView_chk'
		          where pcode = '$sPcode' and dcode_no = '$iDcode_no' ";
					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			parent.frames[3].location = 'detail_code_list.php?page=$page&pcode=$sPcode&dcode_no=$iDcode_no' 
			</script>";
		exit;
	
	}
?>