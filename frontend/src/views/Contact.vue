<template>
  <div class="contact-page hero-page">
    <!-- Page Header Start -->
    <PageHeader 
      title="Contact Us" 
      breadcrumb="Contact" 
      background-image="carousel-1.jpg" 
    />
    <!-- Page Header End -->
    
    <!-- Contact Content -->
    <div class="container-fluid pt-5">
      <div class="container">
        <div class="section-title">
          <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">Contact Us</h4>
          <h1 class="display-4">Feel Free To Contact</h1>
        </div>
        <div class="row px-3 pb-2">
          <div class="col-sm-4 text-center mb-3">
            <i class="fa fa-2x fa-map-marker-alt mb-3 text-primary"></i>
            <h4 class="font-weight-bold">Address</h4>
            <p>{{ companyInfo.address }}</p>
          </div>
          <div class="col-sm-4 text-center mb-3">
            <i class="fa fa-2x fa-phone-alt mb-3 text-primary"></i>
            <h4 class="font-weight-bold">Phone</h4>
            <p>{{ companyInfo.phone }}</p>
          </div>
          <div class="col-sm-4 text-center mb-3">
            <i class="far fa-2x fa-envelope mb-3 text-primary"></i>
            <h4 class="font-weight-bold">Email</h4>
            <p>{{ companyInfo.email }}</p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 pb-5">
            <iframe 
              style="width: 100%; height: 443px; border: 0;"
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63426.82850032877!2d39.19096314199828!3d-6.16494469640977!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185cd9ac1dd8d82d%3A0x7ef7b8b82b1b0b0a!2sStone%20Town%2C%20Zanzibar!5e0!3m2!1sen!2stz!4v1699000000000!5m2!1sen!2stz"
              frameborder="0" 
              allowfullscreen="" 
              aria-hidden="false" 
              tabindex="0">
            </iframe>
          </div>
          <div class="col-md-6 pb-5">
            <div class="contact-form">
              <div id="success"></div>
              <form @submit.prevent="submitForm" name="sentMessage" id="contactForm" novalidate="novalidate">
                <div class="control-group">
                  <input 
                    v-model="form.name"
                    type="text" 
                    class="form-control bg-transparent p-4" 
                    id="name" 
                    placeholder="Your Name"
                    required 
                  />
                  <p class="help-block text-danger"></p>
                </div>
                <div class="control-group">
                  <input 
                    v-model="form.email"
                    type="email" 
                    class="form-control bg-transparent p-4" 
                    id="email" 
                    placeholder="Your Email"
                    required 
                  />
                  <p class="help-block text-danger"></p>
                </div>
                <div class="control-group">
                  <input 
                    v-model="form.subject"
                    type="text" 
                    class="form-control bg-transparent p-4" 
                    id="subject" 
                    placeholder="Subject"
                    required 
                  />
                  <p class="help-block text-danger"></p>
                </div>
                <div class="control-group">
                  <textarea 
                    v-model="form.message"
                    class="form-control bg-transparent py-3 px-4" 
                    rows="5" 
                    id="message" 
                    placeholder="Message"
                    required
                  ></textarea>
                  <p class="help-block text-danger"></p>
                </div>
                <div>
                  <button 
                    class="btn btn-primary font-weight-bold py-3 px-5" 
                    type="submit" 
                    id="sendMessageButton"
                    :disabled="isSubmitting"
                  >
                    {{ isSubmitting ? 'Sending...' : 'Send Message' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import PageHeader from '@/components/layout/PageHeader.vue'
import { useCompanyInfoStore } from '@/stores/companyInfo'

const companyStore = useCompanyInfoStore()

// Form data
const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const isSubmitting = ref(false)

// Get company info with fallbacks for Asili Cafe
const companyInfo = computed(() => ({
  address: companyStore.companyInfo.address || 'Mtweni opposite Karume Institute of Science and Technology | Kinazi mmoja opposite Mmoja Hospital, Zanzibar',
  phone: companyStore.companyInfo.phone || '+255 XXX XXX XXX',
  email: companyStore.companyInfo.email || 'info@asilicafe.co.tz'
}))

// Form submission
const submitForm = async () => {
  isSubmitting.value = true
  
  try {
    // Simulate form submission
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Here you would typically send the data to your backend
    console.log('Form submitted:', form.value)
    
    // Show success message
    alert('Thank you for your message! We will get back to you soon.')
    
    // Reset form
    form.value = {
      name: '',
      email: '',
      subject: '',
      message: ''
    }
  } catch (error) {
    console.error('Error submitting form:', error)
    alert('Sorry, there was an error sending your message. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  // Load company info in background
  companyStore.fetchCompanyInfo().catch(() => {
    console.log('Using fallback company data')
  })
})
</script>

<style scoped>
.contact-page {
  min-height: 100vh;
}

.hero-page :deep(.container-fluid:first-of-type) {
  padding-top: 0 !important;
}

/* Section Title Styling */
.section-title {
  text-align: center;
  margin-bottom: 70px;
}

.section-title h4 {
  color: var(--primary-brown);
  font-weight: 600;
  margin-bottom: 15px;
}

.section-title h1 {
  font-size: 2.5rem;
  font-weight: 600;
  color: #333;
}

/* Contact Info Cards */
.text-center i {
  color: var(--primary-brown);
  margin-bottom: 20px;
}

.text-center h4 {
  color: #333;
  font-size: 1.2rem;
  margin-bottom: 15px;
}

.text-center p {
  color: #666;
  margin: 0;
  line-height: 1.6;
}

/* Map Styling */
iframe {
  border-radius: 8px;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Contact Form Styling */
.contact-form {
  padding: 0;
}

.control-group {
  margin-bottom: 25px;
}

.form-control.bg-transparent {
  background-color: transparent !important;
  border: 2px solid #e9ecef;
  border-radius: 8px;
  color: #333;
  font-size: 16px;
  transition: all 0.3s ease;
}

.form-control.bg-transparent:focus {
  border-color: var(--primary-brown);
  box-shadow: 0 0 0 0.2rem rgba(145, 109, 77, 0.25);
  background-color: transparent !important;
}

.form-control.bg-transparent::placeholder {
  color: #999;
  font-weight: 400;
}

/* Button Styling */
.btn-primary {
  background-color: var(--primary-brown);
  border-color: var(--primary-brown);
  font-size: 16px;
  font-weight: 600;
  transition: all 0.3s ease;
  border-radius: 8px;
}

.btn-primary:hover {
  background-color: var(--primary-accent);
  border-color: var(--primary-accent);
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(145, 109, 77, 0.3);
}

.btn-primary:disabled {
  background-color: #6c757d;
  border-color: #6c757d;
  transform: none;
  box-shadow: none;
}

/* Help text styling */
.help-block {
  font-size: 12px;
  margin-top: 5px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .section-title h1 {
    font-size: 2rem;
  }
  
  .col-sm-4 {
    margin-bottom: 30px;
  }
  
  iframe {
    height: 300px !important;
    margin-bottom: 30px;
  }
  
  .form-control.bg-transparent {
    padding: 15px !important;
  }
  
  .btn-primary {
    width: 100%;
    padding: 15px !important;
  }
}

/* CSS Variables */
:root {
  --primary-brown: #916d4d;
  --primary-accent: #442a09;
}
</style>