<?php
require_once 'functions/util.inc.php';
require_once 'functions/db_connect.inc.php';
db_connect('bakineggs');
require_once 'blog/entry.php';
require_once 'blog/comment.php';
require_once 'vendor/recaptchalib.php';

function render_page($params) {
  if (sizeof($params) > 0 && $_POST['commenting'] == 'true')
    create_comment(Entry::find_by_name(urldecode($params[0])), $_POST);
  else if (sizeof($params) > 0)
    render_entry(Entry::find_by_name(urldecode($params[0])));
  else {
    echo '<ul id="entries">';
    foreach (Entry::find_all() as $entry)
      render_summary($entry, true);
    echo '</ul>';
  }
}

function create_comment($entry, $attributes) {
  $recaptcha_response = recaptcha_check_answer($RECAPTCHA_PRIVATE_KEY,
                                               $_SERVER["REMOTE_ADDR"],
                                               $attributes["recaptcha_challenge_field"],
                                               $attributes["recaptcha_response_field"]);

  if ($recaptcha_response->is_valid && Comment::create($entry, $attributes['body'], $attributes['author'], $_SERVER['REMOTE_ADDR']))
    render_entry($entry);
  else
    render_entry($entry, $attributes, $recaptcha_response->error);
}

function render_entry($entry, $comment_attributes = array(), $recaptcha_error = null) {
  if (!$entry)
    return render_not_found();
  echo '<div class="entry" id="entry_' . h($entry->id) . '">' . "\n";
  echo '<span class="posted_at">' . h(date('F j, Y @ g:ia', $entry->posted_at)) . '</span>' . "\n";
  echo '<h3>' . h($entry->name) . '</h3>' . "\n";
  echo '<div class="body">' . $entry->body . '</div>' . "\n";
  render_comments($entry->comments(), $comment_attributes, $recaptcha_error);
  echo '</div>' . "\n";
}

function render_summary($entry) {
  if (!$entry)
    return;
  echo '<li class="entry summary" id="entry_' . h($entry->id) . '">' . "\n";
  echo '<span class="posted_at">' . h(date('F j, Y @ g:ia', $entry->posted_at)) . '</span>' . "\n";
  echo '<h3><a href="' . $entry->URI() . '">' . h($entry->name) . '</a></h3>' . "\n";
  echo '<div class="body">' . $entry->summary . '</div>' . "\n";
  echo '</li>' . "\n";
}

function render_not_found() {
  echo '<div class="entry not_found">';
  echo '<h3>Entry Not Found</h3>';
  echo '<div class="body"><p>Sorry, I never posted a blog entry with that name.</p></div>';
  echo '</div>';
}

function render_comments($comments, $comment_attributes, $recaptcha_error) {
  if (sizeof($comments) > 0) {
    echo '<h4>Comments</h4>' . "\n";
    echo '<ul class="comments">' . "\n";
    foreach ($comments as $comment)
      render_comment($comment);
    echo '</ul>' . "\n";
  }
  echo '<h4>Post Comment</h4>' . "\n";
  echo '<form method="post">' . "\n";
  echo '<input type="hidden" name="commenting" value="true" />' . "\n";
  echo '<label for="comment_author">Name (optional)</label>' . "\n";
  echo '<input id="comment_author" type="text" name="author" value="' . h($comment_attributes['author']) . '">' . "\n";
  echo '<textarea name="body">' . h($comment_attributes['body']) . '</textarea>' . "\n";
  echo recaptcha_get_html($RECAPTCHA_PUBLIC_KEY, $recaptcha_error);
  echo '<input type="submit" value="Post" />' . "\n";
  echo '</form>';
}

function render_comment($comment) {
  echo '<li class="comment" id="comment_' . h($comment->id) . '">'. "\n";
  if ($comment->author)
    echo '<span class="author">' . h($comment->author) . '</span><span class="said_on">&nbsp;said on&nbsp;</span>';
  echo '<span class="posted_at">' . h(date('F j, Y @ g:ia', $comment->posted_at)) . '</span>' . "\n";
  echo '<div class="body">' . h($comment->body) . '</div>';
  echo '</li>' . "\n";
}
?>
