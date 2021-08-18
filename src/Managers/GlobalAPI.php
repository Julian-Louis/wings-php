<?php

namespace JulianLouis\WingsPHP\Managers;

class GlobalAPI
{

    private HttpClient $manager;

    public function __construct(HttpClient $manager)
    {
        $this->manager = $manager;
    }

    public function system_info()
    {

        return $this->manager->request('GET', '/api/system');
    }

}
