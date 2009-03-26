<?php
require_once 'functions/util.inc.php';
require_once 'functions/db_connect.inc.php';
db_connect('bakineggs');
require_once 'blog/entry.php';

function render_document() {
  header('Content-Type: application/rss+xml');
  echo '<?xml version="1.0"?>' . "\n";
  echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
  echo '  <channel>' . "\n";
  echo '    <title>bakineggs</title>' . "\n";
  echo '    <link>http://bakineggs.com/Blog</link>' . "\n";
  echo '    <atom:link href="http://bakineggs.com/blog.rss" rel="self" type="application/rss+xml" />' . "\n";
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
  echo '      <guid>http://bakineggs.com/Blog/' . urlencode($entry->name) . '</guid>' . "\n";
  echo '      <description>' . strip_tags($entry->summary) . '</description>' . "\n";
  echo '      <pubDate>' . date('r', $entry->posted_at) . '</pubDate>' . "\n";
  echo '    </item>' . "\n";
}

render_document();
?>
