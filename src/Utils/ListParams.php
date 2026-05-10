<?php

declare(strict_types=1);

namespace Genuka\Pay\Utils;

final class ListParams
{
    /**
     * @param  array{per_page?: int, sort?: string, filter?: array<string, string|int|bool|null>}  $params
     * @return array<string, string|int|bool|null>
     */
    public static function format(array $params = []): array
    {
        $query = [];

        if (array_key_exists('per_page', $params)) {
            $query['per_page'] = $params['per_page'];
        }

        if (array_key_exists('sort', $params)) {
            $query['sort'] = $params['sort'];
        }

        foreach (($params['filter'] ?? []) as $key => $value) {
            $query["filter[{$key}]"] = $value;
        }

        return $query;
    }
}
