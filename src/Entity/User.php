<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ApiResource(
 *   normalizationContext={"groups"={"user:read"}},
 *   denormalizationContext={"groups"={"user:write"}}
 * )
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   */
  private ?int $id = null;

  /**
   * @ORM\Column(type="string", length=180, unique=true)
   * @Groups({"user:write", "user:read"})
   */
  private ?string $username = null;

  /**
   * @ORM\Column(type="json")
   * @Groups({"user:write", "user:read"})
   */
  private array $roles = [];

  /**
   * @var string|null The hashed password
   * @ORM\Column(type="string")
   * @Groups({"user:write", "user:read"})
   */
  private ?string $password = null;


  /**
   * @var string|null
   * @SerializedName("password")
   * @Groups({"user:write", "user:read"})
   */
  private ?string $plainPassword = null;

  public function setPlainPassword(string $password): self
  {
    $this->plainPassword = $password;

    return $this;
  }

  public function getPlainPassword(): ?string
  {
    return $this->plainPassword;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  /**
   * A visual identifier that represents this user.
   *
   * @see UserInterface
   */
  public function getUsername(): string
  {
    return (string)$this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  public function setRoles(array $roles): self
  {
    $this->roles = $roles;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getPassword(): string
  {
    return (string)$this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function getSalt()
  {
    // not needed when using the "bcrypt" algorithm in security.yaml
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials()
  {
    // If you store any temporary, sensitive data on the user, clear it here
    $this->plainPassword = null;
  }

  /**
   * @Groups({"user:read"})
   */
  public function getCalculatedField(): int {
    return 5*5;
  }
}
