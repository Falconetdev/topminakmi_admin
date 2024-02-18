<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('ken',[ApiController::class, 'ken']);
Route::get('site',[ApiController::class, 'site']);
Route::get('shop',[ApiController::class, 'shop']);
Route::get('getshop/{pid}',[ApiController::class, 'getshop']);
Route::get('point/{uid}',[ApiController::class, 'point']);
Route::get('booking/{uid}',[ApiController::class, 'booking']);
Route::get('product/{uid}',[ApiController::class, 'product']);
Route::get('oneshopproduct/{uid}/{sid}',[ApiController::class, 'oneshopproduct']);
Route::get('userinfo/{uid}',[ApiController::class, 'userinfo']);
Route::get('login/{email}/{pass}',[ApiController::class, 'login']);
Route::get('get_user_id/{email}',[ApiController::class, 'get_user_id']);

//PRODUCT GOTOCHI
Route::get('pointproduct',[ApiController::class,'pointproduct']);



//point
Route::get('usepoint/{sid}',[ApiController::class, 'usepoint']);
Route::get('userpoint/{uid}',[ApiController::class, 'userpoint']);
Route::get('shopcode/{scode}',[ApiController::class, 'shopcode']);
Route::get('pointnotification/{uid}',[ApiController::class, 'pointnotification']);
Route::get('pointcheck/{sid}',[ApiController::class, 'pointcheck']);

//change
Route::get('changepass/{id}/{pass}',[ApiController::class, 'changepass']);
Route::get('changeuser/{id}/{username}',[ApiController::class, 'changeuser']);
Route::get('changeadrs/{id}/{zip}/{ken}/{address_1}/{address_2}/{phone}',[ApiController::class, 'changeadrs']);
Route::get('changeemail/{id}/{email}',[ApiController::class, 'changeemail']);

//user
Route::get('login/{email}/{pass}',[ApiController::class, 'login']);
Route::get('get_user_id/{email}',[ApiController::class, 'get_user_id']);
Route::get('get_user_data/{uid}',[ApiController::class, 'get_user_data']);
Route::get('add_app_user/{nameone}/{nametwo}/{zipcode}/{pref}/{address_2}/{address_1}/{phone}/{email}/{password}',[ApiController::class, 'add_user']);


//setting
Route::get('updatepass/{id}/{pass}',[ApiController::class, 'updatepass']);
Route::get('updatephone/{id}/{phone}',[ApiController::class, 'updatephone']);
Route::get('updateaddress/{id}/{zip}/{ken}/{address_1}/{address_2}/{phone}',[ApiController::class, 'changeadrs']);

//product
Route::get('shopproduct/{sid}',[ApiController::class, 'shopproduct']);
Route::get('getproduct/{pid}',[apicontroller::class, 'getproduct']);
// plan 
Route::get('plan/{pid}',[apicontroller::class, 'plan']);
//shop category
Route::get('category1/{sid}',[apicontroller::class, 'category1']);
Route::get('category2/{sid}',[apicontroller::class, 'category2']);
Route::get('category3/{sid}',[apicontroller::class, 'category3']);
Route::get('category4/{sid}',[apicontroller::class, 'category4']);
//yamaguchifarm category
Route::get('shopcategory',[apicontroller::class, 'shopcategory']);
Route::get('shopcategorytwo',[apicontroller::class, 'shopcategorytwo']);

// cart
Route::get('addtocart/{uid}/{product_id}/{qty}/{sid}',[apicontroller::class, 'addtocart']);
Route::get('cart/{uid}',[apicontroller::class, 'cart']);
Route::get('deletecart/{id}',[apicontroller::class, 'deletecart']);
Route::get('clearcard/{uid}/{sid}',[apicontroller::class, 'clearcard']);
Route::get('updatecart/{id}/{qty}',[apicontroller::class, 'updatecart']);
Route::get('cartsize/{uid}',[apicontroller::class, 'cartsize']);
Route::get('shopcard/{uid}/{sid}',[apicontroller::class, 'shopcard']);

//news
Route::get('shopnews/{sid}',[ApiController::class, 'shopnews']);

//booking date 
Route::get('bookingdate/{pid}',[ApiController::class, 'bookingdate']);

//Coupon 
Route::get('shopcoupon/{sid}/{uid}',[ApiController::class, 'shopcoupon']);


//web shop plan cate
Route::get('shopmstcategory/{sid}/{seosonid}',[ApiController::class, 'shopmstcategory']);
Route::get('shopmstcategoryview/{sid}/{cid}',[ApiController::class, 'shopmstcategoryview']);

//shop event
Route::get('shopevent/{sid}',[apicontroller::class, 'shopevent']);
Route::get('shopeventcate/{sid}/{cid}',[apicontroller::class, 'shopeventcate']);

//holiday
Route::get('holiday',[apicontroller::class, 'holiday']);

//stamp card
Route::get('addstamp/{sid}/{uid}/{scount}/{timestamp}',[apicontroller::class, 'addstamp']);

// view shop coupons without expire things
Route::get('viewshopcoupon/{coupon_id}', [ApiController::class, 'viewShopCoupon']);
// // view stamp 6 coupons 
// Route::get('viewstampcoupon/{stamp_count}/{uid}', [ApiController::class, 'viewStampCoupon']);
// // view stamp 12 coupons
// Route::get('viewstampcoupon12/{stamp_count}/{uid}', [ApiController::class, 'findTwelveStampCoupon']);

Route::get('viewstampcoupon/{stamp_count}/{uid}', [ApiController::class, 'viewStampCoupon']);

