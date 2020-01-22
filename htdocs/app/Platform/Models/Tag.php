<?php

namespace Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Platform\Controllers\Core;
use Spatie\Image\Manipulations;
use Carbon\Carbon;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $table = 'tags';

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
      return ['created_at', 'updated_at'];
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

        // Order
        $last = Tag::where('created_by', $model->created_by)->max('order');
        $order = ($last !== null) ? $last + 1 : 1;
        $model->order = $order;
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
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'text', 'column' => 'name', 'text' => trans('app.tag'), 'validate' => 'required|max:128', 'required' => true]
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
      return 'tags';
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
        'items' => trans('app.tags'),
        'edit_item' => trans('app.edit_tag'),
        'create_item' => trans('app.create_tag'),
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
        ['visible' => true, 'value' => 'name', 'text' => trans('app.tag'), 'align' => 'left', 'sortable' => true]
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