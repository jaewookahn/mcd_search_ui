#!/usr/bin/python

import solr
import cgi
import cgitb
import MySQLdb
import json
cgitb.enable()

def clean_unicode(s):
	try:
		s = unicode(s, 'utf-8')
	except:
		s = unicode(s, 'ascii')
	else:
		s = s.encode('ascii', 'ignore')
	return s

form = cgi.FieldStorage()
if form.has_key("query"):
	query = form['query'].value
else:
	query = """No Query"""

print "Content-type: text/html\n"

qtemp = []
for qstr in query.split("\n"):
	qstr = qstr.strip()
	qstr = clean_unicode(qstr)
	if len(qstr) == 0:
		continue
	qstr = 'MATERIAL:"' + qstr + '"'
	qtemp.append(qstr)

import string

q = string.join(qtemp, " OR ")

s = solr.SolrConnection('http://research.ischool.drexel.edu:8080/solr4/artstor')
res = s.query(q, fields = ["TITLE", "MATERIAL", "id", "SUBJECT", 'CREATOR'], rows=500)


con = MySQLdb.connect('localhost', 'jahn', 'wodnr405', 'mcd')
cur = con.cursor()

output = {}

output['numrows'] = res.numFound
output['query'] = query
output['records'] = []

field_meta = [('id', 'ID'), ('score', "Score"), ('TITLE', 'Title'), ('MATERIAL', 'Material'), ('SUBJECT', 'subject'), ('CREATOR', 'creator')]

for rec in res:

	q = "select objectId, imageUrl from artstor_thumbnail_urls where objectid = '%s'" % rec['id']
	cur.execute(q)
	
	th_url = "http://md2.artstor.net/%s" % cur.fetchone()[1]

	temp = {}
	temp['url'] = th_url
	for field in field_meta:
		try:
			val = rec[field[0]]
		except:
			val = ""
		
		if isinstance(val, list):
			temp[field[1]] = string.join(val, " | ")
		else:
			try:
				temp[field[1]] = str(val)
			except:
				temp[field[1]] = val
		
	output['records'].append(temp)
	
print json.dumps(output)

