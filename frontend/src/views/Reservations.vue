<template>
  <div class="reservations-page hero-page">
    <!-- Page Header Start -->
    <PageHeader 
      title="Reservations" 
      breadcrumb="Reservations" 
      background-image="carousel-2.jpg" 
    />
    <!-- Page Header End -->
    
    <!-- Reservation Content -->
    <ReservationSection />
  </div>
</template>

<script setup>
import PageHeader from '@/components/layout/PageHeader.vue'
import ReservationSection from '@/components/home/ReservationSection.vue'
</script>

<style scoped>
.reservations-page {
  min-height: 100vh;
}

.hero-page :deep(.container-fluid:first-of-type) {
  padding-top: 0 !important;
  margin-top: 0 !important;
}
</style>