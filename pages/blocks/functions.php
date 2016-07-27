<?


    $host = "localhost";
    $username = "maxsoulf_invuser";
    $password = "3xB}BKI^B))w";
    $database = "maxsoulf_inventory";

    //$db = mysql_connect ("localhost","inventoryuser","12345");
    //mysql_select_db(,$db);

    $db = mysqli_connect($host,$username,$password,$database) or die ('ERROR: Could not connect to database!');

	 // 0 - do not print errors | 1 - print errors
	 $debug_mode = 0;


    /**********************************************************************
    *	show_error
    ***********************************************************************/
    function show_error($message, $type = "error", $close = 0) {
        // $type = info | warning | success | error

        $string = "<div class='alert-msg $type'>";

        if ($type == "success") $string = $string."<h2><span class='icon-ok-sign icon-1x'></span> ";
        else if ($type == "error") $string = $string."<h2><span class=' icon-remove-sign red icon-1x'></span> ";
        else $string = $string."<h2><span class='icon-exclamation-sign icon-1x'></span> ";

        switch ($type) {
        case "info":
            $string = $string."Информация:</h2>";
            break;
        case "warning":
            $string = $string."Внимание!</h2>";
            break;
        case "success":
            $string = $string."Ура!</h2>";
            break;
        case "error":
            $string = $string."Ошибка!</h2>";
        }
        $string = $string."<p>$message</p>";

        if ($close) {
            $string = $string."<a class='close' href='javascript:void(0)'>X</a>";
        }

        $string = $string."</div>";
        return $string;
    }

    /**********************************************************************
     *    show_message
     **********************************************************************
     *
     * @param        $message
     * @param string $type  : info | warning | success | error
     * @param int    $close : 0 | 1
     *
     * @return string
     */
    function show_message($message, $type = "info", $close = 1) {
        $string = "<div class='alert-msg $type'>";

        if ($type == "success") $string = $string."<h2><span class='icon-ok-sign icon-1x'></span> ";
        else if ($type == "error") $string = $string."<h2><span class=' icon-remove-sign red icon-1x'></span> ";
        else $string = $string."<h2><span class='icon-exclamation-sign icon-1x'></span> ";

        switch ($type) {
            case "info":
                $string = $string."Информация:</h2>";
                break;
            case "warning":
                $string = $string."Внимание!</h2>";
                break;
            case "success":
                $string = $string."Ура!</h2>";
                break;
            case "error":
                $string = $string."Ошибка!</h2>";
        }
        $string = $string."<p>$message</p>";

        if ($close) {
            $string = $string."<a class='close' href='javascript:void(0)'>X</a>";
        }

        $string = $string."</div>";
        return $string;
    }

    /**********************************************************************
     *    show_error
     **********************************************************************
     *
     * @param string $message
     * @param string $link
     * @param string $linktitle
     */
    function show_404($message = "По какой то причине Запрошенная страница не может быть найден на нашем сервере ",
                        $link = "/inventory/index.php",
                        $linktitle = "Главная") {
    /*	if (empty($message)) {$message = "For Some Reason The Page You Requested Could Not Be Found On Our Server";}
        if (empty($link)) {$link = "index.php";}
        if (empty($linktitle)) {$linktitle = "Главная";}*/
        echo "
            <div class='full_width'>
              <section class='clear'>
                <div id='fof' class='clear'>
                  <!-- ####################################################################################################### -->
                  <div class='clear'>
                    <div class='one_half first'>
                      <h1>404</h1>
                    </div>
                    <div class='one_half'>
                      <h2>Error - Sorry Something Went Wrong !</h2>
                    </div>
                  </div>
                  <div class='divider2'></div><br><br>
                  <p class='notice'>$message</p>
                  <br><br><br>
                  <p class='clear'><a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад </a> <a class='fl_right' href='$link'>$linktitle &raquo;</a></p>
                  <!-- ####################################################################################################### -->
                </div>

              </section>
            </div>
            ";
    }

    /**********************************************************************
    *	normalize_date
    ***********************************************************************/
    function get_year($date) {
        return substr($date,0,4);
    }
    function get_month($date) {
        return substr($date,5,2);
    }
    function get_day($date) {
        return substr($date,8,2);
    }
    function normalize_date($date) {
    //	1234567890
    //	YYYY-mm-dd
        $yyyy 	= get_year($date);
        $mm 	= get_month($date);
        $dd 	= get_day($date);

        $newDate = $dd."/".$mm."/".$yyyy;
        return $newDate;
    }

    /**********************************************************************
     *	decimal_format
    ***********************************************************************/
    function decimal_format($number) {
        if($number-intval($number)==0)  return intval($number);
        else return $number;

    }

    /**********************************************************************
    *	make_link
    ***********************************************************************/
    function make_link($page, $id, $text) {
        return "<a href='".$page.$id."'>".$text."</a>";
    }


    include "functions/location.php";

    include "functions/categories.php";

    include "functions/products.php";

    include "functions/location_products.php";

    include "functions/transactions.php";

    include "functions/comments.php";


?>
        
        