<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *   normalizationContext={"groups"={"transaction:read"}}
 * )
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 */
class Transaction
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @Groups({"transaction:read"})
   */
  private $id;

  /**
   * @ORM\Column(type="float")
   * @Groups({"transaction:read"})
   */
  private $amount;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getAmount(): ?float
  {
    return $this->amount;
  }

  public function setAmount(float $amount): self
  {
    $this->amount = $amount;

    return $this;
  }

  /**
   * @Groups({"transaction:read"})
   */
  public function getCalcVal(): int
  {
    return 10;
  }
}
