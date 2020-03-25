<?php

namespace App\Controller;

class GithubController extends ServiceController
{

    public function __construct(string $account = '',string $repository = '', string $branch = 'master')
    {
        $this->client = new \Github\Client();
        $this->setLastCommit($account,$repository,$branch);
    }

    public function setLastCommit($account,$repository,$branch)
    {
        $commits = $this->client->api('repo')->commits()->all($account, $repository , array('sha' => $branch));

        $this->commit = $commits[0]['sha'];
    }
}


