<?php /*  Template Name: The Quintessence  */ ?>
<?php get_header(); ?>

<div class="quintessence-container" style="padding-top: 80px;">
    <!-- Hero Section -->
    <section class="quintessence-hero">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">The Quintessence</h1>
                <p class="hero-subtitle festival-subtitle">FIVE elements, ONE festival experience</p>
                <div class="hero-description">
                    <p class="codru-tagline">In our story, CODRU is the 5th element.</p>
                    <p>CODRU is The Quintessence of Life in it's purest form.</p>
                    <p>We believe music has a supernatural power of healing, bringing together people from all over the world. For the last 5 years, CODRU has been our well-being oasis, our shelter, our sanctuary to be ourselves.</p>
                    <p>Let's celebrate all the states of being we've passed through music this last 5 years.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Element Sections -->
    <section class="element-details-section">
        <div class="container">
            <!-- Earth Details -->
            <div class="element-detail earth-detail" id="earth-detail">
                <div class="row align-items-start">
                    <div class="col-md-6 pe-md-5">
                        <h2>Earth</h2>
                        <h3>Grounding Our Festival</h3>
                        <p>All components of the area will be related to the theme, starting with aesthetics, experiences, brands culture, brand activations.</p>
                        <ul>
                            <li>We will reproduce the typology of the element through visual and physical sensations, colors, happenings and immersive experiences.</li>
                            <li>Each partner will choose the area they want to be part of.</li>
                        </ul>
                    </div>
                    <div class="col-md-6 ps-md-5">
                        <div class="element-image earth-image" style="color: #00ff88;">
                            <img src="/wp-content/themes/Divi-child/images/elements/earth-element.jpg" alt="Earth Element" class="element-image">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fire Details -->
            <div class="element-detail fire-detail" id="fire-detail">
                <div class="row align-items-start">
                    <div class="col-md-6 order-lg-2 ps-md-5">
                        <h2>Fire</h2>
                        <h3>Igniting Passion</h3>
                        <p>All components of the area will be related to the theme, starting with aesthetics, experiences, brands culture, brand activations.</p>
                        <ul>
                            <li>We will reproduce the typology of the element through visual and physical sensations, colors, happenings and immersive experiences.</li>
                            <li>Each partner will choose the area they want to be part of.</li>
                        </ul>
                    </div>
                    <div class="col-md-6 order-lg-1 pe-lg-5">
                        <div class="element-image fire-image" style="color: #E16420;">
                            <img src="/wp-content/themes/Divi-child/images/elements/fire-element.jpg" alt="Fire Element" class="element-image">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Air Details -->
            <div class="element-detail air-detail" id="air-detail">
                <div class="row align-items-start">
                    <div class="col-md-6 pe-md-5">
                        <h2>Air</h2>
                        <h3>Freedom of Expression</h3>
                        <p>All components of the area will be related to the theme, starting with aesthetics, experiences, brands culture, brand activations.</p>
                        <ul>
                            <li>We will reproduce the typology of the element through visual and physical sensations, colors, happenings and immersive experiences.</li>
                            <li>Each partner will choose the area they want to be part of.</li>
                        </ul>
                    </div>
                    <div class="col-md-6 ps-md-5">
                        <div class="element-image air-image" style="color: #A701D1;">
                            <img src="/wp-content/themes/Divi-child/images/elements/air-element.jpg" alt="Air Element" class="element-image">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Water Details -->
            <div class="element-detail water-detail" id="water-detail">
                <div class="row align-items-start">
                    <div class="col-md-6 order-lg-2 ps-md-5">
                        <h2>Water</h2>
                        <h3>Flowing Connections</h3>
                        <p>All components of the area will be related to the theme, starting with aesthetics, experiences, brands culture, brand activations.</p>
                        <ul>
                            <li>We will reproduce the typology of the element through visual and physical sensations, colors, happenings and immersive experiences.</li>
                            <li>Each partner will choose the area they want to be part of.</li>
                        </ul>
                    </div>
                    <div class="col-md-6 order-lg-1 pe-md-5">
                        <div class="element-image water-image" style="color: #00ccff;">
                            <img src="/wp-content/themes/Divi-child/images/elements/water-element.jpg" alt="Water Element" class="element-image">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="quintessence-cta">
        <div class="container text-center">
            <h2>Experience The Quintessence</h2>
            <p>Join us at CODRU Festival 2025 and experience how these five elements come together to create something truly magical.</p>
            <a href="<?php echo get_field('ticket_button_url', 'options'); ?>" class="codru-general-button">
                <?php echo get_field('ticket_button_text', 'options'); ?>
            </a>
        </div>
    </section>
</div>

<style>
.quintessence-container {
    background: var(--main-color);
    color: white;
}

.quintessence-container .container {
    width: 95%;
}

/* Hero Section */
.quintessence-hero {
    padding: 100px 0;
    /* background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%); */
    position: relative;
    overflow: hidden;
}

.quintessence-hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.05)"/></svg>') repeat;
    animation: float 20s infinite linear;
}

@keyframes float {
    0% { transform: translateY(0px); }
    100% { transform: translateY(-100px); }
}

.hero-title {
    font-size: 5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    background: linear-gradient(45deg, #00ff88, #00ccff, #E16420, #A701D1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
}

.hero-subtitle {
    font-size: 2rem;
    margin-bottom: 1rem;
    font-weight: 700;
    color: #fff;
}

.festival-subtitle {
    background: linear-gradient(45deg, #00ff88, #00ccff, #E16420, #A701D1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 0 20px rgba(0, 255, 136, 0.3);
}

.hero-description {
    font-size: 1.2rem;
    font-weight: 400;
    max-width: 800px;
    margin: 0 auto;
    color: #fff;
    line-height: 1.8;
}

.hero-description p {
    margin-bottom: 0;
    color: #fff;
    font-weight: 500;
}

.codru-tagline {
    font-size: 2.5rem;
    font-weight: 700 !important;
    margin-bottom: 1rem !important;
    padding-bottom: 0;
    background: linear-gradient(45deg, #00ff88, #00ccff, #E16420, #A701D1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    text-shadow: 0 0 30px rgba(0, 255, 136, 0.3);
}

.hero-description p:last-child {
    margin-bottom: 0;
}

/* Elements Grid */
.elements-grid-section {
    padding: 80px 0;
    background: linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 100%);
}

.elements-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
    margin-top: 50px;
}

.element-card {
    background: rgba(255, 255, 255, 0.03);
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    transition: all 0.4s ease;
    cursor: pointer;
    border: 2px solid transparent;
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
}

.element-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(255,255,255,0.05), transparent);
    transform: translateX(-100%);
    transition: transform 0.8s ease;
}

.element-card:hover::before {
    transform: translateX(100%);
}

.element-card:hover {
    transform: translateY(-15px) scale(1.02);
    border-color: currentColor;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

.element-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.element-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: all 0.4s ease;
    filter: drop-shadow(0 0 20px currentColor);
}

@media (max-width: 768px) {
    .row div.element-image {
        margin-top: 50px;
    }
}

.element-card:hover .element-image {
    transform: scale(1.1);
    filter: drop-shadow(0 0 30px currentColor);
}

.element-title {
    font-size: 2.2rem;
    margin-bottom: 15px;
    font-weight: 700;
    text-shadow: 0 0 20px currentColor;
}

.element-description {
    color: #fff;
    margin-bottom: 20px;
    font-size: 1.1rem;
}

.element-details {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease;
}

.element-card.active .element-details {
    max-height: 200px;
}

/* Element Colors - Extracted from your graphics */
.earth-element {
    color: #00ff88;
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
}

.earth-element:hover {
    background: linear-gradient(135deg, rgba(0, 255, 136, 0.2) 0%, rgba(0, 0, 0, 0.4) 100%);
}

.fire-element {
    color: #ff6b6b;
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
}

.fire-element:hover {
    background: linear-gradient(135deg, rgba(255, 107, 107, 0.2) 0%, rgba(0, 0, 0, 0.4) 100%);
}

.air-element {
    color: #00ccff;
    background: linear-gradient(135deg, rgba(0, 204, 255, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
}

.air-element:hover {
    background: linear-gradient(135deg, rgba(0, 204, 255, 0.2) 0%, rgba(0, 0, 0, 0.4) 100%);
}

.water-element {
    color: #4682b4;
    background: linear-gradient(135deg, rgba(70, 130, 180, 0.1) 0%, rgba(0, 0, 0, 0.3) 100%);
}

.water-element:hover {
    background: linear-gradient(135deg, rgba(70, 130, 180, 0.2) 0%, rgba(0, 0, 0, 0.4) 100%);
}

/* Detailed Sections */
.element-details-section {
    /* padding: 80px 0; */
    /* background: linear-gradient(180deg, #1a1a1a 0%, #0a0a0a 100%); */
}

.element-detail {
    padding: 80px 0;
    /* border-bottom: 1px solid rgba(255, 255, 255, 0.05); */
}

@media (max-width: 768px) {
    .element-detail {
        padding-top: 0;
        padding-bottom: 50px;
    }
}

.element-detail:last-child {
    border-bottom: none;
}

.element-detail h2 {
    font-size: 3.5rem;
    margin-bottom: 15px;
    font-weight: 700;
    text-shadow: 0 0 30px currentColor;
}

.element-detail h3 {
    font-size: 2rem;
    margin-bottom: 25px;
    color: #fff;
}

.element-detail p {
    font-size: 1.2rem;
    font-weight: 500;
    line-height: 1.8;
    margin-bottom: 0;
    padding-bottom: 0;
    color: #fff;
}

.element-detail ul {
    list-style: none;
    padding: 0;
}

.element-detail li {
    padding: 12px 0;
    position: relative;
    padding-left: 35px;
    font-size: 1.2rem;
    color: #fff;
}

.element-detail li::before {
    content: '✦';
    position: absolute;
    left: 0;
    color: currentColor;
    font-size: 1.2rem;
    text-shadow: 0 0 10px currentColor;
}

.element-image {
    /* height: 350px; */
    /* background: rgba(255, 255, 255, 0.05); */
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
}

/* Element-specific detail colors */
.earth-detail h2, .earth-detail li::before {
    color: #00ff88;
}

.fire-detail h2, .fire-detail li::before {
    color: #E16420;
}

.air-detail h2, .air-detail li::before {
    color: #A701D1;
}

.water-detail h2, .water-detail li::before {
    color: #00ccff;
}

/* CTA Section */
.quintessence-cta {
    padding: 100px 0;
    /* background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #0a0a0a 100%); */
    position: relative;
    overflow: hidden;
}

.quintessence-cta::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 50% 50%, rgba(0, 255, 136, 0.1) 0%, transparent 70%);
    animation: pulse 4s infinite ease-in-out;
}

@keyframes pulse {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}

.quintessence-cta h2 {
    font-size: 3rem;
    margin-bottom: 25px;
    background: linear-gradient(45deg, #00ff88, #00ccff, #ff6b6b);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.quintessence-cta p {
    font-size: 1.3rem;
    margin-bottom: 40px;
    color: #fff;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

/* Responsive */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.5rem;
    }
    
    .codru-tagline {
        font-size: 1.8rem;
        line-height: 1.2;
        padding-bottom: 15px;
    }
    
    .festival-subtitle {
        font-size: 1.5rem;
    }
    
    .elements-grid {
        grid-template-columns: 1fr;
        gap: 30px;
    }
    
    .element-detail h2 {
        font-size: 2.5rem;
    }
    
    .element-icon {
        width: 100px;
        height: 100px;
    }
    
    .quintessence-cta h2 {
        font-size: 2.2rem;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Element card interactions
    $('.element-card').on('click', function() {
        const element = $(this).data('element');
        
        // Toggle active state
        $('.element-card').removeClass('active');
        $(this).addClass('active');
        
        // Scroll to detail section
        $('html, body').animate({
            scrollTop: $('#' + element + '-detail').offset().top - 100
        }, 800);
    });
    
    // Smooth scroll for navigation
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 800);
        }
    });
});
</script>

<?php get_footer(); ?>
