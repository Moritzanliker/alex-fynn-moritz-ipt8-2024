import mysql.connector
from mysql.connector import Error

def connect_and_log():
    try:
        # Establish the connection to the MySQL database
        connection = mysql.connector.connect(
            host='127.0.0.1',  # Use the appropriate host
            port= 3308,
            user='root',     # Replace with your MySQL user
            password='root',  # Replace with your MySQL password
            database='taskDB'    # The database you want to connect to
        )
        
        if connection.is_connected():
            print("Successfully connected to the database.")

            # Create a cursor object
            cursor = connection.cursor()

            # Fetch and log data from 'user' table
            cursor.execute("SELECT * FROM user;")
            users = cursor.fetchall()
            print("\nUser Table:")
            for user in users:
                print(user)

            # Fetch and log data from 'task' table
            cursor.execute("SELECT * FROM task;")
            tasks = cursor.fetchall()
            print("\nTask Table:")
            for task in tasks:
                print(task)

    except Error as e:
        print(f"Error while connecting to MySQL: {e}")
    
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed.")

# Call the function
connect_and_log()
