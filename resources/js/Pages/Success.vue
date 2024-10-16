<template>
    <div class="container mx-auto p-4">
      <h1 class="text-3xl font-bold text-green-600">Payment Successful</h1>
      <p class="mt-4">Thank you for your purchase! Your payment was processed successfully.</p>
      <router-link to="/cart" class="btn btn-primary mt-4">Go back to Cart</router-link>
    </div>
  </template>
  
  <script setup>

  </script>
  
  <style scoped>
  .text-green-600 {
    color: #48bb78;
  }
  </style>
  