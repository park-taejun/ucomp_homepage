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

	function isExist($sid)  { 
		
		$b = 0;
		$sqlstr = "SELECT COUNT(*) CNT FROM tb_code_parent where pcode='$sid'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);

		if ($row["CNT"] > 0) {
			$b = 1;
		}
		
		return $b;
	
	}

	function getMaxCode()  { 
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(pcode_no) CNT FROM tb_code_parent "; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;

		return $iNewid;
	
	}

	$sPcode = trim($pcode);
	$iPcode_no = trim($pcode_no);
	$sPcode_name = trim($pcode_name);
	$sPcode_memo = trim($pcode_memo);

	if ($mode == "add") {

		if(!isExist($sPcode) == 1) {
			
			$sMax = getMaxCode();

			$query = "insert into tb_code_parent (pcode, pcode_no, pcode_name, pcode_memo) values 
					  ('$sPcode', '$sMax', '$sPcode_name', '$sPcode_memo')";

			mysql_query($query) or die("Query Error");
			mysql_close($connect);

			echo "<script language=\"javascript\">\n
				alert('등록 되었습니다.');
				parent.frames[3].location = 'parent_code_list.php';
				</script>";
			exit;

		} else {
			echo "<script language=\"javascript\">\n
				alert('이미 등록된 코드값 입니다.');
				</script>";
			exit;
		}
	
	} else if ($mode == "mod") {

		$query = "update tb_code_parent set 
								pcode_name = '$pcode_name',
								pcode_memo = '$pcode_memo'
		          where pcode_no = '$iPcode_no'";
					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			parent.frames[3].location = 'parent_code_modify.php?page=$page&pcode_no=$pcode_no';
			</script>";
		exit;

	} else if ($mode == "del") {
		
		$query = "delete from tb_code_parent 
		          where pcode_no = '$iPcode_no'";

		mysql_query($query) or die("Query Error");
		
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'parent_code_list.php';
			</script>";
		exit;

	}
?>
