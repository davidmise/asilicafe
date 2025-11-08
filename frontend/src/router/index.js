import { createRouter, createWebHistory } from 'vue-router'

// Import components
import Home from '../views/Home.vue'
import About from '../views/About.vue'
import Menu from '../views/Menu.vue'
import Services from '../views/Services.vue'
import Testimonials from '../views/Testimonials.vue'
import Reservations from '../views/Reservations.vue'
import Contact from '../views/Contact.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    component: About
  },
  {
    path: '/menu',
    name: 'Menu',
    component: Menu
  },
  {
    path: '/services',
    name: 'Services',
    component: Services
  },
  {
    path: '/service',
    redirect: '/services'
  },
  {
    path: '/testimonials',
    name: 'Testimonials',
    component: Testimonials
  },
  {
    path: '/testimonial',
    redirect: '/testimonials'
  },
  {
    path: '/reservations',
    name: 'Reservations',
    component: Reservations
  },
  {
    path: '/reservation',
    redirect: '/reservations'
  },
  {
    path: '/contact',
    name: 'Contact',
    component: Contact
  },
  // Catch-all route for 404 pages
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    // Always scroll to top smoothly for page transitions
    return new Promise((resolve) => {
      // Add a slight delay to work with page transition timing
      setTimeout(() => {
        if (savedPosition) {
          resolve({
            ...savedPosition,
            behavior: 'smooth'
          })
        } else {
          resolve({
            top: 0,
            left: 0,
            behavior: 'smooth'
          })
        }
      }, 200) // Delay to allow transition to start
    })
  }
})

export default router