<template>
    <div class="delivery-options">
      <div class="option bg-white p-4 rounded-lg shadow-sm mb-6">
        <h2 class="text-2xl font-semibold mb-2">Courier Delivery</h2>
        <p>Delivery to your address within 1-2 business days. The cost depends on your location.</p>
      </div>
  
      <div class="option bg-white p-4 rounded-lg shadow-sm">
        <h2 class="text-2xl font-semibold mb-2">Self Pickup</h2>
        <p>You can pick up your order from one of our pickup points at a time convenient for you.</p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: "DeliveryOptions"
  };
  </script>
  
  <style scoped>
  .option {
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
  }
  </style>
  