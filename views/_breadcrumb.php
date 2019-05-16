<nav aria-label="breadcrumb">
    <ol class="breadcrumb small rounded-0 bg-transparent">
        <?php
        function breadcrumbs() {
            
            $url = str_replace(dirname($_SERVER['PHP_SELF']), "", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
            $url = preg_replace("/\d+/u", "", $url);
            
            $path = array_filter(explode('/', $url));
        
            $base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

            $keys = array_keys($path);
        
            $last = end($keys);

            if($path) {
                foreach ($path AS $x => $crumb) {
    
                    $title = ucwords(str_replace(Array('.php', '_'), Array('', ' '), $crumb));
    
                    if ($x != $last)
                        $breadcrumbs[] = '<li class="breadcrumb-item"><a href="' . BASE_URL . '/' . $crumb . '">' . $title . '</a></li>';
                    else
                        $breadcrumbs[] = "<li class=\"breadcrumb-item active\" aria-current=\"page\">$title</li>";
                }
            } else {
                $breadcrumbs[] = "<li class=\"breadcrumb-item active\" aria-current=\"page\">Home</li>";
            }
        
        
            return implode($breadcrumbs);
        }

        echo breadcrumbs();
        ?>
    </ol>
</nav>