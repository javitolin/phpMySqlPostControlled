import requests
import json

url = 'http://localhost/androidConnection/connection.php'
params = {'dbName': 'androidhive', 'dbUser' : 'root', 'dbPass' : 'pass', 'Action': 'remove',
			'tableName':'products',
			'rColumn' : 'price', 'rClause' : 90,
			 'insertData': "{\"name\":\"p2\",\"price\":90, \"description\":\"holadsadsa\"}"}
response = requests.post(url, params)
print(response.text)