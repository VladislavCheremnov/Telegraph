<?php
namespace App\Entities;

class FileStorage extends Storage 
{
    public function create(TelegraphText $object): string
    {
        $i = 1;
        $slug = $object->slug . "_" . date("d.m.y");
        if (file_exists($slug)) {
            while (file_exists($slug . '_' . $i)) {
                $i++;
            }
            $slug .= '_' . $i;
        }
        $object->slug = $slug;
        file_put_contents($slug, serialize($object));
        return $slug;
    }

    public function read(string $slug): TelegraphText 
    {
        if (file_exists($slug)) {
            return unserialize(file_get_contents($slug));
        } else {
            throw new \Exception('Файл не найден');
        }
    }

    public function update(string $slug, TelegraphText $object): void
    {
        if (file_exists($slug)) {
            file_put_contents($slug, serialize($object));
        } else {
            throw new \Exception('Файл не найден');
        }
    }

    public function delete(string $slug): void
    {
        if (file_exists($slug)) {
            unlink($slug);
        } else {
            throw new \Exception('Файл не найден');
        }
    }

    public function list(): array
    {
        $list = array_diff(scandir('.'), ['..', '.']);
        foreach ($list as $elem){
            $elem = file_get_contents($elem);
            if (unserialize($elem)) {
                $resultList[] = unserialize($elem);
            }
        }
        if (!empty($resultList)) {
            return $resultList;
        } else {
            throw new \Exception('Файлы не найдены');
        }
    }
}
