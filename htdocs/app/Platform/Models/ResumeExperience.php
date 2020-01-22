<?php

namespace Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Platform\Controllers\Core;
use Spatie\Image\Manipulations;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ResumeExperience extends Model
{
    protected $table = 'resume_experience';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * Appended columns.
     *
     * @var array
     */
    protected $appends = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Field mutators.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * Date/time fields that can be used with Carbon.
     *
     * @return array
     */
    public function getDates() {
      return ['started_at', 'ended_at', 'created_at', 'updated_at'];
    }

    public static function boot() {
      parent::boot();

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

        if (auth()->check()) {
          $model->created_by = auth()->user()->id;
        }
      });
    }

    /**
     * Form for creating a new record, per role
     *
     * @return array
     */
    public static function getCreateForm() {
      $admin = [
        'tab1' => [
          'text' => trans('app.general'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'enum', 'items' => ['education' => trans('app.education'), 'work' => trans('app.work')], 'column' => 'type', 'text' => trans('app.type'), 'validate' => 'required|max:128', 'required' => true],
                ['type' => 'date', 'format' => 'LLL', 'column' => 'started_at', 'text' => trans('app.date_started'), 'validate' => 'required', 'required' => true],
                ['type' => 'date', 'format' => 'LLL', 'column' => 'ended_at', 'text' => trans('app.date_ended'), 'validate' => 'nullable', 'required' => false],
                ['type' => 'text', 'column' => 'name', 'text' => trans('app.name'), 'validate' => 'required|max:200', 'required' => true],
                ['type' => 'text', 'column' => 'location', 'text' => trans('app.location'), 'validate' => 'nullable|max:200', 'required' => false]
              ]
            ]
          ]
        ],
        'tab2' => [
          'text' => trans('app.description'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'wysiwyg', 'column' => 'description', 'text' => trans('app.description'), 'validate' => 'nullable', 'required' => false]
              ]
            ]
          ]
        ]
      ];
      $user = $admin;

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * Name used in plan limitations (optional)
     *
     * @return string
     */
    public static function getLimitationName() {
      return 'experience';
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
      $admin = ['select_all' => true, 'actions' => true, 'create' => true, 'actions_width' => '90px'];
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
        'items' => trans('app.experience'),
        'edit_item' => trans('app.edit_experience'),
        'create_item' => trans('app.create_experience'),
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
      $admin = ['view' => 'none', 'delete' => 'none', 'update' => 'none', 'create' => false];
      $user = ['view' => 'personal', 'delete' => 'personal', 'update' => 'personal', 'create' => true];

      return [
        1 => $admin,
        2 => $user
      ];
    }

    /**
     * The headers for the data table, per role
     *
     * @return array
     */
    public static function getHeaders() {
      $admin = [
        ['visible' => true, 'value' => 'type', 'type' => 'enum', 'items' => ['education' => trans('app.education'), 'work' => trans('app.work')], 'text' => trans('app.type'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'name', 'text' => trans('app.name'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'location', 'text' => trans('app.location'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'started_at', 'type' => 'date', 'format' => 'll', 'text' => trans('app.date_started'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'ended_at', 'type' => 'date', 'format' => 'll', 'text' => trans('app.date_ended'), 'align' => 'left', 'sortable' => true]
      ];
      $user = $admin;

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
      $admin = ['name'];
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
        ['text' => trans('app.delete'), 'action' => 'delete', 'icon' => 'mdi-delete-outline', 'color' => 'accent']
      ];

      $user = $admin;

      return [
        1 => $admin,
        2 => $user
      ];
    }


    /**
     * Relationships
     * -------------
     */

    public function user() {
      return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }
}