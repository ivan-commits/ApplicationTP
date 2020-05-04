<?php

namespace App\DataFixtures;

use App\Entity\ApiToken;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends BaseFixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(10,'main_users',function($i) use ($manager){
            $user = new User();
            $user->setEmail(sprintf('spacebar%d@example.com',$i));
            $user->setFirstName($this->faker->firstName);
            $user->setSurname('surname test');
            $user->setPassword($this->passwordEncoder->encodePassword(
               $user,'engage'
            ));
            $apiToken1 = new ApiToken($user);
            $apiToken2 = new ApiToken($user);
            $manager->persist($apiToken1);
            $manager->persist($apiToken2);
            return $user;
        });

        $this->createMany(3,'admin_user',function($i){
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com',$i));
            $user->setFirstName($this->faker->firstName);
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,'engage'
            ));
            return $user;
        });

        $manager->flush();
    }
}
