<?php /** @noinspection PhpUnused */
declare(strict_types=1);

/**
 * Autogenerated!
 *
 * @table regions
 */
class Region{

    /**
     * @column id
     * @column_type int
     * @var int
     */
    private $id;

    /**
     * @column owner
     * @column_type varchar
     * @var string
     */
    private $owner;

    /**
     * @column x1
     * @column_type int
     * @var int
     */
    private $x1;

    /**
     * @column z1
     * @column_type int
     * @var int
     */
    private $z1;

    /**
     * @column x2
     * @column_type int
     * @var int
     */
    private $x2;

    /**
     * @column z2
     * @column_type int
     * @var int
     */
    private $z2;

    /**
     * @result int
     */
    public function getId() : int{
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return void
     */
    public function setId(int $id) : void{
        $this->id = $id;
    }

    /**
     * @result string
     */
    public function getOwner() : string{
        return $this->owner;
    }

    /**
     * @param string $owner
     *
     * @return void
     */
    public function setOwner(string $owner) : void{
        $this->owner = $owner;
    }

    /**
     * @result int
     */
    public function getX1() : int{
        return $this->x1;
    }

    /**
     * @param int $x1
     *
     * @return void
     */
    public function setX1(int $x1) : void{
        $this->x1 = $x1;
    }

    /**
     * @result int
     */
    public function getZ1() : int{
        return $this->z1;
    }

    /**
     * @param int $z1
     *
     * @return void
     */
    public function setZ1(int $z1) : void{
        $this->z1 = $z1;
    }

    /**
     * @result int
     */
    public function getX2() : int{
        return $this->x2;
    }

    /**
     * @param int $x2
     *
     * @return void
     */
    public function setX2(int $x2) : void{
        $this->x2 = $x2;
    }

    /**
     * @result int
     */
    public function getZ2() : int{
        return $this->z2;
    }

    /**
     * @param int $z2
     *
     * @return void
     */
    public function setZ2(int $z2) : void{
        $this->z2 = $z2;
    }
}