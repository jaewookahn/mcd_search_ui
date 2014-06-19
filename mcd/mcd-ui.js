var tempRoot;

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
		// .on("click", function(d) {search2(d)})
		.on("click", function(d) {
			tempRoot = d;
			var list = "<ol class='concept-list-container concept-list'>";
			for(i = 0; i < d.tree.length; i++) {
				list += "<li class='concept-list-item' onClick='search3(\""+ d.tree[i].conceptID +"\", \""+ d.tree[i].term+"\")'>" + d.tree[i].term + "</li> ";
			}
			list += "</ol>";

			d3.select(".concept-list-container")
	      	.style('position', 'absolute')
	      	.style('left', d3.event.pageX + "px")
	      	.style('top', d3.event.pageY + "px")
			.html(list);
			$(".concept-list-container").show(0);
		})
		// .on("mouseout", function(d) {
		// 	$(".concept-list").hide(0);
		// })
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