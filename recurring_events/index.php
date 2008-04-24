<?php
require_once('functions/util.inc.php');
require_once('functions/db_connect.inc.php');
$dbh = db_connect('bakineggs');
if ($_POST['body'] != '')
  mysql_query("insert into comments (
      `name`,
      `email`,
      `url`,
      `body`
    ) values (
      '".mesc($_POST['name'])."',
      '".mesc($_POST['email'])."',
      '".mesc($_POST['url'])."',
      '".mesc($_POST['body'])."'
    );");
?>
<?= '<?xml version="1.0" encoding="utf-8" ?>' ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>bakineggs - Recurring Events</title>
  </head>
  <body>
    <h1>Recurring Events</h1>
    <p>
      Dan Barry (<a href="http://bakineggs.com">bakineggs</a>) - 4/19/2008
    </p>
    <p>
      I recently had to write some SQL queries to find upcoming events in the calendar part of a project I'm working on.
      After struggling for a few days with a giant where clause that confused me every time I saw it, one of my coworkers pointed me to <a href="http://www.justatheory.com/computers/databases/postgresql/recurring_events.html">a great post on David Wheeler's blog</a>.
      He wrote a functon that does almost exactly what I need.
      <code>select * from recurring_events_for('2008-04-19', '2008-05-19');</code> returns all events occuring in that time span.
      He already did a great job explaining what his function does, so I'll only write about the changes I've made.
    </p>
    <h2>Code</h2>
    <p>
      Don't care what I have to say?
      Just want to see the code?
      I don't blame you.
      I would too.
    </p>
    <p>
      Revision history and any updates are available on <a href="http://github.com/danbarry/recurring_events_for">the github page for this function</a>.
    </p>
    <ul>
      <li><a href="events.sql">events.sql</a></li>
      <li><a href="interval_for.sql">interval_for.sql</a></li>
      <li><a href="intervals_between.sql">intervals_between.sql</a></li>
      <li><a href="generate_recurrences.sql">generate_recurrences.sql</a></li>
      <li><a href="recurring_events_for.sql">recurring_events_for.sql</a></li>
    </ul>
    <p>
      Note: This only works on PostgreSQL with <a href="http://www.postgresql.org/docs/8.3/static/plpgsql.html">PL/pgSQL</a> installed.
    </p>
    <h2>Stuff I Say About the Code</h2>
    <p>
      David's original function was great for basic recurring events, but lacked some recurrence types I needed:
    </p>
    <ul>
      <li>yearly recurring events (very easy to add to his code)</li>
      <li>weekly recurring events that occur on certain days of the week</li>
      <li>monthly recurring events that occur on certain days of the month</li>
      <li>monthly recurring events that occur in certain weeks of the month on certain days of the week (the third tuesday, the second to last monday, etc.)</li>
      <li>yearly recurring events that occur in certain months on certain days of the month (ie the 14th and 19th of february and july)</li>
      <li>yearly recurring events that occur in certain months in certain weeks of the month on certain days of the week (the third tuesday in april and june, the second to last monday in august and november, etc.)</li>
    </ul>
    <p>
      Beyond that, I also needed the following additional features:
    </p>
    <ul>
      <li>have events that are either all day or between certain times</li>
      <li>have an event only occur until a certain date</li>
      <li>have an event only occur a certain number of times</li>
      <li>cancel a certain recurrence of an event</li>
    </ul>
    <h3>All Day vs. Time Span</h3>
    <p>
      I was thinking of having all day events just start at midnight and end at 11:59pm, but I was scared that users viewing the event in different time zones might see it across two days.
      I think the new timezone magic in Rails should take care of this, but I'm not sure.
      If it does, the events.date column can go bye-bye.
    </p>
    <h3>The Tables</h3>
    <pre><?= h(file_get_contents('events.sql')) ?></pre>
    <h4>events</h4>
    <p>
      This table should either have the date column or the starts_at and ends_at columns set, but not both.
      See "All Day vs. Time Span" above.
    </p>
    <p>
      frequency can be any of 'once', 'daily', 'weekly', 'monthly', or 'yearly' and defines the basic recurrence type.
      David ensured that the column had a valid value by using a domain.
      I had initially intended to do this, but upon writing this blog post realized that I'd forgotten. Oops.
      More specific details (day of week, day of month, week of month, month of year, etc.) about the recurrence schedule is stored in the event_recurrences table.
    </p>
    <p>
      count and until limit how long the event can recur.
      count says how many times an event can recur before it stops and until specifies a date after which the event can no longer recur.
      One could theoretically reduce this down to one column and calculate the value if given the other type, but then you have no way of knowing what the user originally specified when presenting the data back to them in an edit screen.
    </p>
    <h4>event_recurrences</h4>
    <p>
      This table specifies additional rules for how events recur.
      An event can have multiple rows in this table to specify multiple rules.
      For instance, to have a weekly event recur on Tuesday and Thursday, you would create a row with day=2 and a row with day=4.
      If there are no rows for an event in this table, it follows basic repetition.
    </p>
    <p>
      For weekly events, the day column specifies the day of week. The other columns are ignored.
    </p>
    <p>
      For monthly events, the day column specifies the day of the month if the week column is null.
      If the week column is not null, it specifies the week of the month (2 would be the 2nd week, -2 would be the 2nd to last week), and the day column specifies the day of the week.
    </p>
    <p>
      For yearly events, the month column specifies the month on which this rule applies.
      If the month column is null, the rule applies for the same month as the original event.
      The day column specifies the day of the month if the week column is null.
      If the week column is not null, it specifies the week of the month (2 would be the 2nd week, -2 would be the 2nd to last week), and the day column specifies the day of the week.
    </p>
    <h4>event_cancellations</h4>
    <p>
      This table stores the start dates of the recurrences of events that should be cancelled.
      If you change something that affects the recurrence schedule of an event, any cancellations will not take effect for that recurrence (unless another recurrence would happen on that same day).
      One could theoretically account for this by offsetting any cancellations when a schedule is changed, but I couldn't figure out a good set of rules for the situations in which to do this.
    </p>
    <h3>interval_for()</h3>
    <pre><?= h(file_get_contents('interval_for.sql')) ?></pre>
    <p>
      This function returns the interval to use when calculating the date of the next recurrence in a series.
      monthly_by_week_dow and yearly_by_week_dow return the closest value to a month and a year that are multiples of 7.
      If you add 364 days to January 1st, you're still in the same year, so we have to later account for the fact that these values are less than a month and year, respectively.
    </p>
    <h3>intervals_between()</h3>
    <pre><?= h(file_get_contents('intervals_between.sql')) ?></pre>
    <p>
      David's original recurring_events_for() called generate_recurrences() to generate every recurrence after the original date of the event and disregarded the ones before the start of the range.
      That is fine for ranges in the near future, but for events far away it becomes very taxing.
      If I have an event that recurs daily and originally started a year ago, there are 365 dates generated that are discarded.
      With a lot of events that started recurring a long time ago, this could add up.
    </p>
    <p>
      I use this function so that I can know how many dates I can skip generating since they're before the start of the range.
      I still have to maintain the day of the week, day of the month, etc., so I can't just start generating dates at the start of the range.
      The easiest way to figure out how many dates I can skip generating without screwing up the the recurrence schedule is count the number of times I can add the interval to the original date before it passes the start of the range.
      But increasing the number of intervals one at a time and checking if it's before the range start again is just as slow as generating all the dates! Doh!
    </p>
    <p>
      That's where the multiplier variable comes in.
      It tries to add a large number of intervals at once and decreases if it goes beyond the range start.
      For dates that are few intervals apart, this provides no performance increase (actually a decrease if the dates are close enough).
      For dates that are far away from each other, though, this provides a huge performance increase.
      For dates that are 5 days apart, the multiplier causes the function to go through 11 iterations instead of 5.
      For dates that are 365 days apart, though, the multipler reduces the iterations from 365 down to 15.
    </p>
    <h3>generate_recurrences()</h3>
    <pre><?= h(file_get_contents('generate_recurrences.sql')) ?></pre>
    <p>
      Instead of checking the recurrence type and then running the same while loop for every type except one-time events, I get the interval from interval_for() and just run the while loop.
      Since recurring_events_for() never calls this function for one-time events, I don't have to worry about those.
      All of the original functionality from David's function, refactored as stated above, is in the "else" section at the end of the function.
    </p>
    <p>
      The chunk of code for positive_week_dow and negative_week_dow generates dates for events that recur on certain days of the week in certain weeks of the month.
      It does this by adjusting to the correct year and/or month using values close to a year or month that are multiples of 7 (28 and 364) to maintain the day of week, then adjusting the week in the month by adding or removing weeks.
      I'm not too good at explaining things, so I won't even try to articulate what the ceil() and floor() crap does.
      Staring at a calendar for a while and figuring out the algorithms would be easier than reading an explanation I wrote.
    </p>
    <h3>recurring_events_for()</h3>
    <pre><?= h(file_get_contents('recurring_events_for.sql')) ?></pre>
    <p>
      This is the main function for finding events in a certain range.
      It starts out by selecting one-time events inside of the range and all repeating events, left joining our recurrence rules from the event_recurrences table.
      Since we're returning a set of events instead of a set of records, the recurrence rules can't be stored in the event variable, so we need the row variable as well.
    </p>
    <p>
      The function starts out by returning any one-time events and continuing to the next row.
      If only the rest were that simple...
    </p>
    <p>
      The next chunk takes either date or starts_at and ends_at and makes start_date and end_date so that we won't have to worry about whether this is an all day or time span event.
      It also stores the original date and start time of the event so that we can know what they were after changing the dates for each recurrence.
    </p>
    <p>
      The next chunk checks if the original event falls within the range and returns it if it wasn't cancelled.
      Even if the original date doesn't follow the recurrence rules, it should still be returned (business logic...if you diagree, remove this chunk).
    </p>
    <p>
      After that, there is a series of if/elsif statements to figure out an offset that adjusts the start_date to the first date that matches the recurrence rules.
      Offset is then adjusted to be greater than 0 (in case the application of a rule put us before the original date) and is added to start_date and end_date to give us the first date of the series.
      If this is just a basic recurring event (no rows in event_recurrences for it), none of rules get applied, so offset is 0 and start_date will be unchanged.
    </p>
    <p>
      The next chunk of code checks to see if we've set until or count, and adjusts the end of the generated recurrences so that we don't have too many events.
    </p>
    <p>
      Finally, we loop through our generated recurrences, change date or starts_at and ends_at to the values for this recurrence, and return it if it wasn't cancelled.
    </p>
    <h2>Post a Comment</h2>
    <form method="post" action="/recurring_events/">
      <p>
        <label for="name">Name</label>
        <em>Optional</em>
        <br />
        <input id="name" type="text" name="name" value="<?= h($_POST['name']) ?>" />
      </p>
      <p>
        <label for="email">Email</label>
        <em>Optional</em>
        <br />
        <input id="email" type="text" name="email" value="<?= h($_POST['email']) ?>" />
      </p>
      <p>
        <label for="url">URL</label>
        <em>Optional</em>
        <br />
        <input id="url" type="text" name="url" value="<?= h($_POST['url']) ?>" />
      </p>
      <p>
        <label for="body">Body</label>
        <br />
        <textarea id="body" name="body" rows="10" cols="70"><?= h($_POST['body']) ?></textarea>
      </p>
      <p>
        <input type="submit" value="Post" />
      </p>
    </form>
    <h2>Comments</h2>
<?php
$comments = mysql_query('select * from comments order by timestamp');
if (mysql_num_rows($comments) == 0) {
?>
    <p>
      No comments have been posted yet
    </p>
<?php
}
while ($comment = mysql_fetch_assoc($comments)) { ?>
    <h3>
      At <?= date('g:ia', strtotime($comment['timestamp'])) ?> UTC on <?= date('l, F j, Y', strtotime($comment['timestamp'])) ?>,
<?php
if ($comment['name']) {
  if ($comment['email']) {
?>
      <a href="mailto:<?= h($comment['email']) ?>"><?= h($comment['name']) ?></a>
<?php
    if ($comment['url']) {
?>
      (<a href="<?= h($comment['url']) ?>"><?= h($comment['url']) ?></a>)
<?php
    }
  } else if ($comment['url']) {
?>
      <a href="<?= h($comment['url']) ?>"><?= h($comment['name']) ?></a>
<?php
  } else {
?>
      <?= h($comment['name']) ?>
<?php
  }
} else if ($comment['email'] || $comment['url']) {
  if ($comment['email']) {
?>
      <a href="mailto:<?= h($comment['email']) ?>"><?= h($comment['email']) ?></a>
<?php
  } else {
?>
      <em>Anonymous</em>
<?php
  }
  if ($comment['url']) {
?>
      (<a href="<?= h($comment['url']) ?>"><?= h($comment['url']) ?></a>)
<?php
  }
}
else
{
?>
      <em>Anonymous</em>
<?php
}
?>
      said:
    </h3>
    <p>
      <?= nl2br(h($comment['body'])) ?>
    </p>
<?php } ?>
  </body>
</html>
