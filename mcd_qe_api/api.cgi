#!/usr/bin/python

print "Content-type: text/html\n"
import cgi, cgitb; cgitb.enable()
import MySQLdb
import string
import json
import solr

con = MySQLdb.connect('localhost', 'jahn', 'wodnr405', 'mcd')
cur = con.cursor()
def qe(fs):
	cids = fs['cids'].value.split()
	res = []
	init = True
	for cid in cids:
		q = "select distinct `FKOS-Concept` cid from MappingComponent mc, MappingMaster mm where `TKOS-Concept` = '%s' and mc.`MappingID` = mm.`MappingID`" % cid
		N = cur.execute(q)
		temp = set()
		for tup in cur.fetchall():
			temp.add(str(tup[0]))
		if init:
			init = False
			res = temp
		else:
			res = res.union(temp)

	if len(res) == 0:
		print json.dumps({"numrows":0, "query":fs['cids'].value})
		return
		
	int_cids = string.join(list(res), ',')
	# query = "select StringId, stringtext from StringTable where StringID in (%s)" % int_cids
	# N = cur.execute(query)
	# final = {'records':[]}
	# for row in cur:
	# 	temp = {}
	# 	temp['cid'] = row[0]
	# 	temp['string'] = row[1]
	# 	final['records'].append(temp)
	# final['numrows'] = N
	# final['cids'] = cids
	# print json.dumps(final)

	query = """

select ust.EntityInstanceID as conceptID, st.StringText from `mcd`.`UniversalSourceTable` as ust
 left join `mcd`.`UniversalRelationshipTable` as urt1 on ust.EntityInstanceID = urt1.entityInstance1
 left join `mcd`.`UniversalRelationshipTable` as urt2 on urt1.entityInstance2 = urt2.entityInstance1
 left join `mcd`.`StringTable` as st on urt2.entityInstance2=st.StringID
 where ust.KOSID = 16906853 and urt1.RelTypeID=19 and urt2.RelTypeID=20
 and ust.EntityInstanceID in (%s)
	"""  % int_cids	
	
	artstor_strings = {}

	N1 = cur.execute(query)
	for row in cur:
		concept_id, concept_string = row
		artstor_strings[concept_id] = concept_string
		
	query = """
select `FKOS-Concept`, `Facet`, `FKOS-Substring` from `MappingMaster` mm, `MappingComponent` mc
where mm.`MappingID` = mc.`MappingID`
and `FKOS-Concept` in (%s);
	
	""" % int_cids
	
	facets = {}
	N2 = cur.execute(query)
	for row in cur:
		artstor_concept, facet, artstor_substring = row
		# artstor_concept = str(artstor_concept)
		tup = (facet, artstor_substring)
		if facets.has_key(artstor_concept):
			facets[artstor_concept].append(tup)
		else:
			facets[artstor_concept] = [tup]

	final = {'records':[]}
	final['query'] = fs['cids'].value
	final['numrows'] = N1
	# final['cids'] = cids
	
	for artstor_concept in facets.keys():
		record = {}
		artstor_string = artstor_strings[artstor_concept]
		record['artstor_conceptid'] = artstor_concept
		record['artstor_string'] = artstor_string
		record['facets'] = facets[artstor_concept]
		
		
		final['records'].append(record)
	
	print json.dumps(final)
		
def searchArtstor(fs):
	if not fs.has_key('query'):
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
acrylic and tempera on linen"""
	else:
		query = fs['query'].value

	qtemp = []
	for qstr in query.split("\n"):
		qstr = qstr.strip()
		if len(qstr) == 0:
			continue
		qstr = 'MATERIAL:"' + qstr + '"'
		qtemp.append(qstr)

	q = string.join(qtemp, " OR ")

	s = solr.SolrConnection('http://research.ischool.drexel.edu:8080/solr4/artstor')
	res = s.query(q, fields = ["TITLE", "MATERIAL", "id"], rows=100000)

	con = MySQLdb.connect('localhost', 'jahn', 'wodnr405', 'mcd')
	cur = con.cursor()


	final = {'records':[]}
	final['numrows'] = res.numFound
	final['query'] = query
	
	field_meta = [('id', 'id'), ('score', "score"), ('TITLE', 'title'), ('MATERIAL', 'material')]

	for i, rec in enumerate(res):
		q = "select * from artstor_thumbnail_urls where objectid = '%s'" % rec['id']
		cur.execute(q)
		th_url = cur.fetchone()[1]

		temp = {}
		for field in field_meta:
			temp[field[1]] = rec[field[0]]
			temp['thumbnail_url'] = th_url
		final['records'].append(temp)
	print json.dumps(final)
	
if __name__ == '__main__':
	fs = cgi.FieldStorage()
	mode = fs['mode'].value
	if mode == 'qe':
		qe(fs)
	if mode == 'searchartstor':
		searchArtstor(fs)
