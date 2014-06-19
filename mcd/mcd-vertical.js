var canvascounter = 2;

function add_canvas() {
	var pr = document.getElementById("visarea");
	var canvas = document.createElement("div");
	canvas.setAttribute("id", "viscanvas" + canvascounter);
	// canvas.setAttribute("style", "background");
	canvas.setAttribute("class", "viscanvas");
	canvas.setAttribute("class", "selected");
	pr.appendChild(canvas);
	init("viscanvas" + canvascounter);

	
	// if(oldcanvasid != "") {
	// 	document.getElementById(oldcanvasid).className = "unselected";
	// }
	
	for(i = 0; i <= canvascounter; i++) {
		var e = document.getElementById("viscanvas").className = "unselected";
		
		var e = document.getElementById("viscanvas" + i);
		if(e && canvascounter != i) {
			e.className = "unselected";
			console.log("UNSELECT" + i);
		}
	}
	canvascounter++;

}