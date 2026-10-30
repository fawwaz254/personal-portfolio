<script setup>
import { computed, onMounted, onUnmounted, ref, nextTick, watch } from 'vue'
import {ArrowDownRight, ArrowUpRight, Check, ChevronRight, CircleCheck,Code2, Download, Github, Linkedin, Mail, Menu, ShieldCheck,Terminal, X } from 'lucide-vue-next'


const apiBase = import.meta.env.VITE_API_BASE_URL || ''
// Opening intro animation
const introVisible = ref(true)
const introText = ref('')
const introMessage = 'Welcome to My Portfolio Website'
let introTypingTimer = null
let introFinishTimer = null

function startIntroAnimation() {
  clearInterval(introTypingTimer)
  clearTimeout(introFinishTimer)

  introVisible.value = true

  // Jika perangkat mengurangi animasi
  if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    introVisible.value = false
    revealHomeContent()
    return
  }

  // Tunggu intro selesai sebelum menghilangkannya
  introFinishTimer = setTimeout(() => {
    introVisible.value = false

    // Tunggu animasi tirai ke atas selesai
    setTimeout(() => {
      revealHomeContent()
    }, 1000)

  }, 2800)
}

const mobileMenuOpen = ref(false)
const activeSection = ref('home')
const isScrolled = ref(false)
function handleNavbarScroll() {
  isScrolled.value = window.scrollY > 30
  updateActiveSection()
}
const activeFilter = ref('Semua')
const selectedProject = ref(null)

  function openProjectPreview(project) {
    selectedProject.value = project}

  function closeProjectPreview() {
    selectedProject.value = null}

  function handleProjectPreviewKeydown(event) {
    if (event.key === 'Escape') {
      closeProjectPreview()
    }
  }

  function updateActiveSection() {
  const sections = ['home', 'about', 'projects', 'contact']

  let currentSection = 'home'
  const triggerPosition = window.innerHeight * 0.35

  sections.forEach((id) => {
    const section = document.getElementById(id)

    if (section && section.getBoundingClientRect().top <= triggerPosition) {
      currentSection = id
    }
  })

  activeSection.value = currentSection
}

  onMounted(() => {
  window.addEventListener('keydown', handleProjectPreviewKeydown)
  window.addEventListener('scroll', handleNavbarScroll, { passive: true })

  // Mengatur posisi navbar dan menjalankan animasi intro
  handleNavbarScroll()
  startIntroAnimation()

  // Membersihkan observer sebelumnya jika tersedia
  if (advancedRevealObserver) {
    advancedRevealObserver.disconnect()
    advancedRevealObserver = null
  }

  // Menjalankan animasi reveal setelah DOM selesai diperbarui
  nextTick(() => {
    initAdvancedReveal()
  })
})

onUnmounted(() => {
  window.removeEventListener('keydown', handleProjectPreviewKeydown)
  window.removeEventListener('scroll', handleNavbarScroll)

  clearTimeout(introFinishTimer)
  clearInterval(introTypingTimer)

  if (advancedRevealObserver) {
    advancedRevealObserver.disconnect()
    advancedRevealObserver = null
  }

})

const contactForm = ref({ name: '', email: '', message: '' })
const sending = ref(false)
const formStatus = ref('')
const apiStatus = ref('Demo mode')


const activeTechTab = ref('languages')
const hoveredTech = ref(null)
const activeTech = ref(null)

const techGroups = {
  languages: [
    {
      name: 'Java',
      logo: 'https://www.vectorlogo.zone/logos/java/java-icon.svg',
      color: '#ED8B00',
      desc: 'Bahasa pemrograman berorientasi objek untuk membangun Web.'
    },
    {
      name: 'PHP',
      logo: 'https://cdn.simpleicons.org/php/777BB4',
      color: '#777BB4',
      desc: 'Bahasa server-side untuk mengembangkan web.'
    },
    {
      name: 'JavaScript',
      logo: 'https://cdn.simpleicons.org/javascript/F7DF1E',
      color: '#F7DF1E',
      desc: 'Membuat antarmuka dan interaksi website menjadi dinamis.'
    },
    {
      name: 'HTML5',
      logo: 'https://cdn.simpleicons.org/html5/E34F26',
      color: '#E34F26',
      desc: 'Menyusun struktur dan konten halaman web.'
    },
    {
      name: 'CSS3',
      logo: 'https://cdn.simpleicons.org/css/1572B6',
      color: '#1572B6',
      desc: 'Mengatur tampilan, layout, dan responsivitas website.'
    }
  ],
  tools: [
    {
      name: 'Laravel',
      logo: 'https://cdn.simpleicons.org/laravel/FF2D20',
      color: '#FF2D20',
      desc: 'Framework PHP untuk membangun web.'
    },
    {
      name: 'Vue.js',
      logo: 'https://cdn.simpleicons.org/vuedotjs/4FC08D',
      color: '#4FC08D',
      desc: 'Framework JavaScript untuk membangun antarmuka interaktif.'
    },
    {
      name: 'Tailwind CSS',
      logo: 'https://cdn.simpleicons.org/tailwindcss/06B6D4',
      color: '#06B6D4',
      desc: 'Framework CSS untuk membangun desain dengan utility classes.'
    },
    {
      name: 'MySQL',
      logo: 'https://cdn.simpleicons.org/mysql/4479A1',
      color: '#4479A1',
      desc: 'Database relasional untuk menyimpan dan mengelola data.'
    },
    {
      name: 'Git',
      logo: 'https://cdn.simpleicons.org/git/F05032',
      color: '#F05032',
      desc: 'Mengelola riwayat perubahan kode dan versi web.'
    },
    {
      name: 'GitHub',
      logo: 'https://cdn.simpleicons.org/github/FFFFFF',
      color: '#FFFFFF',
      desc: 'Menyimpan repositori dan berkolaborasi dalam pengembangan.'
    },
    {
      name: 'Cypress',
      logo: 'https://cdn.simpleicons.org/cypress/69D3A7',
      color: '#69D3A7',
      desc: 'Menjalankan pengujian otomatis pada web.'
    },
    {
    name: 'Postman',
    logo: 'https://cdn.simpleicons.org/postman/FF6C37',
    color: '#FF6C37',
    desc: 'Tools untuk menguji API, mengirim HTTP request, dan memvalidasi respons server.'
    },
  ]
}

const currentTechs = computed(() => techGroups[activeTechTab.value])

const displayedTech = computed(
  () => hoveredTech.value || activeTech.value || currentTechs.value[0]
)

function changeTechTab(tab) {
  activeTechTab.value = tab
  hoveredTech.value = null
  activeTech.value = null
}

// About section scroll reveal
const aboutSection = ref(null)
const aboutVisible = ref(false)

let aboutObserver

onMounted(() => {
  aboutObserver = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        aboutVisible.value = true
        aboutObserver?.disconnect()
      }
    },
    { threshold: 0.12 }
  )

  if (aboutSection.value) {
    aboutObserver.observe(aboutSection.value)
  }
})

onUnmounted(() => {
  aboutObserver?.disconnect()
})


// Animated intro text
const roles = ['Software Tester', 'Quality Assurance Enthusiast', 'Web Developer']
const typedRole = ref('')
let typingTimer
let roleIndex = 0
let charIndex = 0
let deleting = false

onMounted(() => {
  typingTimer = setInterval(() => {
    const currentRole = roles[roleIndex]
    if (!deleting) {
      charIndex++
      typedRole.value = currentRole.slice(0, charIndex)
      if (charIndex >= currentRole.length) {
        deleting = true
        clearInterval(typingTimer)
        typingTimer = setTimeout(() => {
          typingTimer = setInterval(typeNext, 75)
        }, 1400)
      }
    } else {
      charIndex--
      typedRole.value = currentRole.slice(0, charIndex)
      if (charIndex <= 0) {
        deleting = false
        roleIndex = (roleIndex + 1) % roles.length
      }
    }
  }, 105)
})

function typeNext() {
  const currentRole = roles[roleIndex]
  if (!deleting) {
    charIndex++
    typedRole.value = currentRole.slice(0, charIndex)
    if (charIndex >= currentRole.length) {
      deleting = true
      clearInterval(typingTimer)
      typingTimer = setTimeout(() => { typingTimer = setInterval(typeNext, 45) }, 1400)
    }
  } else {
    charIndex--
    typedRole.value = currentRole.slice(0, charIndex)
    if (charIndex <= 0) {
      deleting = false
      roleIndex = (roleIndex + 1) % roles.length
      clearInterval(typingTimer)
      typingTimer = setTimeout(() => { typingTimer = setInterval(typeNext, 105) }, 180)
    }
  }
}

function revealHomeContent() {
  nextTick(() => {
    const elements = document.querySelectorAll(
      '.home-reveal, .home-photo-reveal'
    )

    elements.forEach((element) => {
      element.classList.add('home-visible')
    })
  })
}

onUnmounted(() => { clearInterval(typingTimer); clearTimeout(typingTimer) })


const projects = [
  {
    number: '01',
    name: 'CarSale — Website Jual Beli Mobil',
    type: 'Pengujian Otomatis',
    description:
      'Pengujian otomatis berbasis browser untuk memastikan alur utama Website berjalan sesuai kebutuhan.',
    tags: ['Cypress', 'Pengujian E2E', 'Test Case'],
    details: [
      'Pengujian login dan registrasi',
      'Pengujian tambah, ubah, dan hapus produk',
      'Validasi hasil berdasarkan kondisi yang diharapkan'
    ],
    image: '/public/carsale2.png',
    status: 'Proyek Pengujian',
    accent: 'from-blue-500/20 to-cyan-400/5'
  },
  {
    number: '02',
    name: 'Diakad — Tracer Alumni',
    type: 'Pengujian White-box',
    description:
      'Analisis alur fungsi Tracer Alumni untuk mengidentifikasi jalur independen dan mengevaluasi kompleksitas kode.',
    tags: ['White-box Testing', 'PHP', 'Cyclomatic Complexity'],
    details: [
      'Menganalisis alur kontrol fungsi',
      'Mengidentifikasi jalur independen',
      'Mengevaluasi kompleksitas kode'
    ],
    image: '/public/diakad.png',
    status: 'Proyek Skripsi',
    accent: 'from-violet-500/20 to-blue-400/5'
  },
  
  {
    number: '03',
    name: 'MoneyFlow — Website Keuangan',
    type: 'Pengembangan Web',
    description:
      'Pengembangan web pengelolaan keuangan pribadi untuk membantu pengguna mengatur anggaran, mencatat transaksi, dan memantau tabungan.',
    tags: ['Laravel', 'PHP', 'MySQL', 'Pengembangan Web'],
    details: [
      'Mengembangkan fitur pengelolaan anggaran keuangan',
      'Membangun pencatatan transaksi pemasukan dan pengeluaran',
      'Mengembangkan fitur tabungan dan pencatatan setoran',
      'Mengintegrasikan Website dengan database MySQL'
    ],
    image: '/moneyflow3.png',
    status: 'Proyek Pengembangan Web',
    accent: 'from-emerald-500/15 to-blue-400/5'
  },
  {
    number: '04',
    name: 'Website E-Commerce Hijab',
    type: 'Pengembangan Web',
    description:
      'Pengembangan Website e-commerce berbasis Laravel 11 untuk mendukung proses penjualan produk hijab secara online.',
    tags: ['Laravel 11', 'PHP', 'MySQL', 'E-Commerce'],
    details: [
      'Mengembangkan website e-commerce menggunakan Laravel 11',
      'Membangun halaman katalog produk hijab',
      'Mendukung proses transaksi penjualan secara online'
    ],
    image: '/hijab.png',
    status: 'Proyek Pengembangan Web',
    accent: 'from-pink-500/20 to-purple-400/5'
  }

]

const filters = ['Semua', 'Pengujian Otomatis', 'Pengujian White-box', 'Pengembangan Web']

const visibleProjects = computed(() =>
  activeFilter.value === 'Semua'
    ? projects
    : projects.filter(p => p.type === activeFilter.value)
)

// 3D Tilt + Cursor Spotlight
function handleTilt(event) {
  // Nonaktifkan efek pada perangkat tanpa hover/mouse
  if (window.matchMedia('(hover: none)').matches) return

  const card = event.currentTarget
  const rect = card.getBoundingClientRect()

  const x = event.clientX - rect.left
  const y = event.clientY - rect.top

  const centerX = rect.width / 2
  const centerY = rect.height / 2

  const rotateX = ((y - centerY) / centerY) * -5
  const rotateY = ((x - centerX) / centerX) * 5

  card.style.setProperty('--rotate-x', `${rotateX}deg`)
  card.style.setProperty('--rotate-y', `${rotateY}deg`)

  // Posisi cahaya spotlight
  card.style.setProperty('--spotlight-x', `${x}px`)
  card.style.setProperty('--spotlight-y', `${y}px`)
}

function resetTilt(event) {
  const card = event.currentTarget

  card.style.setProperty('--rotate-x', '0deg')
  card.style.setProperty('--rotate-y', '0deg')
}

// Advanced Scroll Reveal
let advancedRevealObserver = null

function observeRevealElements() {
  if (!advancedRevealObserver) return

  const elements = document.querySelectorAll(
    '.advanced-reveal:not(.advanced-visible)'
  )

  elements.forEach((element) => {
    advancedRevealObserver.observe(element)
  })
}

function initAdvancedReveal() {
  if (!('IntersectionObserver' in window)) {
    document.querySelectorAll('.advanced-reveal').forEach((element) => {
      element.classList.add('advanced-visible')
    })
    return
  }

  advancedRevealObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('advanced-visible')
          advancedRevealObserver.unobserve(entry.target)
        }
      })
    },
    {
      threshold: 0.12,
      rootMargin: '0px 0px -40px 0px'
    }
  )

  observeRevealElements()
}

watch(activeFilter, async () => {
  await nextTick()
  observeRevealElements()
})


onMounted(async () => {
  if (!apiBase) return
  try {
    const response = await fetch(`${apiBase}/projects`)
    if (response.ok) apiStatus.value = 'API connected'
  } catch { apiStatus.value = 'Demo mode' }
})

async function sendMessage() {
  formStatus.value = ''
  if (!contactForm.value.name || !contactForm.value.email || !contactForm.value.message) {
    formStatus.value = 'Please complete all fields.'
    return
  }
  sending.value = true
  try {
    if (!apiBase) {
      formStatus.value = 'Demo mode: message validated locally. Configure VITE_API_BASE_URL to submit to the backend.'
      return
    }
    const response = await fetch(`${apiBase}/contact`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
      body: JSON.stringify(contactForm.value)
    })
    if (!response.ok) throw new Error('Request failed')
    formStatus.value = 'Thanks — your message has been sent.'
    contactForm.value = { name: '', email: '', message: '' }
  } catch {
    formStatus.value = 'Could not send the message. Please email me directly.'
  } finally { sending.value = false }
}
</script>

<template>
  <div class="site-background min-h-screen overflow-hidden text-slate-100">
    <!-- Opening Intro -->
        <Transition name="intro-curtain">
      <div
        v-if="introVisible"
        class="intro-overlay fixed inset-0 z-[200] flex items-center justify-center overflow-hidden"
      >
        <div class="intro-grid"></div>
        <div class="intro-glow intro-glow-one"></div>
        <div class="intro-glow intro-glow-two"></div>

        <div class="relative z-10 px-6 text-center">
          <p class="intro-label mb-6">WELCOME / PORTFOLIO</p>

          <div class="intro-heading">
            <div class="intro-line">
              <span class="intro-word from-left">Welcome to</span>
              <span class="intro-word from-right">my</span>
            </div>

            <div class="intro-line">
              <span class="intro-word from-left">Portfolio</span>
              <span class="intro-word from-right">Website</span>
            </div>
          </div>

          <p class="intro-name mt-6">
            FAWWAZ NAFI MUBAROK
          </p>
        </div>
      </div>
    </Transition>
    <!-- Ambient Background -->
    <div class="ambient-background">
      <div class="ambient-glow glow-one"></div>
      <div class="ambient-glow glow-two"></div>
      <div class="ambient-glow glow-three"></div>
      <div class="ambient-grid"></div>
    </div>
    <header class="fixed inset-x-0 top-0 z-50 transition-all duration-300" :class="isScrolled? 'border-b border-white/[0.07] bg-[#0b1220]/40 backdrop-blur-xl': 'border-b border-transparent bg-transparent backdrop-blur-none'">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
      <!-- Logo -->
      <a href="#home" class="flex shrink-0 items-center gap-3" aria-label="Go to homepage">
        <span class="text-lg font-extrabold tracking-tight">Fawwaz.</span>
      </a>
      <!-- Desktop Navigation -->
      <div class="hidden items-center gap-2 md:flex md:ml-auto md:mr-8">
        <a
          v-for="item in [
            { label: 'Tentang', href: '#about', id: 'about' },
            { label: 'Proyek', href: '#projects', id: 'projects' },
            { label: 'Kontak', href: '#contact', id: 'contact' }
          ]"
          :key="item.id"
          :href="item.href"
          class="rounded-full border px-5 py-2 text-sm font-medium transition-all duration-300"
          :class="activeSection === item.id
            ? 'border-blue-400/30 bg-blue-500/10 text-blue-200 shadow-[0_0_20px_rgba(59,130,246,0.08)]'
            : 'border-transparent text-slate-400 hover:border-white/10 hover:bg-white/[0.04] hover:text-white'"
        >
          {{ item.label }}
        </a>
      </div>
      <!-- Let's Talk -->
      <a
        href="#contact"
        class="hidden shrink-0 items-center rounded-full border border-blue-400/30 px-5 py-2 text-sm font-semibold text-blue-200 transition hover:bg-blue-500/10 md:inline-flex"
      >
        Let's talk <ArrowUpRight :size="15" class="ml-2" />
      </a>
      <!-- Mobile Button -->
      <button
        class="rounded-lg border border-white/10 p-2 md:hidden"
        @click="mobileMenuOpen = !mobileMenuOpen"
        :aria-label="mobileMenuOpen ? 'Close menu' : 'Open menu'"
      >
        <X v-if="mobileMenuOpen" :size="20" />
        <Menu v-else :size="20" />
      </button>
    </nav>
    </header>

    <main>
      <section id="home" class="hero-animated-gradient relative flex min-h-screen items-center pt-20">
        <div class="grid-bg absolute inset-0"></div>
        <div class="pointer-events-none absolute -right-48 top-20 h-[520px] w-[520px] rounded-full bg-blue-600/15 blur-[120px]"></div>
        <div class="relative z-10 mx-auto grid w-full max-w-7xl items-center gap-14 px-5 py-20 lg:grid-cols-[1.1fr_.9fr] lg:px-8">
          <div class="home-content">
            <div class="home-reveal home-reveal-1 mb-7 inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/[0.06] px-3 py-2 text-xs text-emerald-200">
              <span class="pulse-dot h-2 w-2 rounded-full bg-emerald-400"></span> Terbuka untuk Peluang di Bidang Teknologi
            </div>
            <p class="home-reveal home-reveal-2 mono mb-4 text-sm tracking-[.22em] text-blue-300">HAI, Saya</p>
            <h1 class="home-reveal home-reveal-3 max-w-3xl text-5xl font-extrabold leading-[1.08] tracking-[-.055em] sm:text-6xl lg:text-7xl">Fawwaz Nafi<br class="hidden sm:block"/> Mubarok<span class="text-blue-400">.</span></h1>
            <p class="home-reveal home-reveal-4 mt-5 min-h-9 text-xl font-bold text-blue-300 sm:text-2xl"><span>{{ typedRole }}</span><span class="typing-cursor" aria-hidden="true">|</span></p>
            <p class="home-reveal home-reveal-5 mt-5 max-w-xl text-lg leading-8 text-slate-400">Menjelajahi teknologi, mengembangkan ide, dan menciptakan pengalaman digital yang lebih baik.</p>
            <div class="home-reveal home-reveal-6 mt-9 flex flex-wrap gap-3">
              <a href="#projects" class="inline-flex items-center rounded-full bg-blue-600 px-6 py-3.5 text-sm font-bold shadow-lg shadow-blue-950/50 transition hover:-translate-y-1 hover:bg-blue-500">Proyek Saya <ArrowDownRight :size="17" class="ml-2" /></a>
              <a href="#contact" class="inline-flex items-center rounded-full border border-white/15 px-6 py-3.5 text-sm font-bold text-slate-200 transition hover:border-blue-300/50 hover:bg-white/[0.04]">kontak saya <Mail :size="16" class="ml-2" /></a>
            </div>
          </div>
          <div class="home-photo-reveal relative mx-auto w-full max-w-lg">
            <div class="portrait-aura absolute inset-5 rounded-full bg-blue-500/25 blur-[75px]"></div>
            <div class="portrait-frame relative mx-auto aspect-[4/5] w-full max-w-[390px] overflow-hidden rounded-[2rem] border border-blue-300/25 bg-[#101c30] shadow-2xl shadow-blue-950/40">
              <div class="portrait-orbit orbit-one"></div>
              <div class="portrait-orbit orbit-two"></div>
              <img src="/profile.jpg" alt="Portrait of Fawwaz Nafi Mubarok" class="portrait-photo absolute inset-0 h-full w-full object-cover object-[center_35%]" />
              <div class="absolute inset-0 bg-gradient-to-t from-[#07101f]/95 via-transparent to-[#07101f]/10"></div>
              <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-7">
                <div class="mb-2 flex items-center gap-2 text-xs font-semibold uppercase tracking-[.18em] text-blue-200"><span class="h-2 w-2 rounded-full bg-emerald-400"></span> Informatics Graduate</div>
                <p class="text-2xl font-extrabold text-white">Fawwaz Nafi Mubarok</p>
              </div>
            </div>
            <div class="float absolute -bottom-10 -left-4 rounded-xl border border-blue-300/20 bg-[#152742]/95 px-4 py-3 shadow-xl sm:-left-8"><p class="mono text-[10px] uppercase tracking-widest text-slate-400">Filosofi Saya</p><p class="mt-1 text-sm font-bold text-white">Membangun · Menguji · Menyempurnakan</p></div>
          </div>
        </div>
      </section>

    
      <section id="about" ref="aboutSection" class="relative min-h-screen overflow-hidden bg-transparent px-5 pb-12 pt-20 lg:px-8 lg:pt-0">
        <div class="pointer-events-none absolute -left-32 top-20 h-72 w-72 rounded-full bg-blue-600/10 blur-[100px]"></div>
        <div class="pointer-events-none absolute -right-24 bottom-10 h-72 w-72 rounded-full bg-violet-600/10 blur-[100px]"></div>

        <div
          class="relative mx-auto grid max-w-7xl items-center gap-12 lg:grid-cols-[0.9fr_1.1fr] lg:gap-16"
        >
          <!-- LEFT: About -->
          <div
            class="about-reveal"
            :class="{ 'about-visible': aboutVisible }"
          >
            <p class="mono text-xs tracking-[.2em] text-blue-300">
              01 / Tentang Saya
            </p>

            <h2 class="mt-5 text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl">
              Curious by nature.
              <br />
              <span class="about-gradient-text">Precise by practice.</span>
            </h2>

            <p class="mt-6 text-base leading-8 text-slate-300">
              Mengenal teknologi bukan hanya tentang bagaimana Website dibangun,
              tetapi juga bagaimana memastikan Website bekerja dengan baik.
            </p>

            <p class="mt-4 text-sm leading-7 text-slate-400">
              Saya merupakan lulusan S1 Informatika Universitas Muhammadiyah Malang
              yang tertarik pada pengembangan web dan software quality assurance.
              Saya senang mempelajari cara kerja Website, mengembangkan fitur,
              serta menguji fungsionalitasnya agar berjalan sesuai kebutuhan.
            </p>

            <a
            href="/CV-Fawwaz4.pdf"
            target="_blank"
            rel="noopener noreferrer"
            class="group mt-8 inline-flex items-center gap-3 rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-950/40 transition duration-300 hover:-translate-y-1 hover:bg-blue-500 hover:shadow-blue-500/25 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-300"
          >
            <Download
              :size="18"
              class="transition-transform duration-300 group-hover:translate-y-1"
            />
            Unduh CV Saya
            <ArrowUpRight
              :size="16"
              class="transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5"
            />
          </a>
          </div>

          <!-- RIGHT: Interactive technology card -->
          <div
            class="about-reveal mt-8 lg:mt-12"
            :class="{ 'about-visible': aboutVisible }"
            style="--reveal-delay: 180ms"
          >
            <div class="tech-panel relative overflow-hidden rounded-3xl border border-white/10 bg-[#101a2c]/90 shadow-2xl shadow-black/20 transition duration-500 hover:-translate-y-1 hover:border-blue-400/20 hover:shadow-blue-950/30">
              <div class="pointer-events-none absolute -right-20 -top-20 h-48 w-48 rounded-full bg-blue-500/[0.08] blur-3xl"></div>

              <!-- Card header -->
              <div class="relative border-b border-white/[0.08] p-5 sm:p-6">
                <div class="flex items-center justify-between gap-3">
                  <div>
                    <p class="mono text-[10px] tracking-[.18em] text-blue-300">
                      MY TECH STACK
                    </p>
                    <h3 class="mt-2 text-xl font-bold text-white">
                      Tools I work with<span class="text-blue-400">.</span>
                    </h3>
                  </div>

                  <div class="flex items-center gap-2 rounded-full border border-emerald-300/15 bg-emerald-300/[0.05] px-3 py-1.5">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-400"></span>
                    <span class="text-[10px] font-medium text-emerald-200">TECH STACK</span>
                  </div>
                </div>

                <!-- Tabs -->
                <div class="mt-6 grid grid-cols-2 rounded-xl border border-white/[0.08] bg-[#080f1c]/70 p-1">
                  <button
                    type="button"
                    @click="changeTechTab('languages')"
                    :class="activeTechTab === 'languages'
                      ? 'bg-blue-500/15 text-blue-200 shadow-sm'
                      : 'text-slate-500 hover:text-slate-200'"
                    class="rounded-lg px-3 py-3 text-xs font-bold transition duration-300 sm:text-sm"
                  >
                    <Code2 :size="15" class="mr-2 inline-block" />
                    Languages
                  </button>

                  <button
                    type="button"
                    @click="changeTechTab('tools')"
                    :class="activeTechTab === 'tools'
                      ? 'bg-blue-500/15 text-blue-200 shadow-sm'
                      : 'text-slate-500 hover:text-slate-200'"
                    class="rounded-lg px-3 py-3 text-xs font-bold transition duration-300 sm:text-sm"
                  >
                    <Terminal :size="15" class="mr-2 inline-block" />
                    Tools & Frameworks
                  </button>
                </div>
              </div>

              <!-- Logo grid -->
              <div class="relative p-5 sm:p-6">
                <Transition name="tech-grid" mode="out-in">
                  <div
                    :key="activeTechTab"
                    class="grid grid-cols-3 gap-3 sm:grid-cols-4"
                  >
                    <button
                      v-for="tech in currentTechs"
                      :key="tech.name"
                      type="button"
                      class="tech-logo-card group relative flex aspect-square flex-col items-center justify-center gap-3 overflow-hidden rounded-2xl border border-white/[0.06] bg-white/[0.025] p-3 transition duration-300 hover:-translate-y-1 hover:border-white/15 hover:bg-white/[0.05] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-300"
                      :class="{
                        'tech-logo-active': displayedTech.name === tech.name
                      }"
                      :style="{ '--logo-color': tech.color }"
                      :aria-label="tech.name"
                      :aria-pressed="activeTech?.name === tech.name"
                      @mouseenter="hoveredTech = tech"
                      @mouseleave="hoveredTech = null"
                      @focus="hoveredTech = tech"
                      @blur="hoveredTech = null"
                      @click="activeTech = tech"
                    >
                      <span
                        class="tech-logo-glow pointer-events-none absolute inset-0 opacity-0 transition-opacity duration-300"
                      ></span>

                      <img
                        :src="tech.logo"
                        :alt="tech.name"
                        loading="lazy"
                        class="tech-logo relative z-10 h-9 w-9 object-contain transition duration-300 sm:h-10 sm:w-10"
                        :class="displayedTech.name === tech.name
                          ? 'tech-logo-lit'
                          : 'tech-logo-dim'"
                      />

                      <span
                        class="relative z-10 text-center text-[10px] font-semibold transition-colors duration-300 sm:text-xs"
                        :class="displayedTech.name === tech.name
                          ? 'text-white'
                          : 'text-slate-500'"
                      >
                        {{ tech.name }}
                      </span>
                    </button>
                  </div>
                </Transition>

                <!-- Hover / selection details -->
                <div class="tech-detail-box mt-5 min-h-[116px] rounded-2xl border border-white/[0.08] bg-[#080f1c]/65 p-4 sm:p-5">
                  <Transition name="tech-info" mode="out-in">
                    <div :key="displayedTech.name" class="flex items-start gap-4">
                      <div
                        class="grid h-11 w-11 shrink-0 place-items-center rounded-xl border border-white/10 bg-white/[0.04]"
                        :style="{ boxShadow: `0 0 24px ${displayedTech.color}20` }"
                      >
                        <img
                          :src="displayedTech.logo"
                          :alt="displayedTech.name"
                          class="h-6 w-6 object-contain"
                          :style="{ filter: `drop-shadow(0 0 5px ${displayedTech.color}65)` }"
                        />
                      </div>

                      <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                          <h4 class="text-sm font-bold text-white">
                            {{ displayedTech.name }}
                          </h4>
                          <span class="h-1 w-1 rounded-full bg-slate-600"></span>
                          <span class="text-[10px] text-slate-500">
                            {{ activeTechTab === 'languages' ? 'Programming Language' : 'Development Tool' }}
                          </span>
                        </div>

                        <p class="mt-2 text-xs leading-6 text-slate-400">
                          {{ displayedTech.desc }}
                        </p>
                      </div>

                      <span
                        class="mt-1 h-2 w-2 shrink-0 rounded-full"
                        :style="{
                          backgroundColor: displayedTech.color,
                          boxShadow: `0 0 12px ${displayedTech.color}`
                        }"
                      ></span>
                    </div>
                  </Transition>
                </div>

                <p class="mt-4 text-center text-[10px] text-slate-600">
                  HOVER OR SELECT A TECHNOLOGY TO EXPLORE
                </p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="projects" class="relative bg-transparent pt-3 pb-16 lg:pt-5 lg:pb-20">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
          <div class="advanced-reveal flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <div>
              <p class="mono text-xs tracking-[.2em] text-blue-300">
                02 / PROYEK PILIHAN
              </p>
              <h2 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">
                Karya dan Pengujian<span class="text-blue-400">.</span>
              </h2>
              <p class="mt-4 max-w-xl leading-7 text-slate-400">
                Kumpulan proyek yang menunjukkan pengalaman saya dalam pengembangan
                web, pengujian perangkat lunak, dan analisis Website.
              </p>
            </div>

            <div class="flex flex-wrap gap-2">
              <button
                v-for="filter in filters"
                :key="filter"
                @click="activeFilter = filter"
                class="rounded-full border px-4 py-2 text-xs font-semibold transition"
                :class="activeFilter === filter
                  ? 'border-blue-400 bg-blue-500/15 text-blue-200'
                  : 'border-white/10 text-slate-400 hover:border-white/30'"
              >
                {{ filter }}
              </button>
            </div>
          </div>

          <div class="mt-8 grid gap-5 lg:grid-cols-3">
            <article
              v-for="(project, index) in visibleProjects"
              :key="project.number"
              class="card project-tilt-card group overflow-hidden rounded-2xl advanced-reveal"
              :class="`delay-${(index % 4) + 1}`"
              @mousemove="handleTilt"
              @mouseleave="resetTilt"
            >
              <!-- Screenshot proyek -->
              <div
                class="relative h-48 overflow-hidden border-b border-white/[0.06] bg-gradient-to-br sm:h-52"
                :class="project.accent"
              >
                <img
                  :src="project.image"
                  :alt="`Screenshot ${project.name}`"
                  loading="lazy"
                  class="h-full w-full object-cover object-top transition duration-500 group-hover:scale-105"
                />

                <div class="absolute inset-0 bg-gradient-to-t from-[#0b1220]/60 via-transparent to-transparent"></div>

                <span class="mono absolute right-3 top-3 rounded-lg border border-white/10 bg-[#0b1220]/80 px-3 py-1.5 text-xs text-slate-300 backdrop-blur">
                  {{ project.number }} / 04
                </span>
              </div>

              <div class="p-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                  <span class="mono text-[10px] uppercase tracking-widest text-blue-300">
                    {{ project.type }}
                  </span>
                  <span class="text-[10px] text-slate-500">
                    {{ project.status }}
                  </span>
                </div>

                <h3 class="mt-3 text-xl font-bold text-white">
                  {{ project.name }}
                </h3>

                <p class="mt-3 min-h-[72px] text-sm leading-6 text-slate-400">
                  {{ project.description }}
                </p>

                <ul class="mt-4 space-y-2">
                  <li
                    v-for="detail in project.details"
                    :key="detail"
                    class="flex gap-2 text-xs text-slate-400"
                  >
                    <CircleCheck :size="14" class="mt-0.5 shrink-0 text-blue-300" />
                    {{ detail }}
                  </li>
                </ul>

                <div class="mt-5 flex flex-wrap gap-2">
                  <span
                    v-for="tag in project.tags"
                    :key="tag"
                    class="rounded-md border border-white/10 px-2 py-1 text-[10px] text-slate-400"
                  >
                    {{ tag }}
                  </span>
                </div>

                <button
                  type="button"
                  @click="openProjectPreview(project)"
                  class="mt-6 inline-flex items-center text-sm font-semibold text-blue-300 transition hover:text-blue-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-400 rounded-md">
                  Preview Proyek
                  <ChevronRight
                    :size="16"
                    class="ml-1 transition group-hover:translate-x-1"
                  />
                </button>
              </div>
            </article>
          </div>
        </div>
      </section>

      
      <section id="contact" class="relative mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="pointer-events-none absolute left-1/3 top-10 h-64 w-64 rounded-full bg-blue-600/10 blur-[100px]"></div>

        <div class="relative grid items-start gap-12 lg:grid-cols-2 lg:gap-16">
          <div class="advanced-reveal">
            <p class="mono text-xs tracking-[.2em] text-blue-300">03 / HUBUNGI SAYA</p>

            <h2 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">
              Mari ciptakan perangkat lunak
              <br/>
              <span class="text-blue-400">yang lebih berkualitas.</span>
            </h2>

            <p class="mt-5 max-w-lg leading-7 text-slate-400">
              Saya terbuka untuk peluang kerja sebagai Software Tester / Quality Assurance tingkat pemula, program magang, serta diskusi seputar pengujian dan kualitas perangkat lunak.
            </p>

            <div class="mt-8 space-y-4 text-sm">
              <a href="mailto:fawwaznafi562@gmail.com" class="flex items-center gap-3 text-slate-300 transition hover:text-blue-300">
                <Mail :size="18" class="text-blue-300"/> fawwaznafi562@gmail.com
              </a>

              <a href="https://github.com/fawwaz254" target="_blank" rel="noreferrer" class="flex items-center gap-3 text-slate-300 transition hover:text-blue-300">
                <Github :size="18" class="text-blue-300"/> github.com/fawwaz254 <ArrowUpRight :size="14"/>
              </a>

              <a href="https://www.linkedin.com/in/fawwaz-nafi-mubarok-036274361/" target="_blank" rel="noreferrer" class="flex items-center gap-3 text-slate-300 transition hover:text-blue-300">
                <Linkedin :size="18" class="text-blue-300"/> Profil LinkedIn <ArrowUpRight :size="14"/>
              </a>
            </div>
          </div>

          <form class="card advanced-reveal delay-2 w-full min-w-0 rounded-2xl p-6 sm:p-8" @submit.prevent="sendMessage">
            <label class="mb-2 block text-sm font-semibold text-slate-300" for="name">Nama</label>
            <input id="name" v-model="contactForm.name" required class="mb-5 w-full rounded-xl border border-white/10 bg-[#0b1220] px-4 py-3 text-sm outline-none transition placeholder:text-slate-600 focus:border-blue-400/60" placeholder="Masukkan nama Anda"/>

            <label class="mb-2 block text-sm font-semibold text-slate-300" for="email">Alamat Email</label>
            <input id="email" v-model="contactForm.email" required type="email" class="mb-5 w-full rounded-xl border border-white/10 bg-[#0b1220] px-4 py-3 text-sm outline-none transition placeholder:text-slate-600 focus:border-blue-400/60" placeholder="nama@email.com"/>

            <label class="mb-2 block text-sm font-semibold text-slate-300" for="message">Pesan</label>
            <textarea id="message" v-model="contactForm.message" required rows="4" class="w-full resize-y rounded-xl border border-white/10 bg-[#0b1220] px-4 py-3 text-sm outline-none transition placeholder:text-slate-600 focus:border-blue-400/60" placeholder="Tuliskan pesan atau peluang kerja yang ingin disampaikan..."></textarea>

            <button :disabled="sending" class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-bold transition hover:bg-blue-500 disabled:opacity-50">
              {{ sending ? 'Mengirim...' : 'Kirim Pesan' }}
              <ArrowUpRight :size="16" class="ml-2"/>
            </button>

            <p v-if="formStatus" role="status" class="mt-4 text-xs leading-5 text-blue-200">{{ formStatus }}</p>

          </form>
        </div>
      </section>
    </main>
    <footer class="border-t border-white/[0.07] px-5 py-7">
      <div class="mx-auto flex max-w-7xl flex-col justify-between gap-3 text-xs text-slate-600 sm:flex-row"><span>© {{ new Date().getFullYear() }} Fawwaz Nafi Mubarok. Built with care.</span><span class="mono">QUALITY IS A PROCESS, NOT A CHECKBOX.</span></div>
    </footer>
    
    <!-- Popup Preview Detail Proyek -->
    <Transition name="project-modal">
      <div
        v-if="selectedProject"
        class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto bg-black/80 p-3 backdrop-blur-sm sm:p-6"
        @click.self="closeProjectPreview"
        role="dialog"
        aria-modal="true"
        :aria-label="`Detail proyek ${selectedProject.name}`"
      >
        <div
          class="relative my-auto w-full max-w-4xl overflow-hidden rounded-2xl border border-white/10 bg-[#0e1829] shadow-2xl shadow-black/50"
        >
          <!-- Header popup -->
          <div
            class="flex items-start justify-between gap-4 border-b border-white/[0.08] px-5 py-5 sm:px-7"
          >
            <div class="min-w-0">
              <p
                class="mono text-[10px] uppercase tracking-[.18em] text-blue-300"
              >
                {{ selectedProject.status }}
              </p>

              <h2 class="mt-2 text-xl font-bold text-white sm:text-2xl">
                {{ selectedProject.name }}
              </h2>

              <p class="mt-2 text-sm text-slate-400">
                {{ selectedProject.type }}
              </p>
            </div>

            <button
              type="button"
              @click="closeProjectPreview"
              aria-label="Tutup detail proyek"
              class="shrink-0 rounded-xl border border-white/10 p-2 text-slate-300 transition hover:bg-white/10 hover:text-white"
            >
              <X :size="20" />
            </button>
          </div>

          <!-- Screenshot utuh -->
          <div class="border-b border-white/[0.08] bg-[#080f1c] p-3 sm:p-5">
            <div
              class="flex min-h-48 items-center justify-center overflow-hidden rounded-xl bg-white sm:min-h-64"
            >
              <img
                :src="selectedProject.image"
                :alt="`Screenshot ${selectedProject.name}`"
                class="max-h-[55vh] w-full object-contain"
              />
            </div>

            <p class="mt-3 text-center text-xs text-slate-500">
              Pratinjau gambar proyek
            </p>
          </div>

          <!-- Deskripsi dan detail -->
          <div class="grid gap-7 p-5 sm:p-7 md:grid-cols-[1.3fr_0.7fr]">
            <div>
              <h3 class="text-lg font-bold text-white">
                Tentang Proyek
              </h3>

              <p class="mt-3 text-sm leading-7 text-slate-400">
                {{ selectedProject.description }}
              </p>

              <h3 class="mt-6 text-lg font-bold text-white">
                Fokus Pengerjaan
              </h3>

              <ul class="mt-3 space-y-3">
                <li
                  v-for="detail in selectedProject.details"
                  :key="detail"
                  class="flex items-start gap-3 text-sm leading-6 text-slate-300"
                >
                  <CircleCheck
                    :size="17"
                    class="mt-1 shrink-0 text-blue-300"
                  />
                  <span>{{ detail }}</span>
                </li>
              </ul>
            </div>

            <div>
              <div class="rounded-xl border border-white/[0.08] bg-white/[0.025] p-4">
                <h3 class="font-bold text-white">
                  Informasi Proyek
                </h3>

                <p class="mt-4 text-xs text-slate-500">
                  Kategori
                </p>
                <p class="mt-1 text-sm text-slate-300">
                  {{ selectedProject.type }}
                </p>

                <p class="mt-4 text-xs text-slate-500">
                  Jenis Proyek
                </p>
                <p class="mt-1 text-sm text-slate-300">
                  {{ selectedProject.status }}
                </p>

                <div class="mt-5 flex flex-wrap gap-2">
                  <span
                    v-for="tag in selectedProject.tags"
                    :key="tag"
                    class="rounded-lg border border-blue-300/15 bg-blue-400/[0.06] px-2.5 py-1.5 text-xs text-blue-200"
                  >
                    {{ tag }}
                  </span>
                </div>
              </div>

              <a
                href="#contact"
                @click="closeProjectPreview"
                class="mt-4 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-blue-500"
              >
                Diskusikan Proyek
                <ArrowUpRight :size="16" class="ml-2" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>
