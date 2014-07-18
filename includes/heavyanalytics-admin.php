<?php
/**
 * heavyanalytics_admin_page_top()
 *
 * Displays top of page info in the heavy analytics admin area
 */
function heavyanalytics_admin_page_top($name) {
	
	?>
	<div class="wrap">
		<h2 class="heavy-admin-top-icon"><?php _e( $name, 'heavy-analytics' ) ?></h2>

        <div id="heavy-analytics-main-menu-navigation" data-step="1" data-intro="This is the main navigation menu. Use this menu to navigate between the different sections of Heavy Analytics.">
        	<ul>
            	<?php /*?><li data-step="2" data-intro="Link to the main Heavy Analytics dashboard."><span class="bracket">}&nbsp;&nbsp;</span><a href="<?php echo get_admin_url(); ?>?page=heavy-analytics">Heavy Analytics Home</a><span class="bracket">&nbsp;&nbsp;|</span></li><?php */?>
            	<li data-step="3" data-intro="Click here to send us ideas or feedback, or to report an issue."><span class="bracket">}&nbsp;&nbsp;</span><a href="http://wordpress.org/support/plugin/heavy-analytics"><?php _e( 'Ideas, Help, & Feedback', 'heavy-analytics' ) ?></a><span class="bracket">&nbsp;&nbsp;|</span></li>
                <li data-step="4" data-intro="Click here to start the tour you are currently on."><a href="javascript:void(0);" onclick="javascript:introJs().start();"><?php _e( 'Take a Tour', 'heavy-analytics' ) ?></a><span class="bracket">&nbsp;&nbsp;{</span></li>
            </ul>
        </div>
	<?php
}

/**
 * heavyanalytics_admin_page_bottom()
 *
 * Displays bottom of page info in the heavy analytics admin area
 */
function heavyanalytics_admin_page_bottom() {
	
	?>
	</div><!-- .wrap -->
	<?php
}

/**
 * heavyanalytics_admin()
 *
 * Checks for form submission, saves component settings and outputs admin screen HTML.
 */
function heavyanalytics_admin() {
	
	echo '<div id="heavy-analytics-home-view">';
	
	heavyanalytics_admin_page_top('Heavy Analytics');
	
	if ( isset($_POST['submit']) && isset($_POST['start_date']) && isset($_POST['end_date']) && check_admin_referer('heavy-analytics') ) {
		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
	}
	else {
		$start_date = 'all';
		$end_date = 'all';
	}
	?>
    
    <div id="heavy-analytics-site-health" data-step="5" data-intro="This area of the dashboard indicates your overall Site Health. Site Health is a measure of today's volume of posts and comments against an average of the site's historical daily volume of posts and comments. The criteria used is as follows: Great = Twice the average, Good = 1.5 to 1.9 times the average, Okay = .5 to 1.4 times the average, Bad = .5 or less of the average. Posts and comments are weighted evenly; their results are averaged together.">
        <?php
        $obj = new Site_Health();
        $site_health = $obj->site_health_today();
        $site_health_class = strtolower( preg_replace('/[^a-z]+/i', '', $obj->site_health_today()) );
        ?>
        <p><?php _e( 'Activity today is', 'heavy-analytics' ); ?> <span id="show-option" title="this is a tooltip test" class="heavy-analytics-site-health-<?php echo $site_health_class; ?>"><?php echo $site_health; ?></span></p>
    </div><!-- #heavy-analytics-site-health -->

    <div class="heavy-analytics-section-header"><h2><?php _e( 'The', 'heavy-analytics' ); ?> <span class="green"><?php _e( 'best', 'heavy-analytics' ); ?></span> <?php _e( 'posters in your community are:', 'heavy-analytics' ); ?></h2></div><!-- .heavy-analytics-section-header -->
    <div id="heavy-analytics-best-members" class="heavy-analytics-section" data-step="6" data-intro="These are the top posters in your community based on their total number of posts." data-position="top">
    	<div class="heavy-analytics-user">
        	<ul>
        		<?php $obj = new Site_Health(); $top_posters = $obj->top_posters(); ?>
        		<?php
                foreach ($top_posters as $poster) {
                    echo '<li><div class="avatar-container"><div class="user-avatar">' .get_avatar( $poster->ID ). '</div><div class="num-posts">' .$poster->num_posts .'<div class="posts-published">posts published</div></div></div>';
                    echo '<div class="nicename">' .$poster->display_name. '</div></li>';
                }
        		?>
            </ul>
        </div><!-- .heavy-analytics-user -->

    </div><!-- #heavy-analytics-best-members -->

	<div class="heavy-analytics-section-header"><h2><?php _e( 'The', 'heavy-analytics' ); ?> <span class="green"><?php _e( 'best', 'heavy-analytics' ); ?></span> <?php _e( 'commenters in your community are:', 'heavy-analytics' ); ?></h2></div><!-- .heavy-analytics-section-header -->
    <div id="heavy-analytics-best-members" class="heavy-analytics-section" data-step="7" data-intro="These are the top commenters in your community based on their total number of approved comments." data-position="top">
    	<div class="heavy-analytics-user">
        	<ul>
        		<?php $obj = new Site_Health(); $top_commenters = $obj->top_commenters(); ?>
                <?php
                foreach ($top_commenters as $commenter) {
                    echo '<li><div class="avatar-container"><div class="user-avatar">' .get_avatar( $commenter->comment_author_email ). '</div><div class="num-posts">' .$commenter->num_comments .'<div class="posts-published">comments</div></div></div>';
                    echo '<div class="nicename">' .$commenter->comment_author. '</div></li>';
                }
        		?>
            </ul>
        </div><!-- .heavy-analytics-user -->

    </div><!-- #heavy-analytics-best-members -->
    
    <div class="heavy-analytics-section-header"><h2><span class="green"><?php _e( 'Site-wide', 'heavy-analytics' ); ?></span> <?php _e( 'overview:', 'heavy-analytics' ); ?></h2></div><!-- .heavy-analytics-section-header -->
    <div id="heavy-analytics-graph" class="heavy-analytics-section" data-step="8" data-intro="This dynamic chart allows you to view different combinations of metrics over time. You can check or uncheck each series or highlight a section of the chart to customize your view." data-position="top">
        <div id="placeholder" style="width:600px;height:300px"></div>
        <div id="legend-top"></div><div id="choices"></div>
        
        <?php
        $obj = new Post_Analytics(); $data_set_posts = $obj->post_analytics_by_date($start_date, $end_date, 'post');
        $obj = new Post_Analytics(); $data_set_comments = $obj->comment_analytics_by_date($start_date, $end_date, '');
        $obj = new Post_Analytics(); $data_set_users = $obj->user_analytics_by_date($start_date, $end_date);
        $obj = new Post_Analytics(); $data_set_pages = $obj->post_analytics_by_date($start_date, $end_date, 'page');
        $obj = new Post_Analytics(); $data_set_trackbacks = $obj->comment_analytics_by_date($start_date, $end_date, 'trackback');
        $obj = new Post_Analytics(); $data_set_pingbacks = $obj->comment_analytics_by_date($start_date, $end_date, 'pingback');
        
        echo '<script id="source" language="javascript" type="text/javascript">
            
            jQuery(function () {
            
                //put array into javascript variable
                var posts_data_set ='. json_encode($data_set_posts) . '
                var comments_data_set ='. json_encode($data_set_comments) . '
                var users_data_set ='. json_encode($data_set_users) . '
                var pages_data_set ='. json_encode($data_set_pages) . '
                var trackbacks_data_set ='. json_encode($data_set_trackbacks) . '
                var pingbacks_data_set ='. json_encode($data_set_pingbacks) . '
                
                //declare data source
                var datasets = [
                    {
                        data: posts_data_set,
                        label: "Posts Published"
                    },
                    {
                        data: comments_data_set,
                        label: "Comments Approved"
                    },
                    {
                        data: users_data_set,
                        label: "Users Registered"
                    },
                    {
                        data: pages_data_set,
                        label: "Pages Published"
                    },
                    {
                        data: trackbacks_data_set,
                        label: "Trackbacks"
                    },
                    {
                        data: pingbacks_data_set,
                        label: "Pingbacks"
                    }
                ];
                
                //prevent color shifting on toggle
                var i = 0;
                jQuery.each(datasets, function(key, val) {
                    val.color = i;
                    ++i;
                });
                
                //choose options
                var options = {
                    series: { lines: { show: true }, points: { show: true } },
                    legend: {
                     noColums: 1,
                     container: "#legend-top",
                     backgroundColor: null,
                     backgroundOpacity: 0
                    },
                    //xaxis: { mode: "time", timeformat: "%y<br />%m/%d<br />%h%P" },
                    xaxis: { mode: "time" },
                    grid: { hoverable: true },
                    yaxis: { tickDecimals: 0 },
                    selection: { mode: "x" }
					
                };
                
                //data point hover state
                function showTooltip(x, y, contents) {
                    jQuery(\'<div id="tooltip">\' + contents + \'</div>\').css( {
                        position: "absolute",
                        display: "none",
                        top: y + 5,
                        left: x + 5,
                        border: "1px solid #fdd",
                        padding: "2px",
                        "background-color": "#fee",
                        opacity: 0.80
                    }).appendTo("body").fadeIn(200);
                }
        
                var previousPoint = null;
                jQuery("#placeholder").bind("plothover", function (event, pos, item) {
                    jQuery("#x").text(pos.x.toFixed(2));
                    jQuery("#y").text(pos.y.toFixed(2));
                    if (item) {
                        if (previousPoint != item.datapoint) {
                            previousPoint = item.datapoint;
                            
                            jQuery("#tooltip").remove();
                            var x = item.datapoint[0].toFixed(2),
                            y = item.datapoint[1].toFixed(0);
                            
                            var formattedDate = new Date();
                            formattedDate.setTime(x);
                            
                            var pointCount = y;
                            var seriesLabel;
                            if (pointCount == 1) { seriesLabel = item.series.label.replace("s ", " ").replace("Trackbacks", "Trackback").replace("Pingbacks", "Pingback"); }
                            if (pointCount != 1) { seriesLabel = item.series.label; }
                            
                            showTooltip(item.pageX, item.pageY, y + "&nbsp;" + seriesLabel + "<br />on " + formattedDate.toDateString());
                        }
                    }
                    else {
                        jQuery("#tooltip").remove();
                        previousPoint = null;            
                    }
                });
                
                // insert checkboxes 
                var choiceContainer = jQuery("#choices");
                jQuery.each(datasets, function(key, val) {
    
                    choiceContainer.append(\'<br/><input type="checkbox" name="\' + key +
                                           \'" checked="checked" id="id\' + key + \'">\' +
                                           \'<label for="id\' + key + \'">\'
                                           + val.label + \'</label>\');
                });
                choiceContainer.find("input").click(plotAccordingToChoices);
                choiceContainer.append(\'<br /><input type="button" id="resetZoom" value="Reset" />\');
                
                //plot the graph
                function plotAccordingToChoices() {
                
                    var data = [];
                
                    choiceContainer.find("input:checked").each(function () {
                        var key = jQuery(this).attr("name");
                        if (key && datasets[key])
                            data.push(datasets[key]);
                    });
                    
                    if (data.length > 0)
                        var plot = jQuery.plot(jQuery("#placeholder"), data, options);
                }
                plotAccordingToChoices();
                
                jQuery("#placeholder").bind("plotselected", function (event, ranges) {
                    var data = [];
                    
                    choiceContainer.find("input:checked").each(function () {
                        var key = jQuery(this).attr("name");
                        if (key && datasets[key])
                            data.push(datasets[key]);
                    });
                    
                    // do the zooming
                    plot = jQuery.plot(jQuery("#placeholder"), data,
                    jQuery.extend(true, {}, options, {
                        xaxis: { min: ranges.xaxis.from, max: ranges.xaxis.to }
                    }));
                    
                    // Get the current zoom
                    var zoom = plot.getAxes();
                    
                    // Add the zoom to standard options
                    var zoomed = {};
                    jQuery.extend(zoomed,options);
                    zoomed.xaxis.min = zoom.xaxis.min;
                    zoomed.xaxis.max = zoom.xaxis.max;
                });
    
                jQuery("#resetZoom").click(function(){
                    //plot.clearSelection();
                    options = "";
                    options = {
                        series: { lines: { show: true }, points: { show: true } },
                        legend: {
                         noColums: 1,
                     	 container: "#legend-top"
                        },
                        //xaxis: { mode: "time", timeformat: "%y<br />%m/%d<br />%h%P" },
                        xaxis: { mode: "time" },
                        grid: { hoverable: true },
                        yaxis: { tickDecimals: 0 },
                        selection: { mode: "x" }
                    };
    
                    jQuery(\'#choices input:checkbox\').attr("checked", true);
    
                    var data = datasets;
                    var plot = jQuery.plot(jQuery("#placeholder"), data, options);
                  });
    
            });
        </script>';
        ?>
    </div><!-- #heavy-analytics-graph -->
    
    <div class="heavy-analytics-section-header"><h2><span class="green"><?php _e( 'Totals', 'heavy-analytics' ); ?></span> <?php _e( 'view:', 'heavy-analytics' ); ?></h2></div><!-- .heavy-analytics-section-header -->
    <div id="heavy-analytics-spreadsheet-view" class="heavy-analytics-section" data-step="9" data-intro="This table shows content totals across your site." data-position="top">
    
        <table class="widefat fixed" cellspacing="0"> 
            <thead> 
                <tr class="thead"> 
                    <th class="manage-column column-summary_date"></th> 
                    <th class="manage-column column-summary_date"><?php _e( '# of Posts Published', 'heavy-analytics' ); ?></th> 
                    <th class="manage-column column-primary-content-count" id="primary-content-count" scope="col"><?php _e( '# of Comments Approved', 'heavy-analytics' ); ?></th> 
                    <th class="manage-column column-primary-content-percent" id="primary-content-percent" scope="col"><?php _e( '# of Users Registered', 'heavy-analytics' ); ?></th> 
                    <th class="manage-column column-secondary-content-count" id="secondary-content-count" scope="col"><?php _e( '# of Pages Published', 'heavy-analytics' ); ?></th> 
                    <th class="manage-column column-secondary-content-percent" id="secondary-content-percent" scope="col"><?php _e( '# of Trackbacks', 'heavy-analytics' ); ?></th> 
                    <th class="manage-column column-content-inspired-count" id="content-inspired-count" scope="col"><?php _e( '# of Pingbacks', 'heavy-analytics' ); ?></th> 
                </tr> 
            </thead> 
            
            <tbody id="users" class="list:user user-list"> 
                <tr class="" id="user-"> 
                        <td class="summary_date column-summary_date"><strong><?php _e( 'Site-Wide Totals:', 'heavy-analytics' ); ?></strong></td> 
                        <td class="summary_date column-summary_date"><?php $obj = new Post_Analytics(); echo $obj->post_analytics_totals($start_date, $end_date, 'post'); ?></td> 
                        <td class="primary-content-count column-primary-content-count"><?php $obj = new Post_Analytics(); echo $obj->comment_analytics_totals($start_date, $end_date, ''); ?></td> 
                        <td class="primary-content-percent column-primary-content-percent"><?php $obj = new Post_Analytics(); echo $obj->user_analytics_totals($start_date, $end_date); ?></td> 
                        <td class="secondary-content-count column-secondary-content-count"><?php $obj = new Post_Analytics(); echo $obj->post_analytics_totals($start_date, $end_date, 'page'); ?></td> 
                        <td class="secondary-content-percent column-secondary-content-percent"><?php $obj = new Post_Analytics(); echo $obj->comment_analytics_totals($start_date, $end_date, 'trackback'); ?></td> 
                        <td class="content-inspired-count column-content-inspired-count"><?php $obj = new Post_Analytics(); echo $obj->comment_analytics_totals($start_date, $end_date, 'pingback'); ?></td>
				</tr>
			</tbody> 
        </table> 

    </div><!-- #heavy-analytics-spreadsheet-view -->

	<?php
	heavyanalytics_admin_page_bottom();
	
	echo '</div><!-- #heavy-analytics-home-view -->';
}

/**
 * heavyanalytics_admin_posts()
 *
 */
function heavyanalytics_admin_posts() {
	
	echo '<div id="heavy-analytics-posts-view">';
	
	heavyanalytics_admin_page_top('Heavy Analytics Admin: Posts');
	?>
    
    <br />
    
    <img src="<?php echo plugins_url(); ?>/heavyanalytics/includes/images/placeholder-posts-health.jpg" alt="Hot/Cold Meter" />
    <br /><br />
    
    <div id="heavy-admin-posts-by-category" class="heavy-admin-above-graph heavy-admin-float-left">
    
    	<h3><?php _e( 'Posts By Category', 'heavy-analytics' ); ?></h3>
    
    <p>INSERT BIG "CATEGORIES" GRAPH HERE</p>
    <div id="collapser1"><button>Display/Hide Tabular Data for Categories</button></div><!-- #collapser1 -->
    <div id="table1">
        <table id="heavy-admin-table-posts-by-category" class="tablesorter widefat myTable"> 
            <thead>
                <tr class="thead manage-column column-summary_date"> 
                    <th class="manage-column column-summary_date">Name of Category</th> 
                    <th class="manage-column column-summary_date"># of Posts in Category</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php	  
					$number_of_categories = 0;
                    $categories=get_categories();
                    foreach($categories as $category) { 
                        echo '<tr><td class="medium">' . $category->name.'</td>';
                        echo '<td class="short">'. $category->count . '</td></tr>';  
						$number_of_categories++;
                    } 
					
                ?>
            </tbody>
        </table> 
        <table class="table-totals">
        	<tr>
            	<td class="medium table-totals" style="background-color: #CDCDCD; font-weight:bold;">Total Categories:</td>
                <td class="short table-totals" style="background-color: #CDCDCD; font-weight:bold;"><?php echo $number_of_categories; ?></td>
            </tr>
        </table>
    </div><!-- #table1 -->
    <script>
		$("#table1").hide();
		$("#collapser1").click(function () {
			$("#table1").toggle("slow");
		});    
	</script>
    
    </div><!-- #heavy-admin-posts-by-category -->
    
	<div id="heavy-admin-posts-by-tag" class="heavy-admin-above-graph heavy-admin-float-left">
    	<h3>Posts By Tag</h3>
    
    <p>INSERT BIG "TAGS" GRAPH HERE</p>
    <div id="collapser2"><button>Display/Hide Tabular Data for Tags</button></div><!-- #collapser2 -->
    <div id="table2">
        <table id="heavy-admin-table-posts-by-tag" class="tablesorter widefat myTable"> 
            <thead> 
                <tr class="thead manage-column column-summary_date"> 
                    <th class="manage-column column-summary_date">Name of Tag</th> 
                    <th class="manage-column column-summary_date"># of Posts with Tag</th> 
                </tr> 
            </thead> 
            <tbody> 
                <?php	 
					$number_of_tags = 0; 
                    $tags=get_tags();
                    foreach($tags as $tag) { 
                        echo '<tr><td class="medium">' . $tag->name.'</td>';
                        echo '<td class="short">'. $tag->count . '</td></tr>';  
						$number_of_tags++;
                    } 
                ?>
            </tbody> 
        </table> 
        <table class="table-totals">
        	<tr>
                <td class="medium">Total Tags:</td>
                <td class="short"><?php echo $number_of_tags; ?></td>
            </tr>
        </table>
    </div><!-- #table2 -->
    <script>
		$("#table2").hide();
		$("#collapser2").click(function () {
			$("#table2").toggle("slow");
		});    
	</script>
	
	<?php
	heavyanalytics_admin_page_bottom();
	
	echo '</div><!-- #heavy-analytics-posts-view -->';
	echo '</div><!-- #heavy-admin-posts-by-tag -->';
}

/**
 * heavyanalytics_admin_users()
 *
 */
function heavyanalytics_admin_users() {
	
	echo '<div id="heavy-analytics-users-view">';
	
	heavyanalytics_admin_page_top('Heavy Analytics Admin: Users');
    
    heavyanalytics_admin_page_bottom();
	
	echo '</div><!-- #heavy-analytics-users-view -->';
	echo '</div><!-- #heavy-admin-posts-by-tag -->';
}
 
/**
 * heavyanalytics_admin_pages()
 *
 */
function heavyanalytics_admin_pages() {
	
	echo '<div id="heavy-analytics-pages-view">';
	
	heavyanalytics_admin_page_top('Heavy Analytics Admin: Pages');
    
    heavyanalytics_admin_page_bottom();
	
    echo '</div><!-- #heavy-analytics-pages-view -->';
	echo '</div><!-- #heavy-admin-posts-by-tag -->';
}
 
/**
 * heavyanalytics_admin_comments()
 *
 */
function heavyanalytics_admin_comments() {
	
	echo '<div id="heavy-analytics-comments-view">';
	
	heavyanalytics_admin_page_top('Heavy Analytics Admin: Comments');
    
	heavyanalytics_admin_page_bottom();
	
	echo '</div><!-- #heavy-analytics-comments-view -->';
	echo '</div><!-- #heavy-admin-posts-by-tag -->';
}
 
/**
 * heavyanalytics_admin_trackbacks()
 *
 */
function heavyanalytics_admin_trackbacks() {
	
	echo '<div id="heavy-analytics-trackbacks-view">';
	
	heavyanalytics_admin_page_top('Heavy Analytics Admin: Trackbacks');
    
	heavyanalytics_admin_page_bottom();
	
	echo '</div><!-- #heavy-analytics-trackbacks-view -->';	
	echo '</div><!-- #heavy-admin-posts-by-tag -->';
}
 
/**
 * heavyanalytics_admin_pingbacks()
 *
 */
function heavyanalytics_admin_pingbacks() {
	
	echo '<div id="heavy-analytics-pingbacks-view">';
	
	heavyanalytics_admin_page_top('Heavy Analytics Admin: Pingbacks');
    
	heavyanalytics_admin_page_bottom();
	
	echo '</div><!-- #heavy-analytics-pingbacks-view -->';
	echo '</div><!-- #heavy-admin-posts-by-tag -->';
}