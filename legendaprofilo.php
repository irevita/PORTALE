<?php

    include "connessione.php";
    global $name;
    $query_mostraProfilo = mysqli_query($connessione, "SELECT Nick, Nazione, DatadiNascita, Email FROM Utenti WHERE Nick = $name" );

?>  

<ul>
    <li>
        <img src="iconaut.png" class="w3-circle" style="height:106px;width:106px" alt="Avatar">
    </li>
    <?php 
            while($row = mysqli_fetch_array($query_mostraProfilo)){?>
    <li> <i class='fa fa-angellist'> </i> <?php echo $row["Nick"];?> </li>
    <li> <i class='fa fa-home fa-fw w3-margin-right w3-text-theme'> </i> <?php echo $row["Nazione"];?> </li>
    <li> <i class='fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme'> </i> <?php echo $row["DatadiNascita"];?> </li>
    <li> <i class='fa fa-paper-plane-o'> </i> <?php echo $row["Email"];?> </li>
    <?php }?>
</ul>


<div class="w3-col m3">
        <h4 class="w3-marginLeft">Il mio profilo</h4>
        <ul>
            <li>
                <img src="iconaut.png" class="w3-circle" style="height:106px;width:106px" alt="Avatar">
            </li>
            <?php while($row=mysqli_fetch_array($query_mostraProfilo)){?>
            <li> <i class='fa fa-angellist'> </i> <?php echo $row["Nick"];?> </li>
            <li> <i class='fa fa-home fa-fw w3-margin-right w3-text-theme'> </i> <?php echo $row["Nazione"];?> </li>
            <li> <i class='fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme'> </i>
                <?php echo $row["DatadiNascita"];?>
            </li>
            <li> <i class='fa fa-paper-plane-o'> </i> <?php echo $row["Email"];?> </li>
            <?php  }   ?>
        </ul>
    </div>


    il mio profilo query aggiornat
    <div class="w3-col m3">
        <h4 class="w3-marginLeft">Il mio profilo</h4>
        <ul>
            <li>
                <img src="iconaut.png" class="w3-circle" style="height:106px;width:106px" alt="Avatar">
            </li>
            <?php while($row=mysqli_fetch_array($query_mostraProfilo)){?>
            <li> <i class='fa fa-angellist'> </i> <?php echo $row["Nick"];?> </li>
            <li> <i class='fa fa-home fa-fw w3-margin-right w3-text-theme'> </i> <?php echo $row["Nazione"];?> </li>
            <li> <i class='fa fa-birthday-cake fa-fw w3-margin-right w3-text-theme'> </i>
                <?php echo $row["DatadiNascita"];?>
            </li>
            <li> <i class='fa fa-paper-plane-o'> </i> <?php echo $row["Email"];?> </li>
            <?php  }   ?>
        </ul>
    </div>

<!-- i miei blog  -->

 <?php if (isset($_GET["blog"])) {
                    
                    $nomeblog = $_GET["blog"]; ?>
                    <div class="contenitori">
                    <?php while($row = mysqli_fetch_array($query_articolimieiblog)) { ?> 
                    <article>
                        <h3><?php echo $row["Titolo"];?></h3>
                        <p><?php echo $row["Data"];?></p>
                        <p><?php echo $row["TESTO"];?></p>
                        <p><?php echo $row["Categoria"];?></p>
                    </article>
                    <?php } ?>
                    </div> 
            <?php } ?>
            
            
            <div id="mieiblog" class="contenitori">
                <h3>I miei blog: </h3>
                <ul>
                <?php while($row = mysqli_fetch_array($query_mieiblog)) { ?>
                <li>
                    <!-- assegna ad href il link col nome blog corrente -->
                    <?php echo '<a href="area_riservata.php?blog='.$row["NomeBlog"].'">'.$row["NomeBlog"]."</a>"; ?>
                    <p><?php echo $row["Descrizione"];?></p>
                </li>
                <?php } ?> 
                </ul>
            </div>