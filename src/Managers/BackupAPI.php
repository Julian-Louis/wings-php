<?php

namespace JulianLouis\WingsPHP\Managers;

class BackupAPI
{


    private string $routePrefix = '/api/servers/';

    private HttpClient $manager;

    public function __construct(HttpClient $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @see BackupAdapterEnum for $adapter values
     */
    public function create(string $uuid, string $adapter, string $backupUuid, bool $ignore)
    {
        return $this->manager->request('POST', "$this->routePrefix$uuid/backup", ['adapter' => $adapter, 'uuid' => $backupUuid, 'ignore' => $ignore]);
    }

    public function restore(string $uuid, string $backupUuid)
    {
        return $this->manager->request('POST', "$this->routePrefix$uuid/backup/$backupUuid/restore");
    }


}
