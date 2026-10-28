<script setup>
import { computed, onMounted, onUnmounted, ref } from 'vue'
import { ArrowDownRight, ArrowUpRight, Check, ChevronRight, CircleCheck, Code2, Github, Linkedin, Mail, Menu, Terminal, X } from 'lucide-vue-next'

const apiBase = import.meta.env.VITE_API_BASE_URL || ''
const mobileMenuOpen = ref(false)
const activeFilter = ref('All')
const contactForm = ref({ name: '', email: '', message: '' })
const sending = ref(false)
const formStatus = ref('')
const apiStatus = ref('Demo mode')

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

onUnmounted(() => { clearInterval(typingTimer); clearTimeout(typingTimer) })

const projects = [
  {
    number: '01', name: 'CarSale Web Application', type: 'Automation',
    description: 'End-to-end browser testing for core user flows, including authentication and product CRUD operations.',
    tags: ['Cypress', 'E2E Testing', 'Test Cases'],
    details: ['Login and registration flow', 'Create, update, and delete product', 'Assertions for expected UI state'],
    status: 'Case study', accent: 'from-blue-500/20 to-cyan-400/5'
  },
  {
    number: '02', name: 'Diakad — Tracer Alumni', type: 'Manual QA',
    description: 'Academic information system feature with focus on flow analysis, test design, and maintainability.',
    tags: ['White-box Testing', 'PHP', 'Cyclomatic Complexity'],
    details: ['Analyze function control flow', 'Identify independent paths', 'Document testing rationale'],
    status: 'Internship project', accent: 'from-violet-500/20 to-blue-400/5'
  },
  {
    number: '03', name: 'MoneyFlow Finance App', type: 'Manual QA',
    description: 'Personal finance application concept with budgeting, transactions, and savings workflows.',
    tags: ['Functional Testing', 'Laravel', 'MySQL'],
    details: ['Validate budget calculations', 'Check transaction categories', 'Test saving deposit workflows'],
    status: 'Application project', accent: 'from-emerald-500/15 to-blue-400/5'
  }
]
const filters = ['All', 'Automation', 'Manual QA']
const visibleProjects = computed(() => activeFilter.value === 'All' ? projects : projects.filter(p => p.type === activeFilter.value))

const sampleCases = [
  { id: 'TC-001', scenario: 'Login with valid credentials', expected: 'User enters dashboard', result: 'PASS', type: 'Positive' },
  { id: 'TC-002', scenario: 'Login with incorrect password', expected: 'Error message is shown', result: 'PASS', type: 'Negative' },
  { id: 'TC-003', scenario: 'Submit form with required fields empty', expected: 'Validation prevents submission', result: 'REVIEW', type: 'Validation' }
]

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
  <div class="min-h-screen overflow-hidden text-slate-100">
    <header class="fixed inset-x-0 top-0 z-50 border-b border-white/[0.07] bg-[#0b1220]/85 backdrop-blur-xl">
      <nav class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 lg:px-8">
        <a href="#home" class="flex items-center gap-3" aria-label="Go to homepage">
          <span class="font-extrabold tracking-tight">Fawwaz.</span>
        </a>
        <div class="hidden items-center gap-8 text-sm text-slate-400 md:flex">
          <a class="transition hover:text-white" href="#about">Tentang</a>
          <a class="transition hover:text-white" href="#projects">Proyek</a>
          <a class="transition hover:text-white" href="#testing-lab">Testing Lab</a>
          <a class="transition hover:text-white" href="#contact">Kontak</a>
        </div>
        <a href="#contact" class="hidden rounded-full border border-blue-400/30 px-4 py-2 text-sm font-semibold text-blue-200 transition hover:bg-blue-500/10 md:inline-flex">Let's talk <ArrowUpRight :size="15" class="ml-2" /></a>
        <button class="rounded-lg border border-white/10 p-2 md:hidden" @click="mobileMenuOpen = !mobileMenuOpen" :aria-label="mobileMenuOpen ? 'Close menu' : 'Open menu'">
          <X v-if="mobileMenuOpen" :size="20" /><Menu v-else :size="20" />
        </button>
      </nav>
      <div v-if="mobileMenuOpen" class="border-t border-white/10 px-5 py-4 md:hidden">
        <div class="flex flex-col gap-4 text-sm text-slate-300">
          <a @click="mobileMenuOpen=false" href="#about">About</a><a @click="mobileMenuOpen=false" href="#projects">Projects</a><a @click="mobileMenuOpen=false" href="#testing-lab">Testing Lab</a><a @click="mobileMenuOpen=false" href="#contact">Contact</a>
        </div>
      </div>
    </header>

    <main>
      <section id="home" class="relative flex min-h-[680px] items-center pt-20">
        <div class="grid-bg absolute inset-0"></div>
        <div class="pointer-events-none absolute -right-48 top-20 h-[520px] w-[520px] rounded-full bg-blue-600/15 blur-[120px]"></div>
        <div class="relative mx-auto grid w-full max-w-7xl items-center gap-14 px-5 py-20 lg:grid-cols-[1.1fr_.9fr] lg:px-8">
          <div class="reveal">
            <div class="mb-7 inline-flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/[0.06] px-3 py-2 text-xs text-emerald-200">
              <span class="pulse-dot h-2 w-2 rounded-full bg-emerald-400"></span> Terbuka untuk Peluang di Bidang Teknologi
            </div>
            <p class="mono mb-4 text-sm tracking-[.22em] text-blue-300">HAI, Saya</p>
            <h1 class="max-w-3xl text-5xl font-extrabold leading-[1.08] tracking-[-.055em] sm:text-6xl lg:text-7xl">Fawwaz Nafi<br class="hidden sm:block"/> Mubarok<span class="text-blue-400">.</span></h1>
            <p class="mt-5 min-h-9 text-xl font-bold text-blue-300 sm:text-2xl"><span>{{ typedRole }}</span><span class="typing-cursor" aria-hidden="true">|</span></p>
            <p class="mt-5 max-w-xl text-lg leading-8 text-slate-400">Menjelajahi teknologi, mengembangkan ide, dan menciptakan pengalaman digital yang lebih baik.</p>
            <div class="mt-9 flex flex-wrap gap-3">
              <a href="#projects" class="inline-flex items-center rounded-full bg-blue-600 px-6 py-3.5 text-sm font-bold shadow-lg shadow-blue-950/50 transition hover:-translate-y-1 hover:bg-blue-500">Proyek Saya <ArrowDownRight :size="17" class="ml-2" /></a>
              <a href="#contact" class="inline-flex items-center rounded-full border border-white/15 px-6 py-3.5 text-sm font-bold text-slate-200 transition hover:border-blue-300/50 hover:bg-white/[0.04]">kontak saya <Mail :size="16" class="ml-2" /></a>
            </div>
          </div>
          <div class="relative mx-auto w-full max-w-lg reveal delay-2">
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

      <section id="about" class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[.8fr_1.2fr]">
          <div><p class="mono text-xs tracking-[.2em] text-blue-300">01 / ABOUT ME</p><h2 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">Curious by nature.<br/><span class="text-slate-500">Precise by practice.</span></h2></div>
          <div class="max-w-2xl">
            <p class="text-lg leading-8 text-slate-300">I recently graduated with a Bachelor's degree in Informatics from the University of Muhammadiyah Malang. My background in web development helps me understand how applications are built, while my interest in quality assurance drives me to test how they behave in real-world scenarios.</p>
            <p class="mt-5 leading-7 text-slate-400">I value clear test documentation, reproducible bug reports, and testing decisions supported by evidence. I'm continuing to grow in manual testing, test automation, and software quality practices.</p>
            <div class="mt-8 grid gap-3 sm:grid-cols-2">
              <div class="card rounded-xl p-4"><Code2 class="mb-3 text-blue-300" :size="21"/><h3 class="font-bold">Developer perspective</h3><p class="mt-2 text-sm leading-6 text-slate-400">Understanding web flows, APIs, and application logic.</p></div>
              <div class="card rounded-xl p-4"><ShieldCheck class="mb-3 text-blue-300" :size="21"/><h3 class="font-bold">Quality mindset</h3><p class="mt-2 text-sm leading-6 text-slate-400">Thinking about expected results, edge cases, and risks.</p></div>
            </div>
          </div>
        </div>
      </section>

      <section id="projects" class="border-y border-white/[0.07] bg-[#0e1829] py-24">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
          <div class="flex flex-col justify-between gap-6 sm:flex-row sm:items-end">
            <div><p class="mono text-xs tracking-[.2em] text-blue-300">02 / SELECTED WORK</p><h2 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">Testing in practice<span class="text-blue-400">.</span></h2><p class="mt-4 max-w-xl leading-7 text-slate-400">A selection of projects that demonstrate test thinking, automation, and application analysis.</p></div>
            <div class="flex flex-wrap gap-2">
              <button v-for="filter in filters" :key="filter" @click="activeFilter=filter" class="rounded-full border px-4 py-2 text-xs font-semibold transition" :class="activeFilter===filter ? 'border-blue-400 bg-blue-500/15 text-blue-200' : 'border-white/10 text-slate-400 hover:border-white/30'">{{ filter }}</button>
            </div>
          </div>
          <div class="mt-10 grid gap-5 lg:grid-cols-3">
            <article v-for="(project, index) in visibleProjects" :key="project.number" class="card group overflow-hidden rounded-2xl reveal" :class="`delay-${index+1}`">
              <div class="relative flex h-44 items-center justify-center overflow-hidden border-b border-white/[0.06] bg-gradient-to-br" :class="project.accent">
                <div class="absolute inset-0 grid-bg opacity-50"></div>
                <div class="relative rounded-2xl border border-white/15 bg-[#0b1220]/80 p-5 shadow-2xl transition duration-500 group-hover:scale-105 group-hover:-rotate-2">
                  <div class="mono mb-3 text-xs text-blue-300">SUITE_{{ project.number }}</div>
                  <div class="space-y-2"><div class="h-2 w-36 rounded bg-slate-600/70"></div><div class="h-2 w-24 rounded bg-slate-700"></div><div class="mt-3 flex gap-2"><span class="h-5 w-14 rounded bg-emerald-400/20"></span><span class="h-5 w-10 rounded bg-blue-400/20"></span></div></div>
                </div>
                <span class="mono absolute right-4 top-4 text-xs text-slate-400">{{ project.number }} / 03</span>
              </div>
              <div class="p-6">
                <div class="flex items-center justify-between gap-3"><span class="mono text-[10px] uppercase tracking-widest text-blue-300">{{ project.type }}</span><span class="text-[10px] text-slate-500">{{ project.status }}</span></div>
                <h3 class="mt-3 text-xl font-bold">{{ project.name }}</h3>
                <p class="mt-3 min-h-[72px] text-sm leading-6 text-slate-400">{{ project.description }}</p>
                <ul class="mt-4 space-y-2"><li v-for="detail in project.details" :key="detail" class="flex gap-2 text-xs text-slate-400"><CircleCheck :size="14" class="mt-0.5 shrink-0 text-blue-300"/>{{ detail }}</li></ul>
                <div class="mt-5 flex flex-wrap gap-2"><span v-for="tag in project.tags" :key="tag" class="rounded-md border border-white/10 px-2 py-1 text-[10px] text-slate-400">{{ tag }}</span></div>
                <a href="#contact" class="mt-6 inline-flex items-center text-sm font-semibold text-blue-300 transition group-hover:text-blue-200">Discuss this project <ChevronRight :size="16" class="ml-1 transition group-hover:translate-x-1"/></a>
              </div>
            </article>
          </div>
          <p class="mt-5 text-xs leading-5 text-slate-500">Project cards are starter copy. Replace descriptions with your exact contributions and link to public artifacts only when permitted.</p>
        </div>
      </section>

      <section id="testing-lab" class="mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[.8fr_1.2fr]">
          <div><p class="mono text-xs tracking-[.2em] text-blue-300">03 / TESTING LAB</p><h2 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">Evidence over assumptions.</h2><p class="mt-5 leading-7 text-slate-400">A small example of how test cases can be documented and reviewed. Replace these illustrative rows with real test evidence before publishing.</p>
            <div class="mt-7 flex items-center gap-3 rounded-xl border border-amber-300/20 bg-amber-300/[0.05] p-4 text-sm text-amber-100/80"><ShieldCheck class="shrink-0 text-amber-300" :size="20"/><span>Sample data only — not a claim that these tests were executed against a live system.</span></div>
          </div>
          <div class="overflow-hidden rounded-2xl border border-white/10 bg-[#101c30]">
            <div class="flex items-center justify-between border-b border-white/10 px-5 py-4"><div class="flex items-center gap-2"><Terminal :size="17" class="text-blue-300"/><span class="mono text-xs">test-cases.csv</span></div><span class="mono text-[10px] text-slate-500">EXAMPLE DATA</span></div>
            <div class="overflow-x-auto">
              <table class="w-full min-w-[600px] text-left text-xs">
                <thead class="bg-white/[0.03] text-slate-500"><tr><th class="px-5 py-4 font-medium">ID / SCENARIO</th><th class="px-5 py-4 font-medium">EXPECTED RESULT</th><th class="px-5 py-4 font-medium">STATUS</th></tr></thead>
                <tbody><tr v-for="test in sampleCases" :key="test.id" class="border-t border-white/[0.06] transition hover:bg-white/[0.025]"><td class="px-5 py-4"><div class="mono text-blue-300">{{ test.id }}</div><div class="mt-1.5 text-slate-300">{{ test.scenario }}</div><div class="mt-1 text-[10px] text-slate-500">{{ test.type }}</div></td><td class="px-5 py-4 leading-5 text-slate-400">{{ test.expected }}</td><td class="px-5 py-4"><span class="rounded-md px-2 py-1 text-[10px] font-bold" :class="test.result==='PASS' ? 'bg-emerald-400/10 text-emerald-300' : 'bg-amber-300/10 text-amber-200'">{{ test.result }}</span></td></tr></tbody>
              </table>
            </div>
            <div class="flex items-center justify-between border-t border-white/10 px-5 py-4 text-xs text-slate-500"><span>Traceable test design</span><span class="mono">v1.0.0</span></div>
          </div>
        </div>
      </section>

      <section id="skills" class="border-y border-white/[0.07] bg-[#0e1829] py-20">
        <div class="mx-auto max-w-7xl px-5 lg:px-8">
          <p class="mono text-xs tracking-[.2em] text-blue-300">04 / TOOLKIT</p><h2 class="mt-4 text-4xl font-extrabold tracking-tight">Tools & capabilities</h2>
          <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div v-for="skill in [{name:'Test Design',desc:'Test cases · Equivalence partitioning · Boundary values'},{name:'Automation',desc:'Cypress · Browser assertions · E2E workflows'},{name:'Web Technologies',desc:'HTML · CSS · JavaScript · PHP · Laravel'},{name:'Analysis',desc:'White-box testing · Flowgraph · Cyclomatic complexity'}]" :key="skill.name" class="card rounded-xl p-5"><div class="mb-4 grid h-10 w-10 place-items-center rounded-lg bg-blue-500/10 text-blue-300"><Check :size="18"/></div><h3 class="font-bold">{{ skill.name }}</h3><p class="mt-2 text-sm leading-6 text-slate-400">{{ skill.desc }}</p></div>
          </div>
        </div>
      </section>

      <section id="contact" class="relative mx-auto max-w-7xl px-5 py-24 lg:px-8">
        <div class="pointer-events-none absolute left-1/3 top-10 h-64 w-64 rounded-full bg-blue-600/10 blur-[100px]"></div>
        <div class="relative grid gap-12 lg:grid-cols-2">
          <div><p class="mono text-xs tracking-[.2em] text-blue-300">05 / GET IN TOUCH</p><h2 class="mt-4 text-4xl font-extrabold tracking-tight sm:text-5xl">Let's make software<br/><span class="text-blue-400">more reliable.</span></h2><p class="mt-5 max-w-lg leading-7 text-slate-400">I'm open to entry-level Software Tester / QA opportunities, internships, and conversations about software quality.</p>
            <div class="mt-8 space-y-4 text-sm">
              <a href="mailto:your.email@example.com" class="flex items-center gap-3 text-slate-300 transition hover:text-blue-300"><Mail :size="18" class="text-blue-300"/> your.email@example.com</a>
              <a href="https://github.com/fawwaz254" target="_blank" rel="noreferrer" class="flex items-center gap-3 text-slate-300 transition hover:text-blue-300"><Github :size="18" class="text-blue-300"/> github.com/fawwaz254 <ArrowUpRight :size="14"/></a>
              <a href="https://www.linkedin.com/" target="_blank" rel="noreferrer" class="flex items-center gap-3 text-slate-300 transition hover:text-blue-300"><Linkedin :size="18" class="text-blue-300"/> Add your LinkedIn profile <ArrowUpRight :size="14"/></a>
            </div>
          </div>
          <form class="card rounded-2xl p-6 sm:p-8" @submit.prevent="sendMessage">
            <label class="mb-2 block text-sm font-semibold text-slate-300" for="name">Name</label><input id="name" v-model="contactForm.name" required class="mb-5 w-full rounded-xl border border-white/10 bg-[#0b1220] px-4 py-3 text-sm outline-none transition placeholder:text-slate-600 focus:border-blue-400/60" placeholder="Your name"/>
            <label class="mb-2 block text-sm font-semibold text-slate-300" for="email">Email</label><input id="email" v-model="contactForm.email" required type="email" class="mb-5 w-full rounded-xl border border-white/10 bg-[#0b1220] px-4 py-3 text-sm outline-none transition placeholder:text-slate-600 focus:border-blue-400/60" placeholder="you@example.com"/>
            <label class="mb-2 block text-sm font-semibold text-slate-300" for="message">Message</label><textarea id="message" v-model="contactForm.message" required rows="4" class="w-full resize-y rounded-xl border border-white/10 bg-[#0b1220] px-4 py-3 text-sm outline-none transition placeholder:text-slate-600 focus:border-blue-400/60" placeholder="Tell me about the opportunity..."></textarea>
            <button :disabled="sending" class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-bold transition hover:bg-blue-500 disabled:opacity-50">{{ sending ? 'Sending...' : 'Send message' }} <ArrowUpRight :size="16" class="ml-2"/></button>
            <p v-if="formStatus" role="status" class="mt-4 text-xs leading-5 text-blue-200">{{ formStatus }}</p>
            <p class="mt-3 text-[11px] leading-5 text-slate-600">Replace the placeholder email and add spam protection before deploying the backend publicly.</p>
          </form>
        </div>
      </section>
    </main>
    <footer class="border-t border-white/[0.07] px-5 py-7">
      <div class="mx-auto flex max-w-7xl flex-col justify-between gap-3 text-xs text-slate-600 sm:flex-row"><span>© {{ new Date().getFullYear() }} Fawwaz Nafi Mubarok. Built with care.</span><span class="mono">QUALITY IS A PROCESS, NOT A CHECKBOX.</span></div>
    </footer>
  </div>
</template>
