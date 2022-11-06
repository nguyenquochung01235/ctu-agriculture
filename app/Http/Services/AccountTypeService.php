<?php

namespace App\Http\Services;

use App\Models\Account;

class AccountTypeService{
    
    public function getAllAccountType(){
        return Account::get(['code', 'name']);
    }

}