<?
//�̹��� /upload/$image_path/��/��/ �� ����
//������� ��������

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

if($_FILES['imgfile']['name']!=""){  						// �Է��� ÷��ȭ���� �ִ�.
	if (strcmp ($file_ext_type,"html")==0 || 	//���� ���� ���� ȭ�ϵ�(��ũ��Ʈ ����ȭ��)
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
				window.alert('Ȯ���ڰ� html,htm,php,php3,inc,pl,cgi,cgi,asp,phtml,������ ȭ���� �ø��� �����ϴ�.');
        self.close();
			</script>
	    ");
	    exit;
		}

		$uploadfile = $upload_dir;
		
		//���ϸ� �ߺ�üũ
		
		$check_dupe_file_name=confirmFname($_FILES['imgfile']['name'],$upload_dir);
	
		$uploadfile = $upload_dir.$check_dupe_file_name;
		
		if($check_dupe_file_name != "dupe_file_err"){//�ߺ����� �����Ӽ����ϸ�
			
			if (move_uploaded_file($_FILES['imgfile']['tmp_name'], $uploadfile)) {
				print ("
					<script>
		        try{
							opener.txtFileName.value='http://humanoid.kist.re.kr/upload/".$image_path."/images/".$current_year."/".$current_month."/".$check_dupe_file_name."';		
		        }catch(e) {					// ���⿡�� �ٸ� ������ ó���մϴ�.
						}	
		        self.close();
//						window.alert('���������� ���ε� �Ǿ����ϴ�.');
					</script>
			  ");
		
			} else {
			
				print ("
					<script>
						window.alert('���� ���ε� ����');
		        self.close();
					</script>
				");
			}	
			
		}	else	{//�ߺ����� ������ ������� �õ��Ŀ��� �����ϸ� ������� ������ ������.. ^^;
			print ("
				<script>
					window.alert('�ߺ��� ���ϸ��� �ֽ��ϴ�. �ٸ� ���ϸ����� ���ε� ���ּ���');
		      self.close();
				</script>
			 ");		
		}
	}


/////////////////////���ϸ� �ߺ�ó���Լ�/////////////////////////				
function confirmFname($Fname,$img) {  #���ϸ�,������ �Ű�����
	
	$t_MapPath=$img; #����� ���丮
	$attach_file = explode(".",$Fname);   #���ϸ� �и�
	$strName = $attach_file[0];       #���ϸ�
	$strExt =$attach_file[1];         #Ȯ����
	$bExist = True ;   #�ϴ� ������ �ִٰ� �����ϴ� �Ҹ� ����
	$strFileName = $t_MapPath . $strName . "." . $strExt;  #��ü ���
	$FileName = $strName . "." . $strExt;
	$countFileName = 0;
	$i=1;
	If (file_exists($strFileName)) {
		while ($bExist and $i <= 101)  {                   #�켱 �ִٰ� ����, 100�������� üũ
	              
			If (file_exists($strFileName)) {
				$countFileName = $countFileName + 1 ;
	      $FileName1 = $strName . "_" . $countFileName . "." . $strExt;
	      $strFileName  = $t_MapPath . $FileName1;
	                            
	      if($i==100){//99���ص� ���� ������ ������ 1970�� ���� ��������� �� �ο�
					$FileName1=$strName . "_" . time (  ) . "." . $strExt; 
	        $strFileName  = $t_MapPath . $FileName1;
				}
	      if($i==101){//�ʴ��� �ο��ĵ� ���� ���������� ���� ^^;
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
/////////////////////���ϸ� �ߺ�ó���Լ���/////////////////////////
?>



