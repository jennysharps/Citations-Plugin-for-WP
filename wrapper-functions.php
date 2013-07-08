<?php

/**
 * Returned formatted citation
 * @param int $citation_id
 * @author Jenny Sharps <jsharps85@gmail.com>
 */
function get_citation( $citation_id ) {
        return JLS\Citations\Citation::getCitation( $citation_id );
};