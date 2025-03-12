import customtkinter as ctk
import mysql.connector
import bcrypt
import json
import os

# Global login state
logged_in_user = None
SESSION_FILE = "session.json"

# Database connection function
def connect_db():
    try:
        return mysql.connector.connect(
            host="localhost",
            user="root",  # Replace with your MySQL username
            password="",  # Replace with your MySQL password
            database="smartstock_inventory"
        )
    except mysql.connector.Error as err:
        print(f"Database connection failed: {err}")
        return None

# Session management functions
def load_session():
    global logged_in_user
    if os.path.exists(SESSION_FILE):
        with open(SESSION_FILE, 'r') as f:
            data = json.load(f)
            logged_in_user = data.get('employee_id')
            print(f"Session loaded: EmployeeID={logged_in_user}")
    return logged_in_user is not None

def save_session(employee_id):
    with open(SESSION_FILE, 'w') as f:
        json.dump({'employee_id': employee_id}, f)
    print(f"Session saved: EmployeeID={employee_id}")

def clear_session():
    global logged_in_user
    if os.path.exists(SESSION_FILE):
        os.remove(SESSION_FILE)
    logged_in_user = None
    print("Session cleared")

def get_logged_in_user():
    return logged_in_user

class LoginApp(ctk.CTk):
    def __init__(self, on_login_success):
        super().__init__()

        self.geometry("300x800")  # Standard 300x800 window
        self.title("SmartStock POS - Login")
        # Default window behavior: movable, with minimize (-) and close (x) buttons

        ctk.set_appearance_mode("system")

        self.on_login_success = on_login_success  # Callback to switch to POS UI

        # Main frame
        self.main_frame = ctk.CTkFrame(self, corner_radius=0)
        self.main_frame.pack(fill="both", expand=True, padx=20, pady=20)

        # Logo placeholder at the top
        self.logo_frame = ctk.CTkFrame(self.main_frame, height=150, corner_radius=0)
        self.logo_frame.pack(fill="x", pady=(0, 20))
        placeholder_label = ctk.CTkLabel(self.logo_frame, text="Logo Here", font=ctk.CTkFont(size=24))
        placeholder_label.place(relx=0.5, rely=0.5, anchor="center")

        # Login form
        self.create_login_frame()

    def create_login_frame(self):
        login_label = ctk.CTkLabel(self.main_frame, text="Login", font=ctk.CTkFont(size=20, weight="bold"))
        login_label.pack(pady=(0, 20))

        username_label = ctk.CTkLabel(self.main_frame, text="Username", font=ctk.CTkFont(size=14))
        username_label.pack(pady=(0, 5))
        self.username_var = ctk.StringVar(value="sampleemp1")  # Default for testing
        username_entry = ctk.CTkEntry(self.main_frame, textvariable=self.username_var, width=250, font=ctk.CTkFont(size=14))
        username_entry.pack(pady=(0, 20))

        password_label = ctk.CTkLabel(self.main_frame, text="Password", font=ctk.CTkFont(size=14))
        password_label.pack(pady=(0, 5))
        self.password_var = ctk.StringVar(value="password")  # Default for testing
        password_entry = ctk.CTkEntry(self.main_frame, textvariable=self.password_var, show="*", width=250, font=ctk.CTkFont(size=14))
        password_entry.pack(pady=(0, 20))

        login_btn = ctk.CTkButton(
            self.main_frame, text="Login", command=self.process_login,
            width=250, height=40, corner_radius=5, font=ctk.CTkFont(size=14, weight="bold")
        )
        login_btn.pack(pady=20)

        self.error_label = ctk.CTkLabel(self.main_frame, text="", font=ctk.CTkFont(size=12), text_color="red")
        self.error_label.pack(pady=10)

    def process_login(self):
        global logged_in_user
        username = self.username_var.get().strip()
        password = self.password_var.get().strip()

        if not username or not password:
            self.error_label.configure(text="Username and password are required!")
            return

        db = connect_db()
        if db is None:
            self.error_label.configure(text="Database connection failed!")
            return

        try:
            cursor = db.cursor()
            query = "SELECT EmployeeID, Password FROM employees WHERE Username = %s AND Status = 'Active'"
            cursor.execute(query, (username,))
            result = cursor.fetchone()

            if result:
                employee_id, hashed_password = result
                if bcrypt.checkpw(password.encode('utf-8'), hashed_password.encode('utf-8')):
                    logged_in_user = employee_id
                    save_session(employee_id)
                    print(f"Login successful: EmployeeID={employee_id}, Username={username}")
                    self.destroy()  # Close login window
                    self.on_login_success()  # Start POS UI
                else:
                    self.error_label.configure(text="Invalid password!")
            else:
                self.error_label.configure(text="Username not found!")
        except mysql.connector.Error as err:
            print(f"Login error: {err}")
            self.error_label.configure(text="Login failed due to database error!")
        finally:
            if 'cursor' in locals():
                cursor.close()
            if db.is_connected():
                db.close()

def show_login(on_login_success):
    app = LoginApp(on_login_success)
    app.mainloop()