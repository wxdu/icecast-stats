<?php include('includes/bootstrap.php'); ?>
<html>
    <head>
        <title>Listener Stats - <?php echo PROJECT_NAME; ?></title>
        <style>
            body{
                font-family:arial;
            }
        </style>
    </head>
    <body>
        <h2>Most recent first, will only show 100 sessions.</h2>
        <?php
        $col = $db->first();

        $col = array_keys($col);
        ?>

        <table bgcolor="black">
            <tr>

                <?php foreach ($col as $key => $value) : ?>
                    <?php if (!is_numeric($value)) : ?>
                        <td bgcolor="white"><b><?php echo $value ?></b></td>';
                    <?php endif; ?>
                <?php endforeach; ?>

            </tr>

            <?php
            $result = $db->fetchLast(100);

            foreach ($result as $row) :
                ?>
                <tr>
                    <?php
                    foreach ($row as $key => $value) :
                        if (!is_numeric($key)) :
                            ?>
                            <td bgcolor="<?php ($row['duration']) ? 'white' : 'green'; ?>">
                                <?php
                                if ($key == 'duration') {
                                    if ($value == null) {
                                        echo 'So far... ';
                                        echo strTime(time() - strtotime($row['datetime_start']));
                                    } else {
                                        echo strTime($value);
                                    }
                                } else {
                                    echo $value;
                                }
                                ?>
                            </td>
                        <?php
                        endif;
                    endforeach;
                    ?>
                </tr>
            <?php endforeach; ?>
        </table>
    </body>
</html>