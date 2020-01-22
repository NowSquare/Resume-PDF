<?php 

namespace Platform\Controllers\App;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class AppController extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Single Page Application Controller for Web App
   |--------------------------------------------------------------------------
   |
   | App logic
   |--------------------------------------------------------------------------
   */

  /**
   * Main app page
   *
   * @return \Illuminate\View\View
   */
  public function index() {
    // Parse social media
    $social = config('default.social');
    $socialMedia = [];

    foreach ($social as $media) {
        if ($media['url'] !== '') {
            $socialMedia[] = $media;
        }
    }

    $init = [
        'config' => [
            'root' => '',
            'schemeAndHost' => request()->getSchemeAndHttpHost(),
            'language' => config('default.language'),
            'ga' => config('default.google_analytics')
        ],
        'app' => [
            'name' => config('default.app_name'),
            'logo' => config('default.app_logo'),
            'social' => $socialMedia
        ]
    ];

    if (env('APP_DEMO', false)) $init['config']['demo'] = true;

    $init = json_encode($init);

    return view('index', compact('init'));
  }
}