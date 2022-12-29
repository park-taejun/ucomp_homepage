<?
//이미지 /upload/$image_path/년/월/ 에 저장
//없을경우 폴더생성

//$image_path= "K1";

$current_year		= date("Y");
$current_month		= date("m");

$upload_dir = $DOCUMENT_ROOT."/upload/".$image_path ; 

if( !is_dir ($upload_dir) ){
	mkdir("$upload_dir",0777); 
}

$upload_dir = $DOCUMENT_ROOT."/upload/".$image_path."/images/"; 

if( !is_dir ($upload_dir) ){
	mkdir("$upload_dir",0777); 
}

$upload_dir = $DOCUMENT_ROOT."/upload/".$image_path."/images/".$current_year ; 

if( !is_dir ($upload_dir) ){
	mkdir("$upload_dir",0777); 
}

$upload_dir = $DOCUMENT_ROOT."/upload/".$image_path."/images/".$current_year."/".$current_month ; 

if( !is_dir ($upload_dir) ){
	mkdir("$upload_dir",0777); 
}

$upload_dir = $DOCUMENT_ROOT."/upload/".$image_path."/images/".$current_year."/".$current_month."/" ; 

//echo $_FILES['imgfile']['type']."".$imgfile_type;

$file_ext_type_tmp=explode (".", $_FILES['imgfile']['name']);
$file_ext_type= strtolower ($file_ext_type_tmp[1]) ;

//echo "<br>".$file_ext_type."<br>";

echo $file_ext_type_tmp;

if($_FILES['imgfile']['name']!=""){  						// 입력한 첨부화일이 있다.
	if (strcmp ($file_ext_type,"html")==0 || 	//저장 하지 않을 화일들(스크립트 포함화일)
			strcmp ($file_ext_type,"htm")==0 ||
   		strcmp ($file_ext_type,"php")==0 ||
	   	strcmp ($file_ext_type,"php3")==0 ||
	   	strcmp ($file_ext_type,"php4")==0 ||
			strcmp ($file_ext_type,"inc")==0 ||
   		strcmp ($file_ext_type,"pl")==0 ||
	   	strcmp ($file_ext_type,"cgi")==0 ||
			strcmp ($file_ext_type,"asp")==0 ||
			strcmp ($file_ext_type,"")==0 ||     		
	   	strcmp ($file_ext_type,"phtml") ==0 ) {
			print("
      <script>
				window.alert('확장자가 html,htm,php,php3,inc,pl,cgi,cgi,asp,phtml,공백인 화일은 올릴수 없습니다.');
        self.close();
			</script>
	    ");
	    exit;
		}

		$uploadfile = $upload_dir;
		
		//파일명 중복체크
		
		$check_dupe_file_name=confirmFname($_FILES['imgfile']['name'],$upload_dir);
	
		$uploadfile = $upload_dir.$check_dupe_file_name;
		
		if($check_dupe_file_name != "dupe_file_err"){//중복파일 리네임성공하면
			
			if (move_uploaded_file($_FILES['imgfile']['tmp_name'], $uploadfile)) {
				print ("
					<script>
		        try{
							opener.txtFileName.value='http://humanoid.kist.re.kr/upload/".$image_path."/images/".$current_year."/".$current_month."/".$check_dupe_file_name."';		
		        }catch(e) {					// 여기에서 다른 오류를 처리합니다.
						}	
		        self.close();
//						window.alert('성공적으로 업로드 되었습니다.');
					</script>
			  ");
		
			} else {
			
				print ("
					<script>
						window.alert('파일 업로드 실패');
		        self.close();
					</script>
				");
			}	
			
		}	else	{//중복파일 리네임 여러방법 시도후에도 실패하면 여기까지 올일이 있을까.. ^^;
			print ("
				<script>
					window.alert('중복된 파일명이 있습니다. 다른 파일명으로 업로드 해주세요');
		      self.close();
				</script>
			 ");		
		}
	}


/////////////////////파일명 중복처리함수/////////////////////////				
function confirmFname($Fname,$img) {  #파일명,폴더명 매개변수
	
	$t_MapPath=$img; #저장될 디렉토리
	$attach_file = explode(".",$Fname);   #파일명 분리
	$strName = $attach_file[0];       #파일명
	$strExt =$attach_file[1];         #확장자
	$bExist = True ;   #일단 파일이 있다고 가정하는 불린 변수
	$strFileName = $t_MapPath . $strName . "." . $strExt;  #전체 경로
	$FileName = $strName . "." . $strExt;
	$countFileName = 0;
	$i=1;
	If (file_exists($strFileName)) {
		while ($bExist and $i <= 101)  {                   #우선 있다고 생각, 100번까지만 체크
	              
			If (file_exists($strFileName)) {
				$countFileName = $countFileName + 1 ;
	      $FileName1 = $strName . "_" . $countFileName . "." . $strExt;
	      $strFileName  = $t_MapPath . $FileName1;
	                            
	      if($i==100){//99번해도 같은 파일이 있으면 1970년 이후 현재까지의 초 부여
					$FileName1=$strName . "_" . time (  ) . "." . $strExt; 
	        $strFileName  = $t_MapPath . $FileName1;
				}
	      if($i==101){//초단위 부여후도 같은 파일있으면 포기 ^^;
					$FileName1="dupe_file_err";
				}
	                            
			} else {
				$bExist = False;
	    }
			$i++;
		}        
		return $FileName1;
	}else{
		return $FileName;
	}
}
/////////////////////파일명 중복처리함수끝/////////////////////////
?>



