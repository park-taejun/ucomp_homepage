<?php

ini_set('display_errors', '1');
error_reporting(E_ALL ^ E_NOTICE);

# =============================================================================
# File Name    : question.dml.ajax.php
# Modlue       : 
# Writer       : ParkChanho
# Create Date  : 2017-01-21
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

#==============================================================================
# Request Parameter
#==============================================================================

	$eseq_no				= $_POST['eseq_no']!=''?$_POST['eseq_no']:$_GET['eseq_no'];

	$c_use_tf = "Y";
	$c_del_tf = "N";

	$arr_rs = listQuestion($conn, $eseq_no);
	
	if (sizeof($arr_rs) > 0) {
		for ($j = 0 ; $j < sizeof($arr_rs); $j++) {

			$QSEQ_NO	= trim($arr_rs[$j]["QSEQ_NO"]);
			$ESEQ_NO	= trim($arr_rs[$j]["ESEQ_NO"]);
			$TITLE		= SetStringFromDB($arr_rs[$j]["TITLE"]);
			$TYPE			= trim($arr_rs[$j]["TYPE"]);
			$ETC			= trim($arr_rs[$j]["ETC"]);
			$DISP_SEQ	= trim($arr_rs[$j]["DISP_SEQ"]);

			if ($TYPE == "type01") {

				$arr_option_rs = listOption($conn, $QSEQ_NO, $c_use_tf, $c_del_tf);
?>
<ul>
	<li>
		<p class="question"><label><?=$j+1?>.</label><span><?=$TITLE?></span> 	</p>
		<p class="example">
		<?
				if (sizeof($arr_option_rs) > 0) {
					for ($k = 0 ; $k < sizeof($arr_option_rs); $k++) {
						$OSEQ_NO			= trim($arr_option_rs[$k]["OSEQ_NO"]);
						$OPTION_VALUE	= trim($arr_option_rs[$k]["OPTION_VALUE"]);
		?>
			<span><input type="checkbox" /><label> <?=$OPTION_VALUE?></label></span>&nbsp;&nbsp;
		<?
					}
				}
		?>
		</p>
	</li>
	<p style="width:98%;text-align:right;padding: 5px 0 5px 0">
		<input type="button" name="aa" value=" 수정 " class="btntxt"  style="cursor:pointer;height:25px;" onClick="view_question('<?=$QSEQ_NO?>','<?=$TYPE?>')">
		<input type="button" name="aa" value=" 삭제 " class="btntxt"  style="cursor:pointer;height:25px;" onClick="delete_question('<?=$QSEQ_NO?>')">
	</p>
</ul>
<?
			}

			if ($TYPE == "type02") {
?>
<ul>
	<li>
		<p class="question"><label><?=$j+1?>.</label><span><?=$TITLE?></span></p>
		<p class="example">
			<textarea style="width:90%"></textarea>
		</p>
	</li>
	<p style="width:98%;text-align:right;padding: 5px 0 5px 0">
		<input type="button" name="aa" value=" 수정 " class="btntxt"  style="cursor:pointer;height:25px;" onClick="view_question('<?=$QSEQ_NO?>','<?=$TYPE?>')">
		<input type="button" name="aa" value=" 삭제 " class="btntxt"  style="cursor:pointer;height:25px;" onClick="delete_question('<?=$QSEQ_NO?>')">
	</p>
</ul>
<?
			}
?>

<?
		}
	}

	mysql_close($conn);
?>