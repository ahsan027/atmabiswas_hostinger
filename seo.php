<?php
/**
 * Central SEO meta tag generator.
 * Include inside <head> on every public page: <?php include 'seo.php'; ?>
 * Outputs: description, keywords, robots, canonical, Open Graph, Twitter Card.
 */
$_seo_page = basename($_SERVER['SCRIPT_NAME']);
$_seo_logo = 'https://atmabiswas.org/LOGO/NGO_logo_monogram.png';

$_seo_data = [
    'index.php' => [
        'title'       => 'ATMABISWAS - Empowering Lives in Rural Bangladesh',
        'description' => 'ATMABISWAS empowers communities in Bangladesh through microfinance, solar energy, agriculture, and enterprise development programs.',
        'keywords'    => 'ATMABISWAS, NGO Bangladesh, microfinance, solar power, PKSF, RMTP, rural development, agriculture, fishery, enterprise',
        'canonical'   => 'https://atmabiswas.org/',
    ],
    'aboutus.php' => [
        'title'       => 'About ATMABISWAS - Bangladesh NGO | Mission & Vision',
        'description' => 'Learn about ATMABISWAS, a non-governmental organization in Bangladesh dedicated to poverty alleviation, rural development, and community empowerment through microfinance.',
        'keywords'    => 'ATMABISWAS about, NGO Bangladesh, non-governmental organization, rural development, community empowerment, poverty alleviation',
        'canonical'   => 'https://atmabiswas.org/aboutus.php',
    ],
    'contact.php' => [
        'title'       => 'Contact ATMABISWAS - Get in Touch',
        'description' => 'Contact ATMABISWAS NGO in Bangladesh. Find our office address, phone number, and email to connect about our community development programs.',
        'keywords'    => 'ATMABISWAS contact, NGO Bangladesh contact, ATMABISWAS address, Bangladesh NGO email',
        'canonical'   => 'https://atmabiswas.org/contact.php',
    ],
    'career.php' => [
        'title'       => 'Jobs & Career Opportunities - ATMABISWAS Bangladesh',
        'description' => 'Explore current job openings at ATMABISWAS NGO in Bangladesh. Apply for positions in microfinance, health, agriculture, and community development.',
        'keywords'    => 'ATMABISWAS jobs, NGO career Bangladesh, job vacancies Bangladesh, ATMABISWAS recruitment, NGO employment',
        'canonical'   => 'https://atmabiswas.org/career.php',
    ],
    'health.php' => [
        'title'       => 'Health & Nutrition Programs - ATMABISWAS Bangladesh',
        'description' => 'ATMABISWAS promotes community health in Bangladesh through free medicine, sanitation facilities, and nutrition awareness campaigns in rural areas.',
        'keywords'    => 'ATMABISWAS health, nutrition Bangladesh, rural health NGO, free medicine Bangladesh, sanitation facilities, health awareness',
        'canonical'   => 'https://atmabiswas.org/health.php',
    ],
    'Green_Energy.php' => [
        'title'       => 'Green Energy & Solar Power Programs - ATMABISWAS Bangladesh',
        'description' => 'ATMABISWAS advances green energy in Bangladesh through solar power installations, biogas systems, and sustainable energy programs for rural communities.',
        'keywords'    => 'ATMABISWAS green energy, solar power Bangladesh, renewable energy NGO, biogas Bangladesh, sustainable energy rural',
        'canonical'   => 'https://atmabiswas.org/Green_Energy.php',
    ],
    'enterprice.php' => [
        'title'       => 'Enterprise Development Programs - ATMABISWAS Bangladesh',
        'description' => 'ATMABISWAS empowers small and medium enterprises in Bangladesh through digital innovation, vocational training, and financial support programs.',
        'keywords'    => 'ATMABISWAS enterprise, SME Bangladesh, digital innovation NGO, enterprise development Bangladesh, vocational training',
        'canonical'   => 'https://atmabiswas.org/enterprice.php',
    ],
    'Agritural.php' => [
        'title'       => 'Food & Agriculture Programs - ATMABISWAS Bangladesh',
        'description' => 'ATMABISWAS supports food security and sustainable agriculture in Bangladesh through farmer training, resources, and modern farming techniques.',
        'keywords'    => 'ATMABISWAS agriculture, food security Bangladesh, farming NGO, sustainable agriculture, rural farming Bangladesh',
        'canonical'   => 'https://atmabiswas.org/Agritural.php',
    ],
    'Events.php' => [
        'title'       => 'Events & Activities - ATMABISWAS Bangladesh',
        'description' => 'Stay updated with the latest events and activities organized by ATMABISWAS NGO in Bangladesh, promoting community development and social welfare.',
        'keywords'    => 'ATMABISWAS events, NGO events Bangladesh, ATMABISWAS programs, community events, social welfare Bangladesh',
        'canonical'   => 'https://atmabiswas.org/Events.php',
    ],
    'press.php' => [
        'title'       => 'Press & Media Coverage - ATMABISWAS Bangladesh',
        'description' => 'Read the latest press releases, news articles, and media coverage about ATMABISWAS NGO and its impact on communities across Bangladesh.',
        'keywords'    => 'ATMABISWAS press, NGO news Bangladesh, ATMABISWAS media, press release NGO, Bangladesh NGO news',
        'canonical'   => 'https://atmabiswas.org/press.php',
    ],
    'readytoeat.php' => [
        'title'       => 'Ready To Eat Products - ATMABISWAS Bangladesh',
        'description' => 'ATMABISWAS Ready To Eat products offer affordable and nutritious food solutions supporting food security and rural livelihoods across Bangladesh.',
        'keywords'    => 'ATMABISWAS ready to eat, RTE food Bangladesh, nutritious food NGO, food products Bangladesh, rural livelihoods',
        'canonical'   => 'https://atmabiswas.org/readytoeat.php',
    ],
    'founder.php' => [
        'title'       => 'Our Founder - ATMABISWAS Bangladesh NGO',
        'description' => 'Learn about the founder of ATMABISWAS whose vision and leadership have guided the NGO\'s mission to empower communities across rural Bangladesh.',
        'keywords'    => 'ATMABISWAS founder, NGO founder Bangladesh, ATMABISWAS history, Bangladesh NGO leader',
        'canonical'   => 'https://atmabiswas.org/founder.php',
    ],
    'OurTeam.php' => [
        'title'       => 'Our Team - ATMABISWAS Bangladesh NGO',
        'description' => 'Meet the dedicated team and leadership of ATMABISWAS NGO in Bangladesh, working together to drive sustainable community development and social impact.',
        'keywords'    => 'ATMABISWAS team, NGO team Bangladesh, ATMABISWAS staff, Bangladesh NGO leadership',
        'canonical'   => 'https://atmabiswas.org/OurTeam.php',
    ],
    'SeniorManagement.php' => [
        'title'       => 'Senior Management Team - ATMABISWAS Bangladesh',
        'description' => 'Meet the senior management and directors of ATMABISWAS NGO in Bangladesh, leading programs in microfinance, health, agriculture, and community development.',
        'keywords'    => 'ATMABISWAS senior management, NGO directors Bangladesh, ATMABISWAS leadership, Bangladesh NGO management',
        'canonical'   => 'https://atmabiswas.org/SeniorManagement.php',
    ],
    'generalbody.php' => [
        'title'       => 'General Body & Executive Committee - ATMABISWAS Bangladesh',
        'description' => 'Meet the General Body and Executive Committee of ATMABISWAS NGO, the governing body overseeing community development programs and policies in Bangladesh.',
        'keywords'    => 'ATMABISWAS general body, executive committee NGO, ATMABISWAS governance, NGO Bangladesh committee',
        'canonical'   => 'https://atmabiswas.org/generalbody.php',
    ],
    'eve.php' => [
        'title'       => 'Executive Committee - ATMABISWAS Bangladesh',
        'description' => 'Meet the executive members of ATMABISWAS NGO in Bangladesh, driving the mission to empower communities through sustainable development programs.',
        'keywords'    => 'ATMABISWAS executive, NGO executive Bangladesh, ATMABISWAS members, Bangladesh NGO executive',
        'canonical'   => 'https://atmabiswas.org/eve.php',
    ],
    'notice.php' => [
        'title'       => 'Official Notices & Announcements - ATMABISWAS',
        'description' => 'View official notices, announcements, and important updates from ATMABISWAS NGO in Bangladesh.',
        'keywords'    => 'ATMABISWAS notice, NGO announcements Bangladesh, official notice ATMABISWAS, Bangladesh NGO updates',
        'canonical'   => 'https://atmabiswas.org/notice.php',
    ],
    'storelocation.php' => [
        'title'       => 'Branch Locations - ATMABISWAS Bangladesh',
        'description' => 'Find ATMABISWAS branch and office locations across Bangladesh. View addresses and get directions to our service centers and field offices.',
        'keywords'    => 'ATMABISWAS locations, NGO branches Bangladesh, ATMABISWAS offices, Bangladesh NGO branches',
        'canonical'   => 'https://atmabiswas.org/storelocation.php',
    ],
    'social.php' => [
        'title'       => 'Social Development Programs - ATMABISWAS Bangladesh',
        'description' => 'ATMABISWAS social development programs support vulnerable communities in Bangladesh through welfare initiatives, education, and women\'s empowerment.',
        'keywords'    => 'ATMABISWAS social programs, social development Bangladesh, women empowerment NGO, community welfare Bangladesh',
        'canonical'   => 'https://atmabiswas.org/social.php',
    ],
];

$_d = $_seo_data[$_seo_page] ?? [
    'title'       => 'ATMABISWAS - Bangladesh NGO',
    'description' => 'ATMABISWAS is a non-governmental organization in Bangladesh empowering communities through microfinance, agriculture, health, and green energy programs.',
    'keywords'    => 'ATMABISWAS, NGO Bangladesh, community development, rural empowerment',
    'canonical'   => 'https://atmabiswas.org/',
];
?>
<meta name="description" content="<?= htmlspecialchars($_d['description']) ?>">
<meta name="keywords" content="<?= htmlspecialchars($_d['keywords']) ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?= $_d['canonical'] ?>">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= htmlspecialchars($_d['title']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($_d['description']) ?>">
<meta property="og:image" content="<?= $_seo_logo ?>">
<meta property="og:url" content="<?= $_d['canonical'] ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($_d['title']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($_d['description']) ?>">
<meta name="twitter:image" content="<?= $_seo_logo ?>">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NGO",
  "name": "ATMABISWAS",
  "alternateName": "Atmabiswas NGO",
  "url": "https://atmabiswas.org/",
  "logo": "https://atmabiswas.org/LOGO/NGO_logo_monogram.png",
  "description": "ATMABISWAS is a non-governmental organization in Bangladesh dedicated to poverty alleviation, rural development, microfinance, solar energy, agriculture, and enterprise development.",
  "foundingDate": "1991",
  "address": {
    "@type": "PostalAddress",
    "addressCountry": "BD",
    "addressRegion": "Kushtia"
  },
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "general",
    "url": "https://atmabiswas.org/contact.php"
  },
  "sameAs": []
}
</script>
