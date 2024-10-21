<template>
  <div class="container mx-auto p-6">
    <div v-if="product">
      <h1 class="text-3xl font-bold mb-6">{{ product.name }}</h1>
      
      <div class="bg-white shadow rounded-lg p-6">
        <p class="text-xl mb-2">Price: ${{ formatPrice(product.price) }}</p>
        <p class="mb-4">{{ product.description }}</p>
        <p class="mb-2">Category: {{ product.category }}</p>
        <p class="mb-2">Subcategory: {{ product.subcategory }}</p>
        <p class="mb-2">Condition: {{ product.condition }}</p>
        <p class="mb-2">Location: {{ product.location }}</p>
        
        <!-- Display photos if available -->
        <div v-if="product.photos && product.photos.length > 0" class="mt-4">
          <h2 class="text-xl font-semibold mb-2">Photos:</h2>
          <div class="flex flex-wrap gap-4">
            <img v-for="(photo, index) in product.photos" 
                 :key="index" 
                 :src="photo" 
                 :alt="`Product photo ${index + 1}`"
                 class="w-32 h-32 object-cover rounded"
            />
          </div>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-8">
      <p class="text-red-500">Product not found.</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  product: Object,
});

const formatPrice = (price) => {
  const numPrice = Number(price);
  return isNaN(numPrice) ? '0.00' : numPrice.toFixed(2);
};
</script>