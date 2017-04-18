<?php

namespace CoreShop\Bundle\CoreBundle\Product\Rule\Condition;

use CoreShop\Component\Customer\Model\CustomerInterface;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Resource\Model\ResourceInterface;
use CoreShop\Component\Rule\Condition\ConditionCheckerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Webmozart\Assert\Assert;

class CustomerGroupsConditionChecker implements ConditionCheckerInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid($subject, array $configuration)
    {
        $customer = $this->tokenStorage->getToken()->getUser();

        if (!$customer instanceof CustomerInterface) {
            return false;
        }

        foreach ($customer->getCustomerGroups() as $group) {
            if ($group instanceof ResourceInterface) {
                if (in_array($group->getId(), $configuration['customerGroups'])) {
                    return true;
                }
            }
        }

        return false;
    }
}
