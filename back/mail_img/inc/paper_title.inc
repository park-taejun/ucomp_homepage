<?
	if ($_mode == "in") {
		$_mode_str = "등록";
	} else if ($_mode == "list") {
		$_mode_str = "리스트";
	} else if ($_mode == "view") {
		$_mode_str = "수정";
	} else {
		$_mode_str = "";
	}

	if ($code_id == "K1") {
		$_menu_str = "Call for Paper";
	} else if ($code_id == "A2") {
		$_menu_str = "입금 및 배송확인";
	} else if ($code_id == "O3") {
		$_menu_str = "Notice";
	} else if ($code_id == "O4") {
		$_menu_str = "Star News";
	} else if ($code_id == "O5") {
		$_menu_str = "Movie News";
	} else if ($code_id == "O6") {
		$_menu_str = "";
	} else {
		$_menu_str = "";
	}

?>