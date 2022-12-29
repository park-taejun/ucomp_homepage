<?
	function sql_password($db, $value) {
		$query = "select password('$value') as pass";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		return $rows[pass];
	}

	function getZipCode($db,$dong) {

		$offset = $nRowCount*($nPage-1);


		$query = "SELECT POST_NO , SIDO , SIGUNGU, DONG, RI , BUNJI, FULL_ADDR
								FROM TBL_ZIPCODE WHERE 1 = 1 ";
		
		if ($dong <> "") {
			$query .= " AND DONG like '%".$dong."%' ";
		}
		
		$query .= " ORDER BY POST_NO ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function getName($db,$mem_nm) {

		$offset = $nRowCount*($nPage-1);


		$query = "SELECT MEM_ID,MEM_NM FROM TBL_MEMBER  WHERE 1 = 1 and DEL_TF='N'";
		
		if ($mem_nm <> "") {
			$query .= " AND MEM_NM like '%".$mem_nm."%' ";
		}
		
		$query .= " ORDER BY MEM_NO  ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}


	function makeSelectBoxOnChange($db,$pcode,$objname,$size,$str,$val,$checkVal) {
		 
		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		// echo "query : " .$query. "<br />";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class=\"box01\" style='width:".$size."px;' onchange=\"js_".$objname."();\">";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		
		
		$tmp_str .= "</select>";
		
		return $tmp_str;
		
	}

	function makeSelectBoxAllOnChange($db,$pcode,$objname,$size,$str,$val,$checkVal) {
 
		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class=\"box01\" style='width:".$size."px;' onchange=\"js_".$objname."();\">";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}


	function makeSelectBox($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		} else {
			$query .= " AND PCODE = 'XXXXXXXX' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		// echo $query;
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='box01'  style='width:".$size."px;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeSelectBoxSmartday($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		} else {
			$query .= " AND PCODE = 'XXXXXXXX' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='box01'  style='width:".$size."px;' onChange='search_opt_search(this.value)'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeSelectBoxJinhak($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		} else {
			$query .= " AND PCODE = 'XXXXXXXX' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='box01'  style='width:".$size."px;border: 1px solid #e0e0e0;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeSelectBoxPositionCode($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT ADM_ID, ADM_NAME, HEADQUARTERS_CODE, DEPT_CODE, POSITION_CODE, DEPT_UNIT_NAME 
								FROM TBL_NEW_ADMIN_INFO WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		$query .= " AND READER_YN = 'Y' ";
		$query .= " ORDER BY LEVEL ASC, DEPT_CODE ASC, POSITION_CODE ASC ";

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='box01'  style='width:".$size."px;border: 1px solid #e0e0e0;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_ADM_ID				= Trim($row["ADM_ID"]);
			$RS_ADM_NAME			= Trim($row["ADM_NAME"]);
			$RS_DEPT_CODE			= Trim($row["DEPT_CODE"]);
			$RS_POSITION_CODE	= Trim($row["POSITION_CODE"]);
			if ($RS_DEPT_CODE == "") {
				$STR	= "[".$RS_POSITION_CODE."]".$RS_ADM_NAME;
			} else {
				$STR	= "[".$RS_DEPT_CODE."]".$RS_ADM_NAME." ".$RS_POSITION_CODE;
			}


			if ($checkVal == $RS_ADM_ID) {
				$tmp_str .= "<option value='".$RS_ADM_ID."' selected>".$STR."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_ADM_ID."'>".$STR."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeFrontSelectBox($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		} else {
			$query .= " AND PCODE = 'XXXXXXXX' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeSelectBoxAll($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		} else {
			$query .= " AND PCODE = 'XXXXXXXX' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='box01'  style='width:".$size."px;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeSelectBoxMobile($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		} else {
			$query .= " AND PCODE = 'XXXXXXXX' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='form-elem' style='width:".$size."rem;' onChange='search_opt_search(this.value)'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makePayReasonSelectBox($db,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DISTINCT PAY_REASON
								FROM TBL_PAYMENT WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
				
		$query .= " ORDER BY PAY_REASON ASC ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' class=\"box01\"  style='width:".$size."px;'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[0]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makePayReasonSelectBoxWithCondition($db,$objname,$size,$str,$val,$checkVal,$condition) {

		$query = "SELECT DISTINCT PAY_REASON
								FROM TBL_PAYMENT WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ".$condition;
				
		$query .= " ORDER BY PAY_REASON ASC ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' class=\"box01\"  style='width:".$size."px;'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[0]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}


	function makeShoppingSelectBox($db, $groupcd, $objname, $size, $str, $val, $checkVal) {

		$query = "SELECT itemcd, itemnm
								FROM gd_code WHERE 1 = 1 ";
		
		if ($groupcd <> "") {
			$query .= " AND groupcd = '".$groupcd."' ";
		}
		
		$query .= " ORDER BY sort ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' style='width:".$size."px;'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeShoppingCheckBox($db, $groupcd, $objname, $checkVal) {

		$query = "SELECT itemcd, itemnm
								FROM gd_code WHERE 1 = 1 ";
		
		if ($groupcd <> "") {
			$query .= " AND groupcd = '".$groupcd."' ";
		}
		
		$query .= " ORDER BY sort ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			//echo ($checkVal&pow(2,$RS_DCODE))."<br>";

			if ($checkVal&pow(2,$RS_DCODE)) {
				$tmp_str .= "<input type = 'checkbox' class='checkbox' name= '".$objname."' value='".$RS_DCODE."' checked> ".$RS_DCODE_NM." \n";
			} else {
				$tmp_str .= "<input type = 'checkbox' class='checkbox' name= '".$objname."' value='".$RS_DCODE."'> ".$RS_DCODE_NM." \n";
			}
		}
		return $tmp_str;
	}

	function getDcodeName($db, $pcode, $dcode) {

		$query = "SELECT DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}

		if ($pcode <> "") {
			$query .= " AND DCODE = '".$dcode."' ";
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[0];
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;
	}

	function getDcodeCode($db, $pcode, $dcode_nm) {

		$query = "SELECT DCODE
								FROM TBL_CODE_DETAIL WHERE 1 = 1 ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}

		if ($pcode <> "") {
			$query .= " AND DCODE_NM = '".$dcode_nm."' ";
		}
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[0];
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;
	}

	function makeRadioBox($db,$pcode,$objname,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<input type = 'radio' style='width:15px' class='chk' name= '".$objname."' value='".$RS_DCODE."' checked> ".$RS_DCODE_NM."&nbsp;&nbsp;&nbsp;";
			} else {
				$tmp_str .= "<input type = 'radio' style='width:15px' class='chk' name= '".$objname."' value='".$RS_DCODE."'> ".$RS_DCODE_NM."&nbsp;&nbsp;&nbsp;";
			}
		}
		return $tmp_str;
	}

	function makeRadioBoxFront($db,$pcode,$objname,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE <> '0'";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<span><input type='radio' class='chk' name= '".$objname."' value='".$RS_DCODE."' checked> ".$RS_DCODE_NM."</span>";
			} else {
				$tmp_str .= "<span><input type='radio' class='chk' name= '".$objname."' value='".$RS_DCODE."'> ".$RS_DCODE_NM."</span>";
			}
		}
		return $tmp_str;
	}

	function makeRadioBoxOnClick($db,$pcode,$objname,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<input type = 'radio' class='chk' style='width:15px' name= '".$objname."' value='".$RS_DCODE."' checked onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." &nbsp;&nbsp;&nbsp;";
			} else {
				$tmp_str .= "<input type = 'radio' class='chk' style='width:15px' name= '".$objname."' value='".$RS_DCODE."' onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." &nbsp;&nbsp;&nbsp;";
			}
		}
		return $tmp_str;
	}

	// 회원 종류에 따라 달리 보여지는 부분 (회원 정보 수정 시 만 사용
	function makeMemberRadioBoxOnClick($db,$pcode,$objname,$checkVal, $mem_type) {
		
		//echo $mem_type;

		if ($mem_type == "C") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE <> 'E' ";
		}

		if ($mem_type == "L") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE = 'L' ";
		}

		if ($mem_type == "E") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE = 'E' ";
		}

		if ($mem_type == "Y") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE IN ('Y','L') ";
		}

		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' checked onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;";
			} else {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;";
			}
		}
		return $tmp_str;
	}


	// 회원 종류에 따라 달리 보여지는 부분 (회원 정보 수정 시 만 사용
	function makeMemberWithConditionRadioBoxOnClick($db,$pcode,$objname,$checkVal, $mem_type,$condition) {
		
		if ($mem_type == "C") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE <> 'E' ".$condition." ";
		}

		if ($mem_type == "L") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE = 'L' ".$condition." ";
		}

		if ($mem_type == "E") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE = 'E' ".$condition." ";
		}

		if ($mem_type == "Y") {
			$query = "SELECT DCODE, DCODE_NM
									FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' AND DCODE IN ('Y','L') ".$condition." ";
		}

		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' checked onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;";
			} else {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;";
			}
		}
		return $tmp_str;
	}


	function makeRadioBoxJoinHow($db,$pcode,$objname,$checkVal,$join_how_person,$join_how_etc ) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' checked onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			} else {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
			}
			
			if ($RS_DCODE == "JH98") {
				$tmp_str .= "<input type=\"text\" class=\"box01\" style=\"width: 71px;\" maxlength=\"15\" name=\"join_how_person\" value=\"".$join_how_person."\" />\n";
				$tmp_str .= "</td>\n";
				$tmp_str .= "</tr>\n";
				$tmp_str .= "<tr>\n";
				$tmp_str .= "<td>&nbsp;</td>\n";
				$tmp_str .= "<td class=\"end font11\">\n";
			}

			if ($RS_DCODE == "JH99") {
				$tmp_str .= "<input type=\"text\" class=\"box01\" style=\"width: 300px;\" maxlength=\"30\" name=\"join_how_etc\" value=\"".$join_how_etc."\" />\n";
			}

		}
		return $tmp_str;
	}

	function makeCheckBox($db,$pcode,$objname,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if (strrpos($checkVal,$RS_DCODE)) {
				$tmp_str .= "<span class='iradio'><input type='checkbox' class='cl_".$objname."' name='".$objname."' value='".$RS_DCODE."' id='".$i."_".$objname."' checked><label for='".$i."_".$objname."'> ".$RS_DCODE_NM."</label></span>";
			} else {
				$tmp_str .= "<span class='iradio'><input type='checkbox' class='cl_".$objname."' name='".$objname."' value='".$RS_DCODE."' id='".$i."_".$objname."'><label for='".$i."_".$objname."'> ".$RS_DCODE_NM."</label></span>";
			}
		}
		return $tmp_str;
	}

	function getSiteInfo($db, $site_no) {

		$query = "SELECT SITE_NO, SITE_NM, SITE_LANG, SITE_CONTENT
								FROM TBL_SITE_INFO WHERE SITE_NO = '$site_no' ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function getCodeList($db,$pcode) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function makeGoodsSelectBox($db,$goods_type,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT GOODS_NO, GOODS_NM
								FROM TBL_GOODS WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($goods_type <> "") {
			$query .= " AND GOODS_TYPE = '".$goods_type."' ";
		} else {
			$query .= " AND GOODS_TYPE = '' ";
		}
		
		$query .= " ORDER BY DISP_SEQ ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' style='width:".$size."px;'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_GOODS_NO	= Trim($row[0]);
			$RS_GOODS_NM	= Trim($row[1]);

			if ($checkVal == $RS_GOODS_NO) {
				$tmp_str .= "<option value='".$RS_GOODS_NO."' selected>".$RS_GOODS_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_GOODS_NO."'>".$RS_GOODS_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}


	function makeGoodsSelectBoxOnChange($db,$goods_type,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT GOODS_NO, GOODS_NM
								FROM TBL_GOODS WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($goods_type <> "") {
			$query .= " AND GOODS_TYPE = '".$goods_type."' ";
		} else {
			$query .= " AND GOODS_TYPE = '' ";
		}
		
		$query .= " ORDER BY DISP_SEQ ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' style='width:".$size."px;' onChange=\"js_".$objname."();\">";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_GOODS_NO	= Trim($row[0]);
			$RS_GOODS_NM	= Trim($row[1]);

			if ($checkVal == $RS_GOODS_NO) {
				$tmp_str .= "<option value='".$RS_GOODS_NO."' selected>".$RS_GOODS_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_GOODS_NO."'>".$RS_GOODS_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function getGoodsName($db, $goods_no) {

		$query = "SELECT GOODS_NM
								FROM TBL_GOODS WHERE 1 = 1 ";
		
		if ($goods_no <> "") {
			$query .= " AND GOODS_NO = '".$goods_no."' ";
		}		

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[0];
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;
	}

	function makeEventSelectBoxOnChange($db, $objname, $size, $str, $val, $checkVal,$event_type) {

		$query = "SELECT EVENT_NO, EVENT_NM
								FROM TBL_EVENT WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' and event_type='$event_type' ";
				
		$query .= " ORDER BY EVENT_NO DESC ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' style='width:".$size."px;' onChange=\"js_".$objname."();\">";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function getListGoods($db, $site_no, $goods_type) {

		$query = "SELECT GOODS_NO, GOODS_NM, GOODS_TYPE
								FROM TBL_GOODS WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

		if ($site_no <> "") {
			$query .= " AND SITE_NO = '$site_no' ";
		}
		
		if ($goods_type <> "") {
			$query .= " AND GOODS_TYPE = '".$goods_type."' ";
		} else {
			$query .= " AND GOODS_TYPE = '' ";
		}
		
		$query .= " ORDER BY DISP_SEQ ";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function getListShoppingGoods($db, $str) {

		$query = "SELECT distinct a.goodsno,b.*,c.price,c.reserve,c.consumer,d.brandnm
								FROM gd_goods_link a left join gd_goods b on a.goodsno=b.goodsno 
										left join gd_goods_option c on a.goodsno=c.goodsno and link 
										left join gd_goods_brand d on b.brandno=d.sno left join gd_category e on a.category=e.category 
								WHERE a.hidden=0
									AND e.level<=0
									AND open
									AND (concat( keyword, goodsnm, goodscd, maker, if(brandnm is null,'',brandnm) ) like '%".$str."%') 
									ORDER BY 	a.sort ";


		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function makeAdminGroupSelectBox($db, $objname,$size,$str,$val,$checkVal) {

		$query = "SELECT GROUP_NO, GROUP_NAME
								FROM TBL_ADMIN_GROUP WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		$query .= " ORDER BY GROUP_NAME ";
		
		//echo $checkVal;

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' style='width:".$size."px;'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_GROUP_NO			= Trim($row[0]);
			$RS_GROUP_NAME		= Trim($row[1]);

			if (trim($checkVal) == trim($RS_GROUP_NO)) {
				$tmp_str .= "<option value='".$RS_GROUP_NO."' selected>".$RS_GROUP_NAME."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_GROUP_NO."'>".$RS_GROUP_NAME."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function getListAdminGroupMenu($db, $group_no) {
		/*
		if ( $group_no == "4"  ) {
			$query = "SELECT CONCAT(A.MENU_SEQ01,A.MENU_SEQ02,A.MENU_SEQ03) as SEQ, A.MENU_CD, A.MENU_NAME, A.MENU_URL, 
										 B.READ_FLAG, B.REG_FLAG, B.UPD_FLAG, B.DEL_FLAG, B.FILE_FLAG, A.MENU_RIGHT 
								FROM TBL_ADMIN_MENU A, TBL_ADMIN_MENU_RIGHT B 
							 WHERE A.MENU_CD = B.MENU_CD 
								 AND B.GROUP_NO = '".$group_no."' 
								 AND A.MENU_FLAG = 'Y'
								 AND A.DEL_TF = 'N'								 
								 AND A.MENU_CD <> '1803'
							 ORDER BY SEQ "; 
		
		} else {
			*/
			$query = "SELECT CONCAT(A.MENU_SEQ01,A.MENU_SEQ02,A.MENU_SEQ03) as SEQ, A.MENU_CD, A.MENU_NAME, A.MENU_URL, 
										 B.READ_FLAG, B.REG_FLAG, B.UPD_FLAG, B.DEL_FLAG, B.FILE_FLAG, A.MENU_RIGHT 
								FROM TBL_ADMIN_MENU A, TBL_ADMIN_MENU_RIGHT B 
							 WHERE A.MENU_CD = B.MENU_CD 
								 AND B.GROUP_NO = '".$group_no."' 
								 AND A.MENU_FLAG = 'Y'
								 AND A.DEL_TF = 'N'								 
								 AND A.MENU_CD LIKE '2%'  
							 ORDER BY SEQ "; 
		
		// }  
		
  
		// echo $query;      

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function makeScriptArray($db,$pcode,$objname) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
			
		$tmp_str_name		=	"";
		$tmp_str_value	=	"";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			$tmp_str_name		.= ",'".$RS_DCODE_NM."'";
			$tmp_str_value	.= ",'".$RS_DCODE."'";
				
		}
		
		$tmp_str_name  = substr($tmp_str_name, 1, strlen($tmp_str_name)-1);
		$tmp_str_value = substr($tmp_str_value, 1, strlen($tmp_str_value)-1);


		$tmp_str	= $objname."_nm = new Array(".$tmp_str_name."); \n";
		$tmp_str .= $objname."_val = new Array(".$tmp_str_value."); \n";

		return $tmp_str;
	}


	function getMemberType($db, $mem_no) {
		
		$query = "SELECT MEM_TYPE FROM TBL_MEMBER 
								WHERE MEM_NO = '".$mem_no."' 
									AND USE_TF = 'Y' 
									AND DEL_TF = 'N' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		$rs_mem_type			= $rows[0];
		
		if ($rs_mem_type == "Z") {
			return "Z";
			exit;
		}

		if ($rs_mem_type == "C") {
			return "C";
			exit;
		}

		$today = date("Y-m-d",strtotime("0 day"));

		$query = "SELECT PAY_TYPE, PAID_DATE, PAY_STATE, MEM_TYPE, DATE_ADD(PAID_DATE,INTERVAL 1 YEAR), DATE_ADD(PAID_DATE,INTERVAL 1 MONTH)
							 FROM TBL_PAYMENT WHERE MEM_NO = '$mem_no' 
								AND PAY_REASON IN ('회원가입','회원정보수정') 
								AND USE_TF = 'Y' 
								AND DEL_TF = 'N' 
								AND PAY_STATE = '1'
							ORDER BY PAY_NO DESC LIMIT 1";
		
		#echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		
		$rs_pay_type			= $rows[0];
		$rs_paid_date			= $rows[1];
		$rs_pay_state			= $rows[2];
		$rs_mem_type			= $rows[3];
		$rs_year_ex_date	= $rows[4];
		$rs_month_ex_date	= $rows[5];
	
		if ($rs_pay_type == "") {

			$query = "SELECT PAY_TYPE, PAID_DATE, PAY_STATE, MEM_TYPE, DATE_ADD(PAID_DATE,INTERVAL 1 YEAR), DATE_ADD(PAID_DATE,INTERVAL 1 MONTH)
								 FROM TBL_PAYMENT WHERE MEM_NO = '$mem_no' 
									AND PAY_REASON IN ('회원가입','회원정보수정') 
									AND USE_TF = 'Y' 
									AND DEL_TF = 'N' 
									AND PAY_STATE = '0'
								ORDER BY PAY_NO DESC LIMIT 1";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
	
			$rs_pay_type			= $rows[0];
			$rs_paid_date			= $rows[1];
			$rs_pay_state			= $rows[2];
			$rs_mem_type			= $rows[3];
			$rs_year_ex_date	= $rows[4];
			$rs_month_ex_date	= $rows[5];

			if ($rs_mem_type == "E") {
				return "E";
			} else {
				return "C";
			}

		} else {
			
			if ($rs_mem_type == "E") {
				return "E";
			}

			if ($rs_mem_type == "L") {

				if ($rs_pay_type == "CMS") {
					
					//echo $rs_month_ex_date;
					if ($today > $rs_month_ex_date) {
						return "C";
					} else {
						return "L";
					}

				} else {
					return "L";
				}
				
			}

			if ($rs_mem_type == "Y") {

				if ($rs_pay_type == "CMS") {
					if ($today > $rs_month_ex_date) {
						return "C";
					} else {
						return "Y";
					}
				} else {
					
					if ($today > $rs_year_ex_date) {
						return "C";
					} else {
						return "Y";
					}
				}
			}
		}
	}

	function DateScript($db,$choice_date) {

		$query = "SELECT count(RESERVE_NO) FROM TBL_RESERVATION WHERE CHECK_IN_DATE <= '$choice_date' and  CHECK_OUT_DATE >='$choice_date' and PERMISSION_YN='Y' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if($rows[0] > 0){
			$RESERVE_YN="Y";
		}

		return $RESERVE_YN;
	}

	function makeRadioBoxWithConditionOnClick($db,$pcode,$objname,$checkVal,$condition) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ".$condition." ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			
			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' checked onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;";
			} else {
				$tmp_str .= "<span><input type = 'radio' name= '".$objname."' value='".$RS_DCODE."' onClick=\"js_".$objname."();\"> ".$RS_DCODE_NM." </span>&nbsp;&nbsp;&nbsp;";
			}
		}
		return $tmp_str;
	}

	function makeSelectBoxWithCondition($db,$pcode,$objname,$size,$str,$val,$checkVal, $condition) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ".$condition." ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' class=\"box01\"  style='width:".$size."px;'>";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeSelectBoxWithConditionOnChange($db,$pcode,$objname,$size,$str,$val,$checkVal, $condition) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ".$condition." ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' class=\"box01\"  style='width:".$size."px;' onChange=\"js_".$objname."();\">";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeCategorySelectBoxOnChange($db, $checkVal) {

		$query = "SELECT MAX(LENGTH(CATE_CD)) as MAX
									FROM TBL_CATEGORY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		$MAX = $rows[0];

		if ($checkVal == "") {
		
			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";

			//echo $query;
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\" style=\"width:150px\">";
			$tmp_str .= "<option value=''>1차 분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
			}
			$tmp_str .= "</select>&nbsp;";

			if ($MAX >= 4) {
				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\" style=\"width:150px\">";
				$tmp_str .= "<option value=''>2차 분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

			/*
			if ($MAX >= 6) {
				$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\" style=\"width:190px\">";
				$tmp_str .= "<option value=''>3차 분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

			if ($MAX >= 8) {
				$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\" style=\"width:190px\">";
				$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}
			*/

		} else {
			
			$cate_01 = substr($checkVal,0,2);
			$cate_02 = substr($checkVal,0,4);
			$cate_03 = substr($checkVal,0,6);
			$cate_04 = substr($checkVal,0,8);

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\" style=\"width:150px\">";
			$tmp_str .= "<option value=''>1차 분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);
				
				if (trim($cate_01) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
				} else {
					$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
			
			if (strlen($checkVal) >= 2) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_01."%' 
										 AND LENGTH(CATE_CD) = '4' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\" style=\"width:150px\">";
				$tmp_str .= "<option value=''>2차 분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_02) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

			/*
			if (strlen($checkVal) >= 4) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_02."%' 
										 AND LENGTH(CATE_CD) = '6' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\" style=\"width:190px\">";
				$tmp_str .= "<option value=''>3차 분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_03) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

			if (strlen($checkVal) >= 6) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_03."%' 
										 AND LENGTH(CATE_CD) = '8' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\" style=\"width:190px\">";
				$tmp_str .= "<option value=''>4차 분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_04) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

			if (strlen($checkVal) == 2) {
		
				if ($MAX >= 6) {
					$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\" style=\"width:190px\">";
					$tmp_str .= "<option value=''>3차 분류 선택</option>";
					$tmp_str .= "</select>&nbsp;";

				}

				if ($MAX >= 8) {
					$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\" style=\"width:190px\">";
					$tmp_str .= "<option value=''>4차 분류 선택</option>";
					$tmp_str .= "</select>&nbsp;";
				}

			}

			if (strlen($checkVal) == 4) {
	

				if ($MAX >= 8) {
					$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\" style=\"width:190px\">";
					$tmp_str .= "<option value=''>4차 분류 선택</option>";
					$tmp_str .= "</select>&nbsp;";
				}
			}
			*/

		}

		return $tmp_str;
	}

	function makeCompanySelectBox($db, $cp_type, $checkVal) {

		$query = "SELECT CP_NO, CP_NM, CP_TYPE
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($cp_type <> "") {
			$query .= " AND CP_TYPE IN ('".$cp_type."','판매공급') ";
		}
		
		$query .= " ORDER BY CP_NM ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='cp_type' class=\"txt\" >";

		$tmp_str .= "<option value=''> 소속선택 </option>";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE				= Trim($row[0]);
			$RS_DCODE_NM		= Trim($row[1]);
			$RS_DCODE_TYPE	= Trim($row[2]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM." [".$RS_DCODE." ".$RS_DCODE_TYPE."]</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM." [".$RS_DCODE." ".$RS_DCODE_TYPE."]</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeCompanySelectBoxWithName($db, $obj, $cp_type, $checkVal) {

		$query = "SELECT CP_NO, CP_NM, CP_TYPE
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($cp_type <> "") {
			$query .= " AND CP_TYPE IN ('".$cp_type."','판매공급') ";
		}
		
		$query .= " ORDER BY CP_NM ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$obj."' class=\"txt\" >";

		$tmp_str .= "<option value=''> 업체선택 </option>";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE				= Trim($row[0]);
			$RS_DCODE_NM		= Trim($row[1]);
			$RS_DCODE_TYPE	= Trim($row[2]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM." [".$RS_DCODE." ".$RS_DCODE_TYPE."]</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM." [".$RS_DCODE." ".$RS_DCODE_TYPE."]</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeCompanySelectBoxAsCpNo($db, $cp_type, $checkVal) {

		$query = "SELECT CP_NO, CP_NM
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($cp_type <> "") {
			$query .= " AND CP_TYPE IN ('".$cp_type."','판매공급') ";
		}
		
		$query .= " ORDER BY CP_NM ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='cp_type2' class=\"txt\" >";

		$tmp_str .= "<option value=''> 업체선택 </option>";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM." [".$RS_DCODE."]</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM." [".$RS_DCODE."]</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeCompanySelectBoxOnChabge($db, $cp_type, $checkVal) {

		$query = "SELECT CP_NO, CP_NM
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($cp_type <> "") {
			$query .= " AND CP_TYPE IN ('".$cp_type."','판매공급') ";
		}
		
		$query .= " ORDER BY CP_NM ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='cp_type' class=\"txt\" onChange=\"js_cp_type()\">";

		$tmp_str .= "<option value=''> 업체선택 </option>";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM." [".$RS_DCODE."]</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM." [".$RS_DCODE."]</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}


    //정당참여희망분야
	function activemakeCheckBox($db, $pcode,$objname,$val,$checkVal) {

		

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		
		$tmp_str = "";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);
			//$RS_DCODE_NM	= Trim(iconv("euc-kr","utf-8",$row["HNAME"]));

			if (strpos("@@,".$checkVal.",@@" , ",".$RS_DCODE.",")) {
				$tmp_str .= '<li><input type="checkbox" class="chk" name="activitycate[]" value="'.$RS_DCODE.'" checked/>&nbsp;'.$RS_DCODE_NM." &nbsp;</li>";
			} else {
				$tmp_str .= '<li><input type="checkbox" class="chk" name="activitycate[]" value="'.$RS_DCODE.'" />&nbsp;'.$RS_DCODE_NM." &nbsp;</li>";
			}
			
		}
		//$tmp_str .= "</select>";
		return $tmp_str;
	}





	
	function getCompanyName($db, $cp_code) {

		if (is_numeric($cp_code)) {

			$query = "SELECT CP_NO, CP_NM FROM TBL_COMPANY WHERE CP_NO = '".$cp_code."' ";
		
			//echo $query;

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);

			if ($result <> "") {
				$tmp_str  = $rows[1]."";
			} else {
				$tmp_str  = "&nbsp;";
			}
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;

	}

	function getCategoryName($db, $cate_code) {
		$query = "SELECT CATE_CD, CATE_NAME
								FROM TBL_CATEGORY WHERE 1 = 1 ";
		$query .= " AND CATE_CD = '$cate_code' ";


		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0]) {
			$tmp_str  = $rows[1]." [".$rows[0]."]";
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;

	}

	function getCategoryName2($db, $cate_code) {
		$query = "SELECT CATE_CD, CATE_NAME
								FROM TBL_CATEGORY WHERE 1 = 1 ";
		$query .= " AND CATE_CD = '$cate_code' ";


		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0]) {
			$tmp_str  = $rows[1];
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;

	}

	function getCompanyCode($db, $admin_id) {

		$query="SELECT C.CP_NO
							FROM TBL_COMPANY C, TBL_ADMIN_INFO A
						 WHERE C.CP_NO = A.COM_CODE
							 AND A.ADM_ID	= '$admin_id'
							 AND C.DEL_TF = 'N' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[0];
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;

	}


	function getCompanyGoodsPrice($db, $goods_no, $cp_no) {

		$query = "SELECT SALE_PRICE FROM TBL_GOODS_PRICE WHERE USE_TF = 'Y' AND GOODS_NO = '".$goods_no."' AND CP_NO = '".$cp_no."' ";
		
			//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[0];
		} else {
			$tmp_str  = "0";
		}

		return $tmp_str;

	}


// 유일키를 생성
function getUniqueId($db, $len=32) {

	$result = @mysql_query(" LOCK TABLES TBL_UNIQUE_ID WRITE, TBL_CART READ, TBL_ORDER READ ");
	
	if (!$result) {
		$sql = " CREATE TABLE TBL_UNIQUE_ID (
										`on_id` int(11) NOT NULL auto_increment,
										`on_uid` varchar(32) NOT NULL default '',
										`on_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
										`session_id` varchar(32) NOT NULL default '',
										PRIMARY KEY  (`on_id`),
										UNIQUE KEY `on_uid` (`on_uid`) ) ";
		mysql_query($sql,$db);
	}

	// 이틀전 자료는 모두 삭제함
	$ytime = date("Y-m-d H:mi:s",strtotime("-2 day"));
	$sql = " delete from TBL_UNIQUE_ID where on_datetime < '$ytime' ";
	//echo $sql;
	mysql_query($sql,$db);

	$unique = false;

	do {
		$sql = " INSERT INTO TBL_UNIQUE_ID set on_uid = NOW(), on_datetime = NOW(), session_id = '".session_id()."' ";
		
		mysql_query($sql,$db);

		$id = @mysql_insert_id();
		$uid = md5($id);
		$sql =  " UPDATE TBL_UNIQUE_ID set on_uid = '$uid' where on_id = '$id' ";
		mysql_query($sql,$db);
		// 장바구니에도 겹치는게 있을 수 있으므로 ...
		$sql = "select COUNT(*) as cnt from TBL_CART where ON_UID = '$uid' ";
		
		$result = mysql_query($sql,$db);
		$rows   = mysql_fetch_array($result);

		if (!$row[0]) {
			// 주문서에도 겹치는게 있을 수 있으므로 ...
			$sql = "select COUNT(*) as cnt from TBL_ORDER where ON_UID = '$uid' ";

			$result = mysql_query($sql,$db);
			$rows   = mysql_fetch_array($result);

			if (!$row[0])
				$unique = true;
			}
		} while (!$unique); // $unique 가 거짓인동안 실행

		@mysql_query(" UNLOCK TABLES ");

	return $uid;
}


// 주문에서 사용할 함수들 입니다.
// 유일키를 생성
function getReservNo($db, $type, $len=13) {

	$thisdate = date("Y-m-d",strtotime("0 month"));;
	$thisdate_Reserve_no = date("Ymd",strtotime("0 month"));;

	$query ="SELECT COUNT(CNT_NO) AS CNT FROM TBL_RESERVE_NO WHERE THIS_DATE = '$thisdate'";
	$result = mysql_query($query,$db);
	$rows   = mysql_fetch_array($result);
	
	if (!$rows[0]) {
		$sql = " INSERT INTO TBL_RESERVE_NO (CNT_NO, THIS_DATE) VALUES ('1','$thisdate'); ";
	} else {
		$sql = " UPDATE TBL_RESERVE_NO SET CNT_NO = CNT_NO + 1 WHERE THIS_DATE = '$thisdate' ";
	}

	//echo $sql;
	
	mysql_query($sql,$db);
	
	$query ="SELECT IFNULL(MAX(CNT_NO),0) AS NEXT_NO FROM TBL_RESERVE_NO WHERE THIS_DATE = '$thisdate'";
	$result = mysql_query($query,$db);
	$rows   = mysql_fetch_array($result);
	$new_reserve_no  = $thisdate_Reserve_no.$type.right("00000".$rows[0],5);
	
	return $new_reserve_no;
}

function memberChk($db, $name, $phone, $hphone) {

	$query ="SELECT COUNT(MEM_NO) AS CNT 
						 FROM TBL_MEMBER 
						WHERE MEM_NM = '$name'
							AND PHONE = '$phone'
							AND HPHONE = '$hphone' 
							AND USE_TF = 'Y' 
							AND DEL_TF = 'N' ";
	$result = mysql_query($query,$db);
	$rows   = mysql_fetch_array($result);

	if (!$rows[0]) {
		return false;
	} else {
		return true;
	}
}

function getMemberNo($db, $name, $phone, $hphone) {
	
	$mem_no = "";

	$query ="SELECT MEM_NO 
						 FROM TBL_MEMBER 
						WHERE MEM_NM = '$name'
							AND PHONE = '$phone'
							AND HPHONE = '$hphone' 
							AND USE_TF = 'Y' 
							AND DEL_TF = 'N' ";
	$result = mysql_query($query,$db);
	$rows   = mysql_fetch_array($result);

	if ($result <> "") {
		$mem_no  = $rows[0];
	}

	return $mem_no;

}

function getDeliveryLink($db, $delivery_cp, $delivery_no) {
	
	$delivery_url = "";

	$query ="SELECT DCODE_EXT, DCODE, DCODE_NM 
						 FROM TBL_CODE_DETAIL 
						WHERE PCODE = 'DELIVERY_CP'
							AND DCODE = '$delivery_cp'
							AND USE_TF = 'Y' 
							AND DEL_TF = 'N' ";
	
	//echo $query;

	$result = mysql_query($query,$db);
	$rows   = mysql_fetch_array($result);

	if ($result <> "") {
		$delivery_url		= $rows[0];
		$delivery_cp		= $rows[2];
	}
	
	if ($delivery_url == "") {
		$url = "택배사경로 없음";
	} else {
		$url = "<a href='".$delivery_url.$delivery_no."' target='_new'>".$delivery_cp." ".$delivery_no."</a>";
	}
	return $url;

}

	function resetOrderInfor($db, $reserve_no) {
		
		$query = "SELECT QTY, BUY_PRICE, SALE_PRICE, EXTRA_PRICE, ORDER_STATE
								FROM TBL_ORDER_GOODS
							 WHERE USE_TF = 'Y'
								 AND DEL_TF = 'N'
								 AND RESERVE_NO = '$reserve_no' ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
			
		$tmp_order_state = "";

		$total_qty = 0;
		$total_buy_price = 0;
		$total_sale_price = 0;
		$total_extra_price = 0;

		for($i=0 ; $i< $total ; $i++) {

			mysql_data_seek($result,$i);

			$row     = mysql_fetch_array($result);

			$RS_QTY							= Trim($row["QTY"]);
			$RS_BUY_PRICE				= Trim($row["BUY_PRICE"]);
			$RS_SALE_PRICE			= Trim($row["SALE_PRICE"]);
			$RS_EXTRA_PRICE			= Trim($row["EXTRA_PRICE"]);
			$RS_ORDER_STATE			= Trim($row["ORDER_STATE"]);

			if (($RS_ORDER_STATE == "0") || ($RS_ORDER_STATE == "1") || ($RS_ORDER_STATE == "2") || ($RS_ORDER_STATE == "3")) {
				$total_qty = $total_qty + $RS_QTY;
				$total_buy_price = $total_buy_price + ($RS_BUY_PRICE * $RS_QTY);
				$total_sale_price = $total_sale_price + ($RS_SALE_PRICE * $RS_QTY);
				$total_extra_price = $total_extra_price + ($RS_EXTRA_PRICE * $RS_QTY);
			} else if ($RS_ORDER_STATE == "4") {
				$total_qty = $total_qty;
				$total_buy_price = $total_buy_price;
				$total_sale_price = $total_sale_price;
				$total_extra_price = $total_extra_price;
			} else {
				$total_qty = $total_qty - $RS_QTY;
				$total_buy_price = $total_buy_price - ($RS_BUY_PRICE * $RS_QTY);
				$total_sale_price = $total_sale_price - ($RS_SALE_PRICE * $RS_QTY);
				$total_extra_price = $total_extra_price - ($RS_EXTRA_PRICE * $RS_QTY);
			}

			if ($i == 0) {
				$tmp_order_state = $RS_ORDER_STATE;
			} else {
				$tmp_order_state .= ",".$RS_ORDER_STATE;
			}
		}
		
		$up_query = "UPDATE TBL_ORDER SET ORDER_STATE = '$tmp_order_state', 
																			TOTAL_BUY_PRICE = '$total_buy_price',
																			TOTAL_SALE_PRICE = '$total_sale_price',
																			TOTAL_EXTRA_PRICE = '$total_extra_price',
																			TOTAL_QTY = '$total_qty'
																WHERE RESERVE_NO = '$reserve_no' 
																	AND USE_TF = 'Y'
																	AND DEL_TF = 'N' ";
		
		if(!mysql_query($up_query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function resetPaymentInfor($db, $reserve_no) {
		
		$query = "SELECT TOTAL_SALE_PRICE , TOTAL_EXTRA_PRICE, TOTAL_DELIVERY_PRICE 
								FROM TBL_ORDER
							 WHERE USE_TF = 'Y'
								 AND DEL_TF = 'N'
								 AND RESERVE_NO = '$reserve_no' ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
			
		for($i=0 ; $i< $total ; $i++) {

			mysql_data_seek($result,$i);

			$row     = mysql_fetch_array($result);

			$RS_SALE_PRICE			= Trim($row["TOTAL_SALE_PRICE"]);
			$RS_EXTRA_PRICE			= Trim($row["TOTAL_EXTRA_PRICE"]);
			$RS_DELIVERY_PRICE	= Trim($row["TOTAL_DELIVERY_PRICE"]);
			
			$TOTAL_PAYMENT = ($RS_SALE_PRICE + $RS_EXTRA_PRICE + $RS_DELIVERY_PRICE);
		}
		
		$up_query = "UPDATE TBL_PAYMENT SET BANK_AMOUNT = '$TOTAL_PAYMENT'
																WHERE PAY_REASON = '물품구매' 
																	AND RESERVE_NO = '$reserve_no' 
																	AND USE_TF = 'Y'
																	AND DEL_TF = 'N' ";
		
		if(!mysql_query($up_query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}


	function getCompayChk ($db, $cp_type, $s_adm_cp_type, $cp_no) {

		if ($s_adm_cp_type ="운영") {

			$query="SELECT COUNT(*) AS CNT FROM TBL_COMPANY 
							 WHERE CP_TYPE LIKE '%".$cp_type."%' 
								 AND CP_NO	= '$cp_no'
								 AND DEL_TF = 'N' ";
			
		} else {

			$query="SELECT COUNT(*) AS CNT 
								FROM TBL_COMPANY C, TBL_ADMIN_INFO A
							 WHERE C.CP_NO = A.COM_CODE
								 AND C.CP_TYPE LIKE '%".$cp_type."%' 
								 AND A.ADM_ID	= '$cp_no'
								 AND C.DEL_TF = 'N' ";
			
		
		}
		
		//echo $query;
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}

	}


	function getGoodsNoChk ($db, $goods_no) {

		$query="SELECT COUNT(*) AS CNT FROM TBL_GOODS 
						 WHERE GOODS_NO	= '$goods_no'
							 AND DEL_TF = 'N' ";
			
		
		//echo $query;
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}

	}

	function getCompanyChk ($db, $cp_no) {

		$query="SELECT COUNT(*) AS CNT FROM TBL_COMPANY 
						 WHERE CP_NO	= '$cp_no'
							 AND DEL_TF = 'N' ";
			
		
		//echo $query;
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}
	}

	function chkZip($db, $zipcode) {
		
		$zipcode = str_replace("-","",$zipcode);

		$query="SELECT COUNT(*) AS CNT FROM TBL_ZIPCODE WHERE POST_NO = '$zipcode' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}
	}

	function getDeliveryUrl($db, $delivery_cp) {
		
		$query="SELECT DCODE_EXT FROM TBL_CODE_DETAIL WHERE PCODE = 'DELIVERY_CP' AND DCODE = '$delivery_cp' AND USE_TF = 'Y' AND DEL_TF = 'N' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0]) {
			return $rows[0];
		} else {
			return false;
		}
	}

	function isHeadAdmin($db, $adm_no) {
		
		$query="SELECT COUNT(*) AS CNT 
							FROM TBL_ADMIN_INFO A, TBL_COMPANY C
						 WHERE A.COM_CODE = C.CP_NO
							 AND C.CP_TYPE = '운영'
							 AND A.DEL_TF = 'N'
							 AND A.USE_TF = 'Y'
							 AND A.ADM_NO = '$adm_no' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($rows[0] == 0) {
			return false;
		} else {
			return true;
		}
	}

	function make_visual_xml_file($db) {

		$xml_str = "";
		
		$query = "SELECT BANNER_NO, SITE_NO, BANNER_TYPE, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, BANNER_URL, DISP_SEQ, 
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE 1 = 1 
								 AND USE_TF = 'Y' AND DEL_TF = 'N' AND BANNER_TYPE = 'flash' ORDER BY DISP_SEQ ASC LIMIT 0, 5 ";

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$xml_str  = "<?xml version=\"1.0\" encoding=\"euc-kr\"?>\n";
		$xml_str .= "<data>\n";


		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);

			$BANNER_URL		= Trim($row[6]);
			$BANNER_IMG		= Trim($row[4]);
			$BANNER_NM 		= Trim($row[3]);

			$xml_str .= "<photo original=\"/upload_data/banner/".$BANNER_IMG."\" link=\"".$BANNER_URL."\" frame=\"_self\" des1=\"".$BANNER_NM."\"/>\n";
		}

		$xml_str .= "</data>";


		$dirname = "/home/httpd/bluecompany/kor/flash/";
		$filename = "main_visual_list.xml";

		//$xml_str=iconv("euc-kr","UTF-8",$xml_str); 

		$fp = fopen("$dirname$filename","w");
		fputs($fp,$xml_str);
		fclose($fp);

	}


	function getBanner($db, $site_no, $banner_type, $nRowCount) {

		$query = "SELECT BANNER_NO, SITE_NO, BANNER_TYPE, BANNER_NM, BANNER_IMG, BANNER_REAL_IMG, BANNER_URL, DISP_SEQ,
										 USE_TF, DEL_TF, REG_ADM, REG_DATE, UP_ADM, UP_DATE, DEL_ADM, DEL_DATE
								FROM TBL_BANNER WHERE 1 = 1 AND USE_TF = 'Y' AND DEL_TF = 'N' ";

		$query .= " AND BANNER_TYPE = '".$banner_type."' ";
		$query .= " AND SITE_NO = '".$site_no."' ";

		$query .= " ORDER BY DISP_SEQ asc limit 0, ".$nRowCount;

		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function getQueryWord($db) {
		
		$query="SELECT QUERY_WORD FROM TBL_QUERY_WORD
						 WHERE USE_TF = 'Y' AND DEL_TF = 'N' 
						 ORDER BY ADMIN_CHK DESC, CNT DESC LIMIT 0,1 ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function make_rolling_banner_xml_file($db) {

		$xml_str = "";

		$query = "SELECT A.GOODS_NO, A.GOODS_CATE, A.GOODS_CODE, A.GOODS_NAME, A.GOODS_SUB_NAME, 
										 A.CATE_01, A.CATE_02, A.CATE_03, A.CATE_04, A.PRICE, A.BUY_PRICE, A.SALE_PRICE, A.EXTRA_PRICE, 
										 A.STOCK_CNT, A.IMG_URL, A.FILE_NM_100, A.FILE_RNM_100, A.FILE_PATH_100, A.FILE_SIZE_100, A.FILE_EXT_100, 
										 A.FILE_NM_150, A.FILE_RNM_150, A.FILE_PATH_150, A.FILE_SIZE_150, A.FILE_EXT_150, A.CONTENTS, A.READ_CNT, 
										 A.DISP_SEQ, A.USE_TF, A.DEL_TF, A.REG_ADM, A.REG_DATE, A.UP_ADM, A.UP_DATE, A.DEL_ADM, A.DEL_DATE, 
										 (SELECT CP_NM FROM TBL_COMPANY WHERE TBL_COMPANY.CP_NO = A.CATE_03 ) AS CP_NAME, B.CATE_GOODS_NO, B.SEQ_NO_BIG, B.SEQ_NO_SMALL 
								FROM TBL_GOODS A, TBL_CATEGORY_GOODS B 
							 WHERE A.GOODS_NO = B.GOODS_NO 
								 AND A.CATE_04 IN ('판매중','재판매') 
								 AND A.DEL_TF = 'N' 
								 AND A.GOODS_CATE like '%' 
								 AND B.DISP_LOCATION = 'sub_main' 
							 ORDER BY SEQ_NO_SMALL ASC ";

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$xml_str  = "<data imgW=\"130\" imgH=\"130\" imgGap=\"19\">\n";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);

			$GOODS_NO					= trim($row[0]);
			$GOODS_CATE				= trim($row[1]);
			$GOODS_NAME				= SetStringFromDB($row[3]);
			$GOODS_SUB_NAME		= SetStringFromDB($row[4]);
			$SALE_PRICE				= trim($row[11]);
			$IMG_URL					= trim($row[14]);
			$FILE_NM					= trim($row[15]);
			$FILE_NM_150			= trim($row[20]);
			$FILE_RNM_150			= trim($row[21]);
			$FILE_PATH_150		= trim($row[22]);

			$img_url	= getGoodsImage($FILE_NM, $IMG_URL, $FILE_PATH_150, $FILE_RNM_150, "130", "130");

			if ($GOODS_SUB_NAME) { 
				$GOODS_SUB_NAME = "[".$GOODS_SUB_NAME."]";
			} 

			$xml_str .= "<photo image=\"".$img_url."\" link=\"/kor/product/goods_detail.php?goods_no=".$GOODS_NO."\" frame=\"_self\" subject=\"".$GOODS_SUB_NAME."\" desc=\"".$GOODS_NAME."\" price=\"".number_format($SALE_PRICE)."원\"/>\n";
		}
		$xml_str .= "</data>";

		$dirname = "/home/httpd/bluecompany/kor/flash/";
		$filename = "main_rolling_banner_list.xml";

		//$xml_str=iconv("euc-kr","UTF-8",$xml_str); 

		$fp = fopen("$dirname$filename","w");
		fputs($fp,$xml_str);
		fclose($fp);

	}

	function getOrderGoodsName($db, $reserve_no) {

		$goods_name = "";

		$query = "SELECT GOODS_NAME 
								FROM TBL_ORDER_GOODS  
								WHERE ORDER_STATE IN ('0', '1', '2', '3')
								  AND DEL_TF = 'N' AND USE_TF = 'Y'
								  AND RESERVE_NO = '$reserve_no' ";

		$query .= " ORDER BY ORDER_SEQ DESC ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$goods_name = Trim($row[0]);
		}

		if ($total > 1) {
			$goods_name = $goods_name."외".($total -1)."건";
		}

		return $goods_name;
	}

	function getEzwelOrderNo($db, $reserve_no) {

		$cp_order_no = "";

		$query = "SELECT CP_ORDER_NO 
								FROM TBL_ORDER_GOODS  
								WHERE DEL_TF = 'N' AND USE_TF = 'Y'
								  AND RESERVE_NO = '$reserve_no' LIMIT 0, 1 ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$cp_order_no = Trim($row[0]);
		}

		return $cp_order_no;
	}

	function getEzwelMemNo($db, $mem_id) {

		$mem_no = "";

		$query = "SELECT M_NO 
								FROM TBL_MEMBER  
								WHERE DEL_TF = 'N' AND USE_TF = 'Y'
								  AND M_ID = '$mem_id' LIMIT 0, 1 ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();
		

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$mem_no = Trim($row[0]);
		}

		return $mem_no;
	}

	function resetDeliveryInfor($db, $reserve_no) {
		
		$query ="UPDATE TBL_ORDER SET TOTAL_DELIVERY_PRICE = '0' WHERE RESERVE_NO = '$reserve_no' ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}
	
	function insertNameCheckLog($db, $check_kind, $name, $person_code, $data1, $data2, $data3, $flag) {
		
		$query="INSERT INTO TBL_NM_CHECK_LOG (CHK_KIND, NAME, PERSON_CODE, DATA1, DATA2, DATA3, FLAG, CHK_DATE) 
		values ('$check_kind', '$name', password('$person_code'), '$data1', '$data2', '$data3', '$flag', now()); ";

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function cntElectoral ($db) {

		$str_counter = "";

		//2012-02-18$query="SELECT COUNT(*) CNT FROM TBL_ELECTORAL_COLLEGE  WHERE DEL_TF = 'N' ";
		$query="SELECT COUNT(*) CNT FROM TBL_ELECTORAL_COLLEGE 
						 WHERE DEL_TF = 'N' AND REG_DATE>'2012-02-18 08:00:00'";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		
		$str_record = right("000000".$record,6);
		
		for ($i = 0 ; $i < strlen($str_record) ; $i++) {
			$str_counter = $str_counter."<img src='/kor/images/main/".substr($str_record, $i,1).".png' alt='' />";
		}

		return $str_counter;
	}


	function makeCategorySelectBoxOnChange2($db, $checkVal) {

		$query = "SELECT MAX(LENGTH(CATE_CD)) as MAX
									FROM TBL_CATEGORY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		$MAX = $rows[0];

		if ($checkVal == "") {
		
			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND LENGTH(CATE_CD) = '2' 
									 AND CATE_CD !='01'
								 ORDER BY SEQ ASC ";

			//echo $query;
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
			$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
			}
			$tmp_str .= "</select>&nbsp;";


			if ($MAX >= 4) {
				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
				$tmp_str .= "<option value=''>2차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

		} else {
			
			$cate_01 = substr($checkVal,0,2);
			$cate_02 = substr($checkVal,0,4);
			$cate_03 = substr($checkVal,0,6);
			$cate_04 = substr($checkVal,0,8);

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
			$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);
				
				if (trim($cate_01) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
				} else {
					$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
				
			if (strlen($checkVal) >= 2) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_01."%' 
										 AND LENGTH(CATE_CD) = '4' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
				$tmp_str .= "<option value=''>2차 지역분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_02) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

	}

		return $tmp_str;
	}


	function sonamu_show_media ($ad_kind, $ext, $link, $file_nm, $autostart, $file_size) {

		if ($autostart == "1") {
			$autostart = "1";
		} else {
			$autostart = "0";
		}

		$str = "";

		$ext = strtolower($ext);
		
		if ($ad_kind == "AD01") {
			if ($ext == "link") {
				$str = "<iframe width=\"560\" height=\"315\" src=\"".$link."\" frameborder=\"0\" allowfullscreen></iframe>";
			}
		}

		if ($ad_kind == "AD02") {
			if ($ext == "link") {
				$str = "<OBJECT height=45 width=300>
								<PARAM NAME=\"movie\" VALUE=\"".$link."\">
								<PARAM NAME=\"allowFullScreen\" VALUE=\"true\"><PARAM NAME=\"allowscriptaccess\" VALUE=\"always\">
								<PARAM NAME=\"autostart\" VALUE=\"".$autostart."\">
								<embed src=\"".$link."\" type=\"application/x-mplayer2\" allowscriptaccess=\"always\" autostart=\"".$autostart."\" allowfullscreen=\"true\" width=\"300\" height=\"45\"></embed></OBJECT>";

			}
		}
		
		if (($ext == "gif") || ($ext == "jpg") || ($ext == "png") || ($ext == "jpeg")) {

			if (($ad_kind == "AD03") || ($ad_kind == "AD05")) {
				$str = "<img src=\"/upload_data/board/".$file_nm."\" alt=\"".$file_rnm."\" width=\"162\" height=\"167\">";
			} else if ($ad_kind == "AD04") {
				$str = "<img src=\"/upload_data/board/".$file_nm."\" alt=\"".$file_rnm."\" width=\"160\" height=\"113\">";
			} else {
				$str = "<img src=\"/upload_data/board/".$file_nm."\" alt=\"".$file_rnm."\" width=\"140\" height=\"95\">";
			}
		}
		
		if (($ext == "wmv") || ($ext == "avi") || ($ext == "mp4") || ($ext == "mpg")|| ($ext == "mov")) {
			//$str = "<embed src=\"/upload_data/advertisement/".$file_nm."\" autostart=\"true\" width=\"262px\" height=\"236px\" allowScriptAccess='always'></embed>";

			$str = "<OBJECT height=250 width=330>
							<PARAM NAME=\"movie\" VALUE=\"/upload_data/board/".$file_nm."\">
							<PARAM NAME=\"allowFullScreen\" VALUE=\"true\">
							<PARAM NAME=\"allowscriptaccess\" VALUE=\"always\">
							<PARAM NAME=\"autostart\" VALUE=\"".$autostart."\">
							<embed src=\"/upload_data/board/".$file_nm."\" type=\"application/x-mplayer2\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"330\" height=\"250\"></embed></OBJECT>";

		}

		if (($ext == "wav") || ($ext == "mp3") || ($ext == "mid") ) {
			//$str = "<embed src=\"/upload_data/advertisement/".$file_nm."\" autostart=\"true\" width=\"262px\" height=\"236px\" allowScriptAccess='always'></embed>";
			//$str = "<EMBED type=audio/x-wav src=\"/upload_data/advertisement/".$file_nm."\" ></EMBED>";
			
			$str = "<OBJECT height=45 width=300>
							<PARAM NAME=\"movie\" VALUE=\"/upload_data/board/".$file_nm."\">
							<PARAM NAME=\"allowFullScreen\" VALUE=\"true\">
							<PARAM NAME=\"allowscriptaccess\" VALUE=\"always\">
							<PARAM NAME=\"autostart\" VALUE=\"".$autostart."\">
							<embed src=\"/upload_data/board/".$file_nm."\" type=\"application/x-mplayer2\" allowscriptaccess=\"always\" autostart=\"".$autostart."\" allowfullscreen=\"true\" width=\"300\" height=\"45\"></embed></OBJECT>";

		}

		return $str;
	}


	
	function sonamu_show_media2 ($ad_kind, $ext, $link, $file_nm, $autostart, $file_size) {

		if ($autostart == "1") {
			$autostart = "1";
		} else {
			$autostart = "0";
		}

		$str = "";

		$ext = strtolower($ext);
		
		if ($ad_kind == "AD01") {
			if ($ext == "link") {
				$str = "<iframe width=\"125\" height=\"62\" src=\"".$link."\" frameborder=\"0\" allowfullscreen></iframe>";
			}
		}

		if ($ad_kind == "AD02") {
			if ($ext == "link") {
				$str = "<OBJECT height=62 width=125>
								<PARAM NAME=\"movie\" VALUE=\"".$link."\">
								<PARAM NAME=\"allowFullScreen\" VALUE=\"true\"><PARAM NAME=\"allowscriptaccess\" VALUE=\"always\">
								<PARAM NAME=\"autostart\" VALUE=\"".$autostart."\">
								<embed src=\"".$link."\" type=\"application/x-mplayer2\" allowscriptaccess=\"always\" autostart=\"".$autostart."\" allowfullscreen=\"true\" width=\"300\" height=\"45\"></embed></OBJECT>";

			}
		}
		
		if (($ext == "gif") || ($ext == "jpg") || ($ext == "png") || ($ext == "jpeg")) {

			if (($ad_kind == "AD03") || ($ad_kind == "AD05")) {
				$str = "<img src=\"/upload_data/board/".$file_nm."\" alt=\"".$file_rnm."\" width=\"125\" height=\"62\">";
			} else if ($ad_kind == "AD04") {
				$str = "<img src=\"/upload_data/board/".$file_nm."\" alt=\"".$file_rnm."\" width=\"125\" height=\"62\">";
			} else {
				$str = "<img src=\"/upload_data/board/".$file_nm."\" alt=\"".$file_rnm."\" width=\"125\" height=\"62\">";
			}
		}
		
		if (($ext == "wmv") || ($ext == "avi") || ($ext == "mp4") || ($ext == "mpg")|| ($ext == "mov")) {
			//$str = "<embed src=\"/upload_data/advertisement/".$file_nm."\" autostart=\"true\" width=\"262px\" height=\"236px\" allowScriptAccess='always'></embed>";

			$str = "<OBJECT height=62 width=125>
							<PARAM NAME=\"movie\" VALUE=\"/upload_data/board/".$file_nm."\">
							<PARAM NAME=\"allowFullScreen\" VALUE=\"true\">
							<PARAM NAME=\"allowscriptaccess\" VALUE=\"always\">
							<PARAM NAME=\"autostart\" VALUE=\"".$autostart."\">
							<embed src=\"/upload_data/board/".$file_nm."\" type=\"application/x-mplayer2\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"125\" height=\"62\"></embed></OBJECT>";

		}

		if (($ext == "wav") || ($ext == "mp3") || ($ext == "mid") ) {
			//$str = "<embed src=\"/upload_data/advertisement/".$file_nm."\" autostart=\"true\" width=\"262px\" height=\"236px\" allowScriptAccess='always'></embed>";
			//$str = "<EMBED type=audio/x-wav src=\"/upload_data/advertisement/".$file_nm."\" ></EMBED>";
			
			$str = "<OBJECT height=62 width=125>
							<PARAM NAME=\"movie\" VALUE=\"/upload_data/board/".$file_nm."\">
							<PARAM NAME=\"allowFullScreen\" VALUE=\"true\">
							<PARAM NAME=\"allowscriptaccess\" VALUE=\"always\">
							<PARAM NAME=\"autostart\" VALUE=\"".$autostart."\">
							<embed src=\"/upload_data/board/".$file_nm."\" type=\"application/x-mplayer2\" allowscriptaccess=\"always\" autostart=\"".$autostart."\" allowfullscreen=\"true\" width=\"125\" height=\"62\"></embed></OBJECT>";

		}

		return $str;
	}

	function makeCategorySelectBoxOnChange3($db, $checkVal) {

		$query = "SELECT MAX(LENGTH(CATE_CD)) as MAX
									FROM TBL_CATEGORY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		$MAX = $rows[0];

		if ($checkVal == "") {
		
			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND CATE_CD <> '01'
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";

			//echo $query;
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
			$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
			}
			$tmp_str .= "</select>&nbsp;";


			if ($MAX >= 4) {
				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
				$tmp_str .= "<option value=''>2차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

		} else {
			
			$cate_01 = substr($checkVal,0,2);
			$cate_02 = substr($checkVal,0,4);
			$cate_03 = substr($checkVal,0,6);
			$cate_04 = substr($checkVal,0,8);

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND CATE_CD <> '01'
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
			$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);
				
				if (trim($cate_01) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
				} else {
					$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
				
			if (strlen($checkVal) >= 2) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_01."%' 
										 AND LENGTH(CATE_CD) = '4' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
				$tmp_str .= "<option value=''>2차 지역분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_02) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

	}

		return $tmp_str;
	}


	function getShortenUrl($db, $str_long_url) {
		$query ="SELECT SHORTEN_URL FROM TBL_SHORTENURL WHERE LONG_URL = '$str_long_url' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function getSidShortenUrl($db, $str_long_url) {
		$query ="SELECT sid FROM TBL_SHORTENED_URLS WHERE url = '$str_long_url' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}

	function insertShortenUrl($db, $str_long_url, $shorten_url) {
		$query ="SELECT COUNT(*) CNT FROM TBL_SHORTENURL WHERE LONG_URL = '$str_long_url' ";
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		if ($record == 0) {
			$query ="INSERT INTO TBL_SHORTENURL (LONG_URL, SHORTEN_URL) VALUES ('$str_long_url', '$shorten_url')";
			
			echo $query;
			if(!mysql_query($query,$db)) {
				return false;
			} else {
				return true;
			}
		}
	}


	function makeCategorySelectBoxOnChangeArea($db, $checkVal) {

		$query = "SELECT MAX(LENGTH(CATE_CD)) as MAX
									FROM TBL_CATEGORY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		$MAX = $rows[0];

		if ($checkVal == "") {
		
			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND CATE_CD <> '01' 
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";

			//echo $query;
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
			$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);

				$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
			}
			$tmp_str .= "</select>&nbsp;";


			if ($MAX >= 4) {
				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
				$tmp_str .= "<option value=''>2차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

			if ($MAX >= 6) {
				$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\">";
				$tmp_str .= "<option value=''>3차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

			if ($MAX >= 8) {
				$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
				$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
				$tmp_str .= "</select>&nbsp;";
			}

		} else {
			
			$cate_01 = substr($checkVal,0,2);
			$cate_02 = substr($checkVal,0,4);
			$cate_03 = substr($checkVal,0,6);
			$cate_04 = substr($checkVal,0,8);

			$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
									FROM TBL_CATEGORY 
								 WHERE 1 = 1 
									 AND DEL_TF = 'N' 
									 AND USE_TF = 'Y'
									 AND CATE_CD <> '01' 
									 AND LENGTH(CATE_CD) = '2' 
								 ORDER BY SEQ ASC ";
	
			$result = mysql_query($query,$db);
			$total  = mysql_affected_rows();

			$tmp_str = "<select name='gd_cate_01' onChange=\"js_gd_cate_01();\">";
			$tmp_str .= "<option value=''>1차 지역분류 선택</option>";

			for($i=0 ; $i< $total ; $i++) {
				mysql_data_seek($result,$i);
				$row     = mysql_fetch_array($result);
			
				$RS_CATE_CD		= Trim($row[1]);
				$RS_CATE_NAME	= Trim($row[2]);
				
				if (trim($cate_01) == trim($RS_CATE_CD)) {
					$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
				} else {
					$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
				}
			}
			$tmp_str .= "</select>&nbsp;";
				
			if (strlen($checkVal) >= 2) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_01."%' 
										 AND LENGTH(CATE_CD) = '4' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_02' onChange=\"js_gd_cate_02();\">";
				$tmp_str .= "<option value=''>2차 지역분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_02) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

			if (strlen($checkVal) >= 4) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_02."%' 
										 AND LENGTH(CATE_CD) = '6' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\">";
				$tmp_str .= "<option value=''>3차 지역분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_03) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

			if (strlen($checkVal) >= 6) {

				$query = "SELECT CONCAT(CATE_SEQ01,CATE_SEQ02,CATE_SEQ03,CATE_SEQ04) as SEQ, CATE_CD, CATE_NAME
										FROM TBL_CATEGORY 
									 WHERE 1 = 1 
										 AND DEL_TF = 'N' 
										 AND USE_TF = 'Y'
										 AND CATE_CD LIKE '".$cate_03."%' 
										 AND LENGTH(CATE_CD) = '8' 
									 ORDER BY SEQ ASC ";
				$result = mysql_query($query,$db);
				$total  = mysql_affected_rows();

				$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
				$tmp_str .= "<option value=''>4차 지역분류 선택</option>";

				for($i=0 ; $i< $total ; $i++) {
					mysql_data_seek($result,$i);
					$row     = mysql_fetch_array($result);
			
					$RS_CATE_CD		= Trim($row[1]);
					$RS_CATE_NAME	= Trim($row[2]);
				
					if (trim($cate_04) == trim($RS_CATE_CD)) {
						$tmp_str .= "<option value='".$RS_CATE_CD."' selected>".$RS_CATE_NAME."</option>";
					} else {
						$tmp_str .= "<option value='".$RS_CATE_CD."'>".$RS_CATE_NAME."</option>";
					}
				}
				$tmp_str .= "</select>&nbsp;";
			}

			if (strlen($checkVal) == 2) {
		
				if ($MAX >= 6) {
					$tmp_str .= "<select name='gd_cate_03' onChange=\"js_gd_cate_03();\">";
					$tmp_str .= "<option value=''>3차 지역분류 선택</option>";
					$tmp_str .= "</select>&nbsp;";

				}

				if ($MAX >= 8) {
					$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
					$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
					$tmp_str .= "</select>&nbsp;";
				}

			}

			if (strlen($checkVal) == 4) {
	

				if ($MAX >= 8) {
					$tmp_str .= "<select name='gd_cate_04' onChange=\"js_gd_cate_04();\">";
					$tmp_str .= "<option value=''>4차 지역분류 선택</option>";
					$tmp_str .= "</select>&nbsp;";
				}
			}

		}

		return $tmp_str;
	}

	function getBannerList ($db, $banner_type, $dip_cnt) {
		
		$tmp_str = "";
		
		$query = "SELECT BANNER_IMG, BANNER_URL, URL_TYPE, BANNER_NM 
								FROM TBL_BANNER WHERE BANNER_TYPE = '$banner_type' AND USE_TF = 'Y' AND DEL_TF = 'N' ORDER BY DISP_SEQ ASC LIMIT $dip_cnt ";
		
		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}

	function getBcodeName($db, $board_code) {

		$query = "SELECT BOARD_NM
								FROM TBL_BOARD_CONFIG WHERE 1 = 1 ";
		
		if ($board_code <> "") {
			$query .= " AND BOARD_CODE = '".$board_code."' ";
		}

		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[0];
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;
	}

	function getBcodeCate($db, $board_code) {

		$query = "SELECT BOARD_CATE
								FROM TBL_BOARD_CONFIG WHERE 1 = 1 ";
		
		if ($board_code <> "") {
			$query .= " AND BOARD_CODE = '".$board_code."' ";
		}

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$tmp_str  = $rows[0];
		} else {
			$tmp_str  = "&nbsp;";
		}

		return $tmp_str;
	}
	function totalMyCcommunityCommentTf($db, $writer_id, $type) {
	
		$query = "SELECT COUNT(*) CNT
							FROM (
										SELECT COMM_NO, BB_CODE, BB_NO, TITLE, WRITER_NM, REG_DATE
											FROM CTBL_BOARD 
										 WHERE COMMENT_TF = 'Y' 
											 AND WRITER_ID = '$writer_id'
											 AND DEL_TF = 'N'
											 AND USE_TF = 'Y'
										 UNION
										SELECT B.COMM_NO, A.CATE_01 AS BB_CODE, A.CATE_02 AS BB_NO, B.TITLE, B.WRITER_NM, B.REG_DATE
											FROM CTBL_BOARD_COMMENT A, CTBL_BOARD B
										 WHERE A.COMMENT_TF = 'Y' 
											 AND A.WRITER_ID = '$writer_id'
											 AND A.CATE_01 = B.BB_CODE
											 AND A.CATE_02 = B.BB_NO
											 AND A.DEL_TF = 'N'
											 AND A.USE_TF = 'Y'
											 AND B.DEL_TF = 'N'
											 AND B.USE_TF = 'Y'
										 ) AA, CTBL_COMMUNITY BB, CTBL_BOARD_CONFIG CC
									WHERE AA.COMM_NO = BB.COMM_NO
										AND AA.BB_CODE = CC.BOARD_CODE
										AND COMM_TYPE = '$type' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	
	}

	function listMyCcommunityCommentTf($db, $writer_id, $type, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);

		$logical_num = ($total_cnt - $offset) + 1 ;

		$query = "set @rownum = ".$logical_num ."; ";
		
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, BB.COMM_NAME, BB.COMM_DOMAIN, CC.BOARD_NM, AA.COMM_NO, AA.BB_CODE, AA.BB_NO, AA.TITLE, AA.WRITER_NM, AA.REG_DATE,
										 CONCAT(BB.COMM_SEQ01,BB.COMM_SEQ02,BB.COMM_SEQ03) as SEQ
							FROM (
										SELECT COMM_NO, BB_CODE, BB_NO, TITLE, WRITER_NM, REG_DATE
											FROM CTBL_BOARD 
										 WHERE COMMENT_TF = 'Y' 
											 AND WRITER_ID = '$writer_id'
											 AND DEL_TF = 'N'
											 AND USE_TF = 'Y'
										 UNION
										SELECT B.COMM_NO, A.CATE_01 AS BB_CODE, A.CATE_02 AS BB_NO, B.TITLE, B.WRITER_NM, B.REG_DATE
											FROM CTBL_BOARD_COMMENT A, CTBL_BOARD B
										 WHERE A.COMMENT_TF = 'Y' 
											 AND A.WRITER_ID = '$writer_id'
											 AND A.CATE_01 = B.BB_CODE
											 AND A.CATE_02 = B.BB_NO
											 AND A.DEL_TF = 'N'
											 AND A.USE_TF = 'Y'
											 AND B.DEL_TF = 'N'
											 AND B.USE_TF = 'Y'
										 ) AA, CTBL_COMMUNITY BB, CTBL_BOARD_CONFIG CC
									WHERE AA.COMM_NO = BB.COMM_NO
										AND AA.BB_CODE = CC.BOARD_CODE
										AND COMM_TYPE = '$type'
									ORDER BY SEQ, AA.BB_CODE, AA.REG_DATE limit ".$offset.", ".$nRowCount;
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function totalMyCcommunityJoinTf($db, $mem_no) {

		$query = "SELECT COUNT(*) CNT 
								FROM CTBL_COMMUNITY A, CTBL_COMM_MEM B, CTBL_COMM_MEM_READ C
							 WHERE A.COMM_NO = B.COMM_NO
								 AND A.COMM_NO = C.COMM_NO
								 AND B.MEM_NO = C.MEM_NO
								 AND A.USE_TF ='Y'
								 AND A.DEL_TF = 'N'
								 AND B.MEM_TYPE <> '90'
								 AND B.MEM_NO = '$mem_no' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];
		return $record;
	}


	function listMyCcommunityJoinTf($db, $mem_no, $nPage, $nRowCount, $total_cnt) {

		$offset = $nRowCount*($nPage-1);
		$logical_num = ($total_cnt - $offset) + 1 ;
		$query = "set @rownum = ".$logical_num ."; ";
		mysql_query($query,$db);

		$query = "SELECT @rownum:= @rownum - 1  as rn, AA.COMM_NO, AA.COMM_NAME, AA.COMM_TYPE, AA.AREA_CODE, AA.COMM_DOMAIN, AA.READ_FLAG
								FROM (
											SELECT A.COMM_NO, A.COMM_NAME, A.COMM_TYPE, A.AREA_CODE, '1' AS SEQ_NUM, A.COMM_DOMAIN, C.READ_FLAG
												FROM CTBL_COMMUNITY A, CTBL_COMM_MEM  B, CTBL_COMM_MEM_READ C
											 WHERE A.COMM_NO = B.COMM_NO
												 AND A.COMM_NO = C.COMM_NO
												 AND B.MEM_NO = C.MEM_NO
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND B.MEM_NO = '$mem_no'
												 AND B.MEM_TYPE <> '90'
												 AND A.COMM_TYPE = 'SIDO'
											UNION
											SELECT A.COMM_NO, A.COMM_NAME, A.COMM_TYPE, A.AREA_CODE, '2' AS SEQ_NUM, A.COMM_DOMAIN, C.READ_FLAG
												FROM CTBL_COMMUNITY A, CTBL_COMM_MEM  B, CTBL_COMM_MEM_READ C
											 WHERE A.COMM_NO = B.COMM_NO
												 AND A.COMM_NO = C.COMM_NO
												 AND B.MEM_NO = C.MEM_NO
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND B.MEM_NO = '$mem_no'
												 AND B.MEM_TYPE <> '90'
												 AND A.COMM_TYPE = 'AREA'
											UNION
											SELECT A.COMM_NO, A.COMM_NAME, A.COMM_TYPE, A.AREA_CODE, '3' AS SEQ_NUM, A.COMM_DOMAIN, C.READ_FLAG
												FROM CTBL_COMMUNITY A, CTBL_COMM_MEM  B, CTBL_COMM_MEM_READ C
											 WHERE A.COMM_NO = B.COMM_NO
												 AND A.COMM_NO = C.COMM_NO
												 AND B.MEM_NO = C.MEM_NO
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND B.MEM_NO = '$mem_no'
												 AND B.MEM_TYPE <> '90'
												 AND A.COMM_TYPE = 'COMT'
											UNION
											SELECT A.COMM_NO, A.COMM_NAME, A.COMM_TYPE, A.AREA_CODE, '4' AS SEQ_NUM, A.COMM_DOMAIN, C.READ_FLAG
												FROM CTBL_COMMUNITY A, CTBL_COMM_MEM  B, CTBL_COMM_MEM_READ C
											 WHERE A.COMM_NO = B.COMM_NO
												 AND A.COMM_NO = C.COMM_NO
												 AND B.MEM_NO = C.MEM_NO
												 AND A.USE_TF ='Y'
												 AND A.DEL_TF = 'N'
												 AND B.MEM_NO = '$mem_no'
												 AND B.MEM_TYPE <> '90'
												 AND A.COMM_TYPE = 'CLUB'
											) AA
											ORDER BY AA.SEQ_NUM, AA.AREA_CODE, AA.COMM_NO limit ".$offset.", ".$nRowCount;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function delCommunityJoinInfo($db, $comm_no, $mem_no) {
		
		$query ="DELETE FROM CTBL_COMM_MEM WHERE COMM_NO = '$comm_no' AND MEM_NO = '$mem_no' AND MEM_TYPE <> '90' ";

		//echo $query;
		
		if(!mysql_query($query,$db)) {
			return false;
		} else {
			return true;
		}
	}

	function getMyCcommunityReadFlag($db, $area_code, $mem_no) {

		$query = "SELECT A.COMM_DOMAIN, B.READ_FLAG
								FROM CTBL_COMMUNITY A, CTBL_COMM_MEM_READ B
							 WHERE A.COMM_NO = B.COMM_NO
								 AND A.AREA_CODE = '$area_code'
								 AND B.MEM_NO = '$mem_no' ";

		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function getCommonBannerList($db, $banner_lang, $banner_type) {

		$query = "SELECT * FROM TBL_BANNER WHERE BANNER_LANG = '$banner_lang' AND BANNER_TYPE = '$banner_type' AND DEL_TF ='N' AND USE_TF = 'Y' ORDER BY DISP_SEQ ASC ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
							
		return $record;
	}

	function chkBlockIP ($db, $ip) {

		$query ="SELECT COUNT(*) CNT FROM TBL_BLOCK_IP WHERE BLOCK_IP = '$ip' AND USE_TF = 'Y' ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$record  = $rows[0];

		if ($record == 0) {
			return false;
		} else {
			return true;
		}
	}

	if ($conn) {
		if (chkBlockIP($conn, $_SERVER['REMOTE_ADDR'])) {
?>
<meta http-equiv='Refresh' content='0; URL=http://www.daum.net'>
<?
			mysql_close($conn);
			exit;
		}
	}


	function insertNiceLog($db, $check_kind, $name, $jumin1, $jumin2, $data1, $data2, $flag) {
		
		$query="INSERT INTO TBL_NICE_LOG (CHECK_KIND, NAME, JUMIN1, JUMIN2, DATA1, DATA2, FLAG, CHKDATE) 
											 values ('$check_kind', '$name', '$jumin1', '$jumin2', '$data1', '$data2', '$flag', now()); ";
		
		//echo $query;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function isHoliday($db, $h_date) {
		
		$we = date("w", strtotime($h_date));

		$is_holiday = "false";

		if (($we == "0") || ($we == "6")) {
			//echo "we :".$we."<br>";
			$is_holiday = "true";

		} else {

			$query = "SELECT COUNT(H_DATE) AS CNT FROM TBL_HOLIDAY WHERE H_DATE = '$h_date' AND IS_HOLIDAY = 'Y' ";
			
			//echo "query :".$query."<br>";

			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
		
			if ($rows[0] > 0) {
				$is_holiday = "true";
			}
		}

		//echo "is_holiday : ".$is_holiday."<br>";

		return $is_holiday;

	}


	function insertUserLog($db, $user_type, $log_id, $log_ip, $task, $task_type) {
		
		$query="INSERT INTO TBL_USER_LOG (USER_TYPE, LOG_ID, LOG_IP, LOGIN_DATE, TASK, TASK_TYPE) 
															values ('$user_type', '$log_id', '$log_ip', now(), '$task', '$task_type'); ";
		
		//echo $query;
		//exit;

		if(!mysql_query($query,$db)) {
			return false;
			echo "<script>alert(\"[1]오류가 발생하였습니다 - ".mysql_errno().":".mysql_error()."\"); //history.go(-1);</script>";
			exit;
		} else {
			return true;
		}
	}

	function getOrganizationName($db, $group_cd) {
		
		$return_str = "";

		if (strlen($group_cd) >= 3) {

			$query = "SELECT GROUP_NAME
									FROM TBL_GROUP
								 WHERE GROUP_CD = '".substr($group_cd,0, 3)."' ";
			
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$return_str  = $rows[0];

		}

		if (strlen($group_cd) >= 6) {

			$query = "SELECT GROUP_NAME
									FROM TBL_GROUP
								 WHERE GROUP_CD = '".substr($group_cd,0, 6)."' ";
			
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$return_str  = $return_str." ".$rows[0];

		}

		if (strlen($group_cd) >= 9) {

			$query = "SELECT GROUP_NAME
									FROM TBL_GROUP
								 WHERE GROUP_CD = '".substr($group_cd,0, 9)."' ";
			
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$return_str  = $return_str." ".$rows[0];

		}

		if (strlen($group_cd) >= 12) {

			$query = "SELECT GROUP_NAME
									FROM TBL_GROUP
								 WHERE GROUP_CD = '".substr($group_cd,0, 12)."' ";
			
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$return_str  = $return_str." ".$rows[0];

		}

		if (strlen($group_cd) >= 15) {

			$query = "SELECT GROUP_NAME
									FROM TBL_GROUP
								 WHERE GROUP_CD = '".substr($group_cd,0, 15)."' ";
			
			$result = mysql_query($query,$db);
			$rows   = mysql_fetch_array($result);
			$return_str  = $return_str." ".$rows[0];

		}

		return $return_str;

	}

	function getOrganizationShortName($db, $group_cd) {
		
		$return_str = "";

		$query = "SELECT GROUP_NAME
								FROM TBL_GROUP
							 WHERE GROUP_CD = '$group_cd' ";
			
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$return_str  = $rows[0];


		return $return_str;

	}

	function getFbInfo($db, $b_code, $b_no) {

		$query = "SELECT * FROM TBL_BOARD WHERE  B_CODE = '$b_code' AND  B_NO = '$b_no' ";
		//echo $query;
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;
	}


	function makeSelectBoxWebzine($db,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT SEQ_NO, YYYY, MM, TITLE
								FROM TBL_WEBZINE WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		$query .= " ORDER BY YYYY DESC, MM DESC ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class='box01'  style='width:".$size."px;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_SEQ_NO			= Trim($row[0]);
			$RS_YYYY				= Trim($row[1]);
			$RS_MM					= Trim($row[2]);
			$RS_TITLE				= Trim($row[3]);

			if ($checkVal == $RS_SEQ_NO."^".$RS_YYYY."^".$RS_MM) {
				$tmp_str .= "<option value='".$RS_SEQ_NO."^".$RS_YYYY."^".$RS_MM."' selected>".$RS_TITLE."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_SEQ_NO."^".$RS_YYYY."^".$RS_MM."'>".$RS_TITLE."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function getGroupName($db,$group_no) {

		$query = "SELECT GROUP_NAME FROM TBL_ADMIN_GROUP  WHERE GROUP_NO = '$group_no' ";
		
		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$group_name  = $rows[0];
		}

		return $group_name;
	}

	function makeCompanySelectBoxWithObj($db, $obj, $cp_type, $checkVal) {

		$query = "SELECT CP_NO, CP_NM, CP_TYPE
								FROM TBL_COMPANY WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($cp_type <> "") {
			$query .= " AND CP_TYPE IN ('".$cp_type."','판매공급') ";
		}
		
		$query .= " ORDER BY CP_NM ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$obj."' class=\"txt\" >";

		$tmp_str .= "<option value=''> 전체 </option>";

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE				= Trim($row[0]);
			$RS_DCODE_NM		= Trim($row[1]);
			$RS_DCODE_TYPE	= Trim($row[2]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM." [".$RS_DCODE." ".$RS_DCODE_TYPE."]</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM." [".$RS_DCODE." ".$RS_DCODE_TYPE."]</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeEmpSelectBox($db,$objname,$size,$str,$val,$checkVal) {

		$query = " SELECT A.ADM_ID, B.DCODE_NM, A.ADM_NO, A.ADM_NAME
								 FROM TBL_ADMIN_INFO A left outer join TBL_CODE_DETAIL B on A.DEPT_CODE = B.DCODE AND B.PCODE = 'DEPT' 
								WHERE A.COM_CODE = '1'
									AND A.DEL_TF = 'N'
									AND A.USE_TF = 'Y'
								ORDER BY B.DCODE_SEQ_NO ASC, ADM_NAME ASC ";

		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class=\"box01\"  style='width:".$size."px;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		if (($str == "전체") || ($str == "선택")) {
			if ($checkVal == "NONE") {
				$tmp_str .= "<option value='NONE' selected>미지급</option>";
			} else {
				$tmp_str .= "<option value='NONE'>미지급</option>";
			}
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_ADM_ID		= Trim($row[0]);
			$RS_DEPT_CODE	= Trim($row[1]);
			$RS_ADM_NO		= Trim($row[2]);
			$RS_ADM_NAME	= Trim($row[3]);

			if ($checkVal == $RS_ADM_NO) {
				$tmp_str .= "<option value='".$RS_ADM_NO."' selected>".$RS_DEPT_CODE." : ".$RS_ADM_NAME." (".$RS_ADM_ID.")</option>";
			} else {
				$tmp_str .= "<option value='".$RS_ADM_NO."'>".$RS_DEPT_CODE." : ".$RS_ADM_NAME." (".$RS_ADM_ID.")</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function makeEmpSelectBox2022($db,$objname,$size,$str,$val,$checkVal) {

  // left outer join TBL_CODE_DETAIL B on A.DEPT_CODE = B.DCODE AND B.PCODE = 'DEPT' 

		$query = " SELECT A.ADM_ID, B.DCODE_NM, A.ADM_NO, A.ADM_NAME, D.HEADQUARTERS_CODE 
								 FROM TBL_ADMIN_INFO A 
											LEFT OUTER JOIN TBL_ORG D ON A.ADM_NO = D.ADM_NO AND D.YEAR = '2022'
											LEFT OUTER JOIN TBL_CODE_DETAIL H ON D.HEADQUARTERS_CODE = H.DCODE AND H.USE_TF = 'Y' AND H.DEL_TF ='N' AND H.PCODE = 'HEADQUARTERS_2022'
											LEFT OUTER JOIN TBL_CODE_DETAIL B ON D.DEPT_CODE = B.DCODE AND B.USE_TF = 'Y' AND B.DEL_TF ='N' AND B.PCODE = 'DEPT_2022'
								WHERE A.COM_CODE = '1'
									AND A.DEL_TF = 'N'
									AND A.USE_TF = 'Y'
								ORDER BY D.HEADQUARTERS_CODE ASC, D.DEPT_CODE ASC, ADM_NAME ASC ";

		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' id='".$objname."' class=\"box01\"  style='width:".$size."px;' >";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		if (($str == "전체") || ($str == "선택")) {
			if ($checkVal == "NONE") {
				$tmp_str .= "<option value='NONE' selected>미지급</option>";
			} else {
				$tmp_str .= "<option value='NONE'>미지급</option>";
			}
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_ADM_ID						= Trim($row[0]);
			$RS_DEPT_CODE					= Trim($row[1]);
			$RS_ADM_NO						= Trim($row[2]);
			$RS_ADM_NAME					= Trim($row[3]);
			$RS_HEADQUARTERS_CODE = Trim($row[4]);
			
			if ($RS_DEPT_CODE <> "") {
				if ($checkVal == $RS_ADM_NO) {
					$tmp_str .= "<option value='".$RS_ADM_NO."' selected>[".$RS_HEADQUARTERS_CODE."]".$RS_DEPT_CODE." : ".$RS_ADM_NAME." (".$RS_ADM_ID.")</option>";
				} else {
					$tmp_str .= "<option value='".$RS_ADM_NO."'>[".$RS_HEADQUARTERS_CODE."]".$RS_DEPT_CODE." : ".$RS_ADM_NAME." (".$RS_ADM_ID.")</option>";
				}
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}

	function getEmpInfo($db, $adm_no) {

		$query = " SELECT A.ADM_ID, B.DCODE_NM, A.ADM_NO, A.ADM_NAME FROM TBL_ADMIN_INFO A left outer join TBL_CODE_DETAIL B on A.DEPT_CODE = B.DCODE AND B.PCODE = 'DEPT' 
								WHERE A.COM_CODE = '1'
									AND A.DEL_TF = 'N'
									AND A.ADM_NO = '$adm_no' ";
		
		//echo $query;

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);

		if ($result <> "") {
			$RS_ADM_ID		= Trim($rows[0]);
			$RS_DCODE_NM	= Trim($rows[1]);
			$RS_ADM_NO		= Trim($rows[2]);
			$RS_ADM_NAME	= Trim($rows[3]);
		}

		$tmp_str = $RS_DCODE_NM." : ".$RS_ADM_NAME."(".$RS_ADM_ID.")";

		if ($RS_DCODE_NM == "") $tmp_str = "미지급"; 

		return $tmp_str;
	}

	function getHoliday($db) {

		$query = "SELECT H_DATE FROM TBL_HOLIDAY ";

		$result = mysql_query($query,$db);
		$record = array();
		

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

		function makeSelectBoxOnChange_customer($db,$pcode,$objname,$size,$str,$val,$checkVal) {

		$query = "SELECT DCODE, DCODE_NM
								FROM TBL_CODE_DETAIL WHERE 1 = 1 AND DEL_TF = 'N' AND USE_TF = 'Y' ";
		
		if ($pcode <> "") {
			$query .= " AND PCODE = '".$pcode."' ";
		}
		
		$query .= " ORDER BY DCODE_SEQ_NO ";
		
		$result = mysql_query($query,$db);
		$total  = mysql_affected_rows();

		$tmp_str = "<select name='".$objname."' onchange=\"js_".$objname."();\">";

		if ($str <> "") {
			$tmp_str .= "<option value='".$val."'>".$str."</option>";
		}

		for($i=0 ; $i< $total ; $i++) {
			mysql_data_seek($result,$i);
			$row     = mysql_fetch_array($result);
			
			$RS_DCODE			= Trim($row[0]);
			$RS_DCODE_NM	= Trim($row[1]);

			if ($checkVal == $RS_DCODE) {
				$tmp_str .= "<option value='".$RS_DCODE."' selected>".$RS_DCODE_NM."</option>";
			} else {
				$tmp_str .= "<option value='".$RS_DCODE."'>".$RS_DCODE_NM."</option>";
			}
		}
		$tmp_str .= "</select>";
		return $tmp_str;
	}
	

	function getProfileImages($db, $adm_id) {
		
		$query = "SELECT PROFILE FROM TBL_ADMIN_INFO WHERE ADM_ID = '$adm_id' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$return_str  = $rows[0];
		
		if ($return_str) { 
			$return_str = "<img src='/upload_data/profile/".$return_str."' alt='' />";
		}

		return $return_str;

	}

	function getAdminName($db, $adm_no) {
		
		$query = "SELECT ADM_NAME FROM TBL_ADMIN_INFO WHERE ADM_NO = '$adm_no' ";

		$result = mysql_query($query,$db);
		$rows   = mysql_fetch_array($result);
		$return_str  = $rows[0];
		
		return $return_str;

	}

	function getAdminAllInfo($db, $adm_no) {
		
		$query = "SELECT * FROM TBL_ADMIN_INFO WHERE ADM_NO = '$adm_no' ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}

	function getBrochureFile($db) {
		
		$query = "SELECT FILE_NO FROM TBL_BROCHURE_FILE WHERE DEL_TF = 'N' AND USE_TF = 'Y' ORDER BY FILE_NO DESC LIMIT 1 ";

		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}
		return $record;

	}
	
	function selectEqnoMaxSearch ($db, $eq_type, $eq_hgubun, $eq_rengubun) {
		$query = " 	SELECT 
						 MAX( RIGHT( EQ_CD , 4 ) ) + 1 AS EQ_CD
					FROM TBL_EQUIPMENT 
					WHERE 1 = 1 					
					AND EQ_TYPE = '$eq_type'" ;
		 		
					if ( $eq_hgubun == "H" ) {						
						$query .= " AND EQ_CD NOT LIKE '%-H%' AND EQ_CD NOT LIKE '%-M%' ";						
					} else {
						if ( $eq_rengubun == "K" ) { 
							$query .= " AND EQ_CD LIKE '%-H%' ";
						}
						
						if ( $eq_rengubun == "M" ) { 
							$query .= " AND EQ_CD LIKE '%-M%' ";
						}
					}	 
		// echo "query : " .$query. "<br />"; 
		$result = mysql_query($query,$db);
		$record = array();

		if ($result <> "") {
			for($i=0;$i < mysql_num_rows($result);$i++) {
				$record[$i] = sql_result_array($result,$i);
			}
		}  
		 
		return $record;	
	}

//승인상태 함수
	function selectVaState($db, $va_state){
		switch ($va_state){
			case "0" : $record = "신청";
			break;
			case "1" : $record = "승인";
			break;
			case "2" : $record = "보류";
			break;
			case "3" : $record = "반려";
			break;
			case "4" : $record = "미결";
			break;
		}
		return $record;	
	}

//승인상태 함수 모바일
	function selectVaStateMobile($db, $va_state){
		switch ($va_state){
			case "0" : $record = "신청";
			break;
			case "1" : $record = "완료";
			break;
			case "2" : $record = "보류";
			break;
			case "3" : $record = "반려";
			break;
			case "4" : $record = "미결";
			break;
		}
		return $record;	
	}

//승인상태 함수 모바일 css class
	function selectVaStateMobileClass($db, $va_state){
		switch ($va_state){
			case "0" : $record = "normal-04";
			break;
			case "1" : $record = "accent-03";
			break;
			case "2" : $record = "accent-04";
			break;
			case "3" : $record = "accent-01";
			break;
			case "4" : $record = "accent-02";
			break;
		}
		return $record;	
	}



//연차구분 함수
	function selectVaType($db, $va_type){
		switch ($va_type){
			case "1" : $record = "오전반차";
			break;
			case "11" : $record = "오후반차";
			break;
			case "2" : $record = "연차";
			break;
			case "5" : $record = "스마트데이";
			break;
			case "6" : $record = "대체휴무";
			break;
			case "3" : $record = "하계,동계휴가";
			break;
			case "9" : $record = "예비군,민방위훈련";
			break;
			case "8" : $record = "경조휴가";
			break;
			case "7" : $record = "미사용반차";
			break;
			case "4" : $record = "미사용연차";
			break;
			case "10" : $record = "리플레쉬휴가";
			break;
			case "19" : $record = "백신휴가";
			break;
			case "12" : $record = "기타";
			break;
			case "13" : $record = "스마트반차";
			break;
		}
		return $record;	
	}

//승인상태 함수
	function selectCommuteTime($db, $commute_time){
		switch ($commute_time){
			case "1" : $record = "08:00 ~ 17:00";
			break;
			case "2" : $record = "08:30 ~ 17:30";
			break;
			case "3" : $record = "09:00 ~ 18:00";
			break;
			case "4" : $record = "09:30 ~ 18:30";
			break;
			case "5" : $record = "10:00 ~ 19:00";
			break;
		}
		return $record;	
	}
?>
