# account.py
import customtkinter as ctk
import mysql.connector
import matplotlib.pyplot as plt
from matplotlib.backends.backend_tkagg import FigureCanvasTkAgg
from login import get_logged_in_user, clear_session
from datetime import datetime


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


class AccountPage(ctk.CTkFrame):
    def __init__(self, parent, show_main_callback):
        super().__init__(parent, corner_radius=0)

        self.show_main_callback = show_main_callback  # Callback to show the main page
        self.parent = parent  # Reference to POSApp instance for logout

        # Split the account page into top and bottom frames with adjusted height
        self.top_frame = ctk.CTkFrame(self, corner_radius=0, height=200)
        self.top_frame.pack(side="top", fill="x", padx=10, pady=10)
        self.top_frame.pack_propagate(False)

        self.bottom_frame = ctk.CTkFrame(self, corner_radius=0)
        self.bottom_frame.pack(side="bottom", fill="both", expand=True, padx=10, pady=10)

        # Populate the top frame with employee details and logout button
        self.display_employee_details()

        # Add "Logout" button to top-right corner of top_frame
        self.logout_btn = ctk.CTkButton(
            self.top_frame, text="Logout", command=self.logout,
            width=180, height=40, corner_radius=5, font=ctk.CTkFont(size=14, weight="bold"), fg_color="red"
        )
        self.logout_btn.pack(side="right", padx=10, pady=10)

        # Display sales graph in bottom_frame
        self.display_sales_graph()

    def display_employee_details(self):
        # Get the logged-in user's EmployeeID
        logged_in_employee_id = get_logged_in_user()

        if logged_in_employee_id is None:
            error_label = ctk.CTkLabel(
                self.top_frame, text="No user logged in!",
                font=ctk.CTkFont(size=20, weight="bold"), text_color="red",
                justify="center"
            )
            error_label.pack(expand=True)
            return

        # Fetch employee details from the database using EmployeeID
        db = connect_db()
        if db is None:
            error_label = ctk.CTkLabel(
                self.top_frame, text="Database connection failed!",
                font=ctk.CTkFont(size=20, weight="bold"), text_color="red",
                justify="center"
            )
            error_label.pack(expand=True)
            return

        try:
            cursor = db.cursor()
            query = """
                SELECT LastName, FirstName, EmployeeID, Username, Role 
                FROM employees 
                WHERE EmployeeID = %s AND Status = 'Active'
            """
            cursor.execute(query, (logged_in_employee_id,))
            employee = cursor.fetchone()

            if employee:
                last_name, first_name, employee_id, username, role = employee
                full_name = f"{last_name}, {first_name}"

                # Display LastName, FirstName (centered)
                name_label = ctk.CTkLabel(
                    self.top_frame, text=full_name,
                    font=ctk.CTkFont(size=30, weight="bold"),
                    justify="center"
                )
                name_label.pack(pady=(20, 0))

                # Display Role (centered)
                role_label = ctk.CTkLabel(
                    self.top_frame, text=role,
                    font=ctk.CTkFont(size=20),
                    justify="center"
                )
                role_label.pack(pady=(5, 5))

                # Estimate line width based on name length
                char_count = len(full_name)
                line_width = char_count * 15 + 20  # 15px per char + 20px buffer

                # Add a blue horizontal line with dynamic width
                line = ctk.CTkFrame(self.top_frame, width=line_width, height=2, fg_color="#1F6AA5")
                line.pack(pady=5)

                # Display Username (centered)
                username_label = ctk.CTkLabel(
                    self.top_frame, text=f"{username}",
                    font=ctk.CTkFont(size=20, weight="bold"),
                    justify="center"
                )
                username_label.pack(pady=5)

                # Display EmployeeID (centered)
                id_label = ctk.CTkLabel(
                    self.top_frame, text=f"ID: {employee_id}",
                    font=ctk.CTkFont(size=20, weight="bold"),
                    justify="center"
                )
                id_label.pack(pady=5)
            else:
                not_found_label = ctk.CTkLabel(
                    self.top_frame, text="Employee not found!",
                    font=ctk.CTkFont(size=20, weight="bold"), text_color="red",
                    justify="center"
                )
                not_found_label.pack(expand=True)

        except mysql.connector.Error as err:
            print(f"Error fetching employee details: {err}")
            error_label = ctk.CTkLabel(
                self.top_frame, text="Error loading details!",
                font=ctk.CTkFont(size=20, weight="bold"), text_color="red",
                justify="center"
            )
            error_label.pack(expand=True)
        finally:
            if 'cursor' in locals():
                cursor.close()
            if db.is_connected():
                db.close()

    def display_sales_graph(self):
        # Add "Total Sales" header
        header_label = ctk.CTkLabel(
            self.bottom_frame, text="Total Sales",
            font=ctk.CTkFont(size=24, weight="bold"),
            justify="center"
        )
        header_label.pack(pady=(10, 5))

        # Fetch sales data from the database
        db = connect_db()
        if db is None:
            error_label = ctk.CTkLabel(
                self.bottom_frame, text="Database connection failed!",
                font=ctk.CTkFont(size=20, weight="bold"), text_color="red",
                justify="center"
            )
            error_label.pack(expand=True)
            return

        try:
            cursor = db.cursor()
            # Query to get total sales per day
            query = """
                SELECT DATE(Date) AS SaleDate, SUM(Total) AS DailyTotal
                FROM orders
                WHERE Status = 'Paid'
                GROUP BY DATE(Date)
                ORDER BY SaleDate ASC
            """
            cursor.execute(query)
            sales_data = cursor.fetchall()

            if not sales_data:
                no_data_label = ctk.CTkLabel(
                    self.bottom_frame, text="No sales data available!",
                    font=ctk.CTkFont(size=20), text_color="gray",
                    justify="center"
                )
                no_data_label.pack(expand=True)
                return

            # Extract dates and totals
            dates = [row[0].strftime("%Y-%m-%d") for row in sales_data]
            totals = [float(row[1]) for row in sales_data]

            # Create bar graph using Matplotlib
            fig, ax = plt.subplots(figsize=(10, 5))
            ax.bar(dates, totals, color="#1F6AA5")
            ax.set_xlabel("Date")
            ax.set_ylabel("Total Sales (₱)")
            ax.set_title("")
            plt.xticks(rotation=45, ha="right")

            # Adjust layout to prevent label cutoff
            plt.tight_layout()

            # Embed the plot in the Tkinter window
            canvas = FigureCanvasTkAgg(fig, master=self.bottom_frame)
            canvas.draw()
            canvas.get_tk_widget().pack(fill="both", expand=True, padx=10, pady=10)

        except mysql.connector.Error as err:
            print(f"Error fetching sales data: {err}")
            error_label = ctk.CTkLabel(
                self.bottom_frame, text="Error loading sales data!",
                font=ctk.CTkFont(size=20, weight="bold"), text_color="red",
                justify="center"
            )
            error_label.pack(expand=True)
        finally:
            if 'cursor' in locals():
                cursor.close()
            if db.is_connected():
                db.close()

    def logout(self):
        clear_session()
        print("User logged out")
        self.parent.destroy()  # Close POS window
        from login import show_login  # Import here to avoid circular import
        show_login(self.parent.start_pos)  # Show login window again

    def back_to_main(self):
        self.pack_forget()  # Hide the account page
        self.show_main_callback()  # Show the main page