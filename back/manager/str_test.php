<?
	require "../_classes/com/util/Util.php";

	function makeSpace($str, $length) {
		
		if (strlen($str) > $length) {
			$str = left($str, $length);
		}

		$space = "^";
		$ret = "";
		$temp = "";

		for ($j = 0; $j < ($length - strlen($str)) ; $j++) {
			$temp = $temp.$space;
		}
		
		$ret = $str.$temp;


		return $ret;
	}

	$temp = "1234567890";

	echo makeSpace($temp, 5);
?>