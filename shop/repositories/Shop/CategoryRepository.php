<?php

namespace shop\repositories\Shop;

use shop\entities\Shop\Category;
use shop\repositories\NotFoundException;

class CategoryRepository
{
    public function get($id): Category
    {
        if (!$category = Category::findOne($id)) {
            throw new NotFoundException('Категория не найдена.');
        }
        return $category;
    }

    public function save(Category $category): void
    {
        if (!$category->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }
    }

    public function remove(Category $category): void
    {
        if (!$category->delete()) {
            throw new \RuntimeException('Ошибка удаления.');
        }
    }
}
