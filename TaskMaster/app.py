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
        database='taskmaster_db'
    )
    return conn

# Hash password function
def hash_password(password):
    return hashlib.sha256(password.encode()).hexdigest()

# Home route
@app.route('/')
def home():
    return render_template('home.html')

# Login route
@app.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        email = request.form['email']
        password = request.form['password']
        hashed_password = hash_password(password)

        conn = get_db_connection()
        cursor = conn.cursor(dictionary=True)
        cursor.execute("SELECT * FROM Users WHERE email=%s AND password=%s", (email, hashed_password))
        user = cursor.fetchone()
        conn.close()

        if user:
            session['user_id'] = user['id']
            return redirect(url_for('dashboard'))
        else:
            return "Invalid credentials!"

    return render_template('login.html')

# Signup route
@app.route('/signup', methods=['GET', 'POST'])
def signup():
    if request.method == 'POST':
        name = request.form['name']
        email = request.form['email']
        password = request.form['password']
        hashed_password = hash_password(password)

        conn = get_db_connection()
        cursor = conn.cursor()
        cursor.execute("INSERT INTO Users (name, email, password) VALUES (%s, %s, %s)", (name, email, hashed_password))
        conn.commit()
        conn.close()

        return redirect(url_for('login'))

    return render_template('signup.html')

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
