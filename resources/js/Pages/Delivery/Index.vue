<template>
    <div>
      <!-- Navigation Bar -->
      <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
          <!-- Primary Navigation Menu -->
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
              <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                  <Link :href="route('dashboard')">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                  </Link>
                </div>
  
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                  <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                    Dashboard
                  </NavLink>
                  <NavLink :href="route('cart')" :active="route().current('cart')">Cart</NavLink>
                  <NavLink :href="route('products.index')" :active="route().current('products.index')">Products</NavLink>
                </div>
              </div>
  
              <!-- Settings Dropdown -->
              <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                          {{ $page.props.auth.user.name }}
                          <svg
                            class="ml-2 -mr-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </span>
                    </template>
  
                    <template #content>
                      <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                      <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                    </template>
                  </Dropdown>
                </div>
              </div>
            </div>
          </div>
        </nav>
  
        <!-- Delivery Content -->
        <div class="container mx-auto py-12">
          <h1 class="text-4xl font-bold mb-6">Delivery Information</h1>
  
          <p class="mb-4">
            We offer fast and reliable delivery for all our products. You can choose a convenient delivery method when placing your order.
          </p>
  
          <!-- Delivery Options -->
          <div class="delivery-options">
            <div class="option bg-white p-4 rounded-lg shadow-sm mb-6">
              <h2 class="text-2xl font-semibold mb-2">Courier Delivery</h2>
              <p>Delivery to your address within 1-2 business days. The cost depends on your location.</p>
            </div>
  
            <div class="option bg-white p-4 rounded-lg shadow-sm">
              <h2 class="text-2xl font-semibold mb-2">Self Pickup</h2>
              <p>You can pick up your order from one of our pickup points at a time convenient for you.</p>
            </div>
          </div>
  
          <!-- Delivery Cost Calculator -->
          <div class="mt-8">
            <div class="calculator bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-xl font-semibold mb-4">Delivery Cost Calculator</h3>
              <div class="flex items-center mb-4">
                <input
                  v-model="location"
                  type="text"
                  class="border rounded py-2 px-4"
                  placeholder="Enter ZIP code or city"
                />
                <button
                  @click="calculateDelivery"
                  class="ml-4 bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700"
                >
                  Calculate
                </button>
              </div>
              <div v-if="deliveryCost !== null">
                <p class="text-lg font-semibold">
                  Estimated Delivery Cost: <span class="text-blue-500">{{ deliveryCost }} $</span>
                </p>
                <p v-if="deliveryTime !== null" class="mt-4">
                  Estimated Delivery Time: <strong>{{ deliveryTime }} days</strong>
                </p>
              </div>
            </div>
          </div>
  
          <!-- Order Tracking -->
          <div class="mt-8">
            <div class="order-tracking bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-xl font-semibold mb-4">Track Your Order</h3>
              <input
                v-model="orderNumber"
                type="text"
                class="border rounded py-2 px-4"
                placeholder="Enter your order number"
              />
              <button
                @click="trackOrder"
                class="ml-4 bg-purple-500 text-white font-bold py-2 px-4 rounded hover:bg-purple-700"
              >
                Track Order
              </button>
  
              <p v-if="trackingInfo" class="mt-4">
                Status: <strong>{{ trackingInfo.status }}</strong> <br />
                Estimated Delivery: <strong>{{ trackingInfo.estimatedDelivery }}</strong>
              </p>
            </div>
          </div>
  
          <!-- Delivery FAQs -->
          <div class="mt-8">
            <h2 class="text-2xl font-semibold mb-4">Delivery FAQs</h2>
            <div class="faq-item mb-4">
              <h3 class="font-bold">When will my order arrive?</h3>
              <p>Most orders are delivered within 3-5 business days.</p>
            </div>
            <div class="faq-item mb-4">
              <h3 class="font-bold">Can I change my delivery address?</h3>
              <p>Yes, you can update your address before the order is shipped.</p>
            </div>
          </div>
  
          <!-- Find Pickup Points (Map placeholder) -->
          <div class="pickup-map mt-8">
            <h3 class="text-xl font-semibold mb-4">Find a Pickup Point</h3>
            <div id="map" class="w-full h-64 bg-gray-200"></div>
          </div>
  
          <!-- Delivery Service Selection -->
            <div class="mt-8">
                <h2 class="text-2xl font-semibold mb-4">Choose Your Delivery Service</h2>
                <div class="flex space-x-4">
                <button @click="openModal('standard')" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-700">
                    Standard Delivery
                </button>
                <button @click="openModal('express')" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
                    Express Delivery
                </button>
                <button @click="openModal('sameDay')" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
                    Same-Day Delivery
                </button>
                </div>
            </div>

            <!-- Modal for displaying delivery information -->
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-xl font-semibold mb-4">{{ modalTitle }}</h3>
                <p class="mb-6">{{ modalContent }}</p>
                <button @click="closeModal" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
                    Close
                </button>
                </div>
            </div>
  
          <!-- Subscribe to Delivery Updates -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4">Get Delivery Updates</h3>
                <input
                v-model="email"
                type="email"
                class="border rounded py-2 px-4"
                placeholder="Enter your email"
                />
                <button
                @click="subscribeToUpdates"
                class="ml-4 bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700"
                >
                Subscribe
                </button>
                <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
            </div>

            <!-- Confirmation Modal -->
            <div v-if="isModalOpen" class="fixed inset-0 flex items-center justify-center bg-gray-800 bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
                <h3 class="text-xl font-semibold mb-4">Subscription Confirmed</h3>
                <p class="mb-6">You have successfully subscribed to delivery updates with the email: {{ email }}</p>
                <button @click="closeDeliveryModal" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">
                Close
                </button>
                </div>
            </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import ApplicationLogo from '@/Components/ApplicationLogo.vue';
  import Dropdown from '@/Components/Dropdown.vue';
  import DropdownLink from '@/Components/DropdownLink.vue';
  import NavLink from '@/Components/NavLink.vue';
  import { Link } from '@inertiajs/vue3';
  
  const isModalOpen = ref(false);
  const showingNavigationDropdown = ref(false);
  const location = ref('');
  const deliveryCost = ref(null);
  const deliveryTime = ref(null);
  const orderNumber = ref('');
  const trackingInfo = ref(null);
  const email = ref('');
  const error = ref('');
  const modalTitle = ref('');
  const modalContent = ref('');

  const calculateDelivery = () => {
    if (location.value === 'New York') {
      deliveryCost.value = 10;
      deliveryTime.value = 2;
    } else {
      deliveryCost.value = 20;
      deliveryTime.value = 5;
    }
  };
  
  const trackOrder = () => {
    if (orderNumber.value === '12345') {
      trackingInfo.value = {
        status: 'In Transit',
        estimatedDelivery: '3 days',
      };
    } else {
      trackingInfo.value = {
        status: 'Pending',
        estimatedDelivery: '5 days',
      };
    }
  };
  
    function openModal(service) {
    isModalOpen.value = true;
    if (service === 'standard') {
        modalTitle.value = 'Standard Delivery';
        modalContent.value = 'Standard delivery takes 3-5 business days.';
    } else if (service === 'express') {
        modalTitle.value = 'Express Delivery';
        modalContent.value = 'Express delivery ensures that your package arrives within 1-2 business days.';
    } else if (service === 'sameDay') {
        modalTitle.value = 'Same-Day Delivery';
        modalContent.value = 'Same-day delivery is available for orders placed before noon.';
    }
    }

    function closeModal() {
    isModalOpen.value = false;
    modalTitle.value = '';
    modalContent.value = '';
    }

        const subscribeToUpdates = () => {
    if (validateEmail(email.value)) {
        error.value = '';  // Clear previous error if email is valid
        isModalOpen.value = true;  // Open modal to confirm subscription
    } else {
        error.value = 'Please enter a valid email address.';
    }
    };

        const closeDeliveryModal = () => {
    isModalOpen.value = false;
    email.value = '';  // Clear email after subscription
    };

    function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
    }
  </script>
  
  <style scoped>
  .container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  .delivery-options {
    margin-top: 20px;
  }
  
  .option {
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
  }
  
  button {
    transition: background-color 0.3s ease;
  }
  
  #map {
    width: 100%;
    height: 300px;
    background-color: #f0f0f0;
  }

  /* Additional styling for modal */
    .fixed {
    z-index: 50;
    }
  </style>
  