const carouselSlide = document.querySelector('.carousel_slide');
const prevButton = document.getElementById('prevBtn');
const nextButton = document.getElementById('nextBtn');
const slides = document.querySelectorAll('.carousel_slide img');
let counter = 0;
let intervalTime = 5000; 

carouselSlide.style.width = `${100 * slides.length}%`;

function showNextSlide() {
    counter++;
    if (counter >= slides.length) {
        counter = 0;
    }
    carouselSlide.style.transform = `translateX(-${counter * (100 / slides.length)}%)`;
}

nextButton.addEventListener('click', () => {
    showNextSlide();
});

prevButton.addEventListener('click', () => {
    counter--;
    if (counter < 0) {
        counter = slides.length - 1;
    }
    carouselSlide.style.transform = `translateX(-${counter * (100 / slides.length)}%)`;
});

let slideInterval = setInterval(showNextSlide, intervalTime);


nextButton.addEventListener('click', () => {
    clearInterval(slideInterval);
    slideInterval = setInterval(showNextSlide, intervalTime);
});

prevButton.addEventListener('click', () => {
    clearInterval(slideInterval);
    slideInterval = setInterval(showNextSlide, intervalTime);
});
