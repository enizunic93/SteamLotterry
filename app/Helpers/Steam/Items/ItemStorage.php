<?
namespace App\Helpers\Steam\Items;

class ItemStorage
{
    private static $relations = [
        '570' => DotaItem::class,
    ];

    public static function getItemClass($appId)
    {
        $appId = $appId . '';

        if (!isset(self::$relations[$appId])) {
            throw new \InvalidArgumentException('Не существует предмета для игры с таким ID: ' . $appId);
        }

        return self::$relations[$appId];
    }

    public static function getItemContextID($appId)
    {
        $className = self::getItemClass($appId);
        $prop = 'contextId';

        if (!property_exists($className, $prop))
            throw new \RuntimeException('У предмета ' . $className . ' не указан ContextID! Исправьте это скорее!');

        $vars = get_class_vars($className);
        return $vars[$prop];
    }

    /**
     * @param $itemInfo
     * @return mixed
     */
    public static function createItem($itemInfo)
    {
        $item = self::getItemClass($itemInfo['appid']);
        /* @var $item Item */
        return new $item($itemInfo);
    }
}