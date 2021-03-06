<?php

namespace App\Entity\AbstractEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractNameEntity
 */
abstract class AbstractNameEntity extends \App\Entity\AbstractEntity\AbstractExtendedPropertiesEntity
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?: '';
    }
}
