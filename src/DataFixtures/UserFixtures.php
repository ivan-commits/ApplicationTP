<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for($i=0; $i<10; $i++)
        {
            $user = new User();

            $user->setEmail(sprintf('test%d@example.com',$i))
            ->setFirstName(sprintf('FirstName%d',$i))
            ->setSurname(sprintf('Surname%d',$i))
            ->setDateDeNaissance(new \DateTime())
            ->setRoles(['ROLE_USER'])
            ->setSurnameEducateur(sprintf('SurnameEducateur%d',$i))
            ->setFirstnameEducateur(sprintf('FirstnameEducateur%d',$i))
            ->setSurnameOrthophoniste(sprintf('FirstnameEducateur%d',$i))
            ->setFirstnameOrthophoniste(sprintf('FirstnameOrthophoniste%d',$i))
            ->setSurnameParent1(sprintf('SurnameParent1%d',$i))
            ->setFirstNameParent1(sprintf('FirstNameParent1%d',$i))
            ->setSurnameParent2(sprintf('SurnameParent2%d',$i))
            ->setFirstnameParent2(sprintf('FirstnameParent2%d',$i))
            ->setEnabled(true)
            ->setConfirmationResetPassword('')
            ->setConfirmationToken('')
            ->setPassword($this->passwordEncoder->encodePassword($user,'testtest'));

            $manager->persist($user);
          
        };


        $manager->flush();
    }

}
