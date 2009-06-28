<?php
require_once 'functions/util.inc.php';
require_once 'functions/db_connect.inc.php';
db_connect('bakineggs');
require_once 'blog/entry.php';

function render_page($params) {
  if (sizeof($params) > 0)
    render_entry(Entry::find_by_name(urldecode($params[0])));
  else {
    echo '<ul id="entries">';
    foreach (Entry::find_all() as $entry)
      render_summary($entry, true);
    echo '</ul>';
  }
}

function render_entry($entry) {
  if (!$entry)
    return render_not_found();
  echo '<div class="entry" id="entry_' . h($entry->id) . '">' . "\n";
  echo '<span class="posted_at">' . h(date('F j, Y @ g:ia', $entry->posted_at)) . '</span>' . "\n";
  echo '<h3>' . h($entry->name) . '</h3>' . "\n";
  echo '<div class="body">' . $entry->body . '</div>' . "\n";
  echo '</div>' . "\n";
}

function render_summary($entry) {
  if (!$entry)
    return;
  echo '<li class="entry summary" id="entry_' . h($entry->id) . '">' . "\n";
  echo '<span class="posted_at">' . h(date('F j, Y @ g:ia', $entry->posted_at)) . '</span>' . "\n";
  echo '<h3><a href="/Blog/' . urlencode($entry->name) . '">' . h($entry->name) . '</a></h3>' . "\n";
  echo '<div class="body">' . $entry->summary . '</div>' . "\n";
  echo '</li>' . "\n";
}

function render_not_found() {
  echo '<div class="entry not_found">';
  echo '<h3>Entry Not Found</h3>';
  echo '<div class="body"><p>Sorry, I never posted a blog entry with that name.</p></div>';
  echo '</div>';
}
?>
