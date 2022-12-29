<?
	require "./_classes/com/db/DBUtil.php";

	$conn = db_connection("w");

	require "./_common/config.php";
	require "./_classes/com/util/Util.php";
	require "./_classes/com/etc/etc.php";
	require "./_classes/biz/webzine/webzine.php";

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

	if ($g_renewal == "1") {
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
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
<?
	} else {
?>
<!DOCTYPE html>
<html xml:lang="ko" lang="ko">
<head>
<title>LG 하우시스 인 스토리</title>
<meta content="Digital Magazine of LGHausys, LG하우시스 인 스토리" name="keywords" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge, Chrome=1" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no" />
<link type="text/css" rel="stylesheet" href="css_ob/reset.css" />

<style type="text/css">

/* PC 메인 비주얼*/
.maincontents {width:100%; background:url('/upload_data/webzine/<?=$THIS_MAIN_IMAGE01?>') no-repeat 50% 0}
.maincontents .mainvisual {position:relative; width:1000px; height:536px; margin:0 auto; padding:85px 0 0 0; background:url('/upload_data/webzine/<?=$THIS_MAIN_IMAGE03?>') no-repeat 100% 100%}

/* tablet 메인 비주얼*/
.tablet .maincontents {width:100%; background:url('/upload_data/webzine/<?=$THIS_MAIN_IMAGE02?>') no-repeat 50% 0; background-size:100% 596px}
.tablet .maincontents .mainvisual {width:90%; height:486px; padding:50px 0 0 0; background:url('/upload_data/webzine/<?=$THIS_MAIN_IMAGE03?>') no-repeat 50% 200px; background-size:450px auto}

/* mobile 메인 비주얼*/
.mobile .maincontents {width:100%; background:url('/upload_data/webzine/<?=$THIS_MAIN_IMAGE02?>') no-repeat 50% 0; background-size:100% 450px}
.mobile .maincontents .mainvisual {width:90%; height:400px; padding:30px 0 0 0; background:url('/upload_data/webzine/<?=$THIS_MAIN_IMAGE03?>') no-repeat 50% 220px; background-size:250px auto}

</style>

<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
</head>
<?
	}
?>