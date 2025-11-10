<template>
  <div class="container-fluid py-5">
    <div class="container">
      <div class="section-title">
        <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Testimonials</h4>
        <h1 class="display-4">What Our Community Says</h1>
      </div>
      <div v-if="loading" class="loading">
        <div class="spinner"></div>
      </div>
      <div v-else-if="testimonials.length > 0" class="testimonials-marquee-container">
        <!-- First row - moving right to left -->
        <div class="marquee-row marquee-left">
          <div class="marquee-track">
            <div 
              v-for="testimonial in [...testimonials, ...testimonials]" 
              :key="`left-${testimonial.id}`"
              class="testimonial-card"
            >
              <div class="testimonial-header">
                <img 
                  class="testimonial-avatar" 
                  :src="testimonial.image || '/img/testimonial-1.jpg'" 
                  :alt="testimonial.name"
                >
                <div class="testimonial-info">
                  <h5 class="testimonial-name">{{ testimonial.name }}</h5>
                  <p class="testimonial-profession">{{ testimonial.profession }}</p>
                  <div class="rating">
                    <i 
                      v-for="star in 5" 
                      :key="star"
                      class="fa fa-star"
                      :class="{ 'text-warning': star <= testimonial.rating, 'text-muted': star > testimonial.rating }"
                    ></i>
                  </div>
                </div>
              </div>
              <p class="testimonial-message">{{ testimonial.message }}</p>
            </div>
          </div>
        </div>

        <!-- Second row - moving left to right -->
        <div class="marquee-row marquee-right">
          <div class="marquee-track">
            <div 
              v-for="testimonial in [...testimonials.slice().reverse(), ...testimonials.slice().reverse()]" 
              :key="`right-${testimonial.id}`"
              class="testimonial-card"
            >
              <div class="testimonial-header">
                <img 
                  class="testimonial-avatar" 
                  :src="testimonial.image || '/img/testimonial-1.jpg'" 
                  :alt="testimonial.name"
                >
                <div class="testimonial-info">
                  <h5 class="testimonial-name">{{ testimonial.name }}</h5>
                  <p class="testimonial-profession">{{ testimonial.profession }}</p>
                  <div class="rating">
                    <i 
                      v-for="star in 5" 
                      :key="star"
                      class="fa fa-star"
                      :class="{ 'text-warning': star <= testimonial.rating, 'text-muted': star > testimonial.rating }"
                    ></i>
                  </div>
                </div>
              </div>
              <p class="testimonial-message">{{ testimonial.message }}</p>
            </div>
          </div>
        </div>

        <!-- Third row - moving right to left (slower) -->
        <div class="marquee-row marquee-left marquee-slow">
          <div class="marquee-track">
            <div 
              v-for="testimonial in [...testimonials, ...testimonials]" 
              :key="`slow-${testimonial.id}`"
              class="testimonial-card testimonial-card-compact"
            >
              <div class="testimonial-header">
                <img 
                  class="testimonial-avatar" 
                  :src="testimonial.image || '/img/testimonial-1.jpg'" 
                  :alt="testimonial.name"
                >
                <div class="testimonial-info">
                  <h6 class="testimonial-name">{{ testimonial.name }}</h6>
                  <small class="testimonial-profession">{{ testimonial.profession }}</small>
                  <div class="rating">
                    <i 
                      v-for="star in 5" 
                      :key="star"
                      class="fa fa-star"
                      :class="{ 'text-warning': star <= testimonial.rating, 'text-muted': star > testimonial.rating }"
                    ></i>
                  </div>
                </div>
              </div>
              <p class="testimonial-message">{{ testimonial.message.substring(0, 100) }}...</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useTestimonialStore } from '../../stores/testimonial'

const testimonialStore = useTestimonialStore()
const testimonials = ref([])
const loading = ref(false) // Start with false to show content immediately

// Set fallback testimonials immediately
testimonials.value = [
  {
    id: 1,
    name: 'Amina Juma',
    profession: 'Karume Institute Student',
    message: 'Perfect study spot opposite our institute! Amazing traditional dishes and the staff is so friendly. I come here every day to enjoy authentic Swahili meals between classes. The Mtweni branch is peaceful and perfect for learning.',
    image: '/img/testimonial-1.jpg',
    rating: 5
  },
  {
    id: 2,
    name: 'Dr. Salim Khamis',
    profession: 'Medical Doctor',
    message: 'As a doctor working at Mmoja Hospital, the Kinazi mmoja branch is my go-to place for quality Swahili cuisine and quick meals between shifts. The location is perfect and the service is consistently excellent.',
    image: '/img/testimonial-2.jpg',
    rating: 5
  },
  {
    id: 3,
    name: 'Fatma Mrisho',
    profession: 'Local Business Owner',
    message: 'I bring all my business meetings to both branches. The ambiance is professional yet relaxed, and their traditional Swahili dishes are the best on the island. The pilau and seafood specialties are absolutely incredible!',
    image: '/img/testimonial-3.jpg',
    rating: 5
  },
  {
    id: 4,
    name: 'Hassan Ali',
    profession: 'Tech Student',
    message: 'Great WiFi at both locations, comfortable seating, and excellent traditional cuisine. Perfect place for coding sessions and group projects. The authentic flavors and friendly service make us feel right at home.',
    image: '/img/testimonial-4.jpg',
    rating: 4
  }
]

onMounted(async () => {
  try {
    loading.value = true
    await testimonialStore.fetchTestimonials()
    if (testimonialStore.testimonials && testimonialStore.testimonials.length > 0) {
      testimonials.value = testimonialStore.testimonials
    }
    // If no testimonials from API, keep fallback data
  } catch (error) {
    console.error('Error loading testimonials:', error)
    // Keep fallback testimonials that were already set
  } finally {
    loading.value = false
  }
})
</script>

<style scoped>
.loading {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 200px;
}

.spinner {
  border: 4px solid #eeeff1;
  border-top: 4px solid #916d4d;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Marquee Container */
.testimonials-marquee-container {
  width: 100%;
  overflow: hidden;
  padding: 20px 0;
  position: relative;
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.testimonials-marquee-container::before,
.testimonials-marquee-container::after {
  content: '';
  position: absolute;
  top: 0;
  width: 100px;
  height: 100%;
  z-index: 2;
  pointer-events: none;
}

.testimonials-marquee-container::before {
  left: 0;
  background: linear-gradient(to right, #f8f9fa, transparent);
}

.testimonials-marquee-container::after {
  right: 0;
  background: linear-gradient(to left, #f8f9fa, transparent);
}

/* Marquee Rows */
.marquee-row {
  display: flex;
  width: 100%;
  margin-bottom: 20px;
  overflow: hidden;
}

.marquee-track {
  display: flex;
  animation-timing-function: linear;
  animation-iteration-count: infinite;
  gap: 20px;
  flex-shrink: 0;
}

/* Animation Directions and Speeds */
.marquee-left .marquee-track {
  animation: scrollLeft 30s infinite linear;
}

.marquee-right .marquee-track {
  animation: scrollRight 35s infinite linear;
}

.marquee-slow .marquee-track {
  animation-duration: 45s;
}

@keyframes scrollLeft {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

@keyframes scrollRight {
  0% {
    transform: translateX(-50%);
  }
  100% {
    transform: translateX(0);
  }
}

/* Testimonial Cards */
.testimonial-card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  min-width: 350px;
  max-width: 400px;
  box-shadow: 0 8px 25px rgba(145, 109, 77, 0.1);
  border: 1px solid rgba(145, 109, 77, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  position: relative;
  overflow: hidden;
  flex-shrink: 0;
}

.testimonial-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, #916d4d, #da9f5b, #916d4d);
  background-size: 200% 100%;
  animation: shimmer 3s infinite;
}

@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

.testimonial-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 15px 35px rgba(145, 109, 77, 0.2);
}

.testimonial-card-compact {
  min-width: 280px;
  max-width: 320px;
  padding: 20px;
}

/* Testimonial Header */
.testimonial-header {
  display: flex;
  align-items: center;
  margin-bottom: 15px;
  gap: 15px;
}

.testimonial-avatar {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #916d4d;
  flex-shrink: 0;
  box-shadow: 0 4px 12px rgba(145, 109, 77, 0.3);
}

.testimonial-card-compact .testimonial-avatar {
  width: 50px;
  height: 50px;
}

.testimonial-info {
  flex: 1;
  min-width: 0;
}

.testimonial-name {
  margin: 0 0 5px 0;
  color: #916d4d;
  font-weight: 600;
  font-size: 1.1rem;
  line-height: 1.2;
}

.testimonial-card-compact .testimonial-name {
  font-size: 1rem;
  margin-bottom: 3px;
}

.testimonial-profession {
  margin: 0 0 8px 0;
  color: #6c757d;
  font-style: italic;
  font-size: 0.9rem;
  line-height: 1.2;
}

.testimonial-card-compact .testimonial-profession {
  font-size: 0.8rem;
  margin-bottom: 5px;
}

/* Rating Stars */
.rating {
  display: flex;
  gap: 2px;
}

.rating .fa-star {
  font-size: 0.9rem;
  color: #ffc107;
}

.rating .fa-star.text-muted {
  color: #dee2e6 !important;
}

.testimonial-card-compact .rating .fa-star {
  font-size: 0.8rem;
}

/* Testimonial Message */
.testimonial-message {
  color: #495057;
  line-height: 1.6;
  margin: 0;
  font-size: 0.95rem;
  position: relative;
  padding-left: 20px;
}

.testimonial-message::before {
  content: '"';
  position: absolute;
  left: 0;
  top: -5px;
  font-size: 2rem;
  color: #916d4d;
  font-family: serif;
  line-height: 1;
}

.testimonial-message::after {
  content: '"';
  color: #916d4d;
  font-size: 1.2rem;
  font-family: serif;
}

.testimonial-card-compact .testimonial-message {
  font-size: 0.85rem;
  line-height: 1.5;
}

/* Hover Animations */
.testimonial-card:hover .testimonial-avatar {
  transform: scale(1.1);
  transition: transform 0.3s ease;
}

.testimonial-card:hover .testimonial-name {
  color: #da9f5b;
  transition: color 0.3s ease;
}

/* Pause animation on hover */
.marquee-row:hover .marquee-track {
  animation-play-state: paused;
}

/* Responsive Design */
@media (max-width: 768px) {
  .testimonial-card {
    min-width: 280px;
    max-width: 320px;
    padding: 20px;
  }
  
  .testimonial-card-compact {
    min-width: 240px;
    max-width: 280px;
    padding: 15px;
  }

  .marquee-left .marquee-track {
    animation-duration: 25s;
  }

  .marquee-right .marquee-track {
    animation-duration: 28s;
  }

  .marquee-slow .marquee-track {
    animation-duration: 35s;
  }

  .testimonials-marquee-container::before,
  .testimonials-marquee-container::after {
    width: 50px;
  }
}

@media (max-width: 480px) {
  .testimonial-card {
    min-width: 250px;
    max-width: 280px;
  }
  
  .testimonial-card-compact {
    min-width: 200px;
    max-width: 240px;
  }
}

/* Custom scrollbar for overflow areas */
.testimonials-marquee-container::-webkit-scrollbar {
  display: none;
}

.testimonials-marquee-container {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>