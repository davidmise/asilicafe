<template>
  <div class="container-fluid py-5" ref="aboutContainer">
    <div class="container">
      <div class="section-title" ref="sectionTitle">
        <h4 class="text-primary text-uppercase section-subtitle" style="letter-spacing: 5px;">About Us</h4>
        <h1 class="display-4 section-main-title">{{ aboutTitle || 'Serving Since 2010' }}</h1>
      </div>
      <div class="row">
        <div class="col-lg-4 py-0 py-lg-5 about-story" ref="aboutStory">
          <h1 class="mb-3 story-title">{{ storyTitle || 'Our Story' }}</h1>
          <h5 class="mb-3 story-subtitle">Bringing authentic Zanzibari coffee culture since 2010</h5>
          <p class="story-content">{{ storyContent || 'Located in the beautiful island of Zanzibar, Asili Cafe has been serving the finest locally sourced coffee to our community for over a decade. With two convenient branches - one opposite Karume Institute of Science and Technology and another at Kinazi mmoja opposite Mmoja Hospital - we take pride in showcasing the rich coffee heritage of Zanzibar.' }}</p>
          <RouterLink to="/about" class="btn btn-secondary font-weight-bold py-2 px-4 mt-2 story-btn">
            Learn More
          </RouterLink>
        </div>
        <div class="col-lg-4 py-5 py-lg-0 about-image-wrapper" style="min-height: 500px;" ref="aboutImage">
          <div class="position-relative h-100 image-container">
            <img class="position-absolute w-100 h-100 about-main-image" src="/img/about.png" style="object-fit: cover;" alt="About Asili Cafe">
          </div>
        </div>
        <div class="col-lg-4 py-0 py-lg-5 about-vision" ref="aboutVision">
          <h1 class="mb-3 vision-title">{{ visionTitle || 'Our Vision' }}</h1>
          <p class="vision-content">{{ visionContent || 'To be Zanzibar\'s premier destination for exceptional coffee experiences, fostering community connections while supporting local farmers and sustainable practices. We envision Asili Cafe as a cultural hub where tradition meets innovation across our two branches in the heart of Zanzibar.' }}</p>
          <h5 class="mb-3 vision-feature"><i class="fa fa-check text-primary mr-3 feature-icon"></i>Premium Zanzibari coffee beans</h5>
          <h5 class="mb-3 vision-feature"><i class="fa fa-check text-primary mr-3 feature-icon"></i>Two convenient locations</h5>
          <h5 class="mb-3 vision-feature"><i class="fa fa-check text-primary mr-3 feature-icon"></i>Community gathering spaces</h5>
          <RouterLink to="/about" class="btn btn-primary font-weight-bold py-2 px-4 mt-2 vision-btn">
            Learn More
          </RouterLink>
        </div>
   </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, nextTick } from 'vue'
import { useCompanyStore } from '../../stores/company'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

// Template refs for animations
const aboutContainer = ref(null)
const sectionTitle = ref(null)
const aboutStory = ref(null)
const aboutImage = ref(null)
const aboutVision = ref(null)

const companyStore = useCompanyStore()

const aboutTitle = computed(() => companyStore.getCompanyInfo('about_title'))
const storyTitle = computed(() => companyStore.getCompanyInfo('about_story_title'))
const storyContent = computed(() => companyStore.getCompanyInfo('about_story_content'))
const visionTitle = computed(() => companyStore.getCompanyInfo('about_vision_title'))
const visionContent = computed(() => companyStore.getCompanyInfo('about_vision_content'))

// Initialize GSAP animations
const initAnimations = () => {
  // Set initial states for animations
  gsap.set('.section-subtitle', { y: 50, opacity: 0 })
  gsap.set('.section-main-title', { y: 60, opacity: 0 })
  gsap.set('.story-title', { x: -60, opacity: 0 })
  gsap.set('.story-subtitle', { x: -40, opacity: 0 })
  gsap.set('.story-content', { x: -30, opacity: 0 })
  gsap.set('.story-btn', { x: -20, opacity: 0, scale: 0.9 })
  gsap.set('.about-main-image', { scale: 0.8, opacity: 0, rotation: -5 })
  gsap.set('.vision-title', { x: 60, opacity: 0 })
  gsap.set('.vision-content', { x: 40, opacity: 0 })
  gsap.set('.vision-feature', { x: 30, opacity: 0 })
  gsap.set('.vision-btn', { x: 20, opacity: 0, scale: 0.9 })
  gsap.set('.feature-icon', { scale: 0, rotation: 0 })

  // Title animations
  const titleTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: sectionTitle.value,
      start: 'top 80%',
      end: 'bottom 20%',
      toggleActions: 'play none none reverse'
    }
  })

  titleTimeline
    .to('.section-subtitle', {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    })
    .to('.section-main-title', {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: 'power2.out'
    }, '-=0.4')

  // Story section animations (left column)
  const storyTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: aboutStory.value,
      start: 'top 85%',
      end: 'bottom 20%',
      toggleActions: 'play none none reverse'
    }
  })

  storyTimeline
    .to('.story-title', {
      x: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    })
    .to('.story-subtitle', {
      x: 0,
      opacity: 1,
      duration: 0.6,
      ease: 'power2.out'
    }, '-=0.4')
    .to('.story-content', {
      x: 0,
      opacity: 1,
      duration: 0.6,
      ease: 'power2.out'
    }, '-=0.3')
    .to('.story-btn', {
      x: 0,
      opacity: 1,
      scale: 1,
      duration: 0.5,
      ease: 'back.out(1.7)'
    }, '-=0.2')

  // Center image animations
  const imageTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: aboutImage.value,
      start: 'top 85%',
      end: 'bottom 20%',
      toggleActions: 'play none none reverse'
    }
  })

  imageTimeline.to('.about-main-image', {
    scale: 1,
    opacity: 1,
    rotation: 0,
    duration: 1.2,
    ease: 'power2.out'
  })

  // Vision section animations (right column)
  const visionTimeline = gsap.timeline({
    scrollTrigger: {
      trigger: aboutVision.value,
      start: 'top 85%',
      end: 'bottom 20%',
      toggleActions: 'play none none reverse'
    }
  })

  visionTimeline
    .to('.vision-title', {
      x: 0,
      opacity: 1,
      duration: 0.8,
      ease: 'power2.out'
    })
    .to('.vision-content', {
      x: 0,
      opacity: 1,
      duration: 0.6,
      ease: 'power2.out'
    }, '-=0.4')

  // Animate vision features with stagger and icon animations
  const visionFeatures = document.querySelectorAll('.vision-feature')
  visionFeatures.forEach((feature, index) => {
    visionTimeline.to(feature, {
      x: 0,
      opacity: 1,
      duration: 0.5,
      ease: 'power2.out'
    }, `-=0.${3 - index}`)
    
    visionTimeline.to(feature.querySelector('.feature-icon'), {
      scale: 1,
      rotation: 360,
      duration: 0.5,
      ease: 'back.out(1.7)'
    }, `-=0.3`)
  })

  visionTimeline.to('.vision-btn', {
    x: 0,
    opacity: 1,
    scale: 1,
    duration: 0.5,
    ease: 'back.out(1.7)'
  }, '-=0.2')

  // Add hover animations for buttons
  const storyBtn = document.querySelector('.story-btn')
  const visionBtn = document.querySelector('.vision-btn')
  
  if (storyBtn) {
    storyBtn.addEventListener('mouseenter', () => {
      gsap.to(storyBtn, {
        scale: 1.05,
        duration: 0.3,
        ease: 'power2.out'
      })
    })

    storyBtn.addEventListener('mouseleave', () => {
      gsap.to(storyBtn, {
        scale: 1,
        duration: 0.3,
        ease: 'power2.out'
      })
    })
  }

  if (visionBtn) {
    visionBtn.addEventListener('mouseenter', () => {
      gsap.to(visionBtn, {
        scale: 1.05,
        duration: 0.3,
        ease: 'power2.out'
      })
    })

    visionBtn.addEventListener('mouseleave', () => {
      gsap.to(visionBtn, {
        scale: 1,
        duration: 0.3,
        ease: 'power2.out'
      })
    })
  }

  // Add image hover animation
  const aboutMainImage = document.querySelector('.about-main-image')
  const imageContainer = document.querySelector('.image-container')
  
  if (imageContainer && aboutMainImage) {
    imageContainer.addEventListener('mouseenter', () => {
      gsap.to(aboutMainImage, {
        scale: 1.1,
        rotation: 2,
        duration: 0.5,
        ease: 'power2.out'
      })
    })

    imageContainer.addEventListener('mouseleave', () => {
      gsap.to(aboutMainImage, {
        scale: 1,
        rotation: 0,
        duration: 0.5,
        ease: 'power2.out'
      })
    })
  }
}

onMounted(async () => {
  if (!companyStore.companyInfo || Object.keys(companyStore.companyInfo).length === 0) {
    await companyStore.fetchCompanyInfo()
  }

  // Initialize animations after content is loaded
  await nextTick()
  initAnimations()
})

onUnmounted(() => {
  // Clean up ScrollTrigger instances
  ScrollTrigger.getAll().forEach(trigger => trigger.kill())
})
</script>

<style scoped>
.btn-secondary {
  background-color: #231f20;
  border-color: #231f20;
  color: white;
  transition: all 0.3s ease;
}

.btn-secondary:hover {
  background-color: #442a09;
  border-color: #442a09;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(35, 31, 32, 0.3);
}

/* Animation enhancement styles */
.about-story {
  position: relative;
}

.about-image-wrapper {
  overflow: hidden;
  border-radius: 15px;
}

.image-container {
  border-radius: 15px;
  overflow: hidden;
  cursor: pointer;
  transition: all 0.3s ease;
}

.about-main-image {
  transition: all 0.5s ease;
  border-radius: 15px;
}

.about-vision {
  position: relative;
}

.story-title, .vision-title {
  color: var(--primary-brown);
  transition: all 0.3s ease;
}

.story-subtitle {
  color: var(--primary-accent);
  font-weight: 500;
  transition: all 0.3s ease;
}

.story-content, .vision-content {
  color: #666;
  line-height: 1.7;
  transition: all 0.3s ease;
}

.vision-feature {
  transition: all 0.3s ease;
  cursor: default;
}

.vision-feature:hover {
  color: var(--primary-brown);
  transform: translateX(5px);
}

.feature-icon {
  transition: all 0.3s ease;
}

.story-btn, .vision-btn {
  transition: all 0.3s ease;
  border-radius: 25px;
  position: relative;
  overflow: hidden;
}

.story-btn:hover, .vision-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(145, 109, 77, 0.3);
}

.btn-primary {
  background: linear-gradient(45deg, var(--primary-brown), #DA9F5B);
  border: none;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: linear-gradient(45deg, #DA9F5B, var(--primary-brown));
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(218, 159, 91, 0.4);
}

/* Fix the overlapping decorative line issue */
.section-title {
  position: relative;
  padding: 120px 0 35px 0;
  text-align: center;
}

.section-title::after {
  position: absolute;
  content: "";
  width: 2px;
  height: 80px;
  top: 20px;
  left: 50%;
  margin-left: -1px;
  background: #DA9F5B;
}

/* Title animation styles */
.section-subtitle {
  margin-bottom: 10px;
}

.section-main-title {
  margin-bottom: 0;
  background: linear-gradient(45deg, var(--primary-brown), #DA9F5B);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Add subtle background pattern */
.about-story::before {
  content: '';
  position: absolute;
  top: -20px;
  left: -20px;
  right: -20px;
  bottom: -20px;
  background: linear-gradient(135deg, rgba(218, 159, 91, 0.05) 0%, transparent 50%);
  border-radius: 20px;
  z-index: -1;
  opacity: 0;
  transition: opacity 0.5s ease;
}

.about-story:hover::before {
  opacity: 1;
}

.about-vision::before {
  content: '';
  position: absolute;
  top: -20px;
  left: -20px;
  right: -20px;
  bottom: -20px;
  background: linear-gradient(225deg, rgba(218, 159, 91, 0.05) 0%, transparent 50%);
  border-radius: 20px;
  z-index: -1;
  opacity: 0;
  transition: opacity 0.5s ease;
}

.about-vision:hover::before {
  opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 992px) {
  .about-story, .about-vision {
    margin-bottom: 3rem;
  }
  
  .about-image-wrapper {
    margin: 2rem 0;
  }
}

@media (max-width: 768px) {
  .story-title, .vision-title {
    font-size: 1.8rem;
  }
  
  .section-title {
    padding: 80px 0 25px 0;
  }
}
</style>