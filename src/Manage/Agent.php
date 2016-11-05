<?php
namespace Corp\Manage;

use Corp\Manager;
use Corp\Traits;
use Corp\Uri;

class Agent extends Manager
{
    use Traits;

    public function setOpt($agentId)
    {

    }

    public function getInfo($agentId)
    {
        $res = self::httpsGet(Uri::AGENT_GET, [$this->token, $agentId]);

        return $res;
    }

    public function getList()
    {

    }
}