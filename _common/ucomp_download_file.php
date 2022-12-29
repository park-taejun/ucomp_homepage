<?session_start();?>
<?ob_start();

#====================================================================
# common_header Check Session
#====================================================================
	require "../_common/config.php";

#=====================================================================
# common function, login_function
#=====================================================================
	require "../_classes/com/util/Util.php";

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

	$str_path = $g_physical_path;

	$filename_nm = "introduce.pdf";
	$filename_rnm = "introduce.pdf";

	$filename_rnm = iconv("utf-8","euc-kr",$filename_rnm);

	$options = array(
		'disposition' => 'attachment',
		'filename' => $filename_rnm,
	);
	
$path_filename = $str_path.$filename_nm;

//echo $path_filename;

WAF_WriteFile($path_filename, null, $options);

?>
