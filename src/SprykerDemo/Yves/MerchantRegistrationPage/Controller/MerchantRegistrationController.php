<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage\Controller;

use Generated\Shared\Transfer\MerchantProfileAddressTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer;
use Generated\Shared\Transfer\MerchantResponseTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerDemo\Yves\MerchantRegistrationPage\MerchantRegistrationPageFactory getFactory()
 */
class MerchantRegistrationController extends AbstractController
{
    /**
     * @var string
     */
    protected const MERCHANT_AUTHORIZATION_SUCCESS = 'merchant.account.authorization.success';

    /**
     * @var string
     */
    protected const STATUS_WAITING_FOR_APPROVAL = 'waiting-for-approval';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    public function registerAction(Request $request)
    {
        $response = $this->executeRegisterAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@MerchantRegistrationPage/views/merchant-registration/merchant-registration.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array<string, mixed>
     */
    protected function executeRegisterAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createMerchantFormFactory()
            ->createMerchantRegisterFormDataProvider();

        $merchantRegistrationForm = $this
            ->getFactory()
            ->createMerchantFormFactory()
            ->getMerchantRegisterForm(
                $dataProvider->getData(),
                $dataProvider->getOptions(),
            )
            ->handleRequest($request);

        if ($merchantRegistrationForm->isSubmitted() && $merchantRegistrationForm->isValid()) {
            $merchantResponseTransfer = $this->registerMerchant($merchantRegistrationForm->getData());

            if ($merchantResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessages($merchantResponseTransfer);

                return $this->redirectResponseInternal('home');
            }

            foreach ($merchantResponseTransfer->getErrors() as $responseMessage) {
                if ($responseMessage->getMessage()) {
                    $this->addErrorMessage($responseMessage->getMessage());
                }
            }
        }

        return [
            'merchantRegistrationForm' => $merchantRegistrationForm->createView(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantResponseTransfer $merchantResponseTransfer
     *
     * @return void
     */
    protected function addSuccessMessages(MerchantResponseTransfer $merchantResponseTransfer): void
    {
        if (!$merchantResponseTransfer->getErrors()->count()) {
            $this->addSuccessMessage(static::MERCHANT_AUTHORIZATION_SUCCESS);

            return;
        }

        foreach ($merchantResponseTransfer->getErrors() as $responseMessageTransfer) {
            if ($responseMessageTransfer->getMessage()) {
                $this->addErrorMessage($responseMessageTransfer->getMessage());
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantResponseTransfer
     */
    protected function registerMerchant(MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer): MerchantResponseTransfer
    {
        $merchantProfileTransfer = $this->getMerchantProfileTransfer($merchantRegistrationFormDataTransfer);
        $merchantTransfer = $this->getMerchantTransfer($merchantRegistrationFormDataTransfer, $merchantProfileTransfer);

        return $this->getFactory()->getMerchantRegistrationClient()->registerMerchant($merchantTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function getMerchantProfileTransfer(MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer): MerchantProfileTransfer
    {
        $merchantProfileTransfer = new MerchantProfileTransfer();
        $merchantProfileTransfer->setContactPersonFirstName($merchantRegistrationFormDataTransfer->getContactPersonFirstName());
        $merchantProfileTransfer->setContactPersonLastName($merchantRegistrationFormDataTransfer->getContactPersonLastName());
        $merchantProfileTransfer->setContactPersonPhone($merchantRegistrationFormDataTransfer->getContactPersonPhone());
        $merchantProfileTransfer->setContactPersonRole($merchantRegistrationFormDataTransfer->getContactPersonRole());
        $merchantProfileTransfer->setContactPersonTitle($merchantRegistrationFormDataTransfer->getContactPersonTitle());

        $merchantProfileAddressTransfer = $this->getMerchantProfileAddressTransfer($merchantRegistrationFormDataTransfer);
        $merchantProfileTransfer->addAddress($merchantProfileAddressTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    protected function getMerchantProfileAddressTransfer(
        MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer
    ): MerchantProfileAddressTransfer {
        $merchantProfileAddressTransfer = new MerchantProfileAddressTransfer();
        $merchantProfileAddressTransfer->setEmail($merchantRegistrationFormDataTransfer->getEmail());
        $merchantProfileAddressTransfer->setAddress1($merchantRegistrationFormDataTransfer->getAddress1());
        $merchantProfileAddressTransfer->setAddress2($merchantRegistrationFormDataTransfer->getAddress2());
        $merchantProfileAddressTransfer->setCity($merchantRegistrationFormDataTransfer->getCity());
        $merchantProfileAddressTransfer->setCountryName($merchantRegistrationFormDataTransfer->getIso2Code());
        $merchantProfileAddressTransfer->setZipCode($merchantRegistrationFormDataTransfer->getZipCode());

        return $merchantProfileAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    private function getMerchantTransfer(
        MerchantRegistrationFormDataTransfer $merchantRegistrationFormDataTransfer,
        MerchantProfileTransfer $merchantProfileTransfer
    ): MerchantTransfer {
        $merchantTransfer = new MerchantTransfer();
        $merchantTransfer->setEmail($merchantRegistrationFormDataTransfer->getEmail());
        $merchantTransfer->setIsActive(false);
        $merchantTransfer->setRegistrationNumber($merchantRegistrationFormDataTransfer->getRegistrationNumber());
        $merchantTransfer->setName($merchantRegistrationFormDataTransfer->getCompanyName());
        $merchantTransfer->setStatus(static::STATUS_WAITING_FOR_APPROVAL);
        $merchantTransfer->setStoreRelation($this->getStoreRelationTransfer());
        $merchantTransfer->setMerchantProfile($merchantProfileTransfer);
        $merchantTransfer->setMerchantReference(uniqid('MER', true));
        $merchantTransfer->setPassword($merchantRegistrationFormDataTransfer->getPassword());
        $merchantTransfer->setUrl($merchantRegistrationFormDataTransfer->getUrl());

        return $merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreRelationTransfer
     */
    protected function getStoreRelationTransfer(): StoreRelationTransfer
    {
        $currentStore = $this->getFactory()->getStoreClient()->getCurrentStore();
        $storeRelationTransfer = new StoreRelationTransfer();
        $storeRelationTransfer->addStores($currentStore);

        return $storeRelationTransfer;
    }
}
