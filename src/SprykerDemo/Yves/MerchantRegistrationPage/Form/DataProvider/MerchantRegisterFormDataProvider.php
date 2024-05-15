<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Shared\Kernel\Store;
use SprykerDemo\Yves\MerchantRegistrationPage\Form\MerchantRegisterForm;
use SprykerDemo\Yves\MerchantRegistrationPage\MerchanrRegistrationPageConfig;

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
        return $this->setInitialUrlCollection(new MerchantRegistrationFormDataTransfer());
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
     * @param \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer
     */
    protected function setInitialUrlCollection(MerchantRegistrationFormDataTransfer $merchantTransfer): MerchantRegistrationFormDataTransfer
    {
        $urlCollection = new ArrayObject();

        foreach ($this->store->getLocales() as $locales) {
            $urlCollection->append(
                $this->setUrlPrefixToUrlTransfer($locales),
            );
        }
        $merchantTransfer->setUrlCollection($urlCollection);

        return $merchantTransfer;
    }

    /**
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function setUrlPrefixToUrlTransfer(string $locale): UrlTransfer
    {
        $urlTransfer = new UrlTransfer();
        $urlTransfer->setLocaleName($locale);
        $urlTransfer->setUrlPrefix(
            $this->getLocalizedUrlPrefix($locale),
        );

        return $urlTransfer;
    }

    /**
     * @return array<mixed, mixed>
     */
    protected function getAvailableCountries(): array
    {
        $countries = [];

        foreach ($this->store->getCountries() as $iso2Code) {
            $countries[$iso2Code] = static::COUNTRY_GLOSSARY_PREFIX . $iso2Code;
        }

        return $countries;
    }

    /**
     * @param string $locale
     *
     * @return string
     */
    protected function getLocalizedUrlPrefix(string $locale): string
    {
        $localeNameParts = explode('_', $locale);
        $languageCode = $localeNameParts[0];

        return '/' . $languageCode . '/' . MerchanrRegistrationPageConfig::PREFIX_MERCHANT_URL . '/';
    }
}
