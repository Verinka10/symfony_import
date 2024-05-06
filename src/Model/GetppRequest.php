<?php 
namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class GetppRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $firstName,

        #[Assert\GreaterThan(18)]
        public int $age,
    ) {
    }
}