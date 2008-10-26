<?php
class ActivityPresenter {
  public function title($title, $href) {
    if ($title['type'] == 'text')
      return '<h2>' . $this->link_to($href, $title['value']) . '</h2>';
  }

  public function timestamp($timestamp) {
    return '<span class="timestamp">' . date('D, F j, Y g:ia', $timestamp) . '</span>';
  }

  public function content($content) {
    if ($content['type'] == 'text')
      return '<p class="content">' . $this->h($content['value']) . '</p>';
    if ($content['type'] == 'html' || $content['type'] == 'xhtml')
      return '<div class="content">' . $content['value'] . '</div>';
  }

  public function entry($entry) {
    if ($entry['links'] && $entry['links'][0])
      $href = $entry['links'][0]['href'];
    if ($entry['title'])
      $contents .= $this->title($entry['title'], $href);
    if ($entry['published'])
      $contents .= $this->timestamp($entry['published']);
    if ($entry['content'])
      $contents .= $this->content($entry['content']);

    if ($contents)
      return '<div class="entry">' . $contents . '</div>';
    else
      return '';
  }

  public function entries($entries) {
    foreach ($entries as $entry)
      $contents .= $this->entry($entry);
    return $contents;
  }

  private function h($str) {
    return htmlentities($str, ENT_QUOTES);
  }

  private function link_to($href, $text) {
    if ($href)
      return '<a href="' . $this->h($href) . '">' . $this->h($text) . '</a>';
    return $this->h($text);
  }
}
?>
