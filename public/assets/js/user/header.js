function openCategoriesNav() {
    document.getElementById("xpertglow_nav_2").style.width = "250px";
}

function closeCategoriesNav() {
    document.getElementById("xpertglow_nav_2").style.width = "0";
}

function openUserNav() {
    document.getElementById("xpertglow_nav_3").style.width = "200px";
}

function closeUserNav(){
    document.getElementById("xpertglow_nav_3").style.width = "0";
}

function toggleSubcategories(category) {
category.classList.toggle('active');
var icon = category.querySelector('.icon');
icon.classList.toggle('rotate');

var subcategories = category.querySelectorAll('.sub_category');
subcategories.forEach(function(subcategory) {
    if (category.classList.contains('active')) {
        subcategory.style.height = subcategory.scrollHeight + "px";
    } else {
        subcategory.style.height = '0';
    }
});
}

function showLoginOptions() {
    var loginOptions = document.getElementById("login_options");
    if (loginOptions.style.display === "block") {
        loginOptions.style.display = "none";
    } else {
        loginOptions.style.display = "block";
    }
}
