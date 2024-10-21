<!-- resources/js/Pages/Cart.vue -->
<template>
  <Head title="My Cart" />
  <AuthenticatedLayout>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">
        <span class="inline-block align-middle mr-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
        </span>
        My Cart
      </h1>

      <div class="flex flex-col md:flex-row gap-4">
        <!-- Groza saturs -->
        <div class="md:w-2/3">
          <div
            v-for="(item, productId) in cartItems"
            :key="productId"
            class="flex items-center border-b py-4"
          >
            <div class="flex-grow">
              <h2 class="font-semibold">{{ item.name }}</h2>
              <p>{{ item.description }}</p>
              <p>In Stock</p>
              <div class="mt-2">
                <a href="#" @click.prevent="removeItem(productId)" class="text-blue-600 mr-2">Remove</a>
              </div>
            </div>
            <div class="text-right">
              <p class="font-semibold">${{ parseFloat(item.price).toFixed(2) }}</p>
              <input
                type="number"
                min="1"
                v-model.number="item.quantity"
                @change="updateQuantity(productId, item.quantity)"
                class="mt-2 border rounded p-1 w-16 text-center"
              />
            </div>
          </div>
          <p class="mt-4">{{ Object.keys(cartItems).length }} Items</p>
        </div>

        <!-- Informācija par nodokļiem, piegādi un summu -->
        <div class="md:w-1/3">
          <div class="bg-gray-100 p-4 rounded">
            <h2 class="font-semibold mb-2">Product/s cost including Delivery</h2>
            <div class="flex">
            </div>
            <div class="mt-4">
              <p class="flex justify-between"><span>Shipping cost</span>Calculating..</p>
              <p class="flex justify-between"><span>Discount</span><span>None given</span></p>
              <p class="flex justify-between"><span>VAT</span><span>21%</span></p>
              <p class="flex justify-between font-semibold mt-2">
                <span>Estimated Total</span><span>${{ totalPrice().toFixed(2) }}</span>
              </p>
            </div>
            <p class="text-sm mt-2">
              Final VAT calculation may differ based on your location and applicable rates.
            </p>
            <p class="text-sm text-violet-600 mt-2">
              Contact administration for VAT write-off's
            </p>
            <button @click="redirectToDelivery" class="w-full bg-gradient-to-r from-blue-400 to-green-500 text-white font-semibold py-2 rounded mt-4">
              Delivery information
            </button>
            <button @click="redirectToStripeCheckout" class="w-full bg-gradient-to-r from-blue-400 to-violet-500 text-white font-semibold py-2 rounded mt-4">
              Pay securely with Stripe
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';
import { loadStripe } from '@stripe/stripe-js';
import Toast from '@/Components/Toast.vue';

// Definē kā objektu
const props = defineProps({
  cartItems: Object,
});



// Compute the total price
const totalPrice = () =>
  Object.values(props.cartItems).reduce(
    (sum, item) => sum + parseFloat(item.price) * item.quantity,
    0
  );

// Methods

const updateQuantity = async (productId, quantity) => {
  await router.post(route('cart.update'), { product_id: productId, quantity });
};

// Toast domāts priekš produkta noņemšanas paziņojuma

const removeItem = async (productId) => {
  if (confirm('Are you sure you want to remove this item from the cart?')) {
    await router.post(route('cart.remove'), { product_id: productId });
  }
};

// Load Stripe
let stripe;
onMounted(async () => {
  stripe = await loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);
});

// Redirect to Stripe Checkout
const redirectToStripeCheckout = async () => {
  try {
    // Make an API call to the server to create a Checkout Session
    const response = await fetch('/api/create-checkout-session', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify({
        items: props.cartItems,
      }),
    });

    const session = await response.json();

    // Redirect to Stripe Checkout
    const { error } = await stripe.redirectToCheckout({
      sessionId: session.id,
    });

    if (error) {
      console.error('Stripe Checkout error:', error);
    }
  } catch (error) {
    console.error('Error creating checkout session:', error);
  }
};

const redirectToDelivery = () => {
  router.get(route('delivery.index'));
};

</script>

<style scoped>
.container {
  max-width: 1200px;
}

h1 {
  display: flex;
  align-items: center;
}

.flex {
  display: flex;
}

.border-b {
  border-bottom: 1px solid #e2e8f0;
}

.bg-gray-100 {
  background-color: #f7fafc;
}

.rounded {
  border-radius: 0.25rem;
}

.p-4 {
  padding: 1rem;
}

.mt-2 {
  margin-top: 0.5rem;
}

.mt-4 {
  margin-top: 1rem;
}

.text-blue-600 {
  color: #3182ce;
}

.text-yellow-600 {
  color: #d69e2e;
}

.font-semibold {
  font-weight: 600;
}

.text-right {
  text-align: right;
}

.w-full {
  width: 100%;
}

.bg-blue-500 {
  background-color: #4299e1;
}

.text-white {
  color: #fff;
}

.py-2 {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.px-4 {
  padding-left: 1rem;
  padding-right: 1rem;
}

.rounded-r {
  border-top-right-radius: 0.25rem;
  border-bottom-right-radius: 0.25rem;
}

.border {
  border: 1px solid #cbd5e0;
}

.rounded-l {
  border-top-left-radius: 0.25rem;
  border-bottom-left-radius: 0.25rem;
}

.flex-grow {
  flex-grow: 1;
}

.w-16 {
  width: 4rem;
}

.text-center {
  text-align: center;
}
</style>
