<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Repositories;

use Illuminate\Database\Eloquent\Builder;
use InnoShop\Common\Models\TaxClass;
use Throwable;

class TaxClassRepo extends BaseRepo
{
    /**
     * @return array[]
     */
    public static function getCriteria(): array
    {
        return [
            ['name' => 'name', 'type' => 'input', 'label' => trans('panel/common.name')],
            ['name' => 'description', 'type' => 'input', 'label' => trans('panel/common.description')],
            ['name' => 'created_at', 'type' => 'date_range', 'label' => trans('panel/common.created_at')],
        ];
    }

    /**
     * @param  $data
     * @return TaxClass
     * @throws Throwable
     */
    public function create($data): TaxClass
    {
        $item = new TaxClass($data);
        $item->saveOrFail();

        $item->taxRules()->createMany($data['tax_rules']);

        return $item;
    }

    /**
     * @param  $item
     * @param  $data
     * @return mixed
     */
    public function update($item, $data): mixed
    {
        $item->fill($data);
        $item->saveOrFail();

        $item->taxRules()->delete();
        $item->taxRules()->createMany($data['tax_rules']);

        return $item;
    }

    /**
     * @param  $filters
     * @return Builder
     */
    public function builder($filters = []): Builder
    {
        $builder = TaxClass::query();

        $createdStart = $filters['created_at_start'] ?? '';
        if ($createdStart) {
            $builder->where('created_at', '>', $createdStart);
        }

        $createdEnd = $filters['created_at_end'] ?? '';
        if ($createdEnd) {
            $builder->where('created_at', '<', $createdEnd);
        }

        return $builder;
    }
}
