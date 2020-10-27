<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{

  /**
   * @var EntityManagerInterface
   */
  private EntityManagerInterface $entityManager;
  /**
   * @var UserPasswordEncoderInterface
   */
  private UserPasswordEncoderInterface $passwordEncoder;

  public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
  {
    $this->entityManager = $entityManager;
    $this->passwordEncoder = $passwordEncoder;
  }

  public function supports($data, array $context = []): bool
  {
    $httpMethod = $this->getHttpMethodFromContext($context);

    return ($data instanceof User
      && in_array($httpMethod, ['post', 'put', 'patch'])
    );

  }

  /**
   * @param User $data
   * @param array $context
   * @return object|void
   */
  public function persist($data, array $context = [])
  {
    if($data->getPlainPassword()) {
      $data->setPassword(
        $this->passwordEncoder->encodePassword($data, $data->getPlainPassword())
      );
    }

    $data->eraseCredentials();

    $this->entityManager->persist($data);
    $this->entityManager->flush();
  }

  public function remove($data, array $context = [])
  {
    $this->entityManager->remove($data);
    $this->entityManager->flush();
  }


  private function getHttpMethodFromContext($context)
  {
    return isset($context['item_operation_name']) ? $context['item_operation_name'] : $context['collection_operation_name'];
  }

}