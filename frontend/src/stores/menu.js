import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useMenuStore = defineStore('menu', {
  state: () => ({
    menuItems: [],
    loading: false,
    error: null
  }),

  getters: {
    hotCoffee: (state) => state.menuItems.filter(item => item.category === 'hot'),
    coldCoffee: (state) => state.menuItems.filter(item => item.category === 'cold'),
    foodItems: (state) => state.menuItems.filter(item => item.category === 'food'),
    desserts: (state) => state.menuItems.filter(item => item.category === 'dessert'),
    featuredItems: (state) => state.menuItems.filter(item => item.is_featured),
    availableItems: (state) => state.menuItems.filter(item => item.is_available)
  },

  actions: {
    async fetchMenuItems() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/menu-items`)
        this.menuItems = response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching menu items:', error)
      } finally {
        this.loading = false
      }
    },

    async fetchMenuItemsByCategory(category) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/menu-items/category/${category}`)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching menu items by category:', error)
        return []
      } finally {
        this.loading = false
      }
    },

    async fetchFeaturedItems() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/menu-items/featured`)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching featured items:', error)
        return []
      } finally {
        this.loading = false
      }
    }
  }
})