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
 * @var string $select_electronic_ref_type
 * @var string $text_numeric_doi
 * @var string $text_alphanumeric_doi
 * @var string $text_url
 * @author Jenny Sharps <jsharps85@gmail.com>
 *
 *  ex: Jacoby, W. G. (1994). Public attitudes toward government spending. <em>American
 *      Journal of Political Science</em>, vol#(issue#), 336-361. doi:10.1108/03090560710821161 OR
 *      http://dx.doi.org/10.1016/j.appdev.2012.05.005 OR
 *      Retrieved from http://www.journalhomepage.com/full/url/
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
        $co_author_markup =  count( $co_author ) + 1 != $key ? ' &' : '';
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
<?php if( isset( $text_title ) ) { ?> <?php echo $text_title; ?>.<?php } ?>
<?php if( isset( $text_journal_title ) ) { ?> <cite><?php echo $text_journal_title; ?></cite>.<?php } ?>

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
