<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6">
      <div class="w-full max-w-4xl flex items-center space-x-12">
        <!-- Left side with RSS text -->
        <div class="w-1/3 flex justify-center">
          <div class="text-9xl font-bold text-gray-800 tracking-tighter">
            RSS
          </div>
        </div>
        
        <!-- Right side with registration form -->
        <div class="w-2/3">
          <h2 class="text-3xl font-semibold text-gray-800 mb-8">Create your account</h2>
          
          <form @submit.prevent="submit" class="bg-white rounded-lg shadow-lg p-8 space-y-6">
            <div>
              <InputLabel for="name" value="Name" class="block text-sm font-medium text-gray-700 mb-1" />
              <TextInput
                id="name"
                type="text"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                       transition duration-150 ease-in-out"
                v-model="form.name"
                required
                autofocus
                autocomplete="name"
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>
  
            <div>
              <InputLabel for="email" value="Email" class="block text-sm font-medium text-gray-700 mb-1" />
              <TextInput
                id="email"
                type="email"
                class="block w-full px-4 py-3 border border-gray-300 rounded-md text-sm
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                       transition duration-150 ease-in-out"
                v-model="form.email"
                required
                autocomplete="username"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>
  
            <div>
              <InputLabel for="password" value="Password" class="block text-sm font-medium text-gray-700 mb-1" />
              <div class="relative">
                <TextInput
                  id="password"
                  :type="showPassword ? 'text' : 'password'"
                  class="block w-full px-4 py-3 border border-gray-300 rounded-md text-sm
                         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                         transition duration-150 ease-in-out"
                  v-model="form.password"
                  required
                  autocomplete="new-password"
                />
                <button 
                  type="button" 
                  @click="togglePassword('password')" 
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500 hover:text-gray-700 focus:outline-none"
                >
                  {{ showPassword ? 'Hide' : 'Show' }}
                </button>
              </div>
              <InputError class="mt-2" :message="form.errors.password" />
            </div>
  
            <div>
              <InputLabel for="password_confirmation" value="Confirm Password" class="block text-sm font-medium text-gray-700 mb-1" />
              <div class="relative">
                <TextInput
                  id="password_confirmation"
                  :type="showConfirmPassword ? 'text' : 'password'"
                  class="block w-full px-4 py-3 border border-gray-300 rounded-md text-sm
                         focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500
                         transition duration-150 ease-in-out"
                  v-model="form.password_confirmation"
                  required
                  autocomplete="new-password"
                />
                <button 
                  type="button" 
                  @click="togglePassword('confirm')" 
                  class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5 text-gray-500 hover:text-gray-700 focus:outline-none"
                >
                  {{ showConfirmPassword ? 'Hide' : 'Show' }}
                </button>
              </div>
              <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>
  
            <div>
              <PrimaryButton 
                class="w-full justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 
                       transition duration-150 ease-in-out"
                :class="{ 'opacity-75 cursor-not-allowed': form.processing }" 
                :disabled="form.processing"
              >
                Create Account
              </PrimaryButton>
            </div>
          </form>
  
          <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <Link
              :href="route('login')"
              class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out"
            >
              Sign in
            </Link>
          </p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import GuestLayout from '@/Layouts/GuestLayout.vue';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import TextInput from '@/Components/TextInput.vue';
  import { Head, Link, useForm } from '@inertiajs/vue3';
  
  const form = useForm({
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
  });
  
  const submit = () => {
      form.post(route('register'), {
          onFinish: () => form.reset('password', 'password_confirmation'),
      });
  };
  
  const showPassword = ref(false);
  const showConfirmPassword = ref(false);
  
  const togglePassword = (field) => {
      if (field === 'password') {
          showPassword.value = !showPassword.value;
      } else {
          showConfirmPassword.value = !showConfirmPassword.value;
      }
  };
  </script>