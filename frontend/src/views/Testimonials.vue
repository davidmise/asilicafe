<template>
  <div class="testimonials-page">
    <!-- Page Header Start -->
    <PageHeader 
      title="Testimonials" 
      breadcrumb="Testimonials" 
      background-image="carousel-2.jpg" 
    />
    <!-- Page Header End -->
    
    <!-- Testimonials Content -->
    <TestimonialsSection />
  </div>
</template>

<script setup>
import PageHeader from '@/components/layout/PageHeader.vue'
import TestimonialsSection from '@/components/home/TestimonialsSection.vue'
</script>

<style scoped>
.testimonials-page {
  min-height: 100vh;
}
</style>