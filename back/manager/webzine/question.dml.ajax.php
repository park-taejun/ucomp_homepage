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

#==============================================================================
# Request Parameter
#==============================================================================
	$mode				= $_POST['mode']!=''?$_POST['mode']:$_GET['mode'];

	$eseq_no		= $_POST['eseq_no']!=''?$_POST['eseq_no']:$_GET['eseq_no'];
	$qseq_no		= $_POST['qseq_no']!=''?$_POST['qseq_no']:$_GET['qseq_no'];

	$title			= $_POST['title']!=''?$_POST['title']:$_GET['title'];
	$type				= $_POST['type']!=''?$_POST['type']:$_GET['type'];
	$option_data= $_POST['option_data']!=''?$_POST['option_data']:$_GET['option_data'];

	$mode					= trim($mode);
	$title				= SetStringToDB($title);
	$type					= trim($type);

	if ($mode == "I") {

		// 질문 정보 등록
		$arr_data = array("ESEQ_NO"=>$eseq_no,
											"TITLE"=>$title,
											"TYPE"=>$type,
											"ETC"=>$etc
											);

		$new_seq_no = insertQusetion($conn, $arr_data);

		//echo sizeof($option_data);
		
		for ($i = 0 ; $i < sizeof($option_data) ; $i++) {
			
			if ($option_data[$i] <> "") {

				$tmp_option_data = explode("",$option_data[$i]);

				$arr_option_data = array("QSEQ_NO"=>$new_seq_no,
													"OPTION_VALUE"=>trim($tmp_option_data[0])
													);

				if ($tmp_option_data[1] == "") {
					$result = insertOption($conn, $arr_option_data);
				} else {
					$result = updateOption($conn, $arr_option_data, $tmp_option_data[1]);
				}
			}
		}

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

	if ($mode == "U") {

		// 질문 정보 등록
		$arr_data = array("ESEQ_NO"=>$eseq_no,
											"TITLE"=>$title,
											"TYPE"=>$type,
											"ETC"=>$etc
											);

		$result = updateQusetion($conn, $arr_data, $qseq_no);

		$result = deleteOption($conn, $qseq_no);
		
		for ($i = 0 ; $i < sizeof($option_data) ; $i++) {
			
			if ($option_data[$i] <> "") {
				
				$tmp_option_data = explode("",$option_data[$i]);

				$arr_option_data = array("QSEQ_NO"=>$qseq_no,
														"OPTION_VALUE"=>trim($tmp_option_data[0])
														);

				if ($tmp_option_data[1] == "") {
					$result = insertOption($conn, $arr_option_data);
				} else {
					$result = updateOption($conn, $arr_option_data, $tmp_option_data[1]);
				}
			}
		}

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

	if ($mode == "D") {

		$result = deleteQuestion($conn, $qseq_no);
		$result = deleteOption($conn, $qseq_no);

		if ($result) {
			echo "T";
		} else {
			echo "F";
		}
	}

	mysql_close($conn);
?>