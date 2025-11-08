import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useSliderStore = defineStore('slider', {
  state: () => ({
    sliders: [
      {
        id: 1,
        title: 'COFFEE',
        subtitle: 'We Have Been Serving',
        description: '* SINCE 1950 *',
        image: '/img/carousel-1.jpg'
      },
      {
        id: 2,
        title: 'COFFEE',
        subtitle: 'We Have Been Serving',
        description: '* SINCE 1950 *',
        image: '/img/carousel-2.jpg'
      }
    ],
    loading: false,
    error: null
  }),

  getters: {
    activeSliders: (state) => state.sliders.filter(slider => slider.is_active !== false),
    featuredSliders: (state) => state.sliders.filter(slider => slider.is_featured)
  },

  actions: {
    async fetchSliders() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/sliders`)
        if (response.data.data && response.data.data.length > 0) {
          this.sliders = response.data.data
        }
        // Keep default sliders if API fails or returns empty
      } catch (error) {
        this.error = error.message
        console.error('Error fetching sliders:', error)
        // Keep default sliders if API fails
      } finally {
        this.loading = false
      }
    },

    async fetchSlider(id) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/sliders/${id}`)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching slider:', error)
        return null
      } finally {
        this.loading = false
      }
    },

    async createSlider(sliderData) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post(`${API_BASE_URL}/sliders`, sliderData)
        this.sliders.push(response.data.data)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error creating slider:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async updateSlider(id, sliderData) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.put(`${API_BASE_URL}/sliders/${id}`, sliderData)
        const index = this.sliders.findIndex(slider => slider.id === id)
        if (index !== -1) {
          this.sliders[index] = response.data.data
        }
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error updating slider:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteSlider(id) {
      this.loading = true
      this.error = null
      try {
        await axios.delete(`${API_BASE_URL}/sliders/${id}`)
        this.sliders = this.sliders.filter(slider => slider.id !== id)
      } catch (error) {
        this.error = error.message
        console.error('Error deleting slider:', error)
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})