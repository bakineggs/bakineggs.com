<?php
$PAGES = array(
  'about' => 'About Me',
  'projects' => 'My Projects',
  'contributions' => 'Contributions to Other Projects',
  'resume' => 'Resum&eacute;',
  'error404' => 'Error 404'
);

$uri_parts = explode('/', $_SERVER['REQUEST_URI']);
while (sizeof($uri_parts) > 0 && $uri_parts[0] == '')
  array_shift($uri_parts);

if (sizeof($uri_parts) > 0)
  $page = strtolower($uri_parts[0]);
else
  $page = 'about';

if (!in_array($page, array_keys($PAGES)))
  $page = 'error404';

$content_file = 'content/' . $page . '.html';

echo '<?xml version="1.0" encoding="utf-8" ?>';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>bakineggs</title>
    <link rel="stylesheet" type="text/css" href="/style.css" />
    <link rel="stylesheet" type="text/css" media="print" href="/print.css" />
  </head>
  <body>
    <div id="title">
      <h1>bakineggs</h1>
    </div>
    <ul id="navigation">
      <li><a href="/About">About Me</a></li>
      <li><a href="/Projects">My Projects</a></li>
      <li><a href="/Contributions">Contributions</a></li>
      <li><a href="/Resume">Resum&eacute;</a></li>
    </ul>
    <div id="content" class="<?= $page ?>">
      <h2><?= $PAGES[$page] ?></h2>
      <?php readfile($content_file); ?>
    </div>
    <div id="meta">
      <div id="picture">
        <a href="/me.jpg">
          <img src="/me_thumbnail.jpg" alt="bakineggs: Dan Barry" />
        </a>
      </div>
      <div id="links">
        <h2>Me Elsewhere</h2>
        <ul>
          <li><a href="http://github.com/danbarry">GitHub</a></li>
          <li><a href="http://del.icio.us/bakineggs">del.icio.us</a></li>
          <li><a href="http://www.workingwithrails.com/person/13319-dan-barry">Working With Rails</a></li>
        </ul>
      </div>
    </div>
    <div id="credits">
      <p>
        &copy; 2008-<?= date('Y') ?> Dan Barry. Template design by <a href="http://www.sixshootermedia.com">Six Shooter Media</a>.
      </p>
    </div>
  </body>
</html>
