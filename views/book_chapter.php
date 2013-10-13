<?php
/**
 * View for 'Book Chapter' Citation Type
 * @mvc View
 * @var array  $author - []['last'], []['first'], []['middle']
 * @var array  $co_author - []['last'], []['first'], []['middle']
 * @var string $text_year
 * @var string $text_chapter_title
 * @var string $text_title
 * @var string $text_pages
 * @var string $text_location
 * @var string $text_publisher
 * @var string $select_electronic_ref_type
 * @var string $text_numeric_doi
 * @var string $text_alphanumeric_doi
 * @var string $text_url
 * @author Jenny Sharps <jsharps85@gmail.com>
 *
 *  ex: Calfee, R. C., & Valencia, R. R. (2008). The Border. In <em>The Best American Travel
 *      Writing</em> (pp. 107-123). New York, NY: Springer.
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

<?php if( isset( $text_year ) ) { ?> (<?php echo $text_year; ?>).<?php } ?>
<?php if( isset( $text_chapter_title ) ) { ?> <?php echo $text_chapter_title; ?>.<?php } ?>
<?php if( isset( $text_title ) ) { ?> In <cite><?php echo $text_title; ?></cite><?php } ?>
<?php if( isset( $text_pages ) ) { ?> (pp. <?php echo $text_pages; ?>)<?php } ?>
<?php if( isset( $text_title ) || isset( $text_pages ) ) { ?>.<?php } ?>

<?php
if( isset( $text_location ) ) {
    echo $text_location;
    if( isset( $text_publisher ) ) {
        echo ':';
    } else {
        echo '.';
    }
}
?>

<?php if( isset( $text_publisher ) ) { ?> <?php echo $text_publisher; ?>.<?php } ?>

<?php
if( isset( $select_electronic_ref_type ) ) {

    switch( $select_electronic_ref_type ) {
        case 'numeric_doi':
            if( $text_numeric_doi )
                $electronic_ref_markup = " doi:$text_numeric_doi";
            break;
        case 'alphanumeric_doi':
            if( $text_alphanumeric_doi )
                $electronic_ref_markup = " <a href='{$text_alphanumeric_doi}' target='_blank'>{$text_alphanumeric_doi}</a>";
            break;
        case 'url':
            if( $text_url )
                $electronic_ref_markup = " Retrieved from <a href='{$text_url}' target='_blank'>{$text_url}</a>";
            break;
    }

    echo $electronic_ref_markup;
}