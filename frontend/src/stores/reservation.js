import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useReservationStore = defineStore('reservation', {
  state: () => ({
    reservations: [],
    loading: false,
    error: null
  }),

  getters: {
    todaysReservations: (state) => {
      const today = new Date().toISOString().split('T')[0]
      return state.reservations.filter(reservation => reservation.date === today)
    },
    pendingReservations: (state) => state.reservations.filter(reservation => reservation.status === 'pending'),
    confirmedReservations: (state) => state.reservations.filter(reservation => reservation.status === 'confirmed')
  },

  actions: {
    async fetchReservations() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/reservations`)
        this.reservations = response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching reservations:', error)
      } finally {
        this.loading = false
      }
    },

    async createReservation(reservationData) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.post(`${API_BASE_URL}/reservations`, reservationData)
        this.reservations.push(response.data.data)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error creating reservation:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async fetchReservation(id) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/reservations/${id}`)
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error fetching reservation:', error)
        return null
      } finally {
        this.loading = false
      }
    },

    async updateReservation(id, reservationData) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.put(`${API_BASE_URL}/reservations/${id}`, reservationData)
        const index = this.reservations.findIndex(reservation => reservation.id === id)
        if (index !== -1) {
          this.reservations[index] = response.data.data
        }
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error updating reservation:', error)
        throw error
      } finally {
        this.loading = false
      }
    },

    async deleteReservation(id) {
      this.loading = true
      this.error = null
      try {
        await axios.delete(`${API_BASE_URL}/reservations/${id}`)
        this.reservations = this.reservations.filter(reservation => reservation.id !== id)
      } catch (error) {
        this.error = error.message
        console.error('Error deleting reservation:', error)
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})