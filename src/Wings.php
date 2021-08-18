<?php

namespace JulianLouis\WingsPHP;

use GuzzleHttp\Client;
use JulianLouis\WingsPHP\Managers\BackupAPI;
use JulianLouis\WingsPHP\Managers\GlobalAPI;
use JulianLouis\WingsPHP\Managers\HttpClient;
use JulianLouis\WingsPHP\Managers\ServerAPI;

final class Wings extends HttpClient
{
    public Client $http;
    public GlobalAPI $global;
    public ServerAPI $server;
    public BackupAPI $backup;

    public string $base_uri;
    public string $token;

    public function __construct($base_uri, $token)
    {
        $this->http = new Client([
            'base_uri' => $base_uri,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
                'Content-Type' => 'application/json',
            ],
        ]);

        $this->global = new GlobalAPI($this);
        $this->server = new ServerAPI($this);
        $this->backup = new BackupAPI($this);

    }

}
