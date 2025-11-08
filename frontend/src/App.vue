<template>
  <div id="app">
    <!-- Navbar Component -->
    <Navbar />

    <!-- Main Content -->
    <div class="main-content-wrapper">
      <router-view />
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
// Import layout components
import Navbar from './components/layout/Navbar.vue'
import Footer from './components/layout/Footer.vue'
</script>

<style>
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
</style>
