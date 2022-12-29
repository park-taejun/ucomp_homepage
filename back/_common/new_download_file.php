<?session_start();?>
<?ob_start();

#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/config.php";
#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";
	$conn = db_connection("r");
#=====================================================================
# common function, login_function
#=====================================================================
	require "../_classes/com/util/Util.php";
	require "../_classes/biz/board/board.php";
	require "../_classes/biz/files/files.php";
	require "../_classes/biz/member/member.php";
	require "../_classes/biz/webzine/webzine.php";

/**
* void WAF_WriteFile(string filename, string mimetype = null, mixed options = null);
* 
* 파일을 열어서 클라이언트에게 전송한다.
* 리턴값은 없고, 파일 전송이 완료되면 페이지 처리가 종료된다.
* 오류가 발생할 경우 상황에 맞는 HTTP 표준 오류(4xx, 5xx)가
* 클라이언트에게 전달되고 페이지 처리가 종료된다.
* 
* HTTP/1.1 의 다음 기능을 지원함
* 
* - Range 헤더 지원(파일의 일부분만 전송)
* 
* - If-Modified-Since 헤더 지원(변경된 경우만 전송)
* 
* 클라이언트의 캐시에 컨텐츠가 이미 있을 경우
* 마지막 확인 시간 전송해서 그 이후에 변경되었으면 전송을 하고 변경이 안되었으면
* 같은 컨텐츠가 다시 전송 되는 것을 피하기 위해서 사용한다.
* 변경이 되었으면 전송을 하고(200 OK), 변경 안되었으면 304 Not Modified 를 리턴한다.
* 
* - If-Unmodified-Since 헤더 지원(변경 안된 경우만 전송)
* 
* If-Unmodified-Since 헤더는 캐시에 컨텐츠의 일부만 있을 경우
* 마지막 확인 시간 전송해서 그 이후에 변경되지 않았으면 필요한 부분만 전송을 하고
* 변경이 안되었을 경우 전체를 받기 위해서 사용한다.(Range 헤더와 조합으로)
* 변경이 되었으면 412 Precondition Failed 을 리턴하고,
* 변경 안되었으면 전송한다. (200 OK 또는 206 Partial Content)
* 
* - If-Range 헤더 지원(변경 안된 경우는 부분전송, 변경된 경우는 전체 전송)
* 
* If-Range 헤더는 If-Unmodified-Since 와 비슷한데
* If-Unmodified-Since는 변경된 경우에는 412 Precondition Failed 오류로 요청을 끝내므로
* 새로 갱신된 파일을 받으려면 다시 요청을 해야한다.
* If-Range 는 변경이 안되었으면 부분전송을 하고, 변경된 경우는 전체 전송을 하도록 한다.
* Range 헤더가 같이 지정되지 않으면 의미가 없다.
* ※ HTTP/1.1 규약에는 엔티티 태그(Etags 헤더)를 시간 대신에 보낼 수도 있으나
*    이 함수는 엔티티 태그를 지원하지 않는다.
* 
* GET /_test/test_range.php HTTP/1.0          // wget은 HTTP/1.1 을
* User-Agent: Wget/1.8.2                      // 100% 지원하지 못하는 듯
* Host: image.cine21.com                      // If- 씨리즈 헤더는 직접지정해야함
* Connection: Keep-Alive
* Range: bytes=200-                           // Range 헤더가 지정됨
* If-Range: Fri, 19 May 2005 08:03:25 +0000   // 이전에 받은 시간
* 
* HTTP/1.1 200 OK                             // 그러나 200 OK(전체 전송)
* Date: Fri, 20 May 2005 09:20:43 GMT
* Server: Apache
* Content-Length: 512
* Last-Modified: Fri, 20 May 2005 05:03:25 +0000  // 새로 변경된 시간
* Accept-Ranges: bytes
* Keep-Alive: timeout=3, max=128
* Connection: Keep-Alive
* Content-Type: text/plain;charset=euc-kr
* 
* ※ HTTP/1.1 규약의 엔티티 태그 관련 Etags, If-Match, If-None-Match 등은 지원하지 않는다.
* 
* options:
*     disposition
*         Content-Disposition 헤더를 지정한다.
*         206 Partial Content, 304 Not Modified 일경우는 무시된다.
* 
*         WAF_WriteFile($filename, null, array('disposition' => 'attachment'));
* 
*     filename
*         Content-Disposition 헤더에 출력될 파일이름을 지정한다.
* 
*         $options = array(
*             'disposition' => 'attachment',
*             'filename' => 'a.txt',
*         );

*         WAF_WriteFile($filename, null, $options);
* 
*     headers
*         추가 헤더를 지정한다. 파일 전송이 성공될 경우만 실행된다.
* 
*         $options = array(
*             'headers' => array(
*                 'Expires'   => gmdate('r', time() + 86400), // 앞으로 24시간까지만 유효함
*             );
*         );
*         WAF_WriteFile($filename, null, $options);
* 
*     range
*         Range 헤더를 override 한다. 클라이언트에서 전달된
*         Range 헤더($_SERVER['HTTP_RANGE']) 대신 지정한 값을 쓴다.
* 
*         WAF_WriteFile($filename, null, array('range' => 'bytes=100-'));
* 
*         혹은 부분 전송 기능을 disable 시키기 위해서 사용할 수 있다.
* 
*         WAF_WriteFile($filename, null, array('range' => FALSE));
* 
*     check_func
*         파일 이름을 전달받아 boolean을 리턴하는 함수를 만들어
*         검사 함수로 전달할 수 있다.
* 
*         // 상대경로로 상위 디렉토리의 파일을 찾는 것을 금지
*         function SampleCallback($filename) {
*             return ($filename{0} == '/' || strpos($filename, '../') === FALSE);
*         }
*         WAF_WriteFile($filename, null, array('check_func' => 'SampleCallback'));
*/

	@extract($HTTP_POST_VARS); 
	@extract($HTTP_GET_VARS);
	@extract($HTTP_SESSION_VARS);
	@extract($HTTP_SERVER_VARS); 
	@extract($HTTP_ENV_VARS);

	extract($_POST);
	extract($_GET);

	global $BUFSIZ;
	$BUFSIZ = 8196; 

	function WAF_WriteFile($filename, $mimetype = null, $options = null) {
		
		$filename = trim($filename);

		if (!file_exists($filename)) {
			WAF_HTTPError(404);       // 404 Not Found
		} elseif (!is_file($filename) || !is_readable($filename)) {
			WAF_HTTPError(403);       // 403 Forbidden
		}
		
		// 만약 검사 함수가 지정되면 추가적인 검사를 수행
		if (is_callable($options['check_func'])) {
			if (!call_user_func($options['check_func'], $filename)) {
				WAF_HTTPError(403);       // 403 Forbidden
			}
		}

		// 전체 전송, 부분전송 공통 헤더 설정
		$mtime = filemtime($filename);

		// If-Modified-Since 처리
		if ($_SERVER['HTTP_IF_MODIFIED_SINCE']) {
			$if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
    
			if ($if_modified_since >= $mtime) {
				Header("HTTP/1.1 304 Not Modified");
				exit();
			}
		}

		// If-Unmodified-Since 처리
		if ($_SERVER['HTTP_IF_UNMODIFIED_SINCE']) {
			$if_modified_since = strtotime($_SERVER['HTTP_IF_UNMODIFIED_SINCE']);
    
			if ($if_modified_since < $mtime) {
				WAF_HTTPError(412); // 412 Precondition Failed
				exit();
			}
		}

		$options['headers']["Last-Modified"] = gmdate("r", $mtime);
  
		if ($mimetype == null)
			$mimetype = "application/octet-stream";
  
		$options['headers']["Content-Type"] = $mimetype;

		// options에 Range 헤더를 지정할 경우 클라이언트에서 온 정보를 무시한다.
		$range = (isset($options['range']))? $options['range']: $_SERVER['HTTP_RANGE'];

		// If-Range 처리
		if ($_SERVER['HTTP_IF_RANGE']) {
			$if_modified_since = strtotime($_SERVER['HTTP_IF_RANGE']);
		
			if ($if_modified_since < $mtime) {
				$range = '';       // 변경이 되었으므로 전체 전송을 하도록 한다.
			}
		}

		if ($range && preg_match('/bytes\s*=\s*(\d+)?\s*-\s*(\d+)?/i', $range, $part)) {
			$start = $part[1];
			$end = $part[2];
			$options['headers']["Accept-Ranges"] = "bytes";
			WAF_WritePartialFile($filename, $start, $end, $mimetype, $options);
		} else {       // Range 헤더가 없거나, 형식이 올바르지 않거나, 단위가 bytes 가 아닐 때
              // 그러나 range 지원이 중지되지 않았으면 Range 헤더를 지원한다는 것을 알려준다.
			if (!(isset($options['range']) && !$options['range']))
				$options['headers']["Accept-Ranges"] = "bytes";

			if ($options['disposition']) {
				$basename = ($options['filename'])? $options['filename']: basename($filename);
				$options['headers']["Content-Disposition"] = "{$options['disposition']}; filename=\"{$basename}\"";
			}

			WAF_WriteFullFile($filename, $mimetype, $options);
		}
		exit();
	}

/**
* 파일을 열어서 전체를 전송한다.
* 독립적으로 사용하지 말고 WAF_WriteFile()을 사용할 것
*/

	function WAF_WriteFullFile($filename, $mimetype = null, $options = null) {

		global $BUFSIZ;
		$length = filesize($filename);

		Header("HTTP/1.1 200 OK");
		Header("Content-Length: $length");
	
		if (count($options['headers']) > 0) {
			foreach ($options['headers'] as $header => $value)
			Header("$header: $value");
		}

		// 만약 출력된 것이 버퍼에 있으면 지운다.
		ob_end_clean();

		// GET, POST 만 실제 파일을 전송한다.
		if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {
			// 파일을 연다.
			$fp = fopen($filename, "rb");
    
			if (!$fp) {
				WAF_HTTPError(500);       // 500 Internal Server Error
			}

			while (!feof($fp)) {
				$buf = fread($fp, $BUFSIZ);
				$read = strlen($buf);
				print($buf);
			}
			fclose($fp);
		}
	}


	/**
	* 파일을 열어서 일부분을 전송한다.
	* 독립적으로 사용하지 말고 WAF_WriteFile()을 사용할 것
	*/
	function WAF_WritePartialFile($filename, $start, $end, $mimetype = null, $options = null) {
		global $BUFSIZ;

		$filesize = filesize($filename);
  
		if ($end == null)
			$end = $filesize - 1;

			// 지정한 Range 가 올바르지 않다.
		if ($end >= $filesize || $start > $end) {
			WAF_HTTPError(416);       // 416 Requested Range Not Satisfiable
		}

		$length = $end - $start + 1;

		Header("HTTP/1.1 206 Partial Content");
		Header("Content-Length: $length");
		Header("Content-Range: bytes {$start}-{$end}/{$filesize}");
  
		if (count($options['headers']) > 0) {
			foreach ($options['headers'] as $header => $value)
				Header("$header: $value");
		}

		// 만약 출력된 것이 버퍼에 있으면 지운다.
		ob_end_clean();

		// GET, POST 만 실제 파일을 전송한다.
		if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST')  {
			// 파일을 연다.
			$fp = fopen($filename, "rb");
    
			if (!$fp) {
				WAF_HTTPError(500);       // 500 Internal Server Error
			}

			// 시작 위치로 이동
			if ($start > 0) {
				$rs = fseek ($fp, $start, SEEK_SET);
      
				if ($rs < 0) {
					WAF_HTTPError(500);       // 500 Internal Server Error
				}
			}

			// $start 지점에서 $end 지점까지 쓰기
			$pos = $start;

			while (!feof($fp) && $pos < $end) {
				$buf = fread($fp, $BUFSIZ);
				$read = strlen($buf);

				if ($pos + $read < $end) {
					print($buf);
					$pos += $read;
				} else {
					$read = $end - $pos + 1;
					$buf = substr($buf, 0, $read);
					print($buf);
					$pos += $read;
				}
			}
			fclose($fp);
		}
	}

	global $HTTP_STATUS_MESSAGE;

	$HTTP_STATUS_MESSAGE = array (
		400       => 'Bad Request',					412       => 'Precondition Failed',
		401       => 'Unauthorized',				413       => 'Request Entity Too Large',
		402       => 'Payment Required',		414       => 'Request-URI Too Long',
		403       => 'Forbidden',						415       => 'Unsupported Media Type',
		404       => 'Not Found',						416       => 'Requested Range Not Satisfiable',
		405       => 'Method Not Allowed',	417       => 'Expectation Failed',
		406       => 'Not Acceptable',			500       => 'Internal Server Error',
		407       => 'Proxy Authentication Required',              501       => 'Not Implemented',
		408       => 'Request Timeout',			502       => 'Bad Gateway',
		409       => 'Conflict',						503       => 'Service Unavailable',
		410       => 'Gone',								504       => 'Gateway Timeout',
		411       => 'Length Required',			505       => 'HTTP Version Not Supported',
	);

	function WAF_HTTPError($status, $title = null, $message = null, $options = null) {

		global $HTTP_STATUS_MESSAGE;
		$status_message = $HTTP_STATUS_MESSAGE[$status];
		Header("HTTP/1.1 {$status} {$status_message}");
		Header("Content-Type: text/html");

		if ($message == null)
			$message = $status_message;

		// 버퍼를 클리어
		if ($options['error_page']) {
			ob_end_clean();
			include($options['error_page']);
			exit();
		} else {
			ob_end_clean();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>:: :</title>
<script> alert('첨부파일이 존재하지 않습니다.'); history.back(); </script>
</head>
<body>
<h1><?= "$status $status_message" . (($title)? " - $title": "") ?></h1>
<p><?= $message ?></p>
</body>
</html>
<?
	}
	exit();
}
	$filename_nm	=		trim($file_name);
	$filename_rnm	=		trim($filename_rnm);
	$str_path			=		trim($str_path);

	$filename_nm		= str_quote_smart($filename_nm);
	$filename_rnm		= str_quote_smart($filename_rnm);
	$str_path				= str_quote_smart($str_path);

	if ($str_path <> "") {
		$str_path =  $g_physical_path.$str_path;
	}

	$menu				=		trim($menu);
	// 공지사항
	$bb_code	=		trim($bb_code);
	$bb_no		=		trim($bb_no);
	// 배너
	$banner_no	=		trim($banner_no);
	// 기업
	$cp_no		=		trim($cp_no);
	$field		=		trim($field);

	$seq_no		=		trim($seq_no);

	$menu				= str_quote_smart($menu);
	$bb_code		= upper_quote_smart($bb_code);
	$bb_no			= quote_smart($bb_no);

	if ($menu == "goods") {
		
		$arr_rs = selectGoods($conn, $g_no);
		
		if ($field == "file_nm_100") {
			$filename_rnm		= trim($arr_rs[0]["FILE_NM_100"]); 
			$filename_nm		= trim($arr_rs[0]["FILE_NM_100"]); 
		}

		if ($field == "file_rnm_100") {
			$filename_rnm		= trim($arr_rs[0]["FILE_RNM_100"]); 
			$filename_nm		= trim($arr_rs[0]["FILE_RNM_100"]); 
		}

		if ($field == "file_path_100") {
			$filename_rnm		= trim($arr_rs[0]["FILE_PATH_100"]); 
			$filename_nm		= trim($arr_rs[0]["FILE_PATH_100"]); 
		}

		if ($field == "file_nm_150") {
			$filename_rnm		= trim($arr_rs[0]["FILE_NM_150"]); 
			$filename_nm		= trim($arr_rs[0]["FILE_NM_150"]); 
		}

		if ($field == "file_rnm_150") {
			$filename_rnm		= trim($arr_rs[0]["FILE_RNM_150"]); 
			$filename_nm		= trim($arr_rs[0]["FILE_RNM_150"]); 
		}

		$str_path = $g_physical_path."upload_data/goods/";
	}	

	if ($menu == "webzine") {
		
		$arr_rs = selectWebzine($conn, $seq_no);
		
		$rs_yyyy				= trim($arr_rs[0]["YYYY"]);
		$rs_mm					= trim($arr_rs[0]["MM"]);


		$filename_nm		= $rs_yyyy.".".$rs_mm.".webzine.pdf";
		$filename_rnm		= trim($arr_rs[0]["PDF_FILE"]); 

		$str_path = $g_physical_path."upload_data/webzine/";
	}	

	if ($menu == "banner") {
		
		$arr_rs = selectBanner($conn, $banner_no);
		
		$filename_rnm		= trim($arr_rs[0]["BANNER_IMG"]); 
		$filename_nm		= trim($arr_rs[0]["BANNER_REAL_IMG"]); 

		$str_path = $g_physical_path."upload_data/banner/";
	}	


	if ($menu == "member") {
		
		$arr_rs = selectMember($conn, $m_no);
		
		$filename_rnm		= trim($arr_rs[0]["M_SIGNATURE"]); 
		$filename_nm		= trim($arr_rs[0]["M_SIGNATURE"]); 

		$str_path = $g_physical_path."upload_data/member/";
	}	

	if ($menu == "files") {
		
		$arr_rs = selectFiles($conn, $file_no);
		
		if ($field == "pdf_file_nm") {
			$filename_nm		= trim($arr_rs[0]["PDF_FILE_RNM"]); 
			$filename_rnm		= trim($arr_rs[0]["PDF_FILE_NM"]); 
		}

		if ($field == "hwp_file_nm") {
			$filename_nm		= trim($arr_rs[0]["HWP_FILE_RNM"]); 
			$filename_rnm		= trim($arr_rs[0]["HWP_FILE_NM"]); 
		}

		$str_path = $g_physical_path."upload_data/files/";
	}	

	if ($menu == "board") {
		
		$arr_rs = selectBoard($conn, $bb_code, $bb_no);
		
		if (($field=="FILE_NM") || ($field=="file_nm")) {
			$filename_rnm		= trim($arr_rs[0]["FILE_NM"]); 
			$filename_nm		= trim($arr_rs[0]["FILE_RNM"]); 
		}else{
			$filename_rnm		= trim($arr_rs[0]["CATE_01"]); 
			$filename_nm		= trim($arr_rs[0]["CATE_02"]); 
		}
		$str_path = $g_physical_path."upload_data/board/";
			
	}	

	if ($menu == "board2") {
		
		$filename_rnm		= $file_nm; 
		$filename_nm		= $file_rnm; 

		$str_path = $g_physical_path."upload_data/board/";
			
	}	
	
	if ($menu == "boardfile") {
		
		$arr_rs = selectBoardFile($conn, $file_no);

		$file_no				= trim($arr_rs[0]["FILE_NO"]); 
		$filename_rnm		= trim($arr_rs[0]["FILE_NM"]); 
		$filename_nm		= trim($arr_rs[0]["FILE_RNM"]); 
		$filename_path	= trim($arr_rs[0]["FILE_PATH"]); 
		$bo_table				= trim($arr_rs[0]["bo_table"]); 

		// 다운로드 수 증가
		$result = updateBoardFileHitCnt($conn, $file_no);

		$pos = strrpos($filename_path, "./files");
		
		if ($bo_table) {
			
			$str_path = $g_old_data_path.$bo_table."/";

		} else {
			if($pos === false) {
				$str_path = $g_physical_path."upload_data/board/";
			} else {
				$filename_path = str_replace("./files", "/files", $filename_path);
				$filename_rnm	 = "";
				$str_path = $g_physical_path.$filename_path;
			}
		}
	}

	if ($menu == "main") {
		
		$arr_rs = selectMain($conn,$cate, $memo_no);
		
		$str_path = $g_physical_path."upload_data/board/";
		
		$filename_rnm	= trim($arr_rs[0]["IMAGE"]); 
		$filename_nm	= trim($arr_rs[0]["IMAGE_RE"]); 

	}	

	if ($menu == "publishing") {
		
		$arr_rs = selectPublishing($conn, $pb_no);
		
		$str_path = $g_physical_path."upload_data/publishing/";
		
		if ($field == "contracts_nm") {
			
			$filename_nm	= trim($arr_rs[0]["CONTRACTS_NM"]); 
			$filename_rnm	= trim($arr_rs[0]["CONTRACTS_RNM"]); 
		}
	}
	
	if ($menu == "company") {
		
		$arr_rs = selectCompany($conn, $cp_no);
		
		$str_path = $g_physical_path."upload_data/company/";
		
		if ($field == "contracts_nm") {
			
			$filename_nm	= trim($arr_rs[0]["CONTRACTS_NM"]); 
			$filename_rnm	= trim($arr_rs[0]["CONTRACTS_RNM"]); 
		}
			
	}

	if ($menu == "mojib") {
		
		require "../_classes/biz/mojib/mojib.php";
		
		$arr_rs = selectMojib($conn, $page_no);
		
		$str_path = $g_physical_path."upload_data/mojib/";
		
		if ($field == "title_img") {
			
			$filename_rnm	= trim($arr_rs[0]["TITLE_IMG"]); 
			$filename_nm	= trim($arr_rs[0]["TITLE_IMG_OVER"]); 
		}

		if ($field == "page_img") {
			
			$filename_rnm	= trim($arr_rs[0]["PAGE_IMG"]); 
			$filename_nm	= trim($arr_rs[0]["PAGE_IMG_OVER"]); 
		}
			
	}


	//$filename_rnm = iconv("euc-kr","utf-8",$filename_rnm);
	$filename_nm = iconv("utf-8","euc-kr",$filename_nm);
	//$filename_nm = iconv("utf-8","euc-kr",$filename_nm);

//	echo $filename_nm;
//	echo $filename_rnm;
	
	#$filename_nm = "ssss.xls";


	$options = array(
		'disposition' => 'attachment',
		'filename' => $filename_nm,
	);
	
$path_filename = $str_path.$filename_rnm;

//echo $path_filename;

WAF_WriteFile($path_filename, null, $options);

#=====================================================================
# DB Close
#=====================================================================
	sqlsrv_close($conn);
?>
