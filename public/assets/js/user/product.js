const mainImage = document.getElementById('main_image');
const thumbnailButtons = document.querySelectorAll('.image_select button');
function changeMainImage(event) {
    const button = event.currentTarget;
    const newSrc = button.dataset.src;
    mainImage.src = newSrc;
}
thumbnailButtons.forEach(button => {
    button.addEventListener('click', changeMainImage);
});

const quantityInput = document.getElementById('quantity');
const decreaseButton = document.getElementById('decrease');
const increaseButton = document.getElementById('increase');

increaseButton.addEventListener('click', increaseQuantity);
decreaseButton.addEventListener('click', decreaseQuantity);

function increaseQuantity() {
let quantity = parseInt(quantityInput.value, 10);
quantity++;
quantityInput.value = quantity;
}

function decreaseQuantity() {
    let quantity = parseInt(quantityInput.value, 10);
    if (quantity > 1) {
        quantity--;
        quantityInput.value = quantity;
    }
}
