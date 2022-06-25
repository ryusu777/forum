<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TagPertanyaan
 *
 * @ORM\Table(name="tag_pertanyaan", indexes={@ORM\Index(name="id_pertanyaan", columns={"pertanyaan_id"}), @ORM\Index(name="id_tag", columns={"tag_id"})})
 * @ORM\Entity
 */
class TagPertanyaan
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_tag_pertanyaan", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idTagPertanyaan;

    /**
     * @var \Tag
     *
     * @ORM\ManyToOne(targetEntity="Tag")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tag_id", referencedColumnName="id_tag")
     * })
     */
    private $tag;

    /**
     * @var \Pertanyaan
     *
     * @ORM\ManyToOne(targetEntity="Pertanyaan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pertanyaan_id", referencedColumnName="id_pertanyaan")
     * })
     */
    private $pertanyaan;

    public function getIdTagPertanyaan(): ?int
    {
        return $this->idTagPertanyaan;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getPertanyaan(): ?Pertanyaan
    {
        return $this->pertanyaan;
    }

    public function setPertanyaan(?Pertanyaan $pertanyaan): self
    {
        $this->pertanyaan = $pertanyaan;

        return $this;
    }


}
