<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Models\Attribute\Group;

use InnoShop\Common\Models\BaseModel;

class Translation extends BaseModel
{
    protected $table = 'attribute_group_translations';

    protected $fillable = [
        'attribute_group_id', 'locale', 'name',
    ];
}
