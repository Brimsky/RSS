<template>
  <AuthenticatedLayout>
    <div class="mx-auto p-6 flex flex-col items-center justify-center min-h-screen bg-gray-100">
      <div class="bg-white shadow-lg rounded-lg p-8 text-center max-w-lg w-full">
        <div class="crossmark-container mb-6">
          <svg class="w-16 h-16 text-red-600 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </div>
        <h1 class="text-4xl font-bold text-red-600 mb-4">Payment Canceled</h1>
        <p class="text-gray-700 text-lg">Your payment has been canceled. Please try again or contact support.</p>
        <a href="/cart" class="btn-primary mt-6 transition-transform duration-300 transform hover:scale-105 inline-block">Back to Cart</a>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<style scoped>
/* Dizains */
.text-red-600 {
  color: #f56565;
}

.btn-primary {
  padding: 0.75rem 1.5rem;
  background-color: #f56565;
  color: white;
  font-weight: bold;
  border-radius: 0.375rem;
  box-shadow: 0 4px 14px rgba(245, 101, 101, 0.4);
  transition: background-color 0.3s, box-shadow 0.3s;
}

.btn-primary:hover {
  background-color: #e53e3e;
  box-shadow: 0 6px 20px rgba(245, 101, 101, 0.6);
}

.crossmark-container {
  display: inline-block;
  background-color: #ffe6e6;
  border-radius: 50%;
  padding: 1rem;
}
</style>
