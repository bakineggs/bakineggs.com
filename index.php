<?= '<?xml version="1.0" encoding="utf-8" ?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>bakineggs</title>
    <link rel="stylesheet" type="text/css" href="/style.css" />
  </head>
  <body>
    <div id="content">
      <div id="title">
        <h1>bakineggs</h1>
      </div>
      <div id="about">
        <h2>About Me</h2>
        <p>
          My name is Dan Barry.
          I'm a junior in Computer Science at <a href="http://illinois.edu">The University of Illinois at Urbana-Champaign</a>.
          I've been programming for over ten years now.
          Most recently, I've been programming in <a href="http://ruby-lang.org">Ruby</a> and using the <a href="http://rubyonrails.org">Rails</a> framework.
          I'm also proficient in <a href="http://php.net">PHP</a>.
        </p>
      </div>
      <div id="projects">
        <h2>Projects</h2>
        <ul>
          <li>
            <h3><a href="http://github.com/danbarry/recurring_events_for/">recurring_events_for</a></h3>
            <p>
              recurring_events_for is a function written in PL/pgSQL (a procedural language for PostgreSQL).
              It is used to find the dates on which recurring events occur (for instance, an event that occurs on the second Tuesday of every month).
              It can also return all events occurring within a given timespan based on their repition rules.
            </p>
          </li>
          <li>
            <h3><a href="http://github.com/danbarry/poker/">poker</a></h3>
            <p>
              This is a ruby library for constructing and comparing poker hands.
              It also provides a deck of cards.
            </p>
          </li>
          <li>
            <h3><a href="http://github.com/danbarry/hold_em_bonus/">hold_em_bonus</a></h3>
            <p>
              This is a ruby script I wrote to analyze different betting strategies that can be used in Hold'em Bonus (a casino table game).
              It uses the poker library I wrote.
            </p>
          </li>
          <li>
            <h3><a href="http://github.com/danbarry/cars/">cars</a></h3>
            <p>
              Using PHP and JavaScript, I wrote a spreadsheet-like webapp that I used to collect data about different cars I was considering purchasing.
              The actual spreadsheet with the research I did is available <a href="/cars">here</a> (I ended up buying the Lincoln Continental).
            </p>
          </li>
        </ul>
      </div>
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
    <script type="text/javascript" src="/jquery-1.2.6.min.js"></script>
    <script type="text/javascript" src="/shadow.js"></script>
  </body>
</html>
