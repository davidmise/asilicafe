<template>
  <Transition name="fade">
    <a 
      v-if="showButton"
      href="#" 
      class="btn btn-lg btn-primary btn-lg-square back-to-top"
      @click.prevent="scrollToTop"
    >
      <i class="fa fa-angle-double-up"></i>
    </a>
  </Transition>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const showButton = ref(false)

const handleScroll = () => {
  showButton.value = window.pageYOffset > 100
}

const scrollToTop = () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  })
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>

<style scoped>
.back-to-top {
  position: fixed;
  bottom: 30px;
  right: 30px;
  z-index: 999;
  width: 50px;
  height: 50px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  text-decoration: none;
  transition: all 0.3s ease;
}

.back-to-top:hover {
  transform: translateY(-3px);
  box-shadow: 0 5px 15px rgba(218, 159, 91, 0.4);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>