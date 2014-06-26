#!/usr/bin/python

import solr
import cgi
import cgitb
import MySQLdb
import json
cgitb.enable()

form = cgi.FieldStorage()
if form.has_key("query"):
	query = form['query'].value
else:
	query = """No Query"""

print "Content-type: text/html\n"

qtemp = []
for qstr in query.split("\n"):
	qstr = qstr.strip()
	if len(qstr) == 0:
		continue
	qstr = 'MATERIAL:"' + qstr + '"'
	qtemp.append(qstr)

import string

q = string.join(qtemp, " OR ")

s = solr.SolrConnection('http://research.ischool.drexel.edu:8080/solr4/artstor')
res = s.query(q, fields = ["TITLE", "MATERIAL", "id"], rows=500)


con = MySQLdb.connect('localhost', 'jahn', 'wodnr405', 'mcd')
cur = con.cursor()

output = {}

output['numrows'] = res.numFound
output['query'] = query
output['records'] = []

field_meta = [('id', 'ID'), ('score', "Score"), ('TITLE', 'Title'), ('MATERIAL', 'Material')]

for rec in res:

	q = "select * from artstor_thumbnail_urls where objectid = '%s'" % rec['id']
	cur.execute(q)
	th_url = "http://md2.artstor.net/%s" %cur.fetchone()[1]

	temp = {}
	temp['url'] = th_url
	for field in field_meta:
		temp[field[1]] = str(rec[field[0]])
	output['records'].append(temp)
	
print json.dumps(output)

