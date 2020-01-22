<?php 

namespace Platform\Controllers\Core;

use App\Http\Controllers\Controller;
use Carbon\Carbon;

class Content extends \App\Http\Controllers\Controller {

  /*
   |--------------------------------------------------------------------------
   | Content Controller
   |--------------------------------------------------------------------------
   |
   | Gets and parses translation files
   |--------------------------------------------------------------------------
   */

  /**
   * Get  available languages
   *
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function getAvailableLanguages() {
    $dir_path = resource_path() . '/lang';
    $dir = new \DirectoryIterator($dir_path);
    $response = [];
    foreach ($dir as $fileinfo) {
      if (! $fileinfo->isDot()) {
        // Check if campaign translations exist
        $lang = $fileinfo->getFilename();
        if (strlen($lang) == '2' && \File::exists($dir_path . '/' . $lang . '/app.php')) {
          $file = include($dir_path . '/' . $lang . '/app.php');
          $response[] = [
            'title' => $file['language_title'],
            'abbr' => $file['language_abbr'],
            'code' => $lang
          ];
        }
      }
    }

    return response()->json($response, 200);
  }

  /**
   * Get translation
   *
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function getTranslation($lang) {
    app()->setLocale($lang);

    // Replace variables
    $vars = [
      'app_name' => config('default.app_name'),
      'app_url' => config('default.app_url'),
      'app_contact_email' => config('default.app_contact_email'),
      'year' => date('Y')
    ];

    // Replace translation variables recursive
    $translation = $this->replaceTranslationVars(trans('app'), $vars);

    return response()->json($translation, 200);
  }

  /**
   * Nested translation
   *
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function replaceTranslationVars($translation, $vars) {
    $response = [];
    foreach ($translation as $key => $val) {
      if (is_array($val)) {
        foreach ($val as $k => $v) {
          $response[$key][$k] = trans('app.' . $key . '.' . $k, $vars);
        }
      } else {
        $response[$key] = trans('app.' . $key, $vars);
      }
    }
    return $response;
  }
}