<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{ asset('assets/css/user/header.css') }}">
    <title>@yield('title')</title>
</head>
<body>
    
    <div class="xpertglow_nav_1">
            <div class="nav_left">
                <div class="nav_left_list">
                    <button onclick="openCategoriesNav()">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                <a href="{{ route('user.home') }}" class="nav_left_logo">
                    XpertGlow
                </a>
                
            </div>

            
            <div class="nav_mid">
                <div class="search_container">
                    <form action="{{ route('searchh') }}" method="GET">
                        <input type="text" id="searchInput" name="query" required placeholder="Search for Products">
                        <input type="submit" value="Search">
                    </form>
                </div>
            
                <div class="search_results">
                    <ul id="searchResults">
                    </ul>
                    <div class="loader" id="loader">
                        <i class="fa-solid fa-spinner"></i>
                    </div>

                </div>
             
            </div>

            <div class="nav_right">

                <button class="nav_right_search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>

                @auth

                    <a href="{{ route('favorite_page') }}">
                    <button class="nav_right_favorite">
                        <i class="fa-solid fa-heart"></i>
                    </button>
                    </a>
                    
                    <a href="{{ route('cart_page') }}">
                    <button class="nav_right_cart">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </button>
                    </a>

                    <button class="nav_right_user" onclick="openUserNav()">
                        <i class="fa-solid fa-user"></i>
                    </button>
                @else
                    <button class="nav_right_login" onclick="showLoginOptions()">
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </button>
    
                    <div id="login_options" class="login_options">
                        <h3>Welcome to XpertGlow</h3>
                        <a href="{{ route('login_page') }}">login</a>
                        <a href="{{ route('register_page') }}">register</a>
                    </div>
                @endauth
                
            </div>
    </div>

    <div id="xpertglow_nav_2" class="xpertglow_nav_2">
          
        <div class="close_btn"><button onclick="closeCategoriesNav()"><i class="fa-solid fa-xmark"></i></button></div>
        
        <div id="categories_container" class="categories_container">
            @foreach($categories as $category)
            <div class="category" onclick="toggleSubcategories(this)">
                <button>{{ $category->name }}<i class="icon fa-solid fa-arrow-down"></i></button>
                @foreach($category->subcategories as $subcategory)
                    <a href="{{ route('subcategory', ['id' => $subcategory->id]) }}" class="sub_category">{{ $subcategory->name }}</a>
                @endforeach
            </div>
            @endforeach
        </div>
        
    </div>
    @auth
    <div id="xpertglow_nav_3" class="xpertglow_nav_3">
        <div class="close_btn"><button onclick="closeUserNav()"><i class="fa-solid fa-xmark"></i></button></div>
        <div id="user_options_container" class="user_options_container">
            <a href="{{ route('account_page') }}" class="user_option">
                <button><i class="fa-solid fa-gears"></i>Account</button>
            </a>
            <a href="{{ route('order_page') }}" class="user_option">
                <button><i class="fa-solid fa-truck"></i>Orders</button>
            </a>

            <a class="user_option">
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                        <button><i class="fa-solid fa-right-from-bracket"></i>Logout</button>
                </form>
            </a>
        </div>
    </div>

    @endauth

    @yield('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('assets/js/user/header.js') }}"></script>
</body>
</html>