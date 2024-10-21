<template>
  <div class="delivery-options">
    <div 
      class="option bg-white p-6 rounded-lg shadow-md mb-6 transition-transform hover:scale-105" 
      :class="{'selected': selectedOption === 'courier'}"
      @click="selectOption('courier')"
    >
      <h2 class="text-2xl font-semibold mb-2">Courier Delivery</h2>
      <p class="text-gray-700 mb-4">Get your order delivered directly to your doorstep within 1-10 business days. The cost varies based on your location and the weight of the package.</p>
      <ul class="list-disc list-inside text-gray-600">
        <li>Fast and reliable service</li>
        <li>Real-time tracking available</li>
        <li>Convenient delivery time slots</li>
      </ul>
      <p class="font-semibold text-blue-600 mt-4">Delivery Fee: Calculated at checkout.</p>
    </div>

    <div 
      class="option bg-white p-6 rounded-lg shadow-md mb-6 transition-transform hover:scale-105" 
      :class="{'selected': selectedOption === 'pickup'}"
      @click="selectOption('pickup')"
    >
      <h2 class="text-2xl font-semibold mb-2">Self Pickup</h2>
      <p class="text-gray-700 mb-4">Choose to pick up your order from one of our convenient pickup points. This option is great for those who prefer to collect their items themselves.</p>
      <ul class="list-disc list-inside text-gray-600">
        <li>No delivery charges</li>
        <li>Flexible pickup hours</li>
        <li>Faster processing time for your order</li>
      </ul>
      <p class="font-semibold text-blue-600 mt-4">Available at various locations in your city.</p>
    </div>
  </div>
</template>

<script>
export default {
  name: "DeliveryOptions",
  data() {
    return {
      selectedOption: null // Track the selected option
    };
  },
  methods: {
    selectOption(option) {
      this.selectedOption = option; // Update selected option on click
    }
  }
};
</script>

<style scoped>
.delivery-options {
  max-width: 800px; /* Limit the width for better layout */
  margin: auto; /* Center align the options */
  padding: 20px; /* Add padding around the options */
}

.option {
  padding: 20px; /* Increased padding for better readability */
  border: 1px solid #e5e7eb;
  border-radius: 12px; /* Slightly more rounded corners */
  background: #f9f9f9; /* Light background color for better contrast */
  transition: transform 0.2s ease; /* Smooth scaling transition */
  cursor: pointer; /* Change cursor to pointer on hover */
}

.option:hover {
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); /* Elevate on hover */
}

.selected {
  border-color: #38b2ac; /* Change border color for selected option */
  background: #e6fffa; /* Light background for selected option */
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* More prominent shadow for selected */
  scale: 105px;
}

h2 {
  color: #2c3e50; /* Darker title color */
}

p {
  line-height: 1.5; /* Improved line spacing for readability */
}

.list-disc {
  margin-left: 1.5rem; /* Indent list items */
}
</style>
