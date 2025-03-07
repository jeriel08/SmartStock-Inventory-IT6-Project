# cart.py
import customtkinter as ctk
from tkinter import messagebox
import place_order

def initialize_cart(cart_frame, items, total, toggle_callback):
    global cart_items, grand_total
    cart_items = items
    grand_total = total

    cart_content = ctk.CTkScrollableFrame(master=cart_frame)
    cart_content.pack(fill=ctk.BOTH, expand=True, padx=10, pady=10)

    grand_total_frame = ctk.CTkFrame(master=cart_frame)
    grand_total_frame.pack(fill=ctk.X, padx=10, pady=10)

    grand_total_label = ctk.CTkLabel(master=grand_total_frame, text=f"Grand Total: ${grand_total:.2f}", font=("Arial", 24, "bold"))
    grand_total_label.pack(side=ctk.LEFT, padx=(0, 10))

    def toggle_place_order_from_cart():
        if not cart_items:
            messagebox.showwarning("Empty Cart", "Cannot place order with an empty cart.")
            return
        cart_frame.pack_forget()
        place_order_frame = ctk.CTkFrame(master=cart_frame.master)
        place_order_frame.grid(row=0, column=1, columnspan=2, sticky="nsew", padx=10, pady=10)
        place_order.initialize_place_order(place_order_frame, cart_items, grand_total, toggle_callback)
        global order_visible
        order_visible = True

    place_order_btn = ctk.CTkButton(master=grand_total_frame, text="Place Order", width=200, height=50, font=("Arial", 16), command=toggle_place_order_from_cart)
    place_order_btn.pack(side=ctk.RIGHT, padx=5)

    back_btn = ctk.CTkButton(master=grand_total_frame, text="Back", command=toggle_callback)
    back_btn.pack(side=ctk.RIGHT, padx=5)

    update_cart_display(cart_frame, cart_items, grand_total)

def update_cart_display(cart_frame, items, total):
    global cart_items, grand_total
    cart_items = items
    grand_total = total

    cart_content = cart_frame.winfo_children()[0]
    for widget in cart_content.winfo_children():
        widget.destroy()

    for idx, (product, price, quantity, item_total) in enumerate(cart_items):
        item_frame = ctk.CTkFrame(master=cart_content)
        item_frame.pack(fill=ctk.X, pady=10, padx=10)

        item_label = ctk.CTkLabel(
            master=item_frame,
            text=f"{product}",
            font=("Arial", 14),
            height=60,
            width=200,
            anchor="w",
            justify="left"
        )
        item_label.pack(side=ctk.LEFT, padx=(0, 10))

        price_frame = ctk.CTkFrame(master=item_frame, width=60, height=40, border_width=2, border_color="white")
        price_frame.pack(side=ctk.LEFT, padx=5)
        price_label = ctk.CTkLabel(master=price_frame, text=f"${price:.2f}", font=("Arial", 14))
        price_label.place(relx=0.5, rely=0.5, anchor="center")

        total_frame = ctk.CTkFrame(master=item_frame, width=60, height=40, border_width=2, border_color="white")
        total_frame.pack(side=ctk.LEFT, padx=5)
        total_label = ctk.CTkLabel(master=total_frame, text=f"${item_total:.2f}", font=("Arial", 14))
        total_label.place(relx=0.5, rely=0.5, anchor="center")

        qty_frame = ctk.CTkFrame(master=item_frame)
        qty_frame.pack(side=ctk.RIGHT, padx=5)

        minus_btn = ctk.CTkButton(
            master=qty_frame,
            text="-",
            width=40,
            height=40,
            command=lambda i=idx: subtract_quantity(i, cart_frame)
        )
        minus_btn.pack(side=ctk.LEFT, padx=5)

        qty_display = ctk.CTkLabel(
            master=qty_frame,
            text=str(quantity),
            width=60,
            height=40,
            font=("Arial", 16),
            justify="center"
        )
        qty_display.pack(side=ctk.LEFT, padx=5)

        plus_btn = ctk.CTkButton(
            master=qty_frame,
            text="+",
            width=40,
            height=40,
            command=lambda i=idx: add_quantity(i, cart_frame)
        )
        plus_btn.pack(side=ctk.LEFT, padx=5)

        delete_btn = ctk.CTkButton(
            master=qty_frame,
            text="Delete",
            width=80,
            height=40,
            fg_color="red",
            hover_color="darkred",
            font=("Arial", 14),
            command=lambda i=idx: remove_item(i, cart_frame)
        )
        delete_btn.pack(side=ctk.LEFT, padx=5)

    grand_total_frame = cart_frame.winfo_children()[1]
    grand_total_label = grand_total_frame.winfo_children()[0]
    grand_total_label.configure(text=f"Grand Total: ${grand_total:.2f}")

def subtract_quantity(index, cart_frame):
    global cart_items, grand_total
    product, price, quantity, item_total = cart_items[index]
    if quantity > 1:
        new_quantity = quantity - 1
        new_total = price * new_quantity
        grand_total = grand_total - item_total + new_total
        cart_items[index] = (product, price, new_quantity, new_total)
        update_cart_display(cart_frame, cart_items, grand_total)

def add_quantity(index, cart_frame):
    global cart_items, grand_total
    product, price, quantity, item_total = cart_items[index]
    new_quantity = quantity + 1
    new_total = price * new_quantity
    grand_total = grand_total - item_total + new_total
    cart_items[index] = (product, price, new_quantity, new_total)
    update_cart_display(cart_frame, cart_items, grand_total)

def remove_item(index, cart_frame):
    global cart_items, grand_total
    item_total = cart_items[index][3]
    grand_total -= item_total
    cart_items.pop(index)
    update_cart_display(cart_frame, cart_items, grand_total)
    if not cart_items:
        cart_frame.pack_forget()