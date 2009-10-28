<?php
class Comment {
  public $id, $entry_id, $body, $author, $author_ip, $posted_at;

  public static function create($entry, $body, $author, $author_ip) {
    if (!$entry->id || !trim($body))
      return null;
    $result = mysql_query("insert into comments (entry_id, body, author, author_ip) values ('" . mesc($entry->id) ."', '" . mesc($body) ."', '" . mesc($author) ."', '" . mesc($author_ip) ."')");
    if (!($id = mysql_insert_id()))
      return null;
    return self::from_row(mysql_query("select * from comments where id = '" . mesc($id) . "'"));
  }

  public static function find_all_by_entry_id($entry_id) {
    $result = mysql_query("select * from comments where entry_id = '" . mesc($entry_id) . "' order by posted_at desc");
    $entries = array();
    while ($row = mysql_fetch_assoc($result))
      array_push($entries, self::from_row($row));
    return $entries;
  }

  private static function from_row($row) {
    $posted_at = strtotime($row['posted_at']);
    return new Comment($row['id'], $row['entry_id'], $row['body'], $row['author'], $row['author_ip'], $posted_at);
  }

  private function __construct($id, $entry_id, $body, $author, $author_ip, $posted_at) {
    $this->id = $id;
    $this->entry_id = $entry_id;
    $this->body = $body;
    $this->author = $author;
    $this->author_ip = $author_ip;
    $this->posted_at = $posted_at;
  }
}
?>
