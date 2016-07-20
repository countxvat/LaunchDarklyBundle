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

    public function isOn($key, Context $context = null, $default = false)
    {
        $context = $this->addContext($context);
        $on = $this->inner->isOn($key, $context, $default);
        $this->logger->logFlagRequest($key, $on, $context);

        return $on;
    }

    public function toggle($key, $user, Context $context = null, $default = false)
    {
        $context = $this->addContext($context);
        $on = $this->inner->toggle($key, $user, $context, $default);
        $this->logger->logFlagRequest($key, $on, $context);

        return $on;
    }

    public function getFlag($key, $user, Context $context = null, $default = false)
    {
        $context = $this->addContext($context);
        $on = $this->inner->getFlag($key, $user, $context, $default);
        $this->logger->logFlagRequest($key, $on, $context);

        return $on;
    }

    private function addContext(Context $context = null)
    {
        $context = $context ?: new Context();
        if (!$context->type) {
            $context->type = "code";
            $context->backtrace = debug_backtrace(2);
        }

        return $context;
    }
}
