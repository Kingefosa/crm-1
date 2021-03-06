<?php

namespace Oro\Bundle\ChannelBundle\Provider;

use Doctrine\ORM\EntityManager;
use Oro\Bundle\ChannelBundle\Entity\Channel;
use Oro\Bundle\ChannelBundle\Model\ChannelAwareInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestChannelProvider
{
    /** @var RegistryInterface */
    protected $registry;

    /** @var RequestStack */
    protected $requestStack;

    /**
     * @param RequestStack      $requestStack
     * @param RegistryInterface $registry
     */
    public function __construct(RequestStack $requestStack, RegistryInterface $registry)
    {
        $this->registry = $registry;
        $this->requestStack = $requestStack;
    }

    /**
     * @param ChannelAwareInterface|mixed $entity
     */
    public function setDataChannel($entity)
    {
        $channel = $this->getChannelReference();

        if ($channel && $entity instanceof ChannelAwareInterface) {
            $entity->setDataChannel($channel);
        }
    }

    /**
     * @return Channel|bool
     */
    protected function getChannelReference()
    {
        $channelId = $this->requestStack->getCurrentRequest()->query->get('channelId');

        if (!empty($channelId)) {
            /** @var EntityManager $em */
            $em = $this->registry->getManager();

            return $em->getReference('OroChannelBundle:Channel', $channelId);
        }

        return false;
    }
}
