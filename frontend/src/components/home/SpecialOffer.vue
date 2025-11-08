<template>
  <div class="offer container-fluid my-5 py-5 text-center position-relative overlay-top overlay-bottom">
    <div class="container py-5">
      <h1 class="display-3 text-primary mt-3">50% OFF</h1>
      <h1 class="text-white mb-3">Sunday Special Offer</h1>
      <h4 class="text-white font-weight-normal mb-4 pb-3">
        Only for Sunday from 1st Jan to 30th Jan 2025
      </h4>
      <form class="form-inline justify-content-center mb-4" @submit.prevent="subscribeOffer">
        <div class="input-group">
          <input 
            type="email" 
            class="form-control p-4" 
            placeholder="Your Email" 
            style="height: 60px;"
            v-model="email"
            required
          >
          <div class="input-group-append">
            <button class="btn btn-primary font-weight-bold px-4" type="submit">
              Sign Up
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')

const subscribeOffer = () => {
  if (email.value) {
    alert('Thank you for subscribing to our special offers!')
    email.value = ''
  }
}
</script>

<style scoped>
.offer {
  background: linear-gradient(rgba(51, 33, 29, .8), rgba(51, 33, 29, .8)), url('/img/carousel-1.jpg');
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
}

.form-control {
  background-color: rgba(255, 255, 255, 0.9);
  border: none;
}

.form-control:focus {
  background-color: white;
  box-shadow: none;
}
</style>