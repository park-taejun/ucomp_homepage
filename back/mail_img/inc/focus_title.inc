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

	if ($BoardId == "F1") {
		$_menu_str = "FOCUS";
		$_path_str = "F1_files";
		$_image_path_str = "F1_images";
	}

?>