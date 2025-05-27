<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage\Form\DataProvider;

use Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Client\Store\StoreClientInterface;
use SprykerDemo\Yves\MerchantRegistrationPage\Form\MerchantRegisterForm;
use SprykerDemo\Yves\MerchantRegistrationPage\MerchantRegistrationPageConfig;

class MerchantRegisterFormDataProvider
{
    /**
     * @var string
     */
    public const COUNTRY_GLOSSARY_PREFIX = 'countries.iso.';

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected StoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(StoreClientInterface $storeClient)
    {
        $this->storeClient = $storeClient;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer
     */
    public function getData(): MerchantRegistrationFormDataTransfer
    {
        return $this->setInitialUrlPrefix(new MerchantRegistrationFormDataTransfer());
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            MerchantRegisterForm::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
        ];
    }

    /**
     * @return array<mixed, mixed>
     */
    protected function getAvailableCountries(): array
    {
        $countries = [];

        foreach ($this->storeClient->getCurrentStore()->getCountries() as $iso2Code) {
            $countries[$iso2Code] = static::COUNTRY_GLOSSARY_PREFIX . $iso2Code;
        }

        return $countries;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer
     */
    protected function setInitialUrlPrefix(MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer): MerchantRegistrationFormDataTransfer
    {
        $merchantRegistrationFormDataTransfer->setUrl((new UrlTransfer())->setUrlPrefix('/' . MerchantRegistrationPageConfig::PREFIX_MERCHANT_URL . '/'));

        return $merchantRegistrationFormDataTransfer;
    }
}
