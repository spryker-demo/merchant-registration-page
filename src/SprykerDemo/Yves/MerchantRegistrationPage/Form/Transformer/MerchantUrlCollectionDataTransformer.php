<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerDemo\Yves\MerchantRegistrationPage\Form\Transformer;

use ArrayObject;
use Symfony\Component\Form\DataTransformerInterface;

class MerchantUrlCollectionDataTransformer implements DataTransformerInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>
     */
    public function transform(mixed $value): ArrayObject
    {
        $merchantUrlCollection = new ArrayObject();
        if (!$value) {
            return $merchantUrlCollection;
        }
        foreach ($value as $urlTransfer) {
            $url = $urlTransfer->getUrl();
            $url = preg_replace('#^' . $urlTransfer->getUrlPrefix() . '#i', '', $url ?: '');
            $urlTransfer->setUrl($url);
            $merchantUrlCollection->append($urlTransfer);
        }

        return $merchantUrlCollection;
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $value
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\UrlTransfer>
     */
    public function reverseTransform(mixed $value): ArrayObject
    {
        $merchantUrlCollection = new ArrayObject();
        if (!$value) {
            return $merchantUrlCollection;
        }
        foreach ($value as $urlTransfer) {
            $urlPrefix = $urlTransfer->getUrlPrefix();
            if ($urlPrefix) {
                $url = $urlTransfer->getUrl();
                if ($url) {
                    if (preg_match('#^' . $urlPrefix . '#i', $url) > 0) {
                        $merchantUrlCollection->append($urlTransfer);

                        continue;
                    }
                    $url = preg_replace('#^/#', '', $url);
                    $urlWithPrefix = $urlPrefix . $url;
                    $urlTransfer->setUrl($urlWithPrefix);
                    $merchantUrlCollection->append($urlTransfer);
                }
            }
        }

        return $merchantUrlCollection;
    }
}
