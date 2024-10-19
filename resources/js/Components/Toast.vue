<template>
    <transition name="fade">
      <div
        v-show="isVisible"
        class="fixed top-5 right-5 bg-green-500 text-white p-4 rounded shadow-lg transition-all ease-in-out"
      >
        {{ message }}
      </div>
    </transition>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  const props = defineProps({
    message: String,
    duration: {
      type: Number,
      default: 3000, // Message display time (3 seconds)
    },
  });
  
  const isVisible = ref(false);
  
  onMounted(() => {
    // Showing the toast when mounting the component
    isVisible.value = true;
  
    // Hide the toast after a set time
    setTimeout(() => {
      isVisible.value = false;
    }, props.duration);
  });
  </script>
  
  <style scoped>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity 3s, transform 3s; 
  }
  
  .fade-enter-from,
  .fade-leave-to {
    opacity: 0;
    transform: translateY(20px); 
  }
  
  .fade-leave-from,
  .fade-enter-to {
    opacity: 1;
    transform: translateY(0); 
    transition: opacity 2s, transform 2s; 
  }
  </style>
  