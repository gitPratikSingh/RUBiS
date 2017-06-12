<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <body>
        <?php
        $scriptName = "BrowseCategories.php";
        include("PHPprinter.php");
        include("DBQueries.php");
        $startTime = getMicroTime();

        $DBQueries = new DBQueries();

        $region = $_POST['region'];
        if ($region == null)
            $region = $_GET['region'];

        $username = $_POST['nickname'];
        if ($username == null)
            $username = $_GET['nickname'];

        $password = $_POST['password'];
        if ($password == null)
            $password = $_GET['password'];

        $userId = -1;
        if (($username != null && $username != "") || ($password != null && $password != "")) { // Authenticate the user
            $userId = $DBQueries->user_authenticate($username, $password, $link);
            if ($userId == -1) {
                printError($scriptName, $startTime, "Authentication", "You don't have an account on RUBiS!<br>You have to register first.<br>\n");
                exit();
            }
        }

        printHTMLheader("RUBiS available categories");

        $result = $DBQueries->selectFrom("categories");
        if ($DBQueries->selectCountFrom("categories") == 0)
            print("<h2>Sorry, but there is no category available at this time. Database table is empty</h2><br>\n");
        else
            print("<h2>Currently available categories</h2><br>\n");

        foreach($result as $row){
            if ($region != NULL)
                print("<a href=\"/PHP/SearchItemsByRegion.php?category=" . $row["id"] . "&categoryName=" . urlencode($row["name"]) . "&region=$region\">" . $row["name"] . "</a><br>\n");
            else if ($userId != -1)
                print("<a href=\"/PHP/SellItemForm.php?category=" . $row["id"] . "&user=$userId\">" . $row["name"] . "</a><br>\n");
            else
                print("<a href=\"/PHP/SearchItemsByCategory.php?category=" . $row["id"] . "&categoryName=" . urlencode($row["name"]) . "\">" . $row["name"] . "</a><br>\n");
        }
        $DBQueries = null;
        printHTMLfooter($scriptName, $startTime);
        ?>
    </body>
</html>
