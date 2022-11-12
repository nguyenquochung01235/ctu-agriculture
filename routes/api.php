<?php

use App\Http\Controllers\Client\LoginController;
use App\Http\Controllers\Client\MenuClientController;
use App\Http\Controllers\Client\UserController;
use App\Http\Controllers\AccountTypeController;
use App\Http\Controllers\Client\CategoryVatTuController;
use App\Http\Controllers\Client\DanhMucQuyDinhController;
use App\Http\Controllers\Client\GiongLuaController;
use App\Http\Controllers\Client\HoatDongMuaVuController;
use App\Http\Controllers\Client\HopDongMuaBanController;
use App\Http\Controllers\Client\HopTacXaController;
use App\Http\Controllers\Client\LichMuaVuController;
use App\Http\Controllers\Client\NhatKyDongRuongController;
use App\Http\Controllers\Client\NotificationController;
use App\Http\Controllers\Client\ThuaDatController;
use App\Http\Controllers\Client\ThuongLaiController;
use App\Http\Controllers\Client\XaVienController;
use App\Http\Controllers\UploadImageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::post("/thuadat/update/{id_thuadat}",[ThuaDatController::class, 'updateThuaDat']);


Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::middleware(['auth.jwt'])->group(function(){
        Route::get('/me', [LoginController::class, 'me']);
        Route::get('/logout',[LoginController::class, 'logout']);

        Route::get('/notification', [NotificationController::class, 'getNotification']);
        Route::put('/notification/make-read/{id}', [NotificationController::class, 'makeReadNotification']);
        Route::get('/notification/is-read-all', [NotificationController::class, 'isReadAllNotify']);


        Route::prefix("user")->group(function(){
            Route::get("/detail", [UserController::class, 'getDetailUser']);
            Route::post("/update",[UserController::class, 'updateUser']);
            Route::post("/change/password",[UserController::class, 'updatePassword']);
        }); 

        Route::prefix("auto-complete")->group(function(){
            Route::get("gionglua/get-list",[GiongLuaController::class, 'getListGiongLua']);
            Route::get("danhmucquydinh/get-list", [DanhMucQuyDinhController::class, 'getListDanhMucQuyDinh']);
        }); 

        Route::prefix("htx")->group(function(){
            Route::get("/dash-board",[HopTacXaController::class, 'infoDashBoard']);
            Route::get("/get-detail",[HopTacXaController::class, 'getDetail']);
            Route::get("/search",[HopTacXaController::class, 'searchHopTacXaByPhoneNumber']);
            Route::get("/chunhiem/{id_hoptacxa}",[HopTacXaController::class, 'getChuNhiemHTX']);
            Route::post('/create', [HopTacXaController::class, 'createNewHTX']);
            Route::post('/update', [HopTacXaController::class, 'updateHTX']);
            Route::post('/add-new-member', [HopTacXaController::class, 'addNewMemberToHTX']);
            Route::delete('/delete-member/{id}', [HopTacXaController::class, 'deleteMemberToHTX']);
            Route::put('/update-active/{id_user}', [HopTacXaController::class, 'toggleActiveMemberHTX']);
        }); 
        
        Route::prefix("xavien")->group(function(){
            Route::get("/get-detail",[XaVienController::class, 'getDetail']);
            Route::get("/get-detail/{id_user}",[XaVienController::class, 'getDetailByHTX']);
            Route::get('/role', [XaVienController::class, 'getRoleXaVien']);
            Route::get("/get-list-xavien",[XaVienController::class, 'getListXaVienOfHTX']);
            Route::post('/search-by-phone-number', [XaVienController::class, 'searchXaVienByPhoneNumber']);
            Route::post('/update', [XaVienController::class, 'updateXaVien']);
        }); 

        Route::prefix("lichmuavu")->group(function(){
            Route::post("/create",[LichMuaVuController::class, 'createLichMuaVu']);
            Route::get("/get-list-for-hdmb/{id_hoptacxa}",[LichMuaVuController::class, 'getListLichMuaVuForHopDongMuaBan']);
            Route::get("/get-detail/{id_lichmuavu}",[LichMuaVuController::class, 'getDetailLichMuaVu']);
            Route::get("/get-list",[LichMuaVuController::class, 'getListLichMuaVu']);
            Route::put("/update/{id_lichmuavu}",[LichMuaVuController::class, 'updateLichMuaVu']);
            Route::delete("/delete/{id_lichmuavu}",[LichMuaVuController::class, 'deleteLichMuaVu']);
        }); 
        
        Route::prefix("hoatdongmuavu")->group(function(){
            Route::get("/get-detail/{id_hoatdongmuavu}",[HoatDongMuaVuController::class, 'getDetailHoatDongMuaVu']);
            Route::get("/get-list", [HoatDongMuaVuController::class, 'getListHoatDongMuaVu']);
            Route::post("/create",[HoatDongMuaVuController::class, 'createHoatDongMuaVu']);
            Route::put("/update/{id_hoatdongmuavu}",[HoatDongMuaVuController::class, 'updateHoatDongMuaVu']);
            Route::delete("/delete/{id}",[HoatDongMuaVuController::class, 'deleteHoatDongMuaVu']);
        }); 
        
        Route::prefix("nhatkydongruong")->group(function(){
            Route::get("/get-detail/{id_nhatkydongruong}",[NhatKyDongRuongController::class, 'getDetailNhatKyDongRuong']);
            Route::get("/get-list/{id_lichmuavu}", [NhatKyDongRuongController::class, 'getListNhatKyDongRuong']);
            Route::post("/attach",[NhatKyDongRuongController::class, 'attachHoatDongIntoNhatKyFromHopTacXaToXaVien']);
            Route::put("/make-done/{id_nhatkydongruong}", [NhatKyDongRuongController::class, 'toggleActiveNhatKyDongRuong']);
            Route::post("/create",[NhatKyDongRuongController::class, 'addNewNhatKyHoatDong']);
            Route::delete("/delete/{id}",[NhatKyDongRuongController::class, 'deleteNhatKyHoatDong']);
            Route::get("get-list/all/{id_lichmuavu}", [NhatKyDongRuongController::class, 'getListNhatKyDongRuongForHTX']);
            Route::put("/htx-accept/{id_nhatkydongruong}", [NhatKyDongRuongController::class, 'acceptNhatKyDongRuong']);
            
        }); 

        Route::prefix("gionglua")->group(function(){
            Route::get("/get-list-gionglua",[GiongLuaController::class, 'getListGiongLua']);
        }); 

        Route::prefix("thuadat")->group(function(){
            Route::post("/create",[ThuaDatController::class, 'createThuaDat']);
            Route::post("/update/{id_thuadat}",[ThuaDatController::class, 'updateThuaDat']);
            Route::get("/get-list",[ThuaDatController::class, 'getListThuaDat']);
            Route::get("/get-detail/{id_thuadat}",[ThuaDatController::class, 'getDetailThuaDat']);
            Route::get("/get-list/all",[ThuaDatController::class, 'getAllListThuaDat']);
            Route::put("/active/{id_thuadat}",[ThuaDatController::class, 'activeThuaDat']);
            Route::delete("/delete/{id_thuadat}",[ThuaDatController::class, 'deleteThuaDat']);
        }); 

        Route::prefix("danhmucquydinh")->group(function(){
            Route::post("/create",[DanhMucQuyDinhController::class, 'createDanhMucQuyDinh']);
            Route::get("/get-detail/{id_danhmucquydinh}",[DanhMucQuyDinhController::class, 'getDetailDanhMucQuyDinh']);
            Route::get("/get-list", [DanhMucQuyDinhController::class, 'getListDanhMucQuyDinh']);
            Route::put("/update/{id_danhmucquydinh}",[DanhMucQuyDinhController::class, 'updateDanhMucQuyDinh']);
            Route::delete("/delete/{id_danhmucquydinh}",[DanhMucQuyDinhController::class, 'deleteDanhMucQuyDinh']);
        });

        Route::prefix("category-vattu")->group(function(){
            Route::post("/create",[CategoryVatTuController::class, 'createCategoryVatTu']);
            Route::get("/get-detail/{id_category_vattu}",[CategoryVatTuController::class, 'getDetailCategoryVatTu']);
            Route::get("/get-list", [CategoryVatTuController::class, 'getListCategoryVatTu']);
            Route::put("/update/{id_category_vattu}",[CategoryVatTuController::class, 'updateCategoryVatTu']);
            Route::delete("/delete/{id_category_vattu}",[CategoryVatTuController::class, 'deleteCategoryVatTu']);
        }); 

        Route::prefix("thuonglai")->group(function(){
            Route::get("/dash-board",[ThuongLaiController::class, 'infoDashBoard']);
            Route::get("/get-detail",[ThuongLaiController::class, 'getDetailThuongLai']);
            Route::post('/create-hopdong', [ThuongLaiController::class, 'thuongLaiCreateHopDongMuaBan']);
            Route::post('/update', [ThuongLaiController::class, 'updateThuongLai']);
        }); 

        Route::prefix("hopdongmuaban")->group(function(){
            Route::get("/get-detail/{id_hopdongmuaban}",[HopDongMuaBanController::class, 'getDetailHopDong']);
            Route::get("/get-list",[HopDongMuaBanController::class, 'getListHopDong']);
            Route::put("/confirm/{id_hopdongmuaban}",[HopDongMuaBanController::class, 'confirmHopDong']);
            Route::put("/update/{id_hopdongmuaban}",[HopDongMuaBanController::class, 'updateHopDong']);
            Route::delete("/delete/{id_hopdongmuaban}",[HopDongMuaBanController::class, 'deleteHopDong']);
        }); 


});


Route::post('/upload/image', [UploadImageController::class, 'uploadImage']);


Route::get('/get/menu-client', [MenuClientController::class, 'getMenuClient']);

Route::prefix("service")->group(function(){
    // Get List Role Of User
    Route::get('/get/account-type', [AccountTypeController::class, 'getAllAccountType']);
    // Create New User
    Route::post('/create/user', [UserController::class, 'createNewUser']);
}); 

