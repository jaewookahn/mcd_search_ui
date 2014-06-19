#!/usr/bin/python

import solr
import cgi
import cgitb
import MySQLdb

cgitb.enable()

form = cgi.FieldStorage()
if form.has_key("query"):
	query = form['query'].value
else:
	query = """watercolor on silk
watercolor and ink on silk
gouache on fabric
gouache on cotton
gouache on linen
gouache on silk
tempera on Cloth
tempera on linen
tempera on canvas over wood
casein on canvas
acrylic and watercolor on linen
oil and watercolor on canvas
acrylic and tempera on canvas
acrylic and tempera on linen	
"""
print "Content-type: text/html\n"

print """
<html>
<head>
<title>MCD Query Expansion: Search against Solr</title>
</head>

"""

q = """
MATERIAL:"watercolor on silk" OR
MATERIAL:"watercolor and ink on silk" OR
MATERIAL:"gouache on fabric" OR
MATERIAL:"gouache on cotton" OR
MATERIAL:"gouache on linen" OR
MATERIAL:"gouache on silk" OR
MATERIAL:"tempera on Cloth" OR
MATERIAL:"tempera on linen" OR
MATERIAL:"tempera on canvas over wood" OR
MATERIAL:"casein on canvas" OR
MATERIAL:"acrylic and watercolor on linen" OR
MATERIAL:"oil and watercolor on canvas" OR
MATERIAL:"acrylic and tempera on canvas" OR
MATERIAL:"acrylic and tempera on linen"
"""

print "<div><b>Expanded query terms (one term per line)</b></div>"
print "<form method='post' action='index.cgi'>"
print "<textarea name='query' cols=100 rows=10 style='background: lightYellow'>"
print query
print "</textarea><br>"
print "<input type='submit'>"
print "</form>"
qtemp = []
for qstr in query.split("\n"):
	qstr = qstr.strip()
	if len(qstr) == 0:
		continue
	qstr = 'MATERIAL:"' + qstr + '"'
	qtemp.append(qstr)

import string

q = string.join(qtemp, " OR ")

print "<div><b>Solr Query</b></div>"

print "<div style='width:600px; border: solid 1px black; font-family: Arial; font-size: 12px; margin: 5px; background: lightYellow'>"
print q
print "</div>"


s = solr.SolrConnection('http://research.ischool.drexel.edu:8080/solr4/artstor')
res = s.query(q, fields = ["TITLE", "MATERIAL", "id"], rows=100)


con = MySQLdb.connect('localhost', 'jahn', 'wodnr405', 'mcd')
cur = con.cursor()

print "<div><b>Search results: %d records found</b></div>" % res.numFound

field_meta = [('id', 'ID'), ('score', "Score"), ('TITLE', 'Title'), ('MATERIAL', 'Material')]
print "<table style='border:1 solid black; border-collapse:collapse; background:white; font-family:Arial; font-size:11px'>"
print "<tr>"
print "<td></td>"
for field in field_meta:
	print "<td style='font-weight:bold; border:1 solid black; border-collapse:collapse' >%s</td>" % field[1]
print "</tr>"
for rec in res:
	# print "<div style='border: 1 black solid; margin: 5px; padding: 2px'>"
	print "<tr>"
	print "<td>"
	q = "select * from artstor_thumbnail_urls where objectid = '%s'" % rec['id']
	cur.execute(q)
	th_url = cur.fetchone()[1]
	print "<img src='http://md2.artstor.net/%s'>" % (th_url)
	print "</td>"
	for field in field_meta:
		# print "<div style='background:#e0ffff; font-family: Arial; font-size: 12px'>"
		print "<td style='border:1 solid black; border-collapse:collapse' >"
		# print field[1] + ": "
		print str(rec[field[0]])
		print "</td>"
		# print "</div>"
	# print "<div>Score:" + str(rec['score']) + "</div>"
	# print "<div>Title:" + str(rec['TITLE']) + "</div>"
	# print "<div>Material:" + str(rec['MATERIAL']) + "</div>"
	# print "</div>"
	print "</tr>"
print "</table>"