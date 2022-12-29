<?php

ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);

# =============================================================================
# File Name    : question.dml.ajax.php
# Modlue       : 
# Writer       : ParkChanho
# Create Date  : question.dml.ajax.php
# Modify Date  : 
#	Copyright : Copyright @fivelink Corp. All Rights Reserved.
# =============================================================================

#=====================================================================
# common function, login_function
#=====================================================================
	require "../../_common/config.php";
	require "../../_classes/com/util/Util.php";
	require "../../_classes/com/util/ImgUtil.php";
	require "../../_classes/com/etc/etc.php";
	require "../../_classes/biz/webzine/webzine.php";

#====================================================================
# DB Include, DB Connection
#====================================================================
	require "../../_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];
	$qseq_no		= $_POST['qseq_no']!=''?$_POST['qseq_no']:$_GET['qseq_no'];

	$mode			= trim($mode);
	$qseq_no	= trim($qseq_no);

	if ($mode == "S") {

		$arr_rs = selectQuestion($conn, $qseq_no);
		
		if (sizeof($arr_rs) > 0) {

			$QSEQ_NO	= trim($arr_rs[0]["QSEQ_NO"]);
			$ESEQ_NO	= trim($arr_rs[0]["ESEQ_NO"]);
			$TITLE		= trim($arr_rs[0]["TITLE"]);
			$TYPE			= trim($arr_rs[0]["TYPE"]);
			$ETC			= trim($arr_rs[0]["ETC"]);
			$DISP_SEQ	= trim($arr_rs[0]["DISP_SEQ"]);
			
			$c_use_tf = "Y";
			$c_del_tf = "N";

		}
	}

?>
<input type="hidden" name="type" id="type" value="type02">
<input type="hidden" name="qseq_no" id="qseq_no" value="<?=$QSEQ_NO?>">
<div style="width:95%; padding:20px 5px 5px 5px">
	질문 : <input type="text" style="width:95%" name="title" id="title" value="<?=$TITLE?>"> 
</div>
<?
	mysql_close($conn);
?>