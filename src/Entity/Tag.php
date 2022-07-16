<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity
 */
class Tag
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_tag", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTag;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nama_tag", type="string", length=100, nullable=true)
     */
    private $namaTag;

    public function getIdTag(): ?int
    {
        return $this->idTag;
    }

    public function getNamaTag(): ?string
    {
        return $this->namaTag;
    }

    public function setNamaTag(?string $namaTag): self
    {
        $this->namaTag = $namaTag;

        return $this;
    }


}
