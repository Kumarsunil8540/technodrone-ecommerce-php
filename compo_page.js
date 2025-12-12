document.addEventListener('DOMContentLoaded', function() {
    // 1. आवश्यक DOM Elements को पकड़ना
    const componentSection = document.getElementById('componentSection');
    // PHP से लोड हुए सारे कार्ड्स को एक बार में Array में ले लो
    const allCards = componentSection ? Array.from(componentSection.querySelectorAll('.component-card')) : [];
    
    // फ़िल्टर और सॉर्ट कंट्रोल
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('searchInput');
    const sortSelect = document.getElementById('sortSelect');
    const priceSlider = document.querySelector('.price-slider');
    const priceDisplay = document.querySelector('.price-range-display');
    
    // फ़िल्टर की वर्तमान स्थिति (State)
    let currentFilter = 'all';
    let currentSearchTerm = '';
    let currentSort = sortSelect ? sortSelect.value : 'newest';
    let currentMaxPrice = priceSlider ? parseFloat(priceSlider.value) : 10000;
    
    if (allCards.length === 0 && !document.querySelector('.no-results-msg')) {
        console.warn('No component cards found to filter.');
        return;
    }

    // ==========================================================
    // 2. MAIN FILTER AND SORT LOGIC
    // ==========================================================
    function applyFiltersAndSort() {
        let cardsToDisplay = allCards.slice(); // Original Array की कॉपी

        // 2a. FILTERING (Category & Search)
        cardsToDisplay = cardsToDisplay.filter(card => {
            const category = card.getAttribute('data-category');
            const price = parseFloat(card.getAttribute('data-price')) || 0;
            const name = card.querySelector('.component-name')?.textContent.toLowerCase() || '';
            const searchTerm = currentSearchTerm.toLowerCase().trim();

            // Category Filter Check
            const passesCategory = currentFilter === 'all' || category === currentFilter;

            // Search Filter Check
            const passesSearch = !searchTerm || name.includes(searchTerm);

            // Price Range Check
            const passesPrice = price <= currentMaxPrice;

            return passesCategory && passesSearch && passesPrice;
        });

        // 2b. SORTING
        const sortValue = currentSort;
        cardsToDisplay.sort((a, b) => {
            const priceA = parseFloat(a.getAttribute('data-price')) || 0;
            const priceB = parseFloat(b.getAttribute('data-price')) || 0;

            if (sortValue === 'price-asc') {
                return priceA - priceB;
            } else if (sortValue === 'price-desc') {
                return priceB - priceA;
            } 
            // NOTE: 'newest' PHP से already सॉर्टेड है। 'rating' के लिए आपको data-rating attribute डालना होगा।
            return 0; // Default sort order (PHP's DESC)
        });
        
        // 2c. DOM UPDATE (Rendering)
        
        // पहले सारे कार्ड्स को DOM से हटाओ (performance के लिए 'display: none' से बेहतर)
        componentSection.innerHTML = '';
        
        // No Results मैसेज को हटाओ अगर पहले से मौजूद है
        const existingNoResults = document.querySelector('.no-results-msg');
        if (existingNoResults) existingNoResults.remove();


        if (cardsToDisplay.length > 0) {
            // फ़िल्टर किए गए और सॉर्ट किए गए कार्ड्स को DOM में वापस जोड़ो
            cardsToDisplay.forEach(card => {
                componentSection.appendChild(card);
                card.style.display = 'article'; // सुनिश्चित करें कि वे visible हैं
            });
        } else {
             // 'No results' message दिखाओ
             const msg = document.createElement('p');
             msg.className = 'no-results-msg';
             msg.textContent = 'Sorry, no components match your current selection or search.';
             componentSection.appendChild(msg);
        }
    }


    // ==========================================================
    // 3. EVENT LISTENERS
    // ==========================================================
    
    // 3a. CATEGORY FILTER (Sidebar Buttons)
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Active state अपडेट
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // State अपडेट
            currentFilter = this.getAttribute('data-category');
            applyFiltersAndSort();
        });
    });

    // 3b. SEARCH INPUT 
    // Throttling search input to avoid lag
    let searchTimeout;
    searchInput?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentSearchTerm = this.value;
            applyFiltersAndSort();
        }, 300); // 300ms delay
    });

    // 3c. SORT SELECT
    sortSelect?.addEventListener('change', function() {
        currentSort = this.value;
        applyFiltersAndSort();
    });

    // 3d. PRICE RANGE SLIDER
    priceSlider?.addEventListener('input', function() {
        // Price Display Update
        const val = parseFloat(this.value);
        priceDisplay.textContent = `₹100 - ₹${val.toLocaleString('en-IN')}`;
        
        // State Update and Filter Application (Trigger filter instantly for better UX)
        currentMaxPrice = val;
        applyFiltersAndSort(); 
    });
    
    // Initial Price Display (Page Load)
    if (priceSlider && priceDisplay) {
        priceDisplay.textContent = `₹100 - ₹${parseFloat(priceSlider.value).toLocaleString('en-IN')}`;
    }

});