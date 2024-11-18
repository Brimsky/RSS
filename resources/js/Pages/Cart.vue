<template>
  <AuthenticatedLayout>
    <div class="container mx-auto py-8 px-4 md:px-6">
      <div class="flex flex-col md:flex-row gap-8">
        <!-- Cart Items -->
        <div class="md:w-2/3 space-y-6">
          <div v-for="(item, productId) in cartItems" :key="productId" class="bg-white shadow-lg rounded-lg p-6">
            <div class="flex items-center">
              <div class="flex-grow">
                <h2 class="text-xl font-semibold text-gray-800">{{ item.name }}</h2>
                <p class="text-gray-600 mt-1">{{ item.description }}</p>
                <p class="text-green-500 font-medium mt-2">Available</p>
                <div class="mt-4">
                  <a href="#" @click.prevent="removeItem(productId)" class="text-red-500 hover:text-red-700 transition">Remove</a>
                </div>
              </div>
              <div class="text-right">
                <p class="text-lg font-semibold text-gray-800">${{ parseFloat(item.price).toFixed(2) }}</p>
                <input type="number" min="1" v-model.number="item.quantity" @change="updateQuantity(productId, item.quantity)" class="mt-3 border rounded p-2 w-20 text-center text-gray-600" />
              </div>
            </div>
          </div>
          <p class="font-semibold text-gray-700">{{ Object.keys(cartItems).length }} Items</p>
        </div>

        <!-- Summary Section -->
        <div class="md:w-1/3">
          <div class="bg-white shadow-lg rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Order Summary</h2>
            
            <!-- Form Fields -->
            <div class="mb-6">
              <label for="service-type" class="block text-gray-700 font-medium text-sm mb-1">Service Type</label>
              <input id="service-type" type="text" class="border rounded-lg p-3 w-full" placeholder="Enter your Service type" />
            </div>
            <div class="mb-6">
              <label for="name" class="block text-gray-700 font-medium text-sm mb-1">Name</label>
              <input id="name" type="text" class="border rounded-lg p-3 w-full" placeholder="Enter your name" />
            </div>
            <div class="mb-6">
              <label for="address" class="block text-gray-700 font-medium text-sm mb-1">Address</label>
              <input id="address" type="text" class="border rounded-lg p-3 w-full" placeholder="Enter your Address" />
            </div>
            <div class="mb-6">
              <label for="phone" class="block text-gray-700 font-medium text-sm mb-1">Phone</label>
              <input id="phone" type="text" class="border rounded-lg p-3 w-full" placeholder="Enter your Phone number" />
            </div>
            <div class="mb-6">
              <label for="email" class="block text-gray-700 font-medium text-sm mb-1">Email</label>
              <input id="email" type="email" class="border rounded-lg p-3 w-full" placeholder="Enter your Email address" />
            </div>
            
            <!-- City input and delivery cost calculation -->
            <div class="mb-6">
              <label for="city" class="block text-gray-700 font-medium text-sm mb-1">City</label>
              <input id="city" v-model="city" type="text" class="border rounded-lg p-3 w-full" placeholder="Enter your city" @input="calculateDeliveryCost" />
            </div>

            <div class="space-y-4">
              <p class="flex justify-between text-gray-600">
                <span>Shipping cost</span>
                <span v-if="deliveryCost !== null">${{ deliveryCost.toFixed(2) }}</span>
                <span v-else>Calculating...</span>
              </p>
              <p class="flex justify-between text-lg font-semibold text-gray-800">
                <span>Total</span>
                <span>${{ totalPrice().toFixed(2) }}</span>
              </p>
            </div>

            <p class="text-blue-500 text-sm mt-2">For VAT exemptions, contact administration.</p>
            <button 
              :disabled="!city || !city.trim() || Object.keys(cartItems).length === 0" 
              @click="redirectToStripeCheckout" 
              class="w-full mt-6 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold py-3 rounded-lg shadow-md hover:from-blue-600 hover:to-purple-600 transition disabled:opacity-50 disabled:cursor-not-allowed">
              Pay with Stripe
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { loadStripe } from '@stripe/stripe-js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  cartItems: Object,
});

const city = ref('');
const deliveryCost = ref(null);
const serviceType = ref('');
const name = ref('');
const address = ref('');
const phone = ref('');
const email = ref('');

const totalPrice = () => {
  const itemsTotal = Object.values(props.cartItems).reduce(
    (sum, item) => sum + parseFloat(item.price) * item.quantity,
    0
  );
  return itemsTotal + (deliveryCost.value ? parseFloat(deliveryCost.value) : 0);
};

const updateQuantity = async (productId, quantity) => {
  await router.post(route('cart.update'), { product_id: productId, quantity });
};

const removeItem = async (productId) => {
  if (confirm('Are you sure you want to remove this item from the cart?')) {
    await router.post(route('cart.remove'), { product_id: productId });
  }
};

const calculateDeliveryCost = () => {
  const cityDistances = {
    'riga': 0,
    'jurmala': 30,
    'liepāja': 200,
    'ventspils': 150,
    'daugavpils': 230,
    'jelgava': 50,
    'valmiera': 110,
    'cēsis': 90,
    'jēkabpils': 140,
    'rēzekne': 240,
    'ogre': 35,
    'saldus': 115,
    'kuldīga': 155,
    'madona': 165,
    'gulbene': 180,
    'talsi': 100,
    'alūksne': 200,
    'limbaži': 80,
    'preiļi': 210,
    'bauska': 65,
    'dobele': 75,
    'smiltene': 130,
    'viļņa': 300,
    'kauņa': 270,
    'tallina': 310,
    'helsinki': 400,
    'stokholma': 500,
    'varšava': 650,
    'berlīne': 1000,
    'kopenhāgena': 1100,
    'oslo': 1200,
    'amsterdama': 1500,
    'brisele': 1600,
    'parīze': 1800,
    'londona': 2000,
    'madride': 3000,
    'roma': 2100,
    'vīne': 1300,
    'prāga': 1200,
    'budapešta': 1400,
    'cirīhe': 1800,
    'maskava': 920,
};

  
  const normalizedCity = city.value.toLowerCase();
  const distance = cityDistances[normalizedCity] || 100; 
  
  deliveryCost.value = distance < 50 ? 5 : (5 + distance * 0.1); 
};

let stripe;
onMounted(async () => {
  stripe = await loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);
});

const redirectToStripeCheckout = async () => {
  try {
    const response = await fetch('/api/create-checkout-session', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({
        items: props.cartItems,
        deliveryCost: parseFloat(deliveryCost.value), 
        totalAmount: totalPrice(),
        serviceType: serviceType.value,
        name: name.value,
        address: address.value,
        phone: phone.value,
        email: email.value
      }),
    });
    const session = await response.json();
    const { error } = await stripe.redirectToCheckout({ sessionId: session.id });
    if (error) {
      console.error('Stripe Checkout error:', error);
    }
  } catch (error) {
    console.error('Error creating checkout session:', error);
  }
};
</script>

<style scoped>
  .container {
    max-width: 1200px;
  }
  .bg-white {
    background-color: #ffffff;
  }
  .shadow-lg {
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
  }
  .rounded-lg {
    border-radius: 0.75rem;
  }
  .w-full {
    width: 100%;
  }
  .mt-6 {
    margin-top: 1.5rem;
  }
</style>