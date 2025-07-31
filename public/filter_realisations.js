const buttons = document.querySelectorAll('.filter-btn');
const realisations = document.querySelectorAll('.realisation');
const loadMoreBtn = document.getElementById('loadMoreBtn');

let currentCategory = 'all';
let visibleCount = 16;

function showImages() {
let count = 0;

realisations.forEach(real => {
const category = real.dataset.category;
const match = (currentCategory === 'all' || category === currentCategory);

if (match && count < visibleCount) {
real.style.display = 'block';
count++;
} else {
real.style.display = 'none';
}
});

// Vérifie s’il reste des images à afficher
const total = Array.from(realisations).filter(real => currentCategory === 'all' || real.dataset.category === currentCategory).length;

if (visibleCount >= total) {
loadMoreBtn.style.display = 'none';
} else {
loadMoreBtn.style.display = 'inline-block';
}
}

// Quand on clique sur un bouton de filtre
buttons.forEach(button => {
button.addEventListener('click', () => {
currentCategory = button.dataset.category;
visibleCount = 16; // Réinitialise à 16 au changement de filtre
showImages();
});
});

// Quand on clique sur "Afficher plus"
loadMoreBtn.addEventListener('click', () => {
visibleCount += 16;
showImages();
});

// Affiche les premières images au chargement
showImages();