<?php
require __DIR__ . '/../lib/lib-utf8.php';
require __DIR__ . '/../lib/lib-entity.php';
require __DIR__ . '/../lib/lib-feedparser.php';
require __DIR__ . '/../lib/activity_presenter.php';

define('ACTIVITY_FILE', '/tmp/bakineggs_activity.html');
define('ACTIVITY_ATOM', 'http://github.com/danbarry.atom');

$parser = new FeedParserURL();
$result = $parser->Parse(ACTIVITY_ATOM);
if ($result['feed'] && $entries = $result['feed']['entries']) {
  $tmpfile = tempnam('/tmp', 'activity');
  $activity = fopen($tmpfile, 'w');
  $presenter = new ActivityPresenter();
  fputs($activity, $presenter->entries($entries));
  fclose($activity);
  rename($tmpfile, ACTIVITY_FILE);
}
?>