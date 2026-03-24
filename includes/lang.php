<?php
// includes/lang.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if a language change has been requested
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Default to English if no language is set
$current_lang = $_SESSION['lang'] ?? 'en';

$translations = [
    'en' => [
        'welcome' => 'Welcome',
        'dashboard' => 'Dashboard',
        'residents' => 'Residents',
        'houses' => 'Houses',
        'families' => 'Families',
        'id_cards' => 'ID Cards',
        'logout' => 'Logout',
        'stats' => 'Statistics',
        'home' => 'Home',
        'services' => 'Services',
        'about' => 'About',
        'staff_portal' => 'Staff Portal',
        'reg_resident' => 'Register Resident',
        'total_residents' => 'Total Residents',
        'total_houses' => 'Total Houses',
        'total_families' => 'Total Families',
        'ids_issued' => 'IDs Issued',
        'language' => 'Language',
        'search' => 'Search',
        'save' => 'Save Changes',
        'back' => 'Back',
        'staff_mgmt' => 'Staff Management',
        'administrator' => 'Administrator',
        'manager' => 'Manager',
        'secretary' => 'Secretary',
        'security_committee' => 'Security Committee',
        'data_clerk' => 'Data Clerk',
        'vital_records' => 'Vital Records',
        'birth_cert' => 'Birth Certificate',
        'death_cert' => 'Death Certificate',
        'mark_deceased' => 'Report Death',
        'is_deceased' => 'Status: Deceased'
    ],
    'om' => [ // Afaan Oromoo
        'welcome' => 'Baga nagaan dhuftan',
        'dashboard' => 'Daashboordii',
        'residents' => 'Jiraattota',
        'houses' => 'Manneen',
        'families' => 'Maatiiwwan',
        'id_cards' => 'Waraqaa Eenyummaa',
        'logout' => 'Ba’uu',
        'stats' => 'Istaatistiksii',
        'home' => 'Mana',
        'services' => 'Tajaajila',
        'about' => 'Waa’ee',
        'staff_portal' => 'Seensa Hojjettootaa',
        'reg_resident' => 'Jiraataa Galmeessu',
        'total_residents' => 'Waliigala Jiraattotaa',
        'total_houses' => 'Waliigala Manneenii',
        'total_families' => 'Waliigala Maatiiwwanii',
        'ids_issued' => 'W.E Keenname',
        'language' => 'Afaan',
        'search' => 'Barbaaduu',
        'save' => 'Ol-kaa’uu',
        'back' => 'Duubatti',
        'staff_mgmt' => 'Hoggansa Hojjettootaa',
        'administrator' => 'Bulchiinsa',
        'manager' => 'Maanajara',
        'secretary' => 'Barreessaa',
        'security_committee' => 'Koree Nageenyaa',
        'data_clerk' => 'Barreessaa Deetaa',
        'vital_records' => 'Galmee Jireenyaa',
        'birth_cert' => 'Waraqaa Dhalootaa',
        'death_cert' => 'Waraqaa Du’aa',
        'mark_deceased' => 'Du’a Galmeessu',
        'is_deceased' => 'Haala: Kan Boqotan'
    ],
    'am' => [ // Amharic
        'welcome' => 'እንኳን ደህና መጡ',
        'dashboard' => 'ዳሽቦርድ',
        'residents' => 'ነዋሪዎች',
        'houses' => 'ቤቶች',
        'families' => 'ቤተሰቦች',
        'id_cards' => 'መታወቂያ ካርዶች',
        'logout' => 'ውጣ',
        'stats' => 'ስታቲስቲክስ',
        'home' => 'መነሻ',
        'services' => 'አገልግሎቶች',
        'about' => 'ስለ እኛ',
        'staff_portal' => 'የሰራተኞች ፖርታል',
        'reg_resident' => 'ነዋሪ መመዝገቢያ',
        'total_residents' => 'ጠቅላላ ነዋሪዎች',
        'total_houses' => 'ጠቅላላ ቤቶች',
        'total_families' => 'ጠቅላላ ቤተሰቦች',
        'ids_issued' => 'የተሰጡ መታወቂያዎች',
        'language' => 'ቋንቋ',
        'search' => 'ፈልግ',
        'save' => 'አስቀምጥ',
        'back' => 'ተመለስ',
        'staff_mgmt' => 'የሰራተኞች አስተዳደር',
        'administrator' => 'አስተዳዳሪ',
        'manager' => 'ስራ አስኪያጅ',
        'secretary' => 'ፀሃፊ',
        'security_committee' => 'የደህንነት ኮሚቴ',
        'data_clerk' => 'የመረጃ ጸሐፊ',
        'vital_records' => 'የወሳኝ ኩነቶች መዝገብ',
        'birth_cert' => 'የልደት ምስክር ወረቀት',
        'death_cert' => 'የሞት ምስክር ወረቀት',
        'mark_deceased' => 'ሞትን መመዝገብ',
        'is_deceased' => 'ሁኔታ፡ የሞቱ'
    ]
];

// Helper function to get translation
function __($key) {
    global $translations, $current_lang;
    return $translations[$current_lang][$key] ?? $translations['en'][$key] ?? $key;
}
?>
