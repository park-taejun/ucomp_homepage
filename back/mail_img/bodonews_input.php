<?
	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	
	$sYear1 = date(Y)-1;
	$sYear2 = date(Y)+2;
	$sYY1 = date(Y);
	$sMM1 = date(m);
	$sDD1 = date(d);
	
?>		
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<LINK rel="stylesheet" HREF="inc/admin.css" TYPE="text/css">
<TITLE><?echo $g_site_title?></TITLE>

<SCRIPT language="javascript">
<!--
	function goBack() {
		document.frm.target = "frmain";
		document.frm.action="bodonews_list.php";
		document.frm.submit();
	}

	function goIn() {
		
		if(window.VBN_prepareSubmit != null){if(!VBN_prepareSubmit()) return false;}	
		
		if (document.frm.Title.value == "") {
			alert("제목를 입력하세요.");
			document.frm.Title.focus();
			return;
		}

		if (document.frm.Content.value == "") {
			alert("내용을 입력하세요.");
			document.frm.Content.focus();
			return;
		}
		
		if (document.frm.chkbshow.checked == 1) {
			document.frm.bshow.value = "1";
		} else {
			document.frm.bshow.value = "0";
		}
		
		document.frm.target = "frhidden";
		document.frm.action = "bodonews_db.php";
		document.frm.submit();
		
	}
	
	
	function Set_Date(cName_yy, cName_mm, cName_dd, cYear, cMonth, cDay) {
		
		var cStart = "<?echo $sYear1?>";
		var cEnd = "<?echo $sYear2?>";
				
		Set_YY(cName_yy,cStart,cEnd,cYear);
		Set_MM(cName_mm,cMonth);
		
		ChgDate(cName_yy,cName_mm,cName_dd,cDay);
			
	}
	
	function Set_YY(cName_yy, cStart, cEnd, cYY)	{
	with(document.frm)	{
		var nCnt= 0;
		for(var i=cStart;i<=cEnd;i++)	{
			elements[cName_yy].options[nCnt] = new Option(i,i);
			if (cYY==i)
				elements[cName_yy].options[nCnt].selected = true;
			nCnt++;
		}
	}
	}

	function Set_MM(cName_mm,cMM)	{
	with(document.frm)	{
		for(var i=0;i<12;i++)	{
			nCnt = i+1;
			if (nCnt < 10)	cMonth = "0"+nCnt;
			else		cMonth = nCnt;
			elements[cName_mm].options[i] = new Option(cMonth,cMonth);
			if (cMM == cMonth)
				elements[cName_mm].options[i].selected = true;
		}
	}
	}

	
	function ChgDate(cName_yy, cName_mm, cName_dd,cDD)	{
	with(document.frm)	{
		var nCnt= 0;
		var nLastDay = 0;
		var cYear = elements[cName_yy].options[elements[cName_yy].selectedIndex].value;
		var cMonth = elements[cName_mm].options[elements[cName_mm].selectedIndex].value;
		

		if ((cMonth=="01")||(cMonth=="03")||(cMonth=="05")||(cMonth=="07")||(cMonth=="08")||(cMonth=="10")||(cMonth=="12"))
			nLastDay = 31;
		else	nLastDay = 30;

		if (cMonth=="02")	{
			if (parseFloat(cYear/4)==parseInt(cYear/4))	{
				if (parseFloat(cYear/100)==parseInt(cYear/100))
					if (parseFloat(cYear/400)==parseInt(cYear/400))
						nLastDay=29;
					else	nLastDay=28;
				else	nLastDay=29;
			}
			else	nLastDay=28;
		}

		if( arguments.length > 3 )
			Set_Day(nLastDay,cName_dd,arguments[3]);
		else	Set_Day(nLastDay,cName_dd,"");
	}	
	}

	function Set_Day(nLastDay, cName_dd, cDD)	{
	with(document.frm)	{
		for(i=elements[cName_dd].options.length-1;i>0;i--)
			elements[cName_dd].options[i] = null;
		var nn=0

		for(var i=0;i<nLastDay;i++)	{
			nn=i+1;
			if (nn < 10)	cDay="0"+nn;
			else 		cDay=nn;
			elements[cName_dd].options[i] = new Option(cDay,cDay);
			if (cDD=="")
				elements[cName_dd].options[0].selected=true;
			else if (cDD==cDay)
				elements[cName_dd].options[i].selected=true;
		}

	}	
	}
	
//-->
</SCRIPT>
</HEAD>
<BODY>
<form name='frm' method='post' action='bodonews_db.php' enctype='multipart/form-data'>
<TABLE cellspacing="0" cellpadding="10" class="TITLE">
<TR>
<?
	if ($NewsId == "A1") {
?>
	<TD align="left"><B>보도자료</B></TD>
<?
	} 
?>
	<TD align="right" width="300" align="center" bgcolor=silver>
		<input type="button" onClick="goIn();" value="등록" name="btn3">
		<input type="button" onClick="goBack();" value="목록" name="btn4">
		<INPUT type="hidden" name="page" value="<?echo $page?>">
	</TD>
</TR>
</TABLE>
<table height='35' width='100%' cellpadding='0' cellspacing='0' border='1' bordercolorlight='#666666' bordercolordark='#FFFFFF' bgcolor='#FFFFFF' bordercolor='#FFFFFF'>
<tr>
	<td align='center'>
<TABLE border="0" cellspacing="1" cellpadding="2" class="IN">
<tr>
	<th>
		제 목 :
	</th>
	<td>
		<input type="text" name="Title" size="50" value="">
	</td>
</tr>
<tr>
	<th>
		홍보매체 :
	</th>
	<td>
		<input type="text" name="Source" size="30" value="">
	</td>
</tr>
<tr>
	<th>
		내 용 :
	</th>
	<td>
		<textarea name="Content" cols="80" rows="20"></textarea>
		<!-- (주)벤처브레인 WEAS 삽입 스크립트1(시작) -->
		<VentureBrain width="570" height="420" id=VBN_id_WEAS>
  		<!--(주의) VBN_LICENSE_로 시작하는 태그값을 변경하시면 KEY를 재발급받으셔야 합니다.-->
  		<param name=VBN_LICENSE_DOMAIN value="be-kr.co.kr">
  		<param name=VBN_LICENSE_KEY value="l5z7WR-9yzntG-E8PpaW-AuGkG7-eM5Rem">
  		<param name=VBN_LICENSE_NUMBER value="7025-0000-0000-0228">
  		<!-- 아래 옵션들은 사용자 편의대로 설정하실 수 있습니다. -->
  		<param name=VBN_OPTION_version value="Standard Multi">
  		<param name=VBN_OPTION_baseSaveFileURL value="be-kr.co.kr">
  		<param name=VBN_OPTION_scriptIncludeFlag value="true">
  		<param name=VBN_OPTION_fileNumberingFlag value="true">
  		<param name=VBN_OPTION_fileLocalPathSendFlag value="true">
  		<param name=VBN_OPTION_toolBarHidden value="attachFile">
  		<param name=VBN_OPTION_readWidth value="-1">
		</VentureBrain>

<script language="javascript" id=VBN_JSID_init src="http://be-kr.co.kr/VBN_WEAS/weas/js/VBN_WEAS_000.js"></script>
<!-- (주)벤처브레인 WEAS 삽입 스크립트1(끝) -->

	</td>
</tr>
<tr>
	<th>
		홍보날짜 :
	</th>
	<td>
		<SELECT name="Year1" onchange=ChgDate("Year1","Month1","Day1")>
			<option value="">9999</option>
			<option value=""></option>
		</SELECT>년&nbsp;
	
		<SELECT name="Month1" onchange=ChgDate("Year1","Month1","Day1")>
			<option value="">99</option>
			<option value=""></option>
		</SELECT>월&nbsp;
	
		<SELECT name="Day1">
			<option value="">99</option>
			<option value=""></option>
		</SELECT>일
	</td>
</tr>
<tr>
	<th>
		보이기 :
	</th>
	<td>
		<input type="checkbox" name="chkbshow" value="1"> 보이기
	</td>
</tr>
</TABLE>
<table border="0">
	</td>
</tr>
</table>
<input type="hidden" name="bshow" value="">
<input type="hidden" name="usedate" value="">
<input type="hidden" name="mode" value="add">
<INPUT type="hidden" name="idxfield" value="<?echo $idxfield?>">
<INPUT type="hidden" name="qry_str" value="<?echo $qry_str?>">
<input type="hidden" name="NewsId" value="<?echo $NewsId?>">
</FORM>
</body>
</html>
<!-- (주)벤처브레인 WEAS 삽입 스크립트2(시작) -->
<script>if(window.VBN_connectVentureBrainNetwork != null) VBN_connectVentureBrainNetwork();</script>
<!-- (주)벤처브레인 WEAS 삽입 스크립트2(끝) -->
<script language="javascript">
	Set_Date("Year1","Month1","Day1","<?echo $sYY1?>","<?echo $sMM1?>","<?echo $sDD1?>");
</script>