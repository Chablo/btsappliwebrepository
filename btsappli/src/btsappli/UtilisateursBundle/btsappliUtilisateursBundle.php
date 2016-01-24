<?php

namespace btsappli\UtilisateursBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class btsappliUtilisateursBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}