<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Panel\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use InnoShop\Common\Models\Product;
use InnoShop\Common\Repositories\AttributeRepo;
use InnoShop\Common\Repositories\BrandRepo;
use InnoShop\Common\Repositories\CategoryRepo;
use InnoShop\Common\Repositories\ProductRepo;
use InnoShop\Common\Repositories\TaxClassRepo;
use InnoShop\Common\Repositories\WeightClassRepo;
use InnoShop\Common\Resources\SkuListItem;
use InnoShop\Panel\Requests\ProductRequest;
use Throwable;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function index(Request $request): mixed
    {
        $filters = $request->all();

        $data = [
            'criteria'        => ProductRepo::getCriteria(),
            'sortOptions'     => ProductRepo::getSortOptions(),
            'products'        => ProductRepo::getInstance()->list($filters),
            'categoryOptions' => ProductRepo::getCategoryOptions(),
        ];

        return inno_view('panel::products.index', $data);
    }

    /**
     * Product creation page.
     *
     * @return mixed
     * @throws Exception
     */
    public function create(): mixed
    {
        return $this->form(new Product);
    }

    /**
     * @param  ProductRequest  $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function store(ProductRequest $request): RedirectResponse
    {
        try {
            

            $data    = $request->all();
            $product = ProductRepo::getInstance()->create($data);

            
            

            return redirect(panel_route('products.index'))
                ->with('instance', $product)
                ->with('success', panel_trans('common.saved_success'));
        } catch (Exception $e) {
            return redirect(panel_route('products.index'))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Product  $product
     * @return mixed
     * @throws Exception
     */
    public function edit(Product $product): mixed
    {
        return $this->form($product);
    }

    /**
     * @param  $product
     * @return mixed
     * @throws Exception
     */
    public function form($product): mixed
    {
        $categories = CategoryRepo::getInstance()->withActive()->all();

        $skus = SkuListItem::collection($product->skus)->jsonSerialize();

        $attributeData = AttributeRepo::getInstance()->getAttributesWithValues();

        $data = [
            'product'         => $product,
            'skus'            => $skus,
            'categories'      => $categories,
            'brands'          => BrandRepo::getInstance()->all()->toArray(),
            'tax_classes'     => TaxClassRepo::getInstance()->all()->toArray(),
            'weightClasses'   => WeightClassRepo::getInstance()->withActive()->all()->toArray(),
            'attribute_count' => $product->productAttributes->count(),
            'all_attributes'  => $attributeData,
        ];

        return inno_view('panel::products.form', $data);
    }

    /**
     * @param  ProductRequest  $request
     * @param  Product  $product
     * @return RedirectResponse
     * @throws Throwable
     */
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        try {
            $data = $request->all();
            ProductRepo::getInstance()->update($product, $data);

            return redirect(panel_route('products.index'))
                ->with('instance', $product)
                ->with('success', panel_trans('common.updated_success'));
        } catch (Exception $e) {
            return redirect(panel_route('products.edit', $product))
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function destroy(Product $product): RedirectResponse
    {
        try {
            ProductRepo::getInstance()->destroy($product);

            return back()->with('success', panel_trans('common.deleted_success'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * @param  Product  $product
     * @return RedirectResponse
     */
    public function copy(Product $product): RedirectResponse
    {
        try {
            ProductRepo::getInstance()->copy($product);

            return back()->with('success', panel_trans('common.saved_success'));
        } catch (Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Bulk update products
     *
     * @param  Request  $request
     * @return mixed
     */
    public function bulkUpdate(Request $request): mixed
    {
        try {
            $action = $request->input('action');
            $ids    = $request->input('ids', []);
            $data   = $request->input('data', []);

            // Validate required parameters
            if (empty($action) || empty($ids)) {
                return json_fail(__('panel/common.invalid_parameters'));
            }

            // Validate action type
            $allowedActions = ['price', 'categories', 'quantity', 'publish', 'unpublish'];
            if (! in_array($action, $allowedActions)) {
                return json_fail(__('panel/common.invalid_action'));
            }

            $result = ProductRepo::getInstance()->bulkUpdate($ids, $action, $data);

            return json_success(__('panel/product.bulk_update_success', ['count' => $result['count']]));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Bulk destroy products
     *
     * @param  Request  $request
     * @return mixed
     */
    public function bulkDestroy(Request $request): mixed
    {
        try {
            $ids = $request->input('ids', []);

            if (empty($ids)) {
                return json_fail(__('panel/common.select_items'));
            }

            $deletedCount = ProductRepo::getInstance()->bulkDestroy($ids);

            return json_success(__('panel/product.bulk_delete_success', ['count' => $deletedCount]));
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
