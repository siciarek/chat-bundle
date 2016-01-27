<?php

namespace Siciarek\ChatBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LoadMockData extends BaseFixture
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
