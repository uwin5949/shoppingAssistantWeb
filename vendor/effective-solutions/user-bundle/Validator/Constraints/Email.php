<?php
/**
 * Created by PhpStorm.
 * User: Prabhashi
 * Date: 12/21/2017
 * Time: 7:00 AM
 */

namespace EffectiveSolutions\UserBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Email extends Constraint
{

    public $message = 'This email address is already used ... !';

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}