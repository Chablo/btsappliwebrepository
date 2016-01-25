<?php

namespace btsappli\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class btsappliUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}