<?php
// index.php - Premium Landing Page
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/lang.php';

// Fetch stats for the landing page
$resident_count = $pdo->query("SELECT COUNT(*) FROM individuals")->fetchColumn();
$house_count = $pdo->query("SELECT COUNT(*) FROM houses")->fetchColumn();
$family_count = $pdo->query("SELECT COUNT(*) FROM families")->fetchColumn();

// Default values if empty
$resident_count = $resident_count ?: 1250;
$house_count = $house_count ?: 450;
$family_count = $family_count ?: 400;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | Hirmata Mentina Kebele Admin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@500;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        admin: {
                            primary: '#4f46e5',    // Indigo
                            secondary: '#0ea5e9',  // Sky
                            dark: '#0f172a',       // Slate 900
                            card: '#1e293b'        // Slate 800
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.9) 0%, rgba(30, 58, 138, 0.8) 100%);
        }
    </style>
</head>
<body class="bg-admin-dark text-slate-200 font-sans selection:bg-admin-secondary selection:text-white">

    <!-- Header / Navbar -->
    <nav class="fixed w-full z-50 glass py-4 px-6 md:px-12 flex justify-between items-center">
        <div class="flex items-center gap-5">
            <div class="flex items-center gap-4 border-r border-white/10 pr-5">
                <img src="assets/img/ethiopia_flag.png" alt="Ethiopia" class="w-8 h-5 rounded-sm shadow-md">
                <img src="assets/img/oromia_flag.png" alt="Oromia" class="w-8 h-5 rounded-sm shadow-md">
            </div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-admin-secondary rounded-lg flex items-center justify-center">
                    <i class="fas fa-landmark text-white text-xl"></i>
                </div>
                <div class="hidden sm:block">
                    <span class="block font-display font-bold text-xl tracking-tight text-white leading-tight">HIRMATA <span class="text-admin-secondary">MENTINA</span></span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Administrative Portal</span>
                </div>
            </div>
        </div>
        <div class="hidden md:flex gap-8 font-medium">
            <a href="#" class="hover:text-admin-secondary transition-colors"><?php echo __('home'); ?></a>
            <a href="#services" class="hover:text-admin-secondary transition-colors"><?php echo __('services'); ?></a>
            <a href="stats.php" class="hover:text-admin-secondary transition-colors"><?php echo __('stats'); ?></a>
            <a href="#about" class="hover:text-admin-secondary transition-colors"><?php echo __('about'); ?></a>
        </div>
        
        <div class="flex items-center gap-6">
            <!-- Language Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-2 text-white hover:text-admin-secondary transition-colors font-medium">
                    <i class="fas fa-globe"></i> <?php echo strtoupper($current_lang); ?>
                </button>
                <div class="absolute right-0 top-full mt-2 w-40 glass rounded-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[100] p-1">
                    <a href="?lang=en" class="block px-4 py-2 hover:bg-white/10 rounded-lg text-sm transition-colors">English</a>
                    <a href="?lang=om" class="block px-4 py-2 hover:bg-white/10 rounded-lg text-sm transition-colors">Afaan Oromoo</a>
                    <a href="?lang=am" class="block px-4 py-2 hover:bg-white/10 rounded-lg text-sm transition-colors">አማርኛ</a>
                </div>
            </div>

            <a href="auth/login.php" class="bg-admin-secondary hover:bg-admin-secondary/80 text-white px-6 py-2 rounded-full font-semibold transition-all shadow-lg shadow-admin-secondary/20">
                <?php echo __('staff_portal'); ?> <i class="fas fa-arrow-right ml-2 text-sm"></i>
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0 z-0">
            <img src="assets/img/jimma_hero.png" alt="Jimma Aerial View" class="w-full h-full object-cover">
            <div class="absolute inset-0 hero-gradient"></div>
        </div>

        <div class="container mx-auto px-6 relative z-10 grid md:grid-cols-2 gap-12 items-center">
            <div class="space-y-8" data-aos="fade-right">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-admin-secondary/20 border border-admin-secondary/30 text-admin-secondary text-sm font-semibold uppercase tracking-wider">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-admin-secondary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-admin-secondary"></span>
                    </span>
                    Digital Administration Portal
                </div>
                <h1 class="font-display text-5xl md:text-7xl font-bold text-white leading-tight">
                    Powering <span class="text-transparent bg-clip-text bg-gradient-to-r from-admin-secondary to-blue-400">Community</span> Through Data.
                </h1>
                <p class="text-slate-400 text-lg md:text-xl max-w-xl leading-relaxed">
                    Welcome to the official administrative portal of Hirmata Mentina Kebele. We are dedicated to providing seamless, transparent, and efficient digital services for all our residents.
                </p>
                <div class="flex gap-4">
                    <a href="#services" class="bg-white text-admin-dark px-8 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-slate-200 transition-all">
                        Our Services <i class="fas fa-chevron-down text-sm"></i>
                    </a>
                    <a href="auth/login.php" class="glass text-white px-8 py-3 rounded-xl font-bold flex items-center gap-2 hover:bg-white/10 transition-all border border-white/20">
                        Admin Login
                    </a>
                </div>
            </div>
            
            <!-- Hero Stats / Info Card -->
            <div class="hidden md:block" data-aos="fade-left" data-aos-delay="200">
                <div class="glass p-8 rounded-3xl space-y-6">
                    <div class="flex items-center gap-4 border-b border-white/10 pb-4">
                        <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center text-green-500 text-xl">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-white font-bold">System Online</p>
                            <p class="text-xs text-slate-400">Uptime: 99.9% ── v2.1.0</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <p class="text-3xl font-display font-bold text-white"><?php echo number_format($resident_count); ?></p>
                            <p class="text-xs text-slate-400 uppercase tracking-widest mt-1"><?php echo __('residents'); ?></p>
                        </div>
                        <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                            <p class="text-3xl font-display font-bold text-white"><?php echo number_format($house_count); ?></p>
                            <p class="text-xs text-slate-400 uppercase tracking-widest mt-1"><?php echo __('houses'); ?></p>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-admin-primary to-admin-secondary p-6 rounded-2xl">
                        <p class="font-semibold text-white mb-2">Notice Board</p>
                        <p class="text-sm text-blue-100/80 leading-snug">New digital ID registration is now open for residents of Zone 4 & 5. Please prepare your supporting documents.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-24 bg-slate-900/50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto mb-16 space-y-4">
                <h2 class="text-admin-secondary font-bold uppercase tracking-widest text-sm">Our Core Services</h2>
                <p class="text-4xl font-display font-bold text-white">Digital Government for Everyone</p>
                <div class="h-1 w-20 bg-admin-secondary mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="p-8 rounded-2xl bg-admin-dark border border-white/5 hover:border-admin-secondary/50 transition-all group shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 rounded-2xl bg-admin-secondary/10 flex items-center justify-center text-admin-secondary text-2xl mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-id-card"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Resident ID Services</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Process your residency identifiers with modern security and faster turnaround times.</p>
                    <a href="#" class="text-admin-secondary font-bold text-sm flex items-center gap-2 group-hover:gap-4 transition-all">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Service 2 -->
                <div class="p-8 rounded-2xl bg-admin-dark border border-white/5 hover:border-admin-secondary/50 transition-all group shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 rounded-2xl bg-admin-secondary/10 flex items-center justify-center text-admin-secondary text-2xl mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Civil Registration</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Official registration for vital events including births, marriages, and other legal status changes.</p>
                    <a href="#" class="text-admin-secondary font-bold text-sm flex items-center gap-2 group-hover:gap-4 transition-all">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>

                <!-- Service 3 -->
                <div class="p-8 rounded-2xl bg-admin-dark border border-white/5 hover:border-admin-secondary/50 transition-all group shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 rounded-2xl bg-admin-secondary/10 flex items-center justify-center text-admin-secondary text-2xl mb-6 group-hover:scale-110 transition-transform">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Clearance Issuance</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Get official administrative clearances for employment, travel, or legal purposes within 24 hours.</p>
                    <a href="#" class="text-admin-secondary font-bold text-sm flex items-center gap-2 group-hover:gap-4 transition-all">
                        Learn More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-24 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-16 items-center">
                <div class="relative" data-aos="zoom-in">
                    <div class="absolute -top-8 -left-8 w-32 h-32 bg-admin-secondary/10 rounded-full blur-3xl"></div>
                    <img src="assets/img/jimma_about.png" alt="Jimma Town View" class="rounded-3xl shadow-2xl relative z-10 border border-white/10 hover:scale-[1.02] transition-transform duration-500">
                </div>
                <div class="space-y-6" data-aos="fade-left">
                    <h2 class="text-admin-secondary font-bold uppercase tracking-widest text-sm">Message from the Office</h2>
                    <h3 class="text-4xl font-display font-bold text-white leading-tight">Leading Hirmata Mentina Into a <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-blue-600">Digital Future.</span></h3>
                    <p class="text-slate-400 leading-relaxed text-lg">
                        "Our goal is to eliminate bureaucracy and bring administrative services directly to the palms of our residents. By digitizing our records and processes, we ensure that every citizen of Hirmata Mentina is served with dignity and speed."
                    </p>
                    <div class="flex items-center gap-4 pt-4">
                        <div class="w-12 h-12 rounded-full border border-admin-secondary flex items-center justify-center text-admin-secondary">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="italic text-slate-500">Empowering residents through innovation and transparency.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-admin-dark border-t border-white/5 pt-16 pb-8">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-12 mb-12">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-admin-secondary rounded flex items-center justify-center">
                            <i class="fas fa-landmark text-white"></i>
                        </div>
                        <span class="font-display font-bold text-lg text-white">HIRMATA MENTINA</span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Leading the digital transformation of local governance in Jimma, Ethiopia. Dedicated to transparency and resident satisfaction.
                    </p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Quick Links</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
                        <li><a href="#" class="hover:text-admin-secondary transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="hover:text-admin-secondary transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="hover:text-admin-secondary transition-colors">Official Documents</a></li>
                        <li><a href="#" class="hover:text-admin-secondary transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Office Hours</h4>
                    <ul class="space-y-4 text-slate-500 text-sm">
                        <li>Mon - Fri: 2:30 AM - 11:30 PM</li>
                        <li>Saturday: 3:30 AM - 10:30 PM</li>
                        <li>Sunday: Closed</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 uppercase tracking-widest text-xs">Contact Us</h4>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        <i class="fas fa-map-marker-alt text-admin-secondary mr-2"></i> Jimma, Oromia<br>
                        <i class="fas fa-phone text-admin-secondary mr-2 text-xs"></i> +251 9111111<br>
                        <i class="fas fa-envelope text-admin-secondary mr-2 text-xs"></i> info@hirmata.gov.et
                    </p>
                </div>
            </div>
            <div class="border-t border-white/5 pt-8 text-center text-slate-600 text-xs mt-8">
                &copy; <?php echo date('Y'); ?> Jimma Zone ICT Department | Empowering Jimma Digitally.
            </div>
        </div>
    </footer>

    <!-- AOS Script -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });
    </script>
</body>
</html>
