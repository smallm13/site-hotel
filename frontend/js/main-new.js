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
