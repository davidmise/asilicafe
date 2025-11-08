import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useTestimonialStore = defineStore('testimonial', {
  state: () => ({
    testimonials: [],
    loading: false,
    error: null
  }),

  getters: {
    featuredTestimonials: (state) => state.testimonials.filter(testimonial => testimonial.is_featured),
    activeTestimonials: (state) => state.testimonials.filter(testimonial => testimonial.is_active)
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

    async fetchTestimonial(id) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/testimonials/${id}`)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching testimonial:', error)
        return null
      } finally {
        this.loading = false
      }
    }
  }
})