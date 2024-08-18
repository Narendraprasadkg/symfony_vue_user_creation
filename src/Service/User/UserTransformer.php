<?php

namespace App\Service\User;

use App\Entity\User;

class UserTransformer
{
    public function transform(User $user): array
    {
        return [
            'id' => $user->getId(),
            'customerNumber' => $user->getCustomerNumber(),
            'anrede' => $user->getAnrede(),
            'firstName' => $user->getFirstName(),
            'secondName' => $user->getSecondName(),
            'street' => $user->getStreet(),
            'houseNumber' => $user->getHouseNumber(),
            'pin' => $user->getPin(),
            'location' => $user->getLocation(),
            'email' => $user->getEmail(),
        ];
    }
}

?>