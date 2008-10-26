<?php
require 'lib/lib-utf8.php';
require 'lib/lib-entity.php';
require 'lib/lib-feedparser.php';
require 'lib/activity_presenter.php';

define('ACTIVITY_FILE', '/tmp/bakineggs_activity.html');
define('ACTIVITY_ATOM', 'http://github.com/danbarry.atom');

if (!file_exists(ACTIVITY_FILE) || filemtime(ACTIVITY_FILE) + 600 < time()) {
  touch(ACTIVITY_FILE); // prevent other instances of this script from modifying

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
}

echo '<?xml version="1.0" encoding="utf-8" ?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>bakineggs</title>
    <link rel="stylesheet" type="text/css" href="/style.css" />
  </head>
  <body>
    <div id="title">
      <h1>bakineggs</h1>
    </div>
    <div id="activity">
      <? readfile('/tmp/bakineggs_activity.html') ?>
    </div>
    <div id="picture">
      <a href="/me.jpg">
        <img src="/me_thumbnail.jpg" alt="bakineggs: Dan Barry" />
      </a>
    </div>
    <div id="projects">
      <h2>Projects</h2>
      <ul>
        <li><a href="http://github.com/danbarry/recurring_events_for/">recurring_events_for</a></li>
        <li><a href="http://github.com/danbarry/mapit/">Map It!</a></li>
        <li><a href="http://github.com/danbarry/whatpeoplesay/">What People Say</a></li>
        <li><a href="http://github.com/danbarry/cars/">Cars</a></li>
      </ul>
    </div>
    <div id="links">
      <h2>Me Elsewhere</h2>
      <ul>
        <li><a href="http://github.com/danbarry">GitHub</a></li>
        <li><a href="http://del.icio.us/bakineggs">del.icio.us</a></li>
        <li><a href="http://www.workingwithrails.com/person/13319-dan-barry">Working With Rails</a></li>
      </ul>
    </div>
    <script type="text/javascript" src="/jquery-1.2.6.min.js"></script>
    <script type="text/javascript" src="/shadow.js"></script>
  </body>
</html>
