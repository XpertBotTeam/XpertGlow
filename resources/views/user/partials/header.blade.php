<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/user/header.css') }}">
    <title>Document</title>
</head>
<body>
    
    <div class="xpertglow_nav_1">
            <div class="nav_left">
                <div class="nav_left_list">
                    <button onclick="openNav()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <div class="nav_left_logo">
                    XpertGlow
                </div>
                
            </div>

            <div class="nav_mid">
                <div class="search-container">
                    <input type="text" placeholder="Search for Products">
                    <input type="submit" value="Search">
                </div>
             
            </div>

            <div class="nav_right">

                <button class="nav_right_search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
                
                <button class="nav_right_cart">
                    <i class="fa-solid fa-cart-shopping"></i>
                </button>
                
                <button class="nav_right_login" onclick="showLoginOptions()">
                    <i class="fa-solid fa-right-to-bracket"></i>
                </button>

                <div id="login_options" class="login_options">
                    <h3>Welcome to XpertGlow</h3>
                    <a href="./login.html">login</a>
                    <a href="./register.html">register</a>
                </div>

            </div>
    </div>

    <div id="xpertglow_nav_2" class="xpertglow_nav_2">
          
        <div class="close_btn"><button onclick="closeNav()"><i class="fa-solid fa-xmark"></i></button></div>
        <div class="categories_container">

            <div class="category" onclick="toggleSubcategories(this)">
                <button>Makeup<i class="icon fa-solid fa-arrow-down"></i></button>
                <a href="#" class="sub_category">issa</a>
                <a href="#"  class="sub_category">mhmd</a>
                <a href="#"  class="sub_category">hassan</a>
            </div>

            <div class="category" onclick="toggleSubcategories(this)">
                <button>Skin Care<i class="icon fa-solid fa-arrow-down"></i></button>
                <a href="#"  class="sub_category">1</a>
                <a href="#"  class="sub_category">2</a>
                <a href="#"  class="sub_category">3</a>
            </div>
            
        </div>

    </div>
    <script src="{{ asset('assets/js/user/header.js') }}"></script>
</body>
</html>