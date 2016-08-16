<?php

use Inviqa\LaunchDarklyBundle\Client\StaticClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function indexAction() {
        if ($this->get('inviqa_launchdarkly.client')->isOn('new-homepage-content')) {
            return new Response("<html><body>the new homepage content</body></html>");
        }
        return new Response("<html><body>the old homepage content</body></html>");
    }

    public function templatedAction() {
        return $this->render('index.html.twig');
    }

    public function serviceAction() {
        return new Response($this->get('inviqa_launchdarkly.test_service')->getContent());
    }

    public function aliasedServiceAction() {
        return new Response($this->get('inviqa_launchdarkly.aliased_test_service')->getContent());
    }

    public function taggedServiceAction() {
        return new Response($this->get('inviqa_launchdarkly.tagged_test_service')->getContent());
    }

    public function staticAction() {
        if (StaticClient::isOn('new-static-access-content')) {
            return new Response("<html><body>the new static access content</body></html>");
        }
        return new Response("<html><body>the old static access content</body></html>");
    }
}