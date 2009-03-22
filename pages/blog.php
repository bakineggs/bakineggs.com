<?php
require_once 'functions/util.inc.php';
require_once 'functions/db_connect.inc.php';
db_connect('bakineggs');
require_once 'blog/entry.php';

function render_page($params) {
  if (sizeof($params) > 0)
    render_entry(Entry::find_by_name(urldecode($params[0])));
  else
    foreach (Entry::find_all() as $entry)
      render_entry($entry, true);
}

function render_entry($entry, $link_name = false) {
  if (!$entry)
    return render_not_found();
  echo '<div class="entry" id="entry_' . h($entry->id) . '">';
  echo '<h3>';
  if ($link_name)
    echo '<a href="/blog/' . urlencode($entry->name) . '">';
  echo h($entry->name);
  if ($link_name)
    echo '</a>';
  echo '</h3>';
  echo '<p>' . $entry->body . '</p>';
  echo '<span class="posted_at">' . h($entry->posted_at) . '</span>';
  echo '</div>';
}

function render_not_found() {
  echo '<div class="entry not_found">';
  echo '<h3>Entry Not Found</h3>';
  echo '<p>Sorry, I never posted a blog entry with that name.</p>';
  echo '</div>';
}
?>
