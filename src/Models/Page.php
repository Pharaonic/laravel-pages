<?php

namespace Pharaonic\Laravel\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Pharaonic\Laravel\Translatable\Translatable;

/**
 * @property integer $id
 * @property string $creator_id
 * @property string $creator_type
 * @property boolean $published
 * @property string $created_at
 * @property string $updated_at
 * @property Object $creator
 */
class Page extends Model
{
    use Translatable;

    /**
     * Fields List
     *
     * @var array
     */
    protected $fillable = ['creator_id', 'creator_type', 'published'];

    /**
     * Translatable Fields
     *
     * @var array
     */
    protected $translatableAttributes = ['translator_id', 'translator_type', 'page_id', 'title', 'content', 'description', 'keywords'];

    /**
     * Casting Fields
     *
     * @var array
     */
    protected $casts = ['published' => 'boolean'];

    ///////////////////////////////////////////////////////
    //                                                   //
    //                  RELATIONSHIPS                    //
    //                                                   //
    ///////////////////////////////////////////////////////

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function creator()
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
                $model->creator_id = $auth->id;
                $model->creator_type = get_class($auth);
            }
        });
    }
}
