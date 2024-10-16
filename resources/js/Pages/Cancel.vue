<template>
    <div class="container mx-auto p-4">
      <h1 class="text-3xl font-bold text-red-600">Payment Canceled</h1>
      <p class="mt-4">Your payment was canceled. If you wish to try again, please return to your cart.</p>
      <router-link to="/cart" class="btn btn-primary mt-4">Return to Cart</router-link>
    </div>
  </template>
  
  <script setup>
  // No special script needed here
  </script>
  
  <style scoped>
  /* Add your cancel page styles here */
  .text-red-600 {
    color: #f56565;
  }
  </style>
  