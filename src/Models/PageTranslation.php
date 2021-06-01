<?php

namespace Pharaonic\Laravel\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Pharaonic\Laravel\Sluggable\Sluggable;

/**
 * @property integer $id
 * @property string $translator_id
 * @property string $translator_type
 * @property integer $page_id
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string $description
 * @property string $keywords
 * @property string $created_at
 * @property string $updated_at
 * @property Page $page
 * @property Object $translator
 */
class PageTranslation extends Model
{
    use Sluggable;

    /**
     * Fields List
     *
     * @var array
     */
    protected $fillable = [
        'page_id', 'locale',
        'translator_id', 'translator_type',
        'title', 'content', 'description', 'keywords'
    ];

    /**
     * TimeStamps Status
     *
     * @var boolean
     */
    public $timestamps  = false;

    /**
     * Sluggable attribute's name
     *
     * @var string
     */
    protected $sluggable = 'title';

    ///////////////////////////////////////////////////////
    //                                                   //
    //                  RELATIONSHIPS                    //
    //                                                   //
    ///////////////////////////////////////////////////////

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function translator()
    {
        return $this->morphTo();
    }

    ///////////////////////////////////////////////////////
    //                                                   //
    //                     ACTIONS                       //
    //                                                   //
    ///////////////////////////////////////////////////////

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($model) {
            $auth = Auth::user();
            if ($auth) {
                $model->translator_id = $auth->id;
                $model->translator_type = get_class($auth);
            }
        });
    }
}
