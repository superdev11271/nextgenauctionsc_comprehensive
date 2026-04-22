<?php

/*
|--------------------------------------------------------------------------
| Auction Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\AuctionAttributeController;
use App\Http\Controllers\AuctionProductController;
use App\Http\Controllers\AuctionProductBidController;
use App\Http\Controllers\BidderChatController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SellerChatController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\XeroController;

// Any
Route::post('/xero-webhook',[XeroController::class, 'xeroWebHookEndPoint'])->name('xero.webhook');
Route::get('/testpage-fire-event',[TestController::class, 'testpage_event']);
Route::get('/test',[TestController::class, 'createXeroToken']);
Route::get('/test/run_cron_jobs',[TestController::class, 'runAllCronJobs']);
Route::get('/test/clear-cache',[TestController::class, 'runAllCronJobs']);

Route::post('auction/get_attributes_by_subcategory', [AuctionAttributeController::class,'get_attributes_by_subcategory'])->name('auction.get_attributes_by_subcategory');
Route::get('/accept-bid/{bid}',[AuctionProductBidController::class, 'acceptBidOffer'])->name('accept.bid');
Route::get('/reject-bid/{bid}',[AuctionProductBidController::class, 'rejectBidOffer'])->name('reject.bid');
Route::post('/notify-bidders/{product}',[AuctionProductBidController::class, 'notifyBidders'])->name('notify.bidders');

Route::post('chat-updates/{product:slug}',[SellerChatController::class, 'getUpdatesAjax'])->name('seller.chat.updates');
Route::get('chat/{product:slug}/{currentbid}',[SellerChatController::class, 'index'])->name('seller.chat');
Route::post('chat/store',[SellerChatController::class, 'store'])->name('seller.chat.store');
Route::post('get_chats/{bid}',[SellerChatController::class, 'getChatHistory'])->name('chat.history');

Route::post('customer/chat/store',[BidderChatController::class, 'store'])->name('customer.chat.store');
Route::post('customer/chat/history/{bid}',[SellerChatController::class, 'getChatHistory'])->name('chat.history');

Route::get('/check-auction-number', [AuctionProductController::class, 'checkAuctionNumber'])->name('check.auction.number');
// Route::group(['prefix' => 'admin', 'middleware' => ['auth','verified']], function () {
// });

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin']], function () {
    // Auction product lists
    Route::controller(AuctionAttributeController::class)->group(function () {
        // Attribute Creation pages
        Route::get('auction/auction-attributes', 'auction_attribues')->name('auction.attibutes');
        Route::post('auction/add-drop-down-value', 'addDropDownValue')->name('auction.add.ddvalue');
        Route::get('auction/auction_attribute_delete/{attribute}', 'destroy')->name('auction.attribute.delete');
        Route::post('auction/store-auction-attribute', 'store')->name('auction.attibute.store');
        Route::get('auction/edit-auction-attribute/{attribute}', 'edit')->name('auction.attibute.edit');
        Route::post('auction/update-auction-attribute/{attribute}', 'update')->name('auction.attibute.update');

        // To list on category selection
        Route::post('auction/show_category_attributes', 'show_category_attributes')->name('auction.showcategory.attributes');
    });

    Route::controller(AuctionProductController::class)->group(function () {
        Route::get('auction/all-products', 'all_auction_product_list')->name('auction.all_products');
        Route::get('auction/inhouse-products', 'inhouse_auction_products')->name('auction.inhouse_products');
        Route::get('auction/seller-products', 'seller_auction_products')->name('auction.seller_products');

        Route::get('/auction-product/create', 'product_create_admin')->name('auction_product_create.admin');
        Route::post('/auction-product/store', 'product_store_admin')->name('auction_product_store.admin');
        Route::get('/auction_products/edit/{id}', 'product_edit_admin')->name('auction_product_edit.admin');
        Route::post('/auction_products/update/{id}', 'product_update_admin')->name('auction_product_update.admin');
        Route::get('/auction_products/destroy/{id}', 'product_destroy_admin')->name('auction_product_destroy.admin');

        // Sales
        Route::get('/auction_products-orders', 'admin_auction_product_orders')->name('auction_products_orders');

        // Auction Bid List
        Route::get('/auction_bid_product_admin', 'auction_bid_product_admin')->name('auction_bid_product_admin');

        // Relist Auction Product
        Route::get('relist-products/{id}', 'relist_auction_product_form')->name('auction.relist_auction');
        // Route::get('bulk-relist-products/{id}', 'bulk_relist_auction_product_form')->name('auction.bulk_relist_auction');
        Route::post('relist-products', 'relist_product_store')->name('auction.relist_auction_store');

        Route::post('bulk-relist-products', 'bulk_relist_auction_product_form')->name('auction.bulk_relist_auction_form');
        Route::post('bulk-relist-auction-store', 'bulk_relist_auction_store')->name('auction.bulk_relist_auction_store');


        // Move to Marketplace Auction Product
        Route::get('move_to_marketplace-form/{id}', 'move_to_marketplace_form')->name('auction.move_to_marketplace_form');
        Route::post('move_to_marketplace-store', 'move_to_marketplace_form_store')->name('auction.move_to_marketplace_store');

        Route::get('/auction_products/reclaimed/{id}', 'product_reclaim')->name('auction_product_reclaimed.admin');

        Route::get('/auction_product_collection', 'checkAuctionNumber')->name('auction_product_collection');


    });

    Route::controller(AuctionProductBidController::class)->group(function () {
        Route::get('/product-bids/{id}', 'product_bids_admin')->name('product_bids.admin');
        Route::get('/product-bids/destroy/{id}', 'bid_destroy_admin')->name('product_bids_destroy.admin');
    });
});

Route::group(['prefix' => 'seller', 'middleware' => ['seller', 'verified', 'user']], function () {
    Route::controller(AuctionProductController::class)->group(function () {
        Route::get('/auction_products', 'auction_product_list_seller')->name('auction_products.seller.index');
        Route::get('/current_bid_product_seller', 'auction_bid_product_seller')->name('current_bid.seller');

        Route::get('/auction-product/create', 'product_create_seller')->name('auction_product_create.seller');
        Route::post('/auction-product/store', 'product_store_seller')->name('auction_product_store.seller');
        Route::get('/auction_products/edit/{id}', 'product_edit_seller')->name('auction_product_edit.seller');
        Route::post('/auction_products/update/{id}', 'product_update_seller')->name('auction_product_update.seller');
        Route::get('/auction_products/destroy/{id}', 'product_destroy_seller')->name('auction_product_destroy.seller');

        Route::get('/auction_products-orders', 'seller_auction_product_orders')->name('auction_products_orders.seller');

        Route::get('/auction_products/reclaimed/{id}', 'product_reclaim')->name('auction_product_reclaimed.seller');

         // Relist Auction Product
         Route::get('relist-products/{id}', 'seller_relist_auction_product_form')->name('auction.seller_relist_auction');
         Route::post('relist-products', 'seller_relist_product_store')->name('auction.seller_relist_auction_store');

        // Move to Marketplace Auction Product
        Route::get('seller_move_to_marketplace-form/{id}', 'move_to_marketplace_form_seller')->name('auction.seller_move_to_marketplace_form');
        Route::post('seller_move_to_marketplace-store', 'seller_move_to_marketplace_form_store')->name('auction.seller_move_to_marketplace_store');



    });
    Route::controller(AuctionProductBidController::class)->group(function () {
        Route::get('/product-bids/{id}', 'product_bids_seller')->name('product_bids.seller');
        Route::get('/product-bids/destroy/{id}', 'bid_destroy_seller')->name('product_bids_destroy.seller');
    });
});

Route::group(['middleware' => ['auth']], function () {
    // Route::resource('auction_product_bids', AuctionProductBidController::class);
    Route::post('auction_product_bids', [AuctionProductBidController::class,"validate_and_store"])->name("auction_product_bids.store");
    Route::post('cancel_autobid/{product}', [AuctionProductBidController::class,"cancelAutobid"])->name("auction_product_bids.cancel");

    Route::post('/auction/cart/show-cart-modal', [CartController::class, 'showCartModalAuction'])->name('auction.cart.showCartModal');
    Route::get('/auction/purchase_history', [AuctionProductController::class, 'purchase_history_user'])->name('auction_product.purchase_history');
    Route::get('/auction/get-highest-bid/{product}', [AuctionProductController::class, 'get_highest_bid'])->name('get_highest_bid');
});

Route::post('/home/section/auction_products', [HomeController::class, 'load_auction_products_section'])->name('home.section.auction_products');

Route::controller(AuctionProductController::class)->group(function () {
    Route::get('/auction-product/{slug}', 'auction_product_details')->name('auction-product');
    Route::get('/auction-products', 'all_auction_products')->name('auction_products.all');
    Route::get('/upcoming-auction-products', 'all_upcoming_auction_products')->name('auction_products.upcoming');
    Route::get('/auction-products/collection', 'auctionCollection')->name('auction_collection');
    Route::get('/upcoming-auction-products/collection', 'upcomingAuctionCollection')->name('upcoming_auction_collection');
});

Route::get('/auction-collection-products/{auction_number}', [SearchController::class, 'listingByCollectionOfAuction'])->name('auction_collection_products.all');
