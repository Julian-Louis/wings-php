<?php

namespace JulianLouis\WingsPHP\Managers;

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

class ServerAPI
{
    public string $route_prefix = '/api/servers';

    private HttpClient $manager;

    public function __construct(HttpClient $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->manager->request('GET', "$this->route_prefix");
    }

    public function create()
    {        
        $a = gen_uuid();
        echo $a;

        $values = [
            "uuid" => $a,
            "invocation" => "",
            "skip_egg_scripts" => false,
            "start_on_completion" => true,
            "build" => [
                "memory" => 1000,
                "swap" => 1000,
                "io" => 100,
                "cpu" => 100,
                "disk" => 1000,
                "threads" => 8
            ],
            "allocations" => [
                "default" => [
                    "ip" => "127.0.0.1",
                    "port" => 25655
                ],
                "mappings" => [
                    "127.0.0.1" => [25655]
                ]
            ],
            "environment" => [
                "variables" => ["EZ" => "WOW"]
            ],
            "container" => [
                "image" => "quay.io/pterodactyl/core:source"
            ]
        ];

        return $this->manager->request('POST', "$this->route_prefix", $values);
    }

    public function get($uuid)
    {

        return $this->manager->request('GET', "$this->route_prefix/$uuid");
    }


    public function delete($uuid) : array
    {
        return $this->manager->request('DELETE', "$this->route_prefix/$uuid");
    }

    public function patch($uuid)
    {
        return $this->manager->request('PATCH', "$this->route_prefix/$uuid");
    }

    public function logs($uuid)
    {
        return $this->manager->request('GET', "$this->route_prefix/$uuid/logs");
    }

    public function start($uuid): bool
    {
        $this->manager->request('POST', "$this->route_prefix/$uuid/power", ['action' => 'start']);
        return true;
    }

    public function stop($uuid): bool
    {
        $this->manager->request('POST', "$this->route_prefix/$uuid/power", ['action' => 'stop']);
        return true;
    }

    public function restart($uuid): bool
    {
        $this->manager->request('POST', "$this->route_prefix/$uuid/power", ['action' => 'restart']);
        return true;
    }

    public function kill($uuid): bool
    {
        $this->manager->request('POST', "$this->route_prefix/$uuid/power", ['action' => 'kill']);
        return true;
    }

    public function commands(string $uuid, array $commands): bool
    {
        $this->manager->request('POST', "$this->route_prefix/$uuid/commands", ['commands' => $commands]);
        return true;
    }

    public function install(string $uuid): bool
    {
        $this->manager->request('POST', "$this->route_prefix/$uuid/install");
        return true;
    }

    public function reinstall(string $uuid): bool
    {
        $this->manager->request('POST', "$this->route_prefix/$uuid/reinstall");
        return true;
    }

}

