<template>
    <div>
      <!-- Navigation Bar -->
      <div class="min-h-screen bg-gray-100">
        <nav class="bg-white border-b border-gray-100">
          <!-- Primary Navigation Menu -->
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
              <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                  <Link :href="route('dashboard')">
                    <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                  </Link>
                </div>
  
                <!-- Navigation Links -->
                
              </div>
  
              <!-- Settings Dropdown -->
              <div class="hidden sm:flex sm:items-center sm:ml-6">
                <div class="ml-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md">
                        <button
                          type="button"
                          class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                        >
                          {{ $page.props.auth.user.name }}
                          <svg
                            class="ml-2 -mr-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </span>
                    </template>
  
                    <template #content>
                      <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                      <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                    </template>
                  </Dropdown>
                </div>
              </div>
            </div>
          </div>
        </nav>
  
        <!-- Delivery Content -->
        <div class="container mx-auto py-12">
          <h1 class="text-4xl font-bold mb-6">Delivery Information</h1>
  
          <p class="mb-4">
            We offer fast and reliable delivery for all our products. You can choose a convenient delivery method when placing your order.
          </p>
  
          <!-- Delivery Options -->
          <div class="delivery-options">
            <div class="option bg-white p-4 rounded-lg shadow-sm mb-6">
              <h2 class="text-2xl font-semibold mb-2">Courier Delivery</h2>
              <p>Delivery to your address within 1-2 business days. The cost depends on your location.</p>
            </div>
  
            <div class="option bg-white p-4 rounded-lg shadow-sm">
              <h2 class="text-2xl font-semibold mb-2">Self Pickup</h2>
              <p>You can pick up your order from one of our pickup points at a time convenient for you.</p>
            </div>
          </div>
  
          <!-- Delivery Cost Calculator -->
          <div class="mt-8">
            <div class="calculator bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-xl font-semibold mb-4">Delivery Cost Calculator</h3>
              <div class="flex items-center mb-4">
                <input
                  v-model="location"
                  type="text"
                  class="border rounded py-2 px-4"
                  placeholder="Enter ZIP code or city"
                />
                <button
                  @click="calculateDelivery"
                  class="ml-4 bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700"
                >
                  Calculate
                </button>
              </div>
              <div v-if="deliveryCost !== null">
                <p class="text-lg font-semibold">
                  Estimated Delivery Cost: <span class="text-blue-500">{{ deliveryCost }} $</span>
                </p>
                <p v-if="deliveryTime !== null" class="mt-4">
                  Estimated Delivery Time: <strong>{{ deliveryTime }} days</strong>
                </p>
              </div>
            </div>
          </div>
  
          <!-- Order Tracking -->
          <div class="mt-8">
            <div class="order-tracking bg-white p-6 rounded-lg shadow-sm">
              <h3 class="text-xl font-semibold mb-4">Track Your Order</h3>
              <input
                v-model="orderNumber"
                type="text"
                class="border rounded py-2 px-4"
                placeholder="Enter your order number"
              />
              <button
                @click="trackOrder"
                class="ml-4 bg-purple-500 text-white font-bold py-2 px-4 rounded hover:bg-purple-700"
              >
                Track Order
              </button>
  
              <p v-if="trackingInfo" class="mt-4">
                Status: <strong>{{ trackingInfo.status }}</strong> <br />
                Estimated Delivery: <strong>{{ trackingInfo.estimatedDelivery }}</strong>
              </p>
            </div>
          </div>
  
          <!-- Delivery FAQs -->
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
  
                <!-- Find Pickup Points -->
            <div class="pickup-map mt-8">
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
  
          <!-- Delivery Service Selection -->
          <div class="mt-8">
            <h2 class="text-2xl font-semibold mb-4">Choose Your Delivery Service</h2>
            <div class="flex space-x-4">
              <button @click="openModal('Standard Delivery')" class="bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-700">
                Standard Delivery
              </button>
              <button @click="openModal('Express Delivery')" class="bg-red-500 text-white font-bold py-2 px-4 rounded hover:bg-red-700">
                Express Delivery
              </button>
              <button @click="openModal('Same-Day Delivery')" class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700">
                Same-Day Delivery
              </button>
            </div>
          </div>
  
          <!-- Modal Window -->
          <div v-if="isModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center">
            <div class="bg-white p-8 rounded-lg shadow-lg">
              <h2 class="text-2xl font-bold mb-4">{{ modalTitle }}</h2>
              <p>{{ modalContent }}</p>
              <button @click="closeModal" class="mt-6 bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                Close
              </button>
            </div>
          </div>
  
          <!-- Subscribe to Delivery Updates -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold mb-4">Subscribe to Delivery Updates</h3>
            <input v-model="email" type="email" class="border rounded py-2 px-4" placeholder="Enter your email" />
            <button @click="subscribe" class="ml-4 bg-indigo-500 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700">
                Subscribe
            </button>
      
            <p v-if="emailError" class="text-red-500 mt-2">{{ emailError }}</p>

            <div v-if="isSubscriptionModalOpen" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-lg">
          <h2 class="text-2xl font-bold mb-4">Subscription Successful!</h2>
          <p>Thank you for subscribing to delivery updates. We will keep you informed about your deliveries.</p>
          <button @click="closeSubscriptionModal" class="mt-6 bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
            Close
          </button>
        </div>
      </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script>
    import ApplicationLogo from '@/Components/ApplicationLogo.vue'; 
    import { Link, NavLink } from '@inertiajs/inertia-vue3';
    import Dropdown from '@/Components/Dropdown.vue';
    import DropdownLink from '@/Components/DropdownLink.vue';

export default {
  data() {
    return {
      ApplicationLogo,
      Link,
      NavLink,
      Dropdown,
      DropdownLink,
      location: '',         // For search and calculator
      deliveryCost: null,
      deliveryTime: null,
      orderNumber: '',
      trackingInfo: null,
      isModalOpen: false,
      modalTitle: '',
      modalContent: '',
      email: '',
      emailError: '',
      isSubscriptionModalOpen: false,
      map: null,            // To store the map
      markers: [],          // Markers for pickup points
      pickupPoints: [       // Additional pickup points in different cities
        { lat: 40.7128, lng: -74.006, name: 'Pickup Point 1 - New York' },
        { lat: 40.730610, lng: -73.935242, name: 'Pickup Point 2 - Brooklyn' },
        { lat: 40.6782, lng: -73.9442, name: 'Pickup Point 3 - Queens' },
        { lat: 34.0522, lng: -118.2437, name: 'Pickup Point 1 - Los Angeles' }, 
        { lat: 34.040713, lng: -118.246769, name: 'Pickup Point 2 - Los Angeles' }, 
        { lat: 51.5074, lng: -0.1278, name: 'Pickup Point 1 - London' }, 
        { lat: 51.5156, lng: -0.1410, name: 'Pickup Point 2 - London' }, 
        { lat: 56.9496, lng: 24.1052, name: 'Pickup Point 1 - Riga, Latvia' }, 
        { lat: 56.9489, lng: 24.1064, name: 'Pickup Point 2 - Riga, Latvia' }, 
        { lat: 56.5113, lng: 21.0137, name: 'Pickup Point 1 - Liepaja, Latvia' }, 
        { lat: 56.5187, lng: 21.0129, name: 'Pickup Point 2 - Liepaja, Latvia' }, 
        { lat: 57.5360, lng: 25.4270, name: 'Pickup Point 1 - Valmiera, Latvia' }, 
        { lat: 57.5352, lng: 25.4260, name: 'Pickup Point 2 - Valmiera, Latvia' }, 
      ],
    };
  },

  mounted() {
    this.initMap(); // Initializing the Map When Mounting a Component
  },

  methods: {
    calculateDelivery() {
      // The logic of determining the minimum cost of delivery for Riga
      if (this.location.toLowerCase() === 'riga') {
        this.deliveryCost = (Math.random() * 10 + 5).toFixed(2); // Cheapest price for Riga (from 5 to 15)
      } else {
        this.deliveryCost = (Math.random() * 100).toFixed(2);    // Price for all others
      }
      this.deliveryTime = Math.floor(Math.random() * 5) + 1;
    },
    trackOrder() {
      this.trackingInfo = {
        status: 'In Transit',
        estimatedDelivery: '2023-10-20',
      };
    },
    openModal(serviceType) {
      this.isModalOpen = true;
      if (serviceType === 'Standard Delivery') {
        this.modalTitle = 'Standard Delivery';
        this.modalContent = 'Delivery within 3-5 business days.';
      } else if (serviceType === 'Express Delivery') {
        this.modalTitle = 'Express Delivery';
        this.modalContent = 'Delivery within 1-2 business days.';
      } else if (serviceType === 'Same-Day Delivery') {
        this.modalTitle = 'Same-Day Delivery';
        this.modalContent = 'Delivery on the same day if ordered before 12 PM.';
      }
    },
    closeModal() {
      this.isModalOpen = false;
    },
    validateEmail(email) {
      const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
      return emailPattern.test(email);
    },
    subscribe() {
      if (!this.validateEmail(this.email)) {
        this.emailError = 'Please enter a valid email address.';
        return;
      }
      this.emailError = '';
      this.isSubscriptionModalOpen = true;
    },
    closeSubscriptionModal() {
      this.isSubscriptionModalOpen = false;
    },
    
    // Initializing a map using Leaflet.js and OpenStreetMap
    initMap() {
        this.map = L.map('map').setView([56.9496, 24.1052], 10); // Riga Centre
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
      }).addTo(this.map);

      this.addPickupMarkers();
    },
    
    // Adding markers to the map via Leaflet
            addPickupMarkers() {
        this.clearMarkers();
        
                // Define custom icons for normal, hover, and selected states
                const defaultIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
            shadowSize: [41, 41],
            shadowAnchor: [12, 41]
        });

        const hoverIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-2x.png',  // Используем увеличенную версию стандартной иконки
            iconSize: [35, 51],
            iconAnchor: [17, 51],
            popupAnchor: [1, -34],
            shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
            shadowSize: [51, 51],
            shadowAnchor: [17, 51]
        });

        const selectedIcon = L.icon({
            iconUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon-red.png',  // Используем красную версию иконки
            iconSize: [30, 46],
            iconAnchor: [15, 46],
            popupAnchor: [1, -34],
            shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
            shadowSize: [46, 46],
            shadowAnchor: [15, 46]
        });


        this.pickupPoints.forEach((point) => {
            // Create a marker with the default icon
            const marker = L.marker([point.lat, point.lng], { icon: defaultIcon }).addTo(this.map);

            // Event listeners for marker hover (mouseover and mouseout)
            marker.on('mouseover', () => {
            marker.setIcon(hoverIcon);  // Change to hover icon
            });

            marker.on('mouseout', () => {
            marker.setIcon(defaultIcon);  // Revert to default icon
            });

            // Event listener for marker click (open menu or modal)
            marker.on('click', () => {
            marker.setIcon(selectedIcon);  // Change to selected icon

            // Show custom menu/modal here
            this.showMarkerMenu(point);
            });

            // Bind a popup to the marker that shows the pickup point name
            marker.bindPopup(`<h3>${point.name}</h3>`);

            // Store the marker
            this.markers.push(marker);
        });
        },

        // Method to handle displaying a menu or modal after selecting a marker
        showMarkerMenu(point) {
        // This could open a modal or show a sidebar with more options
        this.modalTitle = `Selected: ${point.name}`;
        this.modalContent = `More options for ${point.name}.`;
        this.isModalOpen = true;
        },

    // Clear all markers from the map
    clearMarkers() {
      this.markers.forEach(marker => this.map.removeLayer(marker));
      this.markers = [];
    },

    findPickupPoints() {
      const lowerCaseLocation = this.location.toLowerCase();
      let centerCoords;

      // Logic of finding a city and changing the center of the map
      if (lowerCaseLocation === 'new york') {
        centerCoords = [40.7128, -74.006];
      } else if (lowerCaseLocation === 'los angeles') {
        centerCoords = [34.0522, -118.2437];
      } else if (lowerCaseLocation === 'london') {
        centerCoords = [51.5074, -0.1278];
      } else if (lowerCaseLocation === 'riga') {
        centerCoords = [56.9496, 24.1052];
      } else if (lowerCaseLocation === 'liepaja') {
        centerCoords = [56.5113, 21.0137];
      } else if (lowerCaseLocation === 'valmiera') {
        centerCoords = [57.5360, 25.4270];
      } else {
        alert('Pickup points not found for this location.');
        return;
      }

      // Centering the map on the found coordinates
      this.map.setView(centerCoords, 12);
    },
  },
};
</script>


  <style scoped>
  .container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }
  
  .delivery-options {
    margin-top: 20px;
  }
  
  .option {
    padding: 15px;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
  }
  
  button {
    transition: background-color 0.3s ease;
  }
  
  #map {
  width: 100%;
  height: 300px;
  transition: all 0.3s ease-in-out;  /* Плавное скрытие карты */
  position: relative;
  z-index: 1;  /* Карта будет на заднем плане */
}

  /* Additional styling for modal */
    .fixed {
    z-index: 50;
    }

    .leaflet-marker-icon {
  transition: transform 0.2s ease-in-out;
}

.custom-popup {
  font-family: Arial, sans-serif;
  font-size: 14px;
  padding: 10px;
  background-color: white;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.15);
  max-width: 250px;
}

.custom-popup h3 {
  margin: 0;
  font-size: 16px;
  color: #333;
}

.custom-popup p {
  font-size: 13px;
  color: #666;
  margin: 10px 0;
}

.custom-popup button {
  padding: 6px 12px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.custom-popup button:hover {
  background-color: #0056b3;
}


  </style>
