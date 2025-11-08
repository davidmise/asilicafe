<template>
  <div class="menu-container" ref="menuContainer">
    <!-- Loading State -->
    <div v-if="loading" class="loading-state">
      <div class="loading-spinner"></div>
      <p class="loading-text">Loading our delicious menu...</p>
    </div>

    <!-- Main Menu Layout -->
    <div v-else class="menu-layout" ref="menuLayout">
      <!-- Categories Sidebar -->
      <div class="categories-sidebar" ref="categoriesSidebar">
        <div class="sidebar-header">
          <img src="/img/asili_cafe_icon.png" alt="Asili Cafe" class="cafe-logo" />
          <h3 class="sidebar-title">Our Menu</h3>
          <p class="sidebar-subtitle">Discover our delicious offerings</p>
        </div>
        
        <nav class="categories-nav" ref="categoriesNav">
          <button 
            v-for="(category, index) in categories" 
            :key="category.id"
            :class="['category-btn', { active: activeCategory === category.id }]"
            @click="setActiveCategory(category.id, index)"
            :data-index="index"
            ref="categoryBtns"
          >
            <div class="category-content">
              <span class="category-name">{{ category.name }}</span>
              <small class="category-description">{{ getCategoryDescription(category.id) }}</small>
            </div>
          </button>
        </nav>
      </div>

      <!-- Menu Content Area -->
      <div class="menu-content" ref="contentPanel">
        <div class="content-header">
          <div class="header-ornament"></div>
          <h1 class="category-title">{{ getActiveCategoryName() }}</h1>
          <p class="category-description">{{ getActiveCategoryDescription() }}</p>
          <div class="header-ornament"></div>
        </div>

        <div class="menu-items-grid" ref="itemsGrid">
          <div 
            v-for="(item, itemIndex) in getItemsByCategory(activeCategory)" 
            :key="item.id"
            class="menu-item"
            @click="selectItem(item)"
            :class="{ selected: selectedItem && selectedItem.id === item.id }"
            :data-item-index="itemIndex"
            ref="menuItemRefs"
          >
            <div class="item-image">
              <img 
                :src="item.image || '/img/menu-1.jpg'" 
                :alt="item.name"
                @error="handleImageError($event)"
                loading="lazy"
              >
              <div class="price-badge">
                <span class="currency">TZS</span>
                <span class="amount">{{ formatPrice(item.price) }}</span>
              </div>
            </div>
            
            <div class="item-details">
              <h3 class="item-name">{{ item.name }}</h3>
              <p class="item-description">{{ item.description }}</p>
              
              <div class="item-variations">
                <span v-for="variation in item.variations" :key="variation" class="variation-tag">
                  {{ variation }}
                </span>
              </div>
              
              <button class="order-btn" @click.stop="quickOrder(item)">
                <i class="fa fa-plus"></i>
                Order Now
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Item Details Modal -->
    <div v-if="selectedItem" class="modal-overlay" @click="selectedItem = null" ref="modalBackdrop">
      <div class="modal-content" @click.stop ref="modal">
        <button class="modal-close" @click="selectedItem = null">
          <i class="fa fa-times"></i>
        </button>
        
        <div class="modal-header">
          <img 
            :src="selectedItem.image || '/img/menu-1.jpg'" 
            :alt="selectedItem.name" 
            class="modal-image"
            @error="handleImageError($event)"
          >
          <div class="modal-overlay-content">
            <h2 class="modal-title">{{ selectedItem.name }}</h2>
            <div class="modal-price">
              <span class="currency">TZS</span>
              <span class="amount">{{ formatPrice(selectedItem.price) }}</span>
            </div>
          </div>
        </div>
        
        <div class="modal-body">
          <p class="modal-description">{{ selectedItem.description }}</p>
          
          <div v-if="selectedItem.variations && selectedItem.variations.length > 0" class="modal-variations">
            <h5>Available Options:</h5>
            <div class="modal-variation-list">
              <label v-for="variation in selectedItem.variations" :key="variation" class="variation-option">
                <input type="radio" :name="`variation-${selectedItem.id}`" :value="variation">
                <span class="variation-label">{{ variation }}</span>
              </label>
            </div>
          </div>
          
          <div class="modal-actions">
            <button class="btn-order" @click="addToCart(selectedItem, getSelectedVariation())">
              <i class="fa fa-shopping-cart"></i>
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue'
import { useMenuStore } from '../../stores/menu'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

const menuStore = useMenuStore()
const loading = ref(false)
const activeCategory = ref('soft-drinks')
const selectedItem = ref(null)

// Template refs
const menuContainer = ref(null)
const menuLayout = ref(null)
const categoriesSidebar = ref(null)
const categoriesNav = ref(null)
const categoryBtns = ref([])
const contentPanel = ref(null)
const itemsGrid = ref(null)
const menuItemRefs = ref([])
const modalBackdrop = ref(null)
const modal = ref(null)

// Animation timeline
let mainTimeline = null
let scrollTriggerInstances = []

// Define categories with descriptions
const categories = ref([
  { id: 'soft-drinks', name: 'Soft Drinks', description: 'Refreshing Zanzibari beverages' },
  { id: 'smoothies', name: 'Smoothies', description: 'Fresh tropical island blends' },
  { id: 'milkshakes', name: 'Milk Shakes', description: 'Rich creamy delights' },
  { id: 'burgers', name: 'Burgers', description: 'Hearty & satisfying' },
  { id: 'sandwiches', name: 'Sandwiches', description: 'Made with local spices' },
  { id: 'pizza', name: 'Pizza', description: 'Freshly baked favorites' },
  { id: 'breakfast', name: 'Breakfast', description: 'Traditional island meals' },
  { id: 'soup', name: 'Soup', description: 'Comforting coastal recipes' },
  { id: 'main-fish', name: 'Main Dishes', description: 'Zanzibar specialties' }
])

// Set up the menu items based on the provided menu
const menuData = ref([
  // Soft Drinks
  { id: 1, name: 'Fresh Juice', price: 'Glass', category: 'soft-drinks', description: 'Fresh tropical fruits pressed daily, featuring local Zanzibari favorites', image: '/img/menu-1.jpg' },
  { id: 2, name: 'Asili Juice', price: 'Glass', category: 'soft-drinks', description: 'Our signature blend of exotic island fruits sourced from local Zanzibar markets', image: '/img/menu-2.jpg' },
  { id: 3, name: 'Asili Milk Tea', price: '2,000 / 4,000', category: 'soft-drinks', description: 'Traditional spiced milk tea with our secret blend of Zanzibar spices', variations: ['Small', 'Big'], image: '/img/menu-3.jpg' },
  { id: 4, name: 'Dawa Tea', price: '2,000 / 4,000', category: 'soft-drinks', description: 'Traditional Zanzibari healing tea with honey, ginger, and lemon', variations: ['Small', 'Big'], image: 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=500&h=300&fit=crop' },
  { id: 5, name: 'Black Tea', price: '2,000 / 4,000', category: 'soft-drinks', description: 'Premium Ceylon tea served the Zanzibari way with cardamom', variations: ['Small', 'Big'], image: 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500&h=300&fit=crop' },
  { id: 6, name: 'Soda', price: 'BTL', category: 'soft-drinks', description: 'Refreshing carbonated beverages perfect for the island heat', image: 'https://images.unsplash.com/photo-1546173159-315724a31696?w=500&h=300&fit=crop' },
  { id: 7, name: 'Water', price: 'BTL', category: 'soft-drinks', description: 'Pure filtered water sourced locally in Zanzibar', variations: ['1.5 ltrs', '0.5 ltrs'], image: 'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=500&h=300&fit=crop' },
  
  // Smoothies
  { id: 8, name: 'Mango Smoothie', price: 'Glass', category: 'smoothies', description: 'Sweet tropical mango from Zanzibar orchards blended to perfection', image: '/img/menu-1.jpg' },
  { id: 9, name: 'Avocado Smoothie', price: 'Glass', category: 'smoothies', description: 'Creamy avocado smoothie, a local island favorite packed with nutrients', image: '/img/menu-2.jpg' },
  { id: 10, name: 'Banana Smoothie', price: 'Glass', category: 'smoothies', description: 'Rich banana smoothie made from locally grown sweet island bananas', image: '/img/menu-3.jpg' },
  
  // Milkshakes
  { id: 11, name: 'Iced Caramel Macchiato', price: 'Cup', category: 'milkshakes', description: 'Rich coffee and caramel blend with our signature Zanzibari coffee', image: '/img/menu-1.jpg' },
  { id: 12, name: 'Affogato', price: 'Cup', category: 'milkshakes', description: 'Italian-style dessert with locally roasted Zanzibar espresso over vanilla ice cream', image: '/img/menu-2.jpg' },
  { id: 13, name: 'Strawberry Milkshake', price: 'Glass', category: 'milkshakes', description: 'Fresh strawberries blended with rich local milk', image: '/img/menu-3.jpg' },
  { id: 14, name: 'Banana Milkshake', price: 'Glass', category: 'milkshakes', description: 'Creamy shake made with sweet Zanzibar bananas', image: '/img/menu-1.jpg' },
  { id: 15, name: 'Vanilla Milkshake', price: 'Glass', category: 'milkshakes', description: 'Classic vanilla milkshake with premium Zanzibar vanilla', image: '/img/menu-2.jpg' },
  { id: 16, name: 'Chocolate Milkshake', price: 'Glass', category: 'milkshakes', description: 'Rich chocolate milkshake with local cocoa', image: '/img/menu-3.jpg' },
  { id: 17, name: 'Date Shake (Tende)', price: 'Glass', category: 'milkshakes', description: 'Traditional Zanzibari date shake with natural island sweetness', image: '/img/menu-1.jpg' },
  
  // Burgers
  { id: 18, name: 'Beef Burger', price: '10,000', category: 'burgers', description: 'Juicy beef patty with fresh toppings', image: '/img/menu-2.jpg' },
  { id: 19, name: 'Chicken Burger', price: '10,000', category: 'burgers', description: 'Tender chicken fillet with crisp vegetables', image: '/img/menu-3.jpg' },
  { id: 20, name: 'Vegetable Burger', price: '10,000', category: 'burgers', description: 'Plant-based patty with fresh veggies', image: '/img/menu-1.jpg' },
  
  // Sandwiches
  { id: 21, name: 'Chicken Sandwich', price: '10,000', category: 'sandwiches', description: 'Grilled chicken with fresh bread', image: '/img/menu-2.jpg' },
  { id: 22, name: 'Fish Sandwich', price: '10,000', category: 'sandwiches', description: 'Flaky fish fillet with crisp vegetables', image: '/img/menu-3.jpg' },
  
  // Pizza
  { id: 23, name: 'Margherita Pizza', price: '15,000', category: 'pizza', description: 'Classic pizza with mozzarella and oregano', image: '/img/menu-1.jpg' },
  { id: 24, name: 'Vegetable Pizza', price: '17,000', category: 'pizza', description: 'Assorted fresh vegetables with mozzarella', image: '/img/menu-2.jpg' },
  { id: 25, name: 'Sausage Pizza', price: '17,000', category: 'pizza', description: 'Choice of sausage with mozzarella', image: '/img/menu-3.jpg' },
  { id: 26, name: 'Chicken Pizza', price: '20,000', category: 'pizza', description: 'Tender chicken pieces with mozzarella', image: '/img/menu-1.jpg' },
  { id: 27, name: 'Beef Pizza', price: '20,000', category: 'pizza', description: 'Minced meat with mozzarella', image: '/img/menu-2.jpg' },
  { id: 28, name: 'Seafood Pizza', price: '25,000', category: 'pizza', description: 'Assorted seafood with mozzarella', image: '/img/menu-3.jpg' },
  { id: 29, name: 'BBQ Pizza', price: '20,000', category: 'pizza', description: 'Chicken with olive and green pepper', image: '/img/menu-1.jpg' },
  { id: 30, name: 'Asili Pizza', price: '20,000', category: 'pizza', description: 'Our signature pizza with pineapple, banana, and dates', image: '/img/menu-2.jpg' },
  
  // Breakfast
  { id: 31, name: 'Spanish Omelette', price: '3,000', category: 'breakfast', description: 'Fluffy omelette with vegetables', image: '/img/menu-3.jpg' },
  { id: 32, name: 'Plain Omelette', price: '2,000', category: 'breakfast', description: 'Simple and delicious', image: '/img/menu-1.jpg' },
  { id: 33, name: 'Andazi Mafuta', price: '500', category: 'breakfast', description: 'Traditional fried dough', image: '/img/menu-2.jpg' },
  { id: 34, name: 'Andazi Kavu', price: '1,000', category: 'breakfast', description: 'Crispy fried dough', image: '/img/menu-3.jpg' },
  { id: 35, name: 'Chapati', price: '1,000', category: 'breakfast', description: 'Flatbread perfect with any meal', image: '/img/menu-1.jpg' },
  { id: 36, name: 'Boflo', price: '1,000', category: 'breakfast', description: 'Local breakfast favorite', image: '/img/menu-2.jpg' },
  
  // Soup
  { id: 37, name: 'Octopus Soup', price: '7,000', category: 'soup', description: 'Hearty octopus broth', image: '/img/menu-3.jpg' },
  { id: 38, name: 'Chicken Soup', price: '7,000', category: 'soup', description: 'Comforting chicken broth', image: '/img/menu-1.jpg' },
  { id: 39, name: 'Mixed Seafood Soup', price: '15,000', category: 'soup', description: 'Rich seafood medley', image: '/img/menu-2.jpg' },
  { id: 40, name: 'Mohensho Beef/Chicken', price: '10,000', category: 'soup', description: 'Traditional soup with your choice of meat', image: '/img/menu-3.jpg' },
  { id: 41, name: 'Changu Soup', price: '20,000', category: 'soup', description: 'Premium fish soup', image: '/img/menu-1.jpg' },
  { id: 42, name: 'Tuna Soup', price: '7,000', category: 'soup', description: 'Flavorful tuna broth', image: '/img/menu-2.jpg' },
  { id: 43, name: 'Beef Soup', price: '7,000', category: 'soup', description: 'Hearty beef broth', image: '/img/menu-3.jpg' },
  { id: 44, name: 'Vegetable Soup', price: '7,000', category: 'soup', description: 'Fresh vegetable medley', image: '/img/menu-1.jpg' },
  
  // Main Fish
  { id: 45, name: 'Grilled Tuna', price: '15,000', category: 'main-fish', description: 'Fresh tuna perfectly grilled', image: '/img/menu-2.jpg' },
  { id: 46, name: 'Kingfish Grilled/Curry', price: '15,000', category: 'main-fish', description: 'Your choice of preparation', variations: ['Grilled', 'Curry'], image: '/img/menu-3.jpg' },
  { id: 47, name: 'Octopus Grilled/Curry', price: '15,000', category: 'main-fish', description: 'Tender octopus prepared to your liking', variations: ['Grilled', 'Curry'], image: '/img/menu-1.jpg' },
  { id: 48, name: 'Calamari Grilled/Curry', price: '15,000', category: 'main-fish', description: 'Squid prepared as you prefer', variations: ['Grilled', 'Curry'], image: '/img/menu-2.jpg' },
  { id: 49, name: 'Manchurian Curry', price: '15,000', category: 'main-fish', description: 'Asian-inspired curry', variations: ['Vegetable', 'Fish', 'Chicken'], image: '/img/menu-3.jpg' },
  { id: 50, name: 'Manchurian Chicken', price: '15,000', category: 'main-fish', description: 'Chicken in Manchurian sauce', image: '/img/menu-1.jpg' },
  { id: 51, name: 'Beef Curry', price: '15,000', category: 'main-fish', description: 'Tender beef in rich curry', image: '/img/menu-2.jpg' },
  { id: 52, name: 'Mix Sea Food Curry', price: '30,000', category: 'main-fish', description: 'Assorted seafood in curry sauce', image: '/img/menu-3.jpg' },
  { id: 53, name: 'Butter Chicken', price: '15,000', category: 'main-fish', description: 'Creamy butter chicken', image: '/img/menu-1.jpg' },
  { id: 54, name: 'Grilled Chicken', price: '18,000', category: 'main-fish', description: 'Perfectly grilled chicken', image: '/img/menu-2.jpg' },
  { id: 55, name: 'Grilled Changu (Whole Fish)', price: '28,000', category: 'main-fish', description: 'Whole fish grilled to perfection', image: '/img/menu-3.jpg' },
  { id: 56, name: 'Britan', price: '13,000', category: 'main-fish', description: 'Traditional dish (For Order)', image: '/img/menu-1.jpg' },
  { id: 57, name: 'Pilau', price: '10,000', category: 'main-fish', description: 'Spiced rice dish (For Order)', image: '/img/menu-2.jpg' },
  { id: 58, name: 'Chinese Rice', price: '10,000', category: 'main-fish', description: 'Asian-style fried rice (For Order)', image: '/img/menu-3.jpg' },
  { id: 59, name: 'Seafood Plata 0.5', price: '35,000', category: 'main-fish', description: 'Half portion seafood platter', image: '/img/menu-1.jpg' },
  { id: 60, name: 'Seafood Plata Full', price: '50,000', category: 'main-fish', description: 'Full seafood platter', image: '/img/menu-2.jpg' },
  { id: 61, name: 'Ugali, Rice, French Fries', price: '5,000', category: 'main-fish', description: 'Side dish selection', image: '/img/menu-3.jpg' },
  { id: 62, name: 'Vegetable with Octopus', price: '20,000', category: 'main-fish', description: 'Fresh vegetables with tender octopus', image: '/img/menu-1.jpg' }
])

// Computed properties
const getItemsByCategory = (categoryId) => {
  return menuData.value.filter(item => item.category === categoryId)
}

// Helper methods
const getCategoryDescription = (categoryId) => {
  const category = categories.value.find(cat => cat.id === categoryId)
  return category ? category.description : 'Delicious options'
}

const getActiveCategoryName = () => {
  const category = categories.value.find(cat => cat.id === activeCategory.value)
  return category ? category.name : ''
}

const getActiveCategoryDescription = () => {
  const category = categories.value.find(cat => cat.id === activeCategory.value)
  return category ? category.description : 'Explore our delicious selections'
}

// Methods
const setActiveCategory = (categoryId, index) => {
  if (activeCategory.value === categoryId) return
  
  const previousActiveIndex = categories.value.findIndex(cat => cat.id === activeCategory.value)
  activeCategory.value = categoryId
  
  // Animate category button transition
  animateCategoryTransition(previousActiveIndex, index)
}

const selectItem = (item) => {
  selectedItem.value = item
  nextTick(() => {
    if (modalBackdrop.value && modal.value) {
      animateModalOpen()
    }
  })
}

// Add order functionality
const addToCart = (item, variation = null) => {
  // Create order object
  const order = {
    id: Date.now(),
    item: item,
    variation: variation,
    quantity: 1,
    timestamp: new Date().toISOString()
  }
  
  // For demo purposes, show success message
  alert(`Added "${item.name}" ${variation ? `(${variation})` : ''} to cart!\n\nThis is a demo implementation. In a real app, this would be connected to your ordering system.`)
  
  // Close modal
  selectedItem.value = null
  
  // Log order for debugging
  console.log('Order placed:', order)
}

// Quick order function for direct ordering from menu grid
const quickOrder = (item) => {
  addToCart(item)
}

// Get selected variation from modal radio buttons
const getSelectedVariation = () => {
  if (!selectedItem.value || !selectedItem.value.id) return null
  
  const checkedRadio = document.querySelector(`input[name="variation-${selectedItem.value.id}"]:checked`)
  return checkedRadio ? checkedRadio.value : null
}

const formatPrice = (price) => {
  return price
}

// Handle image loading errors
const handleImageError = (event) => {
  // Fallback to available menu images in order
  const fallbackImages = ['/img/menu-1.jpg', '/img/menu-2.jpg', '/img/menu-3.jpg']
  const currentSrc = event.target.src
  
  // Find next fallback that isn't the current failed source
  const nextFallback = fallbackImages.find(img => !currentSrc.includes(img))
  
  if (nextFallback) {
    event.target.src = nextFallback
  } else {
    // Create a placeholder with text if all images fail
    event.target.style.display = 'none'
    const placeholder = document.createElement('div')
    placeholder.className = 'image-placeholder'
    placeholder.innerHTML = '<i class="fa fa-image"></i><span>Menu Item</span>'
    event.target.parentNode.appendChild(placeholder)
  }
}

// Simplified GSAP Animation Functions
const initializeAnimations = () => {
  // Reset any existing animations
  if (mainTimeline) {
    mainTimeline.kill()
  }
  scrollTriggerInstances.forEach(trigger => trigger.kill())
  scrollTriggerInstances = []

  // Categories panel entrance
  gsap.fromTo(categoriesSidebar.value, {
    x: -50,
    opacity: 0
  }, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out"
  })

  // Category buttons staggered animation
  gsap.fromTo(categoryBtns.value, {
    y: 20,
    opacity: 0
  }, {
    y: 0,
    opacity: 1,
    duration: 0.6,
    stagger: 0.05,
    ease: "power2.out",
    delay: 0.3
  })

  // Content panel entrance
  gsap.fromTo(contentPanel.value, {
    x: 50,
    opacity: 0
  }, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out",
    delay: 0.2
  })

  // Animate menu items
  animateItemsIn()
}

const animateItemsIn = () => {
  if (!itemsGrid.value) return
  
  const items = itemsGrid.value.querySelectorAll('.menu-item')
  
  gsap.fromTo(items, {
    y: 30,
    opacity: 0,
    scale: 0.95
  }, {
    y: 0,
    opacity: 1,
    scale: 1,
    duration: 0.6,
    stagger: 0.08,
    ease: "power2.out"
  })
}

const animateCategoryTransition = (fromIndex, toIndex) => {
  // Simple category button animation
  categoryBtns.value.forEach((btn, index) => {
    if (index === toIndex) {
      gsap.to(btn, {
        scale: 1.02,
        duration: 0.2,
        ease: "power2.out"
      })
    } else {
      gsap.to(btn, {
        scale: 1,
        duration: 0.2,
        ease: "power2.out"
      })
    }
  })

  // Animate new items
  setTimeout(() => {
    animateItemsIn()
  }, 100)
}

const animateModalOpen = () => {
  gsap.fromTo(modalBackdrop.value, {
    opacity: 0
  }, {
    opacity: 1,
    duration: 0.3,
    ease: "power2.out"
  })

  gsap.fromTo(modal.value, {
    scale: 0.9,
    opacity: 0,
    y: 20
  }, {
    scale: 1,
    opacity: 1,
    y: 0,
    duration: 0.4,
    ease: "back.out(1.7)"
  })
}

// Watch for modal close
watch(selectedItem, (newVal, oldVal) => {
  if (oldVal && !newVal && modalBackdrop.value) {
    gsap.to(modal.value, {
      scale: 0.9,
      opacity: 0,
      y: 20,
      duration: 0.3,
      ease: "power2.in"
    })

    gsap.to(modalBackdrop.value, {
      opacity: 0,
      duration: 0.2,
      delay: 0.1,
      ease: "power2.out"
    })
  }
})

onMounted(async () => {
  try {
    loading.value = true
    await menuStore.fetchMenuItems()
    // If we have menu items from the API, we could update menuData.value here
  } catch (error) {
    console.error('Error loading menu items:', error)
  } finally {
    loading.value = false
    
    // Initialize animations after content is loaded
    await nextTick()
    setTimeout(() => {
      initializeAnimations()
    }, 100)
  }
})

// Cleanup on unmount
import { onUnmounted } from 'vue'

onUnmounted(() => {
  if (mainTimeline) {
    mainTimeline.kill()
  }
  scrollTriggerInstances.forEach(trigger => trigger.kill())
  ScrollTrigger.getAll().forEach(trigger => trigger.kill())
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;600;700&family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');

/* Component Variables - Website Theme Colors */
.menu-container {
  --primary-brown: #916d4d;
  --secondary-brown: #da9f5b;
  --dark-brown: #2c1810;
  --light-cream: #f8f4e6;
  --warm-beige: #ede4d1;
  --soft-gold: #d4af37;
  --text-dark: #2c2c2c;
  --text-light: #6c757d;
  --white: #ffffff;
  --shadow-light: rgba(145, 109, 77, 0.1);
  --shadow-medium: rgba(145, 109, 77, 0.2);
  --shadow-heavy: rgba(0, 0, 0, 0.15);
}

/* Main Container */
.menu-container {
  width: 100%;
  background: linear-gradient(135deg, var(--light-cream) 0%, var(--warm-beige) 100%);
  font-family: 'Inter', sans-serif;
  overflow-x: hidden;
  position: relative;
  padding: 0;
  margin: 0;
}

/* Loading State */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 60vh;
  color: var(--primary-brown);
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid var(--shadow-light);
  border-top: 4px solid var(--primary-brown);
  border-radius: 50%;
  animation: menuSpin 1s linear infinite;
  margin-bottom: 1.5rem;
}

.loading-text {
  font-size: 1.1rem;
  text-align: center;
  opacity: 0.8;
  font-weight: 500;
}

/* Main Layout */
.menu-layout {
  display: flex;
  position: relative;
  min-height: 100vh;
  background: var(--white);
  box-shadow: none;
  border-radius: 0;
  overflow: hidden;
  margin: 0;
}

/* Categories Sidebar */
.categories-sidebar {
  width: 20%;
  background: linear-gradient(180deg, var(--dark-brown) 0%, var(--primary-brown) 100%);
  color: var(--white);
  position: sticky;
  top: 0;
  height: 100vh;
  max-height: 100vh;
  overflow-y: auto;
  flex-shrink: 0;
}

.categories-sidebar::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(circle at 20% 20%, rgba(212, 175, 55, 0.1) 1px, transparent 1px),
    radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.1) 1px, transparent 1px);
  background-size: 40px 40px;
  pointer-events: none;
  opacity: 0.3;
}

.sidebar-header {
  padding: 2rem 1.5rem;
  text-align: center;
  border-bottom: 2px solid rgba(218, 159, 91, 0.3);
  background: rgba(218, 159, 91, 0.1);
  position: relative;
  z-index: 2;
}

.cafe-logo {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  border: 3px solid rgba(218, 159, 91, 0.5);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
  margin-bottom: 1rem;
  transition: transform 0.3s ease;
  animation: slowRotate 12s linear infinite;
}

.cafe-logo:hover {
  transform: scale(1.05);
  animation-play-state: paused;
}

@keyframes slowRotate {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.sidebar-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.5rem;
  font-weight: 700;
  margin: 0 0 0.5rem;
  color: var(--soft-gold);
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.sidebar-subtitle {
  font-size: 1rem;
  margin: 0;
  opacity: 0.9;
  font-weight: 300;
}

/* Categories Navigation */
.categories-nav {
  padding: 1.5rem 0;
  position: relative;
  z-index: 2;
}

.category-btn {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 0.8rem;
  padding: 1rem 1.5rem;
  margin: 0.3rem 1rem;
  background: transparent;
  border: 2px solid transparent;
  border-radius: 12px;
  color: var(--white);
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  text-align: left;
  position: relative;
  overflow: hidden;
  width: calc(100% - 2rem);
}

.category-btn::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(218, 159, 91, 0.2), transparent);
  transition: left 0.5s;
}

.category-btn:hover::before {
  left: 100%;
}

.category-btn:hover {
  background: rgba(218, 159, 91, 0.2);
  border-color: var(--secondary-brown);
  transform: translateX(8px);
  box-shadow: 0 5px 20px rgba(218, 159, 91, 0.3);
}

.category-btn.active {
  background: rgba(218, 159, 91, 0.15);
  border-color: var(--soft-gold);
  box-shadow: 0 2px 8px rgba(212, 175, 55, 0.3);
  transform: translateX(8px);
}

.category-btn.active .category-name {
  color: var(--soft-gold);
  font-weight: 600;
}

.category-btn.active .category-description {
  color: rgba(212, 175, 55, 0.8);
  opacity: 1;
}

.category-content {
  flex: 1;
}

.category-name {
  font-size: 1rem;
  font-weight: 600;
  display: block;
  margin-bottom: 0.2rem;
  font-family: 'Lora', serif;
}

.category-description {
  font-size: 0.75rem;
  opacity: 0.8;
  display: block;
  font-weight: 300;
}

.category-btn.active .category-description {
  opacity: 0.95;
}

/* Menu Content Area */
.menu-content {
  width: 80%;
  background: var(--white);
  overflow-y: auto;
  position: relative;
  min-height: 100vh;
}

.content-header {
  padding: 2.5rem 3rem 1.5rem;
  background: linear-gradient(135deg, var(--light-cream), var(--warm-beige));
  border-bottom: 3px solid var(--primary-brown);
  text-align: center;
  position: sticky;
  top: 0;
  z-index: 10;
  box-shadow: 0 3px 20px var(--shadow-light);
}

.header-ornament {
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-brown), var(--secondary-brown), var(--primary-brown));
  margin: 0 auto 1.5rem;
  border-radius: 2px;
  box-shadow: 0 2px 8px var(--shadow-medium);
}

.category-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  color: var(--dark-brown);
  margin: 0 0 0.5rem;
  font-weight: 700;
  text-shadow: 2px 2px 4px var(--shadow-light);
}

.content-header .category-description {
  font-size: 1rem;
  color: var(--primary-brown);
  margin: 0;
  font-weight: 400;
  opacity: 0.9;
}

/* Menu Items Grid */
.menu-items-grid {
  padding: 2rem 3rem;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1.5rem;
  background: var(--white);
}

/* Individual Menu Items */
.menu-item {
  background: var(--white);
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 6px 20px var(--shadow-light);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  cursor: pointer;
  position: relative;
  border: 2px solid transparent;
  display: flex;
  flex-direction: column;
}

.menu-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, var(--shadow-light), rgba(218, 159, 91, 0.05));
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 18px;
  z-index: 1;
}

.menu-item:hover::before {
  opacity: 1;
}

.menu-item:hover {
  transform: translateY(-6px) scale(1.01);
  box-shadow: 0 15px 35px var(--shadow-medium);
  border-color: var(--primary-brown);
}

.menu-item.selected {
  border-color: var(--secondary-brown);
  box-shadow: 0 20px 50px var(--shadow-medium);
}

/* Item Image */
.item-image {
  position: relative;
  height: 160px;
  overflow: hidden;
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.menu-item:hover .item-image img {
  transform: scale(1.1);
}

.image-placeholder {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, var(--light-cream), var(--warm-beige));
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: var(--primary-brown);
  font-size: 0.9rem;
  font-weight: 500;
}

.image-placeholder i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  opacity: 0.6;
}

.price-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  padding: 0.5rem 1rem;
  border-radius: 25px;
  box-shadow: 0 4px 15px rgba(145, 109, 77, 0.4);
  z-index: 2;
  transition: all 0.3s ease;
  font-weight: 600;
  font-size: 0.85rem;
}

.menu-item:hover .price-badge {
  transform: scale(1.1);
  box-shadow: 0 12px 35px rgba(145, 109, 77, 0.6);
}

.currency {
  font-size: 0.7rem;
  opacity: 0.9;
  margin-right: 0.2rem;
}

.amount {
  font-size: 0.9rem;
  font-weight: 700;
}

/* Item Details */
.item-details {
  padding: 1.2rem;
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.item-name {
  font-family: 'Lora', serif;
  color: var(--dark-brown);
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0 0 0.5rem;
  line-height: 1.3;
}

.item-description {
  color: var(--text-light);
  margin: 0 0 1rem;
  line-height: 1.5;
  font-size: 0.85rem;
}

.item-variations {
  margin: 0.8rem 0 1rem;
  min-height: 1.5rem;
  flex-grow: 1;
}

.variation-tag {
  display: inline-block;
  background: linear-gradient(135deg, var(--light-cream), var(--warm-beige));
  color: var(--primary-brown);
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: 600;
  border: 1px solid var(--shadow-light);
  margin: 0.1rem 0.3rem 0.1rem 0;
  transition: all 0.3s ease;
}

.variation-tag:hover {
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  transform: translateY(-2px);
}

.order-btn {
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  border: none;
  padding: 0.7rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  width: 100%;
  justify-content: center;
  margin-top: auto;
}

.order-btn:hover {
  background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown));
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(145, 109, 77, 0.4);
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(44, 24, 16, 0.9);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 3000;
  padding: 2rem;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: var(--white);
  border-radius: 25px;
  max-width: 700px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
  animation: modalSlideIn 0.3s ease-out;
}

.modal-close {
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(145, 109, 77, 0.9);
  color: var(--white);
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}

.modal-close:hover {
  background: var(--primary-brown);
  transform: rotate(90deg) scale(1.1);
}

.modal-header {
  position: relative;
  height: 300px;
  overflow: hidden;
  border-radius: 25px 25px 0 0;
}

.modal-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.modal-overlay-content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(transparent, rgba(44, 24, 16, 0.9));
  color: var(--white);
  padding: 2rem;
}

.modal-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 700;
  margin: 0 0 1rem;
}

.modal-overlay-content .modal-price {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
}

.modal-overlay-content .currency {
  font-size: 1rem;
  opacity: 0.9;
}

.modal-overlay-content .amount {
  font-size: 2rem;
  font-weight: 800;
}

.modal-body {
  padding: 2.5rem;
}

.modal-description {
  color: var(--text-light);
  margin-bottom: 2rem;
  font-size: 1.1rem;
  line-height: 1.8;
}

.modal-variations h5 {
  color: var(--dark-brown);
  margin-bottom: 1.5rem;
  font-weight: 600;
  font-family: 'Lora', serif;
  font-size: 1.2rem;
}

.modal-variation-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
}

.variation-option {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border: 2px solid var(--light-cream);
  border-radius: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.variation-option:hover {
  border-color: var(--primary-brown);
  background: var(--light-cream);
}

.variation-option input[type="radio"] {
  accent-color: var(--primary-brown);
  transform: scale(1.2);
}

.variation-label {
  font-weight: 500;
  color: var(--dark-brown);
  font-size: 1rem;
}

.modal-actions {
  text-align: center;
  padding-top: 2rem;
  border-top: 2px solid var(--light-cream);
}

.btn-order {
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  border: none;
  padding: 1.5rem 4rem;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.4s ease;
  display: inline-flex;
  align-items: center;
  gap: 1rem;
}

.btn-order:hover {
  background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown));
  transform: translateY(-3px);
  box-shadow: 0 15px 40px rgba(145, 109, 77, 0.4);
}

/* Animations */
@keyframes menuSpin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes modalSlideIn {
  0% {
    opacity: 0;
    transform: translateY(-50px) scale(0.9);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Responsive Design */
@media (max-width: 1200px) {
  .menu-items-grid {
    grid-template-columns: repeat(3, 1fr);
    gap: 1.2rem;
    padding: 2rem;
  }
  
  .content-header {
    padding: 2rem;
  }
  
  .category-title {
    font-size: 1.8rem;
  }
}

@media (max-width: 992px) {
  .menu-layout {
    flex-direction: column;
    min-height: auto;
  }
  
  .categories-sidebar {
    width: 100%;
    height: auto;
    max-height: none;
    position: relative;
  }
  
  .categories-nav {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
  }
  
  .category-btn {
    margin: 0;
    width: 100%;
    justify-content: flex-start;
  }
  
  .menu-content {
    width: 100%;
    min-height: auto;
  }
}

@media (max-width: 768px) {
  .sidebar-header {
    padding: 1.5rem 1rem;
  }
  
  .cafe-logo {
    width: 50px;
    height: 50px;
  }
  
  .sidebar-title {
    font-size: 1.3rem;
  }
  
  .categories-nav {
    grid-template-columns: 1fr;
    padding: 1rem;
  }
  
  .menu-items-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    padding: 1.5rem;
  }
  
  .content-header {
    padding: 1.5rem;
  }
  
  .category-title {
    font-size: 1.6rem;
  }
  
  .modal-content {
    margin: 1rem;
    max-width: calc(100vw - 2rem);
  }
  
  .modal-header {
    height: 250px;
  }
  
  .modal-body {
    padding: 1.5rem;
  }
}

@media (max-width: 480px) {
  .item-details {
    padding: 1rem;
  }
  
  .item-name {
    font-size: 1rem;
  }
  
  .menu-items-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .modal-overlay-content {
    padding: 1.5rem;
  }
  
  .modal-title {
    font-size: 1.5rem;
  }
  
  .btn-order {
    padding: 1.2rem 2rem;
    font-size: 1rem;
  }
}
</style>