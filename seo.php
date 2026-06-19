<?php
/**
 * Central SEO meta tag generator — Brand Search Domination Edition
 * Include inside <head> on every public page: <?php include 'seo.php'; ?>
 *
 * Outputs per-page: description, keywords, robots, canonical, Open Graph,
 * Twitter Card, og:site_name, og:locale.
 * Outputs globally: Organization+WebSite JSON-LD @graph schema covering all
 * brand-name variations in English and Bengali so Google can confidently
 * associate every spelling variant with atmabiswas.org.
 */
$_seo_page = basename($_SERVER['SCRIPT_NAME']);
$_seo_logo = 'https://atmabiswas.org/LOGO/NGO_logo_monogram.png';

$_seo_data = [
    'index.php' => [
        'title'       => 'ATMABISWAS – Official NGO Bangladesh | আত্মবিশ্বাস | Since 1991',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) — Bangladesh\'s trusted NGO since 1991. Empowering rural communities through microfinance, solar energy, agriculture, and enterprise development. Official website.',
        'keywords'    => 'ATMABISWAS, আত্মবিশ্বাস, Atmabiswas, atma biswas, atma-biswas, NGO Bangladesh, microfinance, solar power, PKSF, RMTP, rural development, agriculture, আত্মবিশ্বাস এনজিও, Atmabiswas NGO',
        'canonical'   => 'https://atmabiswas.org/',
    ],
    'aboutus.php' => [
        'title'       => 'About ATMABISWAS (আত্মবিশ্বাস) – Bangladesh NGO | Mission & Vision',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) — registered Bangladesh NGO since 1991 under the Dept. of Social Welfare. Dedicated to poverty alleviation, rural development, and community empowerment.',
        'keywords'    => 'ATMABISWAS about, আত্মবিশ্বাস, Atmabiswas Bangladesh, non-governmental organization, rural development, community empowerment, poverty alleviation, NGO 1991 Chuadanga',
        'canonical'   => 'https://atmabiswas.org/aboutus.php',
    ],
    'contact.php' => [
        'title'       => 'Contact ATMABISWAS (আত্মবিশ্বাস) – Chuadanga, Bangladesh',
        'description' => 'Contact ATMABISWAS (আত্মবিশ্বাস): Asma Palace, Court Para, Chuadanga-7200, Bangladesh. Phone: +8801713302930. Email: atmabiswas_ngo@yahoo.com. Find all branch offices.',
        'keywords'    => 'ATMABISWAS contact, আত্মবিশ্বাস যোগাযোগ, NGO Bangladesh contact, ATMABISWAS address Chuadanga, ATMABISWAS phone number, Bangladesh NGO office',
        'canonical'   => 'https://atmabiswas.org/contact.php',
    ],
    'career.php' => [
        'title'       => 'Jobs & Careers at ATMABISWAS (আত্মবিশ্বাস) – NGO Bangladesh',
        'description' => 'Explore job openings at ATMABISWAS (আত্মবিশ্বাস) NGO Bangladesh. Apply for positions in microfinance, health, agriculture, and community development. Join our team.',
        'keywords'    => 'ATMABISWAS jobs, আত্মবিশ্বাস চাকরি, NGO career Bangladesh, job vacancies Chuadanga, ATMABISWAS recruitment, NGO employment Bangladesh',
        'canonical'   => 'https://atmabiswas.org/career.php',
    ],
    'health.php' => [
        'title'       => 'Health & Nutrition Programs – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) promotes community health in rural Bangladesh through free medicine, sanitation, and nutrition awareness campaigns.',
        'keywords'    => 'ATMABISWAS health, আত্মবিশ্বাস স্বাস্থ্য, nutrition Bangladesh, rural health NGO, free medicine, sanitation Bangladesh',
        'canonical'   => 'https://atmabiswas.org/health.php',
    ],
    'Green_Energy.php' => [
        'title'       => 'Green Energy & Solar Programs – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) advances solar power, biogas, and sustainable energy programs for rural communities in Bangladesh.',
        'keywords'    => 'ATMABISWAS green energy, আত্মবিশ্বাস সোলার, solar power Bangladesh, renewable energy NGO, biogas Bangladesh, sustainable energy rural',
        'canonical'   => 'https://atmabiswas.org/Green_Energy.php',
    ],
    'enterprice.php' => [
        'title'       => 'Enterprise Development – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) empowers SMEs in Bangladesh through digital innovation, vocational training, and financial support for rural entrepreneurs.',
        'keywords'    => 'ATMABISWAS enterprise, আত্মবিশ্বাস উদ্যোগ, SME Bangladesh, enterprise development, vocational training NGO Bangladesh',
        'canonical'   => 'https://atmabiswas.org/enterprice.php',
    ],
    'Agritural.php' => [
        'title'       => 'Food & Agriculture Programs – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) supports food security and sustainable agriculture in Bangladesh through farmer training, resources, and modern farming techniques.',
        'keywords'    => 'ATMABISWAS agriculture, আত্মবিশ্বাস কৃষি, food security Bangladesh, farming NGO, sustainable agriculture Bangladesh',
        'canonical'   => 'https://atmabiswas.org/Agritural.php',
    ],
    'Events.php' => [
        'title'       => 'Events & Activities – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'Latest events and activities by ATMABISWAS (আত্মবিশ্বাস) NGO Bangladesh — scholarship programs, women\'s rights campaigns, and community welfare events.',
        'keywords'    => 'ATMABISWAS events, আত্মবিশ্বাস ইভেন্ট, NGO events Bangladesh, ATMABISWAS programs, community events Bangladesh',
        'canonical'   => 'https://atmabiswas.org/Events.php',
    ],
    'press.php' => [
        'title'       => 'ATMABISWAS Newsroom — News & Media Center | আত্মবিশ্বাস সংবাদ',
        'description' => 'Latest news, press releases, announcements, and media coverage from ATMABISWAS (আত্মবিশ্বাস) NGO Bangladesh. Stay updated with our community impact stories.',
        'keywords'    => 'ATMABISWAS news, আত্মবিশ্বাস সংবাদ, ATMABISWAS newsroom, NGO news Bangladesh, ATMABISWAS press release, media coverage Bangladesh NGO',
        'canonical'   => 'https://atmabiswas.org/press.php',
    ],
    'readytoeat.php' => [
        'title'       => 'Ready To Eat Products – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) Ready To Eat products — affordable, nutritious food supporting food security and rural livelihoods in Bangladesh.',
        'keywords'    => 'ATMABISWAS ready to eat, আত্মবিশ্বাস খাদ্য, RTE food Bangladesh, nutritious food NGO, food products Bangladesh',
        'canonical'   => 'https://atmabiswas.org/readytoeat.php',
    ],
    'founder.php' => [
        'title'       => 'Our Founder – ATMABISWAS (আত্মবিশ্বাস) Bangladesh NGO',
        'description' => 'Learn about the visionary founder of ATMABISWAS (আত্মবিশ্বাস) who established the NGO in 1991 to empower communities across rural Bangladesh.',
        'keywords'    => 'ATMABISWAS founder, আত্মবিশ্বাস প্রতিষ্ঠাতা, NGO founder Bangladesh, ATMABISWAS history 1991',
        'canonical'   => 'https://atmabiswas.org/founder.php',
    ],
    'OurTeam.php' => [
        'title'       => 'Our Team – ATMABISWAS (আত্মবিশ্বাস) Bangladesh NGO',
        'description' => 'Meet the dedicated team driving ATMABISWAS (আত্মবিশ্বাস) NGO\'s mission of sustainable community development and social impact across Bangladesh.',
        'keywords'    => 'ATMABISWAS team, আত্মবিশ্বাস দল, NGO team Bangladesh, ATMABISWAS staff Bangladesh',
        'canonical'   => 'https://atmabiswas.org/OurTeam.php',
    ],
    'SeniorManagement.php' => [
        'title'       => 'Senior Management – ATMABISWAS (আত্মবিশ্বাস) Bangladesh NGO',
        'description' => 'Meet the senior management and directors of ATMABISWAS (আত্মবিশ্বাস) NGO leading microfinance, health, agriculture, and community development programs.',
        'keywords'    => 'ATMABISWAS senior management, আত্মবিশ্বাস পরিচালনা, NGO directors Bangladesh, ATMABISWAS leadership team',
        'canonical'   => 'https://atmabiswas.org/SeniorManagement.php',
    ],
    'generalbody.php' => [
        'title'       => 'General Body & Governance – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'The General Body and Executive Committee of ATMABISWAS (আত্মবিশ্বাস) — the governing body overseeing community development programs in Bangladesh since 1991.',
        'keywords'    => 'ATMABISWAS general body, আত্মবিশ্বাস সাধারণ পরিষদ, executive committee NGO, ATMABISWAS governance Bangladesh',
        'canonical'   => 'https://atmabiswas.org/generalbody.php',
    ],
    'eve.php' => [
        'title'       => 'Executive Committee – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'Meet the executive members of ATMABISWAS (আত্মবিশ্বাস) NGO driving sustainable development and community empowerment across Bangladesh.',
        'keywords'    => 'ATMABISWAS executive, আত্মবিশ্বাস নির্বাহী কমিটি, NGO executive Bangladesh, ATMABISWAS committee members',
        'canonical'   => 'https://atmabiswas.org/eve.php',
    ],
    'notice.php' => [
        'title'       => 'Official Notices – ATMABISWAS (আত্মবিশ্বাস) NGO Bangladesh',
        'description' => 'Official notices, announcements, and important updates from ATMABISWAS (আত্মবিশ্বাস) NGO Bangladesh.',
        'keywords'    => 'ATMABISWAS notice, আত্মবিশ্বাস নোটিশ, NGO announcements Bangladesh, official notice ATMABISWAS Bangladesh',
        'canonical'   => 'https://atmabiswas.org/notice.php',
    ],
    'storelocation.php' => [
        'title'       => 'Branch Locations – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'Find ATMABISWAS (আত্মবিশ্বাস) branch and office locations across Bangladesh. Addresses and directions to our service centers and field offices.',
        'keywords'    => 'ATMABISWAS locations, আত্মবিশ্বাস শাখা, NGO branches Bangladesh, ATMABISWAS offices Chuadanga, Bangladesh NGO address',
        'canonical'   => 'https://atmabiswas.org/storelocation.php',
    ],
    'social.php' => [
        'title'       => 'Social Development Programs – ATMABISWAS (আত্মবিশ্বাস) Bangladesh',
        'description' => 'ATMABISWAS (আত্মবিশ্বাস) social development programs — supporting vulnerable communities through welfare, education, and women\'s empowerment in Bangladesh.',
        'keywords'    => 'ATMABISWAS social programs, আত্মবিশ্বাস সামাজিক উন্নয়ন, social development Bangladesh, women empowerment NGO Bangladesh',
        'canonical'   => 'https://atmabiswas.org/social.php',
    ],
];

$_d = $_seo_data[$_seo_page] ?? [
    'title'       => 'ATMABISWAS (আত্মবিশ্বাস) – Bangladesh NGO',
    'description' => 'ATMABISWAS (আত্মবিশ্বাস) is a registered non-governmental organization in Bangladesh empowering rural communities since 1991 through microfinance, agriculture, health, and green energy.',
    'keywords'    => 'ATMABISWAS, আত্মবিশ্বাস, Atmabiswas, NGO Bangladesh, community development, rural empowerment',
    'canonical'   => 'https://atmabiswas.org/',
];
?>
<meta name="description" content="<?= htmlspecialchars($_d['description']) ?>">
<meta name="keywords" content="<?= htmlspecialchars($_d['keywords']) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= $_d['canonical'] ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="ATMABISWAS">
<meta property="og:locale" content="en_BD">
<meta property="og:title" content="<?= htmlspecialchars($_d['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($_d['description']) ?>">
<meta property="og:image" content="<?= $_seo_logo ?>">
<meta property="og:image:width" content="512">
<meta property="og:image:height" content="512">
<meta property="og:image:alt" content="ATMABISWAS NGO Official Logo">
<meta property="og:url" content="<?= $_d['canonical'] ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($_d['title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($_d['description']) ?>">
<meta name="twitter:image" content="<?= $_seo_logo ?>">
<meta name="twitter:image:alt" content="ATMABISWAS NGO Official Logo">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": ["Organization", "NGO"],
      "@id": "https://atmabiswas.org/#organization",
      "name": "ATMABISWAS",
      "alternateName": [
        "Atmabiswas",
        "Atma Biswas",
        "Atto Biswas",
        "AtmaBiswas",
        "Atmabiswash",
        "AtmaBishwas",
        "Atmavishwas",
        "attobiswas",
        "atmobiswas",
        "atma-biswas",
        "আত্মবিশ্বাস",
        "আত্ম বিশ্বাস",
        "আত্তো বিশ্বাস",
        "আত্মা বিশ্বাস",
        "আত্নবিশ্বাস"
      ],
      "url": "https://atmabiswas.org/",
      "logo": {
        "@type": "ImageObject",
        "@id": "https://atmabiswas.org/#logo",
        "url": "https://atmabiswas.org/LOGO/NGO_logo_monogram.png",
        "contentUrl": "https://atmabiswas.org/LOGO/NGO_logo_monogram.png",
        "width": 512,
        "height": 512,
        "caption": "ATMABISWAS NGO Official Logo"
      },
      "image": {
        "@id": "https://atmabiswas.org/#logo"
      },
      "description": "ATMABISWAS is a Bangladesh NGO established in 1991 working in rural development, microfinance, agriculture, solar energy, health, and social welfare.",
      "foundingDate": "1991",
      "foundingLocation": {
        "@type": "Place",
        "name": "Chuadanga, Khulna Division, Bangladesh"
      },
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Asma Palace, Court Para",
        "addressLocality": "Chuadanga",
        "postalCode": "7200",
        "addressRegion": "Khulna",
        "addressCountry": "BD"
      },
      "location": {
        "@type": "Place",
        "name": "ATMABISWAS Head Office — Chuadanga",
        "address": {
          "@type": "PostalAddress",
          "streetAddress": "Asma Palace, Court Para",
          "addressLocality": "Chuadanga",
          "postalCode": "7200",
          "addressCountry": "BD"
        }
      },
      "telephone": "+8801713302930",
      "email": "atmabiswas_ngo@yahoo.com",
      "areaServed": {
        "@type": "Country",
        "name": "Bangladesh"
      },
      "knowsAbout": [
        "Microfinance",
        "Rural Development",
        "Agriculture",
        "Solar Energy",
        "Health",
        "Social Welfare"
      ],
      "sameAs": [
        "https://www.facebook.com/people/ATMABISWAS-Ngo/61573032346859/",
        "https://www.facebook.com/atmabiswas.chuadanga/",
        "https://www.youtube.com/@ATMABISWAS01",
        "https://www.youtube.com/channel/UCeqHBixXXoYfaX1gBOP-zOw",
        "https://www.linkedin.com/company/atmabiswas/"
      ],
      "contactPoint": [
        {
          "@type": "ContactPoint",
          "telephone": "+8801713302930",
          "email": "atmabiswas_ngo@yahoo.com",
          "contactType": "customer service",
          "areaServed": "BD",
          "availableLanguage": ["Bengali", "English"]
        }
      ]
    },
    {
      "@type": "WebSite",
      "@id": "https://atmabiswas.org/#website",
      "url": "https://atmabiswas.org/",
      "name": "ATMABISWAS",
      "alternateName": ["Atmabiswas", "আত্মবিশ্বাস", "ATMABISWAS NGO Bangladesh"],
      "description": "Official website of ATMABISWAS – a registered non-governmental organization in Bangladesh empowering rural communities since 1991.",
      "publisher": {
        "@id": "https://atmabiswas.org/#organization"
      },
      "inLanguage": "en-BD"
    }
  ]
}
</script>
