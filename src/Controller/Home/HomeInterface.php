<?php
namespace App\Controller\Home;

use Twig\Environment;

interface HomeInterface{
    public function __invoke(Environment $environment);
}