<template>
  <div class="container-fluid page-header position-relative" :style="headerStyle">
    <div class="d-flex flex-column align-items-center justify-content-center pt-0 pt-lg-5" style="min-height: 400px">
      <h1 class="display-4 mb-3 mt-0 mt-lg-5 text-white text-uppercase">{{ title }}</h1>
      <div class="d-inline-flex mb-lg-5">
        <p class="m-0 text-white">
          <router-link class="text-white" to="/">Home</router-link>
        </p>
        <p class="m-0 text-white px-2">/</p>
        <p class="m-0 text-white">{{ breadcrumb || title }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  breadcrumb: {
    type: String,
    default: ''
  },
  backgroundImage: {
    type: String,
    default: 'carousel-1.jpg'
  }
})

const headerStyle = computed(() => ({
  background: `linear-gradient(rgba(51, 33, 29, .8), rgba(51, 33, 29, .8)), url('/img/${props.backgroundImage}')`,
  backgroundPosition: 'center center',
  backgroundRepeat: 'no-repeat',
  backgroundSize: 'cover'
}))
</script>

<style scoped>
.page-header {
  transition: background 0.3s ease-in-out;
}

.text-white {
  text-decoration: none;
}

.text-white:hover {
  color: #DA9F5B !important;
  text-decoration: none;
}

router-link.text-white:hover {
  color: #DA9F5B !important;
}
</style>