<template>
  <div>
    <h3 class="text-xl font-semibold mb-4">Find a Pickup Point</h3>
    <input
      v-model="location"
      @input="suggestCity"  
      type="text"
      class="border rounded py-2 px-4 mb-4"
      placeholder="Enter city or ZIP code"
    />
    <ul v-if="suggestions.length" class="absolute bg-white border border-gray-300 rounded-md shadow-lg mt-1">
      <li
        v-for="(suggestion, index) in suggestions"
        :key="index"
        @click="selectSuggestion(suggestion)"  
        class="cursor-pointer px-4 py-2 hover:bg-gray-200"
      >
        {{ suggestion }}
      </li>
    </ul>
    <button @click="findPickupPoints" class="ml-4 btn-calculate">
      Find Pickup Points
    </button>
    <div id="map" class="w-full h-64 bg-gray-200 mt-4"></div>
  </div>
</template>

<script>
export default {
  props: ['pickupPoints'],
  data() {
    return {
      location: '',
      map: null,
      markers: [],
      suggestions: [],  // Array to hold suggested city names
      validCities: [    // List of valid city names
        'Riga',
        'Vilnius',
        'Tallinn',
        'Warsaw',
        'Helsinki',
        'Stockholm',
        'Berlin',
        'Moscow',
      ],
    };
  },
  mounted() {
    this.initMap();
  },
  methods: {
    initMap() {
      this.map = L.map('map').setView([56.9496, 24.1052], 10); // Riga Centre
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(this.map);
      this.addPickupMarkers();
    },
    addPickupMarkers() {
      this.clearMarkers();
      const defaultIcon = L.icon({
        iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
        shadowSize: [41, 41],
        shadowAnchor: [12, 41]
      });

      this.pickupPoints.forEach((point) => {
        const marker = L.marker([point.lat, point.lng], { icon: defaultIcon }).addTo(this.map);
        marker.bindPopup(`<h3>${point.name}</h3>`);
        this.markers.push(marker);
      });
    },
    clearMarkers() {
      this.markers.forEach(marker => this.map.removeLayer(marker));
      this.markers = [];
    },
    findPickupPoints() {
      let centerCoords;
      const lowerCaseLocation = this.location.toLowerCase();

      if (lowerCaseLocation === 'riga') {
        centerCoords = [56.9496, 24.1052];
      } else if (lowerCaseLocation === 'vilnius') {
        centerCoords = [54.6872, 25.2797];
      } else if (lowerCaseLocation === 'tallinn') {
        centerCoords = [59.437, 24.7536];
      } else if (lowerCaseLocation === 'warsaw') {
        centerCoords = [52.2297, 21.0122];
      } else if (lowerCaseLocation === 'helsinki') {
        centerCoords = [60.1695, 24.9354];
      } else if (lowerCaseLocation === 'stockholm') {
        centerCoords = [59.3293, 18.0686];
      } else if (lowerCaseLocation === 'berlin') {
        centerCoords = [52.5200, 13.4050];
      } else if (lowerCaseLocation === 'moscow') {
        centerCoords = [55.7558, 37.6173];
      }

      if (centerCoords) {
        this.map.setView(centerCoords, 10);
        this.addPickupMarkers();
      }
    },
    suggestCity() {
      const input = this.location.toLowerCase();
      this.suggestions = this.validCities.filter(city =>
        city.toLowerCase().includes(input) && input.length > 0
      );
    },
    selectSuggestion(suggestion) {
      this.location = suggestion;  // Set the input to the selected suggestion
      this.suggestions = [];  // Clear the suggestions
    }
  }
};
</script>

<style scoped>
#map {
  width: 100%;
  height: 300px;
  z-index: 1;
}
ul {
  list-style: none;
  padding: 0;
  margin: 0;
  position: absolute;  /* Positioning the suggestion dropdown */
  z-index: 10;  /* Ensure it appears above other elements */
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
