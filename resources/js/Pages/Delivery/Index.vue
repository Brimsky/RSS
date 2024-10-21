<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100">
      <div class="container mx-auto py-12">
        <h1 class="text-4xl font-bold mb-6">Delivery Information</h1>

        <p class="mb-4">
          We offer fast and reliable delivery for all our products. You can choose a convenient delivery method when placing your order.
        </p>

        <div class="delivery-options">
          <DeliveryOptions />
        </div>

        <div>
          <button @click="openForm" class="mt-8 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg transform transition duration-300 hover:scale-105 hover:from-blue-600 hover:to-blue-800 hover:shadow-xl">
            Fill Delivery Information
          </button>
        </div>

        <div v-if="isFormVisible" class="mt-8">
          <DeliveryForm />
        </div>

        <div class="mt-8 justify-center">
          <DeliveryCalculator />
        </div>

        <div class="mt-8">
          <OrderTracking />
        </div>

        <div class="mt-8">
          <h2 class="text-2xl font-semibold mb-4">Delivery FAQs</h2>
          <div class="faq-item mb-4">
            <h3 class="font-bold">When will my order arrive?</h3>
            <p>Most orders are delivered within 3-9 business days.</p>
          </div>
          <div class="faq-item mb-4">
            <h3 class="font-bold">Can I change my delivery address?</h3>
            <p>Yes, you can update your address before the order is shipped.</p>
          </div>
        </div>
        <delivery-map :pickupPoints="pickupPoints"></delivery-map>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import DeliveryMap from './DeliveryMap.vue';
import DeliveryOptions from './DeliveryOptions.vue';
import DeliveryCalculator from './DeliveryCalculator.vue';
import OrderTracking from './OrderTracking.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeliveryForm from './DeliveryForm.vue'; 

export default {
  components: {
    AuthenticatedLayout,
    DeliveryMap,
    DeliveryOptions,
    DeliveryCalculator,
    OrderTracking,
    DeliveryForm, 
  },

  data() {
    return {
      isFormVisible: false,
      successMessage: '',
      pickupPoints: [
        { lat: 56.9496, lng: 24.1052, name: 'Pickup Point 1 - Riga, Latvia' },
        { lat: 54.6872, lng: 25.2797, name: 'Pickup Point 1 - Vilnius, Lithuania' },
        { lat: 59.437, lng: 24.7536, name: 'Pickup Point 1 - Tallinn, Estonia' },
        { lat: 52.2297, lng: 21.0122, name: 'Pickup Point 1 - Warsaw, Poland' },
        { lat: 60.1695, lng: 24.9354, name: 'Pickup Point 1 - Helsinki, Finland' },
        { lat: 59.3293, lng: 18.0686, name: 'Pickup Point 1 - Stockholm, Sweden' },
        { lat: 52.5200, lng: 13.4050, name: 'Pickup Point 1 - Berlin, Germany' },
        { lat: 55.7558, lng: 37.6173, name: 'Pickup Point 1 - Moscow, Russia' }
      ],
    };
  },

  methods: {
    openForm() {
      this.isFormVisible = true; 
    },
  },
};
</script>
