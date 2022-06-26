<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pertanyaan
 *
 * @ORM\Table(name="pertanyaan")
 * @ORM\Entity
 */
class Pertanyaan
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_pertanyaan", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPertanyaan;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_name", type="string", length=100, nullable=true)
     */
    private $userName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="judul_tanya", type="string", length=255, nullable=true)
     */
    private $judulTanya;

    /**
     * @var string|null
     *
     * @ORM\Column(name="tanya", type="text", length=65535, nullable=true)
     */
    private $tanya;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function getIdPertanyaan(): ?int
    {
        return $this->idPertanyaan;
    }

    public function getUserName(): ?string
    {
        return $this->userName;
    }

    public function setUserName(?string $userName): self
    {
        $this->userName = $userName;

        return $this;
    }

    public function getJudulTanya(): ?string
    {
        return $this->judulTanya;
    }

    public function setJudulTanya(?string $judulTanya): self
    {
        $this->judulTanya = $judulTanya;

        return $this;
    }

    public function getTanya(): ?string
    {
        return $this->tanya;
    }

    public function setTanya(?string $tanya): self
    {
        $this->tanya = $tanya;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


}
