<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jawaban
 *
 * @ORM\Table(name="jawaban", indexes={@ORM\Index(name="jawaban_ibfk_1", columns={"pertanyaan_id"})})
 * @ORM\Entity
 */
class Jawaban
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_jawaban", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idJawaban;

    /**
     * @var string|null
     *
     * @ORM\Column(name="user_name", type="string", length=100, nullable=true)
     */
    private $userName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="jawab", type="text", length=65535, nullable=true)
     */
    private $jawab;

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

    /**
     * @var int|null
     *
     * @ORM\Column(name="vote", type="integer", nullable=true)
     */
    private $vote;

    /**
     * @var int|null
     *
     * @ORM\Column(name="approve_status", type="integer", nullable=true)
     */
    private $approveStatus;

    /**
     * @var \Pertanyaan
     *
     * @ORM\ManyToOne(targetEntity="Pertanyaan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pertanyaan_id", referencedColumnName="id_pertanyaan", onDelete="CASCADE")
     * })
     */
    private $pertanyaan;

    public function getIdJawaban(): ?int
    {
        return $this->idJawaban;
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

    public function getJawab(): ?string
    {
        return $this->jawab;
    }

    public function setJawab(?string $jawab): self
    {
        $this->jawab = $jawab;

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

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(?int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getApproveStatus(): ?int
    {
        return $this->approveStatus;
    }

    public function setApproveStatus(?int $approveStatus): self
    {
        $this->approveStatus = $approveStatus;

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
