<template>
    <div>
      <h3 class="text-xl font-semibold mb-4">Find a Pickup Point</h3>
      <input
        v-model="location"
        type="text"
        class="border rounded py-2 px-4 mb-4"
        placeholder="Enter city or ZIP code"
      />
      <button @click="findPickupPoints" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
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
        if (lowerCaseLocation === 'new york') {
          centerCoords = [40.7128, -74.006];
        } else if (lowerCaseLocation === 'los angeles') {
          centerCoords = [34.0522, -118.2437];
        } else if (lowerCaseLocation === 'riga') {
          centerCoords = [56.9496, 24.1052];
        }
  
        if (centerCoords) {
          this.map.setView(centerCoords, 10);
          this.addPickupMarkers();
        }
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
  </style>
  