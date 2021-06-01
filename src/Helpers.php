<?php

use Pharaonic\Laravel\Pages\Models\Page;

/**
 * Find A Page
 *
 * @param integer $id
 * @return Page|null
 */
function page(int $id)
{
    return Page::find($id);
}
