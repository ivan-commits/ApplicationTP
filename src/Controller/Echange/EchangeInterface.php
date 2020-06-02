<?php
namespace App\Controller\Echange;

use Twig\Environment;

interface EchangeInterface{
    public function __invoke(Environment $environment);
}