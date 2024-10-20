<template>
  <div class="calculator bg-white p-6 rounded-lg shadow-sm relative">
    <h3 class="text-xl font-semibold mb-4">Delivery Cost Calculator</h3>
    <div class="flex items-center mb-4">
      <input 
        v-model="location" 
        type="text" 
        class="border rounded py-2 px-4" 
        placeholder="Enter ZIP code or city" 
        @input="suggestCity"
      />
      <button @click="calculateDelivery" class="ml-4 btn-calculate">
        Calculate
      </button>
    </div>

    <!-- Display suggested cities if exists -->
    <ul v-if="suggestedCities.length > 0" class="absolute bg-white border border-gray-300 rounded-md shadow-lg mt-1">
      <li
        class="cursor-pointer px-4 py-2 hover:bg-gray-200"
        v-for="city in suggestedCities"
        :key="city"
        @click="applySuggestion(city)">{{ city }}</li>
    </ul>

    <div v-if="deliveryCost !== null">
      <p class="text-lg font-semibold">Estimated Delivery Cost: <span class="text-blue-500">{{ deliveryCost }} $</span></p>
      <p v-if="deliveryTime !== null" class="mt-4">Estimated Delivery Time: <strong>{{ deliveryTime }} days</strong></p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      location: '',
      suggestedCities: [], // Change to array for multiple suggestions
      deliveryCost: null,
      deliveryTime: null,
      rigaCoordinates: { lat: 56.9496, lon: 24.1052 }, // Coordinates of Riga
      cities: {
        'riga': { lat: 56.9496, lon: 24.1052 },
        'vilnius': { lat: 54.6872, lon: 25.2797 },
        'tallinn': { lat: 59.437, lon: 24.7536 },
        'warsaw': { lat: 52.2297, lon: 21.0122 },
        'helsinki': { lat: 60.1695, lon: 24.9354 },
        'stockholm': { lat: 59.3293, lon: 18.0686 },
        'berlin': { lat: 52.5200, lon: 13.4050 },
        'moscow': { lat: 55.7558, lon: 37.6173 }
      }
    };
  },
  methods: {
    calculateDelivery() {
      const city = this.location.toLowerCase();
      if (this.cities[city]) {
        const distance = this.getDistanceFromLatLonInKm(
          this.rigaCoordinates.lat,
          this.rigaCoordinates.lon,
          this.cities[city].lat,
          this.cities[city].lon
        );
        this.deliveryCost = this.calculateCost(distance);
        this.deliveryTime = this.calculateTime(distance);
        this.suggestedCities = []; // Reset suggested cities when calculation is correct
      } else {
        alert('City not found. Please enter a valid city.');
      }
    },
    
    suggestCity() {
      const input = this.location.toLowerCase();
      this.suggestedCities = [];

      if (input.length > 0) {
        // Find all cities containing the input letters
        for (const city in this.cities) {
          if (city.includes(input)) {
            this.suggestedCities.push(city);
          }
        }
      }
    },
    
    applySuggestion(city) {
      this.location = city; // Auto-fill with the selected city
      this.suggestedCities = []; // Clear suggestions after applying
    },

    getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
      const R = 6371; // Radius of the earth in km
      const dLat = this.deg2rad(lat2 - lat1);
      const dLon = this.deg2rad(lon2 - lon1);
      const a =
        Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) *
        Math.sin(dLon / 2) * Math.sin(dLon / 2);
      const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
      const distance = R * c; // Distance in km
      return distance;
    },
    
    deg2rad(deg) {
      return deg * (Math.PI / 180);
    },

    calculateCost(distance) {
      if (distance < 50) {
        return 5; // Base price for short distances (e.g., Riga)
      } else {
        return (5 + (distance * 0.1)).toFixed(2); // 0.1$ per km
      }
    },

    calculateTime(distance) {
      if (distance < 50) {
        return 1; // 1 day for short distances
      } else {
        return Math.ceil(distance / 100); // 1 day for every 100 km
      }
    }
  }
};
</script>

<style scoped>
.calculator {
  padding: 20px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
}

.absolute {
  position: absolute;
  z-index: 10; /* Ensure suggestions are on top */
}

.btn-calculate {
  background: linear-gradient(90deg, #38b2ac, #2c7a7b); /* Gradient background */
  color: white;
  font-weight: bold;
  padding: 12px 20px; /* Increased padding for a larger button */
  border: none;
  border-radius: 10px; /* More rounded edges */
  cursor: pointer;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Shadow effect */
  transition: background 0.3s ease, transform 0.3s ease; /* Smooth transition */
}

.btn-calculate:hover {
  background: linear-gradient(90deg, #2c7a7b, #38b2ac); /* Change gradient on hover */
  transform: translateY(-2px); /* Slight lift effect on hover */
}
</style>
