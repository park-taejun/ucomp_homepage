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
   ERRBACK("Sendmail ���� ���� : $errstr($errno)n");
    exit;
}

fputs($fp,"helo $HTTP_HOST\r\n");// �ʱ� ������ helo �Է�
fputs($fp,"mail from: $FromEmail\r\n");//������ ���� �Է�
fputs($fp,"rcpt to: $to\r\n"); //�޴� ���� �Է�
/* �������� ������ ������� 25�� ��Ʈ ���� �� �Ϸ������� �Ͼ�� ���� telnet ���ϼ��� 25 ���ӽ� ���� */

fputs($fp,"data\r\n");
fputs($fp,"Return-Path: $FromName<$FromEmail>\r\n"); //�����н� �Է�
fputs($fp,"From: $FromName<$FromEmail>\r\n"); // ������ ���� �Է�
fputs($fp,"To: $ToName<$to>\r\n"); // �޴� ��� �Է�
fputs($fp,'Cc:$encoded_mailccrn'); // ���� �Է�
fputs($fp,"Subject: $subject\r\n"); // ���� �Է�
fputs($fp,"X-Mailer: $XMailer\r\n"); // x-Mailer �Է�
fputs($fp,"MIME-Version: 1.0\r\n"); //MIME-Version �Է�
fputs($fp,"Content-Type: text/plain$charset\n"); //MIME-Version �Է�
/* �������� ��� �Է� �κ� */

fputs($fp,$body_txt);

fputs($fp,"\r\n");  //����
fputs($fp,"\r\n.\r\n");//����
/* ���ϳ��� �Է� �� �� */

$err_2 = fgets($fp,128);
/* ���� ������ ���� ������ ���� ������ �� */
/* �Ʒ��� text, html, ÷�������� ���� ��쿡 ���ؼ� ���� �մϴ�. ������ ��� ������ �ؽ�Ʈ�� ��쿡 ���� �����Դϴ�. */
?>
