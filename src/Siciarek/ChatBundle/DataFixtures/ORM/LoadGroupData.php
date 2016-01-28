<?php

namespace Siciarek\ChatBundle\DataFixtures\ORM;

use Siciarek\ChatBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadGroupData extends BasicFixture {
    
    /**
     * @var numeric 
     */
    protected $order = 1;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'name' => 'Users',
                'roles' => [
                    'ROLE_USER',
                ]
            ],
            [
                'name' => 'Admins',
                'roles' => [
                    'ROLE_ADMIN',
                ]
            ],
            [
                'name' => 'Superadmins',
                'roles' => [
                    'ROLE_SUPER_ADMIN',
                ]
            ],
        ];
        
        foreach($data as $o) {
            $group = new \Siciarek\ChatBundle\Entity\Group($o['name'], $o['roles']);
            $manager->persist($group);
            $manager->flush();
            $this->setReference('group' . $group->getName(), $group);
        }
        
    }
}