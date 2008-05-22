<?php
require_once('functions/util.inc.php');
require_once('functions/db_connect.inc.php');
$dbh = db_connect('bakineggs');
function comments_for($post) {
  if ($_POST['body'] != '')
    mysql_query("insert into comments (
        `post`,
        `name`,
        `email`,
        `url`,
        `body`
      ) values (
        '".mesc($post)."',
        '".mesc($_POST['name'])."',
        '".mesc($_POST['email'])."',
        '".mesc($_POST['url'])."',
        '".mesc($_POST['body'])."'
      );");
$output = '    <h2>Post a Comment</h2>
    <form method="post" action="/recurring_events/">
      <p>
        <label for="name">Name</label>
        <em>Optional</em>
        <br />
        <input id="name" type="text" name="name" value="'.h($_POST['name']).'" />
      </p>
      <p>
        <label for="email">Email</label>
        <em>Optional</em>
        <br />
        <input id="email" type="text" name="email" value="'.h($_POST['email']).'" />
      </p>
      <p>
        <label for="url">URL</label>
        <em>Optional</em>
        <br />
        <input id="url" type="text" name="url" value="'.h($_POST['url']).'" />
      </p>
      <p>
        <label for="body">Body</label>
        <br />
        <textarea id="body" name="body" rows="10" cols="70">'.h($_POST['body']).'</textarea>
      </p>
      <p>
        <input type="submit" value="Post" />
      </p>
    </form>
    <h2>Comments</h2>'."\n";
  $comments = mysql_query('select * from comments where `post` = \''.mesc($post).'\' order by timestamp');
  if (mysql_num_rows($comments) == 0)
    $output .= '    <p>      '."\n".'No comments have been posted yet'."\n".'    </p>'."\n";
  while ($comment = mysql_fetch_assoc($comments)) {
    $output .= '    <h3>'."\n".'      At '.date('g:ia', strtotime($comment['timestamp'])).' UTC on '.date('l, F j, Y', strtotime($comment['timestamp'])).','."\n";
    if ($comment['name']) {
      if ($comment['email']) {
        $output .= '      <a href="mailto:'.h($comment['email']).'">'.h($comment['name']).'</a>'."\n";
        if ($comment['url']) {
          $output .= '      (<a href="'.h($comment['url']).'">'.h($comment['url']).'</a>)'."\n";
        }
      } else if ($comment['url']) {
        $output .= '      <a href="'.h($comment['url']).'">'.h($comment['name']).'</a>'."\n";
      } else {
        $output .= '      '.h($comment['name'])."\n";
      }
    } else if ($comment['email'] || $comment['url']) {
      if ($comment['email']) {
        $output .= '      <a href="mailto:'.h($comment['email']).'">'.h($comment['email']).'</a>'."\n";
      } else {
        $output .= '      <em>Anonymous</em>'."\n";
      }
      if ($comment['url']) {
        $output .= '      (<a href="'.h($comment['url']).'">'.h($comment['url']).'</a>)'."\n";
      }
    } else {
      $output .= '      <em>Anonymous</em>'."\n";
    }
    $output .= '      said:'."\n".'    </h3>'."\n".'    <p>'."\n".'      '.nl2br(h($comment['body']))."\n".'    </p>'."\n";
  }
  return $output;
}
?>