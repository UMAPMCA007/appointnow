<?php

namespace App\Http\Controllers\Business;

use App\Enums\GuardType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->guard(GuardType::BUSINESS)->user();
        if ($user->business()->exists()) {
            $staffMembers = $user->business->staffMembers->count();
            return view('business.home.index', compact('staffMembers'));
        }
        return redirect()->route('business.business.create');
    }
}
