# pos.py (or main.py)
import customtkinter as ctk
from tkinter import ttk
import mysql.connector
from decimal import Decimal
from datetime import datetime
from login import load_session, get_logged_in_user, show_login
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

        # Initialize UI (only called if session exists)
        self.create_navigation_bar()
        self.create_product_frame()
        self.create_cart_frame()
        self.account_page = None  # Initialize account page as None

    def start_pos(self):
        # Start the POS UI
        self.mainloop()

    def create_navigation_bar(self):
        self.nav_frame = ctk.CTkFrame(self.main_frame, width=196, corner_radius=0)
        self.nav_frame.pack(side="left", fill="y")

        title_label = ctk.CTkLabel(self.nav_frame, text="Categories", font=ctk.CTkFont(size=25, weight="bold"))
        title_label.pack(pady=(20, 20))

        all_products_btn = ctk.CTkButton(
            self.nav_frame, text="All Products", command=lambda: self.category_selected("All Products"),
            width=195, height=36, corner_radius=5, font=ctk.CTkFont(size=12)
        )
        all_products_btn.pack(pady=3, padx=6)

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
                    width=195, height=36, corner_radius=5, font=ctk.CTkFont(size=12)
                )
                btn.pack(pady=3, padx=6)
            db.close()
        except mysql.connector.Error as err:
            print(f"Error fetching categories: {err}")

        # Toggle button for Account/Back to POS
        self.toggle_btn = ctk.CTkButton(
            self.nav_frame, text="Account", command=self.toggle_page,
            width=176, height=36, corner_radius=5, font=ctk.CTkFont(size=15, weight="bold")
        )
        self.toggle_btn.pack(side="bottom", pady=6, padx=6)

    def show_account_page(self):
        # Hide the main content (product frame and cart frame)
        self.product_frame.pack_forget()
        self.right_container.pack_forget()

        # Create and show the account page if not already created
        if not self.account_page:
            self.account_page = AccountPage(self.main_frame, self.show_main_page)
        self.account_page.pack(fill="both", expand=True, padx=6, pady=6)

        # Update button text
        self.toggle_btn.configure(text="Back to POS")
        self.current_page = "account"

    def show_main_page(self):
        # Hide the account page and show the main content
        if self.account_page:
            self.account_page.pack_forget()
        self.product_frame.pack(side="left", fill="both", expand=True, padx=6, pady=6)
        self.right_container.pack(side="right", fill="y", padx=(0, 6), pady=6)

        # Update button text
        self.toggle_btn.configure(text="Account")
        self.current_page = "main"

    def toggle_page(self):
        if self.current_page == "main":
            self.show_account_page()
        else:
            self.show_main_page()

    def create_product_frame(self):
        self.product_frame = ctk.CTkFrame(self.main_frame, corner_radius=0)
        self.product_frame.pack(side="left", fill="both", expand=True, padx=6, pady=6)

        # Search bar with "Search Product" as placeholder in Arial 12
        self.search_var = ctk.StringVar()
        self.search_bar = ctk.CTkEntry(
            self.product_frame, textvariable=self.search_var, placeholder_text="Search Product",
            width=246, height=31, corner_radius=10, font=("Arial", 12), placeholder_text_color="grey"
        )
        self.search_bar.pack(pady=10)
        self.search_var.trace("w", self.search_products)

        # Subframe for products using grid
        self.products_subframe = ctk.CTkFrame(self.product_frame, fg_color="transparent")
        self.products_subframe.pack(fill="both", expand=True)

        self.display_products("All Products")

    def create_cart_frame(self):
        self.right_container = ctk.CTkFrame(self.main_frame, corner_radius=0)
        self.right_container.pack(side="right", fill="y", padx=(0, 6), pady=6)

        self.cart_frame = ctk.CTkFrame(self.right_container, width=396, corner_radius=0)
        self.cart_frame.pack(side="right", fill="y")
        self.cart_frame.pack_propagate(False)

        # Grand Total with round radius and extra space below
        self.grand_total_label = ctk.CTkLabel(
            self.cart_frame, text="Grand Total\n₱00.00", font=ctk.CTkFont(size=26, weight="bold"),
            justify="center", width=396, wraplength=396, corner_radius=10, fg_color="#D3D3D3"
        )
        self.grand_total_label.pack(pady=(6, 20), fill="x", padx=11)

        self.middle_frame = ctk.CTkFrame(self.cart_frame)
        self.middle_frame.pack(fill="x", expand=False, pady=(1, 1), padx=11)

        self.cart_items_frame = ctk.CTkFrame(self.cart_frame, fg_color="transparent")
        self.cart_items_frame.pack(fill="both", expand=True, pady=1)

        self.place_order_btn = ctk.CTkButton(
            self.cart_frame, text="Place Order", command=self.toggle_order_frame,
            width=346, height=56, corner_radius=5, font=ctk.CTkFont(size=16, weight="bold")
        )
        self.place_order_btn.pack(pady=(0, 6), fill="x", padx=11)

        # Place Order frame with round radius
        self.order_frame = ctk.CTkFrame(self.right_container, width=250, corner_radius=15, fg_color="#D3D3D3")
        self.order_frame.pack_propagate(False)
        self.create_order_frame_content()

    def create_order_frame_content(self):
        self.order_content_frame = ctk.CTkFrame(self.order_frame, corner_radius=15, fg_color="#D3D3D3")
        self.order_content_frame.pack(fill="both", expand=True, padx=3, pady=3)

        order_details_label = ctk.CTkLabel(self.order_content_frame, text="Order Details", font=ctk.CTkFont(size=26, weight="bold"))
        order_details_label.pack(pady=(20, 20))

        name_label = ctk.CTkLabel(self.order_content_frame, text="Name", font=ctk.CTkFont(size=15))
        name_label.pack(pady=(0, 0))
        self.name_var = ctk.StringVar()
        name_entry = ctk.CTkEntry(self.order_content_frame, textvariable=self.name_var, width=200, height=25, font=ctk.CTkFont(size=15))
        name_entry.pack(pady=(0, 3))

        phone_label = ctk.CTkLabel(self.order_content_frame, text="Phone", font=ctk.CTkFont(size=15))
        phone_label.pack(pady=(0, 0))
        self.phone_var = ctk.StringVar()
        phone_entry = ctk.CTkEntry(self.order_content_frame, textvariable=self.phone_var, width=200, height=25, font=ctk.CTkFont(size=15))
        phone_entry.pack(pady=(0, 20))

        amount_received_label = ctk.CTkLabel(self.order_content_frame, text="Amount Received", font=ctk.CTkFont(size=15))
        amount_received_label.pack(pady=(0, 0))
        self.amount_received_var = ctk.StringVar()
        self.amount_received_var.trace("w", self.update_change)
        amount_received_entry = ctk.CTkEntry(self.order_content_frame, textvariable=self.amount_received_var, width=200, height=25, font=ctk.CTkFont(size=20))
        amount_received_entry.pack(pady=(0, 3))

        change_label = ctk.CTkLabel(self.order_content_frame, text="Change", font=ctk.CTkFont(size=15))
        change_label.pack(pady=(0, 0))
        self.change_var = ctk.StringVar(value="₱0.00")
        change_entry = ctk.CTkEntry(self.order_content_frame, textvariable=self.change_var, width=200, height=25, font=ctk.CTkFont(size=20), state="disabled")
        change_entry.pack(pady=(0, 3))

        # Spacer with matching background
        spacer = ctk.CTkFrame(self.order_content_frame, height=1, fg_color="#D3D3D3")
        spacer.pack(expand=True, fill="both")

        purchase_btn = ctk.CTkButton(
            self.order_content_frame, text="Purchase", command=self.process_purchase,
            width=260, height=56, corner_radius=5, font=ctk.CTkFont(size=16, weight="bold")
        )
        purchase_btn.pack(pady=(3, 0))

    def toggle_order_frame(self):
        if not self.cart_items:
            self.show_message("Cart is Empty!", fg_color="red")
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
            self.show_message("Invalid Amount Entered!", fg_color="red")
            return

        if not name or not phone:
            self.show_message("Name and Phone Required!", fg_color="red")
            return
        if amount_received < grand_total:
            self.show_message(f"Insufficient Amount!\nReceived: ₱{amount_received:.2f}\nRequired: ₱{grand_total:.2f}", fg_color="red")
            return

        db = connect_db()
        if db is None:
            self.show_message("Database Connection Failed!", fg_color="red")
            return

        cursor = None
        try:
            cursor = db.cursor()

            query_customer = """
                INSERT INTO customers (Name, PhoneNumber, Created_By, Updated_By)
                VALUES (%s, %s, %s, %s)
            """
            values_customer = (name, phone, get_logged_in_user(), get_logged_in_user())
            cursor.execute(query_customer, values_customer)
            db.commit()
            customer_id = cursor.lastrowid

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
            cursor.execute(query_order, values_order)
            db.commit()
            order_id = cursor.lastrowid
            if not order_id:
                self.show_message("Order Creation Failed!", fg_color="red")
                return

            order_items = [
                {"product_id": pid, "quantity": item["quantity"], "price": item["price"]}
                for pid, item in self.cart_items.items()
            ]

            query_orderline = """
                INSERT INTO orderline (OrderID, ProductID, Quantity, Price)
                VALUES (%s, %s, %s, %s)
            """
            for item in order_items:
                values_orderline = (order_id, item["product_id"], item["quantity"], item["price"])
                cursor.execute(query_orderline, values_orderline)
            db.commit()

            # Clear cart and UI
            self.cart_items.clear()
            self.update_cart_display()
            self.order_frame.pack_forget()
            self.order_frame_visible = False
            self.name_var.set("")
            self.phone_var.set("")
            self.amount_received_var.set("")
            self.change_var.set("₱0.00")

            # Show success message
            self.show_message("Purchase Successful!", fg_color="green")

        except mysql.connector.Error as err:
            self.show_message(f"Database Error: {err}", fg_color="red")
            if db:
                db.rollback()
        except Exception as e:
            self.show_message(f"Error: {e}", fg_color="red")
            if db:
                db.rollback()
        finally:
            if cursor:
                cursor.close()
            if db and db.is_connected():
                db.close()

    def show_message(self, text, fg_color="green", duration=1000):
        message_label = ctk.CTkLabel(
            self, text=text, font=ctk.CTkFont(size=16, weight="bold"),
            text_color="white", fg_color=fg_color, corner_radius=10,
            width=300, height=40, justify="center"
        )
        message_label.place(relx=0.5, rely=0.5, anchor="center")
        self.after(duration, message_label.destroy)

    def update_change(self, *args):
        try:
            amount_received = float(self.amount_received_var.get() or 0)
            change = amount_received - grand_total
            self.change_var.set(f"₱{max(0, change):.2f}")
        except ValueError:
            self.change_var.set("₱0.00")

    def display_products(self, category, search_query=""):
        for widget in self.products_subframe.winfo_children():
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
                no_items_label = ctk.CTkLabel(self.products_subframe, text="No products found", font=ctk.CTkFont(size=10))
                no_items_label.grid(row=0, column=0, pady=1, padx=1)
            else:
                for i, product in enumerate(products):
                    product_id, product_name, product_price = product
                    btn_text = f"{product_name}\n₱{float(product_price):.2f}"
                    item_button = ctk.CTkButton(
                        self.products_subframe, text=btn_text, command=lambda pid=product_id, name=product_name, price=float(product_price): self.product_selected(pid, name, price),
                        height=46, corner_radius=5, border_width=1, border_color="lightblue", text_color="white", font=ctk.CTkFont(size=10)
                    )
                    row_num = (i // 3)
                    col_num = i % 3
                    item_button.grid(row=row_num, column=col_num, pady=3, padx=3, sticky="ew")

                for col in range(3):
                    self.products_subframe.grid_columnconfigure(col, weight=1)

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

            item_label = ctk.CTkLabel(self.middle_frame, text=f"{i}. {name}", font=ctk.CTkFont(size=12), anchor="w")
            item_label.grid(row=i, column=0, padx=3, pady=1, sticky="w")

            price_label = ctk.CTkLabel(self.middle_frame, text=f"₱{price:.2f}", font=ctk.CTkFont(size=10))
            price_label.grid(row=i, column=1, padx=3, pady=1)

            total_label = ctk.CTkLabel(self.middle_frame, text=f"₱{total:.2f}", font=ctk.CTkFont(size=10))
            total_label.grid(row=i, column=2, padx=3, pady=1)

            minus_btn = ctk.CTkButton(self.middle_frame, text="-", width=26, height=21, command=lambda pid=product_id: self.adjust_quantity(pid, -1))
            minus_btn.grid(row=i, column=3, padx=1, pady=1)

            quantity_var = ctk.StringVar(value=str(quantity))
            quantity_entry = ctk.CTkEntry(self.middle_frame, textvariable=quantity_var, width=36, height=21, justify="center", font=ctk.CTkFont(size=10))
            quantity_entry.grid(row=i, column=4, padx=1, pady=1)
            quantity_entry.bind("<Return>", lambda event, pid=product_id: self.update_quantity_from_entry(pid, quantity_var))

            plus_btn = ctk.CTkButton(self.middle_frame, text="+", width=26, height=21, command=lambda pid=product_id: self.adjust_quantity(pid, 1))
            plus_btn.grid(row=i, column=5, padx=1, pady=1)

            delete_btn = ctk.CTkButton(self.middle_frame, text="x", width=26, height=21, command=lambda pid=product_id: self.remove_from_cart(pid), font=ctk.CTkFont(size=10))
            delete_btn.grid(row=i, column=6, padx=3, pady=1)

        self.grand_total_label.configure(text=f"Grand Total\n₱{grand_total:.2f}")
        if self.order_frame_visible:
            self.update_change()

        self.middle_frame.grid_columnconfigure(0, weight=1)
        self.middle_frame.grid_columnconfigure((1, 2, 3, 4, 5, 6), weight=0)

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

def start_app():
    app = POSApp()
    app.start_pos()

if __name__ == "__main__":
    if load_session():
        start_app()
    else:
        show_login(start_app)