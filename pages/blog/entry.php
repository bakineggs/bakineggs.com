<?php
class Entry {
  public $id, $name, $body, $summary, $posted_at;

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

  public function comments() {
    return Comment::find_all_by_entry_id($this->id);
  }

  public function URI() {
    return '/Blog/' . urlencode($this->name);
  }

  private static function from_row($row) {
    $posted_at = strtotime($row['posted_at']);
    return new Entry($row['id'], $row['name'], $row['summary'], $row['body'], $posted_at);
  }

  private function __construct($id, $name, $summary, $body, $posted_at) {
    $this->id = $id;
    $this->name = $name;
    $this->summary = $summary;
    $this->body = $body;
    $this->posted_at = $posted_at;
  }
}
?>
