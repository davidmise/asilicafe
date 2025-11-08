import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useReservationStore = defineStore('reservations', {
  state: () => ({
    reservations: [],
    loading: false,
    error: null,
    successMessage: null
  }),

  actions: {
    async createReservation(reservationData) {
      this.loading = true
      this.error = null
      this.successMessage = null
      try {
        const response = await axios.post(`${API_BASE_URL}/reservations`, reservationData)
        this.successMessage = 'Reservation created successfully!'
        return response.data.data
      } catch (error) {
        if (error.response && error.response.data && error.response.data.message) {
          this.error = error.response.data.message
        } else {
          this.error = 'Failed to create reservation. Please try again.'
        }
        console.error('Error creating reservation:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    clearMessages() {
      this.error = null
      this.successMessage = null
    }
  }
})