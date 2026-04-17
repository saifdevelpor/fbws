<?php

use App\Http\Controllers\Admin\DonationAdminController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DamageController;
use App\Http\Controllers\DashBoardController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupportRequestController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Website\BecomePartController;
use App\Http\Controllers\WebSiteController;
use App\Http\Controllers\WelfareFundController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/');
});

// Account


Route::get('/', [WebSiteController::class, 'index'])->name('website.index');
Route::get('/about', [WebSiteController::class, 'about'])->name('website.about');
Route::get('/item', [WebSiteController::class, 'item'])->name('website.item');
Route::get('/team', [WebSiteController::class, 'team'])->name('website.team');
Route::get('/donate', [WebSiteController::class, 'donate'])->name('website.donate');
Route::get('/payment-page', [WebSiteController::class, 'paymentPage'])->name('website.payment');
Route::get('/monthly-report', [WebSiteController::class, 'monthlyReport'])->name('website.monthly-report');
Route::get('/contact', [WebSiteController::class, 'contact'])->name('website.contact');
Route::post('/contact-submit', [WebSiteController::class, 'submit'])->name('website.contact.submit');
Route::get('/event', [WebSiteController::class, 'social'])->name('website.event');
Route::get('/become-part', [WebSiteController::class, 'show'])->name('website.become-part');
Route::post('/become-part', [WebSiteController::class, 'submitForm'])->name('website.become-part.submit');
Route::get('/my-account', [WebSiteController::class, 'account'])->name('website.profile');
Route::get('/gallery-page', [WebSiteController::class, 'gallery'])->name('website.gallery');
Route::get('/help-center', [WebSiteController::class, 'helpCenter'])->name('website.help-center');
Route::get('/role', [WebSiteController::class, 'role'])->name('website.role');
Route::get('/member-card/verify/{id}', [UserController::class, 'membershipCardVerify'])->name('account.membership-card.verify');
Route::view('/privacy', 'legal.privacy')->name('privacy.policy');
Route::view('/terms', 'legal.terms')->name('terms.page');
Route::view('/conditions', 'legal.conditions')->name('conditions.page');
// Backward compatibility
Route::view('/condition', 'legal.conditions')->name('terms.conditions');

Auth::routes();

// Login history
Route::middleware('auth')->group(function () {
    Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login-history.index');
    Route::get('/admin/login-history', [LoginHistoryController::class, 'admin'])->name('login-history.admin');
    Route::get('/account/login-devices', [LoginHistoryController::class, 'devices'])->name('account.devices');
    Route::post('/account/logout-all-devices', [LoginHistoryController::class, 'logoutAllDevices'])->name('account.devices.logout-all');
    Route::get('/account/change-password', [ChangePasswordController::class, 'edit'])->name('password.edit');
    Route::post('/account/change-password', [ChangePasswordController::class, 'update'])->name('password.change');
    Route::get('/account/e-id-card', [UserController::class, 'membershipCard'])->name('account.membership-card');
    Route::get('/account/e-id-card/{id}', [UserController::class, 'membershipCardForUser'])->name('account.membership-card.user');
    Route::get('/reports/monthly', [ReportController::class, 'index'])->name('reports.monthly');
    Route::get('/reports/monthly/data', [ReportController::class, 'data'])->name('reports.monthly.data');
});


// Dashboard
Route::get('/dashboard', [DashBoardController::class, 'index'])->name('dashboard');

// Language
Route::get('lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'ur'])) {
        session(['locale' => $lang]);
    }
    return back();
})->name('lang.switch');


// User management
Route::get('/user', [UserController::class, 'index'])->name('user-management');
Route::post('/user-save', [UserController::class, 'save'])->name('user-save');
Route::post('/user-update/{id}', [UserController::class, 'update'])->name('user-update');
Route::delete('/user-delete/{id}', [UserController::class, 'delete'])->name('user-delete');

// Profile
Route::get('/profile', [UserController::class, 'profile'])->name('profile');
Route::put('/profile/update', [UserController::class, 'profileUpdate'])->name('profile.update');
Route::get('/profile/{id}', [UserController::class, 'showProfile'])->name('profile.show');

// Items
Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::post('/items', [ItemController::class, 'store'])->name('items.store');
Route::put('/items/{item}', [ItemController::class, 'update'])->name('items.update');
Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

// Payments
Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
Route::post('/payments/save', [PaymentController::class, 'save'])->name('payments.save');
Route::post('/payments/update/{id}', [PaymentController::class, 'update'])->name('payments.update');
Route::delete('/payments/delete/{id}', [PaymentController::class, 'delete'])->name('payments.delete');
Route::get('/payments/history', [PaymentController::class, 'history'])->name('payments.history');
Route::get('/payments/receipt/{id}', [PaymentController::class, 'printReceipt'])->name('payments.print');

// Welfare Fund
Route::get('/welfare', [WelfareFundController::class, 'index'])->name('welfare.index');
Route::post('/welfare/add-income', [WelfareFundController::class, 'addIncome'])->name('welfare.addIncome');
Route::post('/welfare/add-expense', [WelfareFundController::class, 'addExpense'])->name('welfare.addExpense');
Route::get('/welfare/history', [WelfareFundController::class, 'history'])->name('welfare.history');


// Develivery
Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries.index');
Route::get('/deliveries/create', [DeliveryController::class, 'create'])->name('deliveries.create');
Route::post('/deliveries', [DeliveryController::class, 'store'])->name('deliveries.store');
Route::get('/deliveries/{id}/edit', [DeliveryController::class, 'edit'])->name('deliveries.edit');
Route::put('/deliveries/{id}', [DeliveryController::class, 'update'])->name('deliveries.update');
Route::delete('/deliveries/{id}', [DeliveryController::class, 'destroy'])->name('deliveries.destroy');
Route::get('/deliveries/{id}/print', [DeliveryController::class, 'print'])->name('deliveries.print');

// Orders (user request module)
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
Route::post('/orders/{id}/whatsapp-admin', [OrderController::class, 'sendWhatsAppToAdmin'])->name('orders.whatsapp-admin');
Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');
Route::get('/orders/{id}/print', [OrderController::class, 'print'])->name('orders.print');


// Event
Route::get('/events', [EventController::class, 'index'])->name('event-list');
Route::post('/events/save', [EventController::class, 'save'])->name('event-save');
Route::post('/events/update/{id}', [EventController::class, 'update'])->name('event-update');
Route::delete('/events/delete/{id}', [EventController::class, 'delete'])->name('event-delete');

// Complaints
Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
Route::get('/my-complaints', [ComplaintController::class, 'myComplaints'])->name('complaints.mine');
Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
Route::get('/admin/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
Route::patch('/admin/complaints/{complaint}', [ComplaintController::class, 'update'])->name('admin.complaints.update');

// Support Requests
Route::get('/support-requests/create', [SupportRequestController::class, 'create'])->name('support-requests.create');
Route::post('/support-requests', [SupportRequestController::class, 'store'])->name('support-requests.store');
Route::get('/my-support-requests', [SupportRequestController::class, 'mine'])->name('support-requests.mine');
Route::get('/admin/support-requests', [SupportRequestController::class, 'index'])->name('admin.support-requests.index');
Route::get('/admin/support-requests/{supportRequest}', [SupportRequestController::class, 'show'])->name('admin.support-requests.show');
Route::patch('/admin/support-requests/{supportRequest}', [SupportRequestController::class, 'update'])->name('admin.support-requests.update');

// Demages
Route::get('/damages', [DamageController::class, 'index'])->name('damages-list');
Route::post('/damages/save', [DamageController::class, 'store'])->name('damage-save');
Route::post('/damages/update/{id}', [DamageController::class, 'update'])->name('damage-update');
Route::delete('/damages/delete/{id}', [DamageController::class, 'delete'])->name('damage-delete');
Route::delete('/damages/delete-group', [DamageController::class, 'deleteGroup'])->name('damage-delete-group');
Route::get('/damages/print', [DamageController::class, 'print'])->name('damage-print');

// Gallery
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');
Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
Route::put('/gallery/{image}', [GalleryController::class, 'update'])->name('gallery.update');
Route::delete('/gallery/{image}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

// Leads (admin only inside controller)
Route::get('/leads', [LeadController::class, 'index'])->name('leads.index');
Route::patch('/leads/{id}', [LeadController::class, 'update'])->name('leads.update');
Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->name('leads.destroy');

// Audit Log
Route::get('/admin/audit-logs', [AuditLogController::class, 'index'])->name('audit.logs');
Route::delete('/audit-logs/delete', [AuditLogController::class, 'bulkDelete'])->name('audit.logs.delete');


