<?php

namespace Siciarek\ChatBundle\DataFixtures\ORM;

use Siciarek\ChatBundle\DataFixtures\BasicFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends BasicFixture {

    /**
     * @var numeric
     */
    protected $order = 2;

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {

        $data = [
            [
                'enabled' => true,
                'username' => 'system',
                'password' => $this->getContainer()->getParameter('secret'),
                'email' => 'siciarek@hotmail.com',
                'groups' => [

                ]
            ],
            [
                'enabled' => true,
                'username' => 'jsiciarek',
                'password' => 'pass',
                'email' => 'siciarek@gmail.com',
                'groups' => [
                    'Superadmins',
                ]
            ],
            [
                'enabled' => true,
                'username' => 'colak',
                'password' => 'pass',
                'email' => 'colak@gmail.com',
                'groups' => [
                    'Admins',
                ]
            ],
            [
                'enabled' => true,
                'username' => 'molak',
                'password' => 'pass',
                'email' => 'molak@gmail.com',
                'groups' => [
                    'Users',
                ]
            ],
        ];

        foreach ($data as $o) {
            $user = new \Siciarek\ChatBundle\Entity\User();
            $user->setEnabled($o['enabled']);
            $user->setUsername($o['username']);
            $user->setEmail($o['email']);
            $user->setPlainPassword($o['password']);

            foreach ($o['groups'] as $group) {
                $user->addGroup($this->getReference('group' . $group));
            }
            $manager->persist($user);
            $manager->flush();
            
            $this->setReference('user' . $user->getUsername(), $user);
        }
    }

}
