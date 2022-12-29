<?
	//////////////////////////////////////////////////////////////
	//
	// 	Date 		: 2004/03/02
	// 	Last Update : 2004/03/02
	// 	Author 		: Park, ChanHo
	// 	History 	: 2004.03.02 by Park ChanHo 
	// 	File Name 	: admin_left.php
	// 	Description : 메뉴 화면
	// 	Version 	: 1.0
	//
	//////////////////////////////////////////////////////////////

	include "admin_session_check.inc";
	include "./inc/global_init.inc";
	include "../dbconn.inc";
	
	$query = "select big_menu, big_menu_name from tb_big_menu order by big_menu";

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {

		$big_menu_code[] = $row[big_menu];
		$big_menu_name[] = $row[big_menu_name];
		
	}

	$query = "select group_item from tb_admin_group where group_id = '$s_flag'";

	$result = mysql_query($query);
	$list = mysql_fetch_array($result);
	$group_item = $list[group_item];
	
	$query = "select menu_id, big_menu, small_menu, menu_url from tb_admin_menu where menu_id in (".$group_item.")";

	$result = mysql_query($query);

	while($row = mysql_fetch_array($result)) {

		$menu_id[] = $row[menu_id];
		$big_menu[] = $row[big_menu];
		$small_menu[] = $row[small_menu];
		$menu_url[] = $row[menu_url];
		
	}

	mysql_close($connect);
	
?>
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<TITLE><?echo $g_site_title?></TITLE>
<LINK rel="stylesheet" HREF="inc/tour.css" TYPE="text/css">
<SCRIPT language="javascript">
	
	function goPage(strURL) {
		top.frmain.location=strURL;
	}

	function onSelect(no){

		
		var targetId, targetElement, dMax, selectId, selectElement;
		
		targetId = "td" + no;
		
		targetElement = document.all(targetId);
						
		dMax = <?echo sizeof($menu_id)?>;
		
		for (i = 0; i < dMax; i++) {
			selectId = "td" + i;
			selectElement = document.all(selectId);
			//alert(selectElement);
			selectElement.style.background = "#F1F1F1";
		}
		
		targetElement.style.background = "#DFDFDF";
				
	}

</SCRIPT>
</HEAD>
</head>	
<body topmargin="0" leftmargin="0" marginheight="0" marginwidth="0" bgcolor="#F1F1F1">
<table id='t' cellpadding="0" cellspacing="0" border="0" width="160"> 
<form name="frm">
<?
	for ($i = 0; $i < sizeof($big_menu_code); $i++) {
?>
	
	<tr>
		<td style="padding-left:10px" height="28"><b><?echo $big_menu_name[$i]?></b></td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>

<?				
		for ($j = 0; $j < sizeof($menu_id); $j++) {
			if ($big_menu_code[$i] == $big_menu[$j]) {  
?>					
	
	<tr>
		<td style="padding-left:10px" height="28" id="td<?echo $j?>">
			<a class="s" href="javascript:onSelect('<?echo $j?>');goPage('<?echo $menu_url[$j]?>');">>> <?echo $small_menu[$j]?></a>
		</td>
	</tr>
	<tr>
		<td height="1" bgcolor="#CCCCCC"></td>
	</tr>
<?
			}
		}
?>
	
<?
	}
?>
</form>
</table>	
</body>
</html>