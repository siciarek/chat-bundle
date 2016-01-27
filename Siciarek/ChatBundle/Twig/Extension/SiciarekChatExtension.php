<?php

namespace Siciarek\ChatBundle\Twig\Extension;

use Assetic\Test\Filter\JSMinFilterTest;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\Templating\Helper\Helper;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SiciarekChatExtension extends \Twig_Extension
{

    private static $firstAdSet = false;
    /**
     * @var \Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'siciarek_chat_twig_extension';
    }
}
