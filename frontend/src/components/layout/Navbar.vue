<template>
  <!-- Navbar Start -->
  <div class="container-fluid p-0 nav-bar">
    <nav class="navbar navbar-expand-lg bg-none navbar-dark py-3">
      <router-link to="/" class="navbar-brand px-lg-4 m-0">
        <img src="/img/asili_logo.png" alt="Asili Cafe" class="navbar-logo" style="height: 50px; width: auto;">
      </router-link>
      <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
        <div class="navbar-nav ml-auto p-4">
          <router-link to="/" class="nav-item nav-link" active-class="active">Home</router-link>
          <router-link to="/about" class="nav-item nav-link" active-class="active">About</router-link>
          <router-link to="/services" class="nav-item nav-link" active-class="active">Service</router-link>
          <router-link to="/menu" class="nav-item nav-link" active-class="active">Menu</router-link>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Pages</a>
            <div class="dropdown-menu custom-dropdown">
              <router-link to="/reservations" class="dropdown-item custom-dropdown-item">
                Reservation
              </router-link>
              <router-link to="/testimonials" class="dropdown-item custom-dropdown-item">
                Testimonial
              </router-link>
            </div>
          </div>
          <router-link to="/contact" class="nav-item nav-link" active-class="active">Contact</router-link>
        </div>
      </div>
    </nav>
  </div>
  <!-- Navbar End -->
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useCompanyInfoStore } from '../../stores/companyInfo'

const companyInfoStore = useCompanyInfoStore()

// Dynamic company information
const companyName = computed(() => companyInfoStore.companyInfo.name)

onMounted(async () => {
  // Simplified dropdown functionality
  const dropdowns = document.querySelectorAll('.nav-item.dropdown')
  
  dropdowns.forEach(dropdown => {
    const toggle = dropdown.querySelector('.dropdown-toggle')
    const menu = dropdown.querySelector('.dropdown-menu')
    
    if (toggle && menu) {
      // Handle dropdown toggle
      toggle.addEventListener('click', (e) => {
        e.preventDefault()
        const isOpen = menu.classList.contains('show')
        
        // Close all other dropdowns
        document.querySelectorAll('.dropdown-menu').forEach(otherMenu => {
          if (otherMenu !== menu) {
            otherMenu.classList.remove('show')
            const otherToggle = otherMenu.previousElementSibling
            if (otherToggle) otherToggle.setAttribute('aria-expanded', 'false')
          }
        })
        
        // Toggle current dropdown
        menu.classList.toggle('show', !isOpen)
        toggle.setAttribute('aria-expanded', !isOpen)
      })
      
      // Close on outside click
      document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
          menu.classList.remove('show')
          toggle.setAttribute('aria-expanded', 'false')
        }
      })
    }
  })
})
</script>

<style scoped>
/* Navigation Bar - Sticky only on mobile */
.nav-bar {
  transition: all 0.3s ease;
}

/* Sticky navigation only on mobile and tablet devices */
@media (max-width: 1024px) {
  .nav-bar {
    position: sticky !important;
    top: 0 !important;
    z-index: 999 !important;
    background: rgba(51, 33, 29, 0.98) !important;
    backdrop-filter: blur(10px);
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
  }
}

/* Clean Dropdown Styling */
.custom-dropdown {
  background: rgba(255, 255, 255, 0.95) !important;
  border: 1px solid rgba(145, 109, 77, 0.2) !important;
  border-radius: 8px !important;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
  min-width: 180px !important;
  padding: 8px 0 !important;
  margin-top: 8px !important;
  backdrop-filter: blur(8px);
  opacity: 0;
  transform: translateY(-10px);
  transition: all 0.3s ease;
  pointer-events: none;
}

.custom-dropdown.show {
  opacity: 1;
  transform: translateY(0);
  pointer-events: auto;
}

.custom-dropdown-item {
  color: #333 !important;
  padding: 10px 20px !important;
  font-weight: 500 !important;
  transition: all 0.2s ease !important;
  text-decoration: none !important;
  border: none !important;
  background: none !important;
}

.custom-dropdown-item:hover {
  background: rgba(145, 109, 77, 0.1) !important;
  color: #916d4d !important;
  padding-left: 24px !important;
}

.custom-dropdown-item:focus {
  background: rgba(145, 109, 77, 0.1) !important;
  color: #916d4d !important;
  outline: none !important;
}

/* Dropdown Toggle Enhancement */
.dropdown-toggle::after {
  transition: transform 0.2s ease !important;
  margin-left: 6px !important;
}

.dropdown-toggle[aria-expanded="true"]::after {
  transform: rotate(180deg) !important;
}

.nav-item.dropdown .nav-link:hover {
  color: #da9f5b !important;
}

/* Responsive Dropdown */
@media (max-width: 768px) {
  .custom-dropdown {
    position: relative !important;
    background: rgba(255, 255, 255, 0.98) !important;
    box-shadow: none !important;
    border: 1px solid rgba(145, 109, 77, 0.3) !important;
    border-radius: 0 !important;
    margin-top: 0 !important;
    opacity: 1 !important;
    transform: none !important;
  }
  
  .custom-dropdown-item {
    padding: 12px 20px !important;
    border-bottom: 1px solid rgba(145, 109, 77, 0.1) !important;
  }
  
  .custom-dropdown-item:last-child {
    border-bottom: none !important;
  }
}
</style>

