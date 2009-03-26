<?php
require_once 'functions/util.inc.php';
require_once 'functions/db_connect.inc.php';
db_connect('bakineggs');
require_once 'blog/entry.php';

function render_document() {
  echo '<?xml version="1.0"?>' . "\n";
  echo '<rss version="2.0">' . "\n";
  echo '  <channel>' . "\n";
  echo '    <title>bakineggs</title>' . "\n";
  echo '    <link>http://bakineggs.com/Blog</link>' . "\n";
  echo '    <description>Dan Barry\'s Blog</description>' . "\n";
  foreach (Entry::find_all() as $entry)
    render_entry($entry);
  echo '  </channel>' . "\n";
  echo '</rss>' . "\n";
}

function render_entry($entry) {
  echo '    <item>' . "\n";
  echo '      <title>' . $entry->name . '</title>' . "\n";
  echo '      <link>http://bakineggs.com/Blog/' . urlencode($entry->name) . '</link>' . "\n";
  echo '      <description>' . strip_tags($entry->summary) . '</description>' . "\n";
  echo '      <pubDate>' . $entry->posted_at . '</pubDate>' . "\n";
  echo '    </item>' . "\n";
}

render_document();
?>
