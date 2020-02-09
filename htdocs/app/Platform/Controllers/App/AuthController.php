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

class AuthController extends \App\Http\Controllers\Controller
{
    /*
    |--------------------------------------------------------------------------
    | Authorization Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling authentication related
    | features like registration, login, logout and password reset.
    | It's designed for /api/ use with JSON responses.
    |
    */

    /**
     * Handle user registration.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function register(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'name' => 'required|min:2|max:32',
        'email' => 'required|email|max:64|unique:users',
        'password' => 'required|min:8|max:24',
        'terms' => 'accepted',
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $language = ($request->language !== null) ? $request->language : config('default.language');
      $timezone = ($request->timezone !== null) ? $request->timezone : config('default.timezone');
      $currency = config('default.currency');

      // Detect currency based on locale
      if (false !== setlocale(LC_ALL, $locale)) {
        $locale_info = localeconv();
        $currency = $locale_info['int_curr_symbol'];
      }

      $user = new User;

      $user->role = 2;
      $user->active = 1;
      $user->name = $request->name;
      $user->email = $request->email;
      $user->contact_email = $request->email;
      $user->password = bcrypt($request->password);
      $user->language = $language;
      $user->locale = $locale;
      $user->timezone = $timezone;
      $user->currency = $currency;
      $user->last_ip_address = request()->ip();
      $user->created_by = 1;

      $user->save();

      return response()->json(['status' => 'success'], 200);
    }
  
    /**
     * Handle user login.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function login(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'email' => 'required|email|max:64',
        'password' => 'required|min:6|max:24'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $remember = (bool) $request->get('remember', false);

      $credentials = $request->only('email', 'password');
      $credentials['active'] = 1;

      if ($token = $this->guard()->attempt($credentials, $remember)) {
        auth()->user()->logins = auth()->user()->logins + 1;
        auth()->user()->last_ip_address =  request()->ip();
        auth()->user()->last_login = Carbon::now('UTC');
        auth()->user()->save();

        return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
      }
      return response()->json(['error' => 'login_error'], 401);
    }
  
    /**
     * Handle impersonate login.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function impersonate(Request $request) {
      $uuid = request('uuid', null);
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      if (auth()->user()->role == 1) {
        $user = User::withoutGlobalScopes()->where('uuid', $uuid)->firstOrFail();
        if ($token = $this->guard()->login($user)) {
          return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }
      }

      return response()->json(['error' => 'login_error'], 401);
    }

    /**
     * Handle user logout.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function logout() {
      $this->guard()->logout();
      return response()->json([
        'status' => 'success'
      ], 200);
    }

    /**
     * Refresh authorization token.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function refresh() {
      try {
        $token = $this->guard()->refresh();
      }
      catch (\Exception $e) {
        return response()->json(['error' => 'refresh_token_error'], 401);
      }

      return response()
        ->json(['status' => 'successs'], 200)
        ->header('Authorization', $token);
    }

    /**
     * Send a password reset email.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function passwordReset(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'email' => 'required|email|max:64'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $user = User::withoutGlobalScopes()->where('email', $request->email)
        ->where('active', 1)
        ->first();

      if ($user !== null) {

        $token = Str::random(32);

        DB::table('password_resets')
          ->where('email', $user->email)
          ->delete();

        DB::table('password_resets')->insert(
          ['email' => $user->email, 'token' => $token, 'created_at' => Carbon::now('UTC')]
        );

        $email = new \stdClass;
        $email->locale = $locale;
        $email->to_name = $user->name;
        $email->to_email = $user->email;
        $email->subject = trans('app.reset_password_mail_subject');
        $email->body_top = trans('app.reset_password_mail_top');
        $email->cta_label = trans('app.reset_password_mail_cta');
        $email->cta_url = url('password/reset/' . $token);
        $email->body_bottom = trans('app.reset_password_mail_bottom');

        Mail::send(new \App\Mail\SendMail($email));

      } else {
        return response()->json([
          'status' => 'error',
          'error' => trans('passwords.user')
        ], 200);
      }

      return response()->json([
        'status' => 'success'
      ], 200);
    }

    /**
     * Validate reset password token.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function passwordResetValidateToken(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'token' => 'required|min:32|max:32'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $password_reset = DB::table('password_resets')
        ->select('email')
        ->where('token', $request->token)
        ->where('created_at', '>=', \Carbon\Carbon::now()->addHour(-24)->toDateTimeString())
        ->first();

      if ($password_reset !== null) {
        return response()->json([
          'status' => 'success'
        ], 200);
      } else {
        return response()->json([
          'status' => 'error',
          'error' => 'invalid_token'
        ], 200);
      }
    }

    /**
     * Update password.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function passwordUpdate(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      $v = Validator::make($request->all(), [
        'token' => 'required|min:32|max:32',
        'password' => 'required|min:8|max:24'
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      $password_reset = DB::table('password_resets')
        ->select('email')
        ->where('token', $request->token)
        ->where('created_at', '>=', \Carbon\Carbon::now()->addHour(-24)->toDateTimeString())
        ->first();

      if ($password_reset !== null) {

        DB::table('password_resets')->where('token', $request->token)->delete();

        $user = User::withoutGlobalScopes()->where('email', $password_reset->email)
          ->where('active', 1)
          ->first();

        if ($user !== null) {

          $user->password = bcrypt($request->password);
          $user->save();

          return response()->json([
            'status' => 'success'
          ], 200);
        }
      } else {
        return response()->json([
          'status' => 'error',
          'error' => 'invalid_token'
        ], 200);
      }
    }

    /**
     * Update profile.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function postUpdateProfile(Request $request) {
      $locale = request('locale', config('default.language'));
      app()->setLocale($locale);

      if (env('APP_DEMO', false) === true && (auth()->user()->id == 1 || auth()->user()->id == 2)) {
        return response()->json(['status' => 'error', 'error' => 'demo'], 422);
      }

      $v = Validator::make($request->all(), [
        /*'current_password' => 'required|min:8|max:24',*/
        'name' => 'required|min:2|max:64',
        'bio' => 'nullable|max:250',
        'date_of_birth' => 'nullable|date',
        'contact_email' => 'nullable|email|max:128',
        'contact_phone' => 'nullable|max:64',
        'address1' => 'nullable|max:200',
        'address2' => 'nullable|max:200',
        'address3' => 'nullable|max:200',
        'linkedin' => 'nullable|url|max:250',
        'languages' => 'nullable|max:500',
        'website' => 'nullable|url|max:250',
        'email' => ['required', 'email', 'max:64', Rule::unique('users')->where(function ($query) {
            return $query->where('id', '<>', auth()->user()->id);
          })
        ],
      ]);

      if ($v->fails()) {
        return response()->json([
          'status' => 'error',
          'errors' => $v->errors()
        ], 422);
      }

      // Verify password
      /*
      if (! Hash::check($request->current_password, auth()->user()->password)) {
        return response()->json([
          'status' => 'error',
          'errors' => ['current_password' => [trans('app.current_password_incorrect')]]
        ], 422);
      }
      */

      // All good, update profile
      auth()->user()->name = $request->name;
      auth()->user()->job_title = $request->job_title;
      auth()->user()->bio = $request->bio;
      auth()->user()->email = $request->email;
      auth()->user()->date_of_birth = $request->date_of_birth;
      auth()->user()->contact_email = $request->contact_email;
      auth()->user()->contact_phone = $request->contact_phone;
      auth()->user()->address1 = $request->address1;
      auth()->user()->address2 = $request->address2;
      auth()->user()->address3 = $request->address3;
      auth()->user()->linkedin = $request->linkedin;
      auth()->user()->languages = $request->languages;
      auth()->user()->website = $request->website;
      auth()->user()->linkedin = $request->linkedin;
      auth()->user()->timezone = $request->timezone;
      auth()->user()->locale = $request->locale;
      auth()->user()->language = explode('_', $request->locale)[0];

      // Update password
      if ($request->new_password !== null && $request->new_password != 'null') auth()->user()->password = bcrypt($request->new_password);

      auth()->user()->save();

      $tags = ($request->tags == '') ? [] : explode(',', $request->tags);
      auth()->user()->tags()->sync($tags);

      // Update avatar
      if (json_decode($request->avatar_media_changed) === true) {
        $file = $request->file('avatar');
        if ($file !== null) {
          auth()->user()
            ->addMedia($file)
            ->sanitizingFileName(function($fileName) {
              return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection('avatar', 'media');
        } else {
          auth()->user()
            ->clearMediaCollection('avatar');
        }
      }

      // Update cover
      if (json_decode($request->cover_media_changed) === true) {
        $file = $request->file('cover');
        if ($file !== null) {
          auth()->user()
            ->addMedia($file)
            ->sanitizingFileName(function($fileName) {
              return strtolower(str_replace(['#', '/', '\\', ' '], '-', $fileName));
            })
            ->toMediaCollection('cover', 'media');
        } else {
          auth()->user()
            ->clearMediaCollection('cover');
        }
      }

      return response()->json([
        'status' => 'success',
        'user' => $this->user(false)
      ], 200);
    }

    /**
     * Get user info.
     *
     * @return \Symfony\Component\HttpFoundation\Response 
     */
    public function user($returnResponse = true) {
      $user = User::withoutGlobalScopes()->where('id', Auth::user()->id)->where('active', 1)->firstOrFail();

      $return = [
        //'id' => (int) $user->id,
        'active' => (int) $user->active,
        'avatar' => $user->avatar,
        'name' => $user->name ?? '',
        'job_title' => $user->job_title ?? '',
        'bio' => $user->bio ?? '',
        'tags' => $user->tags()->pluck('tags.id'),
        'date_of_birth' => ($user->date_of_birth !== null) ? $user->date_of_birth->format('Y-m-d') : '',
        'contact_phone' => $user->contact_phone ?? '',
        'contact_email' => $user->contact_email ?? '',
        'address1' => $user->address1 ?? '',
        'address2' => $user->address2 ?? '',
        'address3' => $user->address3 ?? '',
        'linkedin' => $user->linkedin ?? '',
        'languages' => $user->languages ?? '',
        'website' => $user->website ?? '',
        'email' => $user->email,
        'role' => (int) $user->role,
        'language' => $user->language,
        'locale' => $user->locale,
        'timezone' => $user->timezone,
        'currency' => $user->currency
      ];

      if ($returnResponse) {
        return response()->json([
          'status' => 'success',
          'data' => $return
        ], 200);
      } else {
        return $return;
      }
    }

    /**
     * Get guard for logged in user.
     *
     * @return \Illuminate\Support\Facades\Auth 
     */
    private function guard() {
      return Auth::guard('api');
    }
}