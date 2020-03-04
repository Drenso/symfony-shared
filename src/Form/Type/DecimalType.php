<?php


namespace App\Form\Type;


use Decimal\Decimal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class DecimalType extends AbstractType
{

  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->addModelTransformer(new CallbackTransformer(
        function ($value) {
          if (!$value instanceof Decimal) {
            return $value;
          }

          return $value->toFloat();
        },
        function ($value) {
          if (!is_string($value) && !is_float($value)) {
            return $value;
          }

          return new Decimal((string) $value);
        }
    ));
  }

  public function getParent()
  {
    return NumberType::class;
  }
}
