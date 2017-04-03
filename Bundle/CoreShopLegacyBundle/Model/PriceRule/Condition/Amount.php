<?php
/**
 * CoreShop.
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Bundle\CoreShopLegacyBundle\Model\PriceRule\Condition;

use CoreShop\Bundle\CoreShopLegacyBundle\Exception;
use CoreShop\Bundle\CoreShopLegacyBundle\Model\Cart\PriceRule;
use CoreShop\Bundle\CoreShopLegacyBundle\Model\Cart;
use CoreShop\Bundle\CoreShopLegacyBundle\Model;

/**
 * Class Amount
 * @package CoreShop\Bundle\CoreShopLegacyBundle\Model\PriceRule\Condition
 */
class Amount extends AbstractCondition
{
    /**
     * @var string
     */
    public static $type = 'amount';

    /**
     * @var int
     */
    public $currency;

    /**
     * @var float
     */
    public $minAmount;

    /**
     * @var float
     */
    public $maxAmount;

    /**
     * @return mixed
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return float
     */
    public function getMinAmount()
    {
        return $this->minAmount;
    }

    /**
     * @param float $minAmount
     */
    public function setMinAmount($minAmount)
    {
        $this->minAmount = $minAmount;
    }

    /**
     * @return float
     */
    public function getMaxAmount()
    {
        return $this->maxAmount;
    }

    /**
     * @param float $maxAmount
     */
    public function setMaxAmount($maxAmount)
    {
        $this->maxAmount = $maxAmount;
    }

    /**
     * Check if Cart is Valid for Condition.
     *
     * @param Cart       $cart
     * @param PriceRule  $priceRule
     * @param bool|false $throwException
     *
     * @return bool
     *
     * @throws Exception
     */
    public function checkConditionCart(Cart $cart, PriceRule $priceRule, $throwException = false)
    {
        //Check Cart Amount
        if ($this->getMinAmount() > 0) {
            $minAmount = $this->getMinAmount();
            $minAmount = \CoreShop\Bundle\CoreShopLegacyBundle\CoreShop::getTools()->convertToCurrency($minAmount, Model\Currency::getById($this->getCurrency()), \CoreShop\Bundle\CoreShopLegacyBundle\CoreShop::getTools()->getCurrency());

            $cartTotal = $cart->getSubtotal();

            if ($minAmount > $cartTotal) {
                if ($throwException) {
                    throw new Exception('You have not reached the minimum amount required to use this voucher');
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Check if Product is Valid for Condition.
     *
     * @param Model\Product $product
     * @param Model\Product\AbstractProductPriceRule $priceRule
     *
     * @return bool
     */
    public function checkConditionProduct(Model\Product $product, Model\Product\AbstractProductPriceRule $priceRule)
    {
        //Amount is not available for product rules
        return false;
    }
}