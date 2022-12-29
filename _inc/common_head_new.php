<?
	require "./_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	require "./_common/config.php";
	require "./_classes/com/util/Util.php";
	require "./_classes/com/util/AES2.php";
	require "./_classes/com/etc/etc.php";
	require "./_classes/biz/webzine/webzine.php";
	require "./_classes/biz/board/board.php";

	// 가장 최근 웹진을 불러 옵니다. 페이지에서 사용 될 듯 합니다.
	$arr_webzine = selectTopWebzine($conn);
	
	if (sizeof($arr_webzine) > 0) {
		$THIS_SEQ_NO						= trim($arr_webzine[0]["SEQ_NO"]);
		$THIS_YYYY							= trim($arr_webzine[0]["YYYY"]);
		$THIS_MM								= trim($arr_webzine[0]["MM"]);
		$THIS_PUB_DATE					= trim($arr_webzine[0]["PUB_DATE"]);
		$THIS_VOL_NO						= trim($arr_webzine[0]["VOL_NO"]);
		$THIS_TITLE							= SetStringFromDB($arr_webzine[0]["TITLE"]);
		$THIS_MEMO							= SetStringFromDB($arr_webzine[0]["MEMO"]);
		$THIS_MAIN_IMAGE01			= trim($arr_webzine[0]["MAIN_IMAGE01"]);
		$THIS_MAIN_IMAGE02			= trim($arr_webzine[0]["MAIN_IMAGE02"]);
		$THIS_MAIN_IMAGE03 			= trim($arr_webzine[0]["MAIN_IMAGE03"]);
		$THIS_PDF_IMAGE					= trim($arr_webzine[0]["PDF_IMAGE"]);
		$THIS_PDF_FILE					= trim($arr_webzine[0]["PDF_FILE"]);
		$THIS_HIT_CNT						= trim($arr_webzine[0]["HIT_CNT"]);
		$THIS_USE_TF						= trim($arr_webzine[0]["USE_TF"]);
	}
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-91821498-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-91821498-1');
</script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<meta name="keywords" content="Digital Magazine of LGHausys, LG하우시스 인스토리" />
<title>LG 하우시스 인 스토리</title>
<link type="text/css" rel="stylesheet" href="/css/reset.css" />
<script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="/js/slick.js"></script>
<script type="text/javascript" src="/js/masonry.pkgd.v3.min.js"></script>
<script type="text/javascript" src="/js/imagesloaded.pkgd.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
</head>
