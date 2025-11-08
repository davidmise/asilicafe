import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useServicesStore = defineStore('services', {
  state: () => ({
    services: [],
    loading: false,
    error: null
  }),

  getters: {
    activeServices: (state) => state.services.filter(service => service.is_active)
  },

  actions: {
    async fetchServices() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/services`)
        this.services = response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching services:', error)
      } finally {
        this.loading = false
      }
    }
  }
})