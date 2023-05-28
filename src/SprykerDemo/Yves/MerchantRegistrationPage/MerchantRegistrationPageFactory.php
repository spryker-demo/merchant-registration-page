<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage;

use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerDemo\Client\MerchantRegistration\MerchantRegistrationClientInterface;
use SprykerDemo\Yves\MerchantRegistrationPage\Form\FormFactory;

class MerchantRegistrationPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerDemo\Yves\MerchantRegistrationPage\Form\FormFactory
     */
    public function createMerchantFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationPageDependencyProvider::STORE_CLIENT);
    }

    /**
     * @return \SprykerDemo\Client\MerchantRegistration\MerchantRegistrationClientInterface
     */
    public function getMerchantRegistrationClient(): MerchantRegistrationClientInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationPageDependencyProvider::MERCHANT_REGISTRATION_CLIENT);
    }
}
