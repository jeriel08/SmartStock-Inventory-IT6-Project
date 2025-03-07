# main.py
import customtkinter as ctk
from tkinter import messagebox, TclError
import mysql.connector
from mysql.connector import Error
import cart
import place_order

# Initialize global variables
grand_total = 0.0
cart_items = []

try:
    print("Attempting to connect to database...")
    database = mysql.connector.connect(
        host='localhost',
        user='root',
        password='',
        database='smartstock_inventory'
    )
    print("Database connection successful!")
    cursor = database.cursor()

    root = ctk.CTk()
    root.title("Product Inventory")
    root.state('zoomed')

    # Use grid for responsive layout
    root.grid_columnconfigure(0, weight=0)
    root.grid_columnconfigure(1, weight=1)
    root.grid_columnconfigure(2, weight=0)
    root.grid_rowconfigure(0, weight=1)

    # Vertical Navbar Frame (wider)
    navbar_frame = ctk.CTkFrame(master=root, width=300, corner_radius=0)
    navbar_frame.grid(row=0, column=0, sticky="ns", padx=0, pady=0)

    logo_label = ctk.CTkLabel(master=navbar_frame, text="Logo", font=("Arial", 20, "bold"))
    logo_label.pack(pady=20)


    def filter_and_show_main(category_id):
        global cart_visible, order_visible
        if cart_visible:
            cart_frame.grid_forget()
            main_frame.grid(row=0, column=1, sticky="nsew", padx=10, pady=10)
            right_frame.grid(row=0, column=2, sticky="ns", padx=10, pady=10)
            cart_visible = False
            cart_btn.configure(text="View Cart")
        elif order_visible:
            place_order_frame.grid_forget()
            main_frame.grid(row=0, column=1, sticky="nsew", padx=10, pady=10)
            right_frame.grid(row=0, column=2, sticky="ns", padx=10, pady=10)
            order_visible = False
            place_order_btn.configure(text="Place Order")
        filter_products(category_id)


    all_products_btn = ctk.CTkButton(master=navbar_frame, text="All Products",
                                     command=lambda: filter_and_show_main(None))
    all_products_btn.pack(pady=5, padx=10, fill=ctk.X)

    dairy_eggs_btn = ctk.CTkButton(master=navbar_frame, text="Dairy and Eggs", command=lambda: filter_and_show_main(1))
    dairy_eggs_btn.pack(pady=5, padx=10, fill=ctk.X)

    baking_supplies_btn = ctk.CTkButton(master=navbar_frame, text="Baking Supplies",
                                        command=lambda: filter_and_show_main(2))
    baking_supplies_btn.pack(pady=5, padx=10, fill=ctk.X)

    snacks_btn = ctk.CTkButton(master=navbar_frame, text="Snacks", command=lambda: filter_and_show_main(3))
    snacks_btn.pack(pady=5, padx=10, fill=ctk.X)

    beverages_btn = ctk.CTkButton(master=navbar_frame, text="Beverages", command=lambda: filter_and_show_main(4))
    beverages_btn.pack(pady=5, padx=10, fill=ctk.X)

    canned_goods_btn = ctk.CTkButton(master=navbar_frame, text="Canned Goods", command=lambda: filter_and_show_main(5))
    canned_goods_btn.pack(pady=5, padx=10, fill=ctk.X)

    condiments_btn = ctk.CTkButton(master=navbar_frame, text="Condiments", command=lambda: filter_and_show_main(6))
    condiments_btn.pack(pady=5, padx=10, fill=ctk.X)

    household_supplies_btn = ctk.CTkButton(master=navbar_frame, text="Household Supplies",
                                           command=lambda: filter_and_show_main(7))
    household_supplies_btn.pack(pady=5, padx=10, fill=ctk.X)

    personal_care_btn = ctk.CTkButton(master=navbar_frame, text="Personal Care",
                                      command=lambda: filter_and_show_main(8))
    personal_care_btn.pack(pady=5, padx=10, fill=ctk.X)

    pet_foods_btn = ctk.CTkButton(master=navbar_frame, text="Pet Foods", command=lambda: filter_and_show_main(9))
    pet_foods_btn.pack(pady=5, padx=10, fill=ctk.X)

    # Main content frame (for product items)
    main_frame = ctk.CTkFrame(master=root)
    main_frame.grid(row=0, column=1, sticky="nsew", padx=10, pady=10)

    product_display_frame = ctk.CTkScrollableFrame(master=main_frame)
    product_display_frame.pack(fill=ctk.BOTH, expand=True, padx=0, pady=0)

    # Right-side frame for entries and buttons
    right_frame = ctk.CTkFrame(master=root, width=300)
    right_frame.grid(row=0, column=2, sticky="ns", padx=10, pady=10)

    entry_frame = ctk.CTkFrame(master=right_frame)
    entry_frame.pack(side=ctk.TOP, fill=ctk.X, pady=5)

    ctk.CTkLabel(master=entry_frame, text="Name:").pack(side=ctk.TOP, padx=(0, 2), pady=2)
    name_entry = ctk.CTkEntry(master=entry_frame, width=200, state='disabled')
    name_entry.pack(side=ctk.TOP, padx=5, pady=2)

    ctk.CTkLabel(master=entry_frame, text="Price:").pack(side=ctk.TOP, padx=(0, 2), pady=2)
    price_entry = ctk.CTkEntry(master=entry_frame, width=200, state='disabled')
    price_entry.pack(side=ctk.TOP, padx=5, pady=2)

    ctk.CTkLabel(master=entry_frame, text="Quantity:").pack(side=ctk.TOP, padx=(0, 2), pady=2)
    qty_entry = ctk.CTkEntry(master=entry_frame, width=200, border_width=2, border_color="white")
    qty_entry.pack(side=ctk.TOP, padx=5, pady=2)

    ctk.CTkLabel(master=entry_frame, text="Total:").pack(side=ctk.TOP, padx=(0, 2), pady=2)
    total_entry = ctk.CTkEntry(master=entry_frame, width=200, state='disabled')
    total_entry.pack(side=ctk.TOP, padx=5, pady=2)


    def toggle_cart():
        global cart_visible, order_visible
        if not cart_visible:
            main_frame.grid_forget()
            right_frame.grid_forget()
            cart_frame.grid(row=0, column=1, columnspan=2, sticky="nsew", padx=10, pady=10)
            cart.update_cart_display(cart_frame, cart_items, grand_total)
            cart_visible = True
            cart_btn.configure(text="Hide Cart")
        else:
            cart_frame.grid_forget()
            main_frame.grid(row=0, column=1, sticky="nsew", padx=10, pady=10)
            right_frame.grid(row=0, column=2, sticky="ns", padx=10, pady=10)
            cart_visible = False
            cart_btn.configure(text="View Cart")
        order_visible = False


    def toggle_place_order():
        global cart_visible, order_visible
        if not cart_items:
            messagebox.showwarning("Empty Cart", "Cannot place order with an empty cart.")
            return
        if not order_visible:
            main_frame.grid_forget()
            right_frame.grid_forget()
            place_order_frame.grid(row=0, column=1, columnspan=2, sticky="nsew", padx=10, pady=10)
            place_order.update_order_display(place_order_frame, cart_items, grand_total, toggle_place_order)
            order_visible = True
            place_order_btn.configure(text="Hide Order")
        else:
            place_order_frame.grid_forget()
            main_frame.grid(row=0, column=1, sticky="nsew", padx=10, pady=10)
            right_frame.grid(row=0, column=2, sticky="ns", padx=10, pady=10)
            order_visible = False
            place_order_btn.configure(text="Place Order")
        cart_visible = False


    # Buttons directly in right_frame
    add_btn = ctk.CTkButton(master=right_frame, text="Add to Cart", width=200,
                            command=lambda: [add_to_cart(), clear_selections()])
    add_btn.pack(side=ctk.TOP, padx=5, pady=5)

    cart_btn = ctk.CTkButton(master=right_frame, text="View Cart", width=200, command=toggle_cart)
    cart_btn.pack(side=ctk.TOP, padx=5, pady=5)

    # Grand Total Frame on right side
    grand_total_frame = ctk.CTkFrame(master=right_frame)
    grand_total_frame.pack(side=ctk.TOP, pady=(20, 10))

    grand_total_label = ctk.CTkLabel(master=grand_total_frame, text="GRAND TOTAL", font=("Arial", 24, "bold"))
    grand_total_label.pack(side=ctk.TOP)

    grand_total_value = ctk.CTkLabel(master=grand_total_frame, text="$0.00", font=("Arial", 24, "bold"))
    grand_total_value.pack(side=ctk.TOP)

    # Place Order button at bottom
    place_order_btn = ctk.CTkButton(master=right_frame, text="Place Order", width=200, height=50, font=("Arial", 16),
                                    command=toggle_place_order)
    place_order_btn.pack(side=ctk.BOTTOM, padx=5, pady=10)

    # Cart Frame (hidden initially)
    cart_frame = ctk.CTkFrame(master=root)
    cart_frame.grid_forget()
    cart.initialize_cart(cart_frame, cart_items, grand_total, lambda: toggle_cart())

    # Place Order Frame (hidden initially)
    place_order_frame = ctk.CTkFrame(master=root)
    place_order_frame.grid_forget()
    place_order.initialize_place_order(place_order_frame, cart_items, grand_total, toggle_place_order)

    selected_item_button = None
    cart_visible = False
    order_visible = False


    def populate_product_display(category_id=None):
        global selected_item_button
        for widget in product_display_frame.winfo_children():
            widget.destroy()
        selected_item_button = None

        try:
            if category_id is None:
                query = "SELECT Name, Price FROM products"
                cursor.execute(query)
            else:
                query = "SELECT Name, Price FROM products WHERE CategoryID = %s"
                cursor.execute(query, (category_id,))
            rows = cursor.fetchall()
            print(f"Fetched {len(rows)} products for display (CategoryID: {category_id if category_id else 'All'})")
            if not rows:
                no_items_label = ctk.CTkLabel(master=product_display_frame, text="No products available",
                                              font=("Arial", 14))
                no_items_label.grid(row=0, column=0, pady=10, padx=10)
            else:
                for i, row in enumerate(rows):
                    item_button = ctk.CTkButton(
                        master=product_display_frame,
                        text=f"{row[0]}\n${row[1]:.2f}",
                        command=lambda name=row[0], price=row[1]: select_product_item(name, price, item_button),
                        height=60,
                        width=180,
                        corner_radius=5,
                        border_width=2,
                        border_color="lightblue",
                        text_color="black",
                        font=("Arial", 14)
                    )
                    row_num = i // 3
                    col_num = i % 3
                    item_button.grid(row=row_num, column=col_num, pady=5, padx=5, sticky="ew")
                for col in range(3):
                    product_display_frame.grid_columnconfigure(col, weight=1)
        except mysql.connector.Error as err:
            messagebox.showerror("Database Error", f"Query failed: {err}")
            print(f"Query error: {err}")


    def select_product_item(name, price, button):
        global selected_item_button
        if selected_item_button and selected_item_button != button:
            selected_item_button.configure(border_color="lightblue")

        button.configure(border_color="#0288D1")
        selected_item_button = button

        name_entry.configure(state='normal')
        name_entry.delete(0, ctk.END)
        name_entry.insert(0, name)
        name_entry.configure(state='disabled')
        price_entry.configure(state='normal')
        price_entry.delete(0, ctk.END)
        price_entry.insert(0, f"{price:.2f}")
        price_entry.configure(state='disabled')
        qty_entry.delete(0, ctk.END)
        qty_entry.insert(0, "1")
        total_entry.configure(state='normal')
        total_entry.delete(0, ctk.END)
        total_entry.insert(0, f"{price:.2f}")
        total_entry.configure(state='disabled')


    def clear_selections():
        global selected_item_button
        if selected_item_button:
            selected_item_button.configure(border_color="lightblue")
            selected_item_button = None


    def filter_products(category_id):
        try:
            print(f"Filtering products by CategoryID: {category_id if category_id else 'All'}")
            populate_product_display(category_id)
        except mysql.connector.Error as err:
            messagebox.showerror("Database Error", f"Query failed: {err}")
            print(f"Query error: {err}")


    def add_to_cart():
        global grand_total, cart_items
        try:
            product = name_entry.get()
            price = float(price_entry.get())
            quantity = int(qty_entry.get())
            if not product:
                messagebox.showwarning("Missing Data", "Product name cannot be empty")
                return
            if price < 0:
                messagebox.showwarning("Invalid Price", "Price cannot be negative")
                return
            if quantity <= 0:
                messagebox.showwarning("Invalid Quantity", "Please enter a quantity greater than 0")
                return
            total = price * quantity
            grand_total += total
            cart_items.append((product, price, quantity, total))
            print(f"Added to cart: {product}, Total: ${total:.2f}, Grand Total: ${grand_total:.2f}")
            grand_total_value.configure(text=f"${grand_total:.2f}")
            if cart_visible:
                cart.update_cart_display(cart_frame, cart_items, grand_total)
            elif order_visible:
                place_order.update_order_display(place_order_frame, cart_items, grand_total, toggle_place_order)
            name_entry.configure(state='normal')
            name_entry.delete(0, ctk.END)
            name_entry.configure(state='disabled')
            price_entry.configure(state='normal')
            price_entry.delete(0, ctk.END)
            price_entry.configure(state='disabled')
            qty_entry.delete(0, ctk.END)
            total_entry.configure(state='normal')
            total_entry.delete(0, ctk.END)
            total_entry.configure(state='disabled')
        except ValueError:
            messagebox.showwarning("Invalid Input", "Please enter valid numbers for price and quantity")


    def update_total(*args):
        try:
            price = float(price_entry.get())
            qty = int(qty_entry.get())
            total = price * qty
            total_entry.configure(state='normal')
            total_entry.delete(0, ctk.END)
            total_entry.insert(0, f"{total:.2f}")
            total_entry.configure(state='disabled')
        except (ValueError, TclError):
            pass


    qty_entry.bind("<KeyRelease>", update_total)

    # Populate product display on startup
    populate_product_display()

    root.mainloop()

except mysql.connector.Error as err:
    messagebox.showerror("Connection Error", f"Cannot connect to database: {err}")
    print(f"Connection error: {err}")

finally:
    if 'database' in locals():
        print("Closing database connection")
        database.close()