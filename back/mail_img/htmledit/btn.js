document.onmouseover = doOver;
document.onmouseout  = doOut;
document.onmousedown = doDown;
document.onmouseup   = doUp;

function doOver() {
	var toEl = getReal(window.event.toElement, "className", "BtnOver");
	var fromEl = getReal(window.event.fromElement, "className", "BtnOver");
	if (toEl == fromEl) return;
	var el = toEl;
	var cDisabled = el.cDisabled;
	cDisabled = (cDisabled != null);
	if (el.className == "BtnOver")
	el.onselectstart = new Function("return false");
	if ((el.className == "BtnOver") && !cDisabled) {
		makeRaised(el);
	}
}
function doOut() {
	var toEl = getReal(window.event.toElement, "className", "BtnOver");
	var fromEl = getReal(window.event.fromElement, "className", "BtnOver");
	if (toEl == fromEl) return;
	var el = fromEl;
	var cDisabled = el.cDisabled;
	cDisabled = (cDisabled != null);

	var cToggle = el.cToggle;
	toggle_disabled = (cToggle != null);

	if (cToggle && el.value) {
	makePressed(el);
	}
	else if ((el.className == "BtnOver") && !cDisabled) {
	makeFlat(el);
	}
}
function doDown() {
	el = getReal(window.event.srcElement, "className", "BtnOver");

	var cDisabled = el.cDisabled;
	cDisabled = (cDisabled != null);

	if ((el.className == "BtnOver") && !cDisabled) {
	makePressed(el)
	}
}
function doUp() {
	el = getReal(window.event.srcElement, "className", "BtnOver");

	var cDisabled = el.cDisabled;
	cDisabled = (cDisabled != null);

	if ((el.className == "BtnOver") && !cDisabled) {
	makeRaised(el);
	}
}
function getReal(el, type, value) {
	temp = el;
	while ((temp != null) && (temp.tagName != "BODY")) {
	if (eval("temp." + type) == value) {
	el = temp;
	return el;
	}
	temp = temp.parentElement;
	}
	return el;
}
function makeFlat(el) {
	with (el.style) {
	border = "1px solid #F0F0E7";
	padding = "1px";
	}
}
function makeRaised(el) {
	with (el.style) {
	borderLeft   = "1px solid buttonhighlight";
	borderRight  = "1px solid buttonshadow";
	borderTop    = "1px solid buttonhighlight";
	borderBottom = "1px solid buttonshadow";
	padding      = "1px";
	}
}
function makePressed(el) {
	with (el.style) {
	borderLeft   = "1px solid buttonshadow";
	borderRight  = "1px solid buttonhighlight";
	borderTop    = "1px solid buttonshadow";
	borderBottom = "1px solid buttonhighlight";
	paddingTop    = "2px";
	paddingLeft   = "2px";
	paddingBottom = "0px";
	paddingRight  = "0px";
	}
}