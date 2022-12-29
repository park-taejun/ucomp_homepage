<?
	header('Content-Type: text/html; charset=UTF-8');
 //

	function postFormData($api_url , $data){
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_POST, true);

		$response = curl_exec($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $response;
	}

/*
	$url = "https://moffice.lgi.co.kr/DevEMS/ExAuth/VerifyToken";
	
	echo "dddd";

	$tempStr = postFormData($url, "AuthToken=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJFbXBJRCI6Ijc1NzEyIiwiUmVxdWVzdFRpbWUiOiIyMDE3MDcyNTAzMTkxMiIsIkV4cGlyZVRpbWUiOiIyMDE3MDcyNTAzMjIxMiIsIkxvY2FsZSI6ImtvIn0.p4horyuvjCcN61QoAICgmJLkHpwuYBLrvCO9PBNofQk");

	if(isset($tempStr)){
		$json_data = json_decode($tempStr);
	}
*/

?>
<script type="text/javascript" src="js/jquery-1.11.2.min.js"></script>
<script>

$(document).ready(function(){

	$.ajax({
		url : 'https://moffice.lgi.co.kr/DevEMS/ExAuth/VerifyToken',
		headers : {'Content-Type : application/JSON'},
		type: 'POST',
		data:{AuthToken:"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJFbXBJRCI6Ijc1NzEyIiwiUmVxdWVzdFRpbWUiOiIyMDE3MDcyNTAzMTkxMiIsIkV4cGlyZVRpbWUiOiIyMDE3MDcyNTAzMjIxMiIsIkxvY2FsZSI6ImtvIn0.p4horyuvjCcN61QoAICgmJLkHpwuYBLrvCO9PBNofQk"},
		dataType:"jsonp",
		crossDomain:true,
		success : function(data){
		//호출 성공하면 작성할 내용
			if(data.documents.length != 0 ){ // 값이 있으면

			}
		}, 
		error:function(request,status,error){
		    alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		}
	}).done(function(data){console.log(data);});	

/*
	$.ajax({
		 url :"https://moffice.lgi.co.kr/DevEMS/ExAuth/VerifyToken"
		,type:"post"
		,data:{AuthToken:"eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJFbXBJRCI6Ijc1NzEyIiwiUmVxdWVzdFRpbWUiOiIyMDE3MDcyNTAzMTkxMiIsIkV4cGlyZVRpbWUiOiIyMDE3MDcyNTAzMjIxMiIsIkxvY2FsZSI6ImtvIn0.p4horyuvjCcN61QoAICgmJLkHpwuYBLrvCO9PBNofQk"}
		,dataType:"jsonp"
		,crossDomain:true
		,success:function(Result){
			alert(Result);
		}

		,error: function(xhr,status, error){
			alert("에러발생");
		}
	});
*/

});

</script>