// Script for Discard Product Modal
console.log("discard-stock.js loaded!");
console.log(document.querySelectorAll(".discard-button"));

document.addEventListener("DOMContentLoaded", function () {
  console.log("Script is running...");

  const discardButtons = document.querySelectorAll(".discard-button");

  if (discardButtons.length === 0) {
    console.log("No discard buttons found!");
  }

  discardButtons.forEach((button) => {
    button.addEventListener("click", function () {
      console.log("Discard button clicked!");

      const row = this.closest("tr");
      if (!row) {
        console.log("Row not found!");
        return;
      }

      const productId = row.children[1].textContent.trim(); // Product ID
      const productName = row.children[0].textContent.trim(); // Product Name

      console.log("Product ID:", productId);
      console.log("Product Name:", productName);

      document.getElementById("discardProductId").value = productId;
      document.getElementById("discardProductName").textContent = productName;
    });
  });
});
