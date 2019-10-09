<?php

namespace shop\entities\Shop\queries;

use shop\entities\Shop\Product\Product;
use yii\db\ActiveQuery;

/**
 * @param null $alias
 * @return $this
 */
class ProductQuery extends ActiveQuery
{
    public function active($alias = null)
    {
        return $this->andWhere([
            ($alias ? $alias . '.' : '') . 'status' => Product::STATUS_ACTIVE,
        ]);
    }
}
