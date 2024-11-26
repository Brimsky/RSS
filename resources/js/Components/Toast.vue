<template>
  <Teleport to="body">
    <transition name="fade">
      <div
        v-show="isVisible"
        :class="[
          'fixed z-50 px-4 py-2 rounded-lg shadow-lg transition-all duration-300 transform',
          'top-4 right-4 sm:top-6 sm:right-6',
          typeClasses[type]
        ]"
      >
        <div class="flex items-center space-x-2">
          <svg v-if="type === 'success'" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <svg v-if="type === 'error'" class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <span class="text-sm font-medium">{{ message }}</span>
        </div>
      </div>
    </transition>
  </Teleport>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  message: String,
  duration: {
    type: Number,
    default: 3000,
  },
  type: {
    type: String,
    default: 'success',
    validator: (value) => ['success', 'error'].includes(value)
  }
});

const isVisible = ref(false);
let timeout;

const typeClasses = {
  success: 'bg-green-500 text-white',
  error: 'bg-red-500 text-white'
};

onMounted(() => {
  isVisible.value = true;
  timeout = setTimeout(() => {
    isVisible.value = false;
  }, props.duration);
});

onBeforeUnmount(() => {
  if (timeout) clearTimeout(timeout);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s, transform 0.3s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
  transform: translateY(0);
}
</style>