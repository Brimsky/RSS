<template>
    <div class="order-tracking bg-white p-6 rounded-lg shadow-sm">
      <h3 class="text-xl font-semibold mb-4">Track Your Order</h3>
      <input v-model="orderNumber" type="text" class="border rounded py-2 px-4" placeholder="Enter your order number" />
      <button @click="trackOrder" class="ml-4 btn-calculate">
        Track Order
      </button>
      <p v-if="trackingInfo" class="mt-4">Status: <strong>{{ trackingInfo.status }}</strong><br />Estimated Delivery: <strong>{{ trackingInfo.estimatedDelivery }}</strong></p>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        orderNumber: '',
        trackingInfo: null
      };
    },
    methods: {
      trackOrder() {
        this.trackingInfo = {
          status: 'In Transit',
          estimatedDelivery: '2023-10-20'
        };
      }
    }
  };
  </script>
  
  <style scoped>
  .order-tracking {
    padding: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
  }

  .btn-calculate {
  background: linear-gradient(90deg, #7b38b2, #512c7b); /* Gradient background */
  color: white;
  font-weight: bold;
  padding: 12px 20px; /* Increased padding for a larger button */
  border: none;
  border-radius: 10px; /* More rounded edges */
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Shadow effect */
  transition: background 0.3s ease, transform 0.3s ease; /* Smooth transition */
}

.btn-calculate:hover {
  background: linear-gradient(90deg, #512c7b, #7b38b2); /* Change gradient on hover */
  transform: translateY(-2px); /* Slight lift effect on hover */
}
  </style>
  