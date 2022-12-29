function getNextValue(dep) {
	
	var gd_cate_01 = "";
	var gd_cate_02 = "";
	var gd_cate_03 = "";
	var gd_cate_04 = "";

	if (document.frm.gd_cate_01 != null) gd_cate_01 = document.frm.gd_cate_01.value;
	if (document.frm.gd_cate_02 != null) gd_cate_02 = document.frm.gd_cate_02.value;
	if (document.frm.gd_cate_03 != null) gd_cate_03 = document.frm.gd_cate_03.value;
	if (document.frm.gd_cate_04 != null) gd_cate_04 = document.frm.gd_cate_04.value;

	$.get("../../_common/get_next_cate.php", 
		{ depth:dep, gd_cate_01:gd_cate_01, gd_cate_02:gd_cate_02 , gd_cate_03:gd_cate_03 , gd_cate_04:gd_cate_04 }, 
		function(data){
			var arr_str = data.split("");
			var arr_str_sub = "";

			for (i=0 ; i < (arr_str.length -1) ; i++) {
				arr_str_sub = arr_str[i].split("");
				add_cate_select(arr_str_sub[0], arr_str_sub[1], arr_str_sub[2], (i+1));
			}
		}
	);
}

function js_gd_cate_01() {

	var frm = document.frm;

	var obj02 = "gd_cate_02";
	obj = eval("document.frm."+obj02);


	if (obj != null) {
		clear_select(obj);
	}
	/*
	var obj03 = "gd_cate_03";
	obj = eval("document.frm."+obj03);

	if (obj != null) {
		clear_select(obj);
	}

	var obj04 = "gd_cate_04";
	obj = eval("document.frm."+obj04);

	if (obj != null) {
		clear_select(obj);
	}
	*/
	if (frm.gd_cate_01.value != "") {
		frm.depth.value = "1";

		getNextValue(frm.depth.value);

	}
	
}

function js_gd_cate_02() {
	//var frm = document.frm;
	/*
	var obj03 = "gd_cate_03";
	obj = eval("document.frm."+obj03);

	if (obj != null) {
		clear_select(obj);
	}

	var obj04 = "gd_cate_04";
	obj = eval("document.frm."+obj04);

	if (obj != null) {
		clear_select(obj);
	}
	
	if (frm.gd_cate_02.value != "") {
		frm.depth.value = "2";
		getNextValue(frm.depth.value);
	}
	*/
}

function js_gd_cate_03() {
	//var frm = document.frm;

	var obj04 = "gd_cate_04";
	obj = eval("document.frm."+obj04);

	if (obj != null) {
		clear_select(obj);
	}
	
	if (frm.gd_cate_03.value != "") {
		frm.depth.value = "3";
		getNextValue(frm.depth.value);
	}
}

function js_gd_cate_04() {
	//var frm = document.frm;

	var obj05 = "gd_cate_05";
	obj = eval("document.frm."+obj05);

	if (obj != null) {
		clear_select(obj);
	}
	
	if (frm.gd_cate_04.value != "") {
		frm.depth.value = "4";
		getNextValue(frm.depth.value);
	}
}

function add_cate_select(depth, value, text, index){

	var obj = "";

	if (depth == "1") {
		obj = eval("document.frm.gd_cate_02");
	}

	if (depth == "2") 
		obj = eval("document.frm.gd_cate_03");

	if (depth == "3") 
		obj = eval("document.frm.gd_cate_04");

	if (obj != null) {
		obj.options[index] = new Option(text, value);
	}
}

function clear_select(obj){
	sel_len = obj.length;
	for(i = sel_len ; i > 0; i--) {
		obj.options[i] = null;
	}
	return ;
}