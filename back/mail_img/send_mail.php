<?

$MailServer = "211.202.2.75";
$MailPort = "25";
$FromEmail = "orion@giringrim.com";
$FromName = "Park Chan Ho";
$to = "orion@ntsmall.co.kr";
$ToName = "Park Chan Ho";
$subject = "Test Mail";
$XMailer = "";
$charset = "EUC-KR";
$body_txt = "Contents";

echo $MailServer;

$fp = fsockopen($MailServer,$MailPort,&$errno,&$errstr,30); 

if(!$fp)
{
   ERRBACK("Sendmail 연결 에러 : $errstr($errno)n");
    exit;
}

fputs($fp,"helo $HTTP_HOST\r\n");// 초기 연결후 helo 입력
fputs($fp,"mail from: $FromEmail\r\n");//보내는 메일 입력
fputs($fp,"rcpt to: $to\r\n"); //받는 메일 입력
/* 상기까지가 본문과 관계없이 25번 포트 접속 후 일련적으로 일어나는 현상 telnet 메일서버 25 접속시 동일 */

fputs($fp,"data\r\n");
fputs($fp,"Return-Path: $FromName<$FromEmail>\r\n"); //리턴패스 입력
fputs($fp,"From: $FromName<$FromEmail>\r\n"); // 보내는 메일 입력
fputs($fp,"To: $ToName<$to>\r\n"); // 받는 사람 입력
fputs($fp,'Cc:$encoded_mailccrn'); // 참조 입력
fputs($fp,"Subject: $subject\r\n"); // 제목 입력
fputs($fp,"X-Mailer: $XMailer\r\n"); // x-Mailer 입력
fputs($fp,"MIME-Version: 1.0\r\n"); //MIME-Version 입력
fputs($fp,"Content-Type: text/plain$charset\n"); //MIME-Version 입력
/* 상기까지가 헤더 입력 부분 */

fputs($fp,$body_txt);

fputs($fp,"\r\n");  //닫음
fputs($fp,"\r\n.\r\n");//닫음
/* 메일내용 입력 및 끝 */

$err_2 = fgets($fp,128);
/* 여기 까지가 가장 간단한 메일 보내기 끝 */
/* 아래는 text, html, 첨부파일이 있을 경우에 대해서 논의 합니다. 참조로 상기 내용은 텍스트일 경우에 대한 내용입니다. */
?>
