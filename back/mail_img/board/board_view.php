<?
	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../../dbconn.inc";

	$query = "select * from tb_board_config where ID = '".$BoardId."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$BOARD_NAME = $list[BOARD_NAME];

	$query = "select * from tb_other_board where BoardId = '".$BoardId."' and SeqNo = '".$id."'";
	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$SeqNo = $list[SeqNo];
	$BoardId = $list[BoardId];
	$Title = $list[Title];
	$writer = $list[writer];
	$data = $list[Content];
	$FileName01 = $list[FileName01];
	$FileName02 = $list[FileName02];
	$FileName03 = $list[FileName03];
	$FileRealName01 = $list[FileRealName01];
	$FileRealName02 = $list[FileRealName02];
	$FileRealName03 = $list[FileRealName03];
	$in_date = $list[in_date];
	$isHtml = $list[isHtml];
	$bshow = $list[bshow];

	$data = stripslashes(Trim($data)); 

	$in_date_v = str_replace("-","", $in_date);

	$Title = htmlspecialchars($Title);	
	$Content = htmlspecialchars($Content);
?>		
<?	$_mode = "view"; ?>
<?	include "../inc/other_title.inc"; ?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/tour.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<SCRIPT language="javascript">
<!--
<!--
	var month_name=new Array('1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월');
	var day_name=new Array('일','월','화','수','목','금','토');

	function show_cal(obj_id,obj_view,datetype,select) {
	   cur_id = obj_id;
	   cur_view = obj_view;
	   
	   if (window.event.clientY + 10 + parseInt(document.all.calendar.style.height) > document.body.clientHeight){
	       document.all.calendar.style.top = document.body.clientHeight - parseInt(document.all.calendar.style.height);
	       }
	   else {
	       document.all.calendar.style.top = window.event.clientY + 10;
	       }
	       
	   if (window.event.clientX - 100 + parseInt(document.all.calendar.style.width) > document.body.clientWidth)
	       document.all.calendar.style.left = document.body.clientWidth - parseInt(document.all.calendar.style.width);
	   else 
	       document.all.calendar.style.left = window.event.clientX - 100;

	   document.all.calendar.style.display = 'block';
	   document.all.calendar.month_name=month_name;
	   document.all.calendar.day_name=day_name;
	   document.all.calendar.datetype = datetype;
	   document.all.calendar.select = select;
	   document.all.calendar.calWidth = document.all.calendar.style.width;
	   document.all.calendar.curDate = obj_id.value;
	   showcal = true;
	}

	function set_cal(id,view)
	{
	   document.all.calendar.style.display = 'none';

	   if (id == "" || view == "") return;
	   
	   cur_id.value = id;
	   cur_view.value = view;
	}

	function set_cal2(id,view,start_id,start,end_id,end)
	{
	   document.all.calendar.style.display = 'none';

	   if (id == "" || view == "") return;
	   
	   cur_id.value = id;
	   cur_view.value = view;
	   
	   if (cur_id == start_id) {
	      if (cur_id.value > end_id.value) {
		 if (cur_id.value.substring(8) > end_id.value.substring(8)) end_id.value = cur_id.value;
	         else end_id.value = cur_id.value.substring(0,8)+end_id.value.substring(8);
	         end.value = cur_view.value;
	      }
	   } else if (cur_id == end_id) {
	      if (cur_id.value < start_id.value) {
		 if (cur_id.value.substring(8) < start_id.value.substring(8)) start_id.value = cur_id.value; 
	         start_id.value = cur_id.value.substring(0,8)+start_id.value.substring(8);
	         start.value = cur_view.value;
	      }
	   }   
	}

	function goBack() {
//		document.frm.target = "frmain";
		document.frm.action="board_list.php";
		document.frm.submit();
	}

	function regFile() {
//		document.frm_file.target = "frhidden";
		document.frm_file.submit(); 
	}

	function goIn() {
				
		if (document.frm.Title.value == "") {
			alert("제목를 입력하세요.");
			document.frm.Title.focus();
			return;
		}

		if (document.frm.data.value == "") {
			alert("설명를 입력하세요.");
			document.frm.data.focus();
			return;
		}

		//if( checkIt(document.frm) ){
		//	document.frm.action = "focus_db.php";
		//  document.frm.submit()
		//} else {
		//  return;
		//}
	
//	document.frm.target = "frhidden";
		document.frm.action = "board_db.php";
		document.frm.submit();
		
	}

	function goPre() {
					
		if (document.frm.Title.value == "") {
			alert("제목을 입력하세요.");
			document.frm.Title.focus();
			return;
		}
		
		if (document.frm.Content.value == "") {
			alert("내용을 입력하세요.");
			document.frm.Content.focus();
			return;
		}
				
//		document.frm.target = "_new";
		document.frm.action = "/people/people_read_pre.php";
		document.frm.submit();
		
	}

	function checkIt(form) {      
		getEditCheck(form);
		form.data.value = form.BB_CONTENT.value;
		if(!form.data.value) {
			alert('내용을 입력하세요!');
      return false;
		}
		return true;
	}

	/**
	* 파일 첨부에 대한 선택에 따른 파일첨부 입력란 visibility 설정
	*/
	function fileView(obj,idx) {
		var fileCnt = 3;
		if(fileCnt == 1) {
			if (obj.selectedIndex == 2) { 
				document.all["FileName"+idx].style.visibility = "visible"; 
			} else { 
				document.all["FileName"+idx].style.visibility = "hidden"; 
			}	
		} else if (fileCnt > 1) {
			if (obj.selectedIndex == 2) { 
				document.all["FileName"+idx].style.visibility = "visible"; 
			} else { 
				document.all["FileName"+idx].style.visibility = "hidden"; 
			}	
		}
	}

  // 이미지 크기에 맞는 이미지 팝업
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
	imgwin.document.write("  self.resizeTo(myWidth, myHeight+20);\n"); 
	imgwin.document.write("}else setTimeOut(resize(), 100);}\n"); 
	imgwin.document.write("</sc"+"ript>\n"); 
	
	imgwin.document.write("</head>\n"); 
	imgwin.document.write('<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" bgcolor="#FFFFFF">\n'); 
	
	imgwin.document.write("<img border=0 src="+what+" xwidth=100 xheight=9 name=il onload='resize();'>\n"); 
	imgwin.document.write("</body>\n"); 
	imgwin.document.close(); 
	
  } 

//-->
</SCRIPT>
</HEAD>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<object id="calendar" data="../inc/cm_calendar.htm" type="text/x-scriptlet" style='position:absolute;display:none;width:180;height:180;' VIEWASTEXT></object>
<script for="calendar" event="onscriptletevent(id, view)">
<!--
	set_cal2(id,view,document.all.dateRegFrom, document.all.dateRegFrom_v,document.all.dateRegTo, document.all.dateRegTo_v);
//-->
</script>
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->

<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
	<tr>
		<td height="30" style="padding-left:20px"><a href="#">게시판 관리</a> > <a href="#"><?echo $BOARD_NAME?></a> > <a href="#"><?echo $_mode_str?></a></td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>
</table>

<!-- Site Map Section End -->
<!-- Main Section  -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
<!-- Form -->				
				<form name='frm' method='post' action='board_db.php' enctype='multipart/form-data'>
				<input type="hidden" name="page" value="<?echo $page?>">
				<input type="hidden" name="mode" value="mod">
				<input type="hidden" name="id" value="<?echo $SeqNo?>">
				<input type="hidden" name="idxfield" value="<?echo $idxfield?>">
				<input type="hidden" name="qry_str" value="<?echo $qry_str?>">
				<input type="hidden" name="BoardId" value="<?echo $BoardId?>">
				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="../images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
<!-- Page Description -->
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td>
									* <?echo $BOARD_NAME?> 수정 화면 입니다.<br>
								</td>
							</tr>
						</table>
<!-- Page Description End -->						
						<table cellpadding="2" cellspacing="1" border="0" width="95%"> 
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">제 목</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="Title" size="90" value="<?echo $Title?>">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">첨부파일</td>
								<td style="padding-left:10px" colspan="3">
								<?
									if (strlen($FileRealName01) > 3) {
								?>	
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="20%">
												<a href="/upload/down_file_func.php?BoardId=<?echo $BoardId?>&id=<?echo $id?>"><img src="../images/icon_filedown.gif" border="0"></a>&nbsp;&nbsp;<?echo $FileRealName01?>&nbsp;
												<!--<a href="/<?echo $BoardId?>_files/down_file.php?file_name=<?echo $FileRealName01?>"><?echo $FileRealName01?></a>&nbsp;-->
											</td>
											<td width="80%">
												<select name="flag01" style="width:70px;" onchange="javascript:fileView(this,'01')">
													<option value="keep">유지</option>
													<option value="delete">삭제</option>
													<option value="update">수정</option>
												</select>
												<input type="hidden" name="oRealName01" value="<?echo $FileRealName01?>">
												<input type="hidden" name="oName01" value="<?echo $FileName01?>">
												<input TYPE="file" NAME="FileName01" size="30" style="visibility:hidden">
											</td>
										</tr>
									</table>
								<?
									} else { 		
								?>	
									<input type="file" name="FileName01" size="30" value="">&nbsp;&nbsp;
									<input type="hidden" name="oName01" value="">
									<input TYPE="hidden" NAME="flag01" value="insert">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<!--
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">첨부 파일</td>
								<td style="padding-left:10px" colspan="3">
								<?
									if (strlen($FileName02) > 3) {
								?>	
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="20%">
												<a href="javascript:fitImagePop('/<?echo $_path_str?>/<?echo $FileName02?>');"><?echo $FileRealName01?></a>&nbsp;
											</td>
											<td width="80%">
												<select name="flag02" style="width:70px;" onchange="javascript:fileView(this,'02')">
													<option value="keep">유지</option>
													<option value="delete">삭제</option>
													<option value="update">수정</option>
												</select>
												<input type="hidden" name="oRealName02" value="<?echo $FileRealName02?>">
												<input type="hidden" name="oName02" value="<?echo $FileName02?>">
												<input TYPE="file" NAME="ImageName02" size="50" style="visibility:hidden">
											</td>
										</tr>
									</table>
								<?
									} else { 		
								?>	
									<input type="file" name="FileName02" size="50" value="">&nbsp;&nbsp; 
									<input type="hidden" name="oName02" value="">
									<input TYPE="hidden" NAME="flag02" value="insert">
								<?
									}	
								?>	
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">첨부 파일</td>
								<td style="padding-left:10px" colspan="3">
								<?
									if (strlen($FileName03) > 3) {
								?>	
									<table cellpadding="0" cellspacing="0" border="0" width="100%">
										<tr>
											<td width="20%">
												<a href="javascript:fitImagePop('/<?echo $_path_str?>/<?echo $FileName03?>');"><?echo $FileRealName03?></a>&nbsp;
											</td>
											<td width="80%">
												<select name="flag03" style="width:70px;" onchange="javascript:fileView(this,'03')">
													<option value="keep">유지</option>
													<option value="delete">삭제</option>
													<option value="update">수정</option>
												</select>
												<input type="hidden" name="oRealName03" value="<?echo $ImageRealName03?>">
												<input type="hidden" name="oName03" value="<?echo $ImageName03?>">
												<input TYPE="file" NAME="ImageName03" size="50" style="visibility:hidden">
											</td>
										</tr>
									</table>
								<?
									} else { 		
								?>	
									<input type="file" name="FileName03" size="50" value="">
									<input type="hidden" name="oName03" value="">
									<input TYPE="hidden" NAME="flag03" value="insert">
								<?
									}	
								?>	
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							-->
							<?
								}
							?>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">내 용</td>
								<td style="padding-left:10px" colspan="3" bgcolor="#FFFFFF">
									<textarea name="data" style="width:690px; height:180px"><?echo $data?></textarea>
									<!--
									<input type="hidden" name="data" value="">
									<? $image_path = "OTHER_".$BoardId;?>
									<?include("../htmledit/init.php");?>
									-->
									<input type="hidden" name="isHtml" value="0">
								</td>
							</tr>
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">작성자</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="writer" size="20" value="<?echo $writer?>">
								</td>
							</tr>
							<!--
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">작성일</td>
								<td style="padding-left:10px" colspan="3">
									<input type="text" name="in_date" size="8" value="<?echo $in_date?>" readOnly=1>	<img src="../images/month_icon.gif" align="absmiddle" style="cursor: hand;" onClick="javascript:show_cal(frm.in_date_v,frm.in_date,'yyyy-MM-dd',1);">
									<input name="in_date_v" type="hidden" value="<?echo $in_date_v?>?>">
								</td>
							</tr>
							-->
							<tr>
								<td height="1" bgcolor="#C4C4C4" colspan="4"></td>
							</tr>
							<tr>
								<td width="15%" bgcolor="#E9E9E9" align="center" height="25">보이기</td>
								<td style="padding-left:10px" colspan="3">
									<input type="checkbox" name="bshow" value="1" <?if ($bshow == "1") echo "checked";?>> 보이기로 설정 하시겠습니까? 예 일 경우 선택 하시요.
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
									<a href="javascript:goIn();"><img src="../images/button_modify.gif" border="0"></a>&nbsp;
									<a href="javascript:goBack();"><img src="../images/button_list_01.gif" border="0"></a>&nbsp;
									<!--<a href="javascript:goPre();"><img src="../images/button_ok.gif" border="0"></a>&nbsp;-->
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</form>
			</table>
			<br>
		</td>
	</tr>
</table>
</body>
</html>
<?
mysql_close($connect);
?>