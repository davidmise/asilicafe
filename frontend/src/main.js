import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'

// Create app instance
const app = createApp(App)

// Use Pinia for state management
app.use(createPinia())

// Use Vue Router
app.use(router)

// Mount the app
app.mount('#app')

// Load original JavaScript libraries after Vue is mounted
setTimeout(() => {
  // Load jQuery first
  const jqueryScript = document.createElement('script')
  jqueryScript.src = 'https://code.jquery.com/jquery-3.4.1.min.js'
  jqueryScript.onload = () => {
    console.log('jQuery loaded')
    
    // Load Bootstrap after jQuery
    const bootstrapScript = document.createElement('script')
    bootstrapScript.src = 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js'
    bootstrapScript.onload = () => {
      console.log('Bootstrap loaded')
      
      // Load other libraries
      loadOtherLibraries()
    }
    document.head.appendChild(bootstrapScript)
  }
  document.head.appendChild(jqueryScript)
}, 100)

function loadOtherLibraries() {
  // Load moment.js first, then other libraries that depend on it
  const momentScript = document.createElement('script')
  momentScript.src = '/lib/tempusdominus/js/moment.min.js'
  momentScript.onload = () => {
    console.log('Moment.js loaded')
    
    // Now load the rest of the libraries in sequence
    const scripts = [
      '/lib/easing/easing.min.js',
      '/lib/waypoints/waypoints.min.js',
      '/lib/owlcarousel/owl.carousel.min.js',
      '/lib/tempusdominus/js/moment-timezone.min.js',
      '/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js',
      '/mail/jqBootstrapValidation.min.js', // Load validation library first
      '/mail/contact.js', // Then load contact.js which depends on it
      '/js/main.js'
    ]
    
    let loadedCount = 0
    scripts.forEach((src, index) => {
      const script = document.createElement('script')
      script.src = src
      script.onload = () => {
        loadedCount++
        console.log(`Loaded ${src}`)
        
        // Initialize carousel when all scripts are loaded
        if (loadedCount === scripts.length) {
          setTimeout(initializeComponents, 500)
        }
      }
      script.onerror = () => {
        console.warn(`Failed to load ${src}`)
        loadedCount++
        if (loadedCount === scripts.length) {
          setTimeout(initializeComponents, 500)
        }
      }
      document.head.appendChild(script)
    })
  }
  momentScript.onerror = () => {
    console.warn('Failed to load moment.js')
    setTimeout(initializeComponents, 500)
  }
  document.head.appendChild(momentScript)
}

function initializeComponents() {
  if (window.$ && window.$('.testimonial-carousel').length) {
    console.log('Initializing testimonial carousel')
    window.$('.testimonial-carousel').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      dots: false,
      nav: true,
      navText: [
        '<i class="fa fa-angle-left" aria-hidden="true"></i>',
        '<i class="fa fa-angle-right" aria-hidden="true"></i>'
      ],
      responsive: {
        0: { items: 1 },
        576: { items: 1 },
        768: { items: 2 },
        992: { items: 3 }
      }
    })
  }
  
  if (window.$ && window.$('.carousel').length) {
    console.log('Initializing main carousel')
    window.$('.carousel').carousel({
      interval: 5000
    })
  }
  
  // Initialize back to top button
  if (window.$ && window.$('.back-to-top').length) {
    window.$(window).scroll(function () {
      if (window.$(this).scrollTop() > 300) {
        window.$('.back-to-top').fadeIn('slow')
      } else {
        window.$('.back-to-top').fadeOut('slow')
      }
    })
    
    window.$('.back-to-top').click(function () {
      window.$('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo')
      return false
    })
  }
}
