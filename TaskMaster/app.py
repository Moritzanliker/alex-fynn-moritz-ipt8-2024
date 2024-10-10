from flask import Flask, render_template, request, redirect, url_for, session
import mysql.connector
import hashlib

app = Flask(__name__)
app.secret_key = 'your_secret_key'

# Database connection
def get_db_connection():
    conn = mysql.connector.connect(
        host='localhost',
        user='your_user',
        password='your_password',
        database='taskDB'
    )
    return conn

# Hash password function
def hash_password(password):
    return hashlib.sha256(password.encode()).hexdigest()

# Home route
@app.route('/')
def home():
    return render_template('index.html')

# Home route
@app.route('/auth', methods=['POST'])
def auth():
    # Get form data from JS (login/register)
    username = request.form.get('username')
    password = request.form.get('password')
    action = request.form.get('action')
    
    if action == 'login':
        if username in taskDB and taskDB[username] == password:
            return jsonify({"success": True, "message": "Login successful!"})
        else:
            return jsonify({"success": False, "message": "Invalid username or password"})
    
    elif action == 'register':
        if username in users_db:
            return jsonify({"success": False, "message": "Username already taken"})
        else:
            # Store new user in database
            users_db[username] = password
            return jsonify({"success": True, "message": "Registration successful!"})
    
    return jsonify({"success": False, "message": "Invalid action"})

if __name__ == '__main__':
    app.run(debug=True)

# Dashboard route
@app.route('/dashboard')
def dashboard():
    if 'user_id' not in session:
        return redirect(url_for('login'))

    user_id = session['user_id']
    conn = get_db_connection()
    cursor = conn.cursor(dictionary=True)

    cursor.execute("SELECT * FROM Projects WHERE user_id=%s", (user_id,))
    projects = cursor.fetchall()

    cursor.execute("SELECT * FROM Tasks WHERE project_id IN (SELECT id FROM Projects WHERE user_id=%s)", (user_id,))
    tasks = cursor.fetchall()

    conn.close()

    return render_template('dashboard.html', projects=projects, tasks=tasks)

if __name__ == '__main__':
    app.run(debug=True)
