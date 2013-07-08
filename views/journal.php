<?php
/**
 * View for 'Journal' Citation Type
 * @mvc View
 * @var array  $author - []['last'], []['first'], []['middle']
 * @var array  $co_author - []['last'], []['first'], []['middle']
 * @var string $text_year
 * @var string $text_title
 * @var string $text_journal_title
 * @var string $text_volume
 * @var string text_issue
 * @var string text_pages
 * @author Jenny Sharps <jsharps85@gmail.com>
 */
?>

<h3>Journal VIEW</h3>
<?php echo $author[0]['first']; ?><br>
<?php echo $co_author[1]['first']; ?>