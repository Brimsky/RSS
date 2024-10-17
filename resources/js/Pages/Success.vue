<template>
  <AuthenticatedLayout>
    <div class=" mx-auto p-6 flex flex-col items-center justify-center min-h-screen bg-gray-100">
      <div class="bg-white shadow-lg rounded-lg p-8 text-center max-w-lg w-full">
        <div class="checkmark-container mb-6">
          <svg class="w-16 h-16 text-green-600 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h1 class="text-4xl font-bold text-green-600 mb-4">Payment Successful</h1>
        <p class="text-gray-700 text-lg">Thank you for your purchase! Your payment was processed successfully.</p>
        <a href="/cart" class="btn-primary mt-6 transition-transform duration-300 transform hover:scale-105 inline-block ">Back to cart!</a>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<style scoped>
.text-green-600 {
  color: #48bb78;
}


.btn-primary {
  padding: 0.75rem 1.5rem;
  background-color: #48bb78;
  color: white;
  font-weight: bold;
  border-radius: 0.375rem;
  box-shadow: 0 4px 14px rgba(72, 187, 120, 0.4);
  transition: background-color 0.3s, box-shadow 0.3s;
}

.btn-primary:hover {
  background-color: #38a169;
  box-shadow: 0 6px 20px rgba(72, 187, 120, 0.6);
}

.checkmark-container {
  display: inline-block;
  background-color: #e6fffa;
  border-radius: 50%;
  padding: 1rem;
}
</style>



