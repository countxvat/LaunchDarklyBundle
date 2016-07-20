<?php

namespace Inviqa\LaunchDarklyBundle\Client;

use Inviqa\LaunchDarklyBundle\Profiler\Context;
use Inviqa\LaunchDarklyBundle\User\UserFactory;
use Inviqa\LaunchDarklyBundle\User\IdProvider;

class UserProvidingClient extends ClientDecorator implements Client
{
    private $idProvider;
    private $userFactory;

    public function __construct(Client $inner, IdProvider $idProvider, UserFactory $userFactory)
    {
        $this->idProvider = $idProvider;
        $this->userFactory = $userFactory;
        parent::__construct($inner);
    }

    public function isOn($key, $default = false, Context $context = null)
    {
        return $this->toggle($key, $this->userFactory->create($this->idProvider->userId()), $default, $context);
    }
}