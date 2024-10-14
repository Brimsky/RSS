<!-- resources/js/Pages/Cart.vue -->
<template>
  <Head title="My Cart" />

  <AuthenticatedLayout>
    <div class="container mx-auto p-4">
      <h1 class="text-2xl font-bold mb-4">
        <span class="inline-block align-middle mr-2">
          <!-- SVG Icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" d="M16 11V7a4 4 0 00-8
                  0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
        </span>
        My Cart
      </h1>

      <div class="flex flex-col md:flex-row gap-4">
        <!-- Cart Items -->
        <div class="md:w-2/3">
          <div
            v-for="(item, productId) in cartItems"
            :key="productId"
            class="flex items-center border-b py-4"
          >
            <!-- Product Image (Optional) -->
            <!-- Uncomment if you have images -->
            <!--
            <img
              :src="item.image"
              :alt="item.name"
              class="w-24 h-32 object-cover mr-4"
            />
            -->
            <div class="flex-grow">
              <h2 class="font-semibold">{{ item.name }}</h2>
              <p>{{ item.description }}</p>
              <p>In Stock</p>
              <div class="mt-2">
                <a href="#" @click.prevent="removeItem(productId)" class="text-blue-600 mr-2">Remove</a>
                <!-- Additional actions can be added here -->
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

        <!-- Sidebar with Promo Code and Total -->
        <div class="md:w-1/3">
          <div class="bg-gray-100 p-4 rounded">
            <h2 class="font-semibold mb-2">ENTER PROMO CODE</h2>
            <div class="flex">
              <input
                v-model="promoCode"
                type="text"
                placeholder="Promo Code"
                class="flex-grow border rounded-l p-2"
              />
              <button @click="submitPromoCode" class="bg-black text-white px-4 py-2 rounded-r">
                Submit
              </button>
            </div>
            <div class="mt-4">
              <p class="flex justify-between"><span>Shipping cost</span><span>{{ shippingCost }}</span></p>
              <p class="flex justify-between"><span>Discount</span><span>-${{ discount.toFixed(2) }}</span></p>
              <p class="flex justify-between"><span>Tax</span><span>{{ tax }}</span></p>
              <p class="flex justify-between font-semibold mt-2">
                <span>Estimated Total</span><span>${{ totalPrice().toFixed(2) }}</span>
              </p>
            </div>
            <p class="text-sm mt-2">
              or 4 interest-free payments of ${{ (totalPrice() / 4).toFixed(2) }} with
              <span class="font-semibold">afterpay</span>
            </p>
            <p class="text-sm text-yellow-600 mt-2">
              You're $10.01 away from free shipping!
            </p>
            <button @click="handleStripePayment" class="w-full bg-blue-500 text-white font-semibold py-2 rounded mt-4">
              Pay with Stripe
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia'; // Import Inertia for navigation

// Accept cartItems as a prop
const props = defineProps({
  cartItems: Object,
});

// Reactive references
const promoCode = ref('');
const shippingCost = ref('TBD');
const discount = ref(0);
const tax = ref('TBD');

// Compute the total price
const totalPrice = () =>
  Object.values(props.cartItems).reduce(
    (sum, item) => sum + parseFloat(item.price) * item.quantity,
    0
  );

// Methods
const submitPromoCode = () => {
  // Handle promo code submission (to be implemented)
  console.log('Promo code submitted:', promoCode.value);
};

const handleStripePayment = () => {
  // Placeholder function for Stripe payment integration
  console.log('Initiating Stripe payment for $', totalPrice().toFixed(2));
};

const updateQuantity = async (productId, quantity) => {
  // Update the quantity in the backend
  await Inertia.post(route('cart.update'), { product_id: productId, quantity });
};

const removeItem = async (productId) => {
  if (confirm('Are you sure you want to remove this item from the cart?')) {
    await Inertia.post(route('cart.remove'), { product_id: productId }, {
      preserveState: false,
      onSuccess: () => {
        // Optionally, handle any additional logic here
      },
    });
  }
};

</script>

<style scoped>
/* Add your custom styles here */

/* Example styles for the cart */
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
