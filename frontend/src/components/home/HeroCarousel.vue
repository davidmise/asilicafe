<template>
  <!-- Carousel Start -->
  <div class="container-fluid p-0">
    <div id="blog-carousel" class="carousel slide " data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="w-100" src="/img/carousel-1.jpg" alt="Image">
          <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
            <h2 class="text-primary font-weight-medium m-0">Authentic Zanzibari Coffee</h2>
            <h1 class="display-2 text-white m-0">ASILI CAFE</h1>
            <h2 class="text-white m-0">* ZANZIBAR'S FAVORITE SINCE 2010 *</h2>
          </div>
        </div>
        <div class="carousel-item">
          <img class="w-100" src="/img/carousel-2.jpg" alt="Image">
          <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
            <h2 class="text-primary font-weight-medium m-0">Two Convenient Locations</h2>
            <h1 class="display-1 text-white m-0">WELCOME</h1>
            <h2 class="text-white m-0">* MTWENI & KINAZI MMOJA BRANCHES *</h2>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#blog-carousel" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </a>
      <a class="carousel-control-next" href="#blog-carousel" data-slide="next">
        <span class="carousel-control-next-icon"></span>
      </a>
    </div>
  </div>
  <!-- Carousel End -->
</template>

<script setup>
import { onMounted, nextTick } from 'vue'

onMounted(async () => {
  // Initialize Bootstrap carousel after component is mounted
  await nextTick()
  if (window.$ && window.$('#blog-carousel').length) {
    window.$('#blog-carousel').carousel({
      interval: 5000,
      wrap: true,
      keyboard: true
    })
  }
})
</script>

<style scoped>
/* Carousel styles match the original index.html implementation */
</style>