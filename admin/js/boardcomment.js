/*
//댓글달기: ajax 방식으로 변경
//2012-02-17
//내가그린기린그림
*/
var gbc_parent_board_code='';
var gbc_parent_board_no='';
var gbc_page='1';
var ajax;

var commentcnt=0;

$(document).ready(function() {
	gbc_parent_board_code = $.trim($("#girin_board_comment").attr("parent_board_code"));
	gbc_parent_board_no = $.trim($("#girin_board_comment").attr("parent_board_no"));
//alert(gbc_parent_board_code+" / "+gbc_parent_board_no);
	if(gbc_parent_board_code!="" && gbc_parent_board_no!="")
	{

		GBCgetCommentList();

	}



	//로딩바
	function ajaxloaderView(c,l,s){//c:container, l:loader, s:status
		var $loader = $('<span class="ajaxloder" style="text-align:center"/>');
		if(l!="" && l!=null)$loader = l;
		if(s=="show"){
			$loader.html($("#ajaxloader").html());
			$loader.css({ opacity: 0 }).appendTo(c).animate({ opacity: 1 });
		}else{
			$loader.remove();
		}
	}
	
	$(window).scroll(function() {
		var position = $(window).scrollTop();
		var hh = $(window).height();
		var bd = $(document).height(); 

		if(position+hh>=bd+20){
			//if(Number(curPage)<Number($("#intTotalCount").val())){
				//GBCgetCommentList();
			//}
			
			if(gbc_parent_board_code!="" && gbc_parent_board_no!="")
			{
//alert(position);
				GBCgetCommentList();

			}
		}
	});


});


	var isprocessing_commentlist=false;
	function GBCgetCommentList(){	
		
		if(isprocessing_commentlist)return;
		var html_txt="";
		var url = "data.json.comment.list.php?act=resultlist"; 
			url += "&parent_bb_code="+ gbc_parent_board_code;
			url += "&parent_bb_no="+ gbc_parent_board_no;
			url += "&nPage="+gbc_page;
			url += "&r=" + Math.random();
			//url = url + "q="+encodeURIComponent($("#patentqry").val());

			if(gbc_page<2){
				$("#girin_board_comment").html(""); 
				gbc_page=1;
			}
			ajax=$.ajax({
				mode: "sync",
				port: "searchresults",
				url: url,
				type: "post",
				dataType: "json",
				beforeSend: function( xhr ) {
					//xhr.overrideMimeType( 'text/plain; charset=x-user-defined' );
					isprocessing_commentlist=true;
					//alert(0);
				},
				success: function(data, textStatus, jqXHR) {
					try{
						
						isprocessing_commentlist=false;
						//$("#girin_board_comment").html('');
						//alert(data);
						//var jobj = $.parseJSON($.trim(data));
						//var jobj = $.trim(data);
						//if (typeof data == "undefined") return;
							
						commentcnt = data.total;
						if(commentcnt>=10)gbc_page++;
						//alert(data.infos.length);
						 $.each(data.infos, function(j,item){
								//leafi=i%4 +1;
								tr_class='modeual_cont';
								if(data.infos.length-1 == j)tr_class='end';
								
								html_txt = "";
								html_txt += '<div id="div_comment_'+item[0]+'">';
								html_txt += '<table cellpadding="0" border="0" cellspacing="0" class="rowstable03">';
								html_txt += '<colgroup>';
								html_txt += '	<col width="95%" />';
								html_txt += '	<col width="5%" />';
								html_txt += '</colgroup>';
								html_txt += '<tr>';
								html_txt += '	<td class="modeual_nm">'+item[9]+', '+item[18]+',';
								//html_txt += '	<td class="modeual_nm"><font color="navy">'+item[9]+'</font>';
								//html_txt += '	( '+item[17]+' / '+item[18]+' )';
								//html_txt += '	&nbsp;&nbsp;'+item[14]+'</td>';
								html_txt += '	<td>';
								//html_txt += '		<a href="javascript:gbc_reply_reply('+item[0]+');"><img src="/manager/images/admin/co_btn_reply.gif" alt="답변"></a>';
								//html_txt += '		<a href="javascript:gbc_reply_modify('+item[0]+');"><img src="/manager/images/admin/co_btn_modify.gif" alt="수정"></a>';
								html_txt += '		<a href="javascript:gbc_reply_delete(\''+item[4]+'\', '+item[0]+');"><img src="/site/managerK/images/admin/co_btn_delete.gif" alt="삭제"></a>';
								html_txt += '	</td>';
								html_txt += '</tr>';
								//html_txt += '<tr>';
								//html_txt += '	<td colspan="3" class="'+tr_class+'" style="padding-left:'+item[13]+'px">';
								//html_txt += '		'+item[12]+''+item[11]+'';
								//html_txt += '	</td>';
								//html_txt += '</tr>';
								html_txt += '</table>';
								html_txt += '</div>';

								/*

								html_txt += '<div id="div_comment_update_'+item[0]+'" style="display:none">';
								html_txt += '<div class="sp2"></div>';
								html_txt += '<table cellpadding="0" cellspacing="0" class="colstable">';
								html_txt += '	<colgroup>';
								html_txt += '		<col width="120" />';
								html_txt += '		<col width="*" />';
								html_txt += '	</colgroup>';
								html_txt += '	<tr>';
								html_txt += '		<th>';
								html_txt += '			댓글';
								html_txt += '		</th>';
								html_txt += '		<td class="contentswrite end line" style="padding: 10px 10px 10px 15px">';
								
								html_txt += '			<p class="bold bpd10">이름 <input type="text"  id="update_writer_nm_'+item[0]+'" value="'+item[9]+'" style="width:100px" maxlength="20"/>';
								html_txt += '			&nbsp;&nbsp;&nbsp;&nbsp;비밀번호 <input type="password" id="update_writer_pw_'+item[0]+'" value="'+item[15]+'" maxlength="20" style="width:100px" /></p>';
								html_txt += '			<textarea name="update_contents_'+item[0]+'" id="update_contents_'+item[0]+'" style="width: 80.2%; height:50px" >'+item[11]+'</textarea>';
								html_txt += '			<a href="javascript:gbc_reply_update_write('+item[0]+',\''+item[4]+'\', '+item[0]+',\''+item[9]+'\',\''+item[5]+'\',\''+item[6]+'\',\''+item[7]+'\',\'WRITER_PW\' );">';
								html_txt += '			<img src="/manager/images/admin/co_btn_write.gif" alt="확인"></a>';
								html_txt += '		</td>';
								html_txt += '	</tr>';
								html_txt += '</table>';
								html_txt += '</div>';

								html_txt += '<div id="div_comment_reply_'+item[0]+'" style="display:none">';
								html_txt += '<div class="sp2"></div>';
								html_txt += '<table cellpadding="0" cellspacing="0" class="colstable">';
								html_txt += '	<colgroup>';
								html_txt += '		<col width="120" />';
								html_txt += '		<col width="*" />';
								html_txt += '	</colgroup>';
								html_txt += '	<tr>';
								html_txt += '		<th>';
								html_txt += '			댓글';
								html_txt += '		</th>';
								html_txt += '		<td class="contentswrite end line" style="padding: 10px 10px 10px 15px">';
								
								html_txt += '			<p class="bold bpd10">이름 <input type="text"  id="reply_writer_nm_'+item[0]+'" value="" style="width:100px" maxlength="20"/>';
								html_txt += '			&nbsp;&nbsp;&nbsp;&nbsp;비밀번호 <input type="password" id="reply_writer_pw_'+item[0]+'" maxlength="20" style="width:100px" /></p>';
								html_txt += '			<textarea name="reply_contents_'+item[0]+'" id="reply_contents_'+item[0]+'" style="width: 80.2%; height:50px" ></textarea>';
								html_txt += '			<a href="javascript:gbc_reply_reply_write('+item[0]+',\''+item[4]+'\', '+item[0]+',\''+item[2]+'\',\''+item[1]+'\',\''+item[3]+'\',\''+item[5]+'\',\''+item[6]+'\' );">';
								html_txt += '			<img src="/manager/images/admin/co_btn_write.gif" alt="확인"></a>';
								html_txt += '		</td>';
								html_txt += '	</tr>';
								html_txt += '</table>';
								html_txt += '</div>';
								*/

								var $obj = $(html_txt);
								//$obj.css({ opacity: 0,zoom:"10%" }).appendTo($("#girin_board_comment_ul")).animate({ opacity: 1, zoom:"100%" },1600);
								$obj.css({ opacity: 0,zoom:"10%" }).appendTo($("#girin_board_comment")).animate({ opacity: 1, zoom:"100%" },600);

								//html_txt = "";
						 });	
						 /**/
						 //alert(html_txt);
							//setTimeout('autoLeafLarge()',1000);
							//alert($("#girin_board_comment").html());
					}catch(e){
						alert("e="+e.description);

					}
			 
				},
				complete: function(jqXHR, textStatus) { //Handle any errors
					//alert('c='+textStatus);		
					if (!(textStatus.status >= 200 && textStatus.status < 300)) {
					 //alert(2);
					}
					ajax=null;
				},
				error: function(r) { //Handle any errors
					//alert('e='+r.status);
					ajax=null;
				}
			})
	}




	var	isprocessing_comment=false;
	function gbc_reply_write() {

		if(isprocessing_comment)return;
		//var mb_parent = $.trim($("#mb_parent").val());
		var wmode = "IC";
		var writer_nm = $.trim($("#girin_comment_writer_nm").val());
		var writer_pw = $.trim($("#girin_comment_writer_pw").val());
		//var cate_03 = $.trim($("#girin_comment_cate_03").val());
		//var cate_04 = $.trim($("#girin_comment_cate_04").val());
		var use_tf = $.trim($("#girin_comment_use_tf").val());
		var contents = $.trim($("#girin_comment_contents").val());
		if((contents=="") || (contents== "간략한 댓글을 올려주세요. 로그인 후 등록하실 수 있습니다.")){alert("간략한 댓글을 올려주세요.");return;}

		isprocessing_comment=true;
		$.get("data.json.comment.post.php", 
			{mode:wmode, parent_bb_code:gbc_parent_board_code, parent_bb_no:gbc_parent_board_no, writer_nm:writer_nm, writer_pw:writer_pw,
			contents:contents, use_tf:use_tf, r: Math.random()}, 
			function(data){      
				isprocessing_comment=false;
				try{
					var jobj = $.parseJSON($.trim(data));
					//alert("글쓰기 성공 = " + jobj.snslink+", "+jobj.bbsid);
					if($.trim(jobj.bbsid)!=""){
						commentcnt++;
						//gbc_page=1;
						var html_txt = "";
								html_txt += '<div id="div_comment_'+jobj.bbsid+'">';
								html_txt += '<table cellpadding="0" border="0" cellspacing="0" class="rowstable03">';
								html_txt += '<colgroup>';
								html_txt += '	<col width="95%" />';
								html_txt += '	<col width="5%" />';
								html_txt += '</colgroup>';
								html_txt += '<tr>';
								html_txt += '	<td class="modeual_nm"><font color="navy">'+writer_nm+'</font>&nbsp;&nbsp;방금</td>';
								html_txt += '	<td>';
								//html_txt += '		<a href="javascript:gbc_reply_reply('+commentcnt+');"><img src="/manager/images/admin/co_btn_reply.gif" alt="답변"></a>';
								//html_txt += '		<a href="javascript:gbc_reply_modify('+commentcnt+');"><img src="/manager/images/admin/co_btn_modify.gif" alt="수정"></a>';
								//html_txt += '		<a href="javascript:gbc_reply_delete(\''+jobj.bbscode+'\', '+jobj.bbsid+');"><img src="/manager/images/admin/co_btn_delete.gif" alt="삭제"></a>';
								html_txt += '	</td>';
								html_txt += '</tr>';
								html_txt += '<tr>';
								html_txt += '	<td colspan="3" class="modeual_cont" style="padding-left:10px">';
								html_txt += '		'+contents+'';
								html_txt += '	</td>';
								html_txt += '</tr>';
								html_txt += '</table>';
								html_txt += '</div>';

								var $obj = $(html_txt);
								//$obj.css({ opacity: 0,zoom:"10%" }).appendTo($("#girin_board_comment_ul")).animate({ opacity: 1, zoom:"100%" },1600);
								$obj.css({ opacity: 0,zoom:"10%" }).prependTo($("#girin_board_comment")).animate({ opacity: 1, zoom:"100%" },600);
								$("#girin_comment_contents").val('');
					}
				}catch(e){
					//alert("e="+e)
						gbc_page=1;
						GBCgetCommentList();
				}
			}
		);
	}


	var now_reply_idx = "";
	var now_update_idx = "";

	function gbc_reply_modify(idx) {

		
		if (now_reply_idx == idx) {
			$('#div_comment_update_'+idx).css('display','none');
			now_reply_idx = "";
		} else {
			$('#div_comment_update_'+idx).css('display','block');
			now_reply_idx = idx;
		}

		//init();
	}

	function gbc_reply_reply(idx) {

		
		if (now_reply_idx == idx) {
			$('#div_comment_reply_'+idx).css('display','none');
			now_reply_idx = "";
		} else {
			$('#div_comment_reply_'+idx).css('display','block');
			now_reply_idx = idx;
		}
	}

	function gbc_reply_delete(bb_code, bb_no) {

		var frm = document.frm;
		bDelOK = confirm('자료를 삭제 하시겠습니까?');
		
		if (bDelOK==true) {
			
			$.get("data.json.comment.post.php", 
				{mode:'DC', temp_bb_code:bb_code, temp_bb_no:bb_no, r: Math.random()}, 
				function(data){      
					//isprocessing_comment=false;
					try{
						var jobj = $.parseJSON($.trim(data));
						//alert("글쓰기 성공 = " + jobj.snslink+", "+jobj.bbsid);
						if($.trim(jobj.bbsid)!=""){
							
									//$obj.css({ opacity: 0,zoom:"10%" }).appendTo($("#girin_board_comment_ul")).animate({ opacity: 1, zoom:"100%" },1600);
									//$obj.css({ opacity: 0,zoom:"10%" }).prependTo($("#girin_board_comment")).animate({ opacity: 1, zoom:"100%" },600);
									$("#div_comment_"+bb_no).remove();
						}
					}catch(e){
						//alert("e="+e)
							gbc_page=1;
							//GBCgetCommentList();
					}
				}
			);

		}
	}

	function gbc_reply_update_write(idx, bb_code,bb_no,name,cate_01,cate_02,cate_03,pw) {
		

		var t_writer_nm = $('#update_writer_nm_'+bb_no).val();
		var t_writer_pw = $('#update_writer_pw_'+bb_no).val();
		var contents = $.trim($("#update_contents_"+idx).val());

		
		if($.trim(t_writer_nm)==""){alert("이름을 입력해주세요.");return;}
		if($.trim(t_writer_pw)==""){alert("비밀번호를 입력해주세요.");return;}
		if((contents=="") || (contents== "간략한 댓글을 올려주세요. 로그인 후 등록하실 수 있습니다.")){alert("간략한 댓글을 올려주세요.");return;}

		isprocessing_comment=true;
		$.get("data.json.comment.post.php", 
			{mode:"UC", temp_bb_code:bb_code, temp_bb_no:bb_no, temp_writer_name:t_writer_nm, temp_writer_pw:t_writer_pw, 
			temp_cate_01:cate_01, temp_cate_02:cate_02, temp_cate_03:cate_03, 
			temp_contents:contents, use_tf:'Y', r: Math.random()}, 
			function(data){      
				isprocessing_comment=false;
				try{
					var jobj = $.parseJSON($.trim(data));
					//alert("글쓰기 성공 = " + jobj.snslink+", "+jobj.bbsid);
					if($.trim(jobj.bbsid)!=""){
						/*commentcnt++;
						gbc_page=1;
						var html_txt = "";
								html_txt += '<div id="div_comment_'+jobj.bbsid+'">';
								html_txt += '<table cellpadding="0" border="0" cellspacing="0" class="rowstable03">';
								html_txt += '<colgroup>';
								html_txt += '	<col width="95%" />';
								html_txt += '	<col width="5%" />';
								html_txt += '</colgroup>';
								html_txt += '<tr>';
								html_txt += '	<td class="modeual_nm"><font color="navy">'+writer_nm+'</font>&nbsp;&nbsp;방금</td>';
								html_txt += '	<td>';
								html_txt += '		<a href="javascript:gbc_reply_reply('+commentcnt+');"><img src="/manager/images/admin/co_btn_reply.gif" alt="답변"></a>';
								html_txt += '		<a href="javascript:gbc_reply_modify('+commentcnt+');"><img src="/manager/images/admin/co_btn_modify.gif" alt="수정"></a>';
								html_txt += '		<a href="javascript:gbc_reply_delete(\''+jobj.bbscode+'\', '+jobj.bbsid+');"><img src="/manager/images/admin/co_btn_delete.gif" alt="삭제"></a>';
								html_txt += '	</td>';
								html_txt += '</tr>';
								html_txt += '<tr>';
								html_txt += '	<td colspan="3" class="modeual_cont" style="padding-left:10px">';
								html_txt += '		'+contents+'';
								html_txt += '	</td>';
								html_txt += '</tr>';
								html_txt += '</table>';
								html_txt += '</div>';

								var $obj = $(html_txt);
								//$obj.css({ opacity: 0,zoom:"10%" }).appendTo($("#girin_board_comment_ul")).animate({ opacity: 1, zoom:"100%" },1600);
								$obj.css({ opacity: 0,zoom:"10%" }).prependTo($("#girin_board_comment")).animate({ opacity: 1, zoom:"100%" },600);
								$("#girin_comment_contents").val('');
								*/
						gbc_page=1;
						GBCgetCommentList();
					}
				}catch(e){
					alert("e="+e)
						gbc_page=1;
						GBCgetCommentList();
				}
			}
		);


	}

	function gbc_reply_reply_write(idx, bb_code, bb_no, bb_de, bb_re, bb_po, cate_01, cate_02) {
		

		//var writer_nm = $.trim($("#girin_comment_writer_nm").val());
		//var writer_pw = $.trim($("#girin_comment_writer_pw").val());
		
		var writer_nm = $('#reply_writer_nm_'+bb_no).val();
		var writer_pw = $('#reply_writer_pw_'+bb_no).val();

		//var cate_03 = $.trim($("#girin_comment_cate_03").val());
		var cate_04 = $.trim($("#girin_comment_cate_04").val());


		
		if($.trim(writer_nm)==""){alert("이름을 입력해주세요.");return;}
		if($.trim(writer_pw)==""){alert("비밀번호를 입력해주세요.");return;}
		var contents = $.trim($("#reply_contents_"+idx).val());
		if((contents=="") || (contents== "간략한 댓글을 올려주세요. 로그인 후 등록하실 수 있습니다.")){alert("간략한 댓글을 올려주세요.");return;}

		isprocessing_comment=true;
		$.get("data.json.comment.post.php", 
			{mode:"RC", temp_bb_code:bb_code, temp_bb_no:bb_no, writer_nm:writer_nm, writer_pw:writer_pw, 
			temp_bb_de:bb_de, temp_bb_re:bb_re, temp_bb_po:bb_po, 
			temp_cate_01:cate_01, temp_cate_02:cate_02, 
			temp_contents:contents, use_tf:'Y', r: Math.random()}, 
			function(data){      
				isprocessing_comment=false;
				try{
					var jobj = $.parseJSON($.trim(data));
					//alert("글쓰기 성공 = " + jobj.snslink+", "+jobj.bbsid);
					if($.trim(jobj.bbsid)!=""){
						commentcnt++;
						//gbc_page=1;
						var html_txt = "";
								html_txt += '<div id="div_comment_'+jobj.bbsid+'">';
								html_txt += '<table cellpadding="0" border="0" cellspacing="0" class="rowstable03">';
								html_txt += '<colgroup>';
								html_txt += '	<col width="95%" />';
								html_txt += '	<col width="5%" />';
								html_txt += '</colgroup>';
								html_txt += '<tr>';
								html_txt += '	<td class="modeual_nm"><font color="navy">'+writer_nm+'</font>&nbsp;&nbsp;방금</td>';
								html_txt += '	<td>';
								//html_txt += '		<a href="javascript:gbc_reply_reply('+commentcnt+');"><img src="/manager/images/admin/co_btn_reply.gif" alt="답변"></a>';
								//html_txt += '		<a href="javascript:gbc_reply_modify('+commentcnt+');"><img src="/manager/images/admin/co_btn_modify.gif" alt="수정"></a>';
								//html_txt += '		<a href="javascript:gbc_reply_delete(\''+jobj.bbscode+'\', '+jobj.bbsid+');"><img src="/manager/images/admin/co_btn_delete.gif" alt="삭제"></a>';
								html_txt += '	</td>';
								html_txt += '</tr>';
								html_txt += '<tr>';
								html_txt += '	<td colspan="3" class="modeual_cont" style="padding-left:10px">';
								for(hi=0;hi<=jobj.bb_de;hi++){
									html_txt += '		&nbsp;';
								}
								html_txt += '		&nbsp;┗';
								html_txt += '		'+contents+'';
								html_txt += '	</td>';
								html_txt += '</tr>';
								html_txt += '</table>';
								html_txt += '</div>';

								var $obj = $(html_txt);
								//$obj.css({ opacity: 0,zoom:"10%" }).appendTo($("#girin_board_comment_ul")).animate({ opacity: 1, zoom:"100%" },1600);
								$("#div_comment_reply_"+idx).after($obj).animate({ opacity: 1, zoom:"100%" },600);
								$("#reply_contents_"+idx).val('');
								$("#div_comment_reply_"+idx).hide();
					}
				}catch(e){
						alert("e="+e)
						gbc_page=1;
						GBCgetCommentList();
				}
			}
		);

	}





