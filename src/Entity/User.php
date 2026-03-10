<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'Email déjà utilisé')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // MAIL
    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire.")]
    #[Assert\Email(
        message: "L'adresse email '{{ value }}' n'est pas valide."
    )]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    // PLAINPASSWORD
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire.")]
    #[Assert\Length(
        min: 3,
        minMessage: "Le mot de passe doit contenir au moins 3 caractères."
    )]
    private ?string $plainPassword = null;

    // FIRSTNAME
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire.")]
    #[Assert\Length(
        min: 3,
        minMessage: "Le prénom doit contenir au moins 3 lettres."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ]+$/",
        message: "Le prénom ne doit contenir que des lettres."
    )]
    private ?string $firstName = null;

    // LASTNAME
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom est obligatoire.")]
    #[Assert\Length(
        min: 2,
        minMessage: "Le nom doit contenir au moins 2 lettres."
    )]
    #[Assert\Regex(
        pattern: "/^[a-zA-ZÀ-ÿ]+$/",
        message: "Le nom ne doit contenir que des lettres."
    )]
    private ?string $lastName = null;

    // CGU
    #[ORM\Column]
    #[Assert\IsTrue(message: "Vous devez accepter les CGU.")]
    private ?bool $acceptedTerms = null;

    /**
     * @var Collection<int, Order>
     */
    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: 'customer', orphanRemoval: true)]
    private Collection $orders;

    #[ORM\Column]
    private ?bool $apiAccess = false;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
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

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // Plainpassword
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    /**
     * Ensure the session doesn't contain actual password hashes by CRC32C-hashing them, as supported since Symfony 7.3.
     */
    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);

        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void
    {
        // @deprecated, to be removed when upgrading to Symfony 8
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isAcceptedTerms(): ?bool
    {
        return $this->acceptedTerms;
    }

    public function setAcceptedTerms(bool $acceptedTerms): static
    {
        $this->acceptedTerms = $acceptedTerms;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): static
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): static
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }

    public function isApiAccess(): ?bool
    {
        return $this->apiAccess;
    }

    public function setApiAccess(bool $apiAccess): static
    {
        $this->apiAccess = $apiAccess;

        return $this;
    }
}
