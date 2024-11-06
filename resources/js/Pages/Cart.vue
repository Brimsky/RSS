<template>
  <Head title="My Cart" />
  <AuthenticatedLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6 flex items-center">
        <span class="inline-block mr-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-600" fill="none"
            viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
        </span>
        My Cart
      </h1>

      <div class="flex flex-col md:flex-row gap-6">
        <!-- Cart Items -->
        <div class="md:w-2/3 space-y-4">
          <div
            v-for="(item, productId) in cartItems"
            :key="productId"
            class="flex items-center bg-white shadow-md p-4 rounded-lg"
          >
            <div class="flex-grow">
              <h2 class="font-semibold text-lg text-gray-800">{{ item.name }}</h2>
              <p class="text-sm text-gray-600">{{ item.description }}</p>
              <p class="text-green-600">In Stock</p>
              <div class="mt-2">
                <a href="#" @click.prevent="removeItem(productId)" class="text-red-600 hover:text-red-800 transition">
                  Remove
                </a>
              </div>
            </div>
            <div class="text-right">
              <p class="text-lg font-semibold text-gray-800">${{ parseFloat(item.price).toFixed(2) }}</p>
              <input
                type="number"
                min="1"
                v-model.number="item.quantity"
                @change="updateQuantity(productId, item.quantity)"
                class="mt-2 border rounded p-1 w-16 text-center text-gray-600"
              />
            </div>
          </div>
          <p class="mt-4 font-semibold text-gray-700">{{ Object.keys(cartItems).length }} Items</p>
        </div>

        <!-- Summary Section -->
        <div class="md:w-1/3">
          <div class="bg-white shadow-lg p-6 rounded-lg">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Order Summary</h2>
            
            <!-- City input and delivery cost calculation -->
            <div class="mb-4">
              <label for="city" class="block text-sm font-medium text-gray-700">City</label>
              <input 
                id="city"
                v-model="city"
                type="text" 
                class="border rounded p-2 w-full"
                placeholder="Enter your city"
                @input="calculateDeliveryCost"
              />
              <p v-if="deliveryCost !== null" class="text-sm text-gray-600 mt-1">
                Estimated Delivery Cost: ${{ deliveryCost.toFixed(2) }}
              </p>
            </div>

            <div class="space-y-3">
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

            <p class="text-sm text-gray-500 mt-3">
              Final VAT may vary based on your location.
            </p>
            <p class="text-sm text-blue-600 mt-2">
              For VAT exemptions, contact administration.
            </p>
            <button @click="redirectToStripeCheckout"
              class="w-full mt-3 bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold py-2 rounded-lg shadow-md hover:from-blue-600 hover:to-purple-600 transition">
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
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import { loadStripe } from '@stripe/stripe-js';

const props = defineProps({
  cartItems: Object,
});



const city = ref('');
const deliveryCost = ref(null);

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

// Calculate delivery cost based on city
const calculateDeliveryCost = () => {
  const cityDistances = {
    'riga': 0,
    'jurmala': 30,
    'liepaja': 200,
    'ventspils': 150,
    'daugavpils': 230,
    // add more cities with approximate distances in km
  };
  
  const normalizedCity = city.value.toLowerCase();
  const distance = cityDistances[normalizedCity] || 100; // Default distance if city not found
  
  deliveryCost.value = distance < 50 ? 5 : (5 + distance * 0.1); // Basic calculation formula
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
        deliveryCost: parseFloat(deliveryCost.value), // Send delivery cost separately
        totalAmount: totalPrice() // Send the total including delivery cost
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
h1 {
  font-size: 2.25rem;
  line-height: 2.5rem;
}
.bg-white {
  background-color: #ffffff;
}
.shadow-md {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.shadow-lg {
  box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}
.rounded-lg {
  border-radius: 0.5rem;
}
.w-full {
  width: 100%;
}
.mt-3 {
  margin-top: 0.75rem;
}
.mt-4 {
  margin-top: 1rem;
}
</style>
