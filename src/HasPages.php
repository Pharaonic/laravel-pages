<?php

namespace Pharaonic\Laravel\Pages;

use Pharaonic\Laravel\Helpers\Traits\HasCustomAttributes;
use Pharaonic\Laravel\Pages\Models\ModelHasPage;

/**
 * Has-Pages Trait
 *
 * @version 1.0
 * @author Moamen Eltouny (Raggi) <raggi@raggitech.com>
 */
trait HasPages
{
    use HasCustomAttributes;

    public static $pagesActions = [];

    /**
     * @return void
     */
    public function initializeHasPages()
    {
        foreach ($this->pagesAttributes ?? ['page'] as $attr)
            $this->fillable[] = $attr;
    }

    /**
     * Boot
     * 
     * @return void
     */
    protected static function bootHasPages()
    {
        // Creating
        self::creating(function ($model) {
            $attrs = $model->pagesAttributes ?? ['page'];
            
            foreach ($model->getAttributes() as $name => $value) {
                if (in_array($name, $attrs)) {
                    self::$pagesActions[$name] = $value;
                    unset($model->{$name});
                }
            }
        });

        // Created
        self::created(function ($model) {
            if (count(self::$pagesActions) > 0) {
                foreach (self::$pagesActions as $name => $value)
                    $model->setAttribute($name, $model->_setPageAttribute($name, $value));

                self::$pagesActions = [];
            }
        });

        // Updating
        self::updating(function ($model) {
            $attrs = $model->pagesAttributes ?? ['page'];

            foreach ($model->getAttributes() as $name => $value) {
                if (in_array($name, $attrs)) {
                    self::$pagesActions[$name] = $value;
                    unset($model->attributes[$name]);
                }
            }
        });

        // Updated
        self::updated(function ($model) {
            if (count(self::$pagesActions) > 0) {
                foreach (self::$pagesActions as $name => $value)
                    $model->setAttribute($name, $model->_setPageAttribute($name, $value));

                self::$pagesActions = [];
            }
        });

        // Retrieving
        self::retrieved(function ($model) {
            $attrs = $model->pagesAttributes ?? ['page'];

            try {
                foreach ($attrs as $attr) $model->addGetterAttribute($attr, '_getPageAttribute');
                foreach ($attrs as $attr) $model->addSetterAttribute($attr, '_setPageAttribute');
            } catch (\Throwable $e) {
                throw new \Exception('You have to use Pharaonic\Laravel\Helpers\Traits\HasCustomAttributes as a trait in ' . get_class($model));
            }
        });

        // Deleting
        self::deleting(function ($model) {
            $model->clearPages();
        });
    }

    /////////////////////////////////////////////////////////////
    //
    //                      ACTIONS
    //
    ////////////////////////////////////////////////////////////

    /**
     * Get Page Attribute
     * 
     * @param string $key
     * @return Page|null
     */
    public function _getPageAttribute($key)
    {
        if ($this->isPageAttribute($key)) {
            $page = $this->pages()->where('name', $key)->first();
            return $page ? $page->page : null;
        }
    }

    /**
     * Set Page Attribute
     * 
     * @param string $key
     * @param Page|int $value
     * @return Page|null
     */
    public function _setPageAttribute($key, $value)
    {
        if ($this->isPageAttribute($key)) {
            $page = $this->pages()->where('name', $key)->first();
            $value = is_int($value) ? $value : $value->id;

            if ($page) {
                $page->update(['page_id' => $value]);
                return $page;
            } else {
                $page = $this->pages()->create([
                    'name'     => $key,
                    'page_id'  => $value,
                ]);

                return $page->page;
            }
        }

        return null;
    }

    /**
     * Check if page attribute
     * 
     * @param string $key
     * @return boolean
     */
    public function isPageAttribute(string $key): bool
    {
        return in_array($key, $this->pagesAttributes ?? ['page']);
    }

    /**
     * Clear All Pages
     * 
     * @return boolean
     */
    public function clearPages()
    {
        return $this->pages()->delete();
    }

    /////////////////////////////////////////////////////////////
    //
    //                      RELATIONS
    //
    ////////////////////////////////////////////////////////////

    /**
     * Get All Pages
     */
    public function pages()
    {
        return $this->morphMany(ModelHasPage::class, 'pagable');
    }
}
