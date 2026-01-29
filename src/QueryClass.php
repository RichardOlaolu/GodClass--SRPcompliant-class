<?php
namespace Richardolaolu\GodClass;

use PDO;

class QueryClass extends GodClass
{
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}