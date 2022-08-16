<?php

spl_autoload_register();

use App\Parser;
use App\ParserWithoutGenerators;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $source = [];
    echo '<br /><a href="/">Home</a>';

    if ($_POST['select'] == 'with') {
        $data = new Parser($_POST['url']);
        foreach ($data->parse() as $item) {
            $source[] = $item;
        }
    }
    if ($_POST['select'] == 'without') {
        $data = new ParserWithoutGenerators($_POST['url']);
        $source = $data->parse();
    }

    echo '<pre>';
    echo print_r($source, true);
    echo '</pre>';

    echo 'Memory size was used for this script: ' . memory_get_usage() . ' Bytes';
    echo '<br /><a href="/">Home</a>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title></title>
    <meta charset="utf-8">
</head>
<body>
<form method='post'>
    Enter the RSS URL:<br />
    <input type="text" name="url" value="https://www.liga.net/tech/all/rss.xml" size="70" />
    <br />
    <br />Choose the method: <br/>
    <select name="select">
        <option value="with">With generators</option>
        <option value="without">Without generators</option>
    </select>

    <input type='submit' value='Send'/>
</form>
<?= '<br />Memory size was used for this script: ' . memory_get_usage() . ' Bytes'; ?>
</body>
</html>
