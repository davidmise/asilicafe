import { defineStore } from 'pinia'
import axios from 'axios'

const API_BASE_URL = 'http://localhost:8000/api/v1'

export const useCompanyInfoStore = defineStore('companyInfo', {
  state: () => ({
    companyInfo: {
      name: 'Asili Cafe',
      about_subtitle: 'Authentic Zanzibari Coffee & Cuisine',
      about_description: 'Experience the rich flavors of Zanzibar at Asili Cafe. We serve traditional spiced coffee and local delicacies in a warm, welcoming atmosphere. Located opposite Karume Institute and near Mmoja Hospital, we\'re your perfect study spot and meeting place.',
      vision_description: 'To become Zanzibar\'s premier destination for authentic local coffee and cuisine, fostering community connections while preserving traditional flavors and hospitality.',
      phone: '+255 XXX XXX XXX',
      email: 'info@asilicafe.co.tz',
      address: 'Mtweni opposite Karume Institute of Science and Technology | Kinazi mmoja opposite Mmoja Hospital, Zanzibar'
    },
    loading: false,
    error: null
  }),

  getters: {
    contactInfo: (state) => ({
      phone: state.companyInfo.phone,
      email: state.companyInfo.email,
      address: state.companyInfo.address
    })
  },

  actions: {
    async fetchCompanyInfo() {
      this.loading = true
      this.error = null
      try {
        const response = await axios.get(`${API_BASE_URL}/company-info`)
        if (response.data.data) {
          this.companyInfo = { ...this.companyInfo, ...response.data.data }
        }
      } catch (error) {
        this.error = error.message
        console.error('Error fetching company info:', error)
        // Keep default values if API fails
      } finally {
        this.loading = false
      }
    },

    async updateCompanyInfo(companyData) {
      this.loading = true
      this.error = null
      try {
        const response = await axios.put(`${API_BASE_URL}/company-info`, companyData)
        this.companyInfo = { ...this.companyInfo, ...response.data.data }
        return response.data.data
      } catch (error) {
        this.error = error.message
        console.error('Error updating company info:', error)
        throw error
      } finally {
        this.loading = false
      }
    }
  }
})