<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage\Form\DataProvider;

use Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer;
use Spryker\Shared\Kernel\Store;
use SprykerDemo\Yves\MerchantRegistrationPage\Form\MerchantRegisterForm;

class MerchantRegisterFormDataProvider
{
    /**
     * @var string
     */
    public const COUNTRY_GLOSSARY_PREFIX = 'countries.iso.';

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer
     */
    public function getData(): MerchantRegistrationFormDataTransfer
    {
        return new MerchantRegistrationFormDataTransfer();
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            MerchantRegisterForm::OPTION_COUNTRY_CHOICES => $this->getAvailableCountries(),
        ];
    }

    /**
     * @return array
     */
    protected function getAvailableCountries(): array
    {
        $countries = [];

        foreach ($this->store->getCountries() as $iso2Code) {
            $countries[$iso2Code] = static::COUNTRY_GLOSSARY_PREFIX . $iso2Code;
        }

        return $countries;
    }
}
