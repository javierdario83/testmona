<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function generate_correlative(): string
{
    $attempts = 0;
    do {
        $attempts++;
        $now = new DateTimeImmutable('now');
        $datePart = $now->format('jny');
        $random = str_pad((string) random_int(100, 999), 3, '0', STR_PAD_LEFT);
        $code = $datePart . $random;

        $stmt = db()->prepare('SELECT COUNT(*) FROM readings WHERE correlative = :c');
        $stmt->execute(['c' => $code]);
        $exists = (int) $stmt->fetchColumn() > 0;
    } while ($exists && $attempts < 10);

    return $code;
}

function package_meta(string $packageCode): array
{
    $catalog = [
        'P1' => ['label' => '1 pregunta', 'questions' => 1, 'price' => 10.00],
        'P2' => ['label' => '2 preguntas', 'questions' => 2, 'price' => 18.00],
        'P3' => ['label' => '3 preguntas', 'questions' => 3, 'price' => 25.00],
        'P5' => ['label' => '5 preguntas', 'questions' => 5, 'price' => 38.00],
    ];

    return $catalog[$packageCode] ?? $catalog['P1'];
}

function greeting_by_gender(string $gender): string
{
    return $gender === 'F' ? 'Bienvenida' : 'Bienvenido';
}
