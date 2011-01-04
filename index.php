<?php
$TAGLINES = array(
  'one spec at a time',
  'least surprising surprise',
  'rubies rock your rails'
);
shuffle($TAGLINES);
$tagline = $TAGLINES[0];

$PAGES = array(
  'projects' => 'My Projects',
  'contributions' => 'Contributions to Other Projects',
  'resume' => 'Resum&eacute;',
  'blog' => 'Blog',
  'error404' => 'Error 404'
);

$NAVIGATION_PAGES = array(
  'blog' => 'Blog',
  'projects' => 'My Projects',
  'contributions' => 'Contributions',
  'resume' => 'Resum&eacute;'
);

$DYNAMIC_PAGES = array('blog');

$uri = preg_replace('/\/+/', '/', $_SERVER['REQUEST_URI']);
$uri = preg_replace('/^\/|\/$/', '', $uri);
$uri_parts = explode('/', $uri);

if (sizeof($uri_parts) > 0 && $uri_parts[0] != '')
  $page = strtolower(array_shift($uri_parts));
else {
  $page = 'blog';
  $uri_parts = array();
}

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
    <div id="meta">
      <div id="title">
        <h1>bakineggs</h1>
      </div>
      <span id="tagline">
        <?= $tagline ?>
      </span>
      <div id="about">
        <p>
          My name is Dan Barry.
          I've been a programming enthusiast for over ten years now.
        </p>
        <p>
          Professionally, I've been developing web applications using <a href="http://rubyonrails.org">Ruby on Rails</a>.
          For fun, I use <a href="http://ruby-lang.org">Ruby</a>, C, JavaScript, and <a href="http://code.google.com/p/k-framework">k-framework</a>.
        </p>
        <p>
          I'm currently living in San Francisco, California where I work as a software engineer at <a href="http://www.scribd.com">Scribd</a>.
        </p>
      </div>
    </div>
    <div id="content" class="<?= $page ?>">
      <h2><?= $PAGES[$page] ?></h2>
      <?php render($page, $uri_parts) ?>
    </div>
    <div id="navigation">
      <ul class="internal">
        <?php foreach ($NAVIGATION_PAGES as $uri => $title) { ?>
        <?php $class = $uri == $page ? ' class="current"' : ''; ?>
        <li><a href="/<?= $uri ?>"<?= $class ?>><?= $title ?></a></li>
        <?php } ?>
      </ul>
      <ul class="external">
        <li><a href="http://twitter.com/bakineggs">Twitter</a></li>
        <li><a href="http://github.com/bakineggs">GitHub</a></li>
        <li><a href="http://del.icio.us/bakineggs">del.icio.us</a></li>
        <li><a href="http://www.workingwithrails.com/person/13319-dan-barry">Working With Rails</a></li>
      </ul>
    </div>
    <div id="credits">
      <p>
        &copy; 2008-<?= date('Y') ?> Dan Barry. Designed by <a href="http://extramoose.com">Hunter Hastings</a>.
      </p>
    </div>
  </body>
</html>
