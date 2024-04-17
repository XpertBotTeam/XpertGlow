function openCategoriesNav() {
    document.getElementById("xpertglow_nav_2").style.width = "250px";
    document.getElementById("categories_container").style.display = "block";
    document.getElementById("user_options_container").style.display = "none";
    document.getElementById("xpertglow_nav_3").style.width = "0";
}

function closeCategoriesNav() {
    document.getElementById("categories_container").style.display = "none";
    document.getElementById("xpertglow_nav_2").style.width = "0";
}

function openUserNav() {
    document.getElementById("xpertglow_nav_3").style.width = "200px";
    document.getElementById("user_options_container").style.display = "flex";
    document.getElementById("categories_container").style.display = "none";
    document.getElementById("xpertglow_nav_2").style.width = "0";
}

function closeUserNav(){
    document.getElementById("user_options_container").style.display = "none";
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
        document.getElementById("categories_container").style.display = "none";
        document.getElementById("xpertglow_nav_2").style.width = "0";
    }
}

$(document).ready(function() {
    
    $('#searchInput').on('keyup', function() {
        var query = $(this).val().trim();
        if (query.length > 0) {
            $('.search_results').css('display', 'block');
            $('#loader').show(); 
            $.ajax({
                url: '/ajax_search',
                type: 'GET',
                data: { query: query },
                success: function(response) {
                    displayResults(response);
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                },
                complete: function() {
                    $('#loader').hide(); 
                }
            });
        } else {
            $('#searchResults').empty();
            $('.search_results').css('display', 'none');
        }
    });

    function displayResults(products) {
        var resultsList = $('#searchResults');
        resultsList.empty();
        if (products.length > 0) {
            products.forEach(function(product) {
                resultsList.append('<li><a href="/product/' + product.id + '">' + product.name + '</a></li>');
            });
        } else {
            resultsList.append('<li>No results found</li>');
        }
    }
});


