<template>
  <div class="services-page hero-page">
    <!-- Page Header Start -->
    <PageHeader 
      title="Our Services" 
      breadcrumb="Services" 
      background-image="bg.jpg" 
    />
    <!-- Page Header End -->
    
    <!-- Services Section Component -->
    <ServicesSection />
  </div>
</template>

<script setup>
import ServicesSection from '@/components/home/ServicesSection.vue'
import PageHeader from '@/components/layout/PageHeader.vue'
</script>

<style scoped>
.services-page {
  min-height: 100vh;
}

.hero-page :deep(.container-fluid:first-of-type) {
  padding-top: 0 !important;
}
</style>