<?session_start();?>
<?
header("x-xss-Protection:0");
header('Content-Type: text/html; charset=UTF-8');

require "config.php";

function multiupload($filearray, $cnt, $targetdir, $max_size = 2 /* MByte */, $allowext) {

	$max_size = $max_size * 1024 * 1024;    // 바이트로 계산한다. 1MB = 1024KB = 1048576Byte

	if (!file_exists($targetdir)) { 
		mkdir($targetdir, 0707);
		chmod($targetdir, 0707);
	}

	if($filearray['size'][$cnt] > $max_size){

		return "err_file_size";

	} else {

		$file_ext = end(explode('.', $filearray['name'][$cnt]));
		$file_real_name = str_replace(".".$file_ext,"",$filearray['name'][$cnt]);

		if(in_array(strtolower($file_ext), $allowext)) { // 확장자를 검사한다.
			
			//공백 _ 로 대치 
			$temp_file_name = str_replace(" ","_",$filearray['name'][$cnt]);

			//한글 파일명 처리를 위해 임시 파일명을 날짜로 만듦
			$fn_rand = mt_rand (0, (strlen ($temp_file_name)));
			$writeday = date("YmdHis",strtotime("0 day"));
			$temp_file_name = $writeday."_".$fn_rand.".".$file_ext;

			$file_name = get_filename_check($targetdir, $temp_file_name);

			$path = $targetdir . '/' . $file_name;   
		
			if(move_uploaded_file($filearray['tmp_name'][$cnt], $path)) {
				return $file_name;
			}
			else return "err_file_upload";
				
		} else {

			if ($file_ext!="") {
				return "err_file_ext";
			}
		}
	}
}

//파일 중복 체크
function get_filename_check($filepath, $filename) { 

	if (!preg_match("'/$'", $filepath)) $filepath .= '/'; 
	if (is_file($filepath . $filename)) { 

		preg_match("'^([^.]+)(\[([0-9]+)\])(\.[^.]+)$'", $filename, $match); 

		if (empty($match)) { 

			$filename = preg_replace("`^([^.]+)(\.[^.]+)$`", "\\1[1]\\2", $filename); 
		} 
		else{ 
			$filename = $match[1] . '[' . ($match[3] + 1) . ']' . $match[4]; 
		} 

		return get_filename_check($filepath, $filename); 
	} 
	else {
		return $filename; 
	} 
} 

$file_cnt = count($_FILES["files"]["name"]);

#====================================================================
$savedir1 = $g_physical_path."upload_data/board";
#====================================================================

$arr_file_names = array();
$arr_file_state = array();
$arr_file_messages = array();
$arr_cnt = 0;

for($i=0; $i < $file_cnt; $i++) {

	$file_name	= multiupload($_FILES["files"], $i, $savedir1, 10 , array('gif','jpeg','jpg','png','xls','xlsx','doc','docs','ppt','pptx','pdf','zip','hwp','vnd'));

	$file_rname	= $_FILES["files"]["name"][$i];
	$file_size	= $_FILES["files"]["size"][$i];
	$file_ext		= end(explode('.', $_FILES["files"]["name"][$i]));

	if ($file_name == "err_file_size") {
		$arr_file_messages[0] = "The file size is too large.";
	} else if ($file_name == "err_file_upload") {
		$arr_file_messages[0] = "File upload failed";
	} else if ($file_name == "err_file_ext") {
		$arr_file_messages[0] = "File type is not in white list";
	} else {
		if ((strtolower($file_ext) == "gif") || (strtolower($file_ext) == "jpeg") || (strtolower($file_ext) == "jpg") || (strtolower($file_ext) == "png")) {
			$arr_file_names[$arr_cnt] = $file_name;
			$arr_file_state[$arr_cnt] = true;
			$arr_cnt = $arr_cnt + 1;
		} else {
			$arr_file_names[$arr_cnt] = $file_name;
			$arr_file_state[$arr_cnt] = false;
			$arr_cnt = $arr_cnt + 1;
		}
	}
}

$INS_DATE = date("Y-m-d H:i:s",strtotime("0 day"));

if ($arr_file_messages[0] == "") {

	$arr_data =  array("baseurl"=>"/upload_data/board/","messages"=>$arr_file_messages,"files"=>$arr_file_names,"isImages"=>$arr_file_state,"code"=>220);
	$arr_result = array("success"=>true, "time"=>$INS_DATE, "data"=>$arr_data);

} else {

	$arr_data =  array("messages"=>$arr_file_messages,"code"=>403);
	$arr_result = array("success"=>false, "time"=>$INS_DATE, "data"=>$arr_data);
	
}

echo json_encode($arr_result);

?>