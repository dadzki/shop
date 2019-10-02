<?php


namespace shop\repositories\Shop;

use shop\entities\Shop\Product\Product;
use shop\repositories\NotFoundException;

class ProductRepository
{
    public function get($id): Product
    {
        if (!$product = Product::findOne($id)) {
            throw new NotFoundException('Товар не найден.');
        }
        return $product;
    }

    public function save(Product $product): void
    {
        if (!$product->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(Product $product): void
    {
        if (!$product->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
    }

    public function existsByBrand($id): bool
    {
        return Product::find()->andWhere(['brand_id' => $id])->exists();
    }

    public function existsByMainCategory($id): bool
    {
        return Product::find()->andWhere(['category_id' => $id])->exists();
    }
}
