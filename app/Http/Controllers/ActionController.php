<?php

namespace App\Http\Controllers;

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
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
         $credentials = $request->only('email', 'password');
         if (Auth::attempt($credentials)) {
            $user = Auth::user();
                if ($user->isAdmin()) {
                    return redirect()->route('admin.home');
                } else {
                    if ($user->isBlocked()) {
                        return redirect()->back()->withErrors(['message' => 'User is blocked'])->withInput();
                    }
                    else{
                        return redirect()->route('user.home');
                    }
                }
        } 
        else {
            return redirect()->back()->withErrors(['message' => 'The email address or password is incorrect'])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('user.home');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('user.home');
    }

    public function update_cart_item(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $user_id = Auth::id();
        $item_id = $request->input('item_id');
        $cart_item = CartItem::find($item_id);
        if (!$cart_item) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }
        $cart = $cart_item->cart;
        if (!$cart || $cart->user_id !== $user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $quantity = $request->input('quantity');
        if (!is_numeric($quantity) || $quantity < 1) {
            return response()->json(['error' => 'Invalid quantity'], 400);
        }

        if($cart_item->quantity>$quantity){

            $cart_item->quantity = $quantity;
            $saveSuccess = $cart_item->save();
            if ($saveSuccess) {
                $cart = Cart::where('user_id', $user_id)
                ->with('cartItems.product.images') 
                ->first();
                return response()->json(['success' => true , 'cart' => $cart, 'message' => 'Cart item quantity updated'],200);
            }

        }
        
        else{
            $product = $cart_item->product;

            if ($product->quantity < $quantity) {
                return response()->json(['error' => "Not enough quantity available, only $product->quantity in Stock"], 400);
            }
            $cart_item->quantity = $quantity;
            $saveSuccess = $cart_item->save();
                if ($saveSuccess) {
                    $cart = Cart::where('user_id', $user_id)
                    ->with('cartItems.product.images') 
                    ->first();
                    return response()->json(['success' => true , 'cart' => $cart, 'message' => 'Cart item quantity updated'],200);
            }
        }

    }

    public function delete_cart_item(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $user_id = Auth::id();

        $item_id = $request->input('item_id');

        $cart_item = CartItem::find($item_id);

        if (!$cart_item) {
            return response()->json(['error' => 'Cart item not found'], 404);
        }

        if ($cart_item->cart->user_id !== $user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $deleteSuccess = $cart_item->delete();
        if ($deleteSuccess) {
                $userId = Auth::id();
                $cart = Cart::where('user_id', $userId)
                ->with('cartItems.product.images') 
                ->first();

            return response()->json(['success' => true , 'cart' => $cart, 'message' => 'Cart item removed'],200);
        } 
        
    }

    public function delete_cart(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $user_id = Auth::id();
        $cart_id = $request->input('cart_id');

        $cart = Cart::find($cart_id)->where('user_id', $user_id)->first();

        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }

        if ($cart->user_id !== $user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($cart->cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is already empty'], 200);
        }

        $cart->cartItems()->delete();
        
        return response()->json(['success' => true , 'message' => 'Cart Cleared'], 200);
          
    }

    public function place_order(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->back();
        }

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = Auth::user();

        $cart = $user->cart;

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->back();
        }
        
        foreach ($cart->cartItems as $cart_item) {
            if ($cart_item->quantity > $cart_item->product->quantity) {
                return redirect()->back();
            }
        }

        $totalPrice = 0;
        foreach ($cart->cartItems as $cart_item) {
            $totalPrice += $cart_item->product->price * $cart_item->quantity;
        }

        $order = new Order([
            'address_id' => $request->address_id,
            'user_id' => $user->id,
            'total_price' => $totalPrice,
        ]);

        $order->save();

        foreach ($cart->cartItems as $cart_item) {

            $order_item = new OrderItem([
                'product_id' => $cart_item->product_id,
                'quantity' => $cart_item->quantity,
                'price' => $cart_item->product->price * $cart_item->quantity,
            ]);

            $order->orderItems()->save($order_item);
            $cart_item->product->quantity -= $cart_item->quantity;
            $cart_item->product->save();
        }

        $cart->cartItems()->delete();

        return response()->json(['success' => true , 'message' => 'Order Placed'], 200);

    }

    public function cancel_order(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
        $user_id = Auth::id();

        $order = Order::find($request->input('order_id'));
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($order->user_id !== $user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        if ($order->status === 'cancelled') {
            return response()->json(['message' => 'Order is already cancelled'], 400);
        }

        if ($order->status === 'processing') {
            return response()->json(['message' => 'Order is already in processing status. You cannot cancel it now.'], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        foreach ($order->orderItems as $order_item) {
            $order_item->product->quantity += $order_item->quantity;
            $order_item->product->save();
        }

        return response()->json(['success' => true , 'message' => 'Order cancelled successfully'], 200);

    }

    public function remove_order_item($id)
    {
        if (!Auth::check()) {
            return redirect()->back();
        }
        $user_id = Auth::id();
        $order_item = OrderItem::find($id);
        if(!$order_item){
            return redirect()->back();
        }
        $order = $order_item->order;
        if ($order->user_id !== $user_id) {
            return redirect()->back();
        }
        if ($order->status !== 'pending') {
            return redirect()->back();
        }

        $order->total_price -= $order_item->price * $order_item->quantity;
        $order->save();

        $order_item->product->quantity += $order_item->quantity;
        $order_item->product->save();
        $order_item->delete();

        if ($order->orderItems()->count() === 0) {
            $order->delete();
            return redirect()->to('/order');
        }
        return redirect()->back();
    }

    public function toggle_add_to_cart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product_id = $request->input('product_id');
        
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $user_id = Auth::id();

        $product = Product::find($product_id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $quantity = $request->input('quantity', 1);

        $total_cart_quantity = CartItem::where('product_id', $product_id)
                                   ->whereHas('cart', function ($query) use ($user_id) {
                                       $query->where('user_id', $user_id);
                                   })->sum('quantity');

        $available_quantity = $product->quantity - $total_cart_quantity;
        if ($available_quantity < $quantity) {
            return response()->json(['error' => "Not enough quantity available, only $available_quantity available for you"], 400);
        }

        $cart = Cart::where('user_id', $user_id)->first();

        if (!$cart) {
            $cart = new Cart();
            $cart->user_id = $user_id;
            $cart->save();
        }

        $cart_item = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $product_id)
                            ->first();

        if($cart_item){
                $cart_item->quantity += $quantity;
                $cart_item->save();
        }

        else {
            $cart_item = new CartItem();
            $cart_item->cart_id = $cart->id;
            $cart_item->product_id = $product_id;
            $cart_item->quantity = $quantity;
            $cart_item->save();
        }

        return response()->json(['message' => 'Product added to cart'], 200);
    }

    public function ajax_search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', "%$query%")->get();
        return response()->json($products, 200);

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
                $user_id = Auth::id();
                $userFavorites = Favorite::where('user_id', $user_id)
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
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            $user_id = Auth::id();

            $product_id = $request->input('product_id');

            $product = Product::find($product_id);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            $favorite = Favorite::where('user_id', $user_id)
                            ->where('product_id', $product_id)
                            ->first();

            $isFavorite = filter_var($request->input('is_favorite'), FILTER_VALIDATE_BOOLEAN);

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
                    $newFavorite->user_id = $user_id;
                    $newFavorite->product_id = $product_id;
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
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'surname' => 'required|string',
            'address' => 'required|string',
            'more_info' => 'nullable|string',
            'district' => 'nullable|string',
            'locality' => 'nullable|string',
            'phone' => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back();
        }

        $user_id = Auth::id();
        $address = Address::create([
            'name' => $request->input('name'),
            'surname' => $request->input('surname'),
            'address' => $request->input('address'),
            'more_info' => $request->input('more_info'),
            'district' => $request->input('district'),
            'locality' => $request->input('locality'),
            'phone' => $request->input('phone'),
            'user_id' => $user_id
        ]);
        return redirect()->to('/account');
    }

    public function delete_address($id)
    {
        $address = Address::find($id);

        if (!$address) {
            return redirect()->back();
        }
           
        $address->update(['isDeleted' => true]);

        return redirect()->back();
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
        ]);
        $user_id = Auth::id();
        $user = User::find($user_id);
        if (!$user) {
            return redirect()->back();
        }
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Incorrect Password');
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        if($user->save()){
            Auth::logout();
            return redirect()->to('/login');
        }
        else{
            return redirect()->back();
        }
    }

    public function create_category(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:categories,name',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $category = Category::create($request->all());
            return redirect()->to('/admin/category')->with('success', 'Category created successfully');
    }

    public function update_category(Request $request, $id)
    {
            $category = Category::find($id);
            if (!$category) {
                return redirect()->back()->with('error', 'Category not found');
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:categories,name,'.$id,
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $category->update($request->all());
            return redirect()->to('/admin/category')->with('success', 'Category updated successfully');
    }
    
    public function delete_category($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found');
        }
        $category->delete();
        return redirect()->to('/admin/category')->with('success', 'Category deleted successfully');
    }

    public function create_subcategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $subcategory = new SubCategory();
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category;
        $subcategory->save();

        return redirect()->to('/admin/subcategory')->with('success', 'Subcategory created successfully');
    }

    public function update_subcategory(Request $request, $id)
    {
        $subcategory = SubCategory::find($id);
        if (!$subcategory) {
            return redirect()->back()->with('error', 'Subcategory not found');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'category' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $subcategory->update($request->all());
        return redirect()->to('/admin/subcategory')->with('success', 'Subcategory updated successfully');
    }
    
    public function delete_subcategory($id)
    {
        $subcategory = SubCategory::find($id);
        if (!$subcategory) {
            return redirect()->back()->with('error', 'Subcategory not found');
        }
        $subcategory->delete();
        return redirect()->to('/admin/subcategory')->with('success', 'Subcategory deleted successfully');
    }

    public function create_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'code' => 'required|string',
            'description' => 'nullable|string',
            'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'numeric'],
            'quantity' => 'required|integer|min:0',
            'subcategory' => 'required|exists:sub_categories,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $product = new Product();
        $product->name = $request->name;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->sub_category_id = $request->subcategory; 
        $product->save();
        return redirect()->to('/admin/product')->with('success', 'Product created successfully');
    }

    public function update_product(Request $request, $id)
    {
            $product = Product::find($id);
            if (!$product) {
                return redirect()->back()->with('error', 'Product not found');
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'code' => 'required|string',
                'description' => 'nullable|string',
                'price' => ['required', 'regex:/^\d+(\.\d{1,2})?$/', 'numeric'],
                'quantity' => 'required|integer|min:0',
                'subcategory' => 'required|exists:sub_categories,id',
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
            $product->update($request->all());
            return redirect()->to('/admin/product')->with('success', 'Product updated successfully');
    }
    
    public function delete_product($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
            
        }
        $product->delete();
        return redirect()->to('/admin/product')->with('success', 'Product deleted successfully');
    }

    public function delete_user($id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back();
        }
        if ($user->id === $id) {
            return redirect()->back();
            return redirect()->back()->with('error', 'You cannot delete your own account');
        }
        $deleted_user = User::find($id);
        if (!$deleted_user) {
            return redirect()->back();
        }
        $deleted_user->delete();
        return redirect()->to('/admin/user')->with('success', 'User deleted successfully');
    }

    public function block_user($id)
    {
        $message = "";
        $user = Auth::user();
        if (!$user) {
            return redirect()->back();
        }
        $change_user = User::find($id);
        if (!$change_user) {
            return redirect()->back()->with('error', 'User not found');
        }
        if($change_user->isBlocked==0){
            $change_user->isBlocked = 1;
            $message = "Blocked";
        }
        elseif($change_user->isBlocked==1){
            $change_user->isBlocked = 0;
            $message = "Unblocked";
        }
        $change_user->save();
        return redirect()->to('/admin/user')->with('success', "User $message successfully");
    }

    public function upload_image(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'imageable_type' => 'required|in:App\Models\Category,App\Models\SubCategory,App\Models\Product,App\Models\Carousel',
            'imageable_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $image_file = $request->file('image');
        $path_prefix = 'images/';

        switch ($request->input('imageable_type')) {
            case 'App\Models\Category':
                $path_prefix .= 'categories/';
                break;
            case 'App\Models\SubCategory':
                $path_prefix .= 'subcategories/';
                break;
            case 'App\Models\Product':
                $path_prefix .= 'products/';
                break;
            case 'App\Models\Carousel':
                $path_prefix .= 'carousels/';
                break;
            default:
                $path_prefix .= 'others/';
        }

        $full_path = $image_file->store($path_prefix, 'public');
        $file_name = basename($full_path);

        $image = Image::create([
            'path' => $file_name,
            'imageable_type' => $request->imageable_type,
            'imageable_id' => $request->imageable_id,
        ]);

        return redirect()->back()->with('success', 'Image Uploaded successfully');

    }

    public function delete_image($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return redirect()->back()->with('error', 'Image not found');
        }
        $file_path = "images/";
        switch ($image->imageable_type) {
            case 'App\Models\Category':
                $file_path .= 'categories/';
                break;
            case 'App\Models\SubCategory':
                $file_path .= 'subcategories/';
                break;
            case 'App\Models\Product':
                $file_path .= 'products/';
                break;
            case 'App\Models\Carousel':
                $file_path .= 'carousels/';
                break;
            default:
                $file_path .= 'others/';
        }
        $file_path .= $image->path;
        if (Storage::disk('public')->exists($file_path)) {
            Storage::disk('public')->delete($file_path);
        }
        $image->delete();
        return redirect()->back()->with('success', 'Image deleted successfully');
    }

    public function create_carousel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'carouselable_type' => 'required|in:App\Models\Category,App\Models\SubCategory,App\Models\Product',
            'carouselable_id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $carousel = Carousel::create([
            'carouselable_type' => $request->carouselable_type,
            'carouselable_id' => $request->carouselable_id,
        ]);
        $image_file = $request->file('image');
        $path_prefix = 'images/carousels';
        $full_path = $image_file->store($path_prefix, 'public');
        $file_name = basename($full_path);
        $image = Image::create([
            'path' => $file_name,
            'imageable_type' => 'App\Models\Carousel',
            'imageable_id' => $carousel->id,
        ]);
        return redirect()->back()->with('success', 'Carousel Uploaded successfully');
    }

    public function delete_carousel($id)
    {

        $carousel = Carousel::find($id);

        if (!$carousel) {
            return redirect()->back()->with('error', 'Carousel not found');
        }

        $image = $carousel->image;

        if ($image && Storage::disk('public')->exists('images/carousels/' . $image->path)) {
            Storage::disk('public')->delete('images/carousels/' . $image->path);
        }

        if ($image) {
            $image->delete();
        }

        $carousel->delete();
        return redirect()->back()->with('success', 'Carousel deleted successfully');
    }

    public function update_order_status(Request $request, $id){

        $order = Order::find($id);
        if(!$order){
            return redirect()->back()->with('error', 'Order not Found');
        }
        $status = $request->input('status');
        if (!in_array($status, ['pending', 'processing', 'completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Bad Request');
            
        }
        $order->status = $request->input('status');
        $order->save();
        return redirect()->back()->with('success', 'Order updated successfully');
    }

}
