<?session_start();?>
<?
// 절대 결코 윗부분 칸띄우지마세요
header("Content-Type: text/plain; charset=utf-8"); 

#====================================================================
# common_header Check Session
#====================================================================
	include "../_common/common_header.php"; 

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");
	
#=====================================================================
# common function, login_function
#=====================================================================
	require "../_common/config.php";
	require "../_classes/com/util/Util.php";
	require "../_classes/com/etc/etc.php";
	require "../_classes/biz/group/group.php";

#====================================================================
# Request Parameter
#====================================================================


	$party_val		= trim($party_val);
	$group_cd_01	= trim($group_cd_01);
	$group_cd_02	= trim($group_cd_02);
	$group_cd_03	= trim($group_cd_03);
	$group_cd_04	= trim($group_cd_04);
	$group_cd_05	= trim($group_cd_05);

	$is_depth_01	= "0";
	$is_depth_02	= "0";
	$is_depth_03	= "0";
	$is_depth_04	= "0";
	$is_depth_05	= "0";

	$arr_rs = listGroup($conn, $party_val, "", "Y", "N", "", "");


?>
<select name="group_cd_01" id="group_cd_01" style="width:150px;">
	<option value="">선택 하세요</option>
<?
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$RS_GROUP_CD				= trim($arr_rs[$j]["GROUP_CD"]);
			$RS_GROUP_NAME			= trim($arr_rs[$j]["GROUP_NAME"]);
			
			if ((strlen($RS_GROUP_CD) == 6) && (left($RS_GROUP_CD,3) == $group_cd_01)) $is_depth_01 = "1";

			if (strlen($RS_GROUP_CD) == 3) {
				if (left($group_cd_01,3) == $RS_GROUP_CD) { 
?>
	<option value="<?=$RS_GROUP_CD?>" selected><?=$RS_GROUP_NAME?></option>
<?
				} else {
?>
	<option value="<?=$RS_GROUP_CD?>"><?=$RS_GROUP_NAME?></option>
<?
				}
			}
		}
	}
?>
</select>&nbsp;<?//=$group_cd_01?>
<?
	if (($group_cd_01 <> "") && ($is_depth_01 == "1")) {
?>
<select name="group_cd_02" id="group_cd_02" style="width:150px;">
	<option value="">선택 하세요</option>
<?
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$RS_GROUP_CD				= trim($arr_rs[$j]["GROUP_CD"]);
			$RS_GROUP_NAME			= trim($arr_rs[$j]["GROUP_NAME"]);
			
			if ((strlen($RS_GROUP_CD) == 9) && (left($RS_GROUP_CD,6) == $group_cd_02)) $is_depth_02 = "1";

			if ((strlen($RS_GROUP_CD) == 6) && (left($RS_GROUP_CD,3) == $group_cd_01)) {

				if (left($group_cd_02,6) == $RS_GROUP_CD) { 
?>
	<option value="<?=$RS_GROUP_CD?>" selected><?=$RS_GROUP_NAME?></option>
<?
				} else {
?>
	<option value="<?=$RS_GROUP_CD?>"><?=$RS_GROUP_NAME?></option>
<?
				}
			}
		}
	}

?>
</select>&nbsp;<?//=$group_cd_02?>
<?
	}

	if (($group_cd_02 <> "") && ($is_depth_02 == "1")) {

?>

<select name="group_cd_03" id="group_cd_03" style="width:150px;">
	<option value="">선택 하세요</option>
<?
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$RS_GROUP_CD				= trim($arr_rs[$j]["GROUP_CD"]);
			$RS_GROUP_NAME			= trim($arr_rs[$j]["GROUP_NAME"]);
			
			if ((strlen($RS_GROUP_CD) == 12) && (left($RS_GROUP_CD,9) == $group_cd_03)) $is_depth_03 = "1";

			if ((strlen($RS_GROUP_CD) == 9) && (left($RS_GROUP_CD,6) == $group_cd_02)) {
				if (left($group_cd_03,9) == $RS_GROUP_CD) { 
?>
	<option value="<?=$RS_GROUP_CD?>" selected><?=$RS_GROUP_NAME?></option>
<?
				} else {
?>
	<option value="<?=$RS_GROUP_CD?>"><?=$RS_GROUP_NAME?></option>
<?
				}
			}
		}
	}

?>
</select>&nbsp;<?//=$group_cd_03?>
<?
	}
?>

<?
	if (($group_cd_03 <> "") && ($is_depth_03 == "1")) {
?>

<select name="group_cd_04" id="group_cd_04" style="width:150px;">
	<option value="">선택 하세요</option>
<?
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$RS_GROUP_CD				= trim($arr_rs[$j]["GROUP_CD"]);
			$RS_GROUP_NAME			= trim($arr_rs[$j]["GROUP_NAME"]);

			if ((strlen($RS_GROUP_CD) == 15) && (left($RS_GROUP_CD,12) == $group_cd_04)) $is_depth_04 = "1";

			if ((strlen($RS_GROUP_CD) == 12) && (left($RS_GROUP_CD,9) == $group_cd_03)) {
				if (left($group_cd_04,12) == $RS_GROUP_CD) { 
?>
	<option value="<?=$RS_GROUP_CD?>" selected><?=$RS_GROUP_NAME?></option>
<?
				} else {
?>
	<option value="<?=$RS_GROUP_CD?>"><?=$RS_GROUP_NAME?></option>
<?
				}
			}
		}
	}

?>
</select>&nbsp;<?//=$group_cd_04?>

<?
	}
?>

<?
	if (($group_cd_04 <> "") && ($is_depth_04 == "1")) {
?>

<select name="group_cd_05" id="group_cd_05" style="width:150px;">
	<option value="">선택 하세요</option>
<?
	if (sizeof($arr_rs) > 0) {
		
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$RS_GROUP_CD				= trim($arr_rs[$j]["GROUP_CD"]);
			$RS_GROUP_NAME			= trim($arr_rs[$j]["GROUP_NAME"]);

			if ((strlen($RS_GROUP_CD) == 15) && (left($RS_GROUP_CD,12) == $group_cd_04)) {
				if (left($group_cd_05,15) == $RS_GROUP_CD) { 
?>
	<option value="<?=$RS_GROUP_CD?>" selected><?=$RS_GROUP_NAME?></option>
<?
				} else {
?>
	<option value="<?=$RS_GROUP_CD?>"><?=$RS_GROUP_NAME?></option>
<?
				}
			}
		}
	}

?>
</select>&nbsp;<?//=$group_cd_05?>

<?
	}
?>

<?
#====================================================================
# DB Close
#====================================================================

	mysql_close($conn);

?>
