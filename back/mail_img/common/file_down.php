<?
/**
* void WAF_WriteFile(string filename, string mimetype = null, mixed options = null);
* 
* ������ ��� Ŭ���̾�Ʈ���� �����Ѵ�.
* ���ϰ��� ����, ���� ������ �Ϸ�Ǹ� ������ ó���� ����ȴ�.
* ������ �߻��� ��� ��Ȳ�� �´� HTTP ǥ�� ����(4xx, 5xx)��
* Ŭ���̾�Ʈ���� ���޵ǰ� ������ ó���� ����ȴ�.
* 
* HTTP/1.1 �� ���� ����� ������
* 
* - Range ��� ����(������ �Ϻκи� ����)
* 
* - If-Modified-Since ��� ����(����� ��츸 ����)
* 
* Ŭ���̾�Ʈ�� ĳ�ÿ� �������� �̹� ���� ���
* ������ Ȯ�� �ð� �����ؼ� �� ���Ŀ� ����Ǿ����� ������ �ϰ� ������ �ȵǾ�����
* ���� �������� �ٽ� ���� �Ǵ� ���� ���ϱ� ���ؼ� ����Ѵ�.
* ������ �Ǿ����� ������ �ϰ�(200 OK), ���� �ȵǾ����� 304 Not Modified �� �����Ѵ�.
* 
* - If-Unmodified-Since ��� ����(���� �ȵ� ��츸 ����)
* 
* If-Unmodified-Since ����� ĳ�ÿ� �������� �Ϻθ� ���� ���
* ������ Ȯ�� �ð� �����ؼ� �� ���Ŀ� ������� �ʾ����� �ʿ��� �κи� ������ �ϰ�
* ������ �ȵǾ��� ��� ��ü�� �ޱ� ���ؼ� ����Ѵ�.(Range ����� ��������)
* ������ �Ǿ����� 412 Precondition Failed �� �����ϰ�,
* ���� �ȵǾ����� �����Ѵ�. (200 OK �Ǵ� 206 Partial Content)
* 
* - If-Range ��� ����(���� �ȵ� ���� �κ�����, ����� ���� ��ü ����)
* 
* If-Range ����� If-Unmodified-Since �� ����ѵ�
* If-Unmodified-Since�� ����� ��쿡�� 412 Precondition Failed ������ ��û�� �����Ƿ�
* ���� ���ŵ� ������ �������� �ٽ� ��û�� �ؾ��Ѵ�.
* If-Range �� ������ �ȵǾ����� �κ������� �ϰ�, ����� ���� ��ü ������ �ϵ��� �Ѵ�.
* Range ����� ���� �������� ������ �ǹ̰� ����.
* �� HTTP/1.1 �Ծ࿡�� ��ƼƼ �±�(Etags ���)�� �ð� ��ſ� ���� ���� ������
*    �� �Լ��� ��ƼƼ �±׸� �������� �ʴ´�.
* 
* GET /_test/test_range.php HTTP/1.0          // wget�� HTTP/1.1 ��
* User-Agent: Wget/1.8.2                      // 100% �������� ���ϴ� ��
* Host: image.cine21.com                      // If- ������ ����� ���������ؾ���
* Connection: Keep-Alive
* Range: bytes=200-                           // Range ����� ������
* If-Range: Fri, 19 May 2005 08:03:25 +0000   // ������ ���� �ð�
* 
* HTTP/1.1 200 OK                             // �׷��� 200 OK(��ü ����)
* Date: Fri, 20 May 2005 09:20:43 GMT
* Server: Apache
* Content-Length: 512
* Last-Modified: Fri, 20 May 2005 05:03:25 +0000  // ���� ����� �ð�
* Accept-Ranges: bytes
* Keep-Alive: timeout=3, max=128
* Connection: Keep-Alive
* Content-Type: text/plain;charset=euc-kr
* 
* �� HTTP/1.1 �Ծ��� ��ƼƼ �±� ���� Etags, If-Match, If-None-Match ���� �������� �ʴ´�.
* 
* options:
*     disposition
*         Content-Disposition ����� �����Ѵ�.
*         206 Partial Content, 304 Not Modified �ϰ��� ���õȴ�.
* 
*         WAF_WriteFile($filename, null, array('disposition' => 'attachment'));
* 
*     filename
*         Content-Disposition ����� ��µ� �����̸��� �����Ѵ�.
* 
*         $options = array(
*             'disposition' => 'attachment',
*             'filename' => 'a.txt',
*         );

*         WAF_WriteFile($filename, null, $options);
* 
*     headers
*         �߰� ����� �����Ѵ�. ���� ������ ������ ��츸 ����ȴ�.
* 
*         $options = array(
*             'headers' => array(
*                 'Expires'   => gmdate('r', time() + 86400), // ������ 24�ð������� ��ȿ��
*             );
*         );
*         WAF_WriteFile($filename, null, $options);
* 
*     range
*         Range ����� override �Ѵ�. Ŭ���̾�Ʈ���� ���޵�
*         Range ���($_SERVER['HTTP_RANGE']) ��� ������ ���� ����.
* 
*         WAF_WriteFile($filename, null, array('range' => 'bytes=100-'));
* 
*         Ȥ�� �κ� ���� ����� disable ��Ű�� ���ؼ� ����� �� �ִ�.
* 
*         WAF_WriteFile($filename, null, array('range' => FALSE));
* 
*     check_func
*         ���� �̸��� ���޹޾� boolean�� �����ϴ� �Լ��� �����
*         �˻� �Լ��� ������ �� �ִ�.
* 
*         // ����η� ���� ���丮�� ������ ã�� ���� ����
*         function SampleCallback($filename) {
*             return ($filename{0} == '/' || strpos($filename, '../') === FALSE);
*         }
*         WAF_WriteFile($filename, null, array('check_func' => 'SampleCallback'));
*/


global $BUFSIZ;
$BUFSIZ = 8196; 

function WAF_WriteFile($filename, $mimetype = null, $options = null) {
	if (!file_exists($filename)) {
		WAF_HTTPError(404);       // 404 Not Found
  } elseif (!is_file($filename) || !is_readable($filename)) {
    WAF_HTTPError(403);       // 403 Forbidden
	}

  // ���� �˻� �Լ��� �����Ǹ� �߰����� �˻縦 ����
  if (is_callable($options['check_func'])) {
		if (!call_user_func($options['check_func'], $filename)) {
			WAF_HTTPError(403);       // 403 Forbidden
		}
	}

  // ��ü ����, �κ����� ���� ��� ����
  $mtime = filemtime($filename);

  // If-Modified-Since ó��
  if ($_SERVER['HTTP_IF_MODIFIED_SINCE']) {
		$if_modified_since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
    
		if ($if_modified_since >= $mtime) {
			Header("HTTP/1.1 304 Not Modified");
      exit();
		}
	}

  // If-Unmodified-Since ó��
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

  // options�� Range ����� ������ ��� Ŭ���̾�Ʈ���� �� ������ �����Ѵ�.
  $range = (isset($options['range']))? $options['range']: $_SERVER['HTTP_RANGE'];

  // If-Range ó��
  if ($_SERVER['HTTP_IF_RANGE']) {
		$if_modified_since = strtotime($_SERVER['HTTP_IF_RANGE']);
		
		if ($if_modified_since < $mtime) {
			$range = '';       // ������ �Ǿ����Ƿ� ��ü ������ �ϵ��� �Ѵ�.
		}
	}

  if ($range && preg_match('/bytes\s*=\s*(\d+)?\s*-\s*(\d+)?/i', $range, $part)) {
		$start = $part[1];
    $end = $part[2];
    $options['headers']["Accept-Ranges"] = "bytes";
    WAF_WritePartialFile($filename, $start, $end, $mimetype, $options);
	} else {       // Range ����� ���ų�, ������ �ùٸ��� �ʰų�, ������ bytes �� �ƴ� ��
              // �׷��� range ������ �������� �ʾ����� Range ����� �����Ѵٴ� ���� �˷��ش�.
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
* ������ ��� ��ü�� �����Ѵ�.
* ���������� ������� ���� WAF_WriteFile()�� ����� ��
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

		// ���� ��µ� ���� ���ۿ� ������ �����.
  ob_end_clean();

  // GET, POST �� ���� ������ �����Ѵ�.
	if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST') {
			// ������ ����.
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
* ������ ��� �Ϻκ��� �����Ѵ�.
* ���������� ������� ���� WAF_WriteFile()�� ����� ��
*/
function WAF_WritePartialFile($filename, $start, $end, $mimetype = null, $options = null) {
	global $BUFSIZ;

  $filesize = filesize($filename);
  
	if ($end == null)
		$end = $filesize - 1;

		// ������ Range �� �ùٸ��� �ʴ�.
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

	// ���� ��µ� ���� ���ۿ� ������ �����.
  ob_end_clean();

  // GET, POST �� ���� ������ �����Ѵ�.
  if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD'] == 'POST')  {
		// ������ ����.
    $fp = fopen($filename, "rb");
    
		if (!$fp) {
			WAF_HTTPError(500);       // 500 Internal Server Error
		}

    // ���� ��ġ�� �̵�
    if ($start > 0) {
			$rs = fseek ($fp, $start, SEEK_SET);
      
			if ($rs < 0) {
				WAF_HTTPError(500);       // 500 Internal Server Error
			}
		}

    // $start �������� $end �������� ����
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
       400       => 'Bad Request',				412       => 'Precondition Failed',
       401       => 'Unauthorized',				413       => 'Request Entity Too Large',
       402       => 'Payment Required',		414       => 'Request-URI Too Long',
       403       => 'Forbidden',					415       => 'Unsupported Media Type',
       404       => 'Not Found',					416       => 'Requested Range Not Satisfiable',
       405       => 'Method Not Allowed', 417       => 'Expectation Failed',
       406       => 'Not Acceptable',     500       => 'Internal Server Error',
       407       => 'Proxy Authentication Required',              501       => 'Not Implemented',
       408       => 'Request Timeout',    502       => 'Bad Gateway',
       409       => 'Conflict',           503       => 'Service Unavailable',
       410       => 'Gone',               504       => 'Gateway Timeout',
       411       => 'Length Required',    505       => 'HTTP Version Not Supported',
);

function WAF_HTTPError($status, $title = null, $message = null, $options = null) {

	global $HTTP_STATUS_MESSAGE;
  
	$status_message = $HTTP_STATUS_MESSAGE[$status];
  
	Header("HTTP/1.1 {$status} {$status_message}");
  Header("Content-Type: text/html");
  if ($message == null)
		$message = $status_message;

	// ���۸� Ŭ����
  if ($options['error_page']) {
		ob_end_clean();
    include($options['error_page']);
    exit();
  } else {
		ob_end_clean();
?>
<html>
<head>
<title><?= $title ?></title>
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

	$filename = trim($filename);
	$filerealname = trim($filerealname);

	$options = array(
		'disposition' => 'attachment',
    'filename' => $filerealname,
  );
	
	$path_filename = "/home/humanoid/B1_files/".$filename;

	WAF_WriteFile($path_filename, null, $options);
?>