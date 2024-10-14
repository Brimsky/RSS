<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// izskata uzmetums, nepiemīt nekādas backend funkcionalitātes. CartItems
const cartItems = ref([
  {
    name: 'Canada Goose US Puffer',
    color: 'Black',
    size: 'L',
    price: 2379.95,
    quantity: 1,
    image: '/path/to/product-image.jpg',
  },
  {
    name:'The North Face Puffer',
    color: 'Reflective Black',
    size: 'XL',
    price: 437.95,
    quantity: 1,
    image: '/path/to/product-image.jpg',
  },
]);

const promoCode = ref('');
const shippingCost = ref('TBD');
const discount = ref(0);
const tax = ref('TBD');

const totalPrice = () => cartItems.value.reduce((sum, item) => sum + item.price * item.quantity, 0);

// const submitPromoCode = () => {
//   // Handle promo code submission
//   console.log('Promo code submitted:', promoCode.value);
// };

// const handleStripePayment = () => {
//   // Placeholder function for Stripe payment
//   console.log('Initiating Stripe payment for $', totalPrice().toFixed(2));
// };
</script>

<template>
  <Head title="My Cart" />

  <AuthenticatedLayout>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">
        <span class="inline-block align-middle mr-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
        </span>
        My Cart
      </h1>

      <div class="flex flex-col md:flex-row gap-4">
        <div class="md:w-2/3">
          <div v-for="item in cartItems" :key="item.name" class="flex items-center border-b py-4">
            <img :src="item.image" :alt="item.name" class="w-24 h-32 object-cover mr-4" />
            <div class="flex-grow">
              <h2 class="font-semibold">{{ item.name }}</h2>
              <p>Color: {{ item.color }}</p>
              <p>Size: {{ item.size }}</p>
              <p>In Stock</p>
              <div class="mt-2">
                <a href="#" class="text-blue-600 mr-2">Edit</a>
                <a href="#" class="text-blue-600 mr-2">Remove</a>
                <a href="#" class="text-blue-600 mr-2">Move to Wishlist</a>
                <a href="#" class="text-blue-600">Save for Later</a>
              </div>
            </div>
            <div class="text-right">
              <p class="font-semibold">${{ item.price.toFixed(2) }}</p>
              <select v-model="item.quantity" class="mt-2 border rounded p-1">
                <option v-for="n in 10" :key="n" :value="n">{{ n }}</option>
              </select>
            </div>
          </div>
          <p class="mt-4">{{ cartItems.length }} Items</p>
        </div>

        <div class="md:w-1/3">
          <div class="bg-gray-100 p-4 rounded">
            <h2 class="font-semibold mb-2">ENTER PROMO CODE</h2>
            <div class="flex">
              <input v-model="promoCode" type="text" placeholder="Promo Code" class="flex-grow border rounded-l p-2" />
              <button @click="submitPromoCode" class="bg-black text-white px-4 py-2 rounded-r">Submit</button>
            </div>
            <div class="mt-4">
              <p class="flex justify-between"><span>Shipping cost</span><span>{{ shippingCost }}</span></p>
              <p class="flex justify-between"><span>Discount</span><span>-${{ discount.toFixed(2) }}</span></p>
              <p class="flex justify-between"><span>Tax</span><span>{{ tax }}</span></p>
              <p class="flex justify-between font-semibold mt-2"><span>Estimated Total</span><span>${{ totalPrice().toFixed(2) }}</span></p>
            </div>
            <p class="text-sm mt-2">or 4 interest-free payments of $10.00 with <span class="font-semibold">afterpay</span></p>
            <p class="text-sm text-yellow-600 mt-2">You're $10.01 away from free shipping!</p>
            <button @click="handleStripePayment" class="w-full bg-blue-500 text-white font-semibold py-2 rounded mt-4">
              Pay with Stripe
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>