var mmm_uAgent = navigator.userAgent.toLowerCase();
var mmm_mobilePhones = new Array('iphone','ipod','android','blackberry','windows ce','nokia','webos','opera mini','sonyericsson','opera mobi','iemobile');
var ismobilecon=false;
for(var i=0;i<mmm_mobilePhones.length;i++){
	if(mmm_uAgent.indexOf(mmm_mobilePhones[i]) != -1) ismobilecon  = true;
}



//var cur_cate="";
var cursns="";//현재 선택된 sns
//페이스북 로그인 유저 정보 가져오기 
var snsid_facebook='';
var snsphoto_facebook='';
var snsname_facebook='';

var snsid_twitter='';
var snsphoto_twitter='';
var snsname_twitter='';

var snsid_me2day='';
var snsphoto_me2day='';
var snsname_me2day='';

var snsid_upp='';
var snsphoto_upp='';
var snsname_upp='';

var leafpostions = {};
	leafpostions[0]={	0:{"x":180,"y":330,"z":1},		1:{"x":310,"y":200,"z":0.6},	2:{"x":250,"y":470,"z":0.4},	3:{"x":330,"y":340,"z":0.4},	4:{"x":300,"y":420,"z":0.3},
						5:{"x":250,"y":290,"z":0.4},	6:{"x":400,"y":300,"z":0.4},	7:{"x":400,"y":380,"z":0.3},	8:{"x":390,"y":480,"z":0.7},	9:{"x":430,"y":360,"z":0.8},
						10:{"x":300,"y":280,"z":0.3},	11:{"x":600,"y":250,"z":0.5},	12:{"x":500,"y":300,"z":0.3},	13:{"x":400,"y":100,"z":1},		14:{"x":530,"y":440,"z":0.6},
						15:{"x":470,"y":500,"z":0.3},	16:{"x":500,"y":200,"z":0.8},	17:{"x":630,"y":340,"z":0.7},	18:{"x":640,"y":450,"z":0.6},	19:{"x":440,"y":250,"z":0.4}};


	leafpostions[1]={0:{"x":400,"y":340,"z":0.8},1:{"x":280,"y":170,"z":0.3},2:{"x":490,"y":390,"z":0.5},3:{"x":300,"y":320,"z":0.3},4:{"x":250,"y":460,"z":0.8},
					5:{"x":440,"y":180,"z":0.5},6:{"x":470,"y":490,"z":0.4},7:{"x":590,"y":430,"z":0.5},8:{"x":570,"y":480,"z":0.5},9:{"x":420,"y":70,"z":0.8},
					10:{"x":310,"y":230,"z":0.3},11:{"x":300,"y":440,"z":0.4},12:{"x":600,"y":190,"z":0.5},13:{"x":550,"y":370,"z":0.4},14:{"x":530,"y":500,"z":0.4},
					15:{"x":590,"y":290,"z":0.5},16:{"x":230,"y":470,"z":0.2},17:{"x":350,"y":490,"z":0.3},18:{"x":440,"y":450,"z":0.4},19:{"x":580,"y":340,"z":0.2},
					20:{"x":240,"y":230,"z":0.5},21:{"x":500,"y":330,"z":0.4},22:{"x":210,"y":410,"z":0.6},23:{"x":320,"y":300,"z":0.7},24:{"x":350,"y":420,"z":0.4},
					25:{"x":550,"y":200,"z":0.7},26:{"x":230,"y":300,"z":0.6},27:{"x":380,"y":380,"z":0.2},28:{"x":370,"y":200,"z":0.6},29:{"x":510,"y":170,"z":0.4},
					30:{"x":330,"y":260,"z":0.2},31:{"x":500,"y":450,"z":0.2},32:{"x":630,"y":320,"z":0.6},33:{"x":400,"y":300,"z":0.3},34:{"x":600,"y":375,"z":0.3},
					35:{"x":360,"y":150,"z":0.4},36:{"x":490,"y":290,"z":0.3},37:{"x":640,"y":430,"z":0.4},38:{"x":420,"y":160,"z":0.3},39:{"x":480,"y":250,"z":0.3},
					40:{"x":300,"y":280,"z":0.2},41:{"x":550,"y":440,"z":0.2},42:{"x":630,"y":270,"z":0.3},43:{"x":240,"y":370,"z":0.3},44:{"x":300,"y":380,"z":0.5},
					45:{"x":320,"y":140,"z":0.4},46:{"x":390,"y":470,"z":0.4},47:{"x":640,"y":400,"z":0.3},48:{"x":510,"y":120,"z":0.2},49:{"x":420,"y":260,"z":0.4},

					50:{"x":350,"y":270,"z":0.3},51:{"x":510,"y":460,"z":0.3},52:{"x":670,"y":330,"z":0.7},53:{"x":420,"y":300,"z":0.4},54:{"x":650,"y":375,"z":0.3},
					55:{"x":370,"y":110,"z":0.6},56:{"x":530,"y":280,"z":0.5},57:{"x":660,"y":450,"z":0.6},58:{"x":450,"y":160,"z":0.3},59:{"x":510,"y":230,"z":0.3},
					60:{"x":325,"y":285,"z":0.2},61:{"x":570,"y":460,"z":0.3},62:{"x":660,"y":280,"z":0.4},63:{"x":260,"y":370,"z":0.4},64:{"x":330,"y":380,"z":0.5},
					65:{"x":330,"y":100,"z":0.6},66:{"x":430,"y":480,"z":0.5},67:{"x":660,"y":400,"z":0.3},68:{"x":530,"y":140,"z":0.3},69:{"x":460,"y":280,"z":0.2}};


$(document).ready(function() {

	cookieCheck4id();

	getSnsListMore();
	

	$("li.sns").each(function(i){
		$(this).click(function(){
			//alert($(this).hasClass('facebook'));
			if($(this).find("span").hasClass('facebook')){
				login_facebook();
				if(snsid_facebook!=""){
					if($(this).hasClass("active")){
						logout_facebook();
						$(this).removeClass("active");
					}else{
						$(this).addClass("active");
						cursns="facebook";
					}
				}
			}
			if($(this).find("span").hasClass('twitter')){
				login_twitter();
				if(snsid_twitter!=""){
					if($(this).hasClass("active")){
						logout_twitter();
						$(this).removeClass("active");
					}else{
						$(this).addClass("active");
						cursns="twitter";
					}
				}
			}
			if($(this).find("span").hasClass('me2day')){
				login_me2day();
				if(snsid_me2day!=""){
					if($(this).hasClass("active")){
						logout_me2day();
						$(this).removeClass("active");
					}else{
						$(this).addClass("active");
						cursns="me2day";
					}
				}
			}

			if($(this).find("span").hasClass('upp')){
				login_upp();
				if(snsid_upp!=""){
					if($(this).hasClass("active")){
						logout_upp();
						$(this).removeClass("active");
					}else{
						$(this).addClass("active");
						cursns="upp";
					}
				}
			}
		});
		$(this).css("cursor","pointer");
	});

	//페이스북 로그인
	function login_facebook(){
		 if(snsid_facebook=="" || snsid_facebook==null){
		 var fbw=window.open('/kor/snslib/facebook/login.php','facebook_login','width=400px,height=600px');
		 }
	}

	//페이스북 로그인체크
	function logincheck_facebook(){
	   $("li.facebook").addClass('snslogged');cursns="facebook";
	}

	//twitter 로그인시작
	function login_twitter(){
		if(snsid_twitter=="" || snsid_twitter==null){
		 var tww=window.open('/kor/snslib/twitter/redirect.php','twitter_login','width:400px;height:600px');
		 }
	}
	
	//twitter 로그인체크
	function logincheck_twitter(){		
	   $("li.twitter").addClass('snslogged');cursns="twitter";
	}

	//me2day 로그인시작
	function login_me2day(){
		if(snsid_me2day=="" || snsid_me2day==null){
		 var tww=window.open('/kor/snslib/me2day/index.php','me2day_login','width=900px,height=500px;');
		 }
	}

	//me2day 로그인체크
	function logincheck_me2day(){		
	   $("li.me2day").addClass('snslogged');cursns="me2day";
	}


	//upp 로그인시작
	function login_upp(){
		
		if(snsid_upp=="" || snsid_upp==null){
		 var tww=window.open('/kor/snslib/upp/upp.php?o_mode=request_token','upp_login','width=458px,height=150px');
		 }
	}
	
	//upp 로그인체크
	function logincheck_upp(){		
	   $("li.upp").addClass('snslogged');cursns="upp";
	}
/*	 $.get("snslist.php", 
		 { nPageSize:5, mb_cate:"sns1" }, 
			function(data){ 
				$("#div_sns_container").html(data); 
		   }
	  );
*/
	  $(window).scroll(function() {
			var position = $(window).scrollTop();
			var hh = $(window).height();
			var bd = $(document).height(); 

			if(position+hh>=bd){
				//if(Number(curPage)<Number($("#intTotalCount").val())){
					//getSnsListMore();
				//}
			}
		});



});

	//facebook 로그아웃
	function logout_facebook(){
		var tww=window.open('/kor/snslib/facebook/logout.php','facebook_login','width:300px;height:400px');
		$.cookie("snsid_facebook", null,{expires:-1,path:'/', domain:"goupp.org"});//유저아이디
		$.cookie("snsname_facebook", null,{expires:-1,path:'/', domain:"goupp.org"});//유저이름
		$.cookie("snsphoto_facebook", null,{expires:-1,path:'/', domain:"goupp.org"}); // 사진
		snsid_facebook		= "";
		snsname_facebook		= "";
		snsphoto_facebook	= "";
		if(cursns=="facebook")cursns="";

		//$("#status").html(document.cookie);
	}
	//twitter 로그아웃
	function logout_twitter(){
		var tww=window.open('/kor/snslib/twitter/logout.php','twitter_login','width:300px;height:400px');
		$.cookie("snsid_twitter", null,{expires:-1,path:'/', domain:"goupp.org"});//유저아이디
		$.cookie("snsname_twitter", null,{expires:-1,path:'/', domain:"goupp.org"});//유저이름
		$.cookie("snsphoto_twitter", null,{expires:-1,path:'/', domain:"goupp.org"}); // 사진
		snsid_twitter		= "";
		snsname_twitter		= "";
		snsphoto_twitter	= "";
		if(cursns=="twitter")cursns="";
	}
	//me2day 로그아웃
	function logout_me2day(){
		var tww=window.open('/kor/snslib/me2day/logout.php','me2day_login','width:300px;height:400px');
		$.cookie("snsid_me2day", null, {expires:-1,path:'/', domain:"goupp.org"});//유저아이디
		$.cookie("snsname_me2day", null, {expires:-1,path:'/', domain:"goupp.org"});//유저이름
		$.cookie("snsphoto_me2day", null, {expires:-1,path:'/', domain:"goupp.org"}); // 사진		
		snsid_me2day		= "";	
		snsname_me2day		= "";
		snsphoto_me2day		= "";
		if(cursns=="me2day")cursns="";
	}
	//upp 로그아웃
	function logout_upp(){
		//alert("준비중");
		//return;
		//var tww=window.open('/kor/snslib/upp/logout.php','upp_login','width:300px;height:400px');
		$.cookie("snsid_upp", null, {expires:-1,path:'/', domain:"goupp.org"});//유저아이디
		$.cookie("snsname_upp", null, {expires:-1,path:'/', domain:"goupp.org"});//유저이름
		$.cookie("snsphoto_upp", null, {expires:-1,path:'/', domain:"goupp.org"}); // 사진		
		snsid_upp		= "";	
		snsname_upp		= "";
		snsphoto_upp		= "";
		if(cursns=="upp")cursns="";
	}
	//모든 sns 로그아웃
	function logout_all(){
		logout_facebook();
		logout_twitter();
		logout_me2day();
		//logout_yozm();
		logout_upp();
	}

	
	//leaf  좌표구하기
	var leaf_x,leaf_y=0;
	function get_leaf_pos(){
		leaf_x=Math.round(Math.random()*700);
		if(leaf_x<200)leaf_x=180+Math.round(Math.random()*40);
		if(leaf_x>700)leaf_x=680+Math.round(Math.random()*40);

		leaf_y=Math.round(Math.random()*500);
		if(leaf_y<100)leaf_y=100+Math.round(Math.random()*40);
		if(leaf_y>500)leaf_y=480+Math.round(Math.random()*40);
	}

	//sns 작성하기
	var	isprocessing_comment=false;
	var create_mb_no=0;
	
	function sns_send(){
		if(isprocessing_comment){alert("처리중입니다.");return}
		if(cursns==""){alert("작성한 sns를 선택해 주세요");return;}
		var comment = $.trim($("#comment").val());
		if($.trim(comment)==""){alert("댓글을 입력해 주세요");return;}

		get_leaf_pos();
		
		$("#btn_tree").attr("src","../images/common/loading_150.gif");
		

		if(cursns=="facebook" && snsid_facebook!="")postFaceBook();
		if(cursns=="twitter" && snsid_twitter!="" )postTwitter();
		if(cursns=="me2day"  && snsid_me2day!="")postMe2day();
		if(cursns=="upp" && snsid_upp!="")postUpp();


		  
		  //leafViewLarge('');

	}

	//sns 답변쓰기
	function sns_replyset(sns, mb_no){
		$("#mb_sns").val(sns);
		$("#mb_parent").val(mb_no);
		$("#comment").focus();
	}

	 //페이스북 : 내 담벼락에 새글 쓰고 id가져오기 
	function postFaceBook(){
		 var mb_parent = $.trim($("#mb_parent").val());
		 var comment = $.trim($("#comment").val());
		 if(comment=="")return;
		 
		isprocessing_comment=true;
		$.get("../snslib/facebook/post.php", 
			{ menu:"update", mb_cate:cur_cate,mb_parent:mb_parent, mb_sns:"facebook",
			mb_snsid:snsid_facebook,mb_snsname:snsname_facebook,mb_snsphoto:snsphoto_facebook,comment : comment, xpos:leaf_x, ypos:leaf_y, shorturl:thispage_shorten_url }, 
			function(data){  
				isprocessing_comment=false;  
				$("#btn_tree").attr("src","../images/btn/btn_tree.gif");
				leafViewSmall('');
				$("#comment").val('');

				try{
					//alert( "Data Loaded: " + data ); 
					var jobj = $.parseJSON($.trim(data));
					       
					create_mb_no = jobj.bbsid;
					if($.trim(jobj.snsid)!=""){
						curPage=1;
						getSnsListMore();
					}
				}catch(e){
					//alert(e);
						curPage=1;
						getSnsListMore();
				}
			}
		);
	 }

	 //트위터 : 내 타임라인에 새글 쓰고 id가져오기 
	function postTwitter(){	 
		 var mb_parent = $.trim($("#mb_parent").val());
		 var comment = $.trim($("#comment").val());
		 if(comment=="")return;

		isprocessing_comment=true;
		$.get("../snslib/twitter/post.php", 
			{ menu:"update", mb_cate:cur_cate,mb_parent:mb_parent,mb_sns:"twitter",
			mb_snsid:snsid_twitter,mb_snsname:snsname_twitter,mb_snsphoto:snsphoto_twitter,comment : comment, xpos:leaf_x, ypos:leaf_y , shorturl:thispage_shorten_url }, 
			function(data){     
				isprocessing_comment=false;  
				$("#btn_tree").attr("src","../images/btn/btn_tree.gif");
				
					leafViewSmall('');
				$("#comment").val('');
					try{
						var jobj = $.parseJSON($.trim(data));
						create_mb_no = jobj.bbsid;
						//alert("글쓰기 성공 = " + jobj.snslink+", "+jobj.bbsid);
						if($.trim(jobj.snslink)!=""){
							curPage=1;
							getSnsListMore();
						}
					}catch(e){
						curPage=1;
						getSnsListMore();
					}
				}
		);
	 }

	//미투데이 : 새글 쓰고 id가져오기 
	function postMe2day(){	 
		var mb_parent = $.trim($("#mb_parent").val());
		var comment = $.trim($("#comment").val());
		if(comment=="")return;

		isprocessing_comment=true;
		$.get("../snslib/me2day/post.php?callback=", 
			{ menu:"create", mb_cate:cur_cate,mb_parent:mb_parent,mb_sns:"me2day",
			mb_snsid:snsid_me2day,mb_snsname:snsname_me2day,mb_snsphoto:snsphoto_me2day,comments:comment, xpos:leaf_x, ypos:leaf_y , shorturl:thispage_shorten_url}, 
			function(data){        
				isprocessing_comment=false;  
				$("#btn_tree").attr("src","../images/btn/btn_tree.gif");
					leafViewSmall('');  
				$("#comment").val('');      
				try{
					var jobj = $.parseJSON($.trim(data));
					create_mb_no = jobj.bbsid;
					//alert("글쓰기 성공 = " + jobj.snslink+", "+jobj.bbsid);
					if($.trim(jobj.snslink)!=""){
						curPage=1;
						getSnsListMore();
					}
				}catch(e){
						curPage=1;
						getSnsListMore();
				}
			}
		);
	}

	 //요즘 : 새글 쓰고 id가져오기 
	function postUpp(){	 
		var mb_parent = $.trim($("#mb_parent").val());
		var comment = $.trim($("#comment").val());
		if(comment=="")return;

		isprocessing_comment=true;
		$.get("../snslib/upp/post.php", 
			{menu:"update",mb_cate:cur_cate,parent_msg_id:mb_parent,mb_sns:"upp",
			mb_snsid:snsid_upp,mb_snsname:snsname_upp,mb_snsphoto:snsphoto_upp,comment:comment, xpos:leaf_x, ypos:leaf_y , shorturl:thispage_shorten_url}, 
			function(data){      
				isprocessing_comment=false; 				
				$("#btn_tree").attr("src","../images/btn/btn_tree.gif");
				leafViewSmall('');  
				$("#comment").val('');
				try{
					var jobj = $.parseJSON($.trim(data));
					create_mb_no = jobj.bbsid;
					if(jobj.rstatus=="Fail2")alert("오늘은 이미 작성하셨습니다.");
					//alert("글쓰기 성공 = " + jobj.snslink+", "+jobj.bbsid);
					if($.trim(jobj.snslink)!=""){
						curPage=1;
						getSnsListMore();
					}
				}catch(e){
						curPage=1;
						getSnsListMore();
				}
			}
		);
	}

	 //sns 삭제하기
	 var del_sns_mb_no=0;
	function sns_delete(sns, mb_no, mb_snslink){
		var conf = confirm("정말로 삭제하시겠습니까?");
		if(conf){
			switch(sns){
				case "facebook":
					deleteFaceBook(mb_no, mb_snslink);
					break;
				case "twitter":
					deleteTwitter(mb_no, mb_snslink);
					break;
				case "me2day":
					deleteMe2day(mb_no, mb_snslink);
					break;
				case "upp":
					deleteYozm(mb_no, mb_snslink);
					break;
			}
		}
	}

	//페이스북 : 삭제하기
	function deleteFaceBook(mb_no, mb_snslink){		  
		$.get("../snslib/facebook/post.php", 
			{ menu:"delete", mb_cate:cur_cate,mb_no:mb_no,mb_snslink:mb_snslink }, 
			function(data){ 
				alert( "Delete Success " ); 
				del_sns_mb_no = mb_no;
				//$("#sns_tr1_"+mb_no).remove();
				//$("#sns_tr2_"+mb_no).remove();

				$("#sns_li_"+mb_no).animate({
					width: '1px',
					height: '1px',
					opacity: .1
				  }, 1000, 'linear', function() {					  
						$("#sns_li_"+mb_no).remove();
				  });
				$("#sns_tr2_"+mb_no).animate({
					width: '1px',
					opacity: .1
				  }, 1000, 'linear', function() {		
						$("#sns_tr2_"+mb_no).remove();
				  });

				 $(".mb_no_"+mb_no).remove();
			}
		);
	}

	//트위터 : 삭제하기
	function deleteTwitter(mb_no, mb_snslink){	 
		$.get("../snslib/twitter/post.php", 
			{ menu:"delete", mb_cate:cur_cate,mb_no:mb_no,mb_snslink:mb_snslink }, 
			function(data){ 
				del_sns_mb_no = mb_no;
				//var jobj = $.parseJSON(data);
				//alert("Delete Success = " + jobj.snslink+", "+jobj.bbsid);
				//alert( "Delete Success " ); 
				$("#sns_li_"+mb_no).animate({
					width: '1px',
					height: '1px',
					opacity: .1
				  }, 1000, 'linear', function() {					  
						$("#sns_li_"+mb_no).remove();
				  });
				$("#sns_tr2_"+mb_no).animate({
					width: '1px',
					opacity: .1
				  }, 1000, 'linear', function() {		
						$("#sns_tr2_"+mb_no).remove();
				  });
				 $(".mb_no_"+mb_no).remove();
			}
		);
	}

	//미투데이 : 삭제하기 
	function deleteMe2day(mb_no, mb_snslink){
		$.get("../snslib/me2day/post.php?callback=", 
			{ menu:"delete", mb_cate:cur_cate,mb_no:mb_no,mb_snslink:mb_snslink}, 
			function(data){
				del_sns_mb_no = mb_no;
				//var jobj = $.parseJSON(data);
				//alert("Delete Success = " + jobj.snslink+", "+jobj.bbsid);
				alert( "Delete Success " ); 
				$("#sns_tr1_"+mb_no).remove();
				$("#sns_tr2_"+mb_no).remove();
			}
		);
	}

	//요즘 : 삭제하기 
	function deleteYozm(mb_no, mb_snslink){	 
		$.get("../snslib/yozm/post.php", 
			{o_mode:"yozm_delete",mb_cate:cur_cate,mb_no:mb_no,mb_snslink:mb_snslink}, 
			function(data){
				del_sns_mb_no = mb_no;
				//alert( "Delete Success "+data ); 
				alert( "Delete Success " ); 
				$("#sns_tr1_"+mb_no).remove();
				$("#sns_tr2_"+mb_no).remove();
			}
		);
	}


	
	//쿠키로 로그인 여부 확인/sns버턴 활설/비활성 처리
	function cookieCheck4id(){

		snsid_twitter		= $.cookie("snsid_twitter")!=null	?$.cookie("snsid_twitter"):"";
		snsphoto_twitter	= $.cookie("snsphoto_twitter")!=null	?$.cookie("snsphoto_twitter"):"";
		snsname_twitter		= $.cookie("snsname_twitter")!=null	?$.cookie("snsname_twitter"):"";

		snsid_facebook		= $.cookie("snsid_facebook")!=null	?$.cookie("snsid_facebook"):"";
		snsphoto_facebook	= $.cookie("snsphoto_facebook")!=null	?$.cookie("snsphoto_facebook"):"";
		snsname_facebook	= $.cookie("snsname_facebook")!=null	?$.cookie("snsname_facebook"):"";


		snsid_me2day		= $.cookie("snsid_me2day")!=null	?$.cookie("snsid_me2day"):"";
		snsphoto_me2day		= $.cookie("snsphoto_me2day")!=null	?$.cookie("snsphoto_me2day"):"";
		snsname_me2day		= $.cookie("snsname_me2day")!=null	?$.cookie("snsname_me2day"):"";

		snsid_yozm			= $.cookie("snsid_yozm")!=null	?$.cookie("snsid_yozm"):"";
		snsphoto_yozm		= $.cookie("snsphoto_yozm")!=null	?$.cookie("snsphoto_yozm"):"";
		snsname_yozm		= $.cookie("snsname_yozm")!=null	?$.cookie("snsname_yozm"):"";

		snsid_upp			= $.cookie("snsid_upp")!=null	?$.cookie("snsid_upp"):"";
		snsphoto_upp		= $.cookie("snsphoto_upp")!=null	?$.cookie("snsphoto_upp"):"";
		snsname_upp			= $.cookie("snsname_upp")!=null	?$.cookie("snsname_upp"):"";



		//$("#debug").val($("#debug").val()+snsid_twitter);
		//$("#debug").css("display","block");
		
		$("span.facebook").parent().removeClass('login');
		$("span.twitter").parent().removeClass('login');
		$("span.me2day").parent().removeClass('login');
		//$("span.yozm").parent().removeClass('login');
		$("span.upp").parent().removeClass('login');

		$("span.facebook").parent().removeClass('active');
		$("span.twitter").parent().removeClass('active');
		$("span.me2day").parent().removeClass('active');
		//$("span.yozm").parent().removeClass('active');
		$("span.upp").parent().removeClass('active');

		if(snsid_facebook != "")$("span.facebook").parent().addClass('login');
		if(snsid_twitter != "")$("span.twitter").parent().addClass('login');
		if(snsid_me2day != "")$("span.me2day").parent().addClass('login');
		//if(snsid_yozm != "")$("span.yozm").parent().addClass('login');
		if(snsid_upp != "")$("span.upp").parent().addClass('login');
		
		//$("#snsphoto").html("");
		if(snsid_twitter != "" && cursns=="twitter"){
			$("span.twitter").parent().removeClass('login');
			$("span.twitter").parent().addClass('active');
			//if($("#snsphoto").html()!="<img src='"+snsphoto_twitter+"' style='width:49px;height:49px;'>"){
			if($("#snsphoto > img").attr("src")!=snsphoto_twitter && snsphoto_twitter!=""){
				if(snsphoto_twitter!="")$("#snsphoto").html("<img src='"+snsphoto_twitter+"' style='width:49px;height:49px;'>");
			}
		}
		if(snsid_facebook != "" && cursns =="facebook"){
			$("span.facebook").parent().removeClass('login');
			$("span.facebook").parent().addClass('active');
			//if($("#snsphoto").html()!="<img src='"+snsphoto_facebook+"' style='width:49px;height:49px;'>"){
			if($("#snsphoto > img").attr("src")!=snsphoto_facebook && snsphoto_facebook!=""){
				if(snsphoto_facebook!="")$("#snsphoto").html("<img src='"+snsphoto_facebook+"' style='width:49px;height:49px;'>");
			}
		}
		if(snsid_me2day != "" && cursns=="me2day"){
			$("span.me2day").parent().removeClass('login');
			$("span.me2day").parent().addClass('active');
			//if($("#snsphoto").html()!="<img src='"+snsphoto_me2day+"' style='width:49px;height:49px;'>"){
			if($("#snsphoto > img").attr("src")!=snsphoto_me2day && snsphoto_me2day!=""){
				if(snsphoto_me2day!="")$("#snsphoto").html("<img src='"+snsphoto_me2day+"' style='width:49px;height:49px;'>");
			}
		}
		/*if(snsid_yozm != "" && cursns=="yozm"){
			$("span.yozm").parent().removeClass('login');
			$("span.yozm").parent().addClass('active');
			//if($("#snsphoto").html()!="<img src='"+snsphoto_kbs+"' style='width:49px;height:49px;'>"){
			if($("#snsphoto > img").attr("src")!=snsphoto_yozm && snsphoto_yozm!=""){
				if(snsphoto_yozm!="")$("#snsphoto").html("<img src='"+snsphoto_yozm+"' style='width:49px;height:49px;'>");
			}
			$("#snsphoto").html("");
		}*/
		if(snsid_upp != "" && cursns=="upp"){
			$("span.upp").parent().removeClass('login');
			$("span.upp").parent().addClass('active');
			//if($("#snsphoto").html()!="<img src='"+snsphoto_kbs+"' style='width:49px;height:49px;'>"){
			if($("#snsphoto > img").attr("src")!=snsphoto_upp && snsphoto_upp!=""){
				if(snsphoto_upp!="")$("#snsphoto").html("<img src='"+snsphoto_upp+"' style='width:49px;height:49px;'>");
			}
			//$("#snsphoto").html("");
		}

		if(cursns==""){
			if(snsid_twitter != ""){					 
				cursns="twitter";		
				$("span.twitter").parent().removeClass('login');
				$("span.twitter").parent().addClass('active');
				//if($("#snsphoto").html()!="<img src='"+snsphoto_twitter+"' style='width:49px;height:49px;'>"){
				if($("#snsphoto > img").attr("src")!=snsphoto_twitter && snsphoto_twitter!=""){
					if(snsphoto_twitter!="")$("#snsphoto").html("<img src='"+snsphoto_twitter+"' style='width:49px;height:49px;'>");
				}
			}else if(snsid_facebook != ""){					 
				cursns="facebook";			
				$("span.facebook").parent().removeClass('login');
				$("span.facebook").parent().addClass('active');
				//if($("#snsphoto").html()!="<img src='"+snsphoto_facebook+"' style='width:49px;height:49px;'>"){
				if($("#snsphoto > img").attr("src")!=snsphoto_facebook && snsphoto_facebook!=""){
					if(snsphoto_facebook!="")$("#snsphoto").html("<img src='"+snsphoto_facebook+"' style='width:49px;height:49px;'>");
				}
			}else if(snsid_me2day != ""){					 
				cursns="me2day";		
				$("span.me2day").parent().removeClass('login');
				$("span.me2day").parent().addClass('active');
				//if($("#snsphoto").html()!="<img src='"+snsphoto_me2day+"' style='width:49px;height:49px;'>"){
				if($("#snsphoto > img").attr("src")!=snsphoto_me2day && snsphoto_me2day!=""){
					if(snsphoto_me2day!="")$("#snsphoto").html("<img src='"+snsphoto_me2day+"' style='width:49px;height:49px;'>");
				}
			}else if(snsid_yozm != ""){				 
				cursns="yozm";
				$("span.yozm").parent().removeClass('login');
				$("span.yozm").parent().addClass('active');
				//if($("#snsphoto").html()!="<img src='"+snsphoto_kbs+"' style='width:49px;height:49px;'>"){
				if($("#snsphoto > img").attr("src")!=snsphoto_yozm && snsphoto_yozm!=""){
					if(snsphoto_yozm!="")$("#snsphoto").html("<img src='"+snsphoto_yozm+"' style='width:49px;height:49px;'>");
				}
			}else if(snsid_upp != ""){				 
				cursns="upp";
				$("span.upp").parent().removeClass('login');
				$("span.upp").parent().addClass('active');
				//if($("#snsphoto").html()!="<img src='"+snsphoto_kbs+"' style='width:49px;height:49px;'>"){
				if($("#snsphoto > img").attr("src")!=snsphoto_upp && snsphoto_upp!=""){
					if(snsphoto_upp!="")$("#snsphoto").html("<img src='"+snsphoto_upp+"' style='width:49px;height:49px;'>");
				}
			}else {
				$("#snsphoto").html("");
			}
		}
		
		setTimeout("cookieCheck4id()",1000);

	}

	
	 //sns list 가져오기
	var curPage=1;
	var issnsprocess=false;
	function getSnsListMore(){	 
		//alert(curPage);
		if(issnsprocess)return;
		issnsprocess=true;
		if(curPage==1)$("#div_sns_container").html(""); 
		$.get("snslist2.php", 
			{ nPageSize:10, nPage:curPage, mb_cate:cur_cate }, 
			function(data){ 
				$("#div_sns_container").html($("#div_sns_container").html()+data); 
				if(curPage>0){
					for(var pi=0;pi<curPage;pi++){
						//$("#tr_more_"+pi).hide();
						$("#li_more_"+pi).hide();
					}
				}
				if($.trim(data)=="")$("#li_more_").hide();
				curPage++;
				issnsprocess=false;
			}
		);
	}


	var zindex=10000;
	function leafViewLarge(n){
		var org_n=n;
		if($.trim(n)!="")n='_'+n;
		
		if($.browser.msie && $.browser.version > 6 && $.browser.version < 9) {		
			//var leaftop = parseInt($('#leaf'+n+'').css('top').replace(/px/g, ''));
			//$('#leaf_div'+n).css('top',(leaftop-70)+'px');
			//alert(leaftop+"/"+$('#leaf'+n+'').css('top'));
		}
		$('#leaf_'+n).css('z-index',zindex++);
		$('#leaf_div'+n).css('z-index',zindex++);
		$('#leaf_div'+n).show();
	}

	function leafViewLarge___(n){
		var org_n=n;
		if($.trim(n)!="")n='_'+n;
		var left_val = parseInt($('#leaf_div'+n).attr('leaf_div_left'))>350 ?350:parseInt($('#leaf_div'+n).attr('leaf_div_left'));
	
		//$('#leaf_div'+n).show();
		$('#leaf_div'+n).css('z-index',zindex++);
		
		 //setTimeout("leafViewSmall("+org_n+")",2000);
	/*		
		$('#leaf_div'+n).animate({
			width: '300px',
//			left:  left_val+'px'
		  }, 600, 'linear', function() {
			  //$(this).after('<div>Animation complete.</div>');
			  setTimeout("leafViewSmall("+org_n+")",2000);
		  });
	
		$('#leaf_dl'+n).animate({
			opacity: '1.0'
		  }, 600, 'linear', function() {
			  //$(this).after('<div>Animation complete.</div>');
				$('#leaf_dl'+n).css('zoom','1');
			  setTimeout("leafViewSmall("+org_n+")",2000);
		  });
*/
		 try{		 
			
		 }catch(e){
		 }
	}

			
	function leafViewSmall(n){
		var org_n=n;
		if($.trim(n)!="")n='_'+n;
		/*

		if($.trim(n)=="")n='_'+(curleafcnt-1);
		//alert(n);
		//$('#leaf'+n).show();
		$('#leaf_div'+n).hide();
		*/
		$('#leaf_div'+n).hide();
		$('#leaf_div'+n).css('z-index',$('#leaf_div'+n).attr('leaf_div_index'));
		$('#leaf'+n).css('z-index',$('#leaf_div'+n).attr('leaf_div_index'));
		if($.trim(org_n)==""){
			curLeafPage=1;
			getLeafList();
		}
	}
	function leafViewSmall__(n){
		if($.trim(n)!="")n='_'+n;

		if($.trim(n)=="")n='_'+(curleafcnt-1);
		//alert(n);
		//$('#leaf'+n).show();
		$('#leaf_div'+n).hide();
//alert($('#leaf_dl'+n).attr('leaf_div_zoom')+"/"+$('#leaf_dl'+n).attr('leaf_div_opacity'));
		$('#leaf_dl'+n).animate({
//			zoom:		$('#leaf_dl'+n).attr('leaf_div_zoom'),
			opacity:	$('#leaf_dl'+n).attr('leaf_div_opacity')
		  }, 1000, 'linear', function() {
			  //$(this).after('<div>Animation complete.</div>');
			 //$(this).css("opacity","1");
			 $('#leaf_div'+n).css('z-index',$('#leaf_div'+n).attr('leaf_div_index'));
				//$('#leaf_dl'+n).css('zoom',$('#leaf_dl'+n).attr('leaf_div_zoom'));
			 if($.trim(n)==""){
					leafcnt++;
					$html_txt = "";					
					$html_txt += '<div id="leaf_div_'+leafcnt+'" leaf_div_left="'+leaf_y+'"  leaf_div_top="'+leaf_t+'"   leaf_div_index="'+zindex+'"  >';
					$html_txt += '	<dl  id="leaf_dl_'+leafcnt+'" style="" class="leaf_div mb_no_'+create_mb_no+'" ';
					$html_txt += '		leaf_div_zoom="1"  leaf_div_opacity="1" ';
					$html_txt += '		nmouseover="leafViewLarge('+leafcnt+');" nclick="leafViewLarge('+leafcnt+');" >';
					$html_txt += '		<dt id="tree_sns_img_'+leafcnt+'" style="width:48px;height:48px;">';
					if($.trim($("#tree_sns_img").html())!="")$html_txt += '			<img src="'+$("#tree_sns_img img").attr('src')+'" style="width:48px;height:48px;" alt="" />';
					$html_txt += '		</dt>';
					$html_txt += '		<dd id="tree_sns_comment_'+leafcnt+'">'+$("#tree_sns_comment").html()+'</dd>';
					$html_txt += '	</dl>';
					$html_txt += '</div>';
					$html_txt += '<div id="leaf_'+leafcnt+'" class="leaf mb_no_'+create_mb_no+'" ';
					$html_txt += ' style="background:url(tree/leaf01.png) no-repeat 0 0px;';
					$html_txt += ' position:absolute;width:60px;height:40px;top:'+leaf_y+'px;left:'+leaf_x+'px;display:block;cursor:pointer" ';
					$html_txt += ' onmouseover="leafViewLarge('+leafcnt+');" onclick="leafViewLarge('+leafcnt+');" ontouch="leafViewLarge('+leafcnt+');">';
					$html_txt += '</div>';
					var $obj = $($html_txt);
					$obj.css({ opacity: 0 }).appendTo($("#div_tree_container")).animate({ opacity: 1 },1000);
					$('#leaf').hide();
			 }
		  });
		 /**/
		 try{
			if(jQuery.browser.mozilla || jQuery.browser.opera){
				//$('#leaf_dl'+n).css('-webkit-transform','scale(' + ($('#leaf_dl'+n).attr('leaf_div_zoom')) + ')');
				//$('#leaf_dl'+n).css('-webkit-transform-origin','0 0');
				//$('#leaf_dl'+n).css('-moz-transform','scale(' + ($('#leaf_dl'+n).attr('leaf_div_zoom')) + ')');
				//$('#leaf_dl'+n).css('-moz-transform-origin','0 0');
				//$('#leaf_dl'+n).css('-o-transform','scale(' + ($('#leaf_dl'+n).attr('leaf_div_zoom')) + ')');
				//$('#leaf_dl'+n).css('-o-transform-origin','0 0');
			}
		 }catch(e){
		 }
	}


	function getLeafListJson(pm){
		var getting = true;
		if(pm=="+")curLeafPage++;

		if(pm=="-")curLeafPage--;

		if(curLeafPage<1){
			curLeafPage=1;
			$("#tree_btn_prev").html('<img src="../images/btn/arrow_prev.png" alt="" />');
			getting=false;
		}

		if(curLeafPage>=curLeafTotalPage){
			curLeafPage=curLeafTotalPage;
			$("#tree_btn_next").html('<img src="../images/btn/arrow_next.png" alt="" />');
			getting=false;
		}
			
		//alert("["+pm+"]");
		if(getting)getLeafList();

	}

	function preLeafRemoveAll(){
		try{
			if(curLeafPage>1){
				$("#tree_btn_prev").html('<img src="../images/btn/arrow_prev_over.png" alt="" />');
			}
			if(curLeafPage<curLeafTotalPage){
				$("#tree_btn_next").html('<img src="../images/btn/arrow_next_over.png" alt="" />');
			}
			$.each($("#div_tree_container div"),function(j){	
						  $(this).remove();		
				/*$(this).animate({
						opacity: 0.1,
						width: '30px',
						top:  '0px',
						left:  '0px'
				  }, 600, 'linear', function() {
						  $(this).remove();
				  });*/
			});
		 }catch(e){
			//alert("@@#@@"+e.description);
		 }

	}


	var tzindex=10000;
	var curleafcnt=0;//현재 보이는 나뭇잎 수
	var leafcnt=0;
	var ajax;
	var viewLeafCnt=70;//나무에 한번에 보여줄 잎 개수
	var curLeafTotalPage=1;//전체 
	var curLeafPage=1;
	var isleafprocess=false;
	function getLeafList(){	
		
		
		$("#div_tree_container").append("<div style='padding:20px 0 0 440px;width:900px;height:600px;z-index:22000;'><img src='../images/common/ajax_loader_32_32.gif' style='z-index:22000;'/></div>");
		//ajax_loader_16_16.gif
		

		if(isleafprocess)return;
		isleafprocess=true;
		var $html_txt="";
		var url = "snslist.json.php?act=resultlist"; 
			url += "&mb_cate="+cur_cate;
			url += "&nPage="+curLeafPage;
			url += "&nPageSize="+viewLeafCnt;
			url += "&r=" + Math.random();
			//url = url + "q="+encodeURIComponent($("#patentqry").val());


			ajax=$.ajax({
				mode: "sync",
				port: "searchresults",
				url: url,
				type: "post",
				dataType: "json",
				beforeSend: function( xhr ) {
					//xhr.overrideMimeType( 'text/plain; charset=x-user-defined' );
					//isprocessing_snslist=true;
					//alert(0);
				},
				success: function(data, textStatus, jqXHR) {
					try{
						isleafprocess=false;
						//var jobj = $.parseJSON($.trim(data));
						var jobj = $.trim(data);
						if (typeof data == "undefined") return;
							
						//alert(data.total);
						leafcnt = data.total;
						curLeafTotalPage = data.totalpage;
						preLeafRemoveAll();//이전 나뭇잎은 제거하기
						//curLeafPage++;//이부분 처리는 버턴클릭이벤트 처리함수로 이동, getLeafListJson()

						//curleafcnt = data.infos.length;

						//alert(curleafcnt);

						if(curLeafPage==1)setLeafTotalCnt(leafcnt);//참여자 수 표시하기
						var leafzoom=1;
						var desczoom=1;
						var leafopacity=1;
						//var jsonleafpos = $.parseJSON(leafpostions);

						var jsonleafpos = leafpostions[curLeafPage%4];

						 $.each(data.infos, function(i,item){
								curleafcnt++;
								leafi=i%4 +1;
								
								leafw=40;
								if (leafi<3)leafw=60;

								//leafzoom = 1.0 - (i/20);
								//if(leafzoom < .2)leafzoom = .2;

								desczoom = 1.00 + (i/30);
								
								leafopacity = 1 + (i/10);
								if(leafopacity < 0)leafopacity = .1;

								item[7] = jsonleafpos[i].x;
								item[8] = jsonleafpos[i].y;
								leafzoom = jsonleafpos[i].z;
								//leafzoom=0.2;
								
								if($.browser.msie && $.browser.version > 6 && $.browser.version < 9) {
									//leafzoom = jsonleafpos[i].z*100+"%";
								}

								var div_top = jsonleafpos[i].y-20;
								if(leafzoom > 0.9 )div_top = div_top - 40;
								else if(leafzoom > 0.7 )div_top = div_top - 40;
								else if(leafzoom > 0.5 )div_top = div_top - 35;
								else if(leafzoom > 0.3 )div_top = div_top - 35;
								else if(leafzoom > 0.1 )div_top = div_top - 40;

								
								var div_left = jsonleafpos[i].x;
								if(leafzoom > 0.9 )div_left = div_left +20;
								else if(leafzoom > 0.8 )div_left = div_left +15;
								else if(leafzoom > 0.7 )div_left = div_left +15;
								else if(leafzoom > 0.6 )div_left = div_left +10;
								else if(leafzoom > 0.5 )div_left = div_left +10;
								else if(leafzoom > 0.3 )div_left = div_left +10;

								/*
								if($.browser.msie && $.browser.version > 6 && $.browser.version < 9) {									
									div_top = jsonleafpos[i].y-20;
									if(leafzoom > 0.9 )div_top = div_top - 80;
									else if(leafzoom > 0.7 )div_top = div_top - 80;
									else if(leafzoom > 0.5 )div_top = div_top - 70;
									else if(leafzoom > 0.3 )div_top = div_top - 70;
									else if(leafzoom > 0.1 )div_top = div_top - 80;
								}
								*/
								var img_size = leafzoom*100 + 1;
								var img_padd = leafzoom*100 + 1;
								switch(leafzoom){
									case 0.1:
										img_size = 20;
										img_padd = 20;
										break;
									case 0.2:
										img_size = 20;
										img_padd = 20;
										break;
									case 0.3:
										img_size = 30;
										img_padd = 20;
										break;
									case 0.4:
										img_size = 40;
										img_padd = 20;
										break;
										break;
									case 0.5:
										img_size = 50;
										img_padd = 20;
										break;
										break;
									case 0.6:
										img_size = 60;
										img_padd = 20;
										break;
										break;
									case 0.7:
										img_size = 70;
										img_padd = 20;
										break;
										break;
									case 0.8:
										img_size = 80;
										img_padd = 20;
										break;
										break;
									case 0.9:
										img_size = 90;
										img_padd = 30;
										break;
										break;
									case 1:
										img_size = 100;
										img_padd = 30;
										break;
								}

								$html_txt = "";

								$html_txt += '		<div class="rel" id="leaf_div_'+i+'"  leaf_div_left="'+div_left+'"  leaf_div_top="'+(div_top)+'"  ';
								$html_txt += '			leaf_div_index="'+tzindex+'" style="display:none; top: '+(div_top)+'px; left: '+div_left+'px; ">';
								$html_txt += '			<div class="tooltip-box">';
								//$html_txt += ' leaf_div_index="'+tzindex+'" style="display:none; float:left;height:50px;overflow:hidden;">';
								//$html_txt += '				<p class="tooltip-desc" style=""> '+i+'['+jsonleafpos[i].x+': '+jsonleafpos[i].y+': '+jsonleafpos[i].z+']'+item[5]+'</p>';
								$html_txt += '				<p class="tooltip-desc" style=""> '+item[5]+'</p>';
								$html_txt += '			</div>';
								$html_txt += '		</div>';
								//if($.browser.msie && $.browser.version > 6 && $.browser.version < 9) {
								if(!ismobilecon){

								
									$html_txt += '<div id="leaf_'+i+'" style="display:block; position: absolute; top: '+item[8]+'px; left: '+item[7]+'px; cursor:pointer;" >';									
									$html_txt += '	<div class="gallery-nav">';
									$html_txt += '		<ul >';
									$html_txt += '			<li >';
									$html_txt += '				<a lass="tooltip " style="display:block; " azoom="'+(leafzoom)+'" href="#" no="'+i+'" ';
									$html_txt += '					onmouseover="leafViewLarge('+i+');" onmouseout="leafViewSmall('+i+');" onclick="leafViewLarge('+i+');">';
									if(i==0){
										$html_txt += swfString('../flash/btn100.swf',item[4], 102, 100);
									}else{
										$html_txt += swfString('../flash/btn'+(leafzoom*100)+'.swf',item[4], leafzoom*100+2, leafzoom*100);
									}
									$html_txt += '				</a>';
									$html_txt += '			</li>';
									$html_txt += '		</ul>';
									$html_txt += '	</div>';
									$html_txt += '</div>';
								}else{
									$html_txt += '<div id="leaf_'+i+'" style="position: absolute; top: '+item[8]+'px; left: '+item[7]+'px; " >';
									$html_txt += '	<div class="gallery-nav">';
									$html_txt += '		<ul >';
									$html_txt += '			<li >';
									$html_txt += '				<a class="tooltip " style="background-image:url('+item[4]+');zoom:'+leafzoom+';padding:0px 0px 0px 10px" azoom="'+leafzoom+'" href="#" no="'+i+'" ';
									$html_txt += '					onmouseover="leafViewLarge('+i+');" onmouseout="leafViewSmall('+i+');" onclick="leafViewLarge('+i+');">';
									$html_txt += '				<div style="width:100px;height:100px" onmouseover="leafViewLarge('+i+');" onmouseout="leafViewSmall('+i+');" onclick="leafViewLarge('+i+');"></div>';
									//$html_txt += '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>';
									//$html_txt += '					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>';
									//$html_txt += '			<a class="tooltip " style="background-image:url('+item[4]+');" href="#">';
									//$html_txt += '				<div id="leaf_div_'+i+'" class="tooltip-box"  leaf_div_left="'+item[7]+'"  leaf_div_top="'+(item[8]-60)+'" > ';
									//$html_txt += '				<p class="tooltip-desc" style="zoom:100%"> ['+jsonleafpos[i].x+': '+jsonleafpos[i].y+': '+jsonleafpos[i].z+']'+item[5]+'</p>';
									//$html_txt += '				</div>';
									$html_txt += '				</a>';
									$html_txt += '			</li>';
									$html_txt += '		</ul>';
									$html_txt += '	</div>';
									$html_txt += '</div>';
								}
								//$('#leaf_div_'+i).css('z-index',tzindex);


								var $obj = $($html_txt);
								$obj.appendTo($("#div_tree_container"));

								//$obj.css({ opacity: 0,zoom:0.1 }).appendTo($("#div_tree_container")).animate({ opacity: 1, zoom:leafzoom+"" },1600);
								//2012-03-05$obj.css({ opacity: 0.1 }).appendTo($("#div_tree_container")).animate({ opacity: 1 },1000);
								//$obj.css({ zoom:leafzoom });
								//$obj.appendTo($("#div_tree_container"));
								//$("#div_tree_container").html($("#div_tree_container").html()+$html_txt);
								//$('#leaf_div_'+i).fadeTo(10, 0);


								//$('#leaf_div_'+i).css('position','absolute');
								$('#leaf_div_'+i).css('position','relative');
								$('#leaf_div_'+i).css('z-index',tzindex);
								$('#leaf_'+i).css('z-index',tzindex);
								if($.browser.msie && $.browser.version > 6 && $.browser.version < 9) {
									//$('#leaf_'+i+' a.tooltip').css( 'zoom',leafzoom );
									//$('#leaf_div_'+i).css('top',(div_top-50)+'px');									
									//div_top = jsonleafpos[i].y-20;
									//if(leafzoom > 0.9 )div_top = div_top - 80;
									//else if(leafzoom > 0.7 )div_top = div_top - 80;
									//else if(leafzoom > 0.5 )div_top = div_top - 70;
									//else if(leafzoom > 0.3 )div_top = div_top - 70;
									//else if(leafzoom > 0.1 )div_top = div_top - 80;
									//$('#leaf_div_'+i).css('top',(div_top-100)+'px');
									//$('#leaf_div_'+i).attr('leaf_div_top',(div_top-100));
									//PIE.attach($('#leaf_'+i+' a.tooltip'));
								}

								//$('#leaf_div_'+i).css('top',(item[8]-60)+'px');
								//$('#leaf_div_'+i).css( 'left',item[7]+'px');
								//$('#leaf_div_'+i).before( '<div style="position: absolute; bottom: -11px; left: 45%; background: url(../images/sub/tree.png); width: 19px; height: 11px; ">/');
								//$('#leaf_dl_'+i).css('position','relative');
								//$('#leaf_dl_'+i).css('top','0px');
								//$('#leaf_dl_'+i).css( 'left','0px');
								//$('#leaf_dl_'+i).css('zoom',leafzoom);								
								//$('#tree_sns_comment_'+i).css( 'display','none');

								tzindex--;


								try{
									if(jQuery.browser.mozilla || jQuery.browser.opera){										
										$('#leaf_'+i+' a.tooltip').parent().css('-webkit-transform','scale(' + (leafzoom) + ')');
										$('#leaf_'+i+' a.tooltip').parent().css('-webkit-transform-origin','0 0');
										$('#leaf_'+i+' a.tooltip').parent().css('-moz-transform','scale(' + (leafzoom) + ')');
										$('#leaf_'+i+' a.tooltip').parent().css('-moz-transform-origin','0 0');
										$('#leaf_'+i+' a.tooltip').parent().css('-o-transform','scale(' + (leafzoom) + ')');
										$('#leaf_'+i+' a.tooltip').parent().css('-o-transform-origin','0 0');
									}
								 }catch(e){
									//alert("@@0@@"+e.description);
								 }


								//html_txt = "";
						 });	
						 //alert($html_txt);
							//setTimeout('autoLeafLarge()',1000);//자동으로 크게 보이기
							//$("#debugtxt").val($("#div_tree_container").html());
							//$("#debugtxt").css('display','block');

						 
						 /* ///////////////////////////////////////////////////
						IE 7~8를 위한 PIE 라이브러리 활용.
						border-radius | box-shadow | linear-gradient
						/////////////////////////////////////////////////// */			
						var zzz="/";
						//alert($.browser.version);
						if($.browser.msie && $.browser.version > 6 && $.browser.version < 9) {

							try{
								$('a.tooltip').each(function() {
										//PIE.attach(this);
										//zzz=$(this).parent().html();
										//$(this).parent().css('zoom',parseFloat($(this).attr("azoom"))+0.2);
										//$(this).css('zoom',parseFloat($(this).attr("azoom"))+0.7);
										//$(this).css('zoom',1);
										//$(this).css('display',"block");
										//$(this).parent().parent().parent().parent().css('display',"block");
								});	
								//$('a.tooltip:first').trigger("mouseenter");//function(){alert($(this).html());}
								//$('a.tooltip:first').trigger("click");//function(){alert($(this).html());}
								//setTimeout('mouseLeafout()',1000);

								//$('a.tooltip:first').trigger("mouseleave");//function(){alert($(this).html());}
								//$('a.tooltip:first').trigger("click");//function(){alert($(this).html());}
								//alert(zzz);
							}catch(e){
								//alert("@@1@@"+e.description);
							}

						};

						 // CSS 트렌지션을 지원하지 않는 브라우저 판별.
						//if(!Modernizr.csstransitions ) {
							// .tooltip-box 요소를 감춥니다.
							//$('.tooltip-box').fadeTo(10, 0);
							
							// a.tooltip에 마우스가 올라가면
							$('a.tooltip').hover(function() {
								//alert($(this).css('z-index'));
								$('#leaf_div_'+$(this).attr("no")).stop().animate({'opacity': 1}, 400);
								leafViewLarge($(this).attr("no"));
								$("#comment").val($("#comment").val()+"/"+$(this).attr("no"));
							}, function() {
								// 마우스가 내려갔을 때 처리 동작.
								$('#leaf_div_'+$(this).attr("no")).stop().animate({'opacity': 0}, 400);
								leafViewSmall($(this).attr("no"));
							});
						//};
					}catch(e){
						//alert("@@2@@"+e.description);
					}



							//$("#debugtxt2").val($("#div_tree_container").html());
							//$("#debugtxt2").css('display','block');

					//$("#debugtxt").val($("#div_tree_container").html());
			 
				},
				complete: function(jqXHR, textStatus) { //Handle any errors
					//alert(textStatus);		
					if (!(textStatus.status >= 200 && textStatus.status < 300)) {
					 //alert(2);
					}
					ajax=null;
				},
				error: function(r) { //Handle any errors
					 //alert(r.status);
					ajax=null;
				}
			})
	}

	function mouseLeafout(){
		$('a.tooltip:first').trigger("mouseleave");
	}
	

	var cur_auto_leaf=0;
	function autoLeafLarge(){
		/*
		leafViewLarge(cur_auto_leaf);
		setTimeout('autoLeafLarge()',5000);
		cur_auto_leaf++;
		if(cur_auto_leaf>=leafcnt)cur_auto_leaf=1;
		*/
	}

	
	function setLeafTotalCnt(t){
		try{
			//curLeafTotalPage = parseInt((parseInt(t)/viewLeafCnt))+1;
			var original = ""+t;//String(t);
			$("#leafTotalCnt").html('');
			var lcnt='';

			var chkoi=0;
			var $obj;

			if(original.length>0){
				for(var oi=original.length-1;oi>=0;oi--){
					lcnt='<img src="../images/sub/'+original.substr(oi,1)+'.png" alt="" />';
					$obj = $(lcnt);
					//$obj.css({ opacity: 0,zoom:"10%" }).prepend($("#leafTotalCnt")).animate({ opacity: 1, zoom:"100%" },600);
					$obj.css({ opacity: 0.1,zoom:"10%" });
					$("#leafTotalCnt").prepend($obj);
					$obj.animate({ opacity: 1, zoom:"100%" },600);
					chkoi++;
				}
			}
			if(chkoi<6){
				for(var i=chkoi;i<6;i++){
					lcnt='<img src="../images/sub/0.png" alt="" />';
					$obj = $(lcnt);
					$obj.css({ opacity: 0.1,zoom:"10%" });
					$("#leafTotalCnt").prepend($obj);
					$obj.animate({ opacity: 1, zoom:"100%" },600);
					chkoi++;
				}
			}

		}catch(e){
			alert("[Error]"+e.description);
		}

	}


	function goTop(){
		//$("#div_sns_container").scrollTop(0);	
		//$("#body").scrollTop(0);	
		$("html,body").animate({scrollTop:0},1000);//window.scrollTo(0,0);
		//$(window).scrollTop(600+"px");
	}

	function swfString(url_root,img, w, h){
		var ret="";

		  ret ="<object  id='swf_map' classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' \n"
		  +" codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' \n"
		  +" width='"+w+"' Height='"+h+"'> \n"
		  +"<param name='movie' value='"+url_root+"'>\n"
		  +"<param name='quality' value='high'>\n"
		  +"<param name='allowScriptAccess' value='always' />"
		  +"<param name='wmode' value='transparent'>\n"
		  +"<param name='FlashVars' value='imgURL="+img+"'>\n"
		  +"<embed src='"+url_root+"' "
		  +" wmode='transparent' quality='high' width='"+w+"' height='"+h+"' "
		  +" type='application/x-shockwave-flash'  allowScriptAccess='always' FlashVars='imgURL="+img+"'"
		  +" pluginspage='http://www.macromedia.com/shockwav/download/index.cgi?P1_Prod_Version=ShockwaveFlash'>\n"
		  +"</embed></object>\n";
		return ret;
}