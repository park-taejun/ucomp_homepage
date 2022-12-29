<?
# =============================================================================
# File Name    : config.php
# Modlue       : 
# Writer       : Park Chan Ho 
# Create Date  : 2014.04.25
# Modify Date  : 
#	Copyright : Copyright @ucomp Corp. All Rights Reserved.
# =============================================================================

	//if ($_SERVER['SERVER_NAME'] <> "www.lghausysinstory.com") {
	//<meta http-equiv='Refresh' content='0; URL=http://www.lghausysinstory.com'>
		//exit;
	//}
// 상수 정의

// 입력값 검사 상수
define('_ALPHAUPPER_', 1); // 영대문자
define('_ALPHALOWER_', 2); // 영소문자
define('_ALPHABETIC_', 4); // 영대,소문자
define('_NUMERIC_', 8); // 숫자
define('_HANGUL_', 16); // 한글
define('_SPACE_', 32); // 공백
define('_SPECIAL_', 64); // 특수문자

#====================================================================
# SITE_INFO
#====================================================================

	//$test_url = "_new";

	# 사이트 사용 언어 셋
	Global  $g_charset; 
	$g_charset = "utf-8"; 

	# 사이트 Tile
	Global  $g_site_no; 
	$g_site_no = "1"; 

	Global  $g_base_dir;
	$g_base_dir = ""; 

	# 사이트 Tile
	Global  $g_title_name; 
	$g_title_name = "U: 유컴패니온 인트라넷"; 

	# 사이트 Tile
	Global  $g_title; 
	$g_title = "U: 유컴패니온 인트라넷"; 

	# 사이트 Tile
	Global  $g_front_title; 
	$g_front_title = "U: 유컴패니온 인트라넷"; 
	
	# 사이트 절대 경로
	Global  $g_physical_path; 
											
	$g_physical_path = "/home/httpd/ucom/"; 

	# 사이트 절대 경로
	Global  $g_old_data_path; 
	$g_old_data_path = "/home/httpd/ucom/upload_data/"; 

	//echo $g_physical_path;

	Global  $g_site_domain;
	$g_site_domain	= "www.ucomp.co.kr";

	Global  $g_site_url;
	$g_site_url	= "http://www.ucomp.co.kr";

	//재가입기간 설정
	Global  $g_site_re_enter_period;
	$g_site_re_enter_period	= 30;

	//글쓰기 시간 초단위로 설정
	Global  $g_site_re_write;
	//테스트 기간동안 10초 하자
	$g_site_re_write	= 60;
	//$g_site_re_write	= 10;

	//닉네임 변경 일단위로 설정
	Global  $g_site_nick_period;
	$g_site_nick_period	= 1;

	Global  $g_admin_email_01;
	$g_admin_email_01	= "park@ucomp.co.kr";

	Global  $g_admin_email_02;
	$g_admin_email_02	= "park@ucomp.co.kr";

	Global  $g_admin_email_03;
	$g_admin_email_03	= "park@ucomp.co.kr";

	//모바일로 접속했는지 여부
	$mobile_is_all=false;
	if(preg_match('/(iPhone|Android|Opera Mini|SymbianOS|Windows CE|BlackBerry|Nokia|SonyEricsson|webOS|PalmOS)/i', $_SERVER['HTTP_USER_AGENT'])) {
		$mobile_is_all=true;
	}
	
	// 회원 아이디 닉 금지어 
	$g_prohibit_id = "admin,administrator,system,운영자,어드민,주인장,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,관리자,root,루트,su,guest,방문객";
	
	// 회원 가입시 권한
	Global  $g_register_level;
	$g_register_level = 2;

	$urlencode = urlencode($_SERVER[REQUEST_URI]);

	# 리뉴얼 플래그
	$g_renewal = "1"; 
?>