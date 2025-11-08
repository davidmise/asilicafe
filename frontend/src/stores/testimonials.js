import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useTestimonialsStore = defineStore('testimonials', {
  state: () => ({
    testimonials: [],
    loading: false,
    error: null
  }),

  getters: {
    activeTestimonials: (state) => state.testimonials.filter(testimonial => testimonial.is_active),
    featuredTestimonials: (state) => state.testimonials.filter(testimonial => testimonial.is_featured && testimonial.is_active)
  },

  actions: {
    async fetchTestimonials() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/testimonials`)
        this.testimonials = response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching testimonials:', error)
      } finally {
        this.loading = false
      }
    },

    async fetchFeaturedTestimonials() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/testimonials/featured`)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching featured testimonials:', error)
        return []
      } finally {
        this.loading = false
      }
    }
  }
})