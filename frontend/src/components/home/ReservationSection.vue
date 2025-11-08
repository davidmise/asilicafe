<template>
  <div class="container-fluid my-5">
    <div class="container">
      <div class="reservation position-relative overlay-top overlay-bottom">
        <div class="row align-items-center">
          <div class="col-lg-6 my-5 my-lg-0">
            <div class="p-5">
              <div class="mb-4">
                <h1 class="display-3 text-primary">30% OFF</h1>
                <h1 class="text-white">For Online Reservation</h1>
              </div>
              <p class="text-white">
                Reserve your table at either of our Asili Cafe branches in Zanzibar - Mtweni opposite Karume Institute of Science and Technology, or Kinazi mmoja opposite Mmoja Hospital. 
                Perfect for students, professionals, and families looking for authentic Zanzibari coffee and cuisine.
              </p>
              <ul class="list-inline text-white m-0">
                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>Two convenient locations in Zanzibar</li>
                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>30% discount on total bill</li>
                <li class="py-2"><i class="fa fa-check text-primary mr-3"></i>Complimentary traditional spiced tea</li>
              </ul>
            </div>
          </div>
          <div class="col-lg-6">
            <div class="text-center p-5" style="background: rgba(51, 33, 29, .8);">
              <h1 class="text-white mb-4 mt-5">Book Your Table</h1>
              <ReservationForm />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import ReservationForm from '../forms/ReservationForm.vue'
</script>

<style scoped>
.reservation {
  background: linear-gradient(rgba(51, 33, 29, .7), rgba(51, 33, 29, .7)), url('/img/carousel-2.jpg');
  background-position: center center;
  background-repeat: no-repeat;
  background-size: cover;
  border-radius: 10px;
}
</style>