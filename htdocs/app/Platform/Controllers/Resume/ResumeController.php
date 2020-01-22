<?php

namespace Platform\Controllers\Resume;

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

class ResumeController extends \App\Http\Controllers\Controller
{
  /*
  |--------------------------------------------------------------------------
  | Resume Controller
  |--------------------------------------------------------------------------
  */

  /**
   * Get resume pdf.
   *
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function getDownloadResumePdf(Request $request) {
    //\Log::debug($request->headers);

    $locale = request('locale', config('default.language'));
    $locale = substr($locale, 0, 2);
    app()->setLocale($locale);

    $sizes = ['letter', 'A4'];
    $size = request('size', 'letter');
    if (! in_array($size, $sizes)) $size = 'letter';

    //$user = \App\User::whereId(2)->first();
    $user = auth()->user();
    $resume = $user->getResume();
    $footer = view('pdf.resume-footer', compact('user', 'resume'))->render();

    //$file = storage_path('app/resume-' . $user->id . '.pdf');
    //if (\File::exists($file)) \File::delete($file);

    $pdf = \PDF::loadView('pdf.resume', compact('user', 'resume'))
      ->setOrientation('portrait')
      ->setOption('page-size', $size)
      ->setOption('margin-top', 18)
      ->setOption('margin-right', 18)
      ->setOption('margin-left', 18)
      ->setOption('margin-bottom', 18)
      ->setOption('footer-html', $footer)
      ->setOption('footer-spacing', 5)
      ->stream();

    return $pdf;
    //return response()->download($file, null, [], null);
    //return response()->json(['status' => 'success'], 200);
  }

  /**
   * Get user resume.
   *
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function getUserResume(Request $request) {
    $uuid = $request->uuid;
    if ($uuid == null) {
      $user = auth()->user();
    } else {
      $user = \App\User::whereUuid($uuid)->first();
    }

    $response = $user->getResume();
    return response()->json($response, 200);
  }

  /**
   * Get tags.
   *
   * @return \Symfony\Component\HttpFoundation\Response 
   */
  public function getTags(Request $request) {
    $tags = \Platform\Models\Tag::where('created_by', auth()->user()->id)->orderBy('name')->get()->map(function ($record, $key) {
      return [
        'pk' => $record->id,
        'val' => $record->name
      ];
    });

    return response()->json($tags, 200);
  }
}
