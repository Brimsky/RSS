<template>
    <div class="order-tracking bg-white p-6 rounded-lg shadow-sm">
      <h3 class="text-xl font-semibold mb-4">Track Your Order</h3>
      <input v-model="orderNumber" type="text" class="border rounded py-2 px-4" placeholder="Enter your order number" />
      <button @click="trackOrder" class="ml-4 bg-purple-500 text-white font-bold py-2 px-4 rounded hover:bg-purple-700">
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
  </style>
  