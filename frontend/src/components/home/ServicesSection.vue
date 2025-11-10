<template>
  <div class="container-fluid pt-5" ref="servicesContainer">
    <div class="container">
 <!-- <div class="section-title">
        <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">About Us</h4>
        <h1 class="display-4">{{ aboutTitle || 'Serving Since 2010' }}</h1>
      </div> -->

      <div class="section-title" ref="sectionTitle">
        <h4 class="text-primary text-uppercase section-subtitle" style="letter-spacing: 5px;">Our Services</h4>
        <h1 class="display-4 section-main-title">{{ servicesTitle || 'Authentic Swahili Dining Experience' }}</h1>
      </div>
      <div class="row" ref="servicesRow">
        <!-- QR Code Demo Section -->
        <div class="col-lg-12 mb-5 qr-demo-section" ref="qrDemo">
          <div class="text-center p-4 qr-demo-card">
            <div class="qr-code-container">
              <div class="qr-code-wrapper">
                <svg width="200" height="200" xmlns="http://www.w3.org/2000/svg" class="qr-code-demo">
                  <!-- QR Code Pattern -->
                  <rect width="200" height="200" fill="#ffffff" stroke="#916d4d" stroke-width="2"/>
                  
                  <!-- Corner squares -->
                  <rect x="10" y="10" width="40" height="40" fill="#916d4d"/>
                  <rect x="150" y="10" width="40" height="40" fill="#916d4d"/>
                  <rect x="10" y="150" width="40" height="40" fill="#916d4d"/>
                  <rect x="20" y="20" width="20" height="20" fill="#ffffff"/>
                  <rect x="160" y="20" width="20" height="20" fill="#ffffff"/>
                  <rect x="20" y="160" width="20" height="20" fill="#ffffff"/>
                  
                  <!-- Data patterns -->
                  <rect x="70" y="20" width="10" height="10" fill="#916d4d"/>
                  <rect x="90" y="20" width="10" height="10" fill="#916d4d"/>
                  <rect x="110" y="20" width="10" height="10" fill="#916d4d"/>
                  <rect x="70" y="40" width="10" height="10" fill="#916d4d"/>
                  <rect x="110" y="40" width="10" height="10" fill="#916d4d"/>
                  <rect x="90" y="60" width="10" height="10" fill="#916d4d"/>
                  <rect x="70" y="80" width="10" height="10" fill="#916d4d"/>
                  <rect x="90" y="80" width="10" height="10" fill="#916d4d"/>
                  <rect x="110" y="80" width="10" height="10" fill="#916d4d"/>
                  
                  <rect x="20" y="70" width="10" height="10" fill="#916d4d"/>
                  <rect x="40" y="90" width="10" height="10" fill="#916d4d"/>
                  <rect x="20" y="110" width="10" height="10" fill="#916d4d"/>
                  
                  <rect x="160" y="70" width="10" height="10" fill="#916d4d"/>
                  <rect x="180" y="90" width="10" height="10" fill="#916d4d"/>
                  <rect x="160" y="110" width="10" height="10" fill="#916d4d"/>
                  
                  <!-- Center pattern -->
                  <rect x="90" y="90" width="20" height="20" fill="#916d4d"/>
                  <rect x="95" y="95" width="10" height="10" fill="#ffffff"/>
                  
                  <!-- Random data pattern -->
                  <rect x="70" y="120" width="10" height="10" fill="#916d4d"/>
                  <rect x="90" y="140" width="10" height="10" fill="#916d4d"/>
                  <rect x="110" y="160" width="10" height="10" fill="#916d4d"/>
                  <rect x="130" y="120" width="10" height="10" fill="#916d4d"/>
                  <rect x="150" y="140" width="10" height="10" fill="#916d4d"/>
                  <rect x="170" y="160" width="10" height="10" fill="#916d4d"/>
                  
                  <!-- Asili Cafe logo in center -->
                  <text x="100" y="105" text-anchor="middle" fill="#da9f5b" font-size="8" font-weight="bold">ASILI</text>
                </svg>
              </div>
              <div class="qr-instructions">
                <h3 class="qr-title">Scan & Order</h3>
                <p class="qr-description">Point your camera at this QR code to access our digital menu and place your order instantly</p>
                <div class="qr-benefits">
                  <span class="benefit-tag"><i class="fa fa-check"></i> Contactless Ordering</span>
                  <span class="benefit-tag"><i class="fa fa-check"></i> Real-time Menu</span>
                  <span class="benefit-tag"><i class="fa fa-check"></i> Instant Payment</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- QR Ordering Process Steps -->
        <div 
          v-for="(service, index) in displayServices" 
          :key="service.id" 
          class="col-lg-6 mb-5 service-item"
          :data-index="index"
        >
          <div class="row align-items-center service-card">
            <div class="col-sm-5 service-image-wrapper">
              <div class="service-step-number">{{ index + 1 }}</div>
              <img class="img-fluid mb-3 mb-sm-0 service-image" :src="service.image" :alt="service.title">
            </div>
            <div class="col-sm-7 service-content">
              <h4 class="service-title">
                <i v-if="service.icon" :class="service.icon + ' service-icon'"></i>
                {{ service.title }}
              </h4>
              <p class="m-0 service-description">{{ service.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { useServiceStore } from '../../stores/service'
import { useCompanyStore } from '../../stores/company'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

// Template refs for animations
const servicesContainer = ref(null)
const sectionTitle = ref(null)
const servicesRow = ref(null)

const serviceStore = useServiceStore()
const companyStore = useCompanyStore()

// Fallback services data focusing on QR code ordering system
const fallbackServices = [
  {
    id: 1,
    title: 'Scan QR Code for Menu',
    description: 'Simply scan the QR code at your table to instantly access our full digital menu. Browse through our authentic Swahili cuisine selections, traditional dishes, seafood specialties, and special offers from both branches.',
    image: '/img/service-1.jpg',
    icon: 'fa fa-qrcode'
  },
  {
    id: 2,
    title: 'Select Your Order',
    description: 'Choose from our extensive menu featuring traditional Swahili cuisine, fresh seafood, aromatic pilau, coconut curries, and authentic beverages. Customize your order with available options and variations.',
    image: '/img/service-2.jpg',
    icon: 'fa fa-mobile-alt'
  },
  {
    id: 3,
    title: 'Easy Digital Payment',
    description: 'Pay securely through our digital platform with mobile money, card payments, or cash on delivery. Get instant confirmation and track your order status in real-time.',
    image: '/img/service-3.jpg',
    icon: 'fa fa-credit-card'
  },
  {
    id: 4,
    title: 'Fast Table Service',
    description: 'Your order is sent directly to our kitchen and prepared fresh. Enjoy contactless ordering while our staff delivers your authentic Asili Cafe experience right to your table.',
    image: '/img/service-4.jpg',
    icon: 'fa fa-utensils'
  }
]

// Computed properties following About page pattern
const servicesTitle = computed(() => companyStore.getCompanyInfo('services_title'))

const displayServices = computed(() => {
  // Use API data if available, otherwise fallback data
  return serviceStore.services && serviceStore.services.length > 0 
    ? serviceStore.services 
    : fallbackServices
})

// Initialize GSAP animations
const initAnimations = () => {
  // Set initial states for animations
  gsap.set('.section-subtitle', { y: 50, opacity: 0 })
  gsap.set('.section-main-title', { y: 60, opacity: 0 })
  gsap.set('.qr-demo-card', { y: 80, opacity: 0, scale: 0.9 })
  gsap.set('.qr-code-wrapper', { scale: 0, rotation: -10 })
  gsap.set('.qr-instructions', { x: 50, opacity: 0 })
  gsap.set('.benefit-tag', { y: 20, opacity: 0 })
  gsap.set('.service-item', { y: 80, opacity: 0 })
  gsap.set('.service-image', { scale: 0.8, opacity: 0 })
  gsap.set('.service-title', { x: -30, opacity: 0 })
  gsap.set('.service-description', { x: -20, opacity: 0 })
  gsap.set('.service-icon', { rotation: 0, scale: 0 })
  gsap.set('.service-step-number', { scale: 0, rotation: -180 })

  // Title animations
  const titleTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: sectionTitle.value,
      start: 'top 80%',
      end: 'bottom 20%',
      toggleActions: 'play none none reverse'
    }
  })

  titleTimeline
    .to('.section-subtitle', {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    })
    .to('.section-main-title', {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: 'power2.out'
    }, '-=0.4')

  // QR Demo section animations
  const qrTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: '.qr-demo-section',
      start: 'top 80%',
      end: 'bottom 20%',
      toggleActions: 'play none none reverse'
    }
  })

  qrTimeline
    .to('.qr-demo-card', {
      y: 0,
      opacity: 1,
      scale: 1,
      duration: 0.8,
      ease: 'power2.out'
    })
    .to('.qr-code-wrapper', {
      scale: 1,
      rotation: 0,
      duration: 0.8,
      ease: 'back.out(1.7)'
    }, '-=0.4')
    .to('.qr-instructions', {
      x: 0,
      opacity: 1,
      duration: 0.6,
      ease: 'power2.out'
    }, '-=0.3')
    .to('.benefit-tag', {
      y: 0,
      opacity: 1,
      duration: 0.4,
      stagger: 0.1,
      ease: 'back.out(1.3)'
    }, '-=0.2')

  // Service items animations with stagger
  const serviceItems = document.querySelectorAll('.service-item')
  
  serviceItems.forEach((item, index) => {
    const serviceTimeline = gsap.timeline({
      scrollTrigger: {
        trigger: item,
        start: 'top 85%',
        end: 'bottom 20%',
        toggleActions: 'play none none reverse'
      }
    })

    // Animate the whole service item
    serviceTimeline.to(item, {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    })

    // Animate step number
    serviceTimeline.to(item.querySelector('.service-step-number'), {
      scale: 1,
      rotation: 0,
      duration: 0.6,
      ease: 'back.out(1.7)'
    }, '-=0.6')

    // Animate service image with scale and fade
    serviceTimeline.to(item.querySelector('.service-image'), {
      scale: 1,
      opacity: 1,
      duration: 0.6,
      ease: 'back.out(1.7)'
    }, '-=0.4')

    // Animate service icon with rotation and scale
    serviceTimeline.to(item.querySelector('.service-icon'), {
      rotation: 360,
      scale: 1,
      duration: 0.5,
      ease: 'back.out(1.7)'
    }, '-=0.4')

    // Animate title and description with slide effect
    serviceTimeline.to(item.querySelector('.service-title'), {
      x: 0,
      opacity: 1,
      duration: 0.6,
      ease: 'power2.out'
    }, '-=0.4')
    .to(item.querySelector('.service-description'), {
      x: 0,
      opacity: 1,
      duration: 0.6,
      ease: 'power2.out'
    }, '-=0.2')

    // Add hover animations
    const serviceCard = item.querySelector('.service-card')
    
    serviceCard.addEventListener('mouseenter', () => {
      gsap.to(serviceCard, {
        scale: 1.02,
        duration: 0.3,
        ease: 'power2.out'
      })
      gsap.to(item.querySelector('.service-image'), {
        scale: 1.05,
        duration: 0.3,
        ease: 'power2.out'
      })
      gsap.to(item.querySelector('.service-icon'), {
        rotation: '+=180',
        scale: 1.1,
        duration: 0.3,
        ease: 'power2.out'
      })
      gsap.to(item.querySelector('.service-step-number'), {
        scale: 1.1,
        duration: 0.3,
        ease: 'power2.out'
      })
    })

    serviceCard.addEventListener('mouseleave', () => {
      gsap.to(serviceCard, {
        scale: 1,
        duration: 0.3,
        ease: 'power2.out'
      })
      gsap.to(item.querySelector('.service-image'), {
        scale: 1,
        duration: 0.3,
        ease: 'power2.out'
      })
      gsap.to(item.querySelector('.service-icon'), {
        rotation: '+=0',
        scale: 1,
        duration: 0.3,
        ease: 'power2.out'
      })
      gsap.to(item.querySelector('.service-step-number'), {
        scale: 1,
        duration: 0.3,
        ease: 'power2.out'
      })
    })
  })

  // Add QR code hover animation
  const qrWrapper = document.querySelector('.qr-code-wrapper')
  if (qrWrapper) {
    qrWrapper.addEventListener('mouseenter', () => {
      gsap.to(qrWrapper, {
        scale: 1.05,
        duration: 0.3,
        ease: 'power2.out'
      })
    })

    qrWrapper.addEventListener('mouseleave', () => {
      gsap.to(qrWrapper, {
        scale: 1,
        duration: 0.3,
        ease: 'power2.out'
      })
    })
  }
}

onMounted(async () => {
  try {
    // Fetch company info first (like About page)
    if (!companyStore.companyInfo || Object.keys(companyStore.companyInfo).length === 0) {
      await companyStore.fetchCompanyInfo()
    }
    
    // Then fetch services data
    await serviceStore.fetchServices()

    // Initialize animations after content is loaded
    await nextTick()
    initAnimations()
  } catch (error) {
    console.error('Error loading services:', error)
    // Fallback data will be used automatically
    await nextTick()
    initAnimations()
  }
})

onUnmounted(() => {
  // Clean up ScrollTrigger instances
  ScrollTrigger.getAll().forEach(trigger => trigger.kill())
})
</script>

<style scoped>
.service-icon {
  color: #231f20;
  margin-right: 10px;
  transition: all 0.3s ease;
}

/* QR Code Demo Section */
.qr-demo-section {
  margin-bottom: 3rem;
}

.qr-demo-card {
  background: linear-gradient(135deg, rgba(218, 159, 91, 0.1) 0%, rgba(145, 109, 77, 0.05) 100%);
  border-radius: 20px;
  border: 2px solid rgba(218, 159, 91, 0.2);
  position: relative;
  overflow: hidden;
}

.qr-demo-card::before {
  content: '';
  position: absolute;
  top: -50%;
  left: -50%;
  width: 200%;
  height: 200%;
  background: conic-gradient(from 0deg, transparent, rgba(218, 159, 91, 0.1), transparent);
  animation: rotate 10s linear infinite;
  z-index: -1;
}

@keyframes rotate {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.qr-code-container {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 3rem;
  flex-wrap: wrap;
  position: relative;
  z-index: 2;
}

.qr-code-wrapper {
  position: relative;
  padding: 20px;
  background: white;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(145, 109, 77, 0.2);
  border: 3px solid var(--primary-brown);
}

.qr-code-wrapper::before {
  content: '';
  position: absolute;
  top: -10px;
  left: -10px;
  right: -10px;
  bottom: -10px;
  background: linear-gradient(45deg, var(--primary-brown), #DA9F5B, var(--primary-brown));
  border-radius: 20px;
  z-index: -1;
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% { opacity: 0.7; }
  50% { opacity: 1; }
}

.qr-code-demo {
  border-radius: 10px;
  transition: transform 0.3s ease;
}

.qr-code-wrapper:hover .qr-code-demo {
  transform: scale(1.05);
}

.qr-instructions {
  text-align: center;
  max-width: 400px;
}

.qr-title {
  color: var(--primary-brown);
  font-size: 2rem;
  font-weight: bold;
  margin-bottom: 1rem;
  font-family: 'Playfair Display', serif;
}

.qr-description {
  color: #666;
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 1.5rem;
}

.qr-benefits {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  justify-content: center;
}

.benefit-tag {
  background: linear-gradient(135deg, var(--primary-brown), #DA9F5B);
  color: white;
  padding: 8px 16px;
  border-radius: 25px;
  font-size: 0.9rem;
  font-weight: 600;
  box-shadow: 0 3px 10px rgba(145, 109, 77, 0.3);
  transition: transform 0.3s ease;
}

.benefit-tag:hover {
  transform: translateY(-2px);
}

.benefit-tag i {
  margin-right: 5px;
}

/* Service Step Numbers */
.service-step-number {
  position: absolute;
  top: -10px;
  left: -10px;
  width: 40px;
  height: 40px;
  background: linear-gradient(135deg, var(--primary-brown), #DA9F5B);
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.2rem;
  z-index: 10;
  box-shadow: 0 5px 15px rgba(145, 109, 77, 0.4);
  border: 3px solid white;
}

/* Animation enhancement styles */
.service-item {
  cursor: pointer;
  transition: transform 0.3s ease;
}

.service-card {
  padding: 15px;
  border-radius: 15px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  border: 2px solid transparent;
}

.service-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, rgba(218, 159, 91, 0.05) 0%, transparent 50%);
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 15px;
}

.service-card:hover::before {
  opacity: 1;
}

.service-card:hover {
  box-shadow: 0 15px 35px rgba(218, 159, 91, 0.2);
  background: rgba(218, 159, 91, 0.02);
  border-color: var(--primary-brown);
  transform: translateY(-5px);
}

.service-image-wrapper {
  overflow: hidden;
  border-radius: 12px;
  position: relative;
}

.service-image {
  transition: transform 0.3s ease;
  border-radius: 12px;
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.service-content {
  position: relative;
  z-index: 2;
}

.service-title {
  color: var(--primary-brown);
  margin-bottom: 15px;
  transition: all 0.3s ease;
  font-size: 1.3rem;
  font-weight: 600;
}

.service-description {
  color: #666;
  line-height: 1.7;
  transition: all 0.3s ease;
}

/* Fix the overlapping decorative line issue */
.section-title {
  position: relative;
  padding: 120px 0 35px 0;
  text-align: center;
}

.section-title::after {
  position: absolute;
  content: "";
  width: 2px;
  height: 80px;
  top: 20px;
  left: 50%;
  margin-left: -1px;
  background: #DA9F5B;
}

/* Title animation styles */
.section-subtitle {
  margin-bottom: 10px;
}

.section-main-title {
  margin-bottom: 0;
  background: linear-gradient(45deg, var(--primary-brown), #DA9F5B);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .qr-code-container {
    flex-direction: column;
    gap: 2rem;
  }
  
  .qr-title {
    font-size: 1.5rem;
  }
  
  .qr-benefits {
    flex-direction: column;
    align-items: center;
  }
  
  .service-card {
    margin-bottom: 30px;
  }
  
  .service-item {
    margin-bottom: 2rem;
  }
  
  .service-step-number {
    width: 35px;
    height: 35px;
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  .qr-code-wrapper {
    padding: 15px;
  }
  
  .qr-code-demo {
    width: 150px;
    height: 150px;
  }
  
  .service-image {
    height: 150px;
  }
}
</style>