<?php

declare(strict_types=1);

namespace App\Service\Traits\Redirect;

use Doctrine\ORM\Mapping as ORM;

trait Publishable
{
    /**
     * @ORM\Column(type="boolean")
     */
    private $publish;

    public function setPublish(?bool $publish): self
    {
        $this->publish = $publish;

        return $this;
    }

    public function getPublish(): ?bool
    {
        return $this->publish;
    }
}
