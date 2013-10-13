<?php
/**
 * View for 'Book' Citation Type
 * @mvc View
 * @var array  $author - []['last'], []['first'], []['middle']
 * @var array  $co_author - []['last'], []['first'], []['middle']
 * @var string $text_year
 * @var string $text_title
 * @var string $text_location
 * @var string $text_publisher
 * @author Jenny Sharps <jsharps85@gmail.com>
 *
 *  ex: Calfee, R. C., & Valencia, R. R. (1991). <em>APA guide to preparing manuscripts
 *      for journal publication.</em> Washington, DC: American Psychological Association.
 *
 */
?>

<br><br>remove brs in <?php echo basename( __FILE__, '.php' ); ?>.php view<br><br>

<?php
$co_author = array_filter( $co_author );

if( is_array( $author[0] ) ) {
    $author_markup = $author[0]['last'] ? $author[0]['last'] : '';
    $author_markup .= $author[0]['last'] && ( $author[0]['first'] || $author_item['middle'] ) ? ',' : '';
    $author_markup .= $author[0]['first'] ? ' ' . ucfirst( $author[0]['first'][0] ) . '.' : '';
    $author_markup .= $author[0]['middle'] ? ' ' . ucfirst(  $author[0]['middle'][0] ) . '.' : '';
    $author_markup .= count( $co_author ) > 0 ? ', ' : ' ';

    echo $author_markup;
} ?>

<?php
if( is_array( $co_author ) ) {
    foreach( $co_author as $key => $author_item ) {
        $co_author_markup =  count( $co_author ) + 1 != $key ? ' &' : '';
        $co_author_markup .= isset( $author_item['last'] ) ? ' ' . $author_item['last'] : '';
        $co_author_markup .= isset( $author_item['last'] ) && ( isset( $author_item['first'] ) || isset( $author_item['middle'] ) ) ? ',' : '';
        $co_author_markup .= isset( $author_item['first'] ) ? ' ' . $author_item['first'][0] . '.' : '';
        $co_author_markup .= isset( $author_item['middle'] ) ? ' ' . $author_item['middle'][0] . '.' : '';
        $co_author_markup .= $co_author_markup && count( $co_author ) != $key + 1 ? ',' : '';

        echo $co_author_markup;
    }
} ?>

<?php if( isset( $text_year ) ) { ?> (<?php echo $text_year; ?>).<?php } ?>
<?php if( isset( $text_title ) ) { ?> <cite><?php echo $text_title; ?></cite>. <?php } ?>
<?php if( isset( $text_location ) ) {
    echo $text_location;
    if( isset( $text_publisher ) ) { ?>: <?php } else { ?>.<?php }
} ?>
<?php if( isset( $text_publisher ) ) { ?><?php echo $text_publisher; ?>.<?php } ?>

<br><br><hr><br><br>