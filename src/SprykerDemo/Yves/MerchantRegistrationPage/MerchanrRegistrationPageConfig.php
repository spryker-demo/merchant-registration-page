<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class MerchanrRegistrationPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\MerchantGui\MerchantGuiConfig::PREFIX_MERCHANT_URL
     *
     * @var string
     */
    public const PREFIX_MERCHANT_URL = 'merchant';
}
