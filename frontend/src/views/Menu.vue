<template>
  <div class="menu-page">
    <!-- Page Header Start -->
    <PageHeader 
      title="Menu" 
      breadcrumb="Menu" 
      background-image="carousel-1.jpg" 
    />
    <!-- Page Header End -->
    
    <!-- Featured Menu Component -->
    <FeaturedMenu />
  </div>
</template>

<script setup>
import FeaturedMenu from '@/components/home/FeaturedMenu.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
</script>

<style scoped>
.menu-page {
  min-height: 100vh;
  position: relative;
}

/* Fade gradient at bottom of menu */
.menu-page::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100px;
  background: linear-gradient(to bottom, transparent, rgba(145, 109, 77, 0.03), rgba(44, 24, 16, 0.1));
  pointer-events: none;
  z-index: 1;
}

.menu-price {
  color: #DA9F5B;
  font-weight: bold;
  text-align: center;
  margin-top: 10px;
}

.spinner-border {
  width: 3rem;
  height: 3rem;
}
</style>