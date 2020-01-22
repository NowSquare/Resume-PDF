<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Translation\HasLocalePreference;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Carbon\Carbon;
use ShortUUID\ShortUUID;
use CommerceGuys\Intl\Currency\CurrencyRepository;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class User extends Authenticatable implements JWTSubject, HasLocalePreference, HasMedia
{
    use Notifiable;
    use HasMediaTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * Append programmatically added columns.
     *
     * @var array
     */
    protected $appends = [
      'avatar', 'cover', 'plan_limitations'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'expires_at' => 'datetime',
        'date_of_birth' => 'date',
    ];
    
    /**
     * Get plan limiations.
     *
     * @return string
     */
    public function getPlanLimitationsAttribute() {
      if ($this->premium == 1) {
        return [
          'tags' => config('default.max_items_premium'),
          'experience' => config('default.max_items_premium'),
          'projects' => config('default.max_items_premium'),
        ];
      } else {
        return [
          'tags' => config('default.max_items'),
          'experience' => config('default.max_items'),
          'projects' => config('default.max_items'),
        ];
      }
    }

    public function getJWTIdentifier() {
      return $this->getKey();
    }

    public function getJWTCustomClaims() {
      return [];
    }

    public function registerMediaCollections() {
      $this
        ->addMediaCollection('avatar')
        ->singleFile();

      $this
        ->addMediaCollection('cover')
        ->singleFile();
    }

    public function registerMediaConversions(Media $media = null) {
        $this
          ->addMediaConversion('avatar')
          ->width(512)
          ->height(512)
          ->performOnCollections('avatar');

        $this
          ->addMediaConversion('cover')
          ->width(1280)
          ->height(800)
          ->performOnCollections('cover');
    }

    /**
     * Get the user's preferred locale.
     *
     * @return string
     */
    public function preferredLocale() {
        return $this->language;
    }

    public static function boot() {
      parent::boot();

      // On select
      static::retrieved(function ($model) {
      });

      // On update
      static::updating(function ($model) {
        if (auth()->check()) {
          $model->updated_by = auth()->user()->id;
        }
      });

      // On create
      self::creating(function ($model) {
        // Generate and set UUID
        $uuid = Str::orderedUuid();
        $model->uuid = (string) $uuid;

        // Short UUID
        $su = new ShortUUID();
        $model->slug = Str::slug(substr($su->encode($uuid), 0, 12));

        if (auth()->check()) {
          $model->created_by = auth()->user()->id;
        }
      });
    }

    /**
     * Get avatar
     *
     * @return string for use in <img> src
     */
    public function getAvatarAttribute() {
      if ($this->getFirstMediaUrl('avatar') !== '') {
        $media = $this->getMedia('avatar');
        return $media[0]->getFullUrl();
      } else {
        return (string) \Avatar::create(strtoupper($this->name))->toBase64();
      }
    }

    /**
     * Get cover
     *
     * @return string for use in <img> src
     */
    public function getCoverAttribute() {
      if ($this->getFirstMediaUrl('cover') !== '') {
        $media = $this->getMedia('cover');
        return $media[0]->getFullUrl();
      } else {
        return null;
        //return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
      }
    }

    /**
     * Money formatting
     */
    public function formatMoney($amount, $currency = 'USD', $formatHtml = false) {
      if ($currency == null) $currency = 'USD';
      $value = Money::{$currency}($amount);
      $currencies = new \Money\Currencies\ISOCurrencies();

      $numberFormatter = new \NumberFormatter($this->getLanguage(), \NumberFormatter::CURRENCY);
      $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

      $amount = $moneyFormatter->format($value);
      if ($formatHtml) {
        $amount = explode($numberFormatter->getSymbol(0), $amount);
        $amount = $amount[0] . '<span class="cents">' . $numberFormatter->getSymbol(0) . $amount[1] . '</span>';
      }

      return $amount;
    }

    /**
     * Date / time formatting
     */
    public function formatDate($date, $format = 'date_medium') {
      if ($date !== null) {
        switch ($format) {
          case 'date_medium': $date = $date->timezone($this->getTimezone())->format('d-m-y'); break;
          case 'datetime_medium': $date = $date->timezone($this->getTimezone())->format('d-m-y H:i'); break;
          case 'friendly': $date = $date->timezone($this->getTimezone())->diffForHumans(); break;
        }
        return $date;
      } else {
        return null;
      }
    }

    /**
     * User language
     */
    public function getLanguage() {
      if ($this->language === NULL) {
        return config('default.language');
      } else {
        return $this->language;
      }
    }

    /**
     * User locale
     */
    public function getLocale() {
      if ($this->locale === NULL) {
        return config('default.locale');
      } else {
        return $this->locale;
      }
    }

    /**
     * User timezone
     */
    public function getTimezone() {
      if ($this->timezone === NULL) {
        return config('default.timezone');
      } else {
        return $this->timezone;
      }
    }

    /**
     * User currency
     */
    public function getCurrency() {
      if ($this->currency === NULL) {
        return config('default.currency');
      } else {
        return $this->currency;
      }
    }

    /**
     * Currency decimal points
     */
    public function getCurrencyFormat($get = null) {
      $currencyRepository = new CurrencyRepository;
      $currency = $currencyRepository->get($this->getCurrency());

      $format = [
          'numeric_code' => $currency->getNumericCode(),
          'fraction_digits' => $currency->getFractionDigits(),
          'name' => $currency->getName(),
          'symbol' => $currency->getSymbol(),
          'locale' => $currency->getLocale()
      ];

      return ($get === null) ? $format : $format[$get];
    }

    /**
     * The headers for the data table, per role
     *
     * @return array
     */
    public static function getHeaders() {
      $admin = [
        ['visible' => true, 'value' => 'avatar', 'exclude_from_select' => true, 'width' => '60px', 'text' => trans('app.avatar'), 'align' => 'left', 'sortable' => false],
        ['visible' => true, 'value' => 'name', 'text' => trans('app.name'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'email', 'text' => trans('app.email'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'logins', 'type' => 'number', 'text' => trans('app.logins'), 'align' => 'right', 'sortable' => true],
        ['visible' => true, 'value' => 'last_login', 'type' => 'date_time', 'format' => 'ago', 'text' => trans('app.last_login'), 'align' => 'left', 'sortable' => true, 'default_order' => true],
        ['visible' => true, 'value' => 'active', 'text' => trans('app.active'), 'align' => 'center', 'sortable' => true, 'type' => 'boolean'],
        ['visible' => true, 'value' => 'premium', 'text' => trans('app.premium'), 'align' => 'center', 'sortable' => true, 'type' => 'boolean']
      ];
      $user = $admin;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Form for creating a new record, per role
     *
     * @return array
     */
    public static function getCreateForm() {
      $admin = [
        'tab1' => [
          'text' => trans('app.account'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'text', 'column' => 'name', 'text' => trans('app.name'), 'validate' => 'required|max:32', 'required' => true],
                ['type' => 'email', 'column' => 'email', 'text' => trans('app.email_address'), 'validate' => 'required|email|max:64', 'required' => true],
                ['type' => 'image', 'class' => 'round', 'image' => ['thumb_max_width' => '140px', 'thumb_max_height' => '140px'], 'column' => 'avatar', 'text' => trans('app.avatar'), 'validate' => 'nullable', 'icon' => 'mdi-paperclip'],
                ['type' => 'boolean', 'default' => true, 'column' => 'active', 'text' => trans('app.active'), 'validate' => 'nullable'],
                ['type' => 'boolean', 'default' => false, 'column' => 'premium', 'text' => trans('app.premium'), 'validate' => 'nullable', 'hint' => trans('app.premium_hint', ['max_items_premium' => config('default.max_items_premium'), 'max_items' => config('default.max_items')])]
              ]
            ]
          ]
        ]
      ];
      $user = [];

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Export class name if table can be exported (optional)
     *
     * @return string
     */
    public static function getExportClass() {
      $admin = 'Platform\Exports\UsersExport';
      $user = null;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Extra columns used in select queries, exposed in json response
     *
     * @return array
     */
    public static function getExtraSelectColumns() {
      $admin = ['uuid'];
      $user = $admin;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Extra columns used in select queries, hidden from json response
     *
     * @return array
     */
    public static function getExtraQueryColumns() {
      $admin = ['id', 'created_by'];
      $user = $admin;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Generic settings
     *
     * actions: add actions column (true / false)
     *
     * @return array
     */
    public static function getSettings() {
      $admin = ['select_all' => true, 'actions' => true, 'create' => false, 'actions_width' => '105px'];
      $user = $admin;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Language variables
     *
     * @return array
     */
    public static function getTranslations() {
      return [
        'items' => trans('app.users'),
        'edit_item' => trans('app.edit_user'),
        'create_item' => trans('app.create_user'),
      ];
    }

    /**
     * Define per role if and what they can see
     *
     * all: all records from all accounts
     * account: all records from the current account
     * personal: only records the current user has created
     * created_by: only records created by the user id defined like created_by:1
     * none: this role has no permission
     *
     * @return array
     */
    public static function getPermissions() {
      $admin = ['view' => 'personal', 'delete' => 'personal', 'update' => 'personal', 'create' => true];
      $user = ['view' => 'personal', 'delete' => 'none', 'update' => 'personal', 'create' => false];

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * The columns used for searching the table
     *
     * @return array
     */
    public static function getSearchColumns() {
      $admin = ['name', 'email'];
      $user = $admin;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Available actions for data table row, per role
     *
     * @return array
     */
    public static function getActions() {
      $admin = [
        ['text' => trans('app.edit'), 'action' => 'edit', 'icon' => 'mdi-pencil', 'color' => 'accent'],
        ['text' => trans('app.delete'), 'action' => 'delete', 'icon' => 'mdi-delete-outline', 'color' => 'accent'],
        ['divider'],
        ['text' => trans('app.log_in_to_account'), 'action' => 'log_in_as', 'icon' => 'mdi-login', 'color' => 'accent'],
      ];

      $user = null;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Admin stats
     */
    public function getAdminStats($statsPeriod = '7days') {
      // Totals
      $totals = [
        'users' => $this->users->count()
      ];

      // Period
      if ($statsPeriod == '7days') {
        $from = now()->addDays(-7);
        $to = now();
        $fromPrevious = now()->addDays(-15);
        $toPrevious = now()->addDays(-8);
      }

      // User signups for current period
      $period = new \DatePeriod( new \DateTime($from), new \DateInterval('P1D'), new \DateTime($to));

      $range = [];
      foreach($period as $date){
        $range[$date->format("Y-m-d")] = 0;
      }

      $data = $this->users()
        ->select([
          DB::raw('DATE(`created_at`) as `date`'),
          DB::raw('COUNT(id) as `count`')
        ])
        ->whereBetween('created_at', [$from, $to])
        ->groupBy('date')
        ->get()
        /*
        ->groupBy(function ($val) {
            return Carbon::parse($val->created_at)->format('d');
        })*/
        ->pluck('count', 'date');

      $dbData = [];
      $total = 0;
      if ($data !== null) {
        foreach($data as $date => $count) {
          $dbData[$date] = (int) $count;
          $total += $count;
        }
      }

      $userSignups = array_replace($range, $dbData);
      $userSignupsTotal = $total;

      // Customer signups for previous period
      $period = new \DatePeriod( new \DateTime($fromPrevious), new \DateInterval('P1D'), new \DateTime($toPrevious));
      $data = $this->users()
        ->select([
          DB::raw('COUNT(id) as `count`')
        ])
        ->whereBetween('created_at', [$fromPrevious, $toPrevious])
        ->get()
        ->pluck('count');

      $userSignupsTotalPrevious = (int) $data[0];

      $stats = [
        'version' => config('version.current'),
        'total' => $totals,
        'users' => [
          'signupsCurrentPeriod' => $userSignups,
          'signupsCurrentPeriodTotal' => $userSignupsTotal,
          'signupsPreviousPeriodTotal' => $userSignupsTotalPrevious,
          'signupsChange' => $userSignupsTotal - $userSignupsTotalPrevious
        ]
      ];

      return $stats;
    }

    /**
     * Get all information to create a resume
     */
    public function getResume() {
      $experience = $this->resumeExpierence->map(function ($record, $key) {
        $date = $record->started_at->settings(['locale' => str_replace('-', '_', $this->locale)])->isoFormat('MMM YYYY');
        $date .= ($record->ended_at === null) ? ' - ' . trans('app.present') : ' - ' . $record->ended_at->settings(['locale' => str_replace('-', '_', $this->locale)])->isoFormat('MMM YYYY');

        return [
          'type' => $record->type,
          'name' => $record->name,
          'location' => $record->location,
          'description' => $record->description,
          'date' => $date
        ];
      });

      $projects = $this->resumeProjects->map(function ($record, $key) {
        $date = $record->started_at->settings(['locale' => str_replace('-', '_', $this->locale)])->isoFormat('MMM YYYY');
        if ($record->ended_at !== null) $date .= ' - ' . $record->ended_at->settings(['locale' => str_replace('-', '_', $this->locale)])->isoFormat('MMM YYYY');

        return [
          'title' => $record->title,
          'description' => $record->description,
          'date' => $date,
          'image' => $record->image,
          'tags' => $record->tags()->pluck('tags.name')->toArray()
        ];
      });

      $response = [
        'tags' => $this->tags()->pluck('tags.name')->toArray(),
        'experience' => $experience,
        'projects' => $projects
      ];

      return $response;
    }

    /**
     * Relationships
     * -------------
     */

    public function users() {
      return $this->hasMany(\App\User::class, 'created_by', 'id');
    }

    public function tags() {
      return $this->belongsToMany(\Platform\Models\Tag::class, 'tag_user', 'user_id', 'tag_id');
    }

    public function resumeExpierence() {
      return $this->hasMany(\Platform\Models\ResumeExperience::class, 'created_by', 'id')->orderBy('started_at');
    }

    public function resumeProjects() {
      return $this->hasMany(\Platform\Models\ResumeProject::class, 'created_by', 'id')->orderBy('started_at');
    }
}