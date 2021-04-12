<?php

use App\Http\Controllers\Admin\Answers;
use App\Http\Controllers\Admin\Contacts;
use App\Http\Controllers\Admin\Languages;
use App\Http\Controllers\Admin\Mailing;
use App\Http\Controllers\Admin\Moderators;
use App\Http\Controllers\Admin\Settings;
use App\Http\Controllers\Admin\Statistics;
use App\Http\Controllers\Admin\Users;
use App\Http\Controllers\Bot\RequestHandler;
use App\Http\Controllers\Developer\InfoController;
use App\Http\Controllers\Developer\Lang;
use App\Http\Controllers\Developer\Menu;
use App\Http\Controllers\Developer\MenuAdmin;
use App\Http\Controllers\Developer\Permissions;
use App\Http\Controllers\Developer\RequestJSON;
use App\Http\Controllers\Developer\Todo;
use App\Http\Controllers\Developer\Webhook;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Migrate;
use App\Http\Controllers\Pay;
use App\Http\Controllers\Payment;
use App\Http\Controllers\Seed;
use App\Http\Controllers\Send;
use App\Http\Controllers\Test;
use App\Http\Controllers\Admin\Locale;
use Illuminate\Support\Facades\Route;

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
    return view('/admin');
});

Route::get('/admin', function () {
    return view('admin');
})->middleware(['auth'])->name('admin');

require __DIR__.'/auth.php';

Route::get('test', [Test::class, 'index'])->name('test');

Route::get('migrate', [Migrate::class, 'index'])->name('migrate');
Route::get('migrate/rollback', [Migrate::class, 'rollback'])->name('migrate-rollback');
Route::get('seed', [Seed::class, 'index'])->name('seed');

Route::get('lacale/{language}', [Locale::class, 'index'])->name('locale');

Route::match(['get', 'post'], 'payment/qiwi/handler', [Payment::class, 'qiwiHandler']);
Route::match(['get', 'post'], 'payment/yandex/handler', [Payment::class, 'yandexHandler']);
Route::match(['get', 'post'], 'payment/webmoney/handler', [Payment::class, 'webmoneyHandler']);
Route::match(['get', 'post'], 'payment/paypal/handler', [Payment::class, 'paypalHandler']);

Route::match(['get', 'post'], 'payment/method/{messenger}/{id}/{amount?}/{purpose?}', [Pay::class, 'method']);
Route::match(['post'], 'payment/invoice', [Pay::class, 'invoice'])->name('payment-invoice');

Route::match(['get', 'post'], 'bot/index', [RequestHandler::class, 'index'])->name('bot-request-handler');
Route::get('bot/send/mailing', [Send::class, 'mailing']); // Рассылка (Каждые 2 минуты)

Route::match(['get', 'post'], 'pay/handler', [Payment::class, 'handler']);

Route::group(['middleware' => 'auth', 'prefix'=>'admin'], function() {
    Route::group(['middleware' => 'access:statistics'], function () {
        Route::get('/', [Statistics::class, 'index'])->name('statistics');
    });

    Route::group(['prefix' => '/mailing', 'middleware' => 'access:mailing'], function () {
        Route::get('/', [Mailing::class, 'index'])->name('mailing');
        Route::post('/send', [Mailing::class, 'send']);
        Route::post('/cancel', [Mailing::class, 'cancel']);
        Route::get('/analize', [Mailing::class, 'analize'])->name('mailing-analize');
        Route::get('/log', [Mailing::class, 'log']);
        Route::post('/mark-inactive-users', [Mailing::class, 'markInactiveUsers']);
    });

    Route::group(['prefix' => '/users', 'middleware' => 'access:users'], function () {
        Route::get('/', [Users::class, 'index'])->name('users');
        Route::get('/profile/{id}', [Users::class, 'profile'])->name('user-profile');
        Route::get('/search', [Users::class, 'createUrlSearch']);
        Route::get('/search/{str}', [Users::class, 'search']);
        Route::post('/access/', [Users::class, 'access'])->name('user-access');
        Route::post('/count/chat', [Users::class, 'countChat'])->name('user-count-chat');
        Route::post('/count/mailing', [Users::class, 'countMailing'])->name('user-count-mailing');
        Route::post('/send/message', [Users::class, 'sendMessage'])->name('user-send-message');
    });

    Route::group(['prefix' => 'languages', 'middleware' => 'access:languages'], function () {
        Route::get('/list', [Languages::class, 'list'])->name('languages-list');
        Route::get('/add', [Languages::class, 'add'])->name('languages-add');
        Route::post('/add/save', [Languages::class, 'addSave'])->name('languages-add-save');
        Route::post('/delete', [Languages::class, 'delete'])->name('languages-delete');
    });

    Route::group(['prefix' => 'contacts', 'middleware' => 'access:contacts'], function () {
        Route::get('/general', [Contacts::class, 'general'])->name('contacts-general');
        Route::get('/access', [Contacts::class, 'access'])->name('contacts-access');
        Route::get('/advertising', [Contacts::class, 'advertising'])->name('contacts-advertising');
        Route::get('/offers', [Contacts::class, 'offers'])->name('contacts-offers');
        Route::post('/answer', [Contacts::class, 'answer'])->name('contacts-answer');
        Route::post('/answer/send', [Contacts::class, 'answerSend'])->name('contacts-answer-send');
        Route::post('/delete', [Contacts::class, 'delete'])->name('contacts-delete');
        Route::post('/delete-check', [Contacts::class, 'deleteCheck'])->name('contacts-delete-check');
    });

    Route::group(['prefix' => 'answers', 'middleware' => 'access:answers'], function () {
        Route::get('/list', [Answers::class, 'list'])->name('answers');
        Route::get('/add', [Answers::class, 'add'])->name('answers-add');
        Route::post('/edit', [Answers::class, 'edit']);
        Route::post('/save', [Answers::class, 'save']);
        Route::post('/delete', [Answers::class, 'delete']);
    });

    Route::group(['prefix' => 'moderators', 'middleware' => 'access:moderators'], function () {
        Route::get('/permissions', [Moderators::class, 'permissions'])->name('moderators-permissions');
        Route::get('/add', [Moderators::class, 'add'])->name('moderators-add');
        Route::post('/add/save', [Moderators::class, 'addSave'])->name('moderators-save-add');
        Route::get('/', [Moderators::class, 'list'])->name('moderators-list');
        Route::post('/edit', [Moderators::class, 'edit'])->name('moderators-edit');
        Route::post('/delete', [Moderators::class, 'delete'])->name('moderators-delete');
        Route::post('/edit/save', [Moderators::class, 'editSave'])->name('moderators-save-edit');
        Route::post('/permissions/save', [Moderators::class, 'permissionsSave'])
            ->name('moderators-save-permissions');
    });

    Route::group(['prefix' => 'settings', 'middleware' => 'access:settings'], function () {
        Route::post('/', [Settings::class, 'admin']);
        Route::post('/save', [Settings::class, 'adminUpdate']);
        Route::get('/main', [Settings::class, 'main'])->name('settings-main');
        Route::get('/pages', [Settings::class, 'pages'])->name('settings-pages');
        Route::get('/buttons', [Settings::class, 'buttons'])->name('settings-buttons');
        Route::post('/main/save', [Settings::class, 'mainSave']);
        Route::post('/pages/edit', [Settings::class, 'pagesEdit']);
        Route::post('/pages/save', [Settings::class, 'pagesSave']);
        Route::post('/buttons/edit', [Settings::class, 'buttonsEdit']);
        Route::post('/buttons/save', [Settings::class, 'buttonsSave']);
        Route::post('/buttons/view/save', [Settings::class, 'buttonsViewSave'])->name('save-view-buttons');
        Route::get('/buttons/go/lang', [Settings::class, 'buttonsGoLang'])->name('buttons-go-lang');
        Route::get('/pages/go/lang', [Settings::class, 'pagesGoLang'])->name('pages-go-lang');
        Route::get('/pages/{lang}', [Settings::class, 'pages'])->name('settings-pages-lang');
        Route::get('/buttons/{lang}', [Settings::class, 'buttons'])->name('settings-buttons-lang');
    });

    Route::group(['prefix' => 'payment', 'middleware' => 'access:payment'], function () {
        Route::get('/qiwi', [\App\Http\Controllers\Admin\Payment::class, 'qiwi'])->name('admin-qiwi');
        Route::post('/qiwi/save', [\App\Http\Controllers\Admin\Payment::class, 'qiwiSave'])
            ->name('admin-qiwi-save');
        Route::get('/yandex', [\App\Http\Controllers\Admin\Payment::class, 'yandex'])
            ->name('admin-yandex');
        Route::post('/yandex/save', [\App\Http\Controllers\Admin\Payment::class, 'yandexSave'])
            ->name('admin-yandex-save');
        Route::get('/webmoney', [\App\Http\Controllers\Admin\Payment::class, 'webmoney'])
            ->name('admin-webmoney');
        Route::post('/webmoney/save', [\App\Http\Controllers\Admin\Payment::class, 'webmoneySave'])
            ->name('admin-webmoney-save');
        Route::get('/paypal', [\App\Http\Controllers\Admin\Payment::class, 'paypal'])
            ->name('admin-paypal');
        Route::post('/paypal/save', [\App\Http\Controllers\Admin\Payment::class, 'paypalSave'])
            ->name('admin-paypal-save');
    });
});

Route::group(['middleware' => 'auth', 'prefix'=>'developer'], function() {
    Route::get('/', [Todo::class, 'index']);

    Route::prefix('/settings')->group(function () {
        Route::get('/main', [\App\Http\Controllers\Developer\Settings::class, 'settingsMain']);
        Route::get('/pages', [\App\Http\Controllers\Developer\Settings::class, 'settingsPages']);
        Route::get('/buttons', [\App\Http\Controllers\Developer\Settings::class, 'settingsButtons']);
        Route::post('/main/add', [\App\Http\Controllers\Developer\Settings::class, 'settingsMainAdd']);
        Route::post('/main/delete', [\App\Http\Controllers\Developer\Settings::class, 'settingsMainDelete']);
        Route::post('/main/edit', [\App\Http\Controllers\Developer\Settings::class, 'settingsMainEdit']);
        Route::post('/main/save', [\App\Http\Controllers\Developer\Settings::class, 'settingsMainSave']);
        Route::post('/pages/add', [\App\Http\Controllers\Developer\Settings::class, 'settingsPagesAdd']);
        Route::post('/pages/delete', [\App\Http\Controllers\Developer\Settings::class, 'settingsPagesDelete']);
        Route::post('/pages/edit', [\App\Http\Controllers\Developer\Settings::class, 'settingsPagesEdit']);
        Route::post('/pages/save', [\App\Http\Controllers\Developer\Settings::class, 'settingsPagesSave']);
        Route::post('/buttons/add', [\App\Http\Controllers\Developer\Settings::class, 'settingsButtonsAdd']);
        Route::post('/buttons/delete', [\App\Http\Controllers\Developer\Settings::class, 'settingsButtonsDelete']);
        Route::post('/buttons/edit', [\App\Http\Controllers\Developer\Settings::class, 'settingsButtonsEdit']);
        Route::post('/buttons/save', [\App\Http\Controllers\Developer\Settings::class, 'settingsButtonsSave']);
    });

    Route::prefix('/payment')->group(function () {
        Route::get('/qiwi', [\App\Http\Controllers\Developer\Payment::class, 'qiwi'])->name('qiwi');
        Route::post('/qiwi/save', [\App\Http\Controllers\Developer\Payment::class, 'qiwiSave'])
            ->name('qiwi-save');
        Route::get('/yandex', [\App\Http\Controllers\Developer\Payment::class, 'yandex'])->name('yandex');
        Route::post('/yandex/save', [\App\Http\Controllers\Developer\Payment::class, 'yandexSave'])
            ->name('yandex-save');
        Route::get('/webmoney', [\App\Http\Controllers\Developer\Payment::class, 'webmoney'])
            ->name('webmoney');
        Route::post('/webmoney/save', [\App\Http\Controllers\Developer\Payment::class, 'webmoneySave'])
            ->name('webmoney-save');
        Route::get('/paypal', [\App\Http\Controllers\Developer\Payment::class, 'paypal'])->name('paypal');
        Route::post('/paypal/save', [\App\Http\Controllers\Developer\Payment::class, 'paypalSave'])
            ->name('paypal-save');
    });

    Route::prefix('/webhook')->group(function () {
        Route::get('/', [Webhook::class, 'index']);
        Route::post('/set', [Webhook::class, 'setWebhook']);
    });

    Route::prefix('/answers')->group(function () {
        Route::get('/', [\App\Http\Controllers\Developer\Answers::class, 'index'])->name('index-answers');
        Route::post('/edit', [\App\Http\Controllers\Developer\Answers::class, 'edit'])->name('edit-answer');
        Route::post('/save', [\App\Http\Controllers\Developer\Answers::class, 'save'])->name('save-answer');
        Route::post('/add', [\App\Http\Controllers\Developer\Answers::class, 'add'])->name('add-answer');
        Route::post('/delete', [\App\Http\Controllers\Developer\Answers::class, 'delete'])
            ->name('delete-answer');
    });

    Route::prefix('/permissions')->group(function () {
        Route::get('/', [Permissions::class, 'index'])->name('permissions');
        Route::post('/add', [Permissions::class, 'add'])->name('permission-add');
        Route::post('/delete', [Permissions::class, 'delete'])->name('permission-delete');
    });

    Route::prefix('/request')->group(function () {
        Route::get('/', [RequestJSON::class, 'index'])->name('request');
        Route::post('/send', [RequestJSON::class, 'send'])->name('send-request');
        Route::get('/send', [RequestJSON::class, 'send'])->name('send-request-get');
        Route::post('/get/response', [RequestJSON::class, 'getResponse'])->name('get-response');
    });

    Route::prefix('/menu')->group(function () {
        Route::get('/list', [Menu::class, 'list'])->name('menu-list');
        Route::get('/add', [Menu::class, 'add'])->name('menu-add');
        Route::match(['get', 'post'], '/save', [Menu::class, 'save'])->name('menu-save');
        Route::get('/get', [Menu::class, 'get'])->name('menu-get');
        Route::get('/delete', [Menu::class, 'delete'])->name('menu-delete');
        Route::get('/edit/save', [Menu::class, 'editSave'])->name('menu-edit-save');
        Route::get('/admin/add', [MenuAdmin::class, 'index'])->name('menu-admin-add');
        Route::post('/admin/save', [MenuAdmin::class, 'save'])->name('menu-admin-save');
        Route::get('/admin/list', [MenuAdmin::class, 'list'])->name('menu-admin-list');
        Route::post('/admin/edit', [MenuAdmin::class, 'edit'])->name('menu-admin-edit');
        Route::post('/admin/edit/save', [MenuAdmin::class, 'editSave'])->name('menu-admin-edit-save');
        Route::post('/admin/delete', [MenuAdmin::class, 'delete'])->name('menu-admin-delete');
    });

    Route::prefix('/lang')->group(function () {
        Route::get('/menu/list', [Lang::class, 'menuList'])->name('lang-menu-list');
        Route::get('/menu/add', [Lang::class, 'menuAdd'])->name('lang-menu-add');
        Route::post('/menu/add/save', [Lang::class, 'menuAddSave'])->name('lang-menu-add-save');
        Route::post('/menu/edit', [Lang::class, 'menuEdit'])->name('lang-menu-edit');
        Route::post('/menu/edit/save', [Lang::class, 'menuEditSave'])->name('lang-menu-edit-save');
        Route::post('/menu/delete', [Lang::class, 'menuDelete'])->name('lang-menu-delete');
        Route::get('/pages/list', [Lang::class, 'pagesList'])->name('lang-pages-list');
        Route::get('/pages/add', [Lang::class, 'pagesAdd'])->name('lang-pages-add');
        Route::post('/pages/add/save', [Lang::class, 'pagesAddSave'])->name('lang-pages-add-save');
        Route::post('/pages/edit', [Lang::class, 'pagesEdit'])->name('lang-pages-edit');
        Route::post('/pages/edit/save', [Lang::class, 'pagesEditSave'])->name('lang-pages-edit-save');
        Route::post('/pages/delete', [Lang::class, 'pagesDelete'])->name('lang-pages-delete');
    });

    Route::prefix('/todo')->group(function() {
        Route::get('/', [Todo::class, 'index'])->name('todo');
        Route::post('/add', [Todo::class, 'add'])->name('todo-add');
        Route::post('/to/make', [Todo::class, 'toMake'])->name('todo-to-make');
        Route::post('/to/work', [Todo::class, 'toWork'])->name('todo-to-work');
        Route::post('/to/performed', [Todo::class, 'toPerformed'])->name('todo-to-performed');
        Route::post('/delete', [Todo::class, 'delete'])->name('todo-delete');
    });

    Route::prefix('/info')->group(function() {
        Route::get('/', [InfoController::class, 'index'])->name('info');
        Route::post('/save', [InfoController::class, 'save'])->name('info-save');
    });

});

Route::get('logout', [LoginController::class, 'logout']);

Route::match(['get', 'post'], 'register', function() {
    return redirect('admin/');
});

Route::match(['get', 'post'], '/', function() {
    return redirect('/admin');
});

//TODO: Create an instance of the router class
//$router = app()->make('router');
