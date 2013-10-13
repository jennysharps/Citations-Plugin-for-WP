<?php
/**
 * View for 'Conference' Citation Type (unpublished: speeches, lectures, or other oral presentations )
 * @mvc View
 * @var array  $author - []['last'], []['first'], []['middle']
 * @var array  $co_author - []['last'], []['first'], []['middle']
 * @var string $text_year
 * @var string $text_month
 * @var string $text_title
 * @var string $text_description
 * @var string $text_extended_location
 * @author Jenny Sharps <jsharps85@gmail.com>
 *
 *  ex: Stein, Bob. "Computers and Writing Conference Presentation." Purdue University.
 *      Union Club Hotel, West Lafayette, IN. 23 May 2003. Keynote Address.
 *
 */
?>

<?php
$co_author = array_filter( $co_author );

if( is_array( $author[0] ) ) {
    $author_markup = isset( $author[0]['last'] ) ? $author[0]['last'] : '';
    $author_markup .= isset( $author[0]['last'] ) && ( isset( $author[0]['first'] ) || isset( $author_item['middle'] ) ) ? ',' : '';
    $author_markup .= isset( $author[0]['first'] ) ? ' ' . ucfirst( $author[0]['first'][0] ) . '.' : '';
    $author_markup .= isset( $author[0]['middle'] ) ? ' ' . ucfirst(  $author[0]['middle'][0] ) . '.' : '';
    $author_markup .= isset( $author[0]['last'] ) && ( !isset( $author[0]['first'] ) && !isset( $author[0]['middle'] ) ) && count( $co_author ) == 0 ? ',' : '';
    $author_markup .= count( $co_author ) > 0 ? ', ' : ' ';

    echo $author_markup;
} ?>

<?php
if( is_array( $co_author ) ) {
    foreach( $co_author as $key => $author_item ) {
        $co_author_markup =  count( $co_author ) == $key + 1 ? ' and' : '';
        $co_author_markup .= isset( $author_item['last'] ) ? ' ' . $author_item['last'] : '';
        $co_author_markup .= isset( $author_item['last'] ) && ( isset( $author_item['first'] ) || isset( $author_item['middle'] ) ) ? ',' : '';
        $co_author_markup .= isset( $author_item['first'] ) ? ' ' . $author_item['first'][0] . '.' : '';
        $co_author_markup .= isset( $author_item['middle'] ) ? ' ' . $author_item['middle'][0] . '.' : '';
        $co_author_markup .= isset( $author_item['last'] ) && ( !isset( $author_item['first'] ) && !isset( $author_item['middle'] ) ) && count( $co_author ) == $key + 1 ? ',' : '';
        $co_author_markup .= $co_author_markup && count( $co_author ) != $key + 1 ? ',' : '';

        echo $co_author_markup;
    }
} ?>

<?php if(isset( $text_title ) ) { ?>"<?php echo $text_title; ?>."<?php } ?>
<?php if(isset( $text_extended_location ) ) { ?> <?php echo rtrim( $text_extended_location, '.' ); ?>.<?php } ?>
<?php if( isset($text_month) ) { ?> <?php echo $text_month; } ?>
<?php if( isset( $text_year ) ) { ?> <?php echo $text_year; } ?>
<?php if( isset( $text_month ) || isset( $text_year )  ) { ?>.<?php } ?>
<?php if( isset( $text_description ) ) { ?> <?php echo $text_description; ?>.<?php } ?>