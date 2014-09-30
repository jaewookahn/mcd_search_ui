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

function getmax(tree) {
	var maxf = -1;
	return 5;
	for(var i = 0; i < tree.length; i++) {
		var val = parseInt(tree[i].freq);
		if(maxf < val)
			maxf = val;
	}
	
	return maxf;
}


function get_stems(term) {
	// dump non-words
	term = term.replace(/[^\w]/g, ' ');
	
	// dump multiple white-space
	term = term.replace(/\s+/g, ' ');
	
	// split
	wordlist = term.split(' ');
	
	for(var i = 0; i < wordlist.length; i++) {
		wordlist[i] = stemmer(wordlist[i]);
	}

	return wordlist.join(" ");
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
	d3.json("http://rack90.cs.drexel.edu/ahn/mcdapi_proxy.php?cid=" + cid, function(json) {
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

function addGridNode(root, tree, cnt, offset, color) {
	var pos = calcNodePosition(offset, 4, root.x, root.y, R);
	var label, cid;

	if(tree.length > 0) {
		label = tree[0].term + " (" + cnt + ")";
		cid = tree[0].conceptID;
	}
	else {
		label = "";
		cid = "";
	}
	console.log(".." + cid);
	var node = {x: pos[0], y: pos[1], term: label, rootnode:root, num:offset+1, nodeid:nodeid++, shrunk:false, ratio:1, rippler:R, termtype:"", conceptID:cid, color:color, category:"", childCount: 0};

	nodes.push(node);
	links.push({source:root, target:node, path:false});
	root.childCount += 1;
}


function buildGrid(cid, root) {
	d3.json("http://rack90.cs.drexel.edu/ahn/mcdapi_proxy.php?cid=" + cid, function(json) {
	// d3.json("./sample.json", function(json) {
		
		console.log(">>>" + json.associations.count);
		console.log(">>>" + json.siblings.count);
		console.log(">>>" + json.children.count);

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


function _search2(d) {
	var rootx = d.x;
	var rooty = d.y;
	var query = d.conceptID;

	d3.json("http://rack90.cs.drexel.edu/ahn/mcdapi_proxy.php?cid=" + query, function(json) {

		if(json == null) {
			alert("No search result found.");
			return;
		}
		d.rootnode = null;
		shrink(d, d.x, d.y); 
		
		var root = d;
		
		var assc = json.associations.concepts.slice(0, Math.min(json.associations.concepts.length - 1, 2));
		var sibl = json.siblings.concepts.slice(0, Math.min(json.siblings.concepts.length - 1, 2));
		var chld = json.children.concepts.slice(0, Math.min(json.children.concepts.length - 1, 2));
		var prnt = [json.parent];

		var subTreeCount = assc.length + sibl.length + chld.length + prnt.length;

		addSubTree(prnt, root, "parent", nodeColors[3], subTreeCount);
		addSubTree(assc, root, "association", nodeColors[0], subTreeCount);
		addSubTree(sibl, root, "sibling", nodeColors[1], subTreeCount);
		addSubTree(chld, root, "child", nodeColors[2], subTreeCount);

		paint(rootx, rooty, R);
	});
}

function paint(rootx, rooty, R) {

	vis.append("svg:circle")
		.attr("id", "ripple")
		.attr("cx", rootx)
		.attr("cy", rooty)
		.attr("r", 1e-6)
		.style("stroke", d3.scale.category20c(1))
		.style("stroke-width", 3)
		.style("stroke-opacity", 1)
		.style("fill", "none")
		.attr("transform", "translate("+xyz[0]+","+xyz[1]+")")
		.transition()
		.duration(RIPPLE_DURATION)
		.ease(Math.sqrt)
		.attr("r", R)
		.style("stroke-opacity", 0.1);
		// .remove();	
	
	vis.selectAll("line.link")
		.data(links)
		.enter().insert("svg:line", "circle.node")
		.attr("class", "link")
		.attr("x1", function(d) { return d.source.x; })
		.attr("y1", function(d) { return d.source.y; })
		.attr("x2", function(d) { return d.target.x; })
		.attr("y2", function(d) { return d.target.y; })
		.style("opacity", 1e-6)
		.attr("transform", "translate("+xyz[0]+","+xyz[1]+")")
		.transition()
		.duration(1000)
		.ease(Math.sqrt)	
		.style("opacity", 1);

	$(".node-contextmenu .contextmenu-item")
		.on("click", function() {
			var box = this.getAttribute( 'data-box' );
			// alert(box);
			console.log(box + " " + selectedQueryTerm);
			
			// d3.select(".node-contextmenu")
			// .style('display', 'none');
			
			hide_contextmenu();
			
			document.getElementById("querybox-" + box).value = selectedQueryTerm;
		})

	vis.selectAll(".node")
		.data(nodes)
		.enter().insert("svg:g")
		.attr("class", "node")
		.on("click", function(d) {search2(d)})
		.on("mouseover", function(d) {
			if(d.category == "association") {
				d3.select(".node-relation")
				.text("Relation type: " + d.relationType)
		      	.style('position', 'absolute')
		      	.style('left', d3.event.pageX + "px")
		      	.style('top', d3.event.pageY + "px");
				$(".node-relation").innerHTML = "HA";
				$(".node-relation").show(0);
			}
		})
		.on("mouseout", function(d) {
			d3.select(".node-relation")
			$(".node-relation").hide(0);
		})
		.on("contextmenu", function(data, index) {
			d3.select(".node-contextmenu")
	      	.style('position', 'absolute')
	      	.style('left', d3.event.pageX + "px")
	      	.style('top', d3.event.pageY + "px");
			$(".node-contextmenu").show(200);
			
			d3.event.preventDefault();
			selectedQueryTerm = data.term;
		})
		.call(function(n, d) {

			// label
			n.insert("svg:text")
			.attr("x", function(d) {return d.x;})
			.attr("y", function(d) {return d.y - 12;})
			.text(function(d) {return d.term})
			.attr("font-size", 32)
			.style("opacity", 1e-6)
			.attr("transform", "translate("+xyz[0]+","+xyz[1]+")")
			.transition()
			.duration(1000)
			.ease(Math.sqrt)	
			.style("opacity", 1);

			// node shape
			n.insert("svg:circle")
			.attr("r", 10)
			.attr("cx", function(d) {return d.x;})
			.attr("cy", function(d) {return d.y;})
			.style("opacity", 1e-6)
			.style("fill", function(d) {return d.color})
			// .style("fill", function(d) {return nodeColors[d.num % 6]})
			// .style("fill", function(d) {
			// 
			// 	var typelist = ["parent", "relation", "variance", "child"];
			// 	var typeindex = typelist.indexOf(d.termtype);
			// 	if(typeindex == -1)
			// 		return nodeColors[4];
			// 	else
			// 		return nodeColors[typeindex];
			// 	
				// return nodeColors[d.num % 6]
			// })
			.attr("transform", "translate("+xyz[0]+","+xyz[1]+")")
			.transition()
			.duration(1000)
			.ease(Math.sqrt)	
			.style("opacity", 1)
			.attr("r", function(d) {return Math.max(5, d.ratio * 10)});
			})
			
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


function shrink(d, dx, dy) {
	// 
	// vis.selectAll(".node circle, .node text, #ripple, .link")
	// .transition()
	// .duration(500)
	// .attr("transform", function(d) {var ts = "translate(" + (500 - dx) + "," + (400 - dy) + ")"; console.log(ts); return ts })
	// .call(function(dd) {
	// 	console.log("here");
	// 	console.log("..>T..." + dx);
	// });
	// 
	
	// node shape 
	vis.selectAll(".node circle")
	.filter(function(ds, i) {
		var ans = true;

		// do not shrink
		if(ds.nodeid == d.nodeid) // || ds.rootnode == null || ds.shrunk)
			ans = false;
			
		return ans;
	})
	.call(function (d) {
		d.shrunk = true;
	})
	.transition()
	.duration(SHRINK_DURATION)
	.filter(function(d) {
		if(d.rootnode != null)
			return true;
	})
	.style("opacity", 0.5)
	.attr("cx", function(d) {return newpos(d)[0]})
	.attr("cy", function(d) {return newpos(d)[1]});

	// node label
	vis.selectAll(".node text")
	.filter(function(ds, i) {
		var ans = true;

		if(ds.nodeid == d.nodeid || ds.shunk || ds.rootnode == null) 
			ans = false;
			
		return ans;
	})
	.transition()
	.duration(SHRINK_DURATION)
	.style("font-size", 8)
	.style("opacity", 0.5)
	.attr("x", function(d) {return newpos(d)[0]})
	.attr("y", function(d) {return newpos(d)[1] - 8});

	// path
	vis.selectAll('.link')
	.filter(function(dl, i) {
		if(dl.source == d || dl.target == d || dl.path) {
			dl.path = true;
			return true;
		}
		return false;
	})
	.style("stroke", "#666")
	.style("stroke-width", 5);

	// what does this do exactly???
	//
	vis.selectAll(".node circle")
	.filter(function(ds, i) {if(ds.rootnode == null && ds != d) return true})
	.each(function(ds) {
		var oldr = ds.rippler;
		ds.rippler = oldr*0.8;
	});	

	
	// links
	vis.selectAll('line.link')
	.filter(function(dl, i) {
		if(dl.source == d || dl.target == d || dl.path) {
			return false;
		}
		
		
		// dl.x1 = newpos(dl.source)[0];
		//  		dl.y1 = newpos(dl.target)[1];
		// dl.x2 = newpos(dl.source)[0];
		// dl.y2 = newpos(dl.target)[1];
		return true;
	})
	// .each(function (dl) {
	// 	dl.x1 = 0;
	// 	dl.y1 = 0;
	// })
	// .transition()
	// .attr("x1", 10)
	// .attr("y1", 10)
	// .attr("x2", 10)
	// .attr("y2", 10)
	.attr("x2", function(dl) {return newpos(dl.target)[0]})
	.attr("y2", function(dl) {return newpos(dl.target)[1]})
	;
	

	// .duration(SHRINK_DURATION)
	//todo:fix by embeding new pos to the nodes (?)
	// .attr("x1", function(d) {return newpos(d.source)[0]})
	// .attr("y1", function(d) {return newpos(d.source)[1]})
	// .attr("x2", function(d) {return newpos(d.target)[0]})
	// .attr("y2", function(d) {return newpos(d.target)[1]});
	// .attr("x1", 0)
	// .attr("y1", 0)
	// .attr("x2", 0)
	// .attr("y2", 0);
;
	// ripple
	vis.selectAll("#ripple")
	.transition()
	.duration(SHRINK_DURATION)
	.attr("r", function() {
		var oldr = d3.select(this).attr("r");
		return oldr * 0.8;
	})
	.style("stroke-opacity", 0.05);


}
function enter_pressed(e){
var keycode;
if (window.event) keycode = window.event.keyCode; 
else if (e) keycode = e.which; 
else return false; 
return (keycode == 13); 
}


function openpubmed() {

http://www.ncbi.nlm.nih.gov/pubmed?cmd=Search&db=pubmed&term=(photographs+by+picture-taking+technique)(aerial+photographs)(astrophotographs)&dispmax=40

	var redv = document.getElementById("querybox-red").value;
	var greenv = document.getElementById("querybox-green").value;
	var bluev = document.getElementById("querybox-blue").value;

	var q = "http://www.ncbi.nlm.nih.gov/pubmed?cmd=Search&db=pubmed&dispmax=40&term=" + "("+redv+")("+greenv+")("+bluev+")";
	
	console.log(q);
	window.open(q);
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