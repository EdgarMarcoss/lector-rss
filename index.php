<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Lector de rss usando PHP</title>
        <link rel="stylesheet" href="css/styles.css">
    </head>
    <body>
        <!--formulario para añadir la URL a leer-->
        <div class="content">
            <form method="post" action="">
                <input type="text" name="feedurl" placeholder="Escribe una URL del FEED">&nbsp;<input type="submit" value="Enviar" name="submit">
            </form>
    <?php
        //URL a leer por defecto
        $url = "https://e00-marca.uecdn.es/rss/futbol/america.xml";
        if(isset($_POST['submit'])){
            if($_POST['feedurl'] != ''){
                $url = $_POST['feedurl'];
            }
        }
        $invalidurl = false;
        //Comprobamos si la URL es correcta. 
        if(@simplexml_load_file($url)){
            $feeds = simplexml_load_file($url);
        }else{
            $invalidurl = true;
            echo "<h2>URL de feed RSS incorrecto.</h2>";
            echo "<h3>No te olvides de incluir el protocolo http(s)</h3>";
        }
        $i=0;
        //Comprobamos si la URL está vacía. Continúa si no está vacía. De lo contrario pasa al else.
        if(!empty($feeds)){
            $site = $feeds->channel->title;
            $sitelink = $feeds->channel->link;
            //Título de la página
            echo "<h1>".$site."</h1>";
            foreach ($feeds->channel->item as $item) {
                //Datos obtenidos de la noticia
                $title = $item->title;
                $link = $item->link;
                $description = $item->description;
                $postDate = $item->pubDate;
                $pubDate = date('D, d M Y',strtotime($postDate));
                // 5 es el número de noticias a mostrar
                if($i>=5) break;
        ?>
                <!--contruimos cada una de las noticias-->
                <div class="post">
                    <div class="post-head">
                        <!--Título de la noticia-->
                        <h2><a class="feed_title" href="<?php echo $link; ?>"><?php echo $title; ?></a></h2>
                        <span><?php echo $pubDate; ?></span> <!--Fecha de la publicación-->
                    </div>
                    <!-- Cuerpo de la noticia-->
                    <div class="post-content">
                        <?php echo implode(' ', array_slice(explode(' ', $description), 0, 20)) . "..."; ?> <a href="<?php echo $link; ?>">Leer más</a> <!-- botón leer más. Con enlace a la noticia-->
                    </div>
                </div>
                <?php
                $i++;
            }
        }else{
            //Error que se muestra si no hay nada que mostrar
            if(!$invalidurl){
                echo "<h2>No se encontró nada que mostrar</h2>";
            }
        }
    ?>
        </div>
    </body>
</html>