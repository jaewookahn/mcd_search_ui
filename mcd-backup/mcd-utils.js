function openpubmed() {

http://www.ncbi.nlm.nih.gov/pubmed?cmd=Search&db=pubmed&term=(photographs+by+picture-taking+technique)(aerial+photographs)(astrophotographs)&dispmax=40

	var redv = document.getElementById("querybox-red").value;
	var greenv = document.getElementById("querybox-green").value;
	var bluev = document.getElementById("querybox-blue").value;

	var q = "http://www.ncbi.nlm.nih.gov/pubmed?cmd=Search&db=pubmed&dispmax=40&term=" + "("+redv+")("+greenv+")("+bluev+")";
	
	console.log(q);
	window.open(q);
}

function enter_pressed(e){
var keycode;
if (window.event) keycode = window.event.keyCode; 
else if (e) keycode = e.which; 
else return false; 
return (keycode == 13); 
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