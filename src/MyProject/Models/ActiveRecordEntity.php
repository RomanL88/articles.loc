<?
namespace MyProject\Models;

use MyProject\Services\Db;

abstract class ActiveRecordEntity
{
    /** @var int */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


    public function __set(string $name, $value)
    {
        $camelCaseName = $this->underscoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @return static[]
     */
    public static function findAll(): array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    /**
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id=:id;',
            [':id' => $id],
                static::class
        );
        return $entities ? $entities[0] : null;
    }

    public function save(): void
    {
        $mappedProperies = $this->mapPropertiesToDbFormat();
        if ($this->id !== null) {
            $this->update($mappedProperies);
        } else {
            $this->insert($mappedProperies);
        }

    }
    private function update(array $mappedProperies): void
    {
        //здесь мы обновляем существующую запись в базе

        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperies as $column => $value) {
            $param = ':param' . $index; // param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[$param] = $value; // [:param1 => value1]
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;

        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
    }

    private function insert(array $mappedProperies): void
    {
        //здесь мы создаём новую запись в базе

        /**
         * взять параметры для создания записи таблицы `articles`
         * сформировать запрос
         * выполнить запрос
         */

        $columns2params = [];
        $params2values = [];
        $index = 1;
        foreach ($mappedProperies as $column => $value) {
            $param = ':param' . $index; // param1
            $columns2params[] = $column . ' = ' . $param; // column1 = :param1
            $params2values[$param] = $value; // [:param1 => value1]
            $index++;
        }
        echo "<pre>";
        var_dump($params2values);
        echo "</pre>";
        // шаблон запроса
        /**
         * INSERT INTO `articles` VALUES 
         * `author_id`=1,                   // узнать ID автора 
         * `name`='Новое название статьи',
         * `text`='Новый текст статьи',
         *   
         */




    }




    private function mapPropertiesToDbFormat(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyNameAsUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameAsUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }


    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }



    abstract protected static function getTableName(): string;
}

?>