<template>
    <div>
      <!-- Форма доставки -->
      <form @submit.prevent="submitForm">
        <input type="text" v-model="formData.name" placeholder="Your Name" />
        <input type="text" v-model="formData.address" placeholder="Your Address" />
        <input type="text" v-model="formData.phone" placeholder="Your Phone" />
        <input type="email" v-model="formData.email" placeholder="Your Email" />
  
        <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
          Submit
        </button>
      </form>
  
      <!-- Уведомление об успешном отправлении -->
      <div v-if="successMessage" class="mt-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded">
        {{ successMessage }}
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        formData: {
          name: '',
          address: '',
          phone: '',
          email: '',
        },
        successMessage: '',
      };
    },
    methods: {
      async submitForm() {
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
  