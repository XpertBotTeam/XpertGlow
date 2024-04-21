<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Address;
use App\Models\Carousel;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Image;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ActionController extends Controller
{


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->isAdmin) {
                return redirect()->route('admin.home');
            } else {

                if($user->isBlocked==1){
                    return back()->withErrors(['message' => 'Invalid credentials']);
                }
                else{
                    return redirect()->route('user.home');
                }
            }
        } else {
            return back()->withErrors(['message' => 'Invalid credentials']);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.home');
    }

    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
    
        Auth::login($user);
    
        return redirect()->route('user.home');
    }

    public function update_cart_item(Request $request)
    {
        $itemId = $request->input('item_id');
        $newQuantity = $request->input('quantity');

        $cartItem = CartItem::find($itemId);

        if ($cartItem) {
            $cartItem->quantity = $newQuantity;
            $saveSuccess = $cartItem->save();

            if ($saveSuccess) {
                $userId = Auth::id();
                $cart = Cart::where('user_id', $userId)
                ->with('cartItems.product.images') 
                ->first();

                return response()->json([
                'success' => true,
                'cart' => $cart,
            ]);
            }
        }
    }

    public function delete_cart_item(Request $request)
    {
        $itemId = $request->input('item_id');
       
        $cartItem = CartItem::find($itemId);
        if ($cartItem) {
            $deleteSuccess = $cartItem->delete();
            if ($deleteSuccess) {
                $userId = Auth::id();
                $cart = Cart::where('user_id', $userId)
                ->with('cartItems.product.images') 
                ->first();
                return response()->json([
                'success' => true,
                'cart' => $cart,
            ]);
            } 
        }
    }

    public function delete_cart(Request $request)
    {
        $cartId = $request->input('cart_id');
        $cart = Cart::find($cartId);
        if ($cart) {
            $deleteSuccess = $cart->delete();
            if ($deleteSuccess) {
                return response()->json([
                'success' => true
            ]);
            } 
        }
    }

    public function place_order(Request $request)
    {
        $request->validate([
            'cart_id' => 'required|integer',
            'address_id' => 'required|integer',
        ]);

        $cartId = $request->input('cart_id');
        $addressId = $request->input('address_id');

        $cart = Cart::with('cartItems.product')->find($cartId);
        $address = Address::find($addressId);

        $total_price = 0;
        foreach ($cart->cartItems as $cartItem) {
            $total_price +=$cartItem->product->price*$cartItem->quantity;
        }

        $order = new Order();
        $order->user_id = Auth::id();
        $order->address_id = $address->id;
        $order->total_price = $total_price;
        $order->save();

        foreach ($cart->cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $cartItem->product->id;
            $orderItem->price = $cartItem->product->price;
            $orderItem->quantity = $cartItem->quantity;
            $orderItem->save();
        }

        $cart->delete();

        return response()->json([
            'success' => true
        ]);

    }

    public function cancel_order(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer'
        ]);

        $order = Order::find($request->input('order_id'));
        $order->status = 'cancelled';
        $order->save();

        return response()->json([
            'success' => 'Order has been cancelled successfully',
        ], 200);
    }

    public function remove_order_item($id)
    {
        $order_item = OrderItem::find($id);
        $order_item->delete();
        return redirect()->back();
    }

    public function toggle_add_to_cart(Request $request)
    {
        
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::findOrFail($productId);
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $cartItem = $cart->cartItems()->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();

        } else {
            $cart->cartItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully',
        ]);
    }

    public function ajax_search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', '%' . $query . '%')->get();
        return response()->json($products);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = collect(); 
        $userFavorites = [];

        if ($query) {

            $products = Product::with('images')
                ->where('name', 'LIKE', '%' . $query . '%')
                ->get();
            if (Auth::check()) {
                $userId = Auth::id();
                $userFavorites = Favorite::where('user_id', $userId)
                    ->pluck('product_id')
                    ->toArray();
            }
        }
        return view('user.search', [
            'products' => $products,
            'userFavorites' => $userFavorites,
            'query' => $query
        ]);

    }


    public function toggle_favorite(Request $request)
    {
            if (!Auth::check()) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $userId = Auth::id();
            $productId = $request->input('product_id');
            $isFavorite = filter_var($request->input('is_favorite'), FILTER_VALIDATE_BOOLEAN);

            $favorite = Favorite::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();

            $response['is_favorite'] = null;
            if ($favorite) {

                if ($isFavorite === true) {
                    $response = ['is_favorite' => $favorite];
                    $favorite->delete();
                    $response['is_favorite'] = false;
                }
            }
            else {
                if ($isFavorite === false) {
                    $newFavorite = new Favorite();
                    $newFavorite->user_id = $userId;
                    $newFavorite->product_id = $productId;
                    $newFavorite->save();
                    $response['is_favorite'] = true;
                }
                else{
                    $response['is_favorite'] = false;
                }
            }
            return response()->json($response);
    }


    public function add_address(Request $request)
    {
        $userId = Auth::id();
        $address = Address::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'address' => $request->input('address'),
            'more_info' => $request->input('more_info'),
            'district' => $request->input('district'),
            'locality' => $request->input('locality'),
            'phone' => $request->input('phone'),
            'user_id' => $userId
        ]);
        return redirect()->to('/account');
    }

    public function delete_address($id)
    {
        $address = Address::find($id);
            if ($address) {
                $address->isDeleted = true;
                $address->save();
            }
            return redirect()->back();
    }

    public function change_password(Request $request)
    {
        $userId = Auth::id();
        $user = User::findOrFail($userId);

        if (Hash::check($request->input('current_password'), $user->password)) {
           $user->password = Hash::make($request->input('new_password'));
            $success = $user->save(); 
            if($success){
                Auth::logout();
            }
            return redirect()->to('/login');
        }
        else{
            return redirect()->back();
        }
    }

    public function create_category(Request $request)
    {
        $category = Category::create(['name' => $request->input('name')]);
        return redirect()->to('/admin/category');
    }

    public function update_category(Request $request, $id)
    {
            $category = Category::find($id);
            if ($category) {
                $category->name = $request->input('name');
                $category->save();
            }
            return redirect()->to('/admin/category');
    }
    
    public function delete_category($id)
    {
        $category = Category::find($id);
        $category->delete();
        return redirect()->to('/admin/category');
    }

    public function create_subcategory(Request $request)
    {
        $subcategory = SubCategory::create([
            'name' => $request->input('name'),
            'category_id' => $request->input('category')
        ]);
        return redirect()->to('/admin/subcategory');
    }

    public function update_subcategory(Request $request, $id)
    {
            $subcategory = SubCategory::find($id);

            if ($subcategory) {
                $subcategory->name = $request->input('name');
                $subcategory->category_id = $request->input('category');
                $subcategory->save();
            }
            return redirect()->to('/admin/subcategory');
    }
    
    public function delete_subcategory($id)
    {
        $subcategory = SubCategory::find($id);
        $subcategory->delete();
        return redirect()->to('/admin/subcategory');
    }

    public function create_product(Request $request)
    {
        $product = Product::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'quantity' => $request->input('quantity'),
            'sub_category_id' => $request->input('subcategory'),
        ]);
        return redirect()->to('/admin/product');
    }

    public function update_product(Request $request, $id)
    {
            $product = Product::find($id);
            if ($product) {
                $product->name = $request->input('name');
                $product->description = $request->input('description');
                $product->code = $request->input('code');
                $product->quantity = $request->input('quantity');
                $product->sub_category_id = $request->input('subcategory');
                $product->save();
            }
            return redirect()->to('/admin/product');
    }
    
    public function delete_product($id)
    {
        $product = Product::find($id);
        $product->delete();
        return redirect()->to('/admin/product');
    }

    public function delete_user($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->to('/admin/user');
    }

    public function block_user($id)
    {
        $user = User::find($id);

        if($user->isBlocked==0){
            $user->isBlocked = 1;
        }
        elseif($user->isBlocked==1){
            $user->isBlocked = 0;
        }

        $user->save();
        return redirect()->back();
    }

    public function upload_image(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
        $imageFile = $request->file('image');
        $pathPrefix = 'images/';
        if ($request->has('imageable_type')) {
            if ($request->input('imageable_type') == 'App\Models\Category') {
                $pathPrefix .= 'categories/';
            } elseif ($request->input('imageable_type') == 'App\Models\SubCategory') {
                $pathPrefix .= 'subcategories/';
            } elseif ($request->input('imageable_type') == 'App\Models\Product') {
                $pathPrefix .= 'products/';
            } elseif ($request->input('imageable_type') == 'App\Models\Carousel') {
                $pathPrefix .= 'carousels/';
            }
        }
        $fullPath = $imageFile->store($pathPrefix, 'public');
        $fileName = basename($fullPath);
        $image = new Image();
        $image->path = $fileName;
        $image->imageable_id = $request->input('id'); 
        $image->imageable_type = $request->input('imageable_type'); 
        $image->save();
        return redirect()->back();
    }

    public function delete_image($id)
    {
            $image = Image::find($id);
            if (!$image) {
                return redirect()->back();
            }
            $filePath = null;
            if ($image->imageable_type == "App\Models\Category") {
                $filePath = "images/categories/$image->path";
            } elseif ($image->imageable_type == "App\Models\SubCategory") {
                $filePath = "images/subcategories/$image->path";
            } elseif ($image->imageable_type == "App\Models\Product") {
                $filePath = "images/products/$image->path";
            } elseif ($image->imageable_type == "App\Models\Carousel") {
                $filePath = "images/carousels/$image->path";
            }
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $image->delete();
            return redirect()->back();
    }

    public function create_carousel(Request $request)
    {
           
            $carousel = new Carousel();
            $carousel->carouselable_id = $request->input('carouselable_id');
            $carousel->carouselable_type = $request->input('carouselable_type');
            $carousel->save();

            $imageFile = $request->file('image');
            $pathPrefix = 'images/carousels/';
            $fullPath = $imageFile->store($pathPrefix, 'public');
            $fileName = basename($fullPath);
            $image = new Image();
            $image->path = $fileName;
            $image->imageable_id = $carousel->id; 
            $image->imageable_type = 'App\Models\Carousel'; 
            $image->save();

            return redirect()->back();

    }

    public function delete_carousel($id)
    {
        $carousel = Carousel::find($id);
        if($carousel){
            $image = Image::where('imageable_id', $carousel->id)->first();
            if ($image) {
                $filePath = null;
                if ($image->imageable_type == "App\Models\Category") {
                    $filePath = "images/categories/$image->path";
                } elseif ($image->imageable_type == "App\Models\SubCategory") {
                    $filePath = "images/subcategories/$image->path";
                } elseif ($image->imageable_type == "App\Models\Product") {
                    $filePath = "images/products/$image->path";
                } elseif ($image->imageable_type == "App\Models\Carousel") {
                    $filePath = "images/carousels/$image->path";
                }
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }
                $image->delete();
            }
            $carousel->delete();
        }
        return redirect()->back();
    }

    public function update_order_status(Request $request, $id){
        $order = Order::find($id);
        if ($order) {
            $order->status = $request->input('status');
            $order->save();
        }
        return redirect()->back();
    }

}
