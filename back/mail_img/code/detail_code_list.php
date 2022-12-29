<?
	include "../admin_session_check.inc";
	include "../../dbconn.inc";

	$query = "select pcode_name from tb_code_parent where pcode = '$pcode'";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);

	$pcode_name = $list[pcode_name];

	if (!empty($qry_str)) {

		if ($idxfield == "0") {
			$que = " and name like '%$qry_str%' ";
		} else if ($idxfield == "1") {
			$que = " and code like '%$qry_str%' ";
		} else if ($idxfield == "2") {
			$que = " and memo like '%$qry_str%' ";
		} 
		
		$query = "select count(*) from tb_code_detail where pcode = '$pcode' ";
		$query2 = "select * from from tb_code_detail where pcode = '$pcode' order by dcode_seq";

	} else {
		$query = "select count(*) from tb_code_detail where pcode = '$pcode'  ";
		$query2 = "select * from tb_code_detail where pcode = '$pcode' order by dcode_seq";
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<title><?echo $g_site_title?></title>
<link rel="stylesheet" href="../inc/admin.css" type="text/css">
<style>
	table{ scrollbar-arrow-color: #FFFFFF;
		     scrollbar-3dlight-color: #FFFFFF;
		     scrollbar-highlight-color: #C0C0C0;
		     scrollbar-face-color: #B2B2B2;
		     scrollbar-shadow-color: #F3F3F3;
		     scrollbar-darkshadow-color: #F3F3F3;
		     scrollbar-track-color: #FFFFFF;}
</style>
<script LANGUAGE="JavaScript">
<!--
function goEdit(dcode_no) {
	var frm = document.frm_search; 
	frm.dcode_no.value = dcode_no;
	frm.action = "detail_code_modify.php";
	frm.submit();
}

function chgView(dcode_no, view_chk) {

	var frm = document.frm_search; 
	
	bChgOK = confirm("게시여부를 변경하시겠습니까?");  //게시여부를 변경하시겠습니까?
	
	if ( bChgOK ==true ) {
		frm.dcode_no.value = dcode_no;
		frm.view_chk.value = view_chk
		frm.mode.value = "chgView";
		frm.action = "detail_code_dml.php";
		frm.submit();
	} else {
		return;
	}
}

/*
 * @(#)menu.js
 * 
 * 페이지설명 : 메뉴 순서 바꾸기 스크립트 파일
 * 작성  일자 : 2003.12.01
 */  


var preid = -1;
	
function selectRow(n){

	var clickedRow = event.srcElement.parentElement;
	
	if (preid != -1) {
		document.all.t.rows[preid].style.background = "#FFFFFF";
	}
	document.all.t.rows[clickedRow.rowIndex].style.background = "#E0E0E0";
	
	preid = clickedRow.rowIndex;
}
	
function orderUp() {
	if (preid > 1) {			
		temp1 = document.all.t.rows[preid].innerHTML;
		temp2 = document.all.t.rows[preid-1].innerHTML;
		
		var cells1 = document.all.t.rows[preid].cells;
		var cells2 = document.all.t.rows[preid-1].cells;
	    	    
	    for(var j=0 ; j < cells1.length; j++) {
	    	var temp = cells2[j].innerHTML;
	    	cells2[j].innerHTML =cells1[j].innerHTML;
	    	cells1[j].innerHTML = temp;

	    	var tempCode = document.all.catid[preid-2].value;
	    	
	    	document.all.catid[preid-2].value = document.all.catid[preid-1].value;
	    	document.all.catid[preid-1].value = tempCode;
		}
		
		document.all.t.rows[preid].style.background = "#FFFFFF";	
		document.all.t.rows[preid-1].style.background = "#E0E0E0";	
		preid = preid - 1;	
	}
}		

function orderDown() {

	if (preid < document.all.t.rows.length-1) {		
		temp1 = document.all.t.rows[preid].innerHTML;
		temp2 = document.all.t.rows[preid+1].innerHTML;
		
		var cells1 = document.all.t.rows[preid].cells;
		var cells2 = document.all.t.rows[preid+1].cells;
	    
	    for(var j=0 ; j < cells1.length; j++) {
	    	var temp = cells2[j].innerHTML;
	    	cells2[j].innerHTML =cells1[j].innerHTML;
	    	cells1[j].innerHTML = temp;
	    	
	    	var tempCode = document.all.catid[preid-1].value;
	    	document.all.catid[preid-1].value = document.all.catid[preid].value;
	    	document.all.catid[preid].value = tempCode;
		}
		
		document.all.t.rows[preid].style.background = "#FFFFFF";	
		document.all.t.rows[preid+1].style.background = "#E0E0E0";	
		preid = preid + 1;	
	}
}			

function change_order(form) {
	
	if(document.all.t.rows.length < 2) {
		alert("순서를 저장할 코드가 없습니다."); //순서를 저장할 코드가 없습니다
		return;
	}
	
	document.frm_search.arr_cat_id.value = getIds();

	//alert(document.frm_search.arr_cat_id.value);
	
	document.frm_search.action = "detail_code_order_dml.php";
	document.frm_search.submit();

}

function getIds(){
	var sValues = "";
	if(frm_search.cat_id != null){
		if(frm_search.cat_id.length != null){
			for(i=0; i< frm_search.cat_id.length; i++){	
				if (sValues == "") {
					sValues = frm_search.cat_id[i].value;
				} else {
					sValues += "^"+frm_search.cat_id[i].value;
				}
			}
		}else{
			sValues += frm_search.cat_id.value;
		}
	}
	return sValues;
}
//-->
</script>
</head>
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%"> 
	<tr>
		<td align="center" height="100%" valign="top">
<!-- Site Map Section -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%" bgcolor="#FBFBFB"> 
				<tr>
					<td height="30" style="padding-left:20px"><a href="#">시스템 관리</a> > <a href="#">코드관리</a> > <a href="#">세부분류 코드</a> > <a href="#"><?echo $pcode_name?> 리스트</a></td>
				</tr>
				<tr>
					<td height="1" bgcolor="#CCCCCC"></td>
				</tr>
			</table>
<!-- Site Map Section End -->
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
 				<tr>
					<td height="10"></td>
				</tr>
				<tr>
					<td width="30"><img src="images/trans.gif" width="30" height="1" border="0"></td>
					<td width="99%">
						<br>
						<table width="95%" border="0" cellpadding="0" cellspacing="0" class="body">
							<tr height="2" bgcolor="#CCCCCC"> 
								<td></td>
							</tr>
							<tr height="32"> 
								<td align="right" width="100%" style="padding-right:5px" bgcolor="#F1F1F1">
									<a href="javascript:orderUp();"><img src="../images/up.gif" border="0" alt="위로이동"  align="absmiddle"></a>&nbsp;
									<a href="javascript:orderDown()"><img src="../images/down.gif" border="0" alt="아래로이동"  align="absmiddle"></a>&nbsp;
									<a href="javascript:change_order(this);"><img src="../images/order_ok.gif" border="0" alt="순서저장"  align="absmiddle"></a>&nbsp;	
									<!--<a href="list_excel.asp" target="_blank"><img src="/images/admin/btn_excel.gif" border="0" align="absmiddle"></a>&nbsp;-->
									<a href="detail_code_write.php?pcode=<?echo $pcode?>"><img src="../images/button_write.gif" border="0" align="absmiddle"></a>
								</td>
							</tr>
						</table>
						<table cellpadding="0" cellspacing="1" border="0" width="95%">
						<form name="frm_search" action="detail_code_list.asp" method="post">
							<input type="hidden" name="pcode" value="<?echo $pcode?>">	
							<input type="hidden" name="dcode_no" value="">	
							<input type="hidden" name="mode" value="">	
							<input type="hidden" name="view_chk" value="">	
							<tr>
								<td height="10"></td>
							</tr>
						</table>
						* 관리자가 소분류 코드를 등록, 수정, 삭제 하실 수 있습니다. <!--관리자가 소분류 코드를 등록, 수정, 삭제 하실 수 있습니다.--><br>
						<table id='t' width="95%" cellpadding="0" cellspacing="1" border="0" bgcolor="989898"> 
							<tr align="center" bgcolor="#E9E9E9" height="25">
								<td>코드<!--코드--></td>
								<td>코드명<!--코드명--></td>
								<td>코드(Ext)<!--코드(Ext)--></td>
								<td>게시여부<!--게시여부--></td>
							</tr>
							<?
							  $result = mysql_query($query2);

  							while($row = mysql_fetch_array($result)) {				
									
									$pcode = $row[pcode];
									$dcode = $row[dcode];
									$dcode_ext = $row[dcode_ext];
									$dcode_no = $row[dcode_no];
									$dcode_name = $row[dcode_name];
									$view_chk = $row[view_chk];
										
							?>					
							<tr align="center" height="25" bgcolor="#FFFFFF">
								<td onClick="selectRow()">
									<?echo $dcode?>
									<input type="hidden" name="catid" value="<?echo $dcode_no?>">   
									<input type="hidden" name="cat_id" value="<?echo $dcode_no?>"> 
								</td>
								<td><a href="javascript:goEdit('<?echo $dcode_no?>');"><?echo $dcode_name?></a></td>
								<td onClick="selectRow()"><?echo $dcode_ext?></td>
								<td>
									<a href="javascript:chgView('<?echo $dcode_no?>','<?echo $view_chk?>');"><? if (trim($view_chk) == "N") { ?><font color="red"><? } ?><?echo $view_chk?><? if (trim($view_chk) == "N" ) { ?></font><? } ?></a>
								</td>
							</tr>
							<?
								}	
							?>
						</table>
						<input type="hidden" name="arr_cat_id" value="">
						<input type="hidden" name="arr_catid" value="">
						</form>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?
mysql_close($connect);
?>