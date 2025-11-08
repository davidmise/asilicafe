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
  margin: 0;
  padding: 0;
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