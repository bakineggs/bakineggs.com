<?php
class Entry {
  public $id, $name, $body, $posted_at;

  public static function find_by_name($name) {
    $result = mysql_query("select * from entries where name='" . mesc($name) . "'");
    if ($row = mysql_fetch_assoc($result))
      return self::from_row($row);
    else
      return null;
  }

  public static function find_all() {
    $result = mysql_query('select * from entries order by posted_at desc');
    $entries = array();
    while ($row = mysql_fetch_assoc($result))
      $entries[] = self::from_row($row);
    return $entries;
  }

  private static function from_row($row) {
    return new Entry($row['id'], $row['name'], $row['body'], $row['posted_at']);
  }

  private function __construct($id, $name, $body, $posted_at) {
    $this->id = $id;
    $this->name = $name;
    $this->body = $body;
    $this->posted_at = $posted_at;
  }
}
?>
