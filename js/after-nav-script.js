const textTransition = document.getElementById('text-transition');
const phrases = [
    'Book Your Tickets',
    'Explore Destinations',
    'Enjoy Your Journey',
    'Safe and Convenient Travel',
    'Best Prices Guaranteed',
    '24/7 Customer Support',
    'Discover Amazing Deals',
    'Effortless Booking Process',
    'Travel with Confidence'
];
let currentIndex = 0;


function cycleText() {
    currentIndex = (currentIndex + 1) % phrases.length;
    textTransition.style.opacity = 0;
    setTimeout(() => {
        textTransition.innerHTML = `<span>${phrases[currentIndex]}</span>`;
        textTransition.style.opacity = 1;
        setTimeout(cycleText, 2000); 
    }, 500); 
}


textTransition.innerHTML = `<span>${phrases[currentIndex]}</span>`;
setTimeout(cycleText, 2000); 

