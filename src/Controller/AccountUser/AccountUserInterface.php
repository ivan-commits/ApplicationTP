<?php

namespace App\Controller\AccountUser;

use Symfony\Component\Security\Core\Security;
use Twig\Environment;

interface AccountUserInterface {

    public function __invoke(Security $security, Environment $environment);
}