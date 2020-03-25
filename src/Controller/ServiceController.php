<?php

namespace App\Controller;

interface ServiceInterface
{
    public function getLastCommit();
}

abstract class ServiceController implements ServiceInterface
{
   protected $commit;

   public function getLastCommit()
   {
      return $this->commit;
   }
 
}


