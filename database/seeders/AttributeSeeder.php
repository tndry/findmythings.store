<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use InnoShop\Common\Models\Attribute;
use InnoShop\Common\Models\Attribute\Group as AttributeGroup;
use InnoShop\Common\Models\Attribute\Group\Translation as AttributeGroupTranslation;
use InnoShop\Common\Models\Attribute\Translation as AttributeTranslation;
use InnoShop\Common\Models\Attribute\Value as AttributeValue;
use InnoShop\Common\Models\Attribute\Value\Translation as AttributeValueTranslation;
use InnoShop\Common\Models\Product\Attribute as ProductAttribute;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AttributeGroup::query()->truncate();
        AttributeGroupTranslation::query()->truncate();
        Attribute::query()->truncate();
        AttributeTranslation::query()->truncate();
        AttributeValue::query()->truncate();
        AttributeValueTranslation::query()->truncate();
        ProductAttribute::query()->truncate();

        // Attribute Group
        $attributeGroupsNumber = 4;
        for ($i = 1; $i <= $attributeGroupsNumber; $i++) {
            AttributeGroup::query()->create([
                'position' => $i,
            ]);
        }

        //  Attribute Group Translation
        $items = $this->getGroupTranslations();
        AttributeGroupTranslation::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();

                return $item;
            })->toArray()
        );

        // Attributes
        $items = $this->getAttributes();
        Attribute::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();

                return $item;
            })->toArray()
        );

        // Attribute Translations
        $items = $this->getAttributeTranslations();
        AttributeTranslation::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();

                return $item;
            })->toArray()
        );

        // Attribute Values
        $items = $this->getAttributeValues();
        AttributeValue::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();

                return $item;
            })->toArray()
        );

        // Attribute Value Translations
        $items = $this->getAttributeValueTranslations();
        AttributeValueTranslation::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();

                return $item;
            })->toArray()
        );

        // Product Attribute Relations
        $items = $this->productAttributes();
        ProductAttribute::query()->insert(
            collect($items)->map(function ($item) {
                $item['created_at'] = now();
                $item['updated_at'] = now();

                return $item;
            })->toArray()
        );
    }

    private function getGroupTranslations(): array
    {
        return [
            ['attribute_group_id' => 1, 'locale' => 'zh-cn', 'name' => '默认'],
            ['attribute_group_id' => 1, 'locale' => 'en', 'name' => 'Default'],
            ['attribute_group_id' => 2, 'locale' => 'zh-cn', 'name' => '衣服'],
            ['attribute_group_id' => 2, 'locale' => 'en', 'name' => 'Clothing'],
            ['attribute_group_id' => 3, 'locale' => 'zh-cn', 'name' => '运动'],
            ['attribute_group_id' => 3, 'locale' => 'en', 'name' => 'Sport'],
            ['attribute_group_id' => 4, 'locale' => 'zh-cn', 'name' => '配饰'],
            ['attribute_group_id' => 4, 'locale' => 'en', 'name' => 'Accessory'],
        ];
    }

    private function getAttributes(): array
    {
        return [
            ['attribute_group_id' => 2, 'category_id' => 1, 'position' => 0],
            ['attribute_group_id' => 2, 'category_id' => 1, 'position' => 0],
            ['attribute_group_id' => 2, 'category_id' => 1, 'position' => 0],
            ['attribute_group_id' => 3, 'category_id' => 1, 'position' => 0],
            ['attribute_group_id' => 4, 'category_id' => 1, 'position' => 0],
            ['attribute_group_id' => 4, 'category_id' => 1, 'position' => 0],
        ];
    }

    private function getAttributeTranslations(): array
    {
        return [
            ['attribute_id' => 1, 'locale' => 'zh-cn', 'name' => '功能'],
            ['attribute_id' => 1, 'locale' => 'en', 'name' => 'Features'],
            ['attribute_id' => 2, 'locale' => 'zh-cn', 'name' => '面料'],
            ['attribute_id' => 2, 'locale' => 'en', 'name' => 'Fabric'],
            ['attribute_id' => 3, 'locale' => 'zh-cn', 'name' => '样式'],
            ['attribute_id' => 3, 'locale' => 'en', 'name' => 'Style'],
            ['attribute_id' => 4, 'locale' => 'zh-cn', 'name' => '缓震'],
            ['attribute_id' => 4, 'locale' => 'en', 'name' => 'Cushioning'],
            ['attribute_id' => 5, 'locale' => 'zh-cn', 'name' => 'CUP'],
            ['attribute_id' => 5, 'locale' => 'en', 'name' => 'CUP'],
            ['attribute_id' => 6, 'locale' => 'zh-cn', 'name' => '内存'],
            ['attribute_id' => 6, 'locale' => 'en', 'name' => 'Memory'],
        ];
    }

    private function getAttributeValues(): array
    {
        return [
            ['attribute_id' => 2],
            ['attribute_id' => 2],
            ['attribute_id' => 1],
            ['attribute_id' => 3],
            ['attribute_id' => 2],
            ['attribute_id' => 2],
            ['attribute_id' => 2],
            ['attribute_id' => 3],
            ['attribute_id' => 3],
            ['attribute_id' => 3],
            ['attribute_id' => 1],
            ['attribute_id' => 1],
            ['attribute_id' => 4],
            ['attribute_id' => 4],
            ['attribute_id' => 4],
            ['attribute_id' => 4],
            ['attribute_id' => 4],
            ['attribute_id' => 5],
            ['attribute_id' => 5],
            ['attribute_id' => 5],
            ['attribute_id' => 5],
            ['attribute_id' => 6],
            ['attribute_id' => 6],
        ];
    }

    private function getAttributeValueTranslations(): array
    {
        return [
            ['attribute_value_id' => 1, 'locale' => 'zh-cn', 'name' => '棉'],
            ['attribute_value_id' => 1, 'locale' => 'en', 'name' => 'Cotton'],
            ['attribute_value_id' => 2, 'locale' => 'zh-cn', 'name' => '麻'],
            ['attribute_value_id' => 2, 'locale' => 'en', 'name' => 'Numb'],
            ['attribute_value_id' => 5, 'locale' => 'en', 'name' => 'Silk'],
            ['attribute_value_id' => 5, 'locale' => 'zh-cn', 'name' => '丝'],
            ['attribute_value_id' => 6, 'locale' => 'en', 'name' => 'Hair'],
            ['attribute_value_id' => 6, 'locale' => 'zh-cn', 'name' => '毛'],
            ['attribute_value_id' => 7, 'locale' => 'zh-cn', 'name' => '化纤'],
            ['attribute_value_id' => 7, 'locale' => 'en', 'name' => 'Chemical fiber'],
            ['attribute_value_id' => 4, 'locale' => 'zh-cn', 'name' => '圆领'],
            ['attribute_value_id' => 4, 'locale' => 'en', 'name' => 'Round neck'],
            ['attribute_value_id' => 8, 'locale' => 'en', 'name' => 'Collarless'],
            ['attribute_value_id' => 8, 'locale' => 'zh-cn', 'name' => '无领'],
            ['attribute_value_id' => 9, 'locale' => 'en', 'name' => 'Short sleeve'],
            ['attribute_value_id' => 9, 'locale' => 'zh-cn', 'name' => '短袖'],
            ['attribute_value_id' => 10, 'locale' => 'zh-cn', 'name' => 'T恤'],
            ['attribute_value_id' => 10, 'locale' => 'en', 'name' => 'T-shirt'],
            ['attribute_value_id' => 3, 'locale' => 'zh-cn', 'name' => '防水'],
            ['attribute_value_id' => 3, 'locale' => 'en', 'name' => 'Water proof'],
            ['attribute_value_id' => 11, 'locale' => 'zh-cn', 'name' => '保暖'],
            ['attribute_value_id' => 11, 'locale' => 'en', 'name' => 'keep warm'],
            ['attribute_value_id' => 12, 'locale' => 'zh-cn', 'name' => '防晒'],
            ['attribute_value_id' => 12, 'locale' => 'en', 'name' => 'Sun protection'],
            ['attribute_value_id' => 13, 'locale' => 'zh-cn', 'name' => 'Zoom气垫'],
            ['attribute_value_id' => 13, 'locale' => 'en', 'name' => 'Zoom Air Cushion'],
            ['attribute_value_id' => 14, 'locale' => 'zh-cn', 'name' => 'Max气垫'],
            ['attribute_value_id' => 14, 'locale' => 'en', 'name' => 'Max Air Cushion'],
            ['attribute_value_id' => 15, 'locale' => 'zh-cn', 'name' => 'Boost缓震材料'],
            ['attribute_value_id' => 15, 'locale' => 'en', 'name' => 'Boost cushioning material'],
            ['attribute_value_id' => 16, 'locale' => 'zh-cn', 'name' => 'Lightstrike科技'],
            ['attribute_value_id' => 16, 'locale' => 'en', 'name' => 'Lightstrike Technology'],
            ['attribute_value_id' => 17, 'locale' => 'en', 'name' => 'Fuel Cell Technology'],
            ['attribute_value_id' => 17, 'locale' => 'zh-cn', 'name' => 'FuelCell科技'],
            ['attribute_value_id' => 18, 'locale' => 'zh-cn', 'name' => 'i3'],
            ['attribute_value_id' => 18, 'locale' => 'en', 'name' => 'i3'],
            ['attribute_value_id' => 19, 'locale' => 'zh-cn', 'name' => 'i5'],
            ['attribute_value_id' => 19, 'locale' => 'en', 'name' => 'i5'],
            ['attribute_value_id' => 20, 'locale' => 'zh-cn', 'name' => 'i7'],
            ['attribute_value_id' => 20, 'locale' => 'en', 'name' => 'i7'],
            ['attribute_value_id' => 21, 'locale' => 'zh-cn', 'name' => 'i9'],
            ['attribute_value_id' => 21, 'locale' => 'en', 'name' => 'i9'],
            ['attribute_value_id' => 22, 'locale' => 'zh-cn', 'name' => 'DDR3'],
            ['attribute_value_id' => 22, 'locale' => 'en', 'name' => 'DDR3'],
            ['attribute_value_id' => 23, 'locale' => 'zh-cn', 'name' => 'DDR4'],
            ['attribute_value_id' => 23, 'locale' => 'en', 'name' => 'DDR4'],
        ];
    }

    private function productAttributes(): array
    {
        return [
            ['product_id' => 1, 'attribute_id' => 1, 'attribute_value_id' => 3],
            ['product_id' => 1, 'attribute_id' => 2, 'attribute_value_id' => 1],
            ['product_id' => 1, 'attribute_id' => 4, 'attribute_value_id' => 15],
            ['product_id' => 1, 'attribute_id' => 3, 'attribute_value_id' => 10],
            ['product_id' => 1, 'attribute_id' => 5, 'attribute_value_id' => 21],
            ['product_id' => 1, 'attribute_id' => 6, 'attribute_value_id' => 22],
            ['product_id' => 2, 'attribute_id' => 1, 'attribute_value_id' => 3],
            ['product_id' => 2, 'attribute_id' => 2, 'attribute_value_id' => 1],
            ['product_id' => 2, 'attribute_id' => 3, 'attribute_value_id' => 4],
            ['product_id' => 2, 'attribute_id' => 4, 'attribute_value_id' => 13],
            ['product_id' => 2, 'attribute_id' => 5, 'attribute_value_id' => 18],
            ['product_id' => 2, 'attribute_id' => 6, 'attribute_value_id' => 22],
            ['product_id' => 3, 'attribute_id' => 1, 'attribute_value_id' => 12],
            ['product_id' => 3, 'attribute_id' => 2, 'attribute_value_id' => 5],
            ['product_id' => 3, 'attribute_id' => 4, 'attribute_value_id' => 14],
            ['product_id' => 3, 'attribute_id' => 5, 'attribute_value_id' => 20],
            ['product_id' => 3, 'attribute_id' => 6, 'attribute_value_id' => 23],
            ['product_id' => 4, 'attribute_id' => 1, 'attribute_value_id' => 11],
            ['product_id' => 4, 'attribute_id' => 2, 'attribute_value_id' => 7],
            ['product_id' => 4, 'attribute_id' => 3, 'attribute_value_id' => 10],
            ['product_id' => 4, 'attribute_id' => 5, 'attribute_value_id' => 21],
            ['product_id' => 4, 'attribute_id' => 6, 'attribute_value_id' => 23],
            ['product_id' => 5, 'attribute_id' => 1, 'attribute_value_id' => 11],
            ['product_id' => 5, 'attribute_id' => 2, 'attribute_value_id' => 5],
            ['product_id' => 5, 'attribute_id' => 5, 'attribute_value_id' => 20],
            ['product_id' => 5, 'attribute_id' => 6, 'attribute_value_id' => 23],
            ['product_id' => 5, 'attribute_id' => 3, 'attribute_value_id' => 10],
            ['product_id' => 6, 'attribute_id' => 3, 'attribute_value_id' => 8],
            ['product_id' => 6, 'attribute_id' => 2, 'attribute_value_id' => 1],
            ['product_id' => 6, 'attribute_id' => 6, 'attribute_value_id' => 23],
            ['product_id' => 6, 'attribute_id' => 4, 'attribute_value_id' => 14],
            ['product_id' => 7, 'attribute_id' => 1, 'attribute_value_id' => 11],
            ['product_id' => 7, 'attribute_id' => 3, 'attribute_value_id' => 10],
            ['product_id' => 7, 'attribute_id' => 5, 'attribute_value_id' => 21],
            ['product_id' => 7, 'attribute_id' => 2, 'attribute_value_id' => 5],
            ['product_id' => 7, 'attribute_id' => 6, 'attribute_value_id' => 23],
            ['product_id' => 8, 'attribute_id' => 1, 'attribute_value_id' => 12],
            ['product_id' => 8, 'attribute_id' => 4, 'attribute_value_id' => 14],
            ['product_id' => 8, 'attribute_id' => 5, 'attribute_value_id' => 21],
            ['product_id' => 8, 'attribute_id' => 6, 'attribute_value_id' => 22],
            ['product_id' => 8, 'attribute_id' => 3, 'attribute_value_id' => 9],
            ['product_id' => 8, 'attribute_id' => 2, 'attribute_value_id' => 5],
            ['product_id' => 9, 'attribute_id' => 1, 'attribute_value_id' => 3],
            ['product_id' => 9, 'attribute_id' => 2, 'attribute_value_id' => 1],
            ['product_id' => 9, 'attribute_id' => 3, 'attribute_value_id' => 8],
            ['product_id' => 9, 'attribute_id' => 6, 'attribute_value_id' => 22],
            ['product_id' => 9, 'attribute_id' => 4, 'attribute_value_id' => 16],
            ['product_id' => 9, 'attribute_id' => 5, 'attribute_value_id' => 19],
        ];
    }
}
