<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


class TestController extends AbstractController
{

  /**
   * @var SerializerInterface
   */
  private $serializer;

  public function __construct(SerializerInterface $serializer)
  {

    $this->serializer = $serializer;
    $encoders = [new JsonEncoder()];
    $normalizers = [new ObjectNormalizer()];
    $this->serializer = new Serializer($normalizers, $encoders);
  }

  /**
   * @param Request $request
   * @Route("/test")
   * @return JsonResponse
   */
  public function Test(Request $request)
  {
    $normalizerContext = [
      AbstractNormalizer::GROUPS => ['normalizetest:read'],
      AbstractNormalizer::IGNORED_ATTRIBUTES => [],
    ];

    $obj = new NormalizeTest(1, 4);
    $data = $this->serializer->serialize($obj, 'json', $normalizerContext);


    return new JsonResponse($data, 200, [], true);
  }




}

class NormalizeTest {

  public function __construct(int $x, int $y)
  {
    $this->x = $x;
    $this->y = $y;
  }

  /**
   * @var int
   * @Groups({"normalizetest:read"})
   */
  private int $x = 4;

  private int $y = 2;

  public function getX(): int {
    return $this->x;
  }

  public function getY(): int {
    return $this->y;
  }

  /**
   * @return int
   * @Groups({"normalizetest:read"})
   * @SerializedName("calcVal")
   */
  public function calcVal(): int {
    return $this->x + $this->y;
  }
}