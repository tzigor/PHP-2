<?php

namespace src\Http;

use src\Blog\Exceptions\HttpException;

abstract class Response
{
    protected const SUCCESS = true;
    public function send(): void
    {
        $data = ['success' => static::SUCCESS] + $this->payload();
        // Отправляем заголовок, говорщий, что в теле ответа будет JSON
        header('Content-Type: application/json');
        // Кодируем данные в JSON и отправляем их в теле ответа
        echo json_encode($data, JSON_THROW_ON_ERROR);
    }
    // Декларация абстрактного метода,
    // возвращающего полезные данные ответа
    abstract protected function payload(): array;
}
