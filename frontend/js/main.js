// Carrousel hero avec Swiper
const swiper = new Swiper('.heroSwiper', {
    loop: true,
    autoplay: { delay: 4500, disableOnInteraction: false },
    pagination: { el: '.swiper-pagination', clickable: true },
    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    effect: 'fade',
    fadeEffect: { crossFade: true }
});

// Éléments de réservation
const checkin = document.getElementById('checkin2');
const checkout = document.getElementById('checkout2');
const adultsEl = document.getElementById('adults2');
const childrenEl = document.getElementById('children2');
const roomSelect = document.getElementById('roomType2');
const totalSpan = document.getElementById('totalFinal');
const dinerCheck = document.getElementById('diner');
const excursionCheck = document.getElementById('excursion');
const msgDiv = document.getElementById('bookingResultMsg');
const confirmBtn = document.getElementById('confirmBookingBtn');

// Tarifs et frais
const ratesMap = { standard: 85, deluxe: 135, suite: 220 };
const childExtra = 12;
const extraAdultFee = 28;

// Calcul des nuits
function getNights(start, end) {
    if(!start || !end) return 0;
    const d1 = new Date(start), d2 = new Date(end);
    const diff = (d2 - d1) / (1000*3600*24);
    return diff > 0 ? diff : 0;
}

// Calcul du total
function computeTotal() {
    let start = checkin.value, end = checkout.value;
    let nights = getNights(start, end);
    if(nights === 0) {
        totalSpan.innerText = "0 €";
        return 0;
    }
    let room = roomSelect.value;
    let baseRate = ratesMap[room];
    let adults = parseInt(adultsEl.value) || 1;
    let children = parseInt(childrenEl.value) || 0;
    let extraAdult = (adults > 2) ? (adults - 2) * extraAdultFee : 0;
    let childCost = children * childExtra;
    let nightPrice = baseRate + extraAdult + childCost;
    let totalRooms = nightPrice * nights;
    
    let extraOptions = 0;
    if(dinerCheck.checked) extraOptions += 35 * adults;
    if(excursionCheck.checked) extraOptions += 45 * adults;
    let finalTotal = totalRooms + extraOptions;
    totalSpan.innerText = finalTotal.toFixed(0) + " €";
    return { finalTotal, nights, adults, children, room, extraOptions };
}

// Mise à jour du total
function updateTotal() { computeTotal(); }

// Event listeners pour la réservation
if(checkin) checkin.addEventListener('change', updateTotal);
if(checkout) checkout.addEventListener('change', updateTotal);
if(adultsEl) adultsEl.addEventListener('input', updateTotal);
if(childrenEl) childrenEl.addEventListener('input', updateTotal);
if(roomSelect) roomSelect.addEventListener('change', updateTotal);
if(dinerCheck) dinerCheck.addEventListener('change', updateTotal);
if(excursionCheck) excursionCheck.addEventListener('change', updateTotal);

// Validation des dates
if(checkin) {
    const today = new Date().toISOString().split('T')[0];
    checkin.min = today;
    checkin.addEventListener('change', function(){
        if(checkout.value <= checkin.value) {
            let newDate = new Date(checkin.value);
            newDate.setDate(newDate.getDate()+1);
            checkout.value = newDate.toISOString().split('T')[0];
        }
        updateTotal();
    });
}
if(checkout) checkout.addEventListener('change', updateTotal);
updateTotal();

// Confirmation de réservation
if(confirmBtn) {
    confirmBtn.addEventListener('click', function(e){
        e.preventDefault();
        let data = computeTotal();
        if(!data) data = computeTotal();
        let start = checkin.value, end = checkout.value;
        if(!start || !end || getNights(start,end)<=0){
            msgDiv.innerHTML = '<i class="fas fa-times-circle"></i> Dates invalides, vérifiez votre séjour.';
            msgDiv.style.color = "#9e3b2b";
            return;
        }
        let roomName = roomSelect.options[roomSelect.selectedIndex]?.text;
        let optionsAdded = [];
        if(dinerCheck.checked) optionsAdded.push("Dîner traditionnel");
        if(excursionCheck.checked) optionsAdded.push("Excursion pirogue");
        let optionStr = optionsAdded.length ? optionsAdded.join(", ") : "Aucune option supplémentaire";
        let nightsCount = getNights(start,end);
        let startDate = new Date(start).toLocaleDateString('fr-FR', {day:'numeric',month:'long',year:'numeric'});
        let endDate = new Date(end).toLocaleDateString('fr-FR', {day:'numeric',month:'long',year:'numeric'});
        msgDiv.innerHTML = `<i class="fas fa-check-circle" style="color:#2a7f49;"></i> ✨ Réservation confirmée à l'Étoile du Sud ✨<br>
        ${roomName} · ${data.adults} adulte(s) · ${data.children} enfant(s) · Du ${startDate} au ${endDate} (${nightsCount} nuits)<br>
        Options: ${optionStr}<br>💰 Total : ${data.finalTotal} €. Un conseiller vous contactera dans l'heure.<br>
        🌟 Merci d'avoir choisi l'Étoile du Sud, Saint-Louis.`;
        msgDiv.style.background = "#e2f0e6";
        msgDiv.style.padding = "15px";
        msgDiv.style.borderRadius = "50px";
    });
}

// Navigation avec smooth scroll
document.querySelectorAll('.nav-links a').forEach(link => {
    link.addEventListener('click', function(e){
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const target = document.getElementById(targetId);
        if(target){
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            document.querySelectorAll('.nav-links a').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        }
    });
});

// Mise à jour du menu actif au scroll
window.addEventListener('scroll', function(){
    const sections = ['accueil', 'chambres', 'reserver'];
    let current = '';
    const scrollPos = window.scrollY + 120;
    for(let s of sections){
        const el = document.getElementById(s);
        if(el && el.offsetTop <= scrollPos && el.offsetTop + el.offsetHeight > scrollPos){
            current = s;
            break;
        }
    }
    if(current === '') current = 'accueil';
    document.querySelectorAll('.nav-links a').forEach(a => {
        a.classList.remove('active');
        if(a.getAttribute('href') === '#'+current) a.classList.add('active');
    });
});
const progressSteps = [...document.querySelectorAll('.progress-step')];
const bookingSteps = [...document.querySelectorAll('.booking-step')];
const contactForm = document.getElementById('contactForm');
const contactFeedback = document.getElementById('contactFeedback');
const galleryGrid = document.getElementById('galleryGrid');
const galleryFilters = document.getElementById('galleryFilters');
const testimonialSlider = document.getElementById('testimonialSlider');
const testimonialDots = document.getElementById('testimonialDots');
const mapSection = document.getElementById('location');
const roomData = [
  {id:'standard',name:'Chambre Standard',category:'standard',priceFCFA:65000,priceEUR:100,features:['WiFi','Clim','TV','Balcon']},
  {id:'superieure',name:'Chambre Supérieure',category:'superieure',priceFCFA:90000,priceEUR:140,features:['WiFi','Clim','TV','Vue fleuve']},
  {id:'suite',name:'Suite Junior',category:'suite',priceFCFA:135000,priceEUR:210,features:['WiFi','Clim','TV','Balcon','Jacuzzi']},
  {id:'presidentielle',name:'Suite Présidentielle',category:'presidentielle',priceFCFA:240000,priceEUR:375,features:['WiFi','Clim','TV','Vue fleuve','Jacuzzi']} 
];
const testimonials=[
  {name:'Amadou',country:'Sénégal',rating:5,message:'Un séjour magique, service irréprochable.'},
  {name:'Leïla',country:'France',rating:4.8,message:'Ambiance raffinée et accueil chaleureux.'},
  {name:'Samuel',country:'Canada',rating:5,message:'La piscine et le spa sont exceptionnels.'},
  {name:'Fatou',country:'Sénégal',rating:4.9,message:'Chambre magnifique, vue parfaite sur la ville.'},
  {name:'Marta',country:'Espagne',rating:4.7,message:'Expérience authentique et très confortable.'}
];
const galleryItems=[
  {category:'chambres',label:'Suite',image:'assets/images/gallery-1.svg'},
  {category:'restaurant',label:'Restaurant',image:'assets/images/gallery-2.svg'},
  {category:'piscine',label:'Piscine',image:'assets/images/gallery-3.svg'},
  {category:'exterieur',label:'Terrasse',image:'assets/images/gallery-4.svg'},
  {category:'chambres',label:'Junior Suite',image:'assets/images/gallery-5.svg'},
  {category:'restaurant',label:'Bar',image:'assets/images/gallery-6.svg'},
  {category:'piscine',label:'Espace Spa',image:'assets/images/gallery-7.svg'},
  {category:'exterieur',label:'Façade',image:'assets/images/gallery-8.svg'},
  {category:'chambres',label:'Standard',image:'assets/images/gallery-9.svg'},
  {category:'restaurant',label:'Menu',image:'assets/images/gallery-10.svg'},
  {category:'piscine',label:'Coucher de soleil',image:'assets/images/gallery-11.svg'},
  {category:'exterieur',label:'Saint-Louis',image:'assets/images/gallery-12.svg'}
];
let activeSlide=0;
function updateNavbar(){header.classList.toggle('scrolled',window.scrollY>30)}
function rotateSlides(){slides[activeSlide].classList.remove('active');activeSlide=(activeSlide+1)%slides.length;slides[activeSlide].classList.add('active')}
function renderRooms(filter='all'){roomsGrid.innerHTML='';const filtered=roomData.filter(room=>filter==='all'||room.category===filter);filtered.forEach(room=>{const card=document.createElement('article');card.className='room-card';card.innerHTML=`<div class="room-image">${room.name}</div><div class="room-content"><span class="room-badge">${room.category.replace(/\b\w/g,m=>m.toUpperCase())}</span><h3>${room.name}</h3><ul class="room-list">${room.features.map(item=>`<li>• ${item}</li>`).join('')}</ul><div class="room-price"><span>${room.priceFCFA.toLocaleString()} FCFA</span><span>${room.priceEUR} EUR</span></div><div class="room-actions"><button class="btn btn-secondary" data-room="${room.id}">Réserver</button><button class="btn btn-outline" data-room-detail="${room.id}">Voir détails</button></div></div>`;roomsGrid.appendChild(card)})}
function animateStats(entries){entries.forEach(entry=>{if(entry.isIntersecting){document.querySelectorAll('.stat-card').forEach(card=>{const count = card.querySelector('span');const target = Number(card.dataset.target || count.textContent.replace('+','')) || 0;let current = 0;const step = Math.max(1, Math.ceil(target / 40));const timer = setInterval(()=>{current += step;if(current >= target){count.textContent = card.dataset.target;clearInterval(timer)}else{count.textContent = target > 10 ? current : current.toFixed(1)}}, 25)})}})
function renderGallery(filter='all'){galleryGrid.innerHTML='';galleryItems.filter(item=>filter==='all'||item.category===filter).forEach(item=>{const card=document.createElement('div');card.className='gallery-item';card.dataset.category=item.category;card.innerHTML=`<img src="${item.image}" alt="${item.label}"/><div class="gallery-caption"><h4>${item.label}</h4></div>`;card.addEventListener('click',()=>openLightbox(item));galleryGrid.appendChild(card)})}
function openLightbox(item){const overlay=document.createElement('div');overlay.className='lightbox-overlay';overlay.innerHTML=`<div class="lightbox-content"><img src="${item.image}" alt="${item.label}"/><button class="lightbox-close">×</button></div>`;overlay.addEventListener('click',e=>{if(e.target===overlay||e.target.classList.contains('lightbox-close'))document.body.removeChild(overlay)});document.body.appendChild(overlay)}
function renderTestimonials(){testimonialSlider.innerHTML='';testimonialDots.innerHTML='';testimonials.forEach((item,index)=>{const card=document.createElement('div');card.className='testimonial-card';card.dataset.index=index;card.innerHTML=`<p>“${item.message}”</p><div class="testimonial-author"><strong>${item.name}</strong><span>${item.country}</span><span>${'★'.repeat(Math.round(item.rating))}</span></div>`;testimonialSlider.appendChild(card);const dot=document.createElement('button');dot.className='dot';dot.dataset.index=index;dot.addEventListener('click',()=>showTestimonial(index));testimonialDots.appendChild(dot)});showTestimonial(0)}
function showTestimonial(index){testimonialSlider.querySelectorAll('.testimonial-card').forEach(card=>card.style.display='none');testimonialSlider.querySelector(`.testimonial-card[data-index="${index}"]`).style.display='block';testimonialDots.querySelectorAll('.dot').forEach(dot=>dot.classList.toggle('active',dot.dataset.index==index))}
let testimonialTimer=null;function startTestimonialAutoplay(){testimonialTimer=setInterval(()=>{const active=document.querySelector('.dot.active');const next=(Number(active.dataset.index)+1)%testimonials.length;showTestimonial(next)},4000)}
document.addEventListener('DOMContentLoaded',()=>{renderRooms();renderGallery();renderTestimonials();startTestimonialAutoplay();
  setInterval(rotateSlides,6000);
  window.addEventListener('scroll',updateNavbar);
  navToggle.addEventListener('click',()=>nav.classList.toggle('open'));
  roomFilters.addEventListener('click',e=>{if(e.target.tagName==='BUTTON'){roomFilters.querySelector('.active').classList.remove('active');e.target.classList.add('active');renderRooms(e.target.dataset.filter)}});
  galleryFilters.addEventListener('click',e=>{if(e.target.tagName==='BUTTON'){galleryFilters.querySelector('.active').classList.remove('active');e.target.classList.add('active');renderGallery(e.target.dataset.filter)}});
  const statSection=document.querySelector('#stats');
  const observer=new IntersectionObserver(animateStats,{threshold:0.3});
  observer.observe(statSection);
  bookingForm.querySelectorAll('[data-next]').forEach(btn=>btn.addEventListener('click',()=>changeStep(Number(btn.dataset.next))));
  bookingForm.querySelectorAll('[data-prev]').forEach(btn=>btn.addEventListener('click',()=>changeStep(Number(btn.dataset.prev))));
  bookingForm.addEventListener('submit',handleBookingSubmit);
  contactForm?.addEventListener('submit',handleContactSubmit);
});
function changeStep(step){bookingSteps.forEach(section=>section.classList.toggle('active',Number(section.dataset.step)===step));progressSteps.forEach(stepEl=>stepEl.classList.toggle('active',Number(stepEl.dataset.step)===step));updateBookingSummary()}
function updateBookingSummary(){const form=new FormData(bookingForm.querySelector('form'));const arrival=form.get('arrival');const departure=form.get('departure');const room=form.get('room');const adults=form.get('adults');const children=form.get('children');const selected=roomData.find(r=>r.id===room);const nights=arrival&&departure?Math.max(1,(new Date(departure)-new Date(arrival))/(1000*60*60*24)):1;const total=selected?selected.priceFCFA*nights:0;bookingSummary.innerHTML=`<h3>Récapitulatif</h3><p>Chambre : ${selected?.name||'—'}</p><p>Dates : ${arrival||'—'} → ${departure||'—'}</p><p>Occupants : ${adults} adulte(s), ${children} enfant(s)</p><p>Total estimé : ${total.toLocaleString()} FCFA</p>`}
function handleBookingSubmit(event){event.preventDefault();const form=event.target;const data=Object.fromEntries(new FormData(form).entries());data.reference=`EDS-${new Date().toISOString().slice(0,10).replace(/-/g,'')}-${Math.floor(Math.random()*9000+1000)}`;fetch('/api/bookings',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(data)}).then(r=>r.ok?r.json():Promise.reject(r)).then(json=>{alert(`Réservation confirmée ${json.reference}`);form.reset();changeStep(1)}).catch(()=>alert('Une erreur est survenue. Réessayez.'))}
function handleContactSubmit(event){event.preventDefault();const form=event.target;fetch('/api/contact',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(Object.fromEntries(new FormData(form).entries()))}).then(res=>res.ok?contactFeedback.textContent='Message envoyé, merci !':Promise.reject()).catch(()=>contactFeedback.textContent='Le message n’a pas pu être envoyé.');}
