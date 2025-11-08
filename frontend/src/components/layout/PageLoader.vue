<template>
  <transition name="loader-fade">
    <div v-if="isLoading" class="page-loader" ref="loaderContainer">
      <div class="loader-content">
        <!-- Animated Jar Logo -->
        <div class="jar-container" ref="jarContainer">
          <img 
            src="/img/asili_cafe_icon.png" 
            alt="Asili Cafe" 
            class="jar-logo"
            ref="jarLogo"
          >
          <!-- Coffee Pour Animation -->
          <div class="coffee-pour" ref="coffeePour"></div>
          <!-- Steam Animation -->
          <div class="steam-container">
            <div class="steam steam-1" ref="steam1"></div>
            <div class="steam steam-2" ref="steam2"></div>
            <div class="steam steam-3" ref="steam3"></div>
          </div>
        </div>
        
        <!-- Loading Text -->
        <div class="loading-text" ref="loadingText">
          <h2 class="brand-name">Asili Cafe</h2>
          <!-- <p class="loading-message" ref="loadingMessage">Brewing your experience...</p> -->
          <div class="loading-dots">
            <span class="dot dot-1" ref="dot1"></span>
            <span class="dot dot-2" ref="dot2"></span>
            <span class="dot dot-3" ref="dot3"></span>
          </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-container">
          <div class="progress-bar" ref="progressBar"></div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { gsap } from 'gsap'

const isLoading = ref(true)
const loaderContainer = ref(null)
const jarContainer = ref(null)
const jarLogo = ref(null)
const coffeePour = ref(null)
const steam1 = ref(null)
const steam2 = ref(null)
const steam3 = ref(null)
const loadingText = ref(null)
const loadingMessage = ref(null)
const dot1 = ref(null)
const dot2 = ref(null)
const dot3 = ref(null)
const progressBar = ref(null)

let mainTimeline = null

const initAnimations = () => {
  // Create master timeline
  mainTimeline = gsap.timeline({
    onComplete: () => {
      // Hide loader after animation completes
      setTimeout(() => {
        fadeOutLoader()
      }, 500)
    }
  })

  // Initial states
  gsap.set(jarLogo.value, { scale: 0, rotation: -180, opacity: 0 })
  gsap.set(coffeePour.value, { scaleY: 0, transformOrigin: 'top center' })
  gsap.set([steam1.value, steam2.value, steam3.value], { opacity: 0, y: 20 })
  gsap.set(loadingText.value, { opacity: 0, y: 30 })
  gsap.set([dot1.value, dot2.value, dot3.value], { scale: 0 })
  gsap.set(progressBar.value, { scaleX: 0, transformOrigin: 'left center' })

  // Animation sequence
  mainTimeline
    // Jar entrance with bounce
    .to(jarLogo.value, {
      scale: 1,
      rotation: 0,
      opacity: 1,
      duration: 1.2,
      ease: 'elastic.out(1, 0.6)'
    })
    // Jar gentle float
    .to(jarLogo.value, {
      y: -10,
      duration: 0.8,
      ease: 'power1.inOut',
      yoyo: true,
      repeat: -1
    }, '-=0.3')
    // Coffee pour effect
    .to(coffeePour.value, {
      scaleY: 1,
      duration: 1.5,
      ease: 'power2.inOut'
    }, '-=0.8')
    // Steam rising
    .to([steam1.value, steam2.value, steam3.value], {
      opacity: 0.7,
      y: -50,
      duration: 2,
      stagger: 0.2,
      ease: 'power1.out',
      repeat: -1
    }, '-=1')
    // Loading text fade in
    .to(loadingText.value, {
      opacity: 1,
      y: 0,
      duration: 0.8,
      ease: 'power2.out'
    }, '-=1.5')
    // Dots animation
    .to([dot1.value, dot2.value, dot3.value], {
      scale: 1,
      duration: 0.4,
      stagger: 0.15,
      ease: 'back.out(1.7)',
      repeat: -1,
      repeatDelay: 0.5
    }, '-=1')
    // Progress bar
    .to(progressBar.value, {
      scaleX: 1,
      duration: 2.5,
      ease: 'power1.inOut'
    }, '-=2')

  // Rotate jar slightly for extra life
  gsap.to(jarLogo.value, {
    rotation: 5,
    duration: 2,
    ease: 'sine.inOut',
    yoyo: true,
    repeat: -1
  })
}

const fadeOutLoader = () => {
  gsap.to(loaderContainer.value, {
    opacity: 0,
    duration: 0.8,
    ease: 'power2.inOut',
    onComplete: () => {
      isLoading.value = false
    }
  })
}

// Simulate loading (adjust based on actual asset loading)
const simulateLoading = () => {
  // Listen for page load
  window.addEventListener('load', () => {
    // Give a minimum display time for the animation to be appreciated
    setTimeout(() => {
      if (mainTimeline) {
        mainTimeline.kill()
      }
      fadeOutLoader()
    }, 3000) // 3 seconds minimum
  })

  // Fallback: force hide after max time
  setTimeout(() => {
    if (isLoading.value) {
      fadeOutLoader()
    }
  }, 8000) // 8 seconds maximum
}

onMounted(() => {
  initAnimations()
  simulateLoading()
})

onUnmounted(() => {
  if (mainTimeline) {
    mainTimeline.kill()
  }
})
</script>

<style scoped>
.page-loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, #f8f4e6 0%, #ede4d1 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 99999;
  overflow: hidden;
}

.loader-content {
  text-align: center;
  position: relative;
}

/* Jar Container */
.jar-container {
  position: relative;
  width: 200px;
  height: 200px;
  margin: 0 auto 3rem;
}

.jar-logo {
  width: 100%;
  height: 100%;
  object-fit: contain;
  filter: drop-shadow(0 10px 30px rgba(145, 109, 77, 0.3));
  position: relative;
  z-index: 2;
}

/* Coffee Pour Effect */
.coffee-pour {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateX(-50%);
  width: 20px;
  height: 80px;
  background: linear-gradient(180deg, 
    rgba(145, 109, 77, 0.9) 0%,
    rgba(145, 109, 77, 0.7) 50%,
    rgba(145, 109, 77, 0.5) 100%
  );
  border-radius: 10px;
  filter: blur(2px);
  z-index: 1;
}

/* Steam Animation */
.steam-container {
  position: absolute;
  top: -20px;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
}

.steam {
  position: absolute;
  width: 8px;
  height: 30px;
  background: linear-gradient(180deg, 
    rgba(255, 255, 255, 0.6) 0%,
    rgba(255, 255, 255, 0.3) 50%,
    transparent 100%
  );
  border-radius: 50%;
  filter: blur(3px);
}

.steam-1 {
  left: 30%;
  animation-delay: 0s;
}

.steam-2 {
  left: 50%;
  animation-delay: 0.3s;
}

.steam-3 {
  left: 70%;
  animation-delay: 0.6s;
}

/* Loading Text */
.loading-text {
  margin-bottom: 2rem;
}

.brand-name {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  color: #916d4d;
  margin-bottom: 0.5rem;
  font-weight: 700;
  letter-spacing: 2px;
  text-shadow: 2px 2px 4px rgba(145, 109, 77, 0.2);
}

.loading-message {
  font-size: 1.1rem;
  color: #6c5d53;
  margin-bottom: 1rem;
  font-weight: 400;
}

/* Loading Dots */
.loading-dots {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
  margin-top: 1rem;
}

/* .dot {
  width: 12px;
  height: 12px;
  background: linear-gradient(135deg, #916d4d, #da9f5b);
  border-radius: 50%;
  display: inline-block;
  box-shadow: 0 2px 8px rgba(145, 109, 77, 0.3);
} */

/* Progress Bar */
.progress-container {
  width: 300px;
  height: 4px;
  background: rgba(145, 109, 77, 0.2);
  border-radius: 2px;
  overflow: hidden;
  margin: 2rem auto 0;
}

.progress-bar {
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, #916d4d, #da9f5b, #916d4d);
  background-size: 200% 100%;
  animation: shimmer 2s infinite;
  border-radius: 2px;
}

@keyframes shimmer {
  0% {
    background-position: -100% 0;
  }
  100% {
    background-position: 100% 0;
  }
}

/* Fade transition */
.loader-fade-enter-active,
.loader-fade-leave-active {
  transition: opacity 0.8s ease;
}

.loader-fade-enter-from,
.loader-fade-leave-to {
  opacity: 0;
}

/* Responsive */
@media (max-width: 768px) {
  .jar-container {
    width: 150px;
    height: 150px;
    margin-bottom: 2rem;
  }

  .brand-name {
    font-size: 2rem;
  }

  .loading-message {
    font-size: 1rem;
  }

  .progress-container {
    width: 250px;
  }
}

@media (max-width: 480px) {
  .jar-container {
    width: 120px;
    height: 120px;
  }

  .brand-name {
    font-size: 1.75rem;
  }

  .loading-message {
    font-size: 0.9rem;
  }

  .progress-container {
    width: 200px;
  }
}
</style>
