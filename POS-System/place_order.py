# place_order.py
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
    for product, price, quantity, item_total in order_items:
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

        # Connect to database and insert customer
        try:
            database = mysql.connector.connect(
                host='localhost',
                user='root',
                password='',
                database='smartstock_inventory'
            )
            cursor = database.cursor()

            # Insert into customers table
            query = """
                INSERT INTO customers (Name, Address, PhoneNumber, Created_By, Updated_By)
                VALUES (%s, %s, %s, %s, %s)
            """
            # Using EmployeeID 1 (admin) as default for Created_By and Updated_By
            values = (name, address, phone, 1, 1)
            cursor.execute(query, values)
            database.commit()
            print(f"Customer {name} added to database!")

        except mysql.connector.Error as err:
            messagebox.showerror("Database Error", f"Failed to save customer: {err}")
            print(f"Database error: {err}")
        finally:
            if 'cursor' in locals():
                cursor.close()
            if 'database' in locals():
                database.close()

        # Show thank you message
        messagebox.showinfo("Order Success", "Thank you for your purchase!\n"
                                             f"Order placed for {name}\nPhone: {phone}\nAddress: {address}\nTotal: ${grand_total:.2f}")
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
    for product, price, quantity, item_total in order_items:
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

        # Connect to database and insert customer
        try:
            database = mysql.connector.connect(
                host='localhost',
                user='root',
                password='',
                database='smartstock_inventory'
            )
            cursor = database.cursor()

            # Insert into customers table
            query = """
                INSERT INTO customers (Name, Address, PhoneNumber, Created_By, Updated_By)
                VALUES (%s, %s, %s, %s, %s)
            """
            # Using EmployeeID 1 (admin) as default for Created_By and Updated_By
            values = (name, address, phone, 1, 1)
            cursor.execute(query, values)
            database.commit()
            print(f"Customer {name} added to database!")

        except mysql.connector.Error as err:
            messagebox.showerror("Database Error", f"Failed to save customer: {err}")
            print(f"Database error: {err}")
        finally:
            if 'cursor' in locals():
                cursor.close()
            if 'database' in locals():
                database.close()

        # Show thank you message
        messagebox.showinfo("Order Success", "Thank you for your purchase!\n"
                                             f"Order placed for {name}\nPhone: {phone}\nAddress: {address}\nTotal: ${grand_total:.2f}")
        toggle_callback()

    submit_btn = ctk.CTkButton(master=grand_total_frame, text="Submit Order", command=submit_order)
    submit_btn.pack(side=ctk.RIGHT, padx=5)

    back_btn = ctk.CTkButton(master=grand_total_frame, text="Back", command=toggle_callback)
    back_btn.pack(side=ctk.RIGHT, padx=5)