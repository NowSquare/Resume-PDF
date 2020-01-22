<?php

namespace Platform\Controllers\App;

use App\User;
use App\Http\Controllers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AdminController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    */

    /**
     * Get admin stats.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function getStats(Request $request) {
        $stats = auth()->user()->getAdminStats();
        return response()->json($stats, 200);
    }

    /**
     * Get branding data.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function getBranding(Request $request) {
        $user = auth()->user();

        $branding = [
          'payment_provider' => config('general.payment_provider'),
          'payment_test_mode' => config('general.payment_test_mode'),
          'app_name' => $user->app_name,
          'app_contact' => $user->app_contact,
          'app_mail_name_from' => $user->app_mail_name_from,
          'app_mail_address_from' => $user->app_mail_address_from,
          'app_host' => $user->app_host,
          'account_host' => config('general.cname_domain')
        ];

        return response()->json($branding, 200);
    }

    /**
     * Update branding.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function postUpdateBranding(Request $request) {
        if (auth()->user()->app_demo == 1) return;

        if (env('APP_DEMO', false) === true && (auth()->user()->id == 1 || auth()->user()->id == 2)) {
          return;
        }

        return response()->json([
            'status' => 'success'
        ], 200);
    }
}

