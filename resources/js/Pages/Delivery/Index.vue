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

        <!-- Кнопка открытия формы заполнения данных для доставки -->
        <div>
          <button @click="openForm" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
            Fill Delivery Information
          </button>
        </div>

        <!-- Вставляем компонент DeliveryForm вместо ручной формы -->
        <div v-if="isFormVisible" class="mt-8">
          <DeliveryForm />
        </div>

        <!-- Калькулятор стоимости доставки -->
        <div class="mt-8">
          <DeliveryCalculator />
        </div>

        <!-- Отслеживание заказа -->
        <div class="mt-8">
          <OrderTracking />
        </div>

        <!-- Часто задаваемые вопросы -->
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

        <!-- Карта пунктов самовывоза -->
        <delivery-map :pickupPoints="pickupPoints"></delivery-map>

        <!-- Выбор типа доставки -->
        <div class="mt-8">
          <h2 class="text-2xl font-semibold mb-4">Choose Your Delivery Service</h2>
          <div class="flex space-x-4">
            <button @click="selectDelivery('Standard Delivery')" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-700">
              Standard Delivery
            </button>
            <button @click="selectDelivery('Express Delivery')" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
              Express Delivery
            </button>
            <button @click="selectDelivery('Same-Day Delivery')" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
              Same-Day Delivery
            </button>
          </div>
        </div>

        <!-- Сообщение об успешном выборе -->
        <div v-if="successMessage" class="mt-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
          {{ successMessage }}
        </div>
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
import DeliveryForm from './DeliveryForm.vue'; // Импортируем компонент

export default {
  components: {
    AuthenticatedLayout,
    DeliveryMap,
    DeliveryOptions,
    DeliveryCalculator,
    OrderTracking,
    DeliveryForm, // Регистрируем компонент
  },

  data() {
    return {
      isFormVisible: false,
      successMessage: '',
      pickupPoints: [
        { lat: 40.7128, lng: -74.006, name: 'Pickup Point 1 - New York' },
        { lat: 56.9496, lng: 24.1052, name: 'Pickup Point 1 - Riga, Latvia' }
      ],
    };
  },

  methods: {
    openForm() {
      this.isFormVisible = true; // Открываем форму
    },
    async selectDelivery(serviceType) {
      try {
        const response = await fetch('/api/delivery-selection', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ serviceType }),
        });

        if (response.ok) {
          this.successMessage = `You have successfully selected ${serviceType}.`;
        } else {
          this.successMessage = 'An error occurred. Please try again.';
        }
      } catch (error) {
        this.successMessage = 'An error occurred. Please try again.';
      }

      setTimeout(() => {
        this.successMessage = '';
      }, 5000);
    },
  },
};
</script>
