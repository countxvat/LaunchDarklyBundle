<?php

namespace Inviqa\LaunchDarklyBundle\Client;

use Inviqa\LaunchDarklyBundle\Profiler\Context;
use Inviqa\LaunchDarklyBundle\Profiler\FlagCollector;

class LoggingClient extends ClientDecorator implements Client
{
    private $logger;

    public function __construct(Client $inner, FlagCollector $logger)
    {
        $this->logger = $logger;
        parent::__construct($inner);
    }

    public function isOn($key, $default = false, Context $context = null)
    {
        $context = $this->getContext($context);
        $on = $this->inner->isOn($key, $default, $context);
        $this->logger->logFlagRequest($key, $on, $context);

        return $on;
    }

    public function toggle($key, $user, $default = false, Context $context = null)
    {
        $context = $this->getContext($context);
        $on = $this->inner->toggle($key, $user, $default, $context);
        $this->logger->logFlagRequest($key, $on, $context);

        return $on;
    }

    public function getFlag($key, $user, $default = false, Context $context = null)
    {
        $context = $this->getContext($context);
        $on = $this->inner->getFlag($key, $user, $default, $context);
        $this->logger->logFlagRequest($key, $on, $context);

        return $on;
    }

    private function getContext(Context $context = null)
    {
        return $context ?: Context::fromCode(debug_backtrace(2));
    }
}
