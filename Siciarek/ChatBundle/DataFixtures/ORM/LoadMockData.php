<?php

namespace Siciarek\ChatBundle\ORM\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Siciarek\ChatBundle\DataFixtures\BasicFixture;

class LoadMockData extends BasicFixture
{
    protected $order = 100;
    public $count = 0;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $om)
    {
    }
}
