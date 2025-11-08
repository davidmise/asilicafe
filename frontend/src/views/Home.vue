<template>
  <div class="home">
    <!-- Hero Carousel Component -->
    <HeroCarousel />

    <!-- About Section Component -->
    <AboutSection />

    <!-- Services Section Component -->
    <ServicesSection />

    <!-- Special Offer Component -->
    <SpecialOffer />

    <!-- Reservation Section Component -->
    <ReservationSection />

    <!-- Testimonials Section Component -->
    <TestimonialsSection :single-row="true" />
  </div>
</template>

<script setup>
// Import home page components
import HeroCarousel from '@/components/home/HeroCarousel.vue'
import AboutSection from '@/components/home/AboutSection.vue'
import ServicesSection from '@/components/home/ServicesSection.vue'
import SpecialOffer from '@/components/home/SpecialOffer.vue'
import ReservationSection from '@/components/home/ReservationSection.vue'
import TestimonialsSection from '@/components/home/TestimonialsSection.vue'
</script>