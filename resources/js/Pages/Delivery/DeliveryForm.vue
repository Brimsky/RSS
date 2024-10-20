<template>
  <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-md">
    <!-- Кнопка открытия/закрытия формы -->
    <button 
      @click="toggleForm" 
      class="bg-gray-500 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-600 transition-colors duration-300"
    >
      {{ showForm ? 'Close Form' : 'Open Form' }}
    </button>

    <!-- Форма доставки (показывается только если showForm = true) -->
    <form v-if="showForm" @submit.prevent="submitForm" class="space-y-4 mt-4">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Your Name</label>
        <input
          type="text"
          id="name"
          v-model="formData.name"
          placeholder="John Doe"
          class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
        />
        <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
      </div>

      <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Your Address</label>
        <input
          type="text"
          id="address"
          v-model="formData.address"
          placeholder="123 Main St"
          class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
        />
        <span v-if="errors.address" class="text-red-500 text-sm">{{ errors.address }}</span>
      </div>

      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Your Phone</label>
        <input
          type="text"
          id="phone"
          v-model="formData.phone"
          placeholder="+1 234 567 890"
          class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
        />
        <span v-if="errors.phone" class="text-red-500 text-sm">{{ errors.phone }}</span>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Your Email</label>
        <input
          type="email"
          id="email"
          v-model="formData.email"
          placeholder="example@mail.com"
          class="mt-1 block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
        />
        <span v-if="errors.email" class="text-red-500 text-sm">{{ errors.email }}</span>
      </div>

      <div>
        <button
          type="submit"
          class="w-full bg-blue-500 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-600 transition-colors duration-300"
        >
          Submit
        </button>
      </div>
    </form>

    <!-- Уведомление об успешном отправлении -->
    <div v-if="successMessage" class="mt-6 p-4 bg-green-100 text-green-700 border border-green-400 rounded-lg">
      {{ successMessage }}
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showForm: true,
      formData: {
        name: '',
        address: '',
        phone: '',
        email: '',
      },
      errors: {
        name: '',
        address: '',
        phone: '',
        email: '',
      },
      successMessage: '',
    };
  },
  methods: {
    toggleForm() {
      this.showForm = !this.showForm;
    },
    validateForm() {
      let isValid = true;
      this.errors = {
        name: '',
        address: '',
        phone: '',
        email: '',
      };

      // Проверяем имя
      if (!this.formData.name) {
        this.errors.name = 'Name is required.';
        isValid = false;
      }

      // Проверяем адрес
      if (!this.formData.address) {
        this.errors.address = 'Address is required.';
        isValid = false;
      }

      // Проверяем телефон (например, проверка на минимум 10 символов)
      const phonePattern = /^[+]?[\d\s\-()]+$/; // Простая проверка для телефона
      if (!this.formData.phone) {
        this.errors.phone = 'Phone number is required.';
        isValid = false;
      } else if (!phonePattern.test(this.formData.phone)) {
        this.errors.phone = 'Invalid phone number.';
        isValid = false;
      }

      // Проверяем email
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Простая проверка для email
      if (!this.formData.email) {
        this.errors.email = 'Email is required.';
        isValid = false;
      } else if (!emailPattern.test(this.formData.email)) {
        this.errors.email = 'Invalid email format.';
        isValid = false;
      }

      return isValid;
    },
    async submitForm() {
      if (!this.validateForm()) {
        return; // Если форма не валидна, не отправляем данные
      }

      try {
        const response = await fetch('/delivery/store', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          },
          body: JSON.stringify(this.formData),
        });

        if (response.ok) {
          this.successMessage = 'Delivery information saved successfully!';
          this.formData = {
            name: '',
            address: '',
            phone: '',
            email: '',
          };
        } else {
          this.successMessage = 'An error occurred. Please try again.';
        }
      } catch (error) {
        console.error('Ошибка при отправке данных:', error);
        this.successMessage = 'An error occurred. Please try again.';
      }
    },
  },
};
</script>
