<template>
  <div class="about-page hero-page">
    <!-- Page Header Start -->
    <PageHeader 
      title="About Us" 
      breadcrumb="About" 
      background-image="carousel-2.jpg" 
    />
    <!-- Page Header End -->
    
    <!-- About Section Component -->
    <AboutSection />
  </div>
</template>

<script setup>
import AboutSection from '@/components/home/AboutSection.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
</script>

<style scoped>
.about-page {
  min-height: 100vh;
}

.hero-page :deep(.container-fluid:first-of-type) {
  padding-top: 0 !important;
}
</style>