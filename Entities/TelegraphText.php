<?php
namespace App\Entities;

class TelegraphText 
{
    public string $title;
    public string $text;
    private string $author;
    private string $published;
    public string $slug;

    public function __construct(string $author, string $slug)
    {
        $this->author = $author;
        $this->slug = $slug;
        $this->published = date("d.m.Y H:i");
    }


    public function setAuthor($author): void
    {
        if (mb_strlen($author) <= 120) {
            $this->author = $author;
        } else {
            throw new \Exception('Слишком длинное имя автора');
        }
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setSlug($slug): void
    {
        if (preg_match('/^[a-z0-9_]+$/', $slug)) {
            $this->slug = $slug;
        } else {
            throw new \Exception('Допустимо использовать только буквы латинского алфавита, цифры и символы — "_"');
        }
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setPublished($date): void
    {
        if (strtotime($date) >= strtotime($this->published)) {
            $this->published = $date;
        } else {
            throw new \Exception('Не верная дата.');
        }
    }

    public function getPublished(): ?string
    {
        return $this->published;
    }

    public function __set($name, $value): void
    {
        switch ($name) {
            case 'author':
                $this->setAuthor($value);
                break;
            case 'slug':
                $this->setSlug($value);
                break;
            case 'published':
                $this->setPublished($value);
                break;
            case 'text':
                $this->storeText();
                break;
            default:
                throw new \Exception('Не корректное свойство');
                break;
        }
    }

    public function __get($value)
    {
        switch ($value) {
            case 'author':
                return $this->getAuthor();
                break;
            case 'slug':
                return $this->getSlug();
                break;
            case 'published':
                return $this->getPublished();
                break;
            case 'text':
                return $this->loadText();
                break;
            default:
                throw new \Exception('Не корректное свойство');
                break;
        }
    }

    private function storeText(): void
    {
        $list = [
            'title' => $this->title,
            'text' => $this->text,
            'author' => $this->author,
            'published' => $this->published,
        ];

        $i = 1;
        $this->slug .= '_' . $this->published;
        if (file_exists($this->slug)) {
            while (file_exists($this->slug . '_' . $i)) {
                $i++;
            }
            $this->slug .= '_' . $i;
        }

        file_put_contents($this->slug, serialize($list));
    }

    private function loadText()
    {   
        if (file_get_contents($this->slug)) { 
            $list = unserialize(file_get_contents($this->slug));
            $this->title = $list['title'];
            $this->text = $list['text'];
            $this->author = $list['author'];
            $this->published = $list['published'];
            return $this->text;
        } else {
            echo "Файл не найден";
        }
    }

    public function editText(string $title, string $text): void
    {
        $this->title = $title;
        if(mb_strlen($text) <= 500 && mb_strlen($text) >= 1) {
            $this->text = $text;
        } else {
            throw new \Exception('Длинна текста должна быть от 1 до 500 символов!');
        }
    }
}