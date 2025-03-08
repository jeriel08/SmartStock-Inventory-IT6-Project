document.addEventListener("DOMContentLoaded", function () {
  const returnButtons = document.querySelectorAll(".returnToSupplierBtn");

  returnButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Get product details from the button's data attributes
      const receivingDetailId = this.getAttribute("data-id");
      const productName = this.getAttribute("data-product");
      const quantity = this.getAttribute("data-quantity");
      const supplier = this.getAttribute("data-supplier");

      // Set the modal fields
      document.getElementById("returnReceivingDetailId").value =
        receivingDetailId;
      document.getElementById("returnProductName").value = productName;
      document.getElementById("returnQuantity").value = quantity;
      document.getElementById("returnSupplier").value = supplier;

      // Show the modal
      const modal = new bootstrap.Modal(
        document.getElementById("returnToSupplierModal")
      );
      modal.show();
    });
  });
});
