<?

	include "../admin_session_check.inc";
	include "../inc/global_init.inc";
	include "../inc/other_title.inc"; 

	# file���ε�
	
	$path = "../".$_image_path_str."/";

	$filename1 = trim($filename1);
	$filename2 = trim($filename2);
	$filename3 = trim($filename3);
	$filename4 = trim($filename4);
	$filename5 = trim($filename5);

	if (!file_exists($path))
		mkdir($path, 0777);

	if (strlen($filename1) > 4) {
		$filename1_ext = substr(strrchr($filename1_name, "."), 1);
	
		if ($filename1_ext == "php" || $filename1_ext == "html" || $filename1_ext == "php3")
		{
			echo "<script>
				window.alert('Ȯ���ڰ� php, html, php3�� ���ε��Ҽ��� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		$filename1_strtmp = $path."/".$filename1_name;	


		if (file_exists($filename1_strtmp)) {
			echo "<script>
    			window.alert('$filename1_name �� ���� ���丮�� �����մϴ�..');
				history.go(-1);
			</script>";
			exit;
		}

		if (!copy($filename1, $filename1_strtmp))
		{
			echo "<script>
				window.alert('$filename1_name �� ���ε��� �� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		# file���ε� ��
	}

	if (strlen($filename2) > 4) {
		$filename2_ext = substr(strrchr($filename2_name, "."), 1);
	
		if ($filename2_ext == "php" || $filename2_ext == "html" || $filename2_ext == "php3")
		{
			echo "<script>
				window.alert('Ȯ���ڰ� php, html, php3�� ���ε��Ҽ��� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		$filename2_strtmp = $path."/".$filename2_name;	


		if (file_exists($filename2_strtmp)) {
			echo "<script>
    			window.alert('$filename2_name �� ���� ���丮�� �����մϴ�..');
				history.go(-1);
			</script>";
			exit;
		}

		if (!copy($filename2, $filename2_strtmp))
		{
			echo "<script>
				window.alert('$filename2_name �� ���ε��� �� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		# file���ε� ��
	}

	if (strlen($filename3) > 4) {
		$filename3_ext = substr(strrchr($filename3_name, "."), 1);
	
		if ($filename3_ext == "php" || $filename3_ext == "html" || $filename3_ext == "php3")
		{
			echo "<script>
				window.alert('Ȯ���ڰ� php, html, php3�� ���ε��Ҽ��� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		$filename3_strtmp = $path."/".$filename3_name;	


		if (file_exists($filename3_strtmp)) {
			echo "<script>
    			window.alert('$filename3_name �� ���� ���丮�� �����մϴ�..');
				history.go(-1);
			</script>";
			exit;
		}

		if (!copy($filename3, $filename3_strtmp))
		{
			echo "<script>
				window.alert('$filename3_name �� ���ε��� �� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		# file���ε� ��
	}

	if (strlen($filename4) > 4) {
		$filename4_ext = substr(strrchr($filename4_name, "."), 1);
	
		if ($filename4_ext == "php" || $filename4_ext == "html" || $filename4_ext == "php3")
		{
			echo "<script>
				window.alert('Ȯ���ڰ� php, html, php3�� ���ε��Ҽ��� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		$filename4_strtmp = $path."/".$filename4_name;	


		if (file_exists($filename4_strtmp)) {
			echo "<script>
    			window.alert('$filename4_name �� ���� ���丮�� �����մϴ�..');
				history.go(-1);
			</script>";
			exit;
		}

		if (!copy($filename4, $filename4_strtmp))
		{
			echo "<script>
				window.alert('$filename4_name �� ���ε��� �� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		# file���ε� ��
	}

	if (strlen($filename5) > 4) {
		$filename5_ext = substr(strrchr($filename5_name, "."), 1);
	
		if ($filename5_ext == "php" || $filename5_ext == "html" || $filename5_ext == "php3")
		{
			echo "<script>
				window.alert('Ȯ���ڰ� php, html, php3�� ���ε��Ҽ��� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		$filename5_strtmp = $path."/".$filename5_name;	


		if (file_exists($filename5_strtmp)) {
			echo "<script>
    			window.alert('$filename5_name �� ���� ���丮�� �����մϴ�..');
				history.go(-1);
			</script>";
			exit;
		}

		if (!copy($filename5, $filename5_strtmp))
		{
			echo "<script>
				window.alert('$filename5_name �� ���ε��� �� �����ϴ�.');
				history.go(-1);
				</script>";
			exit;
		}

		# file���ε� ��
	}
	
	echo "<script language=\"javascript\">\n
		alert('���ε� �Ǿ����ϴ�.');
		parent.frames[3].frm_file.reset();
		</script>";
	exit;

?>