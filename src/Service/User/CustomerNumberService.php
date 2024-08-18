<?php
namespace App\Service\User;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;

class CustomerNumberService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMaxCustomerNumber(): int
    {
        $maxCustomerNumber = $this->entityManager
            ->getRepository(User::class)
            ->createQueryBuilder('u')
            ->select('MAX(u.customerNumber)')
            ->getQuery()
            ->getSingleScalarResult();

        return (int) ($maxCustomerNumber ? $maxCustomerNumber : 10000) + 1;
    }
}

?>