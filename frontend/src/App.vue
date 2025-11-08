<template>
  <div id="app">
    <!-- Loading Progress Bar -->
    <div class="page-loading-bar" :class="{ active: isLoading }"></div>
    
    <!-- Navbar Component -->
    <Navbar />

    <!-- Main Content -->
    <div class="main-content-wrapper">
      <router-view v-slot="{ Component, route }">
        <transition 
          name="page" 
          mode="out-in" 
          appear
          @before-enter="onBeforeEnter"
          @after-enter="onAfterEnter"
          @before-leave="onBeforeLeave"
        >
          <component :is="Component" :key="route.path" />
        </transition>
      </router-view>
      <!-- Global Page Fade Transition -->
      <div class="page-fade-transition"></div>
    </div>

    <!-- Footer Component -->
    <Footer />

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="fa fa-angle-double-up"></i></a>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
// Import layout components
import Navbar from './components/layout/Navbar.vue'
import Footer from './components/layout/Footer.vue'

// Loading state for page transitions
const isLoading = ref(false)
const router = useRouter()

// Transition event handlers
const onBeforeLeave = () => {
  isLoading.value = true
}

const onBeforeEnter = () => {
  // Keep loading state active during enter transition
}

const onAfterEnter = () => {
  // Small delay to ensure smooth transition completion
  setTimeout(() => {
    isLoading.value = false
  }, 300)
}

// Router navigation loading states
router.beforeEach((to, from, next) => {
  if (from.name) { // Only show loading on navigation, not initial load
    isLoading.value = true
  }
  next()
})

router.afterEach(() => {
  // Loading state will be handled by transition events
})

onMounted(() => {
  // Ensure loading state is false on initial mount
  isLoading.value = false
})
</script>

<style>
/* Loading Progress Bar */
.page-loading-bar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 3px;
  background: linear-gradient(90deg, 
    var(--primary-brown), 
    var(--soft-gold), 
    var(--primary-brown)
  );
  transform: translateX(-100%);
  transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 9999;
  opacity: 0;
}

.page-loading-bar.active {
  animation: loadingProgress 1s ease-in-out infinite;
  opacity: 1;
}

@keyframes loadingProgress {
  0% {
    transform: translateX(-100%);
  }
  50% {
    transform: translateX(0%);
  }
  100% {
    transform: translateX(100%);
  }
}

/* Global Font Family and Color Scheme */
* {
  font-family: 'After Regular', Arial, sans-serif;
  margin: 0;
  padding: 0;
}

body {
  margin: 0;
  padding: 0;
  overflow-x: hidden;
}

#app {
  margin: 0;
  padding: 0;
  min-height: 100vh;
}

:root {
  --primary-white: #ffffff;
  --primary-brown: #916d4d;
  --primary-dark: #231f20;
  --primary-light: #eeeff1;
  --primary-accent: #442a09;
  --soft-gold: #d4af37;
  --light-cream: #f8f4e6;
  --warm-beige: #f2ede0;
}

/* Override default primary colors */
.text-primary {
  color: var(--primary-brown) !important;
}

.btn-primary {
  background-color: var(--primary-brown);
  border-color: var(--primary-brown);
}

.btn-primary:hover,
.btn-primary:focus,
.btn-primary:active {
  background-color: var(--primary-accent);
  border-color: var(--primary-accent);
}

.bg-primary {
  background-color: var(--primary-brown) !important;
}

.border-primary {
  border-color: var(--primary-brown) !important;
}

/* Back to top button styling */
.back-to-top {
  background-color: var(--primary-brown);
  border-color: var(--primary-brown);
}

.back-to-top:hover {
  background-color: var(--primary-accent);
  border-color: var(--primary-accent);
}

/* Global Page Fade Transition */
.main-content-wrapper {
  position: relative;
  margin: 0;
  padding: 0;
}

.page-fade-transition {
  position: absolute;
  bottom: -1px;
  left: 0;
  right: 0;
  height: 200px;
  background: linear-gradient(to bottom, 
    transparent 0%, 
    rgba(145, 109, 77, 0.01) 10%,
    rgba(145, 109, 77, 0.03) 20%,
    rgba(145, 109, 77, 0.05) 30%,
    rgba(44, 24, 16, 0.08) 40%,
    rgba(44, 24, 16, 0.12) 50%,
    rgba(44, 24, 16, 0.18) 60%,
    rgba(44, 24, 16, 0.25) 70%,
    rgba(44, 24, 16, 0.35) 80%,
    rgba(44, 24, 16, 0.45) 90%,
    rgba(44, 24, 16, 0.55) 100%
  );
  pointer-events: none;
  z-index: 10;
}

/* Page Transition Animations */
.page-enter-active {
  transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  position: relative;
  z-index: 2;
}

.page-leave-active {
  transition: all 0.3s cubic-bezier(0.55, 0.06, 0.68, 0.19);
  position: relative;
  z-index: 1;
}

.page-enter-from {
  opacity: 0;
  transform: translateY(15px) scale(0.995);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-10px) scale(1.005);
}

.page-enter-to,
.page-leave-from {
  opacity: 1;
  transform: translateY(0) scale(1);
}

/* Enhanced smooth scrolling */
html {
  scroll-behavior: smooth;
}

/* Subtle stagger for content elements */
.page-enter-active .container {
  transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  transition-delay: 0.05s;
}

.page-enter-from .container {
  opacity: 0;
  transform: translateY(10px);
}

/* Subtle page transition backdrop */
body {
  background: linear-gradient(135deg, var(--light-cream) 0%, var(--warm-beige) 100%);
  transition: background-color 0.3s ease;
}
</style>
