<template >
  <div class="menu-container" ref="menuContainer">
    <!-- Loading State -->
    <!-- <div v-if="loading" class="loading-state">
      <div class="loading-spinner"></div>
      <p class="loading-text">Loading our delicious menu...</p>
    </div> -->

    <!-- Section Title -->
    <div class="menu-section-header">
      <div class="container">
        <div class="section-title" ref="sectionTitle">
          <h4 class="text-primary text-uppercase section-subtitle" style="letter-spacing: 5px;">Our Menu</h4>
          <h1 class="display-4 section-main-title">{{ aboutTitle || 'Discover Our Flavors' }}</h1>
        </div>
      </div>
    </div>

  
    <!-- Main Menu Layout -->
    <div class="menu-layout" ref="menuLayout">
      <div class="container">

          <!-- Categories Top Navigation Bar -->
    <div class="categories-top-nav" ref="categoriesNav">
      <div class="container">
        <div class="nav-wrapper" ref="navWrapper">
          <button 
            v-for="(category, index) in categories" 
            :key="category.id"
            :class="['category-tab', { active: activeCategory === category.id }]"
            @click="setActiveCategory(category.id, index)"
            :data-index="index"
            ref="categoryBtns"
          >
            <span class="category-name">{{ category.name }}</span>
          </button>
        </div>
      </div>
    </div>
    
        <!-- Menu Content Area (Full Width) -->
        <div class="menu-content" ref="contentPanel">
          <div class="content-header">
            <div class="header-ornament"></div>
            <h1 class="category-title">{{ getActiveCategoryName() }}</h1>
            <p class="category-description">{{ getActiveCategoryDescription() }}</p>
            <div class="header-ornament"></div>
          </div>

          <div class="menu-items-grid" ref="itemsGrid">
          <div 
            v-for="(item, itemIndex) in getItemsByCategory(activeCategory)" 
            :key="item.id"
            class="menu-item"
            @click="selectItem(item)"
            :class="{ selected: selectedItem && selectedItem.id === item.id }"
            :data-item-index="itemIndex"
            ref="menuItemRefs"
          >
            <div class="item-image">
              <img 
                :src="item.image || '/img/menu-1.jpg'" 
                :alt="item.name"
                @error="handleImageError($event)"
                loading="lazy"
              >
              <div class="price-badge">
                <span class="currency">TZS</span>
                <span class="amount">{{ formatPrice(item.price) }}</span>
              </div>
            </div>
            
            <div class="item-details">
              <h3 class="item-name">{{ item.name }}</h3>
              <p class="item-description">{{ item.description }}</p>
              
              <div class="item-variations">
                <span v-for="variation in item.variations" :key="variation" class="variation-tag">
                  {{ variation }}
                </span>
              </div>
              
              <button class="order-btn" @click.stop="quickOrder(item)">
                <i class="fa fa-plus"></i>
                Order Now
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

    <!-- Item Details Modal -->
    <div v-if="selectedItem" class="modal-overlay" @click="selectedItem = null" ref="modalBackdrop">
      <div class="modal-content" @click.stop ref="modal">
        <button class="modal-close" @click="selectedItem = null">
          <i class="fa fa-times"></i>
        </button>
        
        <div class="modal-header">
          <img 
            :src="selectedItem.image || '/img/menu-1.jpg'" 
            :alt="selectedItem.name" 
            class="modal-image"
            @error="handleImageError($event)"
          >
          <div class="modal-overlay-content">
            <h2 class="modal-title">{{ selectedItem.name }}</h2>
            <div class="modal-price">
              <span class="currency">TZS</span>
              <span class="amount">{{ formatPrice(selectedItem.price) }}</span>
            </div>
          </div>
        </div>
        
        <div class="modal-body">
          <p class="modal-description">{{ selectedItem.description }}</p>
          
          <div v-if="selectedItem.variations && selectedItem.variations.length > 0" class="modal-variations">
            <h5>Available Options:</h5>
            <div class="modal-variation-list">
              <label v-for="variation in selectedItem.variations" :key="variation" class="variation-option">
                <input type="radio" :name="`variation-${selectedItem.id}`" :value="variation">
                <span class="variation-label">{{ variation }}</span>
              </label>
            </div>
          </div>
          
          <div class="modal-actions">
            <button class="btn-order" @click="addToCart(selectedItem, getSelectedVariation())">
              <i class="fa fa-shopping-cart"></i>
              Add to Cart
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick, watch } from 'vue'
import { useMenuStore } from '../../stores/menu'
import { gsap } from 'gsap'
import { ScrollTrigger } from 'gsap/ScrollTrigger'

// Register GSAP plugins
gsap.registerPlugin(ScrollTrigger)

const menuStore = useMenuStore()
const loading = ref(false)
const activeCategory = ref('soft-drinks')
const selectedItem = ref(null)

// Template refs
const menuContainer = ref(null)
const menuLayout = ref(null)
const categoriesSidebar = ref(null)
const categoriesNav = ref(null)
const categoryBtns = ref([])
const contentPanel = ref(null)
const itemsGrid = ref(null)
const menuItemRefs = ref([])
const modalBackdrop = ref(null)
const modal = ref(null)

// Animation timeline
let mainTimeline = null
let scrollTriggerInstances = []

// Define categories with descriptions
const categories = ref([
  { id: 'soft-drinks', name: 'Beverages', description: 'Refreshing Swahili drinks' },
  { id: 'smoothies', name: 'Smoothies & Shakes', description: 'Fresh tropical blends' },
  { id: 'breakfast', name: 'Breakfast', description: 'Traditional morning delights' },
  { id: 'burgers-sandwiches', name: 'Burgers & Sandwiches', description: 'Hearty & satisfying' },
  { id: 'pizza', name: 'Pizza', description: 'Wood-fired perfection' },
  { id: 'soup', name: 'Soups', description: 'Comforting coastal recipes' },
  { id: 'seafood', name: 'Seafood', description: 'Fresh from the Indian Ocean' },
  { id: 'main-dishes', name: 'Main Dishes', description: 'Swahili specialties' },
  { id: 'sides', name: 'Sides & Extras', description: 'Perfect accompaniments' }
])

// Set up the menu items based on the provided menu
const menuData = ref([
  // BEVERAGES (Soft Drinks & Hot Drinks)
  { id: 1, name: 'Fresh Juice', price: 'Glass', category: 'soft-drinks', description: 'Freshly pressed tropical fruits - mango, passion fruit, orange, or pineapple', image: 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?w=500&h=300&fit=crop' },
  { id: 2, name: 'Asili Signature Juice', price: 'Glass', category: 'soft-drinks', description: 'Our special blend of exotic Zanzibar fruits with a hint of ginger', image: 'https://images.unsplash.com/photo-1622597467836-f3285f2131b8?w=500&h=300&fit=crop' },
  { id: 3, name: 'Asili Milk Tea (Chai)', price: '2,000 / 4,000', category: 'soft-drinks', description: 'Traditional spiced milk tea with our secret blend of Zanzibar spices', variations: ['Small', 'Large'], image: 'https://images.unsplash.com/photo-1597318181274-838657f691f0?w=500&h=300&fit=crop' },
  { id: 4, name: 'Dawa Tea', price: '2,000 / 4,000', category: 'soft-drinks', description: 'Traditional Swahili healing tea with honey, ginger, and fresh lemon', variations: ['Small', 'Large'], image: 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=500&h=300&fit=crop' },
  { id: 5, name: 'Black Tea (Chai Rangi)', price: '2,000 / 4,000', category: 'soft-drinks', description: 'Premium Ceylon tea served with cardamom, Zanzibar style', variations: ['Small', 'Large'], image: 'https://images.unsplash.com/photo-1559056199-641a0ac8b55e?w=500&h=300&fit=crop' },
  { id: 6, name: 'Tangawizi (Ginger Tea)', price: '2,500 / 5,000', category: 'soft-drinks', description: 'Warming ginger tea with honey and lemon, perfect for digestion', variations: ['Small', 'Large'], image: 'https://images.unsplash.com/photo-1563822249366-c98e01df7d33?w=500&h=300&fit=crop' },
  { id: 7, name: 'Zanzibar Coffee', price: '3,000 / 6,000', category: 'soft-drinks', description: 'Locally roasted coffee beans, served Arabic style with spices', variations: ['Small', 'Large'], image: 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500&h=300&fit=crop' },
  { id: 8, name: 'Soda (Locally Bottled)', price: '2,000', category: 'soft-drinks', description: 'Choice of Coca-Cola, Sprite, Fanta, or Stoney Tangawizi', image: 'https://images.unsplash.com/photo-1629203851122-3726ecdf080e?w=500&h=300&fit=crop' },
  { id: 9, name: 'Mineral Water', price: '1,500 / 2,500', category: 'soft-drinks', description: 'Pure filtered water sourced locally', variations: ['0.5L', '1.5L'], image: 'https://images.unsplash.com/photo-1523362628745-0c100150b504?w=500&h=300&fit=crop' },
  { id: 10, name: 'Madafu (Coconut Water)', price: '3,000', category: 'soft-drinks', description: 'Fresh young coconut water straight from the palm', image: 'https://images.unsplash.com/photo-1585636350143-ea5a3c1b6094?w=500&h=300&fit=crop' },
  
  // SMOOTHIES & MILKSHAKES
  { id: 11, name: 'Mango Smoothie', price: '6,000', category: 'smoothies', description: 'Sweet tropical mango from Zanzibar orchards blended to perfection', image: 'https://images.unsplash.com/photo-1623065422902-30a2d299bbe4?w=500&h=300&fit=crop' },
  { id: 12, name: 'Avocado Smoothie (Parachichi)', price: '7,000', category: 'smoothies', description: 'Creamy avocado smoothie with honey, a local island favorite', image: 'https://images.unsplash.com/photo-1623428187969-5da2dcea5ebf?w=500&h=300&fit=crop' },
  { id: 13, name: 'Banana Smoothie', price: '5,000', category: 'smoothies', description: 'Rich smoothie made from sweet island bananas', image: 'https://images.unsplash.com/photo-1638176066666-ffb2f013c7dd?w=500&h=300&fit=crop' },
  { id: 14, name: 'Papaya Smoothie (Papai)', price: '6,000', category: 'smoothies', description: 'Refreshing papaya blend with lime and mint', image: 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?w=500&h=300&fit=crop' },
  { id: 15, name: 'Mixed Fruit Smoothie', price: '7,500', category: 'smoothies', description: 'Tropical medley of mango, passion fruit, pineapple, and banana', image: 'https://images.unsplash.com/photo-1505252585461-04db1eb84625?w=500&h=300&fit=crop' },
  { id: 16, name: 'Strawberry Milkshake', price: '7,000', category: 'smoothies', description: 'Fresh strawberries blended with rich vanilla ice cream', image: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&h=300&fit=crop' },
  { id: 17, name: 'Chocolate Milkshake', price: '7,000', category: 'smoothies', description: 'Rich chocolate milkshake with premium cocoa', image: 'https://images.unsplash.com/photo-1572490122747-3968b75cc699?w=500&h=300&fit=crop' },
  { id: 18, name: 'Vanilla Milkshake', price: '7,000', category: 'smoothies', description: 'Classic vanilla milkshake with Zanzibar vanilla', image: 'https://images.unsplash.com/photo-1579372786545-d24232daf58c?w=500&h=300&fit=crop' },
  { id: 19, name: 'Date Shake (Tende)', price: '8,000', category: 'smoothies', description: 'Traditional Swahili date shake with milk and honey', image: 'https://images.unsplash.com/photo-1610889556528-9a770e32642f?w=500&h=300&fit=crop' },
  { id: 20, name: 'Iced Caramel Macchiato', price: '8,500', category: 'smoothies', description: 'Cold brew coffee with caramel and milk foam', image: 'https://images.unsplash.com/photo-1517487881594-2787fef5ebf7?w=500&h=300&fit=crop' },
  { id: 21, name: 'Affogato', price: '9,000', category: 'smoothies', description: 'Vanilla ice cream drowned in Zanzibar espresso', image: 'https://images.unsplash.com/photo-1612201142855-bda0a97162d9?w=500&h=300&fit=crop' },
  
  // BREAKFAST
  { id: 22, name: 'Spanish Omelette', price: '5,000', category: 'breakfast', description: 'Fluffy omelette with tomatoes, onions, peppers, and cheese', image: 'https://images.unsplash.com/photo-1612874742237-6526221588e3?w=500&h=300&fit=crop' },
  { id: 23, name: 'Plain Omelette (Mayai)', price: '3,000', category: 'breakfast', description: 'Simple fluffy omelette, cooked to perfection', image: 'https://images.unsplash.com/photo-1608039829572-78524f79c4c7?w=500&h=300&fit=crop' },
  { id: 24, name: 'Andazi Mafuta', price: '500', category: 'breakfast', description: 'Traditional Swahili fried dough, sweet and fluffy', image: 'https://images.unsplash.com/photo-1626788182606-4f1b6d5dc7b6?w=500&h=300&fit=crop' },
  { id: 25, name: 'Andazi Kavu', price: '1,000', category: 'breakfast', description: 'Crispy fried dough triangles, perfect with chai', image: 'https://images.unsplash.com/photo-1626788182606-4f1b6d5dc7b6?w=500&h=300&fit=crop' },
  { id: 26, name: 'Chapati', price: '1,500', category: 'breakfast', description: 'Soft flatbread, perfect with curry or tea', image: 'https://images.unsplash.com/photo-1626764780463-0c3ed8209daf?w=500&h=300&fit=crop' },
  { id: 27, name: 'Boflo (Vitumbua)', price: '1,500', category: 'breakfast', description: 'Sweet rice cakes made with coconut milk', image: 'https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=500&h=300&fit=crop' },
  { id: 28, name: 'Mkate wa Kumimina', price: '2,000', category: 'breakfast', description: 'Zanzibar pizza - thin crepe filled with minced meat and egg', image: 'https://images.unsplash.com/photo-1628191011227-c9d1e3f8e928?w=500&h=300&fit=crop' },
  { id: 29, name: 'Breakfast Combo', price: '8,000', category: 'breakfast', description: 'Omelette, chapati, andazi, and chai tea', image: 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=500&h=300&fit=crop' },
  
  // BURGERS & SANDWICHES
  { id: 30, name: 'Asili Beef Burger', price: '12,000', category: 'burgers-sandwiches', description: 'Juicy beef patty with cheese, lettuce, tomato, onion, and special sauce', image: 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&h=300&fit=crop' },
  { id: 31, name: 'Chicken Burger', price: '11,000', category: 'burgers-sandwiches', description: 'Grilled chicken breast with fresh toppings and garlic aioli', image: 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=500&h=300&fit=crop' },
  { id: 32, name: 'Fish Burger', price: '12,000', category: 'burgers-sandwiches', description: 'Fresh fish fillet with tartar sauce and crisp vegetables', image: 'https://images.unsplash.com/photo-1625869016774-3afbe48eb7c8?w=500&h=300&fit=crop' },
  { id: 33, name: 'Vegetable Burger', price: '10,000', category: 'burgers-sandwiches', description: 'Plant-based patty with avocado and fresh veggies', image: 'https://images.unsplash.com/photo-1520072959219-c595dc870360?w=500&h=300&fit=crop' },
  { id: 34, name: 'Chicken Sandwich', price: '10,000', category: 'burgers-sandwiches', description: 'Grilled chicken with fresh vegetables on toasted bread', image: 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=500&h=300&fit=crop' },
  { id: 35, name: 'Club Sandwich', price: '11,000', category: 'burgers-sandwiches', description: 'Triple-decker with chicken, bacon, egg, lettuce, and tomato', image: 'https://images.unsplash.com/photo-1567234669003-dce7a7a88821?w=500&h=300&fit=crop' },
  { id: 36, name: 'Fish Sandwich', price: '11,000', category: 'burgers-sandwiches', description: 'Flaky fish fillet with tartar sauce and coleslaw', image: 'https://images.unsplash.com/photo-1598182198871-d3f4ab4fd181?w=500&h=300&fit=crop' },
  
  // PIZZA
  { id: 37, name: 'Margherita Pizza', price: '15,000', category: 'pizza', description: 'Classic pizza with fresh mozzarella, tomato sauce, and basil', image: 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=500&h=300&fit=crop' },
  { id: 38, name: 'Vegetable Pizza', price: '18,000', category: 'pizza', description: 'Bell peppers, mushrooms, onions, olives, and mozzarella', image: 'https://images.unsplash.com/photo-1511689660979-10d2b1aada49?w=500&h=300&fit=crop' },
  { id: 39, name: 'Pepperoni Pizza', price: '19,000', category: 'pizza', description: 'Classic pepperoni with mozzarella cheese', image: 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=500&h=300&fit=crop' },
  { id: 40, name: 'Chicken Pizza', price: '20,000', category: 'pizza', description: 'Grilled chicken pieces with onions and mozzarella', image: 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=500&h=300&fit=crop' },
  { id: 41, name: 'BBQ Chicken Pizza', price: '22,000', category: 'pizza', description: 'BBQ sauce, grilled chicken, olives, and green peppers', image: 'https://images.unsplash.com/photo-1571997478779-2adcbbe9ab2f?w=500&h=300&fit=crop' },
  { id: 42, name: 'Beef Pizza', price: '21,000', category: 'pizza', description: 'Seasoned minced beef with onions and mozzarella', image: 'https://images.unsplash.com/photo-1590534247854-e973a3903f94?w=500&h=300&fit=crop' },
  { id: 43, name: 'Seafood Pizza', price: '25,000', category: 'pizza', description: 'Prawns, calamari, and fish with garlic butter', image: 'https://images.unsplash.com/photo-1571407970349-bc81e7e96c47?w=500&h=300&fit=crop' },
  { id: 44, name: 'Asili Special Pizza', price: '23,000', category: 'pizza', description: 'Our signature with pineapple, dates, banana, and cheese - a Zanzibar twist!', image: 'https://images.unsplash.com/photo-1513104890138-7c749659a591?w=500&h=300&fit=crop' },
  { id: 45, name: 'Hawaiian Pizza', price: '19,000', category: 'pizza', description: 'Ham and pineapple with mozzarella', image: 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?w=500&h=300&fit=crop' },
  
  // SOUPS
  { id: 46, name: 'Chicken Soup (Supu ya Kuku)', price: '7,000', category: 'soup', description: 'Comforting chicken broth with vegetables and spices', image: 'https://images.unsplash.com/photo-1581783342648-90be45d49c86?w=500&h=300&fit=crop' },
  { id: 47, name: 'Beef Soup (Supu ya Ng\'ombe)', price: '7,500', category: 'soup', description: 'Hearty beef broth with traditional Swahili spices', image: 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=500&h=300&fit=crop' },
  { id: 48, name: 'Octopus Soup (Supu ya Pweza)', price: '10,000', category: 'soup', description: 'Tender octopus in rich coconut broth', image: 'https://images.unsplash.com/photo-1547592180-85f173990554?w=500&h=300&fit=crop' },
  { id: 49, name: 'Tuna Soup (Supu ya Jodari)', price: '8,000', category: 'soup', description: 'Fresh tuna in flavorful tomato broth', image: 'https://images.unsplash.com/photo-1598511726623-d2e9996892f0?w=500&h=300&fit=crop' },
  { id: 50, name: 'Vegetable Soup (Supu ya Mboga)', price: '6,000', category: 'soup', description: 'Fresh garden vegetables in light broth', image: 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=500&h=300&fit=crop' },
  { id: 51, name: 'Mixed Seafood Soup', price: '15,000', category: 'soup', description: 'Rich medley of prawns, fish, calamari, and octopus', image: 'https://images.unsplash.com/photo-1559847844-d7e97cd00310?w=500&h=300&fit=crop' },
  { id: 52, name: 'Muhogo na Nyama (Cassava Soup)', price: '10,000', category: 'soup', description: 'Traditional cassava soup with beef or chicken', variations: ['Beef', 'Chicken'], image: 'https://images.unsplash.com/photo-1603046891726-36bfd957e0fa?w=500&h=300&fit=crop' },
  { id: 53, name: 'Changu Soup', price: '25,000', category: 'soup', description: 'Premium emperor fish soup, a Zanzibar delicacy', image: 'https://images.unsplash.com/photo-1604329760661-e71dc83f8f26?w=500&h=300&fit=crop' },
  
  // SEAFOOD
  { id: 54, name: 'Grilled Tuna (Jodari)', price: '18,000', category: 'seafood', description: 'Fresh yellowfin tuna grilled with lemon and herbs', image: 'https://images.unsplash.com/photo-1580959375944-0d0b0f21f7d8?w=500&h=300&fit=crop' },
  { id: 55, name: 'Kingfish Grilled/Curry (Nguru)', price: '20,000', category: 'seafood', description: 'Zanzibar kingfish - choose grilled or in coconut curry', variations: ['Grilled', 'Curry'], image: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&h=300&fit=crop' },
  { id: 56, name: 'Octopus Grilled/Curry (Pweza)', price: '22,000', category: 'seafood', description: 'Tender octopus grilled or in spicy Swahili curry', variations: ['Grilled', 'Curry'], image: 'https://images.unsplash.com/photo-1559737558-2f5a555b0be8?w=500&h=300&fit=crop' },
  { id: 57, name: 'Calamari Grilled/Curry (Ngisi)', price: '18,000', category: 'seafood', description: 'Fresh squid prepared your way', variations: ['Grilled', 'Curry'], image: 'https://images.unsplash.com/photo-1604909052743-94e838986d24?w=500&h=300&fit=crop' },
  { id: 58, name: 'Prawns Grilled/Curry (Kamba)', price: '25,000', category: 'seafood', description: 'Jumbo prawns from the Indian Ocean', variations: ['Grilled', 'Curry'], image: 'https://images.unsplash.com/photo-1599084993091-1cb5c0721cc6?w=500&h=300&fit=crop' },
  { id: 59, name: 'Lobster Grilled/Curry (Kamba Kokolimba)', price: '45,000', category: 'seafood', description: 'Fresh lobster cooked to perfection', variations: ['Grilled', 'Curry'], image: 'https://images.unsplash.com/photo-1615274744853-cd8b8460f951?w=500&h=300&fit=crop' },
  { id: 60, name: 'Grilled Changu (Whole Fish)', price: '35,000', category: 'seafood', description: 'Whole emperor fish grilled with Swahili spices', image: 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&h=300&fit=crop' },
  { id: 61, name: 'Mixed Seafood Curry', price: '35,000', category: 'seafood', description: 'Assorted seafood in rich coconut curry sauce', image: 'https://images.unsplash.com/photo-1591255221146-9e8fbb86f03d?w=500&h=300&fit=crop' },
  { id: 62, name: 'Seafood Platter - Half', price: '40,000', category: 'seafood', description: 'Selection of grilled prawns, fish, calamari, and lobster', image: 'https://images.unsplash.com/photo-1559847844-d7e97cd00310?w=500&h=300&fit=crop' },
  { id: 63, name: 'Seafood Platter - Full', price: '65,000', category: 'seafood', description: 'Grand feast of mixed seafood for sharing', image: 'https://images.unsplash.com/photo-1559847844-d7e97cd00310?w=500&h=300&fit=crop' },
  { id: 64, name: 'Fish Fingers', price: '12,000', category: 'seafood', description: 'Crispy fried fish strips with tartar sauce', image: 'https://images.unsplash.com/photo-1579717001958-ef9cc8d8a562?w=500&h=300&fit=crop' },
  
  // MAIN DISHES
  { id: 65, name: 'Chicken Biryani', price: '15,000', category: 'main-dishes', description: 'Fragrant basmati rice with spiced chicken, Zanzibar style', image: 'https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=500&h=300&fit=crop' },
  { id: 66, name: 'Beef Biryani', price: '16,000', category: 'main-dishes', description: 'Tender beef cooked with aromatic rice and spices', image: 'https://images.unsplash.com/photo-1642821373181-696a54913e93?w=500&h=300&fit=crop' },
  { id: 67, name: 'Pilau (Chicken/Beef)', price: '12,000', category: 'main-dishes', description: 'Traditional spiced rice with your choice of meat', variations: ['Chicken', 'Beef'], image: 'https://images.unsplash.com/photo-1596797038530-2c107229654b?w=500&h=300&fit=crop' },
  { id: 68, name: 'Biriyani Rice (Plain)', price: '8,000', category: 'main-dishes', description: 'Saffron-infused basmati rice with spices', image: 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&h=300&fit=crop' },
  { id: 69, name: 'Butter Chicken', price: '16,000', category: 'main-dishes', description: 'Creamy tomato curry with tender chicken pieces', image: 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=500&h=300&fit=crop' },
  { id: 70, name: 'Beef Curry (Mchuzi wa Ng\'ombe)', price: '15,000', category: 'main-dishes', description: 'Slow-cooked beef in traditional Swahili spices', image: 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=500&h=300&fit=crop' },
  { id: 71, name: 'Grilled Chicken (Full/Half)', price: '18,000 / 10,000', category: 'main-dishes', description: 'Marinated chicken grilled over charcoal', variations: ['Full', 'Half'], image: 'https://images.unsplash.com/photo-1598103442097-8b74394b95c6?w=500&h=300&fit=crop' },
  { id: 72, name: 'Manchurian Chicken', price: '16,000', category: 'main-dishes', description: 'Indo-Chinese style chicken in savory sauce', image: 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=500&h=300&fit=crop' },
  { id: 73, name: 'Manchurian Curry', price: '15,000', category: 'main-dishes', description: 'Asian-inspired curry with your choice', variations: ['Vegetable', 'Fish', 'Chicken'], image: 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=500&h=300&fit=crop' },
  { id: 74, name: 'Wali wa Nazi (Coconut Rice)', price: '10,000', category: 'main-dishes', description: 'Rice cooked in coconut milk with spices', image: 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=500&h=300&fit=crop' },
  { id: 75, name: 'Maandazi na Maharage', price: '5,000', category: 'main-dishes', description: 'Traditional coconut doughnuts with spiced beans', image: 'https://images.unsplash.com/photo-1626788182606-4f1b6d5dc7b6?w=500&h=300&fit=crop' },
  { id: 76, name: 'Vegetable Biryani', price: '12,000', category: 'main-dishes', description: 'Mixed vegetables in fragrant spiced rice', image: 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=500&h=300&fit=crop' },
  { id: 77, name: 'Pweza wa Nazi', price: '25,000', category: 'main-dishes', description: 'Octopus cooked in rich coconut sauce', image: 'https://images.unsplash.com/photo-1559737558-2f5a555b0be8?w=500&h=300&fit=crop' },
  
  // SIDES & EXTRAS
  { id: 78, name: 'Ugali', price: '3,000', category: 'sides', description: 'Traditional maize meal, perfect with curry', image: 'https://images.unsplash.com/photo-1604329760661-e71dc83f8f26?w=500&h=300&fit=crop' },
  { id: 79, name: 'White Rice', price: '4,000', category: 'sides', description: 'Steamed basmati rice', image: 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&h=300&fit=crop' },
  { id: 80, name: 'French Fries (Chipsi)', price: '5,000', category: 'sides', description: 'Crispy golden fries with ketchup', image: 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=500&h=300&fit=crop' },
  { id: 81, name: 'Masala Chips', price: '6,000', category: 'sides', description: 'Fries tossed with Swahili spices', image: 'https://images.unsplash.com/photo-1598679253544-2c97992403ea?w=500&h=300&fit=crop' },
  { id: 82, name: 'Kachumbari Salad', price: '4,000', category: 'sides', description: 'Fresh tomato, onion, and cucumber salad', image: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&h=300&fit=crop' },
  { id: 83, name: 'Coconut Chutney', price: '2,500', category: 'sides', description: 'Fresh coconut relish with spices', image: 'https://images.unsplash.com/photo-1596040033229-a0b3b83ded90?w=500&h=300&fit=crop' },
  { id: 84, name: 'Garlic Naan', price: '3,500', category: 'sides', description: 'Oven-baked flatbread with garlic butter', image: 'https://images.unsplash.com/photo-1617343267882-ce20369c2145?w=500&h=300&fit=crop' },
  { id: 85, name: 'Mboga (Vegetable Side)', price: '4,000', category: 'sides', description: 'Sautéed mixed vegetables', image: 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=500&h=300&fit=crop' },
  { id: 86, name: 'Samosas (3 pcs)', price: '3,000', category: 'sides', description: 'Crispy pastry triangles filled with spiced vegetables or meat', variations: ['Vegetable', 'Meat'], image: 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&h=300&fit=crop' },
  { id: 87, name: 'Bhajia (Pakora)', price: '4,000', category: 'sides', description: 'Spiced vegetable fritters in chickpea batter', image: 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=500&h=300&fit=crop' }
])

// Computed properties
const getItemsByCategory = (categoryId) => {
  return menuData.value.filter(item => item.category === categoryId)
}

// Helper methods
const getCategoryDescription = (categoryId) => {
  const category = categories.value.find(cat => cat.id === categoryId)
  return category ? category.description : 'Delicious options'
}

const getActiveCategoryName = () => {
  const category = categories.value.find(cat => cat.id === activeCategory.value)
  return category ? category.name : ''
}

const getActiveCategoryDescription = () => {
  const category = categories.value.find(cat => cat.id === activeCategory.value)
  return category ? category.description : 'Explore our delicious selections'
}

// Methods
const setActiveCategory = (categoryId, index) => {
  if (activeCategory.value === categoryId) return
  
  const previousActiveIndex = categories.value.findIndex(cat => cat.id === activeCategory.value)
  activeCategory.value = categoryId
  
  // Animate category button transition
  animateCategoryTransition(previousActiveIndex, index)
}

const selectItem = (item) => {
  selectedItem.value = item
  nextTick(() => {
    if (modalBackdrop.value && modal.value) {
      animateModalOpen()
    }
  })
}

// Add order functionality
const addToCart = (item, variation = null) => {
  // Create order object
  const order = {
    id: Date.now(),
    item: item,
    variation: variation,
    quantity: 1,
    timestamp: new Date().toISOString()
  }
  
  // For demo purposes, show success message
  alert(`Added "${item.name}" ${variation ? `(${variation})` : ''} to cart!\n\nThis is a demo implementation. In a real app, this would be connected to your ordering system.`)
  
  // Close modal
  selectedItem.value = null
  
  // Log order for debugging
  console.log('Order placed:', order)
}

// Quick order function for direct ordering from menu grid
const quickOrder = (item) => {
  addToCart(item)
}

// Get selected variation from modal radio buttons
const getSelectedVariation = () => {
  if (!selectedItem.value || !selectedItem.value.id) return null
  
  const checkedRadio = document.querySelector(`input[name="variation-${selectedItem.value.id}"]:checked`)
  return checkedRadio ? checkedRadio.value : null
}

const formatPrice = (price) => {
  return price
}

// Handle image loading errors
const handleImageError = (event) => {
  // Fallback to available menu images in order
  const fallbackImages = ['/img/menu-1.jpg', '/img/menu-2.jpg', '/img/menu-3.jpg']
  const currentSrc = event.target.src
  
  // Find next fallback that isn't the current failed source
  const nextFallback = fallbackImages.find(img => !currentSrc.includes(img))
  
  if (nextFallback) {
    event.target.src = nextFallback
  } else {
    // Create a placeholder with text if all images fail
    event.target.style.display = 'none'
    const placeholder = document.createElement('div')
    placeholder.className = 'image-placeholder'
    placeholder.innerHTML = '<i class="fa fa-image"></i><span>Menu Item</span>'
    event.target.parentNode.appendChild(placeholder)
  }
}

// Simplified GSAP Animation Functions
const initializeAnimations = () => {
  // Reset any existing animations
  if (mainTimeline) {
    mainTimeline.kill()
  }
  scrollTriggerInstances.forEach(trigger => trigger.kill())
  scrollTriggerInstances = []

  // Categories panel entrance
  gsap.fromTo(categoriesSidebar.value, {
    x: -50,
    opacity: 0
  }, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out"
  })

  // Category buttons staggered animation
  gsap.fromTo(categoryBtns.value, {
    y: 20,
    opacity: 0
  }, {
    y: 0,
    opacity: 1,
    duration: 0.6,
    stagger: 0.05,
    ease: "power2.out",
    delay: 0.3
  })

  // Content panel entrance
  gsap.fromTo(contentPanel.value, {
    x: 50,
    opacity: 0
  }, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out",
    delay: 0.2
  })

  // Animate menu items
  animateItemsIn()
}

const animateItemsIn = () => {
  if (!itemsGrid.value) return
  
  const items = itemsGrid.value.querySelectorAll('.menu-item')
  
  gsap.fromTo(items, {
    y: 30,
    opacity: 0,
    scale: 0.95
  }, {
    y: 0,
    opacity: 1,
    scale: 1,
    duration: 0.6,
    stagger: 0.08,
    ease: "power2.out"
  })
}

const animateCategoryTransition = (fromIndex, toIndex) => {
  // Simple category button animation
  categoryBtns.value.forEach((btn, index) => {
    if (index === toIndex) {
      gsap.to(btn, {
        scale: 1.02,
        duration: 0.2,
        ease: "power2.out"
      })
    } else {
      gsap.to(btn, {
        scale: 1,
        duration: 0.2,
        ease: "power2.out"
      })
    }
  })

  // Animate new items
  setTimeout(() => {
    animateItemsIn()
  }, 100)
}

const animateModalOpen = () => {
  gsap.fromTo(modalBackdrop.value, {
    opacity: 0
  }, {
    opacity: 1,
    duration: 0.3,
    ease: "power2.out"
  })

  gsap.fromTo(modal.value, {
    scale: 0.9,
    opacity: 0,
    y: 20
  }, {
    scale: 1,
    opacity: 1,
    y: 0,
    duration: 0.4,
    ease: "back.out(1.7)"
  })
}

// Watch for modal close
watch(selectedItem, (newVal, oldVal) => {
  if (oldVal && !newVal && modalBackdrop.value) {
    gsap.to(modal.value, {
      scale: 0.9,
      opacity: 0,
      y: 20,
      duration: 0.3,
      ease: "power2.in"
    })

    gsap.to(modalBackdrop.value, {
      opacity: 0,
      duration: 0.2,
      delay: 0.1,
      ease: "power2.out"
    })
  }
})

onMounted(async () => {
  try {
    loading.value = true
    await menuStore.fetchMenuItems()
    // If we have menu items from the API, we could update menuData.value here
  } catch (error) {
    console.error('Error loading menu items:', error)
  } finally {
    loading.value = false
    
    // Initialize animations after content is loaded
    await nextTick()
    setTimeout(() => {
      initializeAnimations()
    }, 100)
  }
})

// Cleanup on unmount
import { onUnmounted } from 'vue'

onUnmounted(() => {
  if (mainTimeline) {
    mainTimeline.kill()
  }
  scrollTriggerInstances.forEach(trigger => trigger.kill())
  ScrollTrigger.getAll().forEach(trigger => trigger.kill())
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Lora:wght@400;600;700&family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&display=swap');

/* Component Variables - Website Theme Colors */
.menu-container {
  --primary-brown: #916d4d;
  --secondary-brown: #da9f5b;
  --dark-brown: #2c1810;
  --light-cream: #f8f4e6;
  --warm-beige: #ede4d1;
  --soft-gold: #d4af37;
  --text-dark: #2c2c2c;
  --text-light: #6c757d;
  --white: #ffffff;
  --shadow-light: rgba(145, 109, 77, 0.1);
  --shadow-medium: rgba(145, 109, 77, 0.2);
  --shadow-heavy: rgba(0, 0, 0, 0.15);
}

/* Main Container */
.menu-container {
  width: 100%;
  background: linear-gradient(135deg, var(--light-cream) 0%, var(--warm-beige) 100%);
  font-family: 'Inter', sans-serif;
  overflow-x: hidden;
  position: relative;
  padding: 0;
  margin: 0;
}

/* Section Header */
.menu-section-header {
  background: linear-gradient(135deg, var(--light-cream) 0%, var(--warm-beige) 100%);
  padding: 3rem 0 2rem;
  text-align: center;
  position: relative;
}

.menu-section-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 100px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-brown), var(--soft-gold), var(--primary-brown));
  border-radius: 2px;
}

.section-title {
  margin: 0;
}

.section-subtitle {
  color: var(--primary-brown) !important;
  font-weight: 600;
  font-size: 0.9rem;
  margin-bottom: 1rem;
  opacity: 0.9;
}

.section-main-title {
  font-family: 'Playfair Display', serif;
  color: var(--dark-brown);
  font-weight: 700;
  margin: 0;
  text-shadow: 2px 2px 4px rgba(145, 109, 77, 0.1);
}

/* Loading State */
.loading-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 60vh;
  color: var(--primary-brown);
}

.loading-spinner {
  width: 50px;
  height: 50px;
  border: 4px solid var(--shadow-light);
  border-top: 4px solid var(--primary-brown);
  border-radius: 50%;
  animation: menuSpin 1s linear infinite;
  margin-bottom: 1.5rem;
}

.loading-text {
  font-size: 1.1rem;
  text-align: center;
  opacity: 0.8;
  font-weight: 500;
}

/* Main Layout */
.menu-layout {
  display: flex;
  position: relative;
  flex-direction: column;
  background: var(--white);
  box-shadow: none;
  border-radius: 0;
  overflow: hidden;
  margin: 0;
  width: 100%;
  padding: 3rem 0;
}

.menu-layout .container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 15px;
}

/* Categories Top Navigation Bar */
.categories-top-nav {
  background: linear-gradient(135deg, var(--dark-brown) 0%, var(--primary-brown) 100%);
  padding: 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  border-bottom: 3px solid var(--soft-gold);
}

.categories-top-nav .container {
  max-width: 100%;
  padding: 0;
}

.nav-wrapper {
  display: flex;
  overflow-x: auto;
  overflow-y: hidden;
  scrollbar-width: thin;
  scrollbar-color: rgba(218, 159, 91, 0.5) rgba(44, 24, 16, 0.3);
  -webkit-overflow-scrolling: touch;
  padding: 0.5rem 0;
}

/* Modern elegant scrollbar for webkit browsers */
.nav-wrapper::-webkit-scrollbar {
  height: 6px;
}

.nav-wrapper::-webkit-scrollbar-track {
  background: rgba(44, 24, 16, 0.2);
  border-radius: 10px;
  margin: 0 1rem;
}

.nav-wrapper::-webkit-scrollbar-thumb {
  background: linear-gradient(90deg, rgba(218, 159, 91, 0.6), rgba(161, 110, 66, 0.8));
  border-radius: 10px;
  transition: all 0.3s ease;
}

.nav-wrapper::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(90deg, var(--soft-gold), var(--secondary-brown));
  box-shadow: 0 0 6px rgba(218, 159, 91, 0.5);
}

.category-tab {
  flex: 0 0 auto;
  padding: 1rem 2rem;
  background: transparent;
  border: none;
  border-bottom: 3px solid transparent;
  color: rgba(255, 255, 255, 0.7);
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 0.95rem;
  font-weight: 500;
  white-space: nowrap;
  position: relative;
  overflow: hidden;
  outline: none; /* Remove browser focus outline */
}

.category-tab:focus {
  outline: none; /* Ensure no outline on focus */
}

.category-tab::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  width: 0;
  height: 3px;
  background: linear-gradient(90deg, var(--soft-gold), var(--secondary-brown));
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  transform: translateX(-50%);
}

.category-tab:hover {
  color: var(--soft-gold);
  background: rgba(218, 159, 91, 0.1);
}

.category-tab:hover::before {
  width: 80%;
}

.category-tab.active {
  color: var(--soft-gold);
  background: rgba(218, 159, 91, 0.15);
  font-weight: 600;
}

.category-tab.active::before {
  width: 100%;
}

.category-name {
  position: relative;
  z-index: 1;
}

/* Menu Content Area */
.menu-content {
  width: 100%;
  background: var(--white);
  overflow: hidden;
  position: relative;
  display: flex;
  flex-direction: column;
  padding: 2rem 0;
}

.content-header {
  padding: 2rem 0 1.2rem;
  background: linear-gradient(135deg, var(--light-cream), var(--warm-beige));
  border-bottom: 3px solid var(--primary-brown);
  text-align: center;
  z-index: 10;
  box-shadow: 0 3px 20px var(--shadow-light);
  flex-shrink: 0;
}

.header-ornament {
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-brown), var(--secondary-brown), var(--primary-brown));
  margin: 0 auto 1.5rem;
  border-radius: 2px;
  box-shadow: 0 2px 8px var(--shadow-medium);
}

.category-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  color: var(--dark-brown);
  margin: 0 0 0.5rem;
  font-weight: 700;
  text-shadow: 2px 2px 4px var(--shadow-light);
}

.content-header .category-description {
  font-size: 1rem;
  color: var(--primary-brown);
  margin: 0;
  font-weight: 400;
  opacity: 0.9;
}

/* Menu Items Grid */
.menu-items-grid {
  padding: 2rem 0;
  overflow-x: auto;
  overflow-y: hidden;
  flex: 1;
  display: flex;
  gap: 2rem;
  background: var(--white);
  align-items: center;
  justify-content: flex-start;
  width: 100%;
}

.menu-items-grid::-webkit-scrollbar {
  height: 8px;
}

.menu-items-grid::-webkit-scrollbar-track {
  background: var(--light-cream);
  border-radius: 4px;
}

.menu-items-grid::-webkit-scrollbar-thumb {
  background: linear-gradient(90deg, var(--primary-brown), var(--secondary-brown));
  border-radius: 4px;
  transition: background 0.3s ease;
}

.menu-items-grid::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(90deg, var(--secondary-brown), var(--primary-brown));
}

/* Individual Menu Items */
.menu-item {
  background: var(--white);
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 6px 20px var(--shadow-light);
  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  cursor: pointer;
  position: relative;
  border: 2px solid transparent;
  display: flex;
  flex-direction: column;
  width: 280px;
  flex-shrink: 0;
  height: fit-content;
  max-height: calc(100vh - 220px);
}

.menu-item::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(135deg, var(--shadow-light), rgba(218, 159, 91, 0.05));
  opacity: 0;
  transition: opacity 0.3s ease;
  border-radius: 18px;
  z-index: 1;
}

.menu-item:hover::before {
  opacity: 1;
}

.menu-item:hover {
  transform: translateY(-6px) scale(1.01);
  box-shadow: 0 15px 35px var(--shadow-medium);
  border-color: var(--primary-brown);
}

.menu-item.selected {
  border-color: var(--secondary-brown);
  box-shadow: 0 20px 50px var(--shadow-medium);
}

/* Item Image */
.item-image {
  position: relative;
  height: 160px;
  overflow: hidden;
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.menu-item:hover .item-image img {
  transform: scale(1.1);
}

.image-placeholder {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(135deg, var(--light-cream), var(--warm-beige));
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: var(--primary-brown);
  font-size: 0.9rem;
  font-weight: 500;
}

.image-placeholder i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  opacity: 0.6;
}

.price-badge {
  position: absolute;
  top: 10px;
  right: 10px;
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  padding: 0.5rem 1rem;
  border-radius: 25px;
  box-shadow: 0 4px 15px rgba(145, 109, 77, 0.4);
  z-index: 2;
  transition: all 0.3s ease;
  font-weight: 600;
  font-size: 0.85rem;
}

.menu-item:hover .price-badge {
  transform: scale(1.1);
  box-shadow: 0 12px 35px rgba(145, 109, 77, 0.6);
}

.currency {
  font-size: 0.7rem;
  opacity: 0.9;
  margin-right: 0.2rem;
}

.amount {
  font-size: 0.9rem;
  font-weight: 700;
}

/* Item Details */
.item-details {
  padding: 1.2rem;
  position: relative;
  z-index: 2;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.item-name {
  font-family: 'Lora', serif;
  color: var(--dark-brown);
  font-size: 1.1rem;
  font-weight: 600;
  margin: 0 0 0.5rem;
  line-height: 1.3;
}

.item-description {
  color: var(--text-light);
  margin: 0 0 1rem;
  line-height: 1.5;
  font-size: 0.85rem;
}

.item-variations {
  margin: 0.8rem 0 1rem;
  min-height: 1.5rem;
  flex-grow: 1;
}

.variation-tag {
  display: inline-block;
  background: linear-gradient(135deg, var(--light-cream), var(--warm-beige));
  color: var(--primary-brown);
  padding: 0.3rem 0.8rem;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: 600;
  border: 1px solid var(--shadow-light);
  margin: 0.1rem 0.3rem 0.1rem 0;
  transition: all 0.3s ease;
}

.variation-tag:hover {
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  transform: translateY(-2px);
}

.order-btn {
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  border: none;
  padding: 0.7rem 1.5rem;
  border-radius: 25px;
  font-weight: 600;
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  width: 100%;
  justify-content: center;
  margin-top: auto;
}

.order-btn:hover {
  background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown));
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(145, 109, 77, 0.4);
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(44, 24, 16, 0.9);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 3000;
  padding: 2rem;
  backdrop-filter: blur(5px);
}

.modal-content {
  background: var(--white);
  border-radius: 25px;
  max-width: 700px;
  width: 100%;
  max-height: 90vh;
  overflow-y: auto;
  position: relative;
  box-shadow: 0 30px 80px rgba(0, 0, 0, 0.3);
  animation: modalSlideIn 0.3s ease-out;
}

.modal-close {
  position: absolute;
  top: 20px;
  right: 20px;
  background: rgba(145, 109, 77, 0.9);
  color: var(--white);
  border: none;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  cursor: pointer;
  z-index: 10;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
}

.modal-close:hover {
  background: var(--primary-brown);
  transform: rotate(90deg) scale(1.1);
}

.modal-header {
  position: relative;
  height: 300px;
  overflow: hidden;
  border-radius: 25px 25px 0 0;
}

.modal-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.modal-overlay-content {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(44, 24, 16, 0.95) 0%, rgba(44, 24, 16, 0.85) 50%, transparent 100%);
  color: var(--white);
  padding: 2.5rem 2rem;
}

.modal-title {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 700;
  margin: 0 0 1rem;
  color: #ffffff;
  text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.8), 
               0 0 20px rgba(0, 0, 0, 0.6);
  line-height: 1.2;
}

.modal-overlay-content .modal-price {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
}

.modal-overlay-content .currency {
  font-size: 1rem;
  opacity: 0.9;
  text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
}

.modal-overlay-content .amount {
  font-size: 2rem;
  font-weight: 800;
  text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8);
}

.modal-body {
  padding: 2.5rem;
}

.modal-description {
  color: var(--text-light);
  margin-bottom: 2rem;
  font-size: 1.1rem;
  line-height: 1.8;
}

.modal-variations h5 {
  color: var(--dark-brown);
  margin-bottom: 1.5rem;
  font-weight: 600;
  font-family: 'Lora', serif;
  font-size: 1.2rem;
}

.modal-variation-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  margin-bottom: 2rem;
}

.variation-option {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border: 2px solid var(--light-cream);
  border-radius: 15px;
  cursor: pointer;
  transition: all 0.3s ease;
}

.variation-option:hover {
  border-color: var(--primary-brown);
  background: var(--light-cream);
}

.variation-option input[type="radio"] {
  accent-color: var(--primary-brown);
  transform: scale(1.2);
}

.variation-label {
  font-weight: 500;
  color: var(--dark-brown);
  font-size: 1rem;
}

.modal-actions {
  text-align: center;
  padding-top: 2rem;
  border-top: 2px solid var(--light-cream);
}

.btn-order {
  background: linear-gradient(135deg, var(--primary-brown), var(--secondary-brown));
  color: var(--white);
  border: none;
  padding: 1.5rem 4rem;
  border-radius: 50px;
  font-size: 1.1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.4s ease;
  display: inline-flex;
  align-items: center;
  gap: 1rem;
}

.btn-order:hover {
  background: linear-gradient(135deg, var(--secondary-brown), var(--primary-brown));
  transform: translateY(-3px);
  box-shadow: 0 15px 40px rgba(145, 109, 77, 0.4);
}

/* Animations */
@keyframes menuSpin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

@keyframes modalSlideIn {
  0% {
    opacity: 0;
    transform: translateY(-50px) scale(0.9);
  }
  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

/* Responsive Design */
@media (max-width: 1200px) {
  .menu-item {
    width: 260px;
  }
  
  .menu-items-grid {
    padding: 1.5rem 0;
    gap: 1.5rem;
  }
  
  .menu-layout .container {
    padding: 0 20px;
  }
  
  .content-header {
    padding: 1.5rem 0;
  }
  
  .category-title {
    font-size: 1.8rem;
  }

  .category-tab {
    padding: 0.875rem 1.5rem;
    font-size: 0.875rem;
  }
}

@media (max-width: 992px) {
  .menu-layout {
    flex-direction: column;
    padding: 2rem 0;
  }
  
  .menu-layout .container {
    padding: 0 15px;
  }
  
  .category-tab {
    padding: 0.75rem 1.25rem;
    font-size: 0.85rem;
  }
  
  .menu-items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.2rem;
    padding: 2rem 0;
    overflow: visible;
    padding: 1.5rem;
  }
  
  .menu-item {
    width: 100%;
    max-height: none;
  }
}

@media (max-width: 768px) {
  .sidebar-header {
    padding: 1.5rem 1rem;
  }
  
  .cafe-logo {
    width: 50px;
    height: 50px;
  }
  
  .sidebar-title {
    font-size: 1.3rem;
  }
  
  .categories-nav {
    grid-template-columns: 1fr;
    padding: 1rem;
  }
  
  .menu-items-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    padding: 1.5rem 0;
  }
  
  .menu-layout .container {
    padding: 0 12px;
  }
  
  .content-header {
    padding: 1.5rem 0;
  }
  
  .category-title {
    font-size: 1.6rem;
  }
  
  .modal-content {
    margin: 1rem;
    max-width: calc(100vw - 2rem);
  }
  
  .modal-header {
    height: 250px;
  }
  
  .modal-body {
    padding: 1.5rem;
  }
}

@media (max-width: 480px) {
  .item-details {
    padding: 1rem;
  }
  
  .item-name {
    font-size: 1rem;
  }
  
  .menu-items-grid {
    grid-template-columns: 1fr;
    gap: 1rem;
  }
  
  .modal-overlay-content {
    padding: 1.5rem;
  }
  
  .modal-title {
    font-size: 1.5rem;
  }
  
  .btn-order {
    padding: 1.2rem 2rem;
    font-size: 1rem;
  }
}
</style>