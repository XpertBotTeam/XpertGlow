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
const addToCartButton = document.getElementById('addToCart');

increaseButton.addEventListener('click', increaseQuantity);
decreaseButton.addEventListener('click', decreaseQuantity);
addToCartButton.addEventListener('click', addToCart);

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

function addToCart() {
const quantity = parseInt(quantityInput.value, 10);
alert(`Added ${quantity} item(s) to cart!`);
}