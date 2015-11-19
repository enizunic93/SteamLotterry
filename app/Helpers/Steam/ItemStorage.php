<?
namespace App\Helpers\Steam;

class ItemStorage {
    private static $relations = [
        '570' => DotaItem::class,
    ];

    public static function getItemClass($appId) {
        $appId = $appId . '';

        if (!isset(self::$relations[$appId])) {
            throw new \InvalidArgumentException('Не существует предмета для игры с таким ID.');
        }

        return self::$relations[$appId];
    }
}