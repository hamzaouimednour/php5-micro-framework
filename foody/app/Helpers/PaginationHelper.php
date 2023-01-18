<?php 

class Pagination {
    protected $config = array(
		'current_page'      => null,
		'offset'            => 0,
		'per_page'          => 10,
		'total_pages'       => 0,
		'total_items'       => 0,
		'num_links'         => 5,
		'uri_segment'       => 3,
		'show_first'        => false,
		'show_last'         => false,
		'pagination_url'    => null,
		'link_offset'       => 0.5,
		'default_page'      => 'first',
	);
	private $total_records, $tot_pages, $limit, $start_from, $page;
	//$tot_pages = ceil ($total_records / $limit);
	// $start_from = ($page - 1) * $limit;
	//  "SELECT * FROM tbl_name ORDER BY id ASC LIMIT  $start_from,  $limit"; 

	public function front_pagination($item_per_page, $current_page, $total_records, $total_pages)
	{
		$pagination = '';
		if ($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages) { //verify total pages and current page number
			$pagination .= '<ul class="pagination justify-content-center">';

			$right_links    = $current_page + 3;
			$previous       = $current_page - 3; //previous link 
			$next           = $current_page + 1; //next link
			$first_link     = true; //boolean var to decide our first link

			if ($current_page > 1) {
				$previous_link = ($previous == 0) ? 1 : $previous;
				$pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="1" title="First">«</a></li>'; //first link
				$pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $previous_link . '" title="Previous"><</a></li>'; //previous link
				for ($i = ($current_page - 2); $i < $current_page; $i++) { //Create left-hand side links
					if ($i > 0) {
						$pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $i . '" title="Page' . $i . '">' . $i . '</a></li>';
					}
				}
				$first_link = false; //set first link to false
			}

			if ($first_link) { //if current active page is first link
				$pagination .= '<li class="page-item active">
								<span class="page-link">' . $current_page . '
								<span class="sr-only">(current)</span>
                                </span>
                            </li>';
			} elseif ($current_page == $total_pages) { //if it's the last active link
				$pagination .= '<li class="page-item active">
								<span class="page-link">' . $current_page . '
								<span class="sr-only">(current)</span>
                                </span>
                            </li>';
			} else { //regular current link
				$pagination .= '<li class="page-item active">
								<span class="page-link">' . $current_page . '
								<span class="sr-only">(current)</span>
                                </span>
                            </li>';
			}

			for ($i = $current_page + 1; $i < $right_links; $i++) { //create right-hand side links
				if ($i <= $total_pages) {
					$pagination .= '<li><a href="#" data-page="' . $i . '" title="Page ' . $i . '">' . $i . '</a></li>';
				}
			}
			if ($current_page < $total_pages) {
				$next_link = ($i > $total_pages) ? $total_pages : $i;
				$pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $next_link . '" title="Next">></a></li>'; //next link
				$pagination .= '<li class="page-item"><a class="page-link" href="#" data-page="' . $total_pages . '" title="Last">»</a></li>'; //last link
			}

			$pagination .= '</ul>';
		}
		return $pagination; //return pagination links
	}


}
?>