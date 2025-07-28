<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Common\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use InnoShop\Common\Exceptions\Unauthorized;
use InnoShop\Common\Models\Checkout;
use InnoShop\Common\Models\Customer;
use InnoShop\Common\Repositories\AddressRepo;
use InnoShop\Common\Repositories\CheckoutRepo;
use InnoShop\Common\Repositories\Order\FeeRepo;
use InnoShop\Common\Repositories\Order\HistoryRepo;
use InnoShop\Common\Repositories\Order\ItemRepo;
use InnoShop\Common\Repositories\OrderRepo;
use InnoShop\Common\Resources\AddressListItem;
use InnoShop\Common\Resources\CheckoutSimple;
use InnoShop\Common\Services\Checkout\BillingService;
use InnoShop\Common\Services\Checkout\FeeService;
use InnoShop\Common\Services\Checkout\ShippingService;
use InnoShop\Common\Services\Fee\Shipping;
use InnoShop\Common\Services\Fee\Subtotal;
use Throwable;

class CheckoutService
{
    private static mixed $checkoutService = null;

    protected int $customerID;

    protected string $guestID;

    protected mixed $customer;

    protected array $cartList = [];

    private array $addressList = [];

    private array $feeList = [];

    private ?Checkout $checkout = null;

    private array $checkoutData = [];

    /**
     * @param  int  $customerID
     * @param  string  $guestID
     * @throws Throwable
     */
    public function __construct(int $customerID = 0, string $guestID = '')
    {
        if (system_setting('disable_online_order')) {
            throw new Exception('The online order is disabled.');
        }

        if ($customerID) {
            $this->customerID = $customerID;
        } else {
            $this->customerID = current_customer_id();
        }

        if (empty($this->customerID) && system_setting('login_checkout')) {
            throw new Unauthorized('Please login first');
        }

        $this->customer = Customer::query()->find($this->customerID);

        if ($guestID) {
            $this->guestID = $guestID;
        } else {
            $this->guestID = current_guest_id();
        }

        $this->clearGuestAddresses();
    }

    /**
     * @param  int  $customerID
     * @param  string  $guestID
     * @return static
     * @throws Throwable
     */
    public static function getSingleton(int $customerID = 0, string $guestID = ''): static
    {
        if (self::$checkoutService !== null) {
            return self::$checkoutService;
        }

        return self::$checkoutService = new static($customerID, $guestID);
    }

    /**
     * @param  int  $customerID
     * @param  string  $guestID
     * @return static
     * @throws Throwable
     */
    public static function getInstance(int $customerID = 0, string $guestID = ''): static
    {
        return new static($customerID, $guestID);
    }

    /**
     * Get current cart item list.
     *
     * @return array
     */
    public function getCartList(): array
    {
        if ($this->cartList) {
            return $this->cartList;
        }

        $filters = [
            'selected' => true,
        ];

        $cartService = CartService::getInstance($this->customerID, $this->guestID);

        return $this->cartList = $cartService->getCartList($filters);
    }

    /**
     * @return bool
     */
    public function checkIsVirtual(): bool
    {
        $cartList = $this->getCartList();
        foreach ($cartList as $product) {
            if (! $product['is_virtual']) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return mixed
     */
    public function getCartWeight(): mixed
    {
        $weightTotal = 0;
        $cartList    = $this->getCartList();
        foreach ($cartList as $product) {
            $weightTotal += $product['weight'] * $product['quantity'];
        }

        return $weightTotal;
    }

    /**
     * Get current address list.
     *
     * @return array
     */
    public function getAddressList(): array
    {
        if ($this->addressList) {
            return $this->addressList;
        }

        $filters = [
            'customer_id' => $this->customerID,
            'guest_id'    => $this->guestID,
        ];
        $addresses = AddressRepo::getInstance()->builder($filters)->get();

        return $this->addressList = (AddressListItem::collection($addresses))->jsonSerialize();
    }

    /**
     * @return mixed
     */
    public function getDefaultAddress(): array
    {
        $addressList = $this->getAddressList();
        if (empty($addressList)) {
            return [];
        }

        $defaultAddress = collect($addressList)->where('default', 1)->first();

        return $defaultAddress ?: $addressList[0];
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function getShippingAddress(): array
    {
        $filters = [
            'customer_id' => $this->customerID,
            'guest_id'    => $this->guestID,
        ];
        $checkout = CheckoutRepo::getInstance()->builder($filters)->first();
        $address  = $checkout->shippingAddress ?? null;
        if (empty($address)) {
            return $this->getDefaultAddress();
        }

        return $address->toArray();
    }

    /**
     * @return float
     */
    public function getSubTotal(): float
    {
        return (new Subtotal($this))->getSubtotal();
    }

    /**
     * Get fee list.
     *
     * @return array
     * @throws Exception
     */
    public function getFeeList(): array
    {
        if ($this->feeList) {
            return $this->feeList;
        }

        FeeService::getInstance($this)->calculate();
        if (empty($this->feeList)) {
            throw new Exception('Empty checkout fee list !');
        }

        return $this->feeList;
    }

    /**
     * @param  array  $fee
     * @return $this
     */
    public function addFeeList(array $fee): static
    {
        $this->feeList[] = $fee;

        return $this;
    }

    /**
     * @return float
     * @throws Exception
     */
    public function getAmount(): float
    {
        $feeList = $this->getFeeList();

        return round(collect($feeList)->sum('total'), 2);
    }

    /**
     * @return int
     * @throws Exception
     */
    public function getTotalNumber(): int
    {
        $cartList = $this->getCartList();

        return collect($cartList)->sum('quantity');
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function getCheckoutData(): array
    {
        if ($this->checkoutData) {
            $this->validateCheckoutData();

            return $this->checkoutData;
        }

        return $this->checkoutData = $this->freshCheckoutData();
    }

    /**
     * @return array
     * @throws Throwable
     */
    public function freshCheckoutData(): array
    {
        $checkout     = $this->getCheckout();
        $checkoutData = (new CheckoutSimple($checkout))->jsonSerialize();

        $checkoutData['shipping_quote_name'] = Shipping::getInstance($this)->getShippingQuoteName($checkout->shipping_method_code);

        return $checkoutData;
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function validateCheckoutData(): void
    {
        $shippingService    = ShippingService::getInstance()->setCheckoutService($this);
        $shippingMethods    = $shippingService->getMethods();
        $shippingQuoteCodes = $shippingService->getQuoteCodes();
        $defaultAddress     = $this->getDefaultAddress();

        if (! in_array($this->checkoutData['shipping_method_code'], $shippingQuoteCodes) && $defaultAddress) {
            $defaultShippingCode = $shippingMethods[0]['quotes'][0]['code'] ?? '';
            $this->updateValues(['shipping_method_code' => $defaultShippingCode]);
        }

        $billingMethods = BillingService::getInstance()->getMethods();
        $billingCodes   = collect($billingMethods)->pluck('code')->toArray();
        if (! in_array($this->checkoutData['billing_method_code'], $billingCodes)) {
            $this->updateValues(['billing_method_code' => $billingMethods[0]['code'] ?? '']);
        }

        $addressList = $this->getAddressList();
        if (! collect($addressList)->contains('id', $this->checkoutData['shipping_address_id'])) {
            $this->updateValues(['shipping_address_id' => 0]);
        }

        if (! collect($addressList)->contains('id', $this->checkoutData['billing_address_id'])) {
            $this->updateValues(['billing_address_id' => 0]);
        }

        $this->checkoutData = $this->freshCheckoutData();
    }

    /**
     * @return Checkout|null
     * @throws Throwable
     */
    public function getCheckout(): ?Checkout
    {
        if ($this->checkout) {
            return $this->checkout;
        }

        $data = [
            'customer_id' => $this->customerID,
            'guest_id'    => $this->guestID,
        ];
        $checkout = CheckoutRepo::getInstance()->builder($data)->first();

        if (empty($checkout)) {
            $checkout = $this->createCheckout($data);
        }

        return $this->checkout = $checkout;
    }

    /**
     * @param  $data
     * @return mixed
     * @throws Throwable
     */
    public function createCheckout($data): mixed
    {
        $shippingMethods = $billingMethods = [];

        $defaultAddress   = $this->getDefaultAddress();
        $defaultAddressID = $defaultAddress['id'] ?? 0;

        if ($defaultAddressID) {
            $shippingMethods = ShippingService::getInstance()->setCheckoutService($this)->getMethods();
            $billingMethods  = BillingService::getInstance()->getMethods();
        }

        $data['shipping_address_id']  = $defaultAddressID;
        $data['shipping_method_code'] = $shippingMethods[0]['quotes'][0]['code'] ?? '';
        $data['billing_address_id']   = $defaultAddressID;
        $data['billing_method_code']  = $billingMethods[0]['code'] ?? '';

        return CheckoutRepo::getInstance()->create($data);
    }

    /**
     * Get checkout result.
     *
     * @return array
     * @throws Exception|Throwable
     */
    public function getCheckoutResult(): array
    {
        $this->checkCartStockEnough();

        $cartAmount    = $this->getAmount();
        $balanceAmount = $this->getBalanceAmount();

        $result = [
            'cart_list'             => $this->getCartList(),
            'address_list'          => $this->getAddressList(),
            'shipping_methods'      => ShippingService::getInstance()->setCheckoutService($this)->getMethods(),
            'billing_methods'       => BillingService::getInstance()->getMethods(),
            'checkout'              => $this->getCheckoutData(),
            'fee_list'              => $this->getFeeList(),
            'amount'                => $cartAmount,
            'amount_format'         => currency_format($cartAmount),
            'total_number'          => $this->getTotalNumber(),
            'is_virtual'            => $this->checkIsVirtual(),
            'balance_amount'        => $this->getBalanceAmount(),
            'balance_amount_format' => currency_format($balanceAmount, setting_currency_code()),
        ];

        return fire_hook_filter('service.checkout.checkout.result', $result);
    }

    /**
     * @return float
     */
    public function getBalanceAmount(): float
    {
        return (float) ($this->customer->balance ?? 0);
    }

    /**
     * @return void
     */
    private function clearGuestAddresses(): void
    {
        AddressRepo::getInstance()->clearExpiredAddresses();
    }

    /**
     * @param  $values
     * @return mixed
     * @throws Throwable
     */
    public function updateValues($values): mixed
    {
        $checkout = $this->getCheckout();

        return CheckoutRepo::getInstance()->update($checkout, $values);
    }

    /**
     * Check if all cart items have enough stock
     *
     * @throws Exception
     */
    protected function checkCartStockEnough(): void
    {
        $cartList = $this->getCartList();
        foreach ($cartList as $item) {
            if (isset($item['is_stock_enough']) && ! $item['is_stock_enough']) {
                throw new Exception(trans('front/common.stock_not_enough'));
            }
        }
    }

    /**
     * Confirm checkout and place order.
     *
     * @return mixed
     * @throws Exception|Throwable
     */
    public function confirm(): mixed
    {
        $this->checkCartStockEnough();

        DB::beginTransaction();

        try {
            $checkoutData = $this->getCheckoutData();

            $checkoutData['total'] = $this->getAmount();

            $order = OrderRepo::getInstance()->create($checkoutData);

            ItemRepo::getInstance()->createItems($order, $this->cartList);
            FeeRepo::getInstance()->createItems($order, $this->feeList);
            HistoryRepo::getInstance()->initItem($order);

            DB::commit();

            $data = [
                'cart_list' => $this->getCartList(),
                'checkout'  => $checkoutData,
                'order'     => $order,
            ];
            fire_hook_action('service.checkout.confirm.after', $data);

            $this->checkout->delete();
            CartService::getInstance($this->customerID)->getCartBuilder(['selected' => true])->delete();

            return $order;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
