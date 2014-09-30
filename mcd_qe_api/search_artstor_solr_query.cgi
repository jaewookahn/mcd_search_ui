#!/usr/bin/python

import solr
import cgi, os, re
import cgitb
import MySQLdb
import json
import string
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
	query = "*:*"

if len(query.strip()) == 0:
	query = "*:*"

max_rows = 500
image_size = "0"

if form.has_key("image_size"):
    image_size = form['image_size'].value

image_size_string = "size" + image_size


if form.has_key("max_rows"):
    max_rows = form['max_rows'].value

print "Content-type: text/html\n"

s = solr.SolrConnection('http://research.ischool.drexel.edu:8080/solr4/artstor')
res = s.query(query, fields = ["TITLE", "MATERIAL", "id", "SUBJECT", 'CREATOR'], rows=int(max_rows))

con = MySQLdb.connect('localhost', 'jahn', 'wodnr405', 'mcd')
cur = con.cursor()

output = {}

output['numrows'] = res.numFound

if query == "*:*":
	output['query'] = 'All'
else:
	output['query'] = query
output['records'] = []

field_meta = [('id', 'ID'), ('score', "Score"), ('TITLE', 'Title'), ('MATERIAL', 'Material'), ('SUBJECT', 'subject'), ('CREATOR', 'creator')]

for rec in res:

	q = "select objectId, imageUrl from artstor_thumbnail_urls where objectid = '%s'" % rec['id']
	cur.execute(q)
	
	try:
		th_url = "http://md2.artstor.net/%s" % cur.fetchone()[1]
	except:
		th_url = ""

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

        if temp.has_key("url"):
           temp['url'] = temp['url'].replace("size0", image_size_string) 


	output['records'].append(temp)
	
print json.dumps(output)

