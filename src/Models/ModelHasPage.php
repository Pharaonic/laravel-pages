<?php

namespace Pharaonic\Laravel\Pages\Models;

use Illuminate\Database\Eloquent\Model;
use Pharaonic\Laravel\Pages\Models\Page;

/**
 * @property integer $id
 * @property string $name
 * @property string $pagable_type
 * @property string $pagable_id
 * @property integer $page_id
 * @property string $created_at
 * @property string $updated_at
 * @property Page $page
 */
class ModelHasPage extends Model
{
    /**
     * Fields List
     * 
     * @var array
     */
    protected $fillable = [
        'pagable_type', 'pagable_id',
        'name', 'page_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function pagable()
    {
        return $this->morphTo();
    }

    /**
     * Page Object
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function page()
    {
        return $this->hasOne(Page::class, 'id', 'page_id');
    }
}
