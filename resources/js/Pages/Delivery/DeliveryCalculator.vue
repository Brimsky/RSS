<template>
    <div class="calculator bg-white p-6 rounded-lg shadow-sm">
      <h3 class="text-xl font-semibold mb-4">Delivery Cost Calculator</h3>
      <div class="flex items-center mb-4">
        <input v-model="location" type="text" class="border rounded py-2 px-4" placeholder="Enter ZIP code or city" />
        <button @click="calculateDelivery" class="ml-4 bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
          Calculate
        </button>
      </div>
      <div v-if="deliveryCost !== null">
        <p class="text-lg font-semibold">Estimated Delivery Cost: <span class="text-blue-500">{{ deliveryCost }} $</span></p>
        <p v-if="deliveryTime !== null" class="mt-4">Estimated Delivery Time: <strong>{{ deliveryTime }} days</strong></p>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        location: '',
        deliveryCost: null,
        deliveryTime: null
      };
    },
    methods: {
      calculateDelivery() {
        if (this.location.toLowerCase() === 'riga') {
          this.deliveryCost = (Math.random() * 10 + 5).toFixed(2);
        } else {
          this.deliveryCost = (Math.random() * 100).toFixed(2);
        }
        this.deliveryTime = Math.floor(Math.random() * 5) + 1;
      }
    }
  };
  </script>
  
  <style scoped>
  .calculator {
    padding: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
  }
  </style>
  