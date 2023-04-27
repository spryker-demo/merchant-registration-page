<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class MerchantRegistrationPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @var string
     */
    public const STORE_CLIENT = 'STORE_CLIENT';

    /**
     * @var string
     */
    public const MERCHANT_REGISTRATION_CLIENT = 'MERCHANT_REGISTRATION_CLIENT';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addStore($container);
        $container = $this->addStoreClient($container);
        $container = $this->addMerchantRegistrationClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container->set(static::STORE, function () {
            return Store::getInstance();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStoreClient(Container $container): Container
    {
        $container->set(static::STORE_CLIENT, function (Container $container) {
            return $container->getLocator()->store()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantRegistrationClient(Container $container): Container
    {
        $container->set(static::MERCHANT_REGISTRATION_CLIENT, function (Container $container) {
            return $container->getLocator()->merchantRegistration()->client();
        });

        return $container;
    }
}
