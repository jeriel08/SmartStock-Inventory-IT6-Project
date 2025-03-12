# pos.py (or main.py)
import customtkinter as ctk
from tkinter import ttk
import mysql.connector
from decimal import Decimal
from datetime import datetime
from login import load_session, clear_session, get_logged_in_user, show_login
from account import AccountPage  # Import the AccountPage class

# Global grand total
global_total = 0.0

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

class POSApp(ctk.CTk):
    def __init__(self):
        super().__init__()

        self.geometry(f"{self.winfo_screenwidth()}x{self.winfo_screenheight()}")
        self.state('zoomed')
        self.title("SmartStock POS")

        ctk.set_appearance_mode("system")

        self.main_frame = ctk.CTkFrame(self, corner_radius=0)
        self.main_frame.pack(fill="both", expand=True)

        self.cart_items = {}
        self.order_frame_visible = False
        self.current_category = "All Products"
        self.current_page = "main"  # Track current page ("main" or "account")

        # Check session and initialize UI
        if load_session():
            self.create_navigation_bar()
            self.create_product_frame()
            self.create_cart_frame()
            self.account_page = None  # Initialize account page as None
        else:
            self.destroy()  # Close empty POS window
            show_login(self.start_pos)  # Show login window

    def start_pos(self):
        # Reinitialize the POS window after login
        self.__init__()
        self.mainloop()

    def create_navigation_bar(self):
        self.nav_frame = ctk.CTkFrame(self.main_frame, width=200, corner_radius=0)
        self.nav_frame.pack(side="left", fill="y")

        title_label = ctk.CTkLabel(self.nav_frame, text="Categories", font=ctk.CTkFont(size=18, weight="bold"))
        title_label.pack(pady=(10, 5))

        all_products_btn = ctk.CTkButton(
            self.nav_frame, text="All Products", command=lambda: self.category_selected("All Products"),
            width=180, height=40, corner_radius=5, font=ctk.CTkFont(size=14)
        )
        all_products_btn.pack(pady=3, padx=10)

        try:
            db = connect_db()
            if db is None:
                return
            cursor = db.cursor()
            cursor.execute("SELECT Name FROM categories")
            categories = cursor.fetchall()
            for category in categories:
                category_name = category[0]
                btn = ctk.CTkButton(
                    self.nav_frame, text=category_name, command=lambda c=category_name: self.category_selected(c),
                    width=180, height=40, corner_radius=5, font=ctk.CTkFont(size=14)
                )
                btn.pack(pady=3, padx=10)
            db.close()
        except mysql.connector.Error as err:
            print(f"Error fetching categories: {err}")

        # Toggle button for Account/Back to POS
        self.toggle_btn = ctk.CTkButton(
            self.nav_frame, text="Account", command=self.toggle_page,
            width=180, height=40, corner_radius=5, font=ctk.CTkFont(size=14)
        )
        self.toggle_btn.pack(side="bottom", pady=10, padx=10)

        # Logout button removed from here

    def show_account_page(self):
        # Hide the main content (product frame and cart frame)
        self.product_frame.pack_forget()
        self.right_container.pack_forget()

        # Create and show the account page if not already created
        if not self.account_page:
            self.account_page = AccountPage(self.main_frame, self.show_main_page)
        self.account_page.pack(fill="both", expand=True, padx=10, pady=10)

        # Update button text
        self.toggle_btn.configure(text="Back to POS")
        self.current_page = "account"

    def show_main_page(self):
        # Hide the account page and show the main content
        if self.account_page:
            self.account_page.pack_forget()
        self.product_frame.pack(side="left", fill="both", expand=True, padx=10, pady=10)
        self.right_container.pack(side="right", fill="y", padx=(0, 10), pady=10)

        # Update button text
        self.toggle_btn.configure(text="Account")
        self.current_page = "main"

    def toggle_page(self):
        if self.current_page == "main":
            self.show_account_page()
        else:
            self.show_main_page()

    def logout(self):  # Kept for reference, but moved to AccountPage
        clear_session()
        print("User logged out")
        self.destroy()
        show_login(self.start_pos)

    def create_product_frame(self):
        self.product_frame = ctk.CTkFrame(self.main_frame, corner_radius=0)
        self.product_frame.pack(side="left", fill="both", expand=True, padx=10, pady=10)

        self.search_var = ctk.StringVar()
        self.search_bar = ctk.CTkEntry(
            self.product_frame, textvariable=self.search_var, placeholder_text="Search products...",
            width=250, height=35, corner_radius=5, font=ctk.CTkFont(size=14)
        )
        self.search_bar.grid(row=0, column=0, columnspan=3, pady=(0, 5), padx=5, sticky="ew")
        self.search_var.trace("w", self.search_products)

        self.display_products("All Products")

    def create_cart_frame(self):
        self.right_container = ctk.CTkFrame(self.main_frame, corner_radius=0)
        self.right_container.pack(side="right", fill="y", padx=(0, 10), pady=10)

        self.cart_frame = ctk.CTkFrame(self.right_container, width=400, corner_radius=0)
        self.cart_frame.pack(side="right", fill="y")
        self.cart_frame.pack_propagate(False)

        self.grand_total_label = ctk.CTkLabel(
            self.cart_frame, text="Grand Total\n₱00.00", font=ctk.CTkFont(size=30, weight="bold"),
            justify="center", width=400, wraplength=400
        )
        self.grand_total_label.pack(pady=(10, 5), fill="x", padx=15)

        self.middle_frame = ctk.CTkFrame(self.cart_frame)
        self.middle_frame.pack(fill="x", expand=False, pady=(5, 5), padx=15)

        self.cart_items_frame = ctk.CTkFrame(self.cart_frame)
        self.cart_items_frame.pack(fill="both", expand=True, pady=5)

        self.place_order_btn = ctk.CTkButton(
            self.cart_frame, text="Place Order", command=self.toggle_order_frame,
            width=350, height=60, corner_radius=5, font=ctk.CTkFont(size=20, weight="bold")
        )
        self.place_order_btn.pack(pady=(0, 10), fill="x", padx=15)

        self.order_frame = ctk.CTkFrame(self.right_container, width=400, corner_radius=0)
        self.create_order_frame_content()

    def create_order_frame_content(self):
        self.place_order_frame = ctk.CTkFrame(self.order_frame, corner_radius=0)
        self.place_order_frame.pack(fill="both", expand=True, padx=10, pady=10)

        order_details_label = ctk.CTkLabel(self.place_order_frame, text="Order Details", font=ctk.CTkFont(size=30, weight="bold"))
        order_details_label.pack(pady=(0, 10))

        name_label = ctk.CTkLabel(self.place_order_frame, text="Name", font=ctk.CTkFont(size=16))
        name_label.pack(pady=(0, 3))
        self.name_var = ctk.StringVar()
        name_entry = ctk.CTkEntry(self.place_order_frame, textvariable=self.name_var, height=20, font=ctk.CTkFont(size=16))
        name_entry.pack(fill="x", pady=(0, 10))

        phone_label = ctk.CTkLabel(self.place_order_frame, text="Phone", font=ctk.CTkFont(size=16))
        phone_label.pack(pady=(0, 3))
        self.phone_var = ctk.StringVar()
        phone_entry = ctk.CTkEntry(self.place_order_frame, textvariable=self.phone_var, height=20, font=ctk.CTkFont(size=16))
        phone_entry.pack(fill="x", pady=(0, 10))

        amount_received_label = ctk.CTkLabel(self.place_order_frame, text="Amount Received", font=ctk.CTkFont(size=16))
        amount_received_label.pack(pady=(0, 3))
        self.amount_received_var = ctk.StringVar()
        self.amount_received_var.trace("w", self.update_change)
        amount_received_entry = ctk.CTkEntry(self.place_order_frame, textvariable=self.amount_received_var, height=20, font=ctk.CTkFont(size=16))
        amount_received_entry.pack(fill="x", pady=(0, 10))

        change_label = ctk.CTkLabel(self.place_order_frame, text="Change", font=ctk.CTkFont(size=16))
        change_label.pack(pady=(0, 3))
        self.change_var = ctk.StringVar(value="₱0.00")
        change_entry = ctk.CTkEntry(self.place_order_frame, textvariable=self.change_var, height=25, font=ctk.CTkFont(size=14), state="disabled")
        change_entry.pack(fill="x", pady=(0, 10))

        purchase_btn = ctk.CTkButton(
            self.place_order_frame, text="Purchase", command=self.process_purchase,
            width=350, height=40, corner_radius=5, font=ctk.CTkFont(size=16, weight="bold")
        )
        purchase_btn.pack(pady=(10, 0))

    def toggle_order_frame(self):
        if not self.cart_items:
            print("Cart is empty, cannot proceed")
            temp_label = ctk.CTkLabel(self.cart_frame, text="Cart is empty!", font=ctk.CTkFont(size=16), text_color="red")
            temp_label.pack(pady=5)
            self.after(2000, lambda: temp_label.destroy())
            return

        if not self.order_frame_visible:
            self.order_frame.pack(side="left", fill="y")
            self.order_frame_visible = True
            self.update_change()
        else:
            self.order_frame.pack_forget()
            self.order_frame_visible = False

    def process_purchase(self):
        global grand_total
        name = self.name_var.get().strip()
        phone = self.phone_var.get().strip()
        try:
            amount_received = float(self.amount_received_var.get() or 0)
            change = float(self.change_var.get().replace("₱", "") or 0)
        except ValueError:
            print("Error: Invalid amount received or change value!")
            return

        if not name or not phone:
            print("Error: Name and Phone Number are required!")
            return
        if amount_received < grand_total:
            print(f"Error: Insufficient amount received! Received: ₱{amount_received:.2f}, Required: ₱{grand_total:.2f}")
            return

        db = connect_db()
        if db is None:
            print("Error: Could not connect to database!")
            return

        cursor = None
        try:
            cursor = db.cursor()

            query_customer = """
                INSERT INTO customers (Name, PhoneNumber, Created_By, Updated_By)
                VALUES (%s, %s, %s, %s)
            """
            values_customer = (name, phone, get_logged_in_user(), get_logged_in_user())
            print(f"Executing customer query: {query_customer % values_customer}")
            cursor.execute(query_customer, values_customer)
            db.commit()
            customer_id = cursor.lastrowid
            print(f"Customer added successfully: CustomerID={customer_id}, Name={name}, Phone={phone}")

            order_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            order_status = "Paid"
            delivery = 0
            created_by = get_logged_in_user()
            updated_by = get_logged_in_user()

            query_order = """
                INSERT INTO orders (CustomerID, Date, Total, AmountReceived, `Change`, Status, Delivery, Created_at, Created_by, Updated_at, Updated_by)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """
            values_order = (customer_id, order_date, grand_total, amount_received, change, order_status, delivery, order_date, created_by, order_date, updated_by)
            print(f"Executing order query: {query_order % values_order}")
            cursor.execute(query_order, values_order)
            db.commit()
            order_id = cursor.lastrowid
            if order_id:
                print(f"✅ Order inserted with ID: {order_id}")
            else:
                print("Error: Order ID not retrieved after insertion!")

            order_items = [
                {"product_id": pid, "quantity": item["quantity"], "price": item["price"]}
                for pid, item in self.cart_items.items()
            ]
            if not order_items:
                print("⚠ No items in cart to add to orderline!")
            else:
                print(f"✅ Adding {len(order_items)} items to orderline")

            query_orderline = """
                INSERT INTO orderline (OrderID, ProductID, Quantity, Price)
                VALUES (%s, %s, %s, %s)
            """
            for item in order_items:
                values_orderline = (order_id, item["product_id"], item["quantity"], item["price"])
                print(f"Executing orderline query: {query_orderline % values_orderline}")
                cursor.execute(query_orderline, values_orderline)
            db.commit()
            print(f"✅ Order {order_id} recorded with {len(order_items)} items!")

            self.cart_items.clear()
            self.update_cart_display()
            self.order_frame.pack_forget()
            self.order_frame_visible = False
            self.name_var.set("")
            self.phone_var.set("")
            self.amount_received_var.set("")
            self.change_var.set("₱0.00")

        except mysql.connector.Error as err:
            print(f"Database error during purchase processing: {err}")
            if db:
                db.rollback()
        except Exception as e:
            print(f"Unexpected error during purchase processing: {e}")
            if db:
                db.rollback()
        finally:
            if cursor:
                cursor.close()
            if db and db.is_connected():
                db.close()
                print("Database connection closed.")

    def update_change(self, *args):
        try:
            amount_received = float(self.amount_received_var.get() or 0)
            change = amount_received - grand_total
            self.change_var.set(f"₱{max(0, change):.2f}")
        except ValueError:
            self.change_var.set("₱0.00")

    def display_products(self, category, search_query=""):
        for widget in self.product_frame.winfo_children():
            if widget != self.search_bar:
                widget.destroy()

        db = connect_db()
        if db is None:
            return

        try:
            cursor = db.cursor()
            if category == "All Products":
                query = "SELECT ProductID, Name, Price FROM products WHERE Status = 'In Stock' AND Name LIKE %s ORDER BY Name ASC"
                cursor.execute(query, (f"%{search_query}%",))
            else:
                query = "SELECT p.ProductID, p.Name, p.Price FROM products p JOIN categories c ON p.CategoryID = c.CategoryID WHERE c.Name = %s AND p.Status = 'In Stock' AND p.Name LIKE %s ORDER BY p.Name ASC"
                cursor.execute(query, (category, f"%{search_query}%"))
            products = cursor.fetchall()

            if not products:
                no_items_label = ctk.CTkLabel(self.product_frame, text="No products found", font=ctk.CTkFont(size=14))
                no_items_label.grid(row=1, column=0, pady=5, padx=5)
            else:
                for i, product in enumerate(products):
                    product_id, product_name, product_price = product
                    btn_text = f"{product_name}\n₱{float(product_price):.2f}"
                    item_button = ctk.CTkButton(
                        self.product_frame, text=btn_text, command=lambda pid=product_id, name=product_name, price=float(product_price): self.product_selected(pid, name, price),
                        height=50, corner_radius=5, border_width=1, border_color="lightblue", text_color="black", font=ctk.CTkFont(size=14)
                    )
                    row_num = (i // 3) + 1
                    col_num = i % 3
                    item_button.grid(row=row_num, column=col_num, pady=3, padx=3, sticky="ew")

                for col in range(3):
                    self.product_frame.grid_columnconfigure(col, weight=1)

        except mysql.connector.Error as err:
            print(f"Error fetching products: {err}")
        finally:
            if 'cursor' in locals():
                cursor.close()
            if db.is_connected():
                db.close()

    def search_products(self, *args):
        search_query = self.search_var.get().strip()
        self.display_products(self.current_category, search_query)

    def category_selected(self, category):
        self.current_category = category
        search_query = self.search_var.get().strip()
        self.display_products(category, search_query)

    def product_selected(self, product_id, product_name, price):
        if product_id in self.cart_items:
            self.cart_items[product_id]['quantity'] += 1
        else:
            self.cart_items[product_id] = {'name': product_name, 'price': float(price), 'quantity': 1}
        self.update_cart_display()

    def update_cart_display(self):
        global grand_total
        for widget in self.middle_frame.winfo_children():
            widget.destroy()

        grand_total = 0.0
        for i, (product_id, item) in enumerate(self.cart_items.items(), 1):
            name = item['name']
            price = float(item['price'])
            quantity = item['quantity']
            total = price * quantity
            grand_total += total

            item_label = ctk.CTkLabel(self.middle_frame, text=f"{i}. {name}", font=ctk.CTkFont(size=16), anchor="w")
            item_label.grid(row=i, column=0, padx=3, pady=1, sticky="w")

            total_label = ctk.CTkLabel(self.middle_frame, text=f"₱{total:.2f}", font=ctk.CTkFont(size=14))
            total_label.grid(row=i, column=1, padx=3, pady=1)

            minus_btn = ctk.CTkButton(self.middle_frame, text="-", width=30, height=25, command=lambda pid=product_id: self.adjust_quantity(pid, -1))
            minus_btn.grid(row=i, column=2, padx=1, pady=1)

            quantity_var = ctk.StringVar(value=str(quantity))
            quantity_entry = ctk.CTkEntry(self.middle_frame, textvariable=quantity_var, width=40, height=25, justify="center", font=ctk.CTkFont(size=14))
            quantity_entry.grid(row=i, column=3, padx=1, pady=1)
            quantity_entry.bind("<Return>", lambda event, pid=product_id: self.update_quantity_from_entry(pid, quantity_var))

            plus_btn = ctk.CTkButton(self.middle_frame, text="+", width=30, height=25, command=lambda pid=product_id: self.adjust_quantity(pid, 1))
            plus_btn.grid(row=i, column=4, padx=1, pady=1)

            delete_btn = ctk.CTkButton(self.middle_frame, text="x", width=30, height=25, command=lambda pid=product_id: self.remove_from_cart(pid), font=ctk.CTkFont(size=14))
            delete_btn.grid(row=i, column=5, padx=3, pady=1)

        self.grand_total_label.configure(text=f"Grand Total\n₱{grand_total:.2f}")
        if self.order_frame_visible:
            self.update_change()

        self.middle_frame.grid_columnconfigure(0, weight=1)
        self.middle_frame.grid_columnconfigure((1, 2, 3, 4, 5), weight=0)

    def adjust_quantity(self, product_id, change):
        if product_id in self.cart_items:
            new_quantity = self.cart_items[product_id]['quantity'] + change
            if new_quantity > 0:
                self.cart_items[product_id]['quantity'] = new_quantity
            else:
                del self.cart_items[product_id]
            self.update_cart_display()

    def update_quantity_from_entry(self, product_id, quantity_var):
        try:
            new_quantity = int(quantity_var.get())
            if new_quantity > 0:
                self.cart_items[product_id]['quantity'] = new_quantity
            else:
                del self.cart_items[product_id]
            self.update_cart_display()
        except ValueError:
            if product_id in self.cart_items:
                quantity_var.set(str(self.cart_items[product_id]['quantity']))

    def remove_from_cart(self, product_id):
        if product_id in self.cart_items:
            del self.cart_items[product_id]
            self.update_cart_display()

if __name__ == "__main__":
    app = POSApp()
    app.mainloop()