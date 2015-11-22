<?php
namespace App\Helpers\Steam;

class DotaItem extends Item
{
    // Всегда одно для игры
    public static $contextId = 2;

    protected $appId = 570;

    protected static $dotaRaritiesEnum = [
        'common',
        'uncommon',
        'rare',
        'mythical',
        'legendary',
        'immortal',
        'arcana',
        'ancient'
    ];

    private static $dotaTypesEnum = [
        'Wearable',
        'HUD Skin',
        'Loading Screen',
        'Announcer',
        'Courier',
        'Tool',
        'Taunt',
        'Gem / Rune',
        'Bundle'
    ];

    private static $dotaQualitiesEnum = [
        'Infused',
        'Normal',
        'Auspicious',
        'Inscribed',
        'Heroic',
        'Genuine',
        'Cursed',
        'Frozen',
        'Unusual',
        'Elder',
        'Corrupted',
        'Autographed',
        'Exalted',
        'Legacy',
        'Ascendant',
        'Favored',
    ];

    private static $_heroes = [];

    public static function getHeroes()
    {
        if (empty(self::$_heroes)) {
            $api = new APIBridge(config('steam-auth.api_key'));

            $json = $api->callApi('IEconDOTA2_570/', 'GetHeroes/v0001/', [
                'language' => 'russian'
            ]);

            $result = json_decode($json, true);

            $heroes = $result['result']['heroes'];

            self::$_heroes = $heroes;
        }

        return self::$_heroes;
    }

    private $rarity;
    private $quality;

    public function __construct(array $data)
    {
        parent::__construct($data);

        foreach ($this->tags as $tag) {
            if (strtolower($tag['category']) == 'rarity') {
                $this->rarity = strtolower($tag['name']);
            } elseif (strtolower($tag['category']) == 'hero') {
                $this->characters[] = $tag['name'];
            } elseif (strtolower($tag['category']) == 'quality') {
                $this->quality = $tag['name'];
            }
        }
    }

    public function getRarity()
    {
        return $this->rarity;
    }

    public function getHero()
    {
        return $this->characters[0];
    }

    private function getRarityOrder()
    {
        return self::$dotaRaritiesEnum[$this->getRarity()];
    }

    private static function checkBeforeSort($a, $b)
    {
        $badType = false;

        if (!($a instanceof DotaItem))
            $badType = gettype($a);
        if (!($b instanceof DotaItem))
            $badType = gettype($b);

        if ($badType)
            throw new \RuntimeException('Incompatible types. Excepted: ' . self::class . '. Got: ' . $badType);
    }

    /**
     * @param $a DotaItem
     * @param $b DotaItem
     * @return int
     */
    public static function sortByQuality($a, $b)
    {
        static::checkBeforeSort($a, $b);

        $al = (!isset(self::$dotaQualitiesEnum[$a->getQuality()])) ? 0 : self::$dotaQualitiesEnum[$a->getHero()];
        $bl = (!isset(self::$dotaQualitiesEnum[$b->getQuality()])) ? 0 : self::$dotaQualitiesEnum[$b->getHero()];

        if ($al == $bl)
            return 0;

        return ($al > $bl) ? -1 : 1;
    }

    /**
     * @param $a DotaItem
     * @param $b DotaItem
     * @return int
     */
    public static function sortByType($a, $b)
    {
        static::checkBeforeSort($a, $b);

        $al = self::$dotaTypesEnum[$a->getType()];

        $bl = self::$dotaTypesEnum[$b->getType()];

        if ($al == $bl) {
            return 0;
        }

        return ($al > $bl) ? -1 : 1;
    }

    /**
     * @param $a DotaItem
     * @param $b DotaItem
     * @return int
     */
    public static function sortByCharacter($a, $b)
    {
        static::checkBeforeSort($a, $b);

        $al = $bl = 0;

        foreach (self::getHeroes() as $hero) {
            if ($hero['localized_name'] == $a->getHero())
                $al = true;
            elseif ($hero['localized_name'] == $b->getHero())
                $bl = true;
        }

        if (!$al) {
            return 1;
        }

        if (!$bl) {
            return -1;
        }

        return strnatcmp($a->getHero(), $b->getHero());
    }

    /**
     * @param $a DotaItem
     * @param $b DotaItem
     * @return int
     */
    public static function sortByPrice($a, $b)
    {
        static::checkBeforeSort($a, $b);

        $al = $a->getLotPrice();
        $bl = $b->getLotPrice();

        if ($al == $bl) {
            return 0;
        }

        return ($al > $bl) ? -1 : 1;
    }

    /**
     * @param $a DotaItem
     * @param $b DotaItem
     * @return int
     */
    public static function sortByRarity($a, $b)
    {
        static::checkBeforeSort($a, $b);

        $al = $a->getRarityOrder();
        $bl = $b->getRarityOrder();

        if ($al == $bl) {
            return 0;
        }

        return ($al > $bl) ? -1 : 1;
    }

    /**
     * @return mixed
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @param mixed $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }
}