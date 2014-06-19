var w = 600,
	h = 600,
	// fill = d3.scale.category20(),
	nodes = [],
	links = [];

var vis;

// var vis = d3.select("body").append("svg:svg")
// 	.attr("width", w)
// 	.attr("height", h);
// 
// vis.append("svg:rect")
// 	.attr("width", w)
// 	.attr("height", h);

// params
var R = 120;
var nodeColors = ["#c09853", "#b94a48", "#3a87ad", "#468847", "#999999", "#444444"];
var colorCounter = 0;
var xyz = [0,0,1];
var SHRINK_DURATION = 300;
var RIPPLE_DURATION = 500;

// temp data
var nodeid = 0;
var expanded_nodes = [];
var selectedQueryTerm;

function calcNodePosition(i, childConut, rootx, rooty, R) {
	var angle = i * 360 / childConut + 180;
	var radian = angle * (3.141592 / 180);

	var xpos = rootx + Math.sin(radian) * R;
	var ypos = rooty + Math.cos(radian) * R;

	return [xpos, ypos];
}

function hide_contextmenu() {
	$(".node-contextmenu").hide("fast");	
}

function init() {
	if(vis != null)
		vis.remove();

	nodes = [];
	links = [];
	nodeid = 0;
	
	vis = d3.select("#viscanvas").append("svg:svg")
		.attr("width", w)
		.attr("height", h)
		.attr("class", "zoomr")
		.on("zoom", function() {
			console.log(d3.event.translate[0]);
		});
		// .attr("pointer-events", "all")
		// .append("svg:g")
		// .attr("class","zoomr");

	vis.append("svg:rect")
		.attr("width", w)
		.attr("height", h)
		.attr("class", "rectt")
		.call(d3.behavior.zoom().on("zoom", function() {
			xyz[0]=d3.event.translate[0];
			xyz[1]=d3.event.translate[1];

			vis.selectAll(".node circle, .node text, #ripple, .link")
			.attr("transform", "translate("+xyz[0]+","+xyz[1]+")");

			// for(var i = 0; i < nodes.length; i++) {
			// 	nodes[i].x += xyz[0];
			// 	nodes[i].y += xyz[1];
			// }
			hide_contextmenu();
			$(".concept-list-container").hide(0);


		}))
		// 	.attr("class","rectr")
		// 			.call(d3.behavior.zoom([xyz[0],xyz[1],0]).on("zoom", function(){
		// 	xyz[0]=d3.event.translate[0];
		// 	xyz[1]=d3.event.translate[1];
		//  	vis.select(".zoomr").attr("transform",
		//       "translate("+xyz[0]+","+xyz[1]+")scale("+xyz[2]+")");
		// 	vis.select(".rectr").attr("transform",
		// 	  "translate(" + xyz[0]*-1+","+xyz[1]*-1+ ")");
		// }))
		
}

function searchCid(cid) {
	for(i = 0; i < nodes.length; i++) {
		if(nodes[i].conceptID == cid)
			return true;
	}
	return false;
}

function addSubTree(tree, root, cat, color, subTreeCount) {
	
	var offset = root.childCount;
	
	for(var i = 0; i < tree.length; i++) {
		var o = tree[i];
		var maxf = getmax(tree);

		if(searchCid(o.conceptID))
			continue;

		pos = calcNodePosition(offset + i, subTreeCount, root.x, root.y, R);

		var node = {x: pos[0], y: pos[1], term: o.term, rootnode:root, num:offset+i+1, nodeid:nodeid++, shrunk:false, ratio:5/maxf, rippler:R, termtype:o.termtype, conceptID:o.conceptID, color:color, category:cat, childCount: 0};
		if(cat == "association") {
			console.log(o.relationType);
			node.relationType = o.relationType;
		}
		nodes.push(node);
		links.push({source:root, target:node, path:false});
	}
	root.childCount += i;
}

function buildRipple(cid, root) {
	d3.json("http://mcd.ischool.drexel.edu/ahn/mcdapi_proxy.php?cid=" + cid, function(json) {
	// d3.json("./sample.json", function(json) {
		
		console.log(">>>" + json.associations.count);
		console.log(">>>" + json.siblings.count);
		console.log(">>>" + json.children.count);

		var assc = json.associations.concepts;
		var sibl = json.siblings.concepts;
		var chld = json.children.concepts;
		var prnt = [json.parent];

		var count = 0;
		
		count += prnt.length;
			
		// assc = assc.slice(0, Math.min(assc.length - 1, 2));
		// sibl = sibl.slice(0, Math.min(sibl.length - 1, 2));
		// chld = chld.slice(0, Math.min(chld.length - 1, 2));

		chld = chld.slice(0, Math.min(chld.length, 2));
		count += chld.length;

		var gr, ls;
		
		if(assc.length > sibl.length) {
			gr = assc;
			ls = sibl;
		}
		else {
			gr = sibl;
			ls = assc;
		}
		
		assc = assc.slice(0, Math.min(assc.length, 2));
		count += assc.length;
		
		sibl = sibl.slice(0, Math.min(sibl.length, 5 - count));
		count += sibl.length;

		var subTreeCount = assc.length + sibl.length + chld.length + prnt.length;
		subTreeCount = count;
		
		addSubTree(prnt, root, "parent", nodeColors[3], subTreeCount);
		addSubTree(assc, root, "association", nodeColors[0], subTreeCount);
		addSubTree(sibl, root, "sibling", nodeColors[1], subTreeCount);
		addSubTree(chld, root, "child", nodeColors[2], subTreeCount);

		paint(root.x, root.y, R);
	});
	
}

function addGridNode(root, tree, cnt, offset, color, nexts) {
	var pos = calcNodePosition(offset, 4, root.x, root.y, R);
	var label, cid;

	if(tree.length > 0) {
		label = " (" + cnt + ")";
		cid = tree[0].conceptID;
	}
	else {
		label = "";
		cid = "";
	}
	console.log(".." + tree);
	var node = {x: pos[0], y: pos[1], term: label, rootnode:root, num:offset+1, nodeid:nodeid++, shrunk:false, ratio:1, rippler:R, termtype:"", conceptID:cid, color:color, category:"", childCount: 0, tree:tree};

	nodes.push(node);
	links.push({source:root, target:node, path:false});
	root.childCount += 1;
}

function buildGrid(cid, root) {
	d3.json("http://mcd.ischool.drexel.edu/ahn/mcdapi_proxy.php?cid=" + cid, function(json) {
	// d3.json("./sample.json", function(json) {
		
		console.log(">>>" + json.associations.count);
		console.log(">>>" + json.siblings.count);
		console.log(">>>" + json.children.count);

		assc_list = json.associations;
		sibl_list = json.siblings;
		chld_list = json.children;

		var assc = json.associations.concepts;
		var sibl = json.siblings.concepts;
		var chld = json.children.concepts;
		var prnt = [json.parent];

		var assc_cnt = json.associations.count;
		var sibl_cnt = json.siblings.count;
		var chld_cnt = json.children.count;
		var prnt_cnt = prnt.length;

		addGridNode(root, prnt, prnt_cnt, 0, nodeColors[3]);
		addGridNode(root, sibl, sibl_cnt, 1, nodeColors[1]);
		addGridNode(root, chld, chld_cnt, 2, nodeColors[2]);
		addGridNode(root, assc, assc_cnt, 3, nodeColors[0]);

		paint(root.x, root.y, R);
	});
	
}

function search(cid, concept) {

	init();

	var root = {x: w/2, y: h/2, term: concept, rootnode:null, num:0, nodeid:nodeid++, shrunk:false, ratio:1.2, rippler:R, childCount: 0, conceptID:cid};
	nodes.push(root);

	buildGrid(cid, root);
}

function search2(d) {
	buildGrid(d.conceptID, d);
}

function search3(cid, t) {
	$(".concept-list-container").hide(0);
	tempRoot.term = t;
	buildGrid(cid, tempRoot);
	relabel();

}

function newpos(d) {
	if(d.rootnode == null)
		return [d.x, d.y];
		
	var rx = d.rootnode.x, ry = d.rootnode.y;
	var oldr = d.rootnode.rippler;
	console.log("+++" + d.rootnode.childCount);
	pos = calcNodePosition(d.num-1, d.rootnode.childCount, rx, ry, oldr);
	d.x = pos[0]; d.y = pos[1];
	return pos;
}


function relabel() {
	vis.selectAll(".node text")
	.text(function(d) {return d.term});
}

function toggle_nodes(termtype) {
	
	var visible_counter = 0;
	
	vis.selectAll(".node circle, .node text")
	.filter(function(ds, i) {
		if(ds.termtype == termtype || termtype == "all") {
			return false;
		}
		return true;
	})
	.style("opacity", 0)

	vis.selectAll(".node circle, .node text")
	.filter(function(ds, i) {
		if(ds.termtype == termtype || termtype == "all") {
			visible_counter++;
			return true;
		}
		return false;
	})
	.style("opacity", 1);

	console.log(visible_counter);
	visible_counter /= 2;
	
	vis.selectAll(".node circle")
	.filter(function(ds, i) {
		if(ds.termtype == termtype || termtype == "all")
			return true;
		return false;
	})
	.transition()
	.duration(0.5)
	.attr("cx", function(d,i) {return newpos2(d,i,visible_counter)[0]})
	.attr("cy", function(d,i) {return newpos2(d,i,visible_counter)[1]})

	vis.selectAll(".node text")
	.filter(function(ds, i) {
		if(ds.termtype == termtype || termtype == "all")
			return true;
		return false;
	})
	.transition()
	.duration(0.5)
	.attr("x", function(d,i) {return newpos2(d,i,visible_counter)[0]})
	.attr("y", function(d,i) {return newpos2(d,i,visible_counter)[1] - 8});

}


function newpos2(d,i,n) {
	var rx = d.rootnode.x, ry = d.rootnode.y;
	return calcNodePosition(i, n, rx, ry, R);
}