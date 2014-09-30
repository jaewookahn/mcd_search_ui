var areaw = 800;
var areah = 400;

var m = [20, 20, 20, 120],
    w = 400 - m[1] - m[3],
    h = 400 - m[0] - m[2],
    i = 0,
    root;

var tree = d3.layout.tree()
    .size([h, w]);

var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

var xyz = [0,0,1];

var vis;
var rect;

function activate_panning() {
	rect = vis.append("svg:rect")
		.attr("width", areaw)
		.attr("height", areah)
	    .attr("transform", "translate(-" + m[3] + ",-" + m[0] + ")")
		.attr("class", "base_rect")
		.call(d3.behavior.zoom().on("zoom", function() {
			xyz[0]=d3.event.translate[0];
			xyz[1]=d3.event.translate[1];
	
			vis.selectAll(".node circle, .node text, #ripple, .link")
			.attr("transform", "translate("+xyz[0]+","+xyz[1]+")");
		}))
		;
	
}

var oldcanvasid = "";

function setActiveVis(v, canvasid) {
	
	vis = v;

	for(i = 0; i <= canvascounter; i++) {
		var e = document.getElementById("viscanvas").className = "unselected";
		
		var e = document.getElementById("viscanvas" + i);
		if(e && canvascounter != i) {
			e.className = "unselected";
			console.log("UNSELECT" + i);
		}
	}

	// if(oldcanvasid != "")
	// 	document.getElementById(oldcanvasid).className = "unselected";
		
	
		
	document.getElementById(canvasid).className = "selected";
	oldcanvasid = canvasid;
}

function init(canvasid) {
	var vislocal = d3.select("#" + canvasid).append("svg:svg")
	.attr("width", areaw)
	.attr("height", areah)
	.append("svg:g")
	.attr("transform", "translate(" + m[3] + "," + m[0] + ")")
	.attr("canvasid", canvasid)
	.on("click", function(d) { setActiveVis(vislocal, canvasid) });
	;
	
	vis = vislocal;
	
	xyz[0] = m[3];
	xyz[1] = m[0];

	activate_panning();
}

function hide_contextmenu() {
	
}

function cut_tree(tree) {
	var temp = new Array();
	var j = 0;
	for(i = 0; i < tree.children.length; i++) {
		if(tree.children[i].hierarchy == "sibling" && j <= 3)
			temp[j++] = tree.children[i];
		if(tree.children[i].hierarchy == "query")
			temp[j++] = tree.children[i];
	}
	tree.children = temp;
}


function search(cid) {
	console.log("..." + cid);
	// d3.json("http://mbostock.github.com/d3/talk/20111018/flare.json", function(json) {
	d3.json("http://rack90.cs.drexel.edu/ahn/mcdtree_proxy.php?cid=" + cid, function(json) {
		// d3.json("http://rack90.cs.drexel.edu:8080/AAT1/GetHierarchy?cid=ga_300022973", function(json) {
			console.log("here");
		console.log(json);
	// ctree = cut_tree(json);
			
			
	  root = json;
	  root.x0 = h / 2;
	  root.y0 = 0;

	console.log("Search. ");
	console.log(root);

	  function toggleAll(d) {
	    if (d.children) {
	      d.children.forEach(toggleAll);
	      toggle(d);
	    }
	  }

	  // Initialize the display to show a few nodes.
	  // root.children.forEach(toggleAll);
	  toggle(root.children[1]);
	  // toggle(root.children[1].children[2]);
	  // toggle(root.children[9]);
	  // toggle(root.children[9].children[0]);

	  update(root);

	vis.selectAll(".node text")
		.attr("class", "normal_text");

	vis.selectAll(".node text")
	.filter(function(d, i) {
		if(d.conceptID == cid) {
			console.log("HIGHLIGHT: " + d.conceptID + " " + cid);
			return true;
		}
		else {
			return false;
		}
	})
	.attr("class", "highlighted_text");

	
	vis.selectAll(".node circle")
	.filter(function(d, i) {
		if(d.childrenCount > 0)
			return true;
		else
			return false;
	})
	.style("stroke-width", 5);

	
	});
}

function pan(dist) {
	xyz[0] = xyz[0] - dist;
	console.log("*** PAN: " + xyz[0]);

	// vis
	// .transition()
	// .duration(500)	
	// .attr("transform", "translate("+ xyz[0] + "," + xyz[1] + ")");
	vis.selectAll(".node circle, .node text, #ripple, .link")
	.transition()
	.duration(500)
	.attr("transform", "translate("+xyz[0]+","+xyz[1]+")");
	
}

function expand(d) {
	console.log("Clicked on Node:");
	console.log(d.conceptID);
	
	var isRoot = false;
	
	if(root.conceptID == d.conceptID) // current node == root
		isRoot = true;	

	// if(!isRoot && d.children.length > 0)
	// 	return;

	d3.json("http://rack90.cs.drexel.edu/ahn/mcdtree_proxy.php?cid=" + d.conceptID, function(json) {
	
	console.log("Expand JSON output:");
	console.log(json);
	
	if(isRoot) {
		var temp2 = root;
		
		root = {term:json.term, conceptID:json.conceptID};
		root.children = new Array();
		root.children[0] = temp2;
		
		var j = 1;
		for(i = 0; i < Math.min(4, json.children.length); i++) {
			if(json.children[i].hierarchy == "sibling" && json.children[i].conceptID != root.conceptID) {
				root.children[j++] = {term:json.children[i].term, conceptID:json.children[i].conceptID}
			}
		}
		
		update(root);
		
	}
	else {
		d.children = new Array();
		
		for(i = 0; i < json.children.length; i++) {
			if(json.children[i].conceptID == d.conceptID) {
				d.children = json.children[i].children;
				console.log("EXPSELECT:" + d.conceptID + " " + i);
				console.log(d.conceptID);
				console.log(json.children[i]);
				console.log(d.children);
				break;
			}
		}
		
		d.children = json.children[json.children.length-1].children;
		// d.children.splice(0, 3);
		console.log("*** Exp X coord: " + d.x);
		if(d.x > 290) {
			pan(100);
			pan(0);
		}

		update(root);
	}
	
	
	vis.selectAll(".node text")
		.attr("class", "normal_text");

	vis.selectAll(".node text")
	.filter(function(dd, i) {
		if(d.conceptID == dd.conceptID)
			return true;
		else
			return false;
	})
	.attr("class", "highlighted_text");
	
});
}

function set_related(cid, x, y) {
	d3.json("http://rack90.cs.drexel.edu/ahn/mcdapi_proxy.php?cid=" + cid, function(json) {
		var list = "<ol>"
		var assc = json.associations.concepts;
		console.log(assc);
		for(i = 0; i < assc.length; i++) {
			list += "<li>" + assc[i].term + "</li>\n";
		}
		list += "</ol>"
		listContent = list;

		d3.select(".concept-list-container")
      	.style('position', 'absolute')
      	.style('left', x + 80 + "px")
      	.style('top', y + "px")
		.html(listContent);
		$(".concept-list-container").show(0);

	});
}

// function buttonclicked() {
// 
// 	d3.json("http://rack90.cs.drexel.edu:8080/AAT1/GetHierarchy?cid=ga_300022973", function(json) {
// 	
// 		// root.children = new Array();
// 		root.children[0].children = new Array();
// 		root.children[0].children[0] = json;
// 	
// 		root.x0 = h / 2;
// 		root.y0 = 0;
// 		update(root);
// });
// 	
// }

function buttonclicked() {

	d3.json("http://mbostock.github.com/d3/talk/20111018/flare.json", function(json) {
	  // var temp = root.children[3];
	var temp2 = root;
	  // var index = parseInt(document.getElementById("num").value);
	  // root.children[root.children.length] = json.children[1].children[2];
	
	  root = json.children[9].children[0].children[0];
	// console.log(root);
	// console.log(json.children[9].children[0]);
	
	
	   root.children = new Array();
	  root.children[0] = temp2;
	
	  root.x0 = h / 2;
	  root.y0 = 0;
	  update(root);
});
	
}

var listContent;

function show_related(cid, term) {
	// console.log("Mouseover CID:" + cid);
	d3.json("http://rack90.cs.drexel.edu/ahn/mcdapi_proxy.php?cid=" + cid, function(json) {
		// console.log(json);
		var list = "<strong><span class='selected_term'>" + term + "</span> " + json.associations.concepts.length + " associated concepts</strong>";
		// console.log("+++");
		// console.log(json);
		list += "<ol>"
		var assc = json.associations.concepts;
		// console.log(assc);
		for(i = 0; i < assc.length; i++) {
			// console.log(assc[i]);
			list += "<li><span style=\"cursor: pointer\" onClick=\"search('"+assc[i].conceptID+"')\">" + assc[i].term + " ( <span class='rtype'>" + assc[i].relationType + "</span> )</span></li>\n";
		}
		list += "</ol>"

		document.getElementById("related_box").innerHTML = list;

	});
}

function update(source) {
  var duration = d3.event && d3.event.altKey ? 5000 : 500;

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse();

  // Normalize for fixed-depth.
  nodes.forEach(function(d) { d.y = d.depth * 180});

  // Update the nodes…
  var node = vis.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter any new nodes at the parent's previous position.
  var nodeEnter = node.enter().append("svg:g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
	.on("mouseover", function(d) {show_related(d.conceptID, d.term)})
      // .on("click", function(d) { toggle(d); update(d); });
      .on("click", function(d) { expand(d) });

	vis.selectAll('.node')
		.on("contextmenu", function(d) {
			set_related(d.conceptID, d3.event.pageX, d3.event.pageY);
			d3.event.preventDefault();
			console.log("#"+d.childrenCount);
		})


  nodeEnter.append("svg:circle")
      .attr("r", 1e-6)
      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

  nodeEnter.append("svg:text")
      .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.term; })
      .style("fill-opacity", 1e-6);

  // Transition nodes to their new position.
  var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

  nodeUpdate.select("circle")
      .attr("r", 4.5)
      .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

  nodeUpdate.select("text")
      .style("fill-opacity", 1);

  // Transition exiting nodes to the parent's new position.
  var nodeExit = node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .remove();

  nodeExit.select("circle")
      .attr("r", 1e-6);

  nodeExit.select("text")
      .style("fill-opacity", 1e-6);

  // Update the links…
  var link = vis.selectAll("path.link")
      .data(tree.links(nodes), function(d) { return d.target.id; });

  // Enter any new links at the parent's previous position.
  link.enter().insert("svg:path", "g")
      .attr("class", "link")
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      })
    .transition()
      .duration(duration)
      .attr("d", diagonal);

  // Transition links to their new position.
  link.transition()
      .duration(duration)
      .attr("d", diagonal);

  	vis.selectAll(".node circle, .node text, #ripple, .link")
  	.attr("transform", "translate("+xyz[0]+","+xyz[1]+")");

  	vis.selectAll("path.link")
  	.attr("transform", "translate("+xyz[0]+","+xyz[1]+")");


  // Transition exiting nodes to the parent's new position.
  link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
javascript
      })
      .remove();

  // Stash the old positions for transition.
  nodes.forEach(function(d) {
    d.x0 = d.x;
    d.y0 = d.y;
  });


	// vis.selectAll(".node circle, .node text, #ripple, .link")
	// .attr("transform", "translate(1,1)");
	
}

// Toggle children.
function toggle(d) {
  if (d.children) {
    d._children = d.children;
    d.children = null;
  } else {
    d.children = d._children;
    d._children = null;
  }
}
