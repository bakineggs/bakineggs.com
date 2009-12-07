<?php
class Comment {
  public $id, $entry_id, $body, $author, $author_ip, $posted_at;

  public static function create($entry, $body, $author, $author_ip) {
    if (!$entry->id || !trim($body))
      return null;
    $result = pg_query("insert into comments (entry_id, body, author, author_ip) values ('" . pesc($entry->id) ."', '" . pesc($body) ."', '" . pesc($author) ."', '" . pesc($author_ip) ."')");
    if (!($id = pg_last_oid($result)))
      return null;
    return self::from_row(pg_query("select * from comments where id = '" . pesc($id) . "'"));
  }

  public static function find_all_by_entry_id($entry_id) {
    $result = pg_query("select * from comments where entry_id = '" . pesc($entry_id) . "' order by posted_at desc");
    $entries = array();
    while ($row = pg_fetch_assoc($result))
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
