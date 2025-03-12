<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\Sales\SalesController;
use App\Http\Controllers\API\Appointment\AppointmentController;
use App\Http\Controllers\API\Client\ClientController;
use App\Http\Controllers\API\Webhook\WebhookController;
use App\Http\Controllers\API\Script\ScriptController;
use App\Http\Controllers\EncryptionController;

// Public routes (no auth middleware applied)
Route::prefix('v1')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('post.login');
    Route::post('/register', [AuthController::class, 'register'])->name('post.register');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('post.forgot.password');
    Route::post('/verify-otp', [AuthController::class, 'verifyOTP']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('post.reset.password');
    Route::post('/email-verification', [AuthController::class, 'emailVerification'])->name('post.email.verification');
    Route::post('/resend-login-otp', [AuthController::class, 'resendLoginOTP'])->name('resend.login.otp');
    // delete account

});

Route::prefix('v1')->middleware(['auth:api', 'verify_token'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/client/saveDeviceToken', [AuthController::class, 'updateClientDeviceToken']);
    Route::post('/client/updateclientcard', [AuthController::class, 'updateClientCard'])->middleware('decrypt.request');
    Route::post('/client/updateClientProfile', [AuthController::class, 'updateClientProfile']);
    // Sale functions
    Route::post('/sale/purchase/updatecontracts', [SalesController::class, 'updateSalePurchaseContracts']);
    // appointment
    Route::post('/appointment/updateappointment', [AppointmentController::class, 'updateAppointment']);
    //  client
    Route::get('client/clientcompleteinfo', [ClientController::class, 'getClientCompleteInfo']);
    Route::get('client/clientschedule', [ClientController::class, 'getClientSchedule']);
    Route::get('client/clientvisits', [ClientController::class, 'getClientVisits']);
    Route::get('client/clientVitality', [ClientController::class, 'getClientVitality']);
    Route::get('/client/clientupgradeableservice', [ClientController::class, 'getClientUpgradeableService']); // boookings

    Route::post('/delete-account-send-otp', [AuthController::class, 'deleteAccountSendOTP'])->name('post.delete.account.send.otp');
    Route::post('/delete-account-verify-otp', [AuthController::class, 'verifyOTP'])->name('post.delete.account.verify.otp');
    Route::post('/delete-account-permanently', [AuthController::class, 'deleteUserPermanently'])->name('post.delete.user.account');
});

Route::prefix('v1')->group(function () {
    Route::get('/get-social-links', [ScriptController::class, 'getSocialLinks']);
    Route::get('/getLoyality', [ScriptController::class, 'getLoyalty']);
    Route::get('/reward/getRewards', [ScriptController::class, 'getRewards']);
    Route::get('/guest-checkout-token', [AuthController::class, 'guestCheckoutToken'])->name('post.guest.checkout.token');
    Route::post('/appointment/bookableitems', [AppointmentController::class, 'getAppBookableItems']);
    Route::post('/client/clientservicestatus', [ClientController::class, 'getClientServiceStatus']); // boookings
    Route::post('/guest-checkout', [AuthController::class, 'guestCheckout'])->name('post.guest.checkout'); // check weather it is available or not
    Route::post('/appointment/addappointment', [AppointmentController::class, 'bookAppointment']);
    Route::post('/send-notify', [ScriptController::class, 'sendNotification']);
    Route::post('/make-notify', [ScriptController::class, 'makeNotification']);
    Route::get('/footer-content', [ScriptController::class, 'footerContent']);
    Route::get('/check-version', [ScriptController::class, 'checkVersion']);
    Route::post('/contact-us', [ScriptController::class, 'contactUs']);
    Route::get('/terms-of-service', [ScriptController::class, 'termsOfService']);
    Route::get('/privacy-policy', [ScriptController::class, 'privacyPolicy']);
    Route::get('/active-webhook', [WebhookController::class, 'activeWebhook']);
    // Route::post('/webhook-response', [WebhookController::class, 'getWebhookRes']);

    Route::middleware(['decrypt.request'])->group(function () {
        Route::post('/sale/checkoutshoppingcart', [SalesController::class, 'checkoutshoppingcart']);
        Route::post('/sale/guestCheckoutshoppingcart', [SalesController::class, 'guestCheckoutshoppingcart']);
        Route::post('/sale/purchase/contracts', [SalesController::class, 'salePurchaseContracts']);
    });
    Route::post('/sale/checkoutshoppingcartFreeService', [SalesController::class, 'checkoutshoppingcartFreeService']);
    Route::get('/custom-messages', [ScriptController::class, 'allCustomMessages']);
});

Route::post('/submit-form', [EncryptionController::class, 'decryptFormData'])->name('decrypt.form');

Route::get('/test', function(){
    dd("test");
});
