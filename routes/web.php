<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\CustomerAddressController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\MerchantController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\FeaturedProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\GoogleDriveController;
use App\Models\Product;
use App\Models\ProductImg;
use App\Models\User;
use App\Models\Shop;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CustomerSupportController;
use App\Http\Controllers\ChatController;


// Home route for both guests and authenticated users
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/categories/index', [CategoryController::class, 'index'])->name('category.index');
Route::get('/categories', [CategoryController::class, 'showCategories'])->name('categories');
Route::get('/categories/{category_name}/products', [CategoryController::class, 'showProduct'])->name('category.product');
Route::get('/subcategory/{subcategory}/products', [CategoryController::class, 'showSubProducts'])->name('subcategory.products');
Route::get('/merchants', [StoreController::class, 'showMerchants'])->name('merchants.index');
Route::get('/merchant/{shopId}/viewstore', [StoreController::class, 'viewStore'])->name('merchant.viewstore');
Route::get('/merchant/partial/{nav}/{shopId}', [StoreController::class, 'getPartial'])->name('merchant.partial');

Route::get('/customer-support', [CustomerSupportController::class, 'landing'])->name('customer.support');
Route::get('/customer-support/search', [CustomerSupportController::class, 'search'])->name('customersupport.search');
Route::get('/customer-support/autocomplete', [CustomerSupportController::class, 'autocomplete'])->name('customersupport.autocomplete');
Route::get('/customer-support/suggestions', [CustomerSupportController::class, 'getSuggestions'])->name('customersupport.suggestions');

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/upload-picture', [ProfileController::class, 'uploadProfilePicture'])->name('profile.uploadPicture');
    Route::get('/my-profile', [ProfileController::class, 'edit'])->name('myprofile')->middleware('auth');
    Route::get('/addresses', [ProfileController::class, 'showAddresses'])->name('profile.addresses');
    Route::post('/save-address', [CustomerAddressController::class, 'store'])->name('save-address');
    Route::post('/save-gcash-number', [ProfileController::class, 'saveGcashNumber'])->name('save.gcash.number');
    Route::post('/change-password', [ProfileController::class, 'changePassword'])->name('change.password');
    Route::post('/profile/update-field', [ProfileController::class, 'updateField'])->name('profile.updateField');

    Route::get('/favorites', [FavoriteController::class, 'showFavorites'])->name('favorites.index');
    Route::post('/favorites/toggle/{productId}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/favorites/add/{productId}', [FavoriteController::class, 'add'])->name('favorites.add');
    Route::delete('/favorites/remove/{favoriteId}', [FavoriteController::class, 'remove'])->name('favorites.remove');
    Route::get('/favorites/count', [FavoriteController::class, 'count'])->name('favorites.count');

    // Admin-specific route group
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/dashboard/merchants', [AdminDashboardController::class, 'viewMerchants'])->name('admin.dashboard.merchants');
        Route::get('/dashboard/customers', [AdminDashboardController::class, 'viewCustomers'])->name('admin.dashboard.customers');
        Route::get('/dashboard/transactions', [AdminDashboardController::class, 'viewTransactions'])->name('admin.dashboard.transactions');
    });

    Route::get('/customersupport/store-guide', [CustomerSupportController::class, 'create'])->name('customersupport.store-guide');
    Route::post('/customersupport/store-guide', [CustomerSupportController::class, 'store'])->name('customersupport.store-guide.submit');
    Route::get('/inquiries', [ChatController::class, 'fetchInquiries']);
    Route::get('/merchantschat', [ChatController::class, 'fetchMerchants']);
    Route::get('/chat/messages/{chatId}', [ChatController::class, 'getChatMessages'])->name('chat.messages');
    Route::post('/chat/send-message', [ChatController::class, 'sendMessage'])->name('chat.sendmessages');
    Route::post('/chat/sendMessageToAdmin', [ChatController::class, 'sendMessage'])->name('chat.sendMessageToAdmin');
    Route::get('/chat/message/{customerId}', [ChatController::class, 'getMessagesByCustomer']);
    Route::post('/chat/send-messages', [ChatController::class, 'customertoadminmessage'])->name('chat.sendmessage');
    Route::post('/chat/start-messages', [ChatController::class, 'startcustomertoadminmessage'])->name('chat.startmessages');
    Route::post('/start-chat', [ChatController::class, 'startliveChat'])->name('chat.start');

    Route::post('/admin/applications/{id}/confirm', [AdminDashboardController::class, 'confirm'])->name('applications.confirm');
    Route::post('/admin/applications/{id}/decline', [AdminDashboardController::class, 'decline'])->name('applications.decline');

    // Cart Route
    Route::get('/cart', [CartController::class, 'showCart'])->name('cart.show');
    Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/view/add', [CartController::class, 'viewaddToCart'])->name('view.addtocart');
    Route::delete('/cart/remove/{cartId}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{cartId}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/cart-tooltip', [CartController::class, 'getCartTooltip'])->name('cart.tooltip');
    Route::get('/cart/count', [CartController::class, 'getCartItemCount'])->name('cart.count');
    Route::patch('/cart/update-variation/{cart_id}', [CartController::class, 'updateVariation'])->name('cart.update.variation');
    Route::delete('/cart/delete-selected', [CartController::class, 'removeSelected']);

    Route::post('/buy-now', [CartController::class, 'buyNow'])->name('cart.buyNow');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/save-order-address', [CheckoutController::class, 'saveOrderAddress'])->name('save-order-address');
    Route::post('/save-contact-number', [CheckoutController::class, 'saveContactNumber'])->name('save-contact-number');
    Route::post('/place-order', [OrderController::class, 'placeOrder'])->name('place-order');
    Route::get('/payment/{order}', [OrderController::class, 'showPayment'])->name('show-payment');
    Route::post('/upload-receipt/{order}', [OrderController::class, 'uploadReceipt'])->name('upload-receipt');
    Route::get('/show-payment/{order_id}', [PaymentController::class, 'showPayment'])->name('payment.show');
    Route::post('/order/cancel/{order_id}', [OrderController::class, 'cancelOrderCustomer'])->name('order.cancelpayment');

    Route::post('/confirm-gcash-payment', [PaymentController::class, 'confirmGcashPayment'])->name('confirmGcashPayment');
    Route::get('/order-count', [OrderController::class, 'getOrderCount']);
    Route::get('/orders-tooltip', [OrderController::class, 'getOrdersTooltip']);
    Route::get('/orders/{order_id}', [OrderController::class, 'show'])->name('orders.detail');
    Route::get('/orders/refund/{order_id}', [OrderController::class, 'showRefund'])->name('orders.refund');
    // Route::post('/confirm-payment', [OrderController::class, 'confirmPayment'])->name('confirm.payment');
    // Route::post('/decline-payment', [OrderController::class, 'declinePayment'])->name('decline.payment');
    Route::post('/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('update-payment-status');
    Route::post('/update-order-status', [OrderController::class, 'updateOrderStatus'])->name('update.order.status');
    Route::post('/index-update-order-status/{order_id}', [OrderController::class, 'indexUpdateOrderStatus'])->name('index.update.order.status');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/pending', [OrderController::class, 'index'])->name('orders.pending');
    Route::get('/orders/to-ship', [OrderController::class, 'index'])->name('orders.toShip');
    Route::get('/orders/completed', [OrderController::class, 'index'])->name('orders.completed');
    Route::get('/orders/canceled', [OrderController::class, 'index'])->name('orders.canceled');
    Route::post('/order/confirm-received/{order}', [OrderController::class, 'confirmReceived'])->name('order.confirm-received');
    Route::post('/order/done', [OrderController::class, 'markRefundCompleted'])->name('order.done');
    Route::get('/mypurchase', [PurchaseController::class, 'index'])->name('mypurchase');
    Route::post('/cancel-order', [OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/request-cancel-order', [OrderController::class, 'requestCancelOrder'])->name('order.requestCancel');

    Route::post('/order/verify', [OrderController::class, 'verify'])->name('order.verify');
    Route::post('/order/not-match', [OrderController::class, 'notMatch'])->name('order.notMatch');
    Route::get('/merchant/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::match(['get', 'post'], '/merchant/sales-report/pdf', [InventoryController::class, 'generateSalesReportPDF'])->name('sales_report.pdf');
    Route::get('/merchant/inventory/order-report', [InventoryController::class, 'getOrderReport'])->name('order-report');
    Route::match(['get', 'post'], '/order-report/pdf', [InventoryController::class, 'generateOrderReportPDF'])->name('order_report.pdf');

    Route::post('/update-stock', [InventoryController::class, 'updateStock'])->name('update.stock');

    Route::post('/update-contact-number', [CheckoutController::class, 'updateContactNumber'])->name('update.contact.number');


    // Merchant registration steps
    Route::post('/handle-second-step', [MerchantController::class, 'handleSecondStep'])->name('handleSecondStep');
    // Route to display the third step form (terms and conditions)
    Route::get('/merchant/thirdstep', [MerchantController::class, 'thirdStep'])->name('merchant.thirdstep');
    // Handle third step submission (terms acceptance and finalization)
    Route::post('/merchant/thirdstep', [MerchantController::class, 'handleThirdStep'])->name('handleThirdStep');
    // Success route after registration is completed
    Route::get('/merchant/success', [MerchantController::class, 'success'])->name('merchant.success');

    Route::get('/orders/rating/{rating}/{product_id}', [ProductReviewController::class, 'showRatingPage'])->name('show.rating.page');

    Route::post('/submit-review', [ProductReviewController::class, 'store'])->name('submit.review');

    


    Route::get('/mystore', [ShopController::class, 'showStore'])->name('mystore');
    Route::get('/merchant/secondstep', [MerchantController::class, 'secondStep'])->name('merchant.secondstep');
    Route::get('/merchant/my-profile', [MerchantController::class, 'myProfile'])->name('merchant.myProfile');
    Route::post('/merchant/update-contact-mop', [MerchantController::class, 'updateContactMop'])->name('merchant.updateContactMop');
    Route::post('/shop/update-images', [ShopController::class, 'updateImages'])->name('shop.updateImages');
    Route::get('/merchant/partial/{nav}', [ShopController::class, 'getPartial'])->name('merchant.partial');
    Route::get('/category/{category_name}', [ProductController::class, 'showByCategory'])->name('category.products');


    Route::post('/merchant/upload-picture', [MerchantController::class, 'uploadPicture'])->name('merchant.uploadPicture');

    // Product Ratings
    Route::get('/get-order-details/{orderId}', [OrderController::class, 'getOrderDetails']);

    // Define routes for merchant profile action
    Route::get('/merchant/edit-profile', [MerchantController::class, 'editProfile'])->name('merchant.editProfile');
    Route::patch('/merchant/profile/update', [MerchantController::class, 'updateProfile'])->name('merchant.updateProfile');
    Route::post('/merchant/save-gcash-details', [MerchantController::class, 'saveGcashDetails'])->name('save.gcash.details');
    Route::post('/merchant/enable-cod', [MerchantController::class, 'enableCod'])->name('enable.cod');
    Route::delete('/merchant/enable-cod', [MerchantController::class, 'disableCod'])->name('enable.cod');
    Route::patch('/merchant/update-contact', [MerchantController::class, 'updateContactNumber'])->name('merchant.updateContactNumber');
    Route::patch('/merchant/update-email', [MerchantController::class, 'updateEmail'])->name('merchant.updateEmail');
    Route::get('/inventory/add-product', [ProductController::class, 'inventorycreate'])->name('merchant.inventory.create');
    Route::post('/inventory/product', [ProductController::class, 'inventorystore'])->name('merchant.inventory.store');
    Route::get('/inventory/products/{id}/edit', [ProductController::class, 'inventoryedit'])->name('inventory.edit');
    Route::put('/inventory/products/{id}', [ProductController::class, 'inventoryupdate'])->name('inventory.update');
    Route::delete('/inventory/products/{id}', [ProductController::class, 'inventoryproductdestroy'])->name('inventory.destroy');

    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/merchant/product/{id}', [ProductController::class, 'viewProduct'])->name('product.view');
    Route::get('/merchant/productpreview/{id}', [ProductController::class, 'previewProduct'])->name('product.preview');

    Route::delete('/variations/{id}', [VariationController::class, 'destroy'])->name('variation.delete');
    Route::get('/merchant/customize', [MerchantController::class, 'customize'])->name('merchant.customize');
    Route::post('/shop/update-display-image', [MerchantController::class, 'updateDisplayImage'])->name('shop.updateDisplayImage');
    Route::post('/featured-product/store', [FeaturedProductController::class, 'store'])->name('featured-product.store');

    // Group routes under the "merchant" prefix
    Route::prefix('merchant')->group(function () {
        // Route for the "Add Product" page
        Route::get('/add-product', [ProductController::class, 'create'])->name('merchant.product.create');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/product', [ProductController::class, 'store'])->name('merchant.product.store');


    });


});
// Authentication routes
require __DIR__.'/auth.php';

// Registration routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register');
Route::get('/verify-email', [RegisterController::class, 'showVerificationForm'])->name('verification.notice');
Route::post('/verify-email', [RegisterController::class, 'verify'])->name('verification.verify');

// Forgot Password routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{otp}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ForgotPasswordController::class, 'reset'])->name('password.update');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('product.index');
Route::get('/fetch-region-data', [MapController::class, 'fetchRegionData'])->name('map.fetchRegionData'); // AJAX fetch route for region data
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');
Route::get('/products/filter/{category}/{region}', [ProductController::class, 'filterProducts']);


// Route to show the map
Route::get('/map/{regionAlias?}', [MapController::class, 'showMap'])->name('map');
Route::get('/maplanding/{region}', [MapController::class, 'mapLanding'])->name('maplanding');
Route::get('/region-details/{name}', [RegionController::class, 'show']);



// Order tracking routes
Route::get('/track', [OrderTrackingController::class, 'showTrackForm'])->name('order.track.form');
Route::post('/track', [OrderTrackingController::class, 'trackOrder'])->name('order.track');

Route::post('/gcash/payment', [PaymentController::class, 'createGcashPayment'])->name('gcash.payment');
Route::get('/payment/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::get('/payment/failed', [PaymentController::class, 'paymentFailed'])->name('payment.failed');

Route::get('/gcash/form', function () {
    return view('gcash_form');
});

Route::get('/merchant/register', function () {
    return view('auth.merchantregister'); // Adjust the path if necessary
})->name('merchant.register.form');

Route::get('/merchant/start-selling', function () {
    return view('merchant.startselling'); // Adjust the path to match the folder and file name
})->name('merchant.startselling');

Route::post('/merchant/register', [MerchantController::class, 'register'])->name('merchant.register');


Route::get('/try', function () {
    return view('try');
});



Route::get('/search/results', [SearchController::class, 'search'])->name('search.results');
Route::get('/search/suggestions', function (Illuminate\Http\Request $request) {
    $query = $request->query('query');

    // Fetch products with images
    $suggestions = Product::where('product_name', 'like', '%' . $query . '%')
                          ->take(5) // Limit to 5 suggestions
                          ->get(['product_id', 'product_name']);

    // Map over products to include image URLs
    $suggestions->map(function ($product) {
        // Fetch the first image from the product_img table
        $productImage = ProductImg::where('product_id', $product->product_id)->first();
        $product->image_url = $productImage && $productImage->product_img_path1
            ? Storage::url($productImage->product_img_path1)
            : null; // Use null if no image is found

        return $product;
    });

    return response()->json($suggestions);
});
