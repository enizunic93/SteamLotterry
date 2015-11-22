<?php
namespace App\Helpers\Steam;

abstract class Item
{
    public static $contextId;

        /**
     * Имя предмета
     * @var string
     */
    protected $name;

    /**
     * #Цвет предмета (HEX)
     * @var string
     */
    protected $nameColor;

    /**
     * Имя предмета в маркете
     * @var string
     */
    protected $marketName;

    /**
     * Номер приложения, к которому относится предмет
     * @var integer
     */
    protected $appId;

    /**
     * Идентефикатор класса предмета
     * @var integer
     */
    protected $classId;

    /**
     * Идентефикатор экземпляра предмета
     * @var integer
     */
    protected $instanceId;

    /**
     * Хеш для ссылки к иконке предмета
     * @see http://cdn.steamcommunity.com/economy/image/
     * @var string
     */
    protected $iconUrl;

    /**
     * Хеш для ссылки к большой иконке предмета
     * @see http://cdn.steamcommunity.com/economy/image/
     * @var string
     */
    protected $iconUrlLarge;

    /**
     * Тип предмета
     * @var string
     */
    protected $type;

    /**
     * Можно ли меняться вещью
     * @var boolean
     */
    protected $tradable;

    /**
     * Можно ли продавать вещь на торговой площадке Steam
     * @var boolean
     */
    protected $marketable;

    /**
     * Описания предмета
     * @var array
     */
    protected $descriptions = [];

    /**
     * Теги предмета
     * @var array
     */
    protected $tags = [];

    /**
     * Персонажи
     * @var array
     */
    protected $characters = [];

    /**
     * Минимальная цена
     * @var float
     */
    protected $medianPrice;

    /**
     * Средняя цена
     * @var float
     */
    protected $middlePrice;

    /**
     * Суммарное количество продаж и покупок предмета на маркете steam
     * @var int
     */
    protected $marketVolume;

    public function __construct(array $data)
    {
        $this->appId = $data['appid'];
        $this->classId = $data['classid'];
        $this->instanceId = $data['instanceid'];

        $this->iconUrl = $data['icon_url'];
        $this->iconUrlLarge = $data['icon_url_large'];

        $this->name = $data['name'];
        $this->marketName = $data['market_hash_name'];

        $this->nameColor = $data['name_color'];

        $this->tradable = $data['tradable'];
        $this->marketable = $data['marketable'];

        if (is_array($data['descriptions'])) {
            foreach ($data['descriptions'] as $description) {
                if (preg_replace('/\s+/', '', $description['value']) == '')
                    continue;

                $this->descriptions[] = $description['value'];
            }
        }

        if (is_array($data['tags']))
            $this->tags = $data['tags'];

        foreach ($this->tags as $tag) {
            if (strtolower($tag['category_name']) == 'type') {
                $this->type = $tag['name'];
            }
        }

        if ($data['type'] != '')
            $this->type = $data['type'];
    }

    /**
     * @return float
     */
    public function getMinPrice() {
        return $this->medianPrice;
    }

    /**
     * @param $price float|int
     */
    public function setMinPrice($price) {
        $this->medianPrice = $price;
    }

    /**
     * @return float
     */
    public function getMedianPrice() {
        return $this->medianPrice;
    }

    /**
     * @param $price float
     */
    public function setMedianPrice($price) {
        $this->medianPrice = $price;
    }

    /**
     * @param $val int
     */
    public function setMarketVolume($val) {
        $this->marketVolume = $val;
    }

    /**
     * @return int
     */
    public function getMarketVolume() {
        return $this->marketVolume;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getNameColor()
    {
        return $this->nameColor;
    }

    /**
     * @param string $nameColor
     */
    public function setNameColor($nameColor)
    {
        $this->nameColor = $nameColor;
    }

    /**
     * @return string
     */
    public function getMarketName()
    {
        return $this->marketName;
    }

    /**
     * @return int
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * @return int
     */
    public function getClassId()
    {
        return $this->classId;
    }

    /**
     * @return int
     */
    public function getInstanceId()
    {
        return $this->instanceId;
    }

    /**
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * @return string
     */
    public function getIconUrlLarge()
    {
        return $this->iconUrlLarge;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return boolean
     */
    public function isTradable()
    {
        return $this->tradable;
    }

    /**
     * @return boolean
     */
    public function isMarketable()
    {
        return $this->marketable;
    }

    /**
     * @return array
     */
    public function getDescriptions()
    {
        return $this->descriptions;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Ссылка на чистую PNG-пикчу предмета
     * @return string
     */
    public function getClearUrl()
    {
        return 'https://steamcommunity-a.akamaihd.net/economy/image/class/' . $this->appId . '/' . $this->classId . '/';
    }

    public function getLotPrice() {
        return max($this->middlePrice, $this->medianPrice);
    }

    abstract public function getRarity();

    /*
     * Юзаем заглушки, потому что разрабы пыхи вырезали возможность использования абстрактных статических функций.
     * Зачем нужно? Ну, чтобы базовый класс Item расширял интерфейс
     */

    static public function sortByRarity($rarityA, $rarityB) {
        return 0;
    }

    static public function sortByQuality($a, $b) {
        return 0;
    }

    static public function sortByType($typeA, $typeB) {
        return 0;
    }

    static public function sortByCharacter($characterA, $characterB) {
        return 0;
    }

    static public function sortByPrice($priceA, $priceB) {
        return 0;
    }
}