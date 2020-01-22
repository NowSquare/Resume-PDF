<?php

namespace Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Platform\Controllers\Core;
use Spatie\Image\Manipulations;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Models\Media;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class ResumeProject extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $table = 'resume_projects';

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
      'image', 'image_thumb'
    ];

    public function registerMediaCollections() {

      $this
        ->addMediaCollection('image')
        ->singleFile();
    }

    public function registerMediaConversions(Media $media = null) {
      $this
        ->addMediaConversion('thumb')
        ->width(360)
        ->height(240)
        ->performOnCollections('image');

      $this
        ->addMediaConversion('full')
        ->width(1920)
        ->height(1080)
        ->performOnCollections('image');
    }

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
                ['type' => 'date', 'format' => 'LLL', 'column' => 'started_at', 'text' => trans('app.date'), 'validate' => 'required', 'required' => true],
                ['type' => 'text', 'column' => 'title', 'text' => trans('app.title'), 'validate' => 'required|max:200', 'required' => true],
                ['type' => 'wysiwyg', 'column' => 'description', 'text' => trans('app.description'), 'validate' => 'required', 'required' => true]
              ]
            ]
          ]
        ],
        'tab2' => [
          'text' => trans('app.tags'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'relation', 'relation' => ['type' => 'belongsToMany', 'permission' => 'personal', 'with' => 'tags', 'remote_pk' => 'tag_id', 'table' => 'tags', 'pk' => 'id', 'val' => "name", 'orderBy' => 'name', 'order' => 'asc'], 'text' => trans('app.tags'), 'validate' => 'nullable', 'required' => false]
              ]
            ]
          ]
        ],
        'tab3' => [
          'text' => trans('app.image'),
          'subs' => [
            'sub1' => [
              'items' => [
                ['type' => 'image', 'column' => 'image', 'hint' => trans('app.project_image_hint'), 'image' => ['thumb_max_width' => '320px', 'thumb_max_height' => '240px'], 'text' => trans('app.image'), 'validate' => 'nullable', 'icon' => 'mdi-paperclip']
              ]
            ]
          ]
        ],
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
      return 'projects';
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
        'items' => trans('app.projects'),
        'edit_item' => trans('app.edit_project'),
        'create_item' => trans('app.create_project'),
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
        ['visible' => true, 'value' => 'started_at', 'type' => 'date', 'format' => 'll', 'text' => trans('app.date'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'title', 'text' => trans('app.title'), 'align' => 'left', 'sortable' => true],
        ['visible' => true, 'value' => 'image_thumb', 'type' => 'image', 'exclude_from_select' => true, 'max_width' => '140px', 'text' => trans('app.image'), 'align' => 'left', 'sortable' => false]
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
     * Images
     * -------------
     */

    public function getImageAttribute() {
      return ($this->getFirstMediaUrl('image') !== '') ? $this->getMedia('image')[0]->getFullUrl('full') : null;
    }

    public function getImageThumbAttribute() {
      return ($this->getFirstMediaUrl('image') !== '') ? $this->getMedia('image')[0]->getFullUrl('thumb') : null;
    }

    /**
     * Relationships
     * -------------
     */

    public function user() {
      return $this->belongsTo(\App\User::class, 'created_by', 'id');
    }

    public function tags() {
      return $this->belongsToMany(\Platform\Models\Tag::class, 'resume_project_tag', 'resume_project_id', 'tag_id');
    }
}