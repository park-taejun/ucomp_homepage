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

	if ($BoardId == "B1") {
		$_menu_str = "NEWS";
		$_path_str = "B1_files";
		$_image_path_str = "B1_images";
	}

	if ($BoardId == "B2") {
		$_menu_str = "신문보도";
		$_path_str = "B2_files";
		$_image_path_str = "B2_images";
	}

	if ($BoardId == "B3") {
		$_menu_str = "방송보도";
		$_path_str = "B3_files";
		$_image_path_str = "B3_images";
	}

	if ($BoardId == "B4") {
		$_menu_str = "동영상";
		$_path_str = "B4_files";
		$_image_path_str = "B4_images";
	}

	if ($BoardId == "B5") {
		$_menu_str = "사진";
		$_path_str = "B5_files";
		$_image_path_str = "B5_images";
	}

	if ($BoardId == "F1") {
		$_menu_str = "FOCUS";
		$_path_str = "F1_files";
		$_image_path_str = "F1_images";
	}


?>