<?php

namespace shop\validator;

use yii\validators\RegularExpressionValidator;

class SlugValidator extends RegularExpressionValidator
{
    public $pattern = '#^[a-z0-9_-]*$#s';

    public $message = 'Использовать только символы латиницы и цифры, а также "-" и "_".';
}
