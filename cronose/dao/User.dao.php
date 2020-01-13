<?php

require_once 'DAO.php';

class UserDAO extends DAO {

  public static function getUserByDni($dni) {
    $sql = "SELECT * FROM User WHERE dni = '" . $dni . "';";
    $statement = self::$DB->prepare($sql);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public static function getUserByUsername($username) {
    $sql = "SELECT * FROM User WHERE name = '" . $username . "';";
    $statement = self::$DB->prepare($sql);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public static function getUserByTag($tag) {
    $sql = "SELECT * FROM User WHERE name = '" . $tag . "';";
    $statement = self::$DB->prepare($sql);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
  }

  public static function saveUser($user) {
    $user['surname_2'] = $user['surname_2'] ?? null;
    $user['avatar_id'] = $user['avatar_id'] ?? null;
    $fields = "dni, name, surname, surname_2, email, password, tag, coins, registration_date, points, private, city_id, province_id, avatar_id, dni_photo_id";
    $values = "'${user['dni']}', '${user['name']}', '${user['surname']}', '${user['surname_2']}', '${user['email']}', '${user['password']}', ";
    $values = $values.mt_rand(100000, 999999).", 0, '".date("Y-m-d H:i:s")."', 0, ${user['private']}, ${user['city_id']}, ${user['province_id']}, '${user['avatar_id']}', ${user['dni_photo_id']}";
    $sql = "INSERT INTO User (". $fields .") VALUES (". $values .")";
    $statement = self::$DB->prepare($sql);
    try {
      $statement->execute();
      $errors = $statement->errorInfo();
      if ($errors[1]) return Logger::log("ERROR", $errors);
      Logger::log("INFO", "New User saved with dni = ${user['dni']}");
      return self::getUserByDni($user['dni']);
    } catch (PDOException $e) {
      var_dump($statement->columnCount());
      Logger::log("ERROR", $e->getMessage());
      return null;
    }
  }

  public static function getAllDirections() {
    $sql = "select distinct City.name,City.longitude,City.latitude  from City,User";
    $statement = self::$DB->prepare($sql);
    $statement->execute();
    return $statement->fetchAll();
  }

}
