<?php
class Post_Analytics {
	
	var $date_start;
	var $date_end;
	var $type;
	var $published_post_count;
	var $approved_comment_count;
		
	/**
	 * post_analytics()
	 *
	 * Constructor.
	 */
	function post_analytics() {
	
		return 'Test';
	
	}
	
	/**
	 * date_handler($date_start, $date_end)
	 *
	 * Date Validation
	 * $date_start expects a date formatted as YYYYMMDD or the string 'all' or ''
	 * $date_end expects a date formatted as YYYYMMDD or the string 'all' or ''
	 * If either $date_start or $date_end are set to 'all' or '', set dates to 00010101 and 99990101
	 */
	function date_handler($date_start, $date_end) {

		// check if either $date_start or $date_end have the value 'all'
		if ( $date_start == 'all' || $date_start == '' || $date_end == 'all' || $date_end == '' ) {
			return array ('start' => '00010101', 'end' => '99990101');
		}
		else {
			// check $date_start format and validity
			if ( is_numeric($date_start) && (strlen($date_start) == '8') ) {
				
				// check $date_end format and validity
				if ( is_numeric($date_end) && (strlen($date_end) == '8') && $date_end >= $date_start ) {
					$date_end = date("Ymd", strtotime(date("Ymd", strtotime($date_end)) . " +1 day"));
					return array ('start' => $date_start, 'end' => $date_end);
				}
				else { return 'End date is not valid.'; }
			}
			else { return 'Start date is not valid.'; }
		}
	}
	
	/**
	 * post_analytics_totals($date_start, $date_end, $type)
	 *
	 * get count of posts or pages that are published
	 * count post_date in wp_posts where post_date in date range and post_type = "post" (or "page" if defined) and post_status = "published"
	 * $post_type expects either 'post' or 'page'; if not set, default to 'post'
	 * if either date is invalid, data return is for all time
	 */
	function post_analytics_totals($date_start, $date_end, $type) {
		
		global $wpdb;
		
		if ( !isset($type) ) { $type = 'post'; }
		
		$date = $this->date_handler($date_start, $date_end);
		
		// get count of posts that are published
		$published_post_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->posts WHERE post_type = '$type' AND post_status = 'publish' AND post_date BETWEEN $date[start] AND $date[end]"));
		
		return $published_post_count;
		
	}
	
	/**
	 * post_analytics_by_date($date_start, $date_end, $type)
	 *
	 * get array of data containing posts and the dates they were published
	 * $post_type expects either 'post' or 'page'; if not set, default to 'post'
	 * if either date is invalid, data return is for all time
	 */
	function post_analytics_by_date($date_start, $date_end, $type) {
	
		global $wpdb;
		
		if ( !isset($type) ) { $type = 'post'; }
		
		$date = $this->date_handler($date_start, $date_end);

		$posts_by_date = $wpdb->get_results($wpdb->prepare("SELECT post_date, COUNT(*) as count FROM $wpdb->posts WHERE post_type = '$type' AND post_status = 'publish' AND post_date BETWEEN $date[start] AND $date[end] GROUP BY DATE(post_date)"));
		
		$data_set = array();
		
		// Loop through the returned data
		foreach ($posts_by_date as $post_by_date) {
			// For plotting graph
			$data_set[] = array( strtotime($post_by_date->post_date)*1000, $post_by_date->count );
		}
		
		return $data_set;
	
	}
	
	/**
	 * comment_analytics_totals($date_start, $date_end, $type)
	 *
	 * count comment_date in wp_comments where comment_date in date range and comment_type = "comment" & comment_approved = "1"
	 * count comment_date in wp_comments where comment_date in date range and comment_type = "trackback" & comment_approved = "1"
	 * count comment_date in wp_comments where comment_date in date range and comment_type = "pingback" & comment_approved = "1"
	 */
	function comment_analytics_totals($date_start, $date_end, $type) {
		
		global $wpdb;
		
		$date = $this->date_handler($date_start, $date_end);
		
		$approved_comment_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1' AND comment_type = '$type' AND comment_date BETWEEN $date[start] AND $date[end]"));
		
		return $approved_comment_count;
	}
	
	/**
	 * comment_analytics_by_date($date_start, $date_end)
	 *
	 * Get array of data containing comments and the dates they were approved/published/added.
	 * If either date is invalid, data return is for all time.
	 */
	function comment_analytics_by_date($date_start, $date_end, $type) {

		global $wpdb;
		
		$date = $this->date_handler($date_start, $date_end);
		
		$comments_by_date = $wpdb->get_results($wpdb->prepare("SELECT comment_date, COUNT(*) as count FROM $wpdb->comments WHERE comment_approved = '1' AND comment_type='$type' AND comment_date BETWEEN $date[start] AND $date[end] GROUP BY DATE(comment_date)"));
		
		$data_set = array();
		
		// Loop through the returned data
		foreach ($comments_by_date as $comment_by_date) {
			// For plotting graph
			$data_set[] = array( strtotime($comment_by_date->comment_date)*1000, $comment_by_date->count );
		}
		
		return $data_set;
		
	}
	
	/**
	 * user_analytics_totals($date_start, $date_end)
	 *
	 * count user_registered in wp_users where user_registered in date range							
	 */
	function user_analytics_totals($date_start, $date_end) {
	
		global $wpdb;
		
		$date = $this->date_handler($date_start, $date_end);
		
		$registered_user_count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE user_registered BETWEEN $date[start] AND $date[end]"));
		
		return $registered_user_count;
	}

	/**
	 * user_analytics_by_date($date_start, $date_end)
	 *
	 * Get array of data containing users and the dates they registered.
	 * If either date is invalid, data return is for all time.
	 */
	function user_analytics_by_date($date_start, $date_end) {

		global $wpdb;
		
		$date = $this->date_handler($date_start, $date_end);
		
		$users_by_date = $wpdb->get_results($wpdb->prepare("SELECT user_registered, COUNT(*) as count FROM $wpdb->users WHERE user_registered BETWEEN $date[start] AND $date[end] GROUP BY DATE(user_registered)"));							
		
		$data_set = array();
		
		// Loop through the returned data
		foreach ($users_by_date as $user_by_date) {
			// For plotting graph
			$data_set[] = array( strtotime($user_by_date->user_registered)*1000, $user_by_date->count );
		}
		
		return $data_set;
		
	}

}

class Site_Health {	
	function site_health() {}
	
	/**
	 * site_health_today()
	 *
	 * Check today's posts and comments against the average number of daily posts
	 * and comments. Evaluate them using the following criteria:
	 * Great	= Twice the average
	 * Good		= 1.5 to 1.9 times the average
	 * Okay		= .5 to 1.4 times the average
	 * Bad		= .5 or less of the average
	 * 
	 * Posts and comments are weighted evenly; their results are averaged together.
	 */
	function site_health_today() {
		global $wpdb;
		
		// get today's date
		$today = getdate();
		
		// get the average number of posts per day
		// get the date of the first post
		$first_post_date = strtotime($wpdb->get_var($wpdb->prepare("SELECT post_date FROM $wpdb->posts ORDER BY post_date ASC LIMIT 1")));
		
		// count the number days between the first post and today
		$number_of_days_between_posts = round((strtotime("now") - $first_post_date) / 86400);
		
		// get the total number of posts on the site
		$total_number_of_posts = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post'");
		
		// divide the total number of posts by the number of days
		// avoid division by 0 or negative numbers
		if ( $number_of_days_between_posts > 0 ) {
			$average_posts_per_day = $total_number_of_posts / $number_of_days_between_posts;
		} else {
			$average_posts_per_day = 0;
		}
		
		// get the number of posts for today
		$posts_from_today = count(get_posts('numberposts=-1&year=' .$today["year"] .'&monthnum=' .$today["mon"] .'&day=' .$today["mday"] ));
		
		// calculate the posts "score"
		// avoid division by 0 or negative numbers
		if ( $average_posts_per_day > 0 ) { $posts_score = $posts_from_today / $average_posts_per_day; }
		// avoid giving a positive score when there are 0 posts on the site
		elseif ( $posts_from_today = 0 AND $average_posts_per_day = 0 ) { $posts_score = 0; }
		// give the best score possible if there are no average posts per day vs any posts today
		else { $posts_score = 2; }
		
		// get the average number of comments per day
		// get the date of the first comment
		$first_comment_date = strtotime($wpdb->get_var($wpdb->prepare("SELECT comment_date FROM $wpdb->comments ORDER BY comment_date ASC LIMIT 1")));
		
		// count the days between the first comment and today
		$number_of_days_between_comments = round((strtotime("now") - $first_comment_date) / 86400);
		
		// get the total number of comments on the site
		$total_number_of_comments = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1' AND comment_type != 'trackback' AND comment_type != 'pingback'");
		
		// divide the total number of comments by the number of days
		// avoid division by 0 or negative numbers
		if ( $number_of_days_between_comments > 0 ) {
			$average_comments_per_day = $total_number_of_comments / $number_of_days_between_comments;
		} else {
			$average_comments_per_day = 0;
		}
		
		// get the number of comments for today
		$comments_from_today = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE YEAR(comment_date) = $today[year] AND MONTH(comment_date) = $today[mon] AND DAY(comment_date) = $today[mday]");
		
		// calculate the comments "score"
		// avoid division by 0 or negative numbers
		if ( $average_comments_per_day > 0 ) { $comments_score = $comments_from_today / $average_comments_per_day; }
		// avoid giving a positive score when there are 0 comments on the site
		elseif ( $comments_from_today = 0 AND $average_comments_per_day = 0 ) { $comments_score = 0; }
		// give the best score possible if there are no average comments per day vs any comments today
		else { $comments_score = 2; }

		// average the "scores" together and return the site health score
		$average_score = ($posts_score + $comments_score) / 2;
		
		// return the appropriate text based on the average score
		if ( $average_score <= .5 ) { $health_today = 'Bad!'; }
		elseif ( $average_score > .5 AND $average_score <= 1.5 ) { $health_today = 'Okay.'; }
		elseif ( $average_score > 1.5 AND $average_score <= 2 ) { $health_today = 'Good.'; }
		else { $health_today = 'Great!'; }
		
		return $health_today;
	}
	
	/**
	 * top_posters()
	 *
	 * Return five members in descending order by total number of posts. Return
	 * their nice name and the number of posts.
	 */
	function top_posters() {
		global $wpdb;

		// build an array containing user's ID, nice name, and count of total posts
		$top_posters = $wpdb->get_results($wpdb->prepare("SELECT u.ID, u.display_name, COUNT(p.post_author) AS num_posts
														   FROM $wpdb->users AS u
														   LEFT JOIN $wpdb->posts AS p ON u.ID = p.post_author
														   WHERE p.post_type = 'post' AND p.post_status = 'publish'
														   GROUP BY u.ID
														   ORDER BY num_posts DESC LIMIT 5"));

		return $top_posters;
	}
	
	/**
	 * top_commenters()
	 *
	 * Return five members in descending order by total number of comments. Return
	 * their nice name and the number of comments.
	 */
	function top_commenters() {
		global $wpdb;

		$top_commenters = $wpdb->get_results($wpdb->prepare("SELECT comment_author, comment_author_email, COUNT(comment_author) AS num_comments 
																FROM $wpdb->comments
																WHERE comment_approved = '1' AND comment_type != 'trackback' AND comment_type != 'pingback'
																GROUP BY comment_author
																ORDER BY num_comments DESC LIMIT 5"));

		return $top_commenters;
	}
}

class Stickers {}
?>