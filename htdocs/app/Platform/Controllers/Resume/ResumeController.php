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
