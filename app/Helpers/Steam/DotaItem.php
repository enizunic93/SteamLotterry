<?php
namespace App\Helpers\Steam;

class DotaItem extends Item {
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

    private static function getHeroes() {

    }

    private $rarity;

    public function __construct(array $data, array $additional) {
        parent::__construct($data, $additional);

        foreach ($this->tags as $tag) {
            if (strtolower($tag['category']) == 'rarity') {
                $this->rarity = strtolower($tag['name']);
            } elseif (strtolower($tag['category']) == 'hero') {
                $this->characters[] = $tag['name'];
            }
        }
    }

    public function getRarity()
    {
        return $this->rarity;
    }

    public function getHero() {
        return $this->characters[0];
    }

    private function getRarityOrder() {
        return self::$dotaRaritiesEnum[$this->getRarity()];
    }

    private static function checkBeforeSort($a, $b) {
        $badType = false;

        if (!($a instanceof DotaItem))
            $badType = gettype($a);
        if (!($b instanceof DotaItem))
            $badType = gettype($b);

        if($badType)
            throw new \RuntimeException('Incompatible types. Excepted: ' . self::class . '. Got: ' . $badType);
    }

    public static function sortByQuality($qualityA, $qualityB)
    {
        static::checkBeforeSort($qualityA, $qualityB);

        return 0;
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

    public static function sortByCharacter($a, $b)
    {
        return 0;
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

        // От эншнта к камонке, DESC кароче

        return ($al > $bl) ? -1 : 1;
    }
}