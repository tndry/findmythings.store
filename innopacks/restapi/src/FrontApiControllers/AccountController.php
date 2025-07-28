<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\RestAPI\FrontApiControllers;

use Exception;
use Illuminate\Http\Request;
use InnoShop\Common\Repositories\CustomerRepo;
use InnoShop\Common\Resources\CustomerDetail;
use InnoShop\Front\Requests\PasswordRequest;
use InnoShop\Front\Requests\SetPasswordRequest;

class AccountController extends BaseController
{
    /**
     * @param  Request  $request
     * @return mixed
     */
    public function me(Request $request): mixed
    {
        $user   = $request->user();
        $result = new CustomerDetail($user);

        return read_json_success($result);
    }

    /**
     * @param  Request  $request
     * @return mixed
     * @throws Exception
     */
    public function updateProfile(Request $request): mixed
    {
        try {
            $customer    = $request->user();
            $requestData = $request->only(['avatar', 'name', 'email']);
            CustomerRepo::getInstance()->update($customer, $requestData);

            $result = new CustomerDetail($customer);

            return update_json_success($result);

        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Request to change password.
     *
     * @param  PasswordRequest  $request
     * @return mixed
     */
    public function updatePassword(PasswordRequest $request): mixed
    {
        try {
            $customer = $request->user();
            CustomerRepo::getInstance()->updatePassword($customer, $request->all());
            $result = new CustomerDetail($customer);

            return update_json_success($result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }

    /**
     * Request to change password.
     *
     * @param  SetPasswordRequest  $request
     * @return mixed
     */
    public function setPassword(SetPasswordRequest $request): mixed
    {
        try {
            $customer = $request->user();
            if ($customer->has_password) {
                throw new Exception('Has set password, should use API: PUT /account/password');
            }

            CustomerRepo::getInstance()->forceUpdatePassword($customer, $request->get('new_password'));
            $result = new CustomerDetail($customer);

            return update_json_success($result);
        } catch (Exception $e) {
            return json_fail($e->getMessage());
        }
    }
}
