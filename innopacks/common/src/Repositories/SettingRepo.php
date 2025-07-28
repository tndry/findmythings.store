<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use InnoShop\Common\Models\Currency;
use InnoShop\Common\Models\Setting;
use Throwable;

class SettingRepo extends BaseRepo
{
    /**
     * @param  array  $filters
     * @return Builder
     */
    public function builder(array $filters = []): Builder
    {
        $builder = Setting::query();
        $space   = $filters['space'] ?? '';
        if ($space) {
            $builder->where('space', $space);
        }

        $name = $filters['name'] ?? '';
        if ($name) {
            $builder->where('name', $name);
        }

        return fire_hook_filter('repo.setting.builder', $builder);
    }

    /**
     * Get setting group by space.
     */
    public function groupedSettings(): array
    {
        $settings = Setting::all(['space', 'name', 'value', 'json']);

        $result = [];
        foreach ($settings as $setting) {
            $space = $setting->space;
            $name  = $setting->name;
            $value = $setting->value;
            if ($setting->json) {
                $result[$space][$name] = json_decode($value, true);
            } else {
                $result[$space][$name] = $value;
            }
        }

        return $result;
    }

    /**
     * @param  $settings
     * @param  string  $space
     * @return void
     * @throws Throwable
     */
    public function updateValues($settings, string $space = 'system'): void
    {
        foreach ($settings as $name => $value) {
            if (in_array($name, ['_method', '_token'])) {
                continue;
            }
            $this->checkDefaultCurrencyRate($name, $value, $space);
            $this->updateValue($name, $value, $space);
        }
    }

    /**
     * @param  $name
     * @param  $value
     * @return mixed
     * @throws Throwable
     */
    public function updateSystemValue($name, $value): mixed
    {
        return $this->updateValue($name, $value, 'system');
    }

    /**
     * @param  $code
     * @param  $name
     * @param  $value
     * @return mixed
     * @throws Throwable
     */
    public function updatePluginValue($code, $name, $value): mixed
    {
        return $this->updateValue($name, $value, $code);
    }

    /**
     * @param  $name
     * @param  $value
     * @param  $space
     * @return void
     * @throws Exception
     */
    private function checkDefaultCurrencyRate($name, $value, $space): void
    {
        if ($name != 'currency' || $space != 'system') {
            return;
        }
        $currency = Currency::query()->where('code', $value)->first();
        if ($currency->value != 1) {
            throw new Exception(trans('panel/currency.default_currency_rate'));
        }
    }

    /**
     * @param  $name
     * @param  $value
     * @param  string  $space
     * @return mixed
     * @throws Throwable
     */
    private function updateValue($name, $value, string $space): mixed
    {
        if ($value === null) {
            $value = '';
        }

        $setting = $this->builder(['space' => $space, 'name' => $name])->first();

        $isJson = false;
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $isJson  = json_last_error() === JSON_ERROR_NONE && is_array($decoded);
        }

        $settingData = [
            'space' => $space,
            'name'  => $name,
            'value' => $isJson ? $value : (is_array($value) ? json_encode($value) : $value),
            'json'  => $isJson || is_array($value),
        ];

        if (empty($setting)) {
            $setting = new Setting($settingData);
            $setting->saveOrFail();
        } else {
            $setting->update($settingData);
        }

        return $setting;
    }
}
