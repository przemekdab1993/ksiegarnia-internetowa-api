<?php

namespace App\Validator;

use App\Entity\UserApi;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidOwnerValidator extends ConstraintValidator
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\IsValidOwner $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        /**
         * @var UserApi $user
         */
        $user = $this->security->getUser();

        if (!$user instanceof UserApi) {
            $this->context->buildViolation($constraint->anonymousMassage)
                ->addViolation();

            return;
        }

        if (!$value instanceof UserApi) {
            throw new \InvalidArgumentException('@IsValidOwner constraint must be put on a property containing a UserApi object');
        }

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        if ($value->getId() !== $user->getId()) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }

    }
}
