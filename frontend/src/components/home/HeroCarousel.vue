<template>
  <!-- Carousel Start -->
  <div class="container-fluid p-0">
    <div id="blog-carousel" class="carousel slide " data-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img class="w-100" src="/img/carousel-1.jpg" alt="Image">
          <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
            <h2 class="text-primary font-weight-medium m-0">Authentic Swahili Cuisine</h2>
            <h1 class="display-2 text-white m-0">ASILI CAFE</h1>
            <h2 class="text-white m-0">* ZANZIBAR'S FINEST RESTAURANT SINCE 2010 *</h2>
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
/* Carousel responsive styles */
.carousel-item {
  position: relative;
  min-height: 400px;
}

.carousel-item img {
  object-fit: cover;
  height: 100%;
  min-height: 400px;
}

.carousel-caption {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(51, 33, 29, 0.7);
  z-index: 1;
  padding: 2rem 1rem;
}

.carousel-caption h1,
.carousel-caption h2 {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

/* Desktop styles (default) */
.carousel-caption h1.display-1 {
  font-size: 5rem;
  font-weight: 700;
  margin: 1rem 0;
}

.carousel-caption h1.display-2 {
  font-size: 4rem;
  font-weight: 700;
  margin: 1rem 0;
}

.carousel-caption h2 {
  font-size: 1.75rem;
  font-weight: 500;
  margin: 0.5rem 0;
}

/* Tablet landscape (≤1200px) */
@media (max-width: 1200px) {
  .carousel-item {
    min-height: 350px;
  }
  
  .carousel-item img {
    min-height: 350px;
  }
  
  .carousel-caption h1.display-1 {
    font-size: 4rem;
  }
  
  .carousel-caption h1.display-2 {
    font-size: 3.5rem;
  }
  
  .carousel-caption h2 {
    font-size: 1.5rem;
  }
}

/* Tablet portrait (≤992px) */
@media (max-width: 992px) {
  .carousel-item {
    min-height: 300px;
  }
  
  .carousel-item img {
    min-height: 300px;
  }
  
  .carousel-caption {
    padding: 1.5rem 1rem;
  }
  
  .carousel-caption h1.display-1 {
    font-size: 3rem;
  }
  
  .carousel-caption h1.display-2 {
    font-size: 2.5rem;
  }
  
  .carousel-caption h2 {
    font-size: 1.25rem;
  }
}

/* Mobile landscape (≤768px) */
@media (max-width: 768px) {
  .carousel-item {
    min-height: 400px;
  }
  
  .carousel-item img {
    min-height: 400px;
  }
  
  .carousel-caption {
    padding: 1rem 0.75rem;
  }
  
  .carousel-caption h1.display-1 {
    font-size: 2.5rem;
    margin: 0.75rem 0;
  }
  
  .carousel-caption h1.display-2 {
    font-size: 2rem;
    margin: 0.75rem 0;
  }
  
  .carousel-caption h2 {
    font-size: 1rem;
    margin: 0.5rem 0;
  }
  
  .carousel-caption h2.text-white {
    font-size: 0.9rem;
  }
}

/* Mobile portrait (≤576px) */
@media (max-width: 576px) {
  .carousel-item {
    min-height: 350px;
  }
  
  .carousel-item img {
    min-height: 350px;
  }
  
  .carousel-caption {
    padding: 0.75rem 0.5rem;
  }
  
  .carousel-caption h1.display-1 {
    font-size: 2rem;
    margin: 0.5rem 0;
  }
  
  .carousel-caption h1.display-2 {
    font-size: 1.75rem;
    margin: 0.5rem 0;
  }
  
  .carousel-caption h2 {
    font-size: 0.875rem;
    margin: 0.25rem 0;
  }
  
  .carousel-caption h2.text-white {
    font-size: 0.75rem;
    letter-spacing: 1px;
  }
  
  .carousel-caption h2.text-primary {
    font-size: 0.85rem;
  }
}

/* Extra small mobile (≤480px) */
@media (max-width: 480px) {
  .carousel-item {
    min-height: 320px;
  }
  
  .carousel-item img {
    min-height: 320px;
  }
  
  .carousel-caption h1.display-1 {
    font-size: 1.75rem;
  }
  
  .carousel-caption h1.display-2 {
    font-size: 1.5rem;
  }
  
  .carousel-caption h2 {
    font-size: 0.75rem;
  }
  
  .carousel-caption h2.text-white {
    font-size: 0.65rem;
    letter-spacing: 0.5px;
  }
  
  .carousel-caption h2.text-primary {
    font-size: 0.8rem;
  }
}

/* Carousel controls responsive sizing */
.carousel-control-prev-icon,
.carousel-control-next-icon {
  width: 3rem;
  height: 3rem;
}

@media (max-width: 768px) {
  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    width: 2.5rem;
    height: 2.5rem;
  }
}

@media (max-width: 576px) {
  .carousel-control-prev-icon,
  .carousel-control-next-icon {
    width: 2rem;
    height: 2rem;
  }
}

/* Ensure proper aspect ratio on all devices */
@media (orientation: landscape) and (max-height: 500px) {
  .carousel-item {
    min-height: 100vh;
  }
  
  .carousel-item img {
    min-height: 100vh;
  }
}
</style>