<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage\Form\Validator\Constraints;

use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @method \SprykerDemo\Yves\MerchantRegistrationPage\MerchantRegistrationPageFactory getFactory()
 */
class CompanyNameUniqueConstraintValidator extends ConstraintValidator
{
    use VerifiesMerchantCreationAvailability;

    /**
     * @param mixed|string $value
     * @param \Symfony\Component\Validator\Constraint $constraint
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     *
     * @return void
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value) {
            return;
        }

        if (!$constraint instanceof CompanyNameUniqueConstraint) {
            throw new UnexpectedTypeException($constraint, CompanyNameUniqueConstraint::class);
        }

        $merchantTransfer = $constraint->getMerchantRegistrationClient()
            ->merchantExists(
                (new MerchantCriteriaTransfer())->setName($value),
            );

        if ($this->merchantHasAnyExistingData($merchantTransfer)) {
            $this->context->buildViolation($constraint->getMessage())->addViolation();
        }
    }
}
