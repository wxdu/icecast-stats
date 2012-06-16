<?php include('includes/bootstrap.php'); ?>
<html>
    <head>
        <title>Listener Hours - <?php echo PROJECT_NAME; ?></title>
        <style>
            body{
                font-family:arial;
            }
        </style>
    </head>
    <body>

        <?php
        if (!isset($_GET['start'])) {
            $start = $db->first_start();
        } else {
            $start = $_GET['start'];
        }
        if (!isset($_GET['end'])) {
            $end = date("Y-m-d H:i:s");
        } else {
            $end = $_GET['end'];
        }

        $total = 0;
        $listeners = 0;
        $result = $db->getCurrent();
        foreach ($result as $row) {
            $temp = time() - strtotime($row['start']);
            $total = $total + $temp;
            $listeners++;
        }

        $current = $total;
        unset($total);
        ?>

        <h1>Total Listener Hours</h1>

        <form action="" method="GET"><br />
            Format is: YYYY-MM-DD HH:MM:SS <br />
            You don't have to supply all the date, so for example:<br />
            2009 will work, as will 2009-09 and 2009-01-08 and 2009-01-09 08<br /><br />
            <table>
                <tr>
                    <td>Start:</td><td><input type="text" name="start" value="<?php echo $start ?>"></td>
                </tr>
                <tr>
                    <td>End:</td><td><input type="text" name="end" value="<?php echo $end; ?>"></td>
                </tr>
                <tr>
                    <td></td><td><input type="submit" value="get"></td>
                </tr>
            </table>
        </form>

        <?php if ((isset($_GET['start'])) OR (isset($_GET['end']))) : ?>
            <a href="?">Go to All Time stats</a>
        <?php endif; ?>

        <br />

        <?php $result = $db->getStatsByRange($start, $end); ?>

        <br /><b>Current Listeners:</b> <?php echo $listeners; ?><br />
        <b>Current Listener Duration (so far):</b> <?php echo strTime($current) ?><br />
        <br /><b>Total Listener Duration:</b>
        <?php
        $hours = ((($row['duration'] + $current) / 60) / 60);
        $secs = ($row['duration'] + $current) - ((floor($hours) * 60) * 60);
        $mins = $secs / 60;
        echo strTime($row['duration'] + $current);
        ?>

        <br /><b>AKA:</b> <?php echo floor($hours) ?> hours and <?php echo round($mins) ?> minutes<br />
        <br /><b>Average Listener Duration</b> <i>(Rounded - Doesn't include listeners who are still listening)</i>:
        <?php echo strTime(round($row['average'])); ?>



        <br /><b>Max Listener Sessions:</b>

        <?php echo $row['listeners']; ?>

    </body>
</html>
