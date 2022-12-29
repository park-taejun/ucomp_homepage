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

	if ($BoardId == "B2") {
		$_menu_str = "신문보도";
		$_path_str = "B2_files";
		$_image_path_str = "B2_images";
	}

?>