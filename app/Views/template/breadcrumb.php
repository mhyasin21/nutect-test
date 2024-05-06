<?php

if(isset($breadcrumb)){
    if(count($breadcrumb) > 0){
        ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <?php
                        foreach($breadcrumb as $brd){
                    ?>
                        <li class="breadcrumb-item <?=$brd['class']?> "><?=$brd['label']?></li>
                    <?php
                        }
                    ?>
                </ol>
            </nav>                        
        <?php
    }
}

?>