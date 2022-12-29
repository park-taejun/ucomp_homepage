시도당











INSERT INTO CTBL_MENU (COMM_NO, MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_FLAG, MENU_RIGHT, MENU_IMG, MENU_IMG_OVER, ADMIN_TF, DEFAULT_TF, USE_TF, DEL_TF) VALUES
(1, 1, '01', '소개', '#', '04', '00', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 2, '02', '소식', '#', '05', '00', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 3, '03', '참여', '#', '06', '00', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 4, '0101', '소개', '/branch/intro/intro.php', '04', '01', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 5, '0102', '사람들', '/branch/intro/people_list.php', '04', '02', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 6, '0103', '지방의원 소개', '/branch/intro/member_list.php', '04', '03', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 7, '0104', '연락처', '/branch/intro/contact.php', '04', '04', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 8, '0105', '찾아오시는 길', '/branch/intro/map.php', '04', '05', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 9, '0201', '공지사항', '/branch/board/board_list.php?bb_code=NOTICE', '05', '01', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 10, '0202', '주요일정', '/branch/news/schedule.php', '05', '02', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 11, '0203', '자료실', '/branch/board/board_list.php?bb_code=PDS', '05', '03', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 12, '0301', '당원게시판', '/branch/board/board_list.php?bb_code=PARTY', '06', '01', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 13, '0302', '자유게시판', '/branch/board/board_list.php?bb_code=FREE', '06', '02', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 14, '0303', '동영상', '/branch/media/tv_list.php', '06', '03', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 15, '0304', '포토', '/branch/media/photo_list.php', '06', '04', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 16, '04', '게시판 관리', '#', '07', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 17, '0401', '게시판 생성', '/branch/board/board_config_list.php', '07', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 18, '05', '메인,배너 관리', '#', '02', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 19, '0501', '메인 비주얼 관리', '/branch/main/banner_list.php?banner_type=MAIN', '02', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 20, '0502', '배너 관리', '/branch/main/banner_list.php?banner_type=BANNER', '02', '02', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 21, '06', '커뮤니티 관리', '#', '01', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 22, '0601', '커뮤니티 설정 관리', '/branch/community/comm_write.php', '01', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 23, '07', '회원 관리', '#', '03', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 24, '0701', '회원 관리', '/branch/member/member_list.php', '03', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N');


지역위원회

INSERT INTO CTBL_MENU (COMM_NO, MENU_NO, MENU_CD, MENU_NAME, MENU_URL, MENU_SEQ01, MENU_SEQ02, MENU_SEQ03, MENU_FLAG, MENU_RIGHT, MENU_IMG, MENU_IMG_OVER, ADMIN_TF, DEFAULT_TF, USE_TF, DEL_TF) VALUES
(1, 1, '01', '홈페이지 관리', '#', '04', '00', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 4, '0101', '소개', '/branch/intro/intro.php', '04', '01', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 5, '0102', '공지사항', '/branch/board/board_list.php?bb_code=NOTICE', '04', '02', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 6, '0103', '당원게시판', '/branch/board/board_list.php?bb_code=PARTY', '04', '03', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 7, '0104', '동영상', '/branch/media/tv_list.php', '04', '04', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 8, '0105', '포토', '/branch/media/photo_list.php', '04', '05', '00', 'Y', '', '', '', 'N', 'Y', 'Y', 'N'),
(1, 9, '02', '게시판 관리', '#', '05', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 10, '0201', '게시판 생성', '/branch/board/board_config_list.php', '05', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 11, '03', '메인,배너 관리', '#', '02', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 12, '0301', '메인 비주얼 관리', '/branch/main/banner_list.php?banner_type=MAIN', '02', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 13, '0302', '배너 관리', '/branch/main/banner_list.php?banner_type=BANNER', '02', '02', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 14, '04', '커뮤니티 관리', '#', '01', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 15, '0401', '커뮤니티 설정 관리', '/branch/community/comm_write.php', '01', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 16, '05', '회원 관리', '#', '03', '00', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N'),
(1, 17, '0501', '회원 관리', '/branch/member/member_list.php', '03', '01', '00', 'Y', '', '', '', 'Y', 'Y', 'Y', 'N');


