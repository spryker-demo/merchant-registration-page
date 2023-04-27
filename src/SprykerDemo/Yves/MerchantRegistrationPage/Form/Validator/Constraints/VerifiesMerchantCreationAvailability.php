<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage\Form\Validator\Constraints;

use Generated\Shared\Transfer\MerchantTransfer;

trait VerifiesMerchantCreationAvailability
{
    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return bool
     */
    protected function merchantHasAnyExistingData(MerchantTransfer $merchantTransfer): bool
    {
        return count(
            array_filter($merchantTransfer->toArray(), function ($value) {
                if (is_array($value)) {
                    return count($value) > 0;
                }

                return $value !== null;
            }),
        ) > 0;
    }
}
