<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";

	$query = "select * from tb_sian where SeqNo = '$SeqNo'";
	$result = mysql_query($query);
										
	$list = mysql_fetch_array($result);
	$oid = $list[oid];
	$oid_sub = $list[oid_sub];
	$title = $list[title];
	$name = $list[name];
	$realLfile = $list[realLFile];
	$Lfile = $list[LFile];
	$realBfile = $list[realBFile];
	$Bfile = $list[BFile];
	$memo = $list[memo];

	function str_cut($str,$len){
		$slen = strlen($str);
		if (!$str || $slen <= $len) $tmp = $str;
		else	$tmp = preg_replace("/(([\x80-\xff].)*)[\x80-\xff]?$/", "\\1", substr($str,0,$len))."...";
		return $tmp;
	}

	$oid = trim($oid);	
	$oid_sub = trim($oid_sub);	

?>		
<HTML>
<HEAD>
<link rel="stylesheet" href="inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<title><?echo $g_site_title?></title>
<SCRIPT language="javascript">
<!--

	function goIn() {
						
		var frm = document.frm;

		if (frm.title.value == "") {
			alert("������ �Է��� �ּ���.");
			frm.title.focus();
			return;
		}

		if (frm.name.value == "") {
			alert("�ۼ��ڸ� �Է��� �ּ���.");
			frm.name.focus();
			return;
		}

		if (frm.memo.value == "") {
			alert("�޸�(���� ����) �Է��� �ּ���.");
			frm.memo.focus();
			return;
		}

		frm.action = "sian_mod_db.php"
		frm.submit();
			
		
	}

	/**
	* ���� ÷�ο� ���� ���ÿ� ���� ����÷�� �Է¶� visibility ����
	*/
	function fileView(obj,idx) {
		var fileCnt = 3;
		if(fileCnt == 1) {
			if (obj.selectedIndex == 1) { 
				document.all[idx].style.visibility = "visible"; 
			} else { 
				document.all[idx].style.visibility = "hidden"; 
			}	
		} else if (fileCnt > 1) {
			if (obj.selectedIndex == 1) { 
				document.all[idx].style.visibility = "visible"; 
			} else { 
				document.all[idx].style.visibility = "hidden"; 
			}	
		}
	}

  // �̹��� ũ�⿡ �´� �̹��� �˾�
  function fitImagePop(what) { 
		
	var imgwin = window.open("",'WIN','scrollbars=no,status=no,toolbar=no,resizable=1,location=no,menu=no,width=10,height=10'); 
	imgwin.focus(); 
	imgwin.document.open(); 
	imgwin.document.write("<html>\n"); 
	imgwin.document.write("<head>\n"); 
	imgwin.document.write("<title>Toin Manager system</title>\n"); 
	
	imgwin.document.write("<sc"+"ript>\n"); 
	imgwin.document.write("function resize() {\n"); 
	imgwin.document.write("pic = document.il;\n"); 
	imgwin.document.write("if (eval(pic).height) { var name = navigator.appName\n"); 
	imgwin.document.write("  if (name == 'Microsoft Internet Explorer') { myHeight = eval(pic).height + 40; myWidth = eval(pic).width + 12;\n"); 
	imgwin.document.write("  } else { myHeight = eval(pic).height + 9; myWidth = eval(pic).width; }\n"); 
	imgwin.document.write("  clearTimeout();\n"); 
	imgwin.document.write("  var height = screen.height;\n"); 
	imgwin.document.write("  var width = screen.width;\n"); 
	imgwin.document.write("  var leftpos = width / 2 - myWidth / 2;\n"); 
	imgwin.document.write("  var toppos = height / 2 - myHeight / 2; \n"); 
	imgwin.document.write("  self.moveTo(leftpos, toppos);\n"); 
	imgwin.document.write("  self.resizeTo(myWidth, myHeight-10);\n"); 
	imgwin.document.write("}else setTimeOut(resize(), 100);}\n"); 
	imgwin.document.write("</sc"+"ript>\n"); 
	
	imgwin.document.write("</head>\n"); 
	imgwin.document.write('<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" bgcolor="#FFFFFF">\n'); 
	
	imgwin.document.write("<img border=0 src="+what+" xwidth=100 xheight=9 name=il onload='resize();'>\n"); 
	imgwin.document.write("</body>\n"); 
	imgwin.document.close(); 
	
  } 


	function goRe() {
		
		var frm = document.re;
		
		if (frm.memo.value == "") {
			alert("�����ǰ��� �Է��� �ּ���.");
			frm.memo.focus();
			return;
		}

		if (frm.name.value == "") {
			alert("�ۼ��ڸ� �Է��� �ּ���.");
			frm.name.focus();
			return;
		}

		frm.action = "re_db.php"
		frm.submit();
	}

	function delRe(sno) {
		
		var frm = document.re;
		
		bDelOK = confirm("���� ���� �Ͻðڽ��ϱ�?");
		
		if ( bDelOK ==true ) {
			frm.sno.value = sno; 
			frm.action = "re_del_db.php"
			frm.submit();
		} else {
			return;
		}
	}

//-->
</SCRIPT>
</HEAD>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#">�ֹ�����</a> > <a href="#">�ֹ���</a> > <a href="#">�þȼ��� �� ��ȸ</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
			<form name="frm" method="post" action="sian_mod_db.php" enctype='multipart/form-data'>
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">����</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="title" size="45" value="<?echo $title?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">�ۼ���</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="name" size="15" value="<?echo $name?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">����Ʈ �̹���</td>
								<td style="padding-left:10px" colspan="3">
								<?
									if (strlen($realLfile) > 3) {
								?>	
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="20%">
												<a href="javascript:fitImagePop('/sian_files/<?echo $Lfile?>');"><?echo $realLfile?></a>&nbsp;
											</td>
											<td width="80%">
												<select name="flagThu" style="width:70px;" onchange="javascript:fileView(this,'Lfile')">
													<option value="keep">����</option>
													<!--<option value="delete">����</option>-->
													<option value="update">����</option>
												</select>
												<input type="hidden" name="orealLfile" value="<?echo $realLfile?>">
												<input type="hidden" name="oLfile" value="<?echo $realLfile?>">
												<input TYPE="file" NAME="Lfile" size="30" style="visibility:hidden">
											</td>
										</tr>
									</table>
								<?
									} else { 		
								?>	
									<input type="file" name="Lfile" size="50" value="">&nbsp;&nbsp; 
									<input type="hidden" name="oLfile" value="">
									<input TYPE="hidden" NAME="flagThu" value="insert">
									<?
									}	
								?>	
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">�þ� �̹���</td>
								<td style="padding-left:10px" colspan="3">
								<?
									if (strlen($realBfile) > 3) {
								?>	
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="20%">
												<a href="javascript:fitImagePop('/sian_files/<?echo $Bfile?>');"><?echo $realBfile?></a>&nbsp;
											</td>
											<td width="80%">
												<select name="flagBig" style="width:70px;" onchange="javascript:fileView(this,'Bfile')">
													<option value="keep">����</option>
													<!--<option value="delete">����</option>-->
													<option value="update">����</option>
												</select>
												<input type="hidden" name="orealBfile" value="<?echo $realBfile?>">
												<input type="hidden" name="oBfile" value="<?echo $Bfile?>">
												<input TYPE="file" NAME="Bfile" size="30" style="visibility:hidden">
											</td>
										</tr>
									</table>
								<?
									} else { 		
								?>	
									<input type="file" name="Bfile" size="50" value="">&nbsp;&nbsp; 
									<input type="hidden" name="oBfile" value="">
									<input TYPE="hidden" NAME="flagBig" value="insert">
								<?
									}	
								?>	
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">�޸�</td>
								<td style="padding-left:10px" colspan="3">
									<textarea name="memo" style="width:250px; height:60px"><?echo $memo?></textarea>
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<a href="javascript:goIn();"><img src="images/button_save.gif" border="0"></a>&nbsp;
									<a href="javascript:self.close();"><img src="images/button_cancle.gif" border="0"></a>&nbsp;
								</td>
							</tr>
<input type="hidden" name="SeqNo" value="<?echo $SeqNo?>">
<input type="hidden" name="oid" value="<?echo $oid?>">
<input type="hidden" name="oid_sub" value="<?echo $oid_sub?>">
</form>
						</table>
						<br>
						* ���� �ǰ�
						<table cellpadding="0" cellspacing="0" border="0" width="95%"> 
							<tr>
								<td height="2" bgcolor="#000000" colspan="5"></td>
							</tr>
							<?
								# �ֹ� ��ǰ ����
								$query = "select * from tb_sian_re where SeqNo = '$SeqNo' order by regdate desc ";
								$result = mysql_query($query);
										
								while($row = mysql_fetch_array($result)) {

									$sno = $row[sno];
									$SeqNo = $row[SeqNo];
									$id = $row[id];
									$name = $row[name];		
									$regdate = $row[regdate];
									$memo = $row[memo];
									$date_s = date("Y-m-d [H:i]", strtotime($regdate));
							?>
							<tr align="center" height="25" onmouseover="this.style.background='#E9E9E9';" onmouseout="this.style.background='White';">
								<td align="left" width="63%"><?echo $memo?></a></td>
								<td width="12%"><?echo $name?></td>
								<td width="20%"><?echo $date_s?></td>
								<td width="5%"><a href="javascript:delRe('<?echo $sno?>')">����</a></td>
							</tr>
							<tr>
								<td height="1" bgcolor="#EFEFEF" colspan="5"></td>
							</tr>
							<?
								}	
							?>
							<tr>
								<td height="2" bgcolor="#000000" colspan="5"></td>
							</tr>
							
						</table>							
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
						<form name="re" action="re_db.php" method="post">
						<input type="hidden" name="sno" value="">
						<input type="hidden" name="SeqNo" value="<?echo $SeqNo?>">
						<input type="hidden" name="oid" value="<?echo $oid?>">
						<input type="hidden" name="oid_sub" value="<?echo $oid_sub?>">
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">����</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="memo" size="70" maxlength="60" value="">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="20%" bgcolor="#E9E9E9" align="center" height="25">�ۼ���</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="name" size="15" value="<?echo $s_adm_name?>">
									<input type="hidden" name="id" size="15" value="###############">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td height="10"></td>
							</tr>
							<tr>
								<td colspan="4" align="center">
									<a href="javascript:goRe();"><img src="images/button_save.gif" border="0"></a>&nbsp;
								</td>
							</tr>
						</form>
						</table>
					</td>
				</tr>
			</table>
<!-- Main Section End -->
		</td>
	</tr>
</table>
</body>
</html>
<?
	mysql_close($connect);
?>