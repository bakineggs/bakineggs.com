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
      render_summary($entry, true);
}

function render_entry($entry) {
  if (!$entry)
    return render_not_found();
  echo '<div class="entry" id="entry_' . h($entry->id) . '">' . "\n";
  echo '<h3>' . h($entry->name) . '</h3>' . "\n";
  echo '<span class="posted_at">' . h(date('F j, Y @ g:ia', $entry->posted_at)) . '</span>' . "\n";
  echo '<p>' . $entry->body . '</p>' . "\n";
  echo '</div>' . "\n";
}

function render_summary($entry) {
  if (!$entry)
    return;
  echo '<div class="entry summary" id="entry_' . h($entry->id) . '">' . "\n";
  echo '<h3><a href="/Blog/' . urlencode($entry->name) . '">' . h($entry->name) . '</a></h3>' . "\n";
  echo '<span class="posted_at">' . h(date('F j, Y @ g:ia', $entry->posted_at)) . '</span>' . "\n";
  echo '<p>' . $entry->summary . '</p>' . "\n";
  echo '</div>' . "\n";
}

function render_not_found() {
  echo '<div class="entry not_found">';
  echo '<h3>Entry Not Found</h3>';
  echo '<p>Sorry, I never posted a blog entry with that name.</p>';
  echo '</div>';
}
?>
