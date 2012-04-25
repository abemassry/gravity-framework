<?php
function dbSelectQuerySimple($fields, $table, $searchField, $itemVar) {
    $queryString = "SELECT $fields FROM $table WHERE $searchField= :$searchField";
    $preparedStatement = $db->prepare($queryString);
    $preparedStatement->execute(array(':$searchField' => $itemVar));
    $rows = $preparedStatement->fetchAll();
    return $rows;
}


?>
