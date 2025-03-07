# place_order.py
import datetime
import customtkinter as ctk
from tkinter import messagebox
import mysql.connector
from mysql.connector import Error


def initialize_place_order(place_order_frame, items, total, toggle_callback):
    global order_items, grand_total
    order_items = items
    grand_total = total

    # Scrollable content for customer details and cart items
    order_content = ctk.CTkScrollableFrame(master=place_order_frame)
    order_content.pack(fill=ctk.BOTH, expand=True, padx=10, pady=10)

    # Customer details frame (transparent)
    customer_frame = ctk.CTkFrame(master=order_content, fg_color="transparent")
    customer_frame.pack(side=ctk.TOP, pady=10, padx=10, anchor="nw")

    # Name group
    name_frame = ctk.CTkFrame(master=customer_frame, fg_color="transparent")
    name_frame.pack(side=ctk.TOP, pady=5, anchor="w")
    ctk.CTkLabel(master=name_frame, text="Name:", font=("Arial", 14)).pack(side=ctk.TOP, anchor="w")
    name_entry = ctk.CTkEntry(master=name_frame, width=300)
    name_entry.pack(side=ctk.TOP, pady=(2, 5))

    # Phone group
    phone_frame = ctk.CTkFrame(master=customer_frame, fg_color="transparent")
    phone_frame.pack(side=ctk.TOP, pady=5, anchor="w")
    ctk.CTkLabel(master=phone_frame, text="Phone:", font=("Arial", 14)).pack(side=ctk.TOP, anchor="w")
    phone_entry = ctk.CTkEntry(master=phone_frame, width=300)
    phone_entry.pack(side=ctk.TOP, pady=(2, 5))

    # Address group
    address_frame = ctk.CTkFrame(master=customer_frame, fg_color="transparent")
    address_frame.pack(side=ctk.TOP, pady=5, anchor="w")
    ctk.CTkLabel(master=address_frame, text="Address:", font=("Arial", 14)).pack(side=ctk.TOP, anchor="w")
    address_entry = ctk.CTkEntry(master=address_frame, width=300)
    address_entry.pack(side=ctk.TOP, pady=(2, 5))

    # Items frame with table-like display
    items_frame = ctk.CTkFrame(master=order_content)
    items_frame.pack(fill=ctk.X, pady=10, padx=10)

    ctk.CTkLabel(master=items_frame, text="CART", font=("Arial", 24, "bold")).pack(side=ctk.TOP, pady=5)

    # Header
    header_frame = ctk.CTkFrame(master=items_frame)
    header_frame.pack(fill=ctk.X, pady=2)
    ctk.CTkLabel(master=header_frame, text="Product Name", font=("Arial", 14, "bold"), width=200).pack(side=ctk.LEFT,
                                                                                                       padx=5)
    ctk.CTkLabel(master=header_frame, text="Quantity", font=("Arial", 14, "bold"), width=100).pack(side=ctk.LEFT,
                                                                                                   padx=5)
    ctk.CTkLabel(master=header_frame, text="Total", font=("Arial", 14, "bold"), width=100).pack(side=ctk.LEFT, padx=5)

    # Items
    for product, price, quantity, item_total, *extra in order_items:
        row_frame = ctk.CTkFrame(master=items_frame)
        row_frame.pack(fill=ctk.X, pady=2)
        ctk.CTkLabel(master=row_frame, text=product, font=("Arial", 14), width=200).pack(side=ctk.LEFT, padx=5)
        ctk.CTkLabel(master=row_frame, text=str(quantity), font=("Arial", 14), width=100).pack(side=ctk.LEFT, padx=5)
        ctk.CTkLabel(master=row_frame, text=f"${item_total:.2f}", font=("Arial", 14), width=100).pack(side=ctk.LEFT,
                                                                                                      padx=5)

    # Grand Total and Buttons Frame (fixed at bottom, outside scrollable content)
    grand_total_frame = ctk.CTkFrame(master=place_order_frame)
    grand_total_frame.pack(side=ctk.BOTTOM, fill=ctk.X, padx=10, pady=10)

    grand_total_label = ctk.CTkLabel(master=grand_total_frame, text=f"Grand Total: ${grand_total:.2f}",
                                     font=("Arial", 24, "bold"))
    grand_total_label.pack(side=ctk.LEFT, padx=(0, 10))

    def submit_order():
        name = name_entry.get().strip()
        phone = phone_entry.get().strip()
        address = address_entry.get().strip()

        if not order_items:
            messagebox.showwarning("Empty Order", "No items to place in the order.")
            return

        try:
            database = mysql.connector.connect(
                host='localhost',
                user='root',
                password='',
                database='smartstock_inventory'
            )
            cursor = database.cursor()

            # 1️⃣ Insert customer
            query_customer = """
                INSERT INTO customers (Name, Address, PhoneNumber, Created_By, Updated_By)
                VALUES (%s, %s, %s, %s, %s)
            """
            values_customer = (name, address, phone, 1, 1)
            cursor.execute(query_customer, values_customer)
            database.commit()
            customer_id = cursor.lastrowid  # Get new CustomerID

            print(f"✅ Customer inserted with ID: {customer_id}")  # Debugging

            # 2️⃣ Insert order into Orders table
            order_date = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
            order_status = "Pending"
            delivery_status = "Not Delivered"
            created_by = 1  # Assuming employee ID 1 (admin)
            updated_by = 1

            total_price = sum(item["quantity"] * item["price"] for item in order_items)  # Calculate total

            query_order = """
                INSERT INTO orders (CustomerID, Date, Total, Status, Delivery, Created_at, Created_by, Updated_at, Updated_by)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
            """
            values_order = (customer_id, order_date, total_price, order_status, delivery_status, order_date, created_by, order_date, updated_by)
            cursor.execute(query_order, values_order)
            database.commit()
            order_id = cursor.lastrowid  # Get new OrderID

            print(f"✅ Order inserted with ID: {order_id}")  # Debugging

            # 3️⃣ Insert order items into Orderline table
            if not order_items:
                print("⚠ No items in order_items list!")  # Debugging
            else:
                print(f"✅ Adding {len(order_items)} items to orderline")  # Debugging

            query_orderline = """
                INSERT INTO orderline (OrderID, ProductID, Quantity, Price)
                VALUES (%s, %s, %s, %s)
            """

            for item in order_items:
                values_orderline = (order_id, item["product_id"], item["quantity"], item["price"])
                cursor.execute(query_orderline, values_orderline)

            database.commit()  # Commit all orderline items

            print(f"✅ Order {order_id} recorded with {len(order_items)} items!")  # Debugging

        except mysql.connector.Error as err:
            database.rollback()  # Rollback on error
            messagebox.showerror("Database Error", f"Failed to save order: {err}")
            print(f"❌ Database error: {err}")

        finally:
            if 'cursor' in locals():
                cursor.close()
            if 'database' in locals():
                database.close()

        # Show success message
        messagebox.showinfo("Order Success", f"Thank you for your purchase!\n"
                                            f"Order placed for {name}\nTotal: ${total_price:.2f}")
        toggle_callback()


    submit_btn = ctk.CTkButton(master=grand_total_frame, text="Submit Order", command=submit_order)
    submit_btn.pack(side=ctk.RIGHT, padx=5)

    back_btn = ctk.CTkButton(master=grand_total_frame, text="Back", command=toggle_callback)
    back_btn.pack(side=ctk.RIGHT, padx=5)


def update_order_display(place_order_frame, items, total, toggle_callback):
    global order_items, grand_total
    order_items = items
    grand_total = total

    # Clear existing content
    for widget in place_order_frame.winfo_children():
        widget.destroy()

    # Scrollable content for customer details and cart items
    order_content = ctk.CTkScrollableFrame(master=place_order_frame)
    order_content.pack(fill=ctk.BOTH, expand=True, padx=10, pady=10)

    # Rebuild customer details frame (transparent)
    customer_frame = ctk.CTkFrame(master=order_content, fg_color="transparent")
    customer_frame.pack(side=ctk.TOP, pady=10, padx=10, anchor="nw")

    # Name group
    name_frame = ctk.CTkFrame(master=customer_frame, fg_color="transparent")
    name_frame.pack(side=ctk.TOP, pady=5, anchor="w")
    ctk.CTkLabel(master=name_frame, text="Name:", font=("Arial", 14)).pack(side=ctk.TOP, anchor="w")
    name_entry = ctk.CTkEntry(master=name_frame, width=300)
    name_entry.pack(side=ctk.TOP, pady=(2, 5))

    # Phone group
    phone_frame = ctk.CTkFrame(master=customer_frame, fg_color="transparent")
    phone_frame.pack(side=ctk.TOP, pady=5, anchor="w")
    ctk.CTkLabel(master=phone_frame, text="Phone:", font=("Arial", 14)).pack(side=ctk.TOP, anchor="w")
    phone_entry = ctk.CTkEntry(master=phone_frame, width=300)
    phone_entry.pack(side=ctk.TOP, pady=(2, 5))

    # Address group
    address_frame = ctk.CTkFrame(master=customer_frame, fg_color="transparent")
    address_frame.pack(side=ctk.TOP, pady=5, anchor="w")
    ctk.CTkLabel(master=address_frame, text="Address:", font=("Arial", 14)).pack(side=ctk.TOP, anchor="w")
    address_entry = ctk.CTkEntry(master=address_frame, width=300)
    address_entry.pack(side=ctk.TOP, pady=(2, 5))

    # Rebuild items frame with table-like display
    items_frame = ctk.CTkFrame(master=order_content)
    items_frame.pack(fill=ctk.X, pady=10, padx=10)

    ctk.CTkLabel(master=items_frame, text="CART", font=("Arial", 24, "bold")).pack(side=ctk.TOP, pady=5)

    # Header
    header_frame = ctk.CTkFrame(master=items_frame)
    header_frame.pack(fill=ctk.X, pady=2)
    ctk.CTkLabel(master=header_frame, text="Product Name", font=("Arial", 14, "bold"), width=200).pack(side=ctk.LEFT,
                                                                                                       padx=5)
    ctk.CTkLabel(master=header_frame, text="Quantity", font=("Arial", 14, "bold"), width=100).pack(side=ctk.LEFT,
                                                                                                   padx=5)
    ctk.CTkLabel(master=header_frame, text="Total", font=("Arial", 14, "bold"), width=100).pack(side=ctk.LEFT, padx=5)

    # Items
    for product, price, quantity, item_total, *_ in order_items:
        row_frame = ctk.CTkFrame(master=items_frame)
        row_frame.pack(fill=ctk.X, pady=2)
        ctk.CTkLabel(master=row_frame, text=product, font=("Arial", 14), width=200).pack(side=ctk.LEFT, padx=5)
        ctk.CTkLabel(master=row_frame, text=str(quantity), font=("Arial", 14), width=100).pack(side=ctk.LEFT, padx=5)
        ctk.CTkLabel(master=row_frame, text=f"${item_total:.2f}", font=("Arial", 14), width=100).pack(side=ctk.LEFT,
                                                                                                      padx=5)

    # Grand Total and Buttons Frame (fixed at bottom, outside scrollable content)
    grand_total_frame = ctk.CTkFrame(master=place_order_frame)
    grand_total_frame.pack(side=ctk.BOTTOM, fill=ctk.X, padx=10, pady=10)

    grand_total_label = ctk.CTkLabel(master=grand_total_frame, text=f"Grand Total: ${grand_total:.2f}",
                                     font=("Arial", 24, "bold"))
    grand_total_label.pack(side=ctk.LEFT, padx=(0, 10))

    def submit_order():
        name = name_entry.get()
        phone = phone_entry.get()
        address = address_entry.get()
        if not name or not phone or not address:
            messagebox.showwarning("Missing Information", "Please fill in all customer details.")
            return
        if not order_items:
            messagebox.showwarning("Empty Order", "No items to place in the order.")
            return

        try:
            database = mysql.connector.connect(
                host='localhost',
                user='root',
                password='',
                database='smartstock_inventory'
            )
            cursor = database.cursor()

            # 1. Insert into customers table (or find existing customer)
            cursor.execute("SELECT CustomerID FROM customers WHERE Name = %s AND PhoneNumber = %s LIMIT 1",
                           (name, phone))
            customer = cursor.fetchone()

            if customer:
                customer_id = customer[0]  # Use existing CustomerID
            else:
                query = """
                    INSERT INTO customers (Name, Address, PhoneNumber, Created_By, Updated_By)
                    VALUES (%s, %s, %s, %s, %s)
                """
                values = (name, address, phone, 1, 1)
                cursor.execute(query, values)
                database.commit()
                customer_id = cursor.lastrowid  # Get newly inserted CustomerID

            print(f"Customer {name} (ID: {customer_id}) added to database!")

            # 2. Insert new order into orders table
            query = """
                INSERT INTO orders (CustomerID, Date, Total, Status, Delivery, Created_By, Updated_By)
                VALUES (%s, NOW(), %s, 'Paid', 'Pick-up', %s, %s)
            """
            values = (customer_id, grand_total, 1, 1)
            cursor.execute(query, values)
            database.commit()
            order_id = cursor.lastrowid  # Get newly inserted OrderID
            print(f"Order ID {order_id} created.")

            # 3. Insert each product into orderline table
            for product, price, quantity, item_total, category_id in order_items:
                # Get ProductID based on product name and category
                cursor.execute("SELECT ProductID FROM products WHERE Name = %s AND CategoryID = %s LIMIT 1",
                               (product, category_id))
                product_data = cursor.fetchone()

                if product_data:
                    product_id = product_data[0]
                    # Insert into orderline
                    query = """
                        INSERT INTO orderline (OrderID, ProductID, Quantity, Price)
                        VALUES (%s, %s, %s, %s)
                    """
                    values = (order_id, product_id, quantity, price)
                    cursor.execute(query, values)

                    # Deduct stock from products table
                    cursor.execute("UPDATE products SET StockQuantity = StockQuantity - %s WHERE ProductID = %s",
                                   (quantity, product_id))

            database.commit()
            print(f"OrderLine records added, and stock updated.")

        except mysql.connector.Error as err:
            messagebox.showerror("Database Error", f"Failed to save order: {err}")
            print(f"Database error: {err}")
            database.rollback()
        finally:
            if 'cursor' in locals():
                cursor.close()
            if 'database' in locals():
                database.close()

        # Show success message
        messagebox.showinfo("Order Success",
                            f"Order placed for {name}\nPhone: {phone}\nAddress: {address}\nTotal: ${grand_total:.2f}")
        toggle_callback()

    submit_btn = ctk.CTkButton(master=grand_total_frame, text="Submit Order", command=submit_order)
    submit_btn.pack(side=ctk.RIGHT, padx=5)

    back_btn = ctk.CTkButton(master=grand_total_frame, text="Back", command=toggle_callback)
    back_btn.pack(side=ctk.RIGHT, padx=5)