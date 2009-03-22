<?php
$PAGES = array(
  'about' => 'About Me',
  'projects' => 'My Projects',
  'contributions' => 'Contributions to Other Projects',
  'resume' => 'Resum&eacute;',
  'blog' => 'Blog',
  'error404' => 'Error 404'
);

$DYNAMIC_PAGES = array('blog');

$uri = preg_replace('/\/+/', '/', $_SERVER['REQUEST_URI']);
$uri = preg_replace('/^\/|\/$/', '', $_SERVER['REQUEST_URI']);
$uri_parts = explode('/', $uri);

if (sizeof($uri_parts) > 0)
  $page = strtolower(array_shift($uri_parts));
else
  $page = 'about';

if (!in_array($page, array_keys($PAGES)))
  $page = 'error404';

function render($page, $params) {
  global $DYNAMIC_PAGES;
  if (in_array($page, $DYNAMIC_PAGES))
    render_dynamic_page($page, $params);
  else
    render_static_page($page);
}

function render_dynamic_page($page, $params) {
  require 'pages/' . $page . '.php';
  render_page($params);
}

function render_static_page($page) {
  readfile('content/' . $page . '.html');
}

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
    <div id="content" class="<?= $page ?>">
      <h2><?= $PAGES[$page] ?></h2>
      <?php render($page, $uri_parts) ?>
    </div>
    <div id="credits">
      <p>
        &copy; 2008-<?= date('Y') ?> Dan Barry. Template design by <a href="http://www.sixshootermedia.com">Six Shooter Media</a>.
      </p>
    </div>
  </body>
</html>
