import mysql.connector
print("Attempting to connect...")
conn = mysql.connector.connect(
    host='localhost',
    user='root',
    password='',
    database='smartstock_inventory'
)
print("Connected!")
conn.close()