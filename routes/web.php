<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\API\Script\ScriptController;
use App\Http\Controllers\EncryptionController;
use App\Http\Controllers\API\Webhook\WebhookController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\VerifyRecaptcha;


require __DIR__.'/auth.php';



Route::get('/test-email/{email}', function ($email) {
    try {
        $details = [
            'subject' => 'Test Email',
            'body' => 'This is a test email from FireChill!'
        ];

        Mail::raw($details['body'], function($message) use ($details, $email) {
            $message->to($email) // Use the dynamic email parameter
                    ->subject($details['subject']);
        });

        return 'Test email sent successfully to ' . $email . '!';
    } catch (\Exception $e) {
        Log::error('Email send failed: ' . $e->getMessage());
        return 'Failed to send email: ' . $e->getMessage();
    }
});


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('logs/login', [AuthController::class, 'loginWeb']);
Route::get('/login-check', [AuthController::class, 'loginPage']);
Route::post('/authenticate', [AuthController::class, 'authUser'])->name('post.login.web');
Route::get('/terms-of-service', [ScriptController::class, 'termsOfServiceHtml'])->name('page.termsofservice');
Route::get('/privacy-policy', [ScriptController::class, 'privacyPolicyHtml'])->name('page.privacypolicy');
Route::get('/contact-us', [WebController::class, 'contactUs'])->name('page.contactus');
Route::post('/contact-us', [WebController::class, 'contactUsPost'])->name('post.contactus')->middleware('recaptcha');;
Route::get('/pages/rewards', [WebController::class, 'rewardsPage'])->name('page.rewards');

Route::get('/webhook-contract-created', [WebhookController::class, 'getWebhookContractRes']);
Route::post('/webhook-contract-created', [WebhookController::class, 'getWebhookContractRes']);

Route::get('/webhook-appointment-created', [WebhookController::class, 'getWebhookAppointmentCreatedRes']);
Route::post('/webhook-appointment-created', [WebhookController::class, 'getWebhookAppointmentCreatedRes']);

Route::get('/webhook-appointment-updated', [WebhookController::class, 'getWebhookAppointmentUpdatedRes']);
Route::post('/webhook-appointment-updated', [WebhookController::class, 'getWebhookAppointmentUpdatedRes']);

Route::middleware('auth')->group(function () {
    Route::middleware(['admin','verified'])->group(function () {
        Route::get('/logsDashboard', [ScriptController::class, 'logsDashboard'])->name('dash');
        Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index'])->name('logs');
    });
    Route::get('/logoutWeb', [ScriptController::class, 'logoutWeb'])->name('logout.web');
    Route::get('/dashboard', [WebController::class, 'dashboard'])->middleware(['verified'])->name('dashboard');
    Route::get('/profile/{setting?}', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','verify_web_auth'])->group(function () {
    Route::post('/client/book/appointment',[WebController::class, 'bookAppointment'])->name('client.book.appointment');
    Route::get('/client/booking/confirmation',[WebController::class, 'bookingConfirmation'])->name('client.book.confirmation');
    Route::get('/client/appointment/edit/{id}', [WebController::class, 'editAppointment'])->name('client.edit.appointment');
    Route::post('/client/appointment/update', [WebController::class, 'updateAppointment'])->name('client.update.appointment');
    Route::get('/client/booking/history', [WebController::class, 'bookingHistory'])->name('client.booking.history');

    Route::get('/client/available/slots', [WebController::class, 'availableSlots'])->name('client.slots.available');
    Route::get('/client/bookableitems', [WebController::class, 'getBookableItems'])->name('client.bookableitems');
    Route::get('/client/clientservicestatus', [WebController::class, 'getClientServiceStatus'])->name('client.service.status');

    Route::post('/sale/purchase/updatecontracts', [WebController::class, 'updateSalePurchaseContracts'])->name('sale.purchase.updatecontracts');

    Route::middleware(['decrypt.request'])->group(function () {
        Route::post('/sale/purchase/contracts', [WebController::class, 'salePurchaseContracts'])->name('sale.purchase.contracts');
        Route::post('/sale/checkoutshoppingcart', [WebController::class, 'checkoutShoppingCart'])->name('sale.checkoutshoppingcart');
        Route::post('/client/updateclientcard', [WebController::class, 'updateClientCard'])->name('client.update.card');
    });
    Route::post('/sale/checkoutshoppingcartFreeService', [WebController::class, 'checkoutShoppingCartFreeService'])->name('sale.checkoutshoppingcart.free.service');

    Route::get('/client/loyality', [WebController::class, 'getLoyalty'])->name('client.get.loyality');
    Route::get('/client/rewards', [WebController::class, 'getRewards'])->name('client.get.rewards');
    Route::get('/client/rewards/all', [WebController::class, 'rewardsPage'])->name('client.show.rewards');

    Route::get('/client/checkout', [WebController::class, 'checkout'])->name('client.checkout');
});

Route::middleware(['verify.guest'])->group(function(){
    Route::get('/guest/loyality', [WebController::class, 'getLoyalty'])->name('guest.get.loyality');
    Route::get('/guest/rewards', [WebController::class, 'getRewards'])->name('guest.get.rewards');

    Route::get('/guest/booking/confirmation',[WebController::class, 'guestBookingConfirmation'])->name('guest.book.confirmation');
    Route::post('/guest/book/appointment',[WebController::class, 'bookAppointment'])->name('guest.book.appointment');

    Route::get('/guest/available/slots', [WebController::class, 'availableSlots'])->name('guest.slots.available');
    Route::get('/guest/clientservicestatus', [WebController::class, 'getClientServiceStatus'])->name('guest.service.status');

    Route::middleware(['decrypt.request'])->group(function () {
        Route::post('/guest/purchase/contracts', [WebController::class, 'guestPurchaseContract'])->name('guest.purchase.contracts');
        Route::post('/guest/sale/checkoutshoppingcart', [WebController::class, 'guestCheckoutShoppingCart'])->name('guest.sale.checkoutshoppingcart');
    });
    // checkout shopping cart
    Route::post('/guest/sale/checkoutshoppingcartFreeService', [WebController::class, 'guestCheckoutShoppingCartFreeService'])->name('guest.sale.checkoutshoppingcart.free.service');

    Route::get('/guest/checkout', [WebController::class, 'checkout'])->name('guest.checkout');
});

