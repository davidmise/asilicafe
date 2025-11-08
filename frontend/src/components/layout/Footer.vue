<template>
  <div class="container-fluid footer text-white px-0 position-relative">
    <div class="row mx-0 pt-5 px-sm-3 px-lg-5">
      <div class="col-lg-3 col-md-6 mb-5">
        <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Get In Touch</h4>
        <p><i class="fa fa-map-marker-alt mr-2"></i>{{ contactAddress || '123 Street, New York, USA' }}</p>
        <p><i class="fa fa-phone-alt mr-2"></i>{{ contactPhone || '+012 345 67890' }}</p>
        <p class="m-0"><i class="fa fa-envelope mr-2"></i>{{ contactEmail || 'info@example.com' }}</p>
      </div>
      <div class="col-lg-3 col-md-6 mb-5">
        <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Follow Us</h4>
        <p>{{ socialDescription || 'Amet elitr vero magna sed ipsum sit kasd sea elitr lorem rebum' }}</p>
        <div class="d-flex justify-content-start">
          <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" :href="socialTwitter || '#'"><i class="fab fa-twitter"></i></a>
          <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" :href="socialFacebook || '#'"><i class="fab fa-facebook-f"></i></a>
          <a class="btn btn-lg btn-outline-light btn-lg-square mr-2" :href="socialLinkedin || '#'"><i class="fab fa-linkedin-in"></i></a>
          <a class="btn btn-lg btn-outline-light btn-lg-square" :href="socialInstagram || '#'"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-5">
        <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Open Hours</h4>
        <div>
          <h6 class="text-white text-uppercase">Monday - Friday</h6>
          <p>{{ hoursWeekday || '8.00 AM - 8.00 PM' }}</p>
          <h6 class="text-white text-uppercase">Saturday - Sunday</h6>
          <p>{{ hoursWeekend || '2.00 PM - 8.00 PM' }}</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 mb-5">
        <h4 class="text-white text-uppercase mb-4" style="letter-spacing: 3px;">Newsletter</h4>
        <p>{{ newsletterDescription || 'Amet elitr vero magna sed ipsum sit kasd sea elitr lorem rebum' }}</p>
        <div class="w-100">
          <div class="input-group">
            <input 
              type="text" 
              class="form-control border-light" 
              style="padding: 25px;" 
              placeholder="Your Email"
              v-model="newsletterEmail"
            >
            <div class="input-group-append">
              <button class="btn btn-primary font-weight-bold px-3" @click="subscribeNewsletter">Sign Up</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid text-center text-white border-top mt-4 py-4 px-sm-3 px-md-5" style="border-color: rgba(256, 256, 256, .1) !important;">
      <p class="mb-2 text-white">Copyright &copy; <a class="font-weight-bold" href="#">{{ companyName || 'Asili Cafe' }}</a>. All Rights Reserved.</p>
      <p class="m-0 text-white mt-2">Powered by <a class="font-weight-bold" href="http://www.pamojainc.co.tz" target="_blank" style="color: #DA9F5B;">Pamoja INC</a></p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useCompanyStore } from '../../stores/company'

const companyStore = useCompanyStore()
const newsletterEmail = ref('')

// Company info computed properties with fallback values
const companyName = computed(() => companyStore.getCompanyInfo('company_name') || 'Asili Cafe')
const contactAddress = computed(() => companyStore.getCompanyInfo('contact_address') || 'Mtweni opposite Karume Institute of Science and Technology | Kinazi mmoja opposite Mmoja Hospital, Zanzibar')
const contactPhone = computed(() => companyStore.getCompanyInfo('contact_phone') || '+255 XXX XXX XXX')
const contactEmail = computed(() => companyStore.getCompanyInfo('contact_email') || 'info@asilicafe.co.tz')
const hoursWeekday = computed(() => companyStore.getCompanyInfo('hours_weekday') || '7.00 AM - 9.00 PM')
const hoursWeekend = computed(() => companyStore.getCompanyInfo('hours_weekend') || '8.00 AM - 10.00 PM')
const newsletterDescription = computed(() => companyStore.getCompanyInfo('newsletter_description') || 'Stay updated with our latest coffee offerings, events, and special promotions at both Asili Cafe branches in Zanzibar')
const socialDescription = computed(() => companyStore.getCompanyInfo('social_description') || 'Follow us for daily updates, coffee tips, and community events at our Mtweni and Kinazi mmoja locations')
const socialFacebook = computed(() => companyStore.getCompanyInfo('social_facebook') || 'https://facebook.com/asilicafe')
const socialTwitter = computed(() => companyStore.getCompanyInfo('social_twitter') || 'https://twitter.com/asilicafe')
const socialInstagram = computed(() => companyStore.getCompanyInfo('social_instagram') || 'https://instagram.com/asili_cafe')
const socialLinkedin = computed(() => companyStore.getCompanyInfo('social_linkedin') || '#')

const subscribeNewsletter = () => {
  if (newsletterEmail.value) {
    // Here you would typically call an API to subscribe the user
    alert('Thank you for subscribing to our newsletter!')
    newsletterEmail.value = ''
  }
}

onMounted(async () => {
  try {
    await companyStore.fetchCompanyInfo()
  } catch (error) {
    console.log('Using fallback data for footer')
  }
})
</script>

<style scoped>
.footer {
  background: linear-gradient(rgba(51, 33, 29, .8), rgba(51, 33, 29, .8)), url('/img/carousel-2.jpg');
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
  margin-top: 0 !important;
  padding-top: 3rem;
}

.btn-outline-light {
  border-color: #DA9F5B;
  color: #DA9F5B;
}

.btn-outline-light:hover {
  background-color: #DA9F5B;
  border-color: #DA9F5B;
  color: white;
}

.btn-lg-square {
  width: 46px;
  height: 46px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.form-control {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.3);
  color: white;
}

.form-control::placeholder {
  color: rgba(255, 255, 255, 0.7);
}

.form-control:focus {
  background-color: rgba(255, 255, 255, 0.1);
  border-color: #DA9F5B;
  color: white;
  box-shadow: 0 0 0 0.2rem rgba(218, 159, 91, 0.25);
}
</style>