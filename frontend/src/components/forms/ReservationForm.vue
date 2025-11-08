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

/* Mobile Responsive Form Styles */
@media (max-width: 767.98px) {
  /* Adjust form control padding for mobile */
  .form-control,
  .custom-select {
    padding: 0.75rem 1rem !important;
    font-size: 1rem;
  }
  
  /* Make inputs touch-friendly (min 44px height) */
  .form-control,
  .custom-select {
    min-height: 48px;
  }
  
  /* Adjust button padding */
  .btn-block {
    padding: 0.875rem 1rem !important;
    font-size: 1rem;
  }
  
  /* Reduce form group spacing */
  .form-group {
    margin-bottom: 1rem;
  }
  
  /* Adjust textarea rows for mobile */
  .form-control[rows] {
    min-height: 80px;
  }
  
  /* Make form fit better on small screens */
  form {
    margin-bottom: 2rem !important;
  }
  
  /* Adjust alert messages */
  .alert {
    padding: 0.75rem;
    font-size: 0.9rem;
    margin-bottom: 1rem;
  }
}

@media (max-width: 575.98px) {
  /* Extra small devices */
  .form-control,
  .custom-select {
    padding: 0.625rem 0.875rem !important;
    font-size: 0.95rem;
  }
  
  .btn-block {
    padding: 0.75rem 0.875rem !important;
    font-size: 0.95rem;
  }
  
  .form-group {
    margin-bottom: 0.875rem;
  }
  
  form {
    margin-bottom: 1.5rem !important;
  }
}

/* Tablet styles */
@media (min-width: 768px) and (max-width: 991.98px) {
  .form-control,
  .custom-select {
    padding: 0.875rem 1.125rem !important;
  }
  
  .btn-block {
    padding: 1rem 1.25rem !important;
  }
}

/* Improve date and time inputs on mobile */
@media (max-width: 767.98px) {
  input[type="date"],
  input[type="time"] {
    -webkit-appearance: none;
    appearance: none;
    min-height: 48px;
  }
  
  input[type="date"]::-webkit-calendar-picker-indicator,
  input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
  }
}

/* Loading state button */
.btn:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

/* Improve select dropdown on mobile */
@media (max-width: 767.98px) {
  .custom-select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23DA9F5B' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
    background-size: 16px 12px;
    padding-right: 2.5rem;
  }
}
</style>