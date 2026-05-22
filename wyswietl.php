<?php

    $db = mysqli_connect('localhost','root','','zegowskaszama');

    $kategoria = $_POST['kategoria'] ?? 'Wszystko';
    $szukanie = $_POST['szukanie'] ?? '';

    if($kategoria!="Wszystko"){
    $sql = "SELECT produkty.id, produkty.Nazwa, produkty.Cena, produkty.Kategoria, produkty.opis FROM produkty WHERE produkty.Kategoria = '$kategoria' AND produkty.Nazwa LIKE '%$szukanie%' ORDER BY produkty.Nazwa ASC";
    }else{
    $sql = "SELECT produkty.id, produkty.Nazwa, produkty.Cena, produkty.Kategoria, produkty.opis FROM produkty WHERE produkty.Nazwa LIKE '%$szukanie%' ORDER BY produkty.Nazwa ASC";
    }



            $wynik1 = mysqli_query($db, $sql);

            while($d = mysqli_fetch_array($wynik1)){
                if($d['Kategoria'] == "jedzenie"){
                    $zdjecie = "jedzenie.png";
                }else if($d['Kategoria'] == "przekąski"){
                    $zdjecie = "przekaski.png";
                }else if($d['Kategoria'] == "napoje"){
                    $zdjecie = "napoje.png";
                }
                echo '<div class="col-xl-3 col-lg-4 col-md-6 mx-sm-0 mx-3'. $d['Kategoria'].'">

                <div class="card border-0 shadow-sm rounded-4 h-100 product-card">

                    <div class="product-image text-center p-4">
                        <img src="'.$zdjecie.'" alt="" class="img-fluid">
                    </div>

                    <div class="card-body d-flex flex-column">

                        <h4 class="fw-bold">
                            '.$d['Nazwa'].'
                        </h4>

                        <p class="text-secondary flex-grow-1">
                            '.$d['opis'].'
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-3">

                            <span class="fs-3 fw-bold">
                                '.$d['Cena'].' zł
                            </span>

                            <button class="btn add-btn d-flex p-1 justify-content-center align-content-center"> + </button>
                        </div>
                    </div>
                </div>
                </div>';
            }
?>