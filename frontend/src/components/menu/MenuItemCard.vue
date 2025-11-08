<template>
  <div class="row align-items-center mb-5 menu-item-card">
    <div class="col-4 col-sm-3">
      <img 
        class="w-100 rounded-circle mb-3 mb-sm-0" 
        :src="item.image || '/img/menu-1.jpg'" 
        :alt="item.name"
        @error="handleImageError"
      >
      <h5 class="menu-price">${{ formatPrice(item.price) }}</h5>
    </div>
    <div class="col-8 col-sm-9">
      <h4>{{ item.name }}</h4>
      <p class="m-0">{{ item.description }}</p>
      <div v-if="item.is_featured" class="badge bg-primary mt-2">
        <i class="fa fa-star"></i> Featured
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  item: {
    type: Object,
    required: true
  }
})

const formatPrice = (price) => {
  return parseFloat(price).toFixed(2)
}

const handleImageError = (event) => {
  event.target.src = '/img/menu-1.jpg'
}
</script>

<style scoped>
.menu-item-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  padding: 15px;
  border-radius: 10px;
}

.menu-item-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
  background-color: rgba(218, 159, 91, 0.05);
}

.menu-price {
  color: #DA9F5B;
  font-weight: bold;
  font-size: 1.2rem;
  text-align: center;
  margin-top: 10px;
}

.menu-item-card img {
  transition: transform 0.3s ease;
  border: 3px solid #DA9F5B;
  aspect-ratio: 1;
  object-fit: cover;
}

.menu-item-card:hover img {
  transform: scale(1.05);
}

.badge {
  font-size: 0.75rem;
}
</style>