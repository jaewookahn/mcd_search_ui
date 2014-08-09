#!/usr/bin/python

import solr
import cgi
import cgitb
import MySQLdb
import json
cgitb.enable()

print "Content-type: text/html\n"

print """<html>
<head>
<body>
"""

form = cgi.FieldStorage()

if form.has_key("q1"):
	q1 = form['q1'].value
else:
    q1 = """watercolor
watercolors
water color
watercolor paint"""


if form.has_key("q2"):
	q2 = form['q2'].value
else:
    q2 = """cloth
cloths
fabric"""

if form.has_key("q3"):
	q3 = form['q3'].value
else:
    q3 = ""

q1 = q1.strip()
q2 = q2.strip()
q3 = q3.strip()

print """
<form method='post' action='manual_search.cgi'>
<table>
<tr>
<td>
Component 1<br>
<textarea name='q1' cols=30 rows=10 style='background: lightYellow'>
%s
</textarea>
</td>
<td>
Component 2<br>
<textarea name='q2' cols=30 rows=10 style='background: lightYellow'>
%s
</textarea>
</td>

<td>
Component 3<br>
<textarea name='q3' cols=30 rows=10 style='background: lightYellow'>
%s
</textarea>
</td>
</table>
<input type='submit'>
</form>
""" % (q1, q2, q3)



import string

components = []

for q in (q1, q2, q3):
    if q.strip() == "":
        continue
    temp = []
    for s in q.split("\n"):
        s = s.strip()
        if len(s.split(" ")) == 1:
            s = "*%s*" % s
        else:
            s = '"*%s*"' % s
        temp.append("MATERIAL:%s" % s)
    
    components.append("(" + string.join(temp, " OR ") + ")")

solrq = string.join(components, " AND ")
max_rows = 9999

print "<b>Query</b>:"
print solrq
s = solr.SolrConnection('http://research.ischool.drexel.edu:8080/solr4/artstor')
res = s.query(solrq, fields = ["TITLE", "MATERIAL", "id", "SUBJECT", 'CREATOR'], rows=int(max_rows))

print """<table border=1>
<tr>
    <td>ID</td><td>Title</td><td>Material</td>
</tr>
"""
for r in res:
    print "<tr><td>%s</td><td>%s</td><td>%s</td></tr>" % (r['id'], r['TITLE'], r['MATERIAL'])

print "</table>"

