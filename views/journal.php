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
 * @var string $text_issue
 * @var string $text_pages
 * @author Jenny Sharps <jsharps85@gmail.com>
 *
 *  ex: Jacoby, W. G. (1994). Public attitudes toward government spending. American
 *      Journal of Political Science, 38(2), 336-361.
 *
 */
?>

<br><br>

<?php
$co_author = array_filter( $co_author );

$author_count = count( $author ) + count( $co_author );
echo $author_count . '<br>';

if( is_array( $author[0] ) ) {
    $author_markup = $author[0]['last'] ? $author[0]['last'] : '';
    $author_markup .= $author[0]['last'] && ( $author[0]['first'] || $author_item['middle'] ) ? ', ' : '';
    $author_markup .= $author[0]['first'] ? ucfirst( $author[0]['first'][0] ) . '.' : '';
    $author_markup .= $author[0]['middle'] ? ucfirst(  $author[0]['middle'][0] ) . '.' : '';
    $author_markup .= count( $co_author ) > 0 ? ', ' : ' ';

    echo $author_markup;
} ?>

<?php
if( is_array( $co_author ) ) {
    foreach( $co_author as $key => $author_item ) {
        $co_author_markup = isset( $author_item['last'] ) ? $author_item['last'] : '';
        $co_author_markup .= isset( $author_item['last'] ) && ( isset( $author_item['first'] ) || isset( $author_item['middle'] ) ) ? ', ' : '';
        $co_author_markup .= isset( $author_item['first'] ) ? $author_item['first'][0] . '.' : '';
        $co_author_markup .= isset( $author_item['middle'] ) ? $author_item['middle'][0] . '.' : '';
        $co_author_markup .= $co_author_markup && count( $co_author ) != $key + 1 ? ', ' : '';

        echo $co_author_markup;
    }
} ?>

<?php if( isset( $text_title ) ) { ?><?php echo $text_title; ?><?php } ?>
<?php if( isset( $text_journal_title ) ) { ?><cite><?php echo $text_journal_title; ?></cite><?php } ?>

