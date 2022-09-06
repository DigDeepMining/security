<?php
    include("scripts/sessions.php");
    if((isset($_SESSION['message'])) && ($_SESSION['message'] === "Authenticated"))
    {
        echo $_SESSION['message'];
        echo "<p><a href=\"scripts/doLogout.php\">Logout</a></p>";
        echo "<p><a href=\"index.php\">Home</a></p>";
        if(isset($_GET["PersonDetailSearch"]))
        {
            $surname = $_GET['surname'];                           
        }
        else
        {
            $surname = "";
        }
    ?>
        <div class="flex-container">
            <form action="admin.php" method="get">
                <input type="input" name="surname" value="" placeholder="Surame" />
                <input type="submit" value="Go" name="PersonDetailSearch" />
            </form>
            <a href="admin.php?surname=<?php echo $surname; ?>&PersonDetailSearch=Go&orderBy=houseNumName">Order by House Number or Name</a> | 
            <a href="admin.php?surname=<?php echo $surname; ?>&PersonDetailSearch=Go&orderBy=firstName">Order by First Name</a> | 
            <a href="admin.php?surname=<?php echo $surname; ?>&PersonDetailSearch=Go&orderBy=surname">Order by Surname</a>
            <table>
                <tr>
                    <th>First Name</th><th>Second Name</th><th>Surname</th><th>House Number or Name</th>
                    <th>Address One</th><th>Address Two</th><th>Address Three</th><th>Town or City</th>
                    <th>Post Code</th>
                </tr>
            <?php
                if(isset($_GET["PersonDetailSearch"]))
                {
                    include("scripts/connectDB.php");
                    $surname = $_GET['surname'];
                    //$prevPage = $_SERVER['HTTP_REFERER'];
                    if(isset($_GET['orderBy']))
                    {
                        $getOrder = $_GET['orderBy'];
                        $orderBy = "ORDER BY $getOrder";
                    }
                    else
                    {
                        $getOrder = "";
                        $orderBy = "";
                    }
                    if(isset($_GET['limit']))
                    {
                        $getLimit = $_GET['limit'];
                        $limit = "LIMIT $getLimit";
                    }
                    else
                    {
                        $limit = "LIMIT 0,3";
                    }
            
                    $sqlCount = "SELECT *  
                            FROM person, address
                            WHERE surname LIKE '%$surname%'
                            AND address.addressId = person.addressID";
                    $result = mysqli_query($conn, $sqlCount);
                    $rowcount = mysqli_num_rows($result);

                    $countSplit = round($rowcount / 4);
                    $counter = 0;
                    while($rowcount > $counter)
                    {
                        echo "<a href=\"admin.php?surname=$surname&PersonDetailSearch=Go&orderBy=$getOrder&limit=$counter,$countSplit\">".$counter.",".$countSplit."</a> | ";
                        $counter = $counter + $countSplit;
                    }

                    $sql = "SELECT firstName, middleName, surname, houseNumName, addressTwo, addressThree, townCity, county, postCode
                            FROM person, address
                            WHERE surname LIKE '%$surname%'
                            AND address.addressId = person.addressID
                            $orderBy
                            $limit";
                    $result = mysqli_query($conn, $sql);
                    while($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>".$row["firstName"]."</td><td>".$row["middleName"]."</td><td>".$row["surname"]."</td><td>".$row["houseNumName"]."</td>
                                <td>".$row["addressTwo"]."</td><td>".$row["addressThree"]."</td><td>".$row["townCity"]."</td><td>".$row["county"]."</td>
                                <td>".$row["postCode"]."</td>
                            </tr>";
                    };
                }
            ?>
            </table>
        </div>
    <?php
    }
    else
    {
        header("Location: index.php?error=notLoggedIn");
    }
?>