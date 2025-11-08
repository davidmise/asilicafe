import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useCompanyStore = defineStore('company', {
  state: () => ({
    companyInfo: {},
    sliders: [],
    loading: false,
    error: null
  }),

  getters: {
    activeSliders: (state) => state.sliders.filter(slider => slider.is_active),
    getCompanyInfo: (state) => (key) => state.companyInfo[key] || null
  },

  actions: {
    async fetchCompanyInfo() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/company-info`)
        // Convert array to object with key-value pairs
        this.companyInfo = response.data.data.reduce((acc, item) => {
          acc[item.key] = item.value
          return acc
        }, {})
      } catch (error) {
        this.error = error.message
        console.error('Error fetching company info:', error)
      } finally {
        this.loading = false
      }
    },

    async fetchSliders() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/sliders/active`)
        this.sliders = response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching sliders:', error)
      } finally {
        this.loading = false
      }
    },

    async getCompanyInfoByKey(key) {
      try {
        const response = await axios.get(`${API_BASE_URL}/company-info/key/${key}`)
        return response.data.data.value
      } catch (error) {
        console.error(`Error fetching company info for key ${key}:`, error)
        return null
      }
    }
  }
})