<template>
  <form @submit.prevent="submitReservation" class="mb-5">
    <div v-if="reservationStore.successMessage" class="alert alert-success">
      {{ reservationStore.successMessage }}
    </div>
    <div v-if="reservationStore.error" class="alert alert-danger">
      {{ reservationStore.error }}
    </div>
    
    <div class="form-group">
      <input 
        type="text" 
        class="form-control bg-transparent border-primary p-4" 
        placeholder="Name"
        v-model="form.name"
        required 
      />
    </div>
    <div class="form-group">
      <input 
        type="email" 
        class="form-control bg-transparent border-primary p-4" 
        placeholder="Email"
        v-model="form.email"
        required 
      />
    </div>
    <div class="form-group">
      <input 
        type="tel" 
        class="form-control bg-transparent border-primary p-4" 
        placeholder="Phone (Optional)"
        v-model="form.phone"
      />
    </div>
    <div class="form-group">
      <input 
        type="date" 
        class="form-control bg-transparent border-primary p-4" 
        v-model="form.reservation_date"
        :min="today"
        required
      />
    </div>
    <div class="form-group">
      <input 
        type="time" 
        class="form-control bg-transparent border-primary p-4" 
        v-model="form.reservation_time"
        required
      />
    </div>
    <div class="form-group">
      <select 
        class="custom-select bg-transparent border-primary px-4" 
        style="height: 49px;"
        v-model="form.number_of_people"
        required
      >
        <option value="">Select Number of People</option>
        <option v-for="i in 10" :key="i" :value="i">{{ i }} Person{{ i > 1 ? 's' : '' }}</option>
      </select>
    </div>
    <div class="form-group">
      <textarea 
        class="form-control bg-transparent border-primary p-4" 
        rows="3"
        placeholder="Special Requests (Optional)"
        v-model="form.special_requests"
      ></textarea>
    </div>
    
    <div>
      <button 
        class="btn btn-primary btn-block font-weight-bold py-3" 
        type="submit"
        :disabled="reservationStore.loading"
      >
        <span v-if="reservationStore.loading">
          <i class="fa fa-spinner fa-spin"></i> Booking...
        </span>
        <span v-else>Book Now</span>
      </button>
    </div>
  </form>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useReservationStore } from '../../stores/reservations'

const reservationStore = useReservationStore()

const form = ref({
  name: '',
  email: '',
  phone: '',
  reservation_date: '',
  reservation_time: '',
  number_of_people: '',
  special_requests: ''
})

const today = ref('')

const submitReservation = async () => {
  try {
    reservationStore.clearMessages()
    await reservationStore.createReservation(form.value)
    
    // Reset form on success
    form.value = {
      name: '',
      email: '',
      phone: '',
      reservation_date: '',
      reservation_time: '',
      number_of_people: '',
      special_requests: ''
    }
  } catch (error) {
    console.error('Reservation error:', error)
  }
}

onMounted(() => {
  // Set minimum date to today
  const now = new Date()
  today.value = now.toISOString().split('T')[0]
})
</script>

<style scoped>
.form-control,
.custom-select {
  background-color: rgba(255, 255, 255, 0.1) !important;
  border-color: #DA9F5B !important;
  color: white !important;
}

.form-control::placeholder {
  color: rgba(255, 255, 255, 0.7) !important;
}

.form-control:focus,
.custom-select:focus {
  background-color: rgba(255, 255, 255, 0.2) !important;
  border-color: #DA9F5B !important;
  color: white !important;
  box-shadow: 0 0 0 0.2rem rgba(218, 159, 91, 0.25) !important;
}

.custom-select option {
  background-color: #333;
  color: white;
}

.alert {
  margin-bottom: 20px;
}
</style>