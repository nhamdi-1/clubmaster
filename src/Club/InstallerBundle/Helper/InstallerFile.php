<?php

namespace Club\InstallerBundle\Helper;

class InstallerFile
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function installerOpen($exception=false)
    {
        $file = $this->container->get('kernel')->getRootDir().'/installer';

        if (file_exists($file)) return true;

        return false;
    }
}
