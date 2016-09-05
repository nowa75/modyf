<?
  function authenticate() {
    Header("WWW-Authenticate: Basic realm=\"Wymagana autoryzacja.\"");
    Header("HTTP/1.0 401 Unathorized");
    echo "<CENTER><H1>";
    echo "Odmowa dostępu do danych!\n";
    echo "<BR>";
    echo "Wprowadź poprawną nazwę użytkownika oraz hasło!\n";
    echo "</H1></CENTER>";
    exit;
  }
?>
<?
    //$connection=pg_Connect("user=postgres dbname=infotest");
    $connection=pg_Connect("host=10.215.109.10 dbname=infotest user=baza_pracownikow password=b@z@Prac");
    @$user = $_SERVER['PHP_AUTH_USER'];
    @$pw   = $_SERVER['PHP_AUTH_PW'];
    $result=pg_Exec($connection,"SELECT wlasciciel FROM Hasla WHERE wlasciciel='$user' AND wlasciciel<>'sluzba' 
                                                              AND   haslo='$pw'");
    $stan=pg_NumRows($result);							       
    //var_dump($_SERVER);
    if ($stan == 0 ) authenticate();
?>




<!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html>
<head>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
   <title>Baza pracownikow, MEN Modyfikacja pozycji.</title>
<style>


.inputbox {
	padding: 2px;
	border:solid 1px #cccccc;
	background-color: #ffffff;
}

TH { color: #606060; }


</style>


</head>
<body text="#000000" bgcolor="#FFFFFF" link="#0000EF" vlink="#51188E" alink="#FF0000">

<center>
<h3 style='color: #606060'>Modyfikacja informacji o pracowniku</h3></center>


<?if(@$_REQUEST['zatwierdz']==""){
    $sql = "SELECT * FROM prac WHERE id_prac = '" . (string)$_REQUEST['idPrac'] . "' LIMIT 1";
    //$conection = pg_connect("user=postgres dbname=infotest");
    $conection=pg_Connect("host=10.215.109.10 dbname=infotest user=baza_pracownikow password=b@z@Prac");
    $pracownicy = pg_query($conection, $sql);
    $prac = null;
    if(pg_num_rows($pracownicy) > 0) {
    	$prac = @pg_fetch_object($pracownicy);
    }
?>

<FORM ACTION="modyfikuj.php" ENCTYPE="multipart/form-data" METHOD="POST">
<INPUT TYPE="hidden" NAME="idPracH" VALUE="<?echo $_REQUEST['idPrac'];?>">
<table>
<TR>
 <TH>NRE (z kadr MEN)</TH>
 <TD><INPUT class="inputbox" TYPE="TEXT" NAME="nre" disabled="disabled" VALUE="<?if($prac){echo $prac->nre;}?>">
 </TD>
</TR>

<TR>
 <TH>Nazwisko</TH>
 <TD><INPUT class="inputbox" TYPE="TEXT" NAME="nazwisko" VALUE="<?if($prac){echo $prac->nazwisko;}?>">
 </TD>
</TR>

<TR>
 <TH>Imię</TH>
 <TD><INPUT class="inputbox"  TYPE="TEXT" NAME="imie" VALUE="<?if($prac){echo $prac->imie;}?>">
 </TD>
</TR>

<TR>
 <TH>Stanowisko</TH>
 <TD>
     <SELECT class="inputbox"  NAME="c4">
    <?
      // Wyswietl stanowisko - odpowiednik kodu
      //$conection=pg_Connect("user=postgres dbname=infotest");
      $conection=pg_Connect("host=10.215.109.10 dbname=infotest user=baza_pracownikow password=b@z@Prac");
      $stan=pg_Exec($conection,"SELECT c4,stanowisko  FROM stan WHERE c4='" . (string)$prac->c4 . "'");    
      echo "<OPTION VALUE=".pg_Result($stan,0,"c4").">".pg_Result($stan,0,"stanowisko")." (".pg_Result($stan,0,"c4").")</OPTION>";
      
      // Wyswietl wszystkie dostepne stanowiska po wcisnieciu klawisza
      $result=pg_Exec($conection,"SELECT c4,stanowisko FROM stan ORDER by stanowisko");
      for($i=0;$i<pg_NumRows($result);$i++) {
      	  echo "<OPTION VALUE=".pg_Result($result,$i,"c4").">".pg_Result($result,$i,"stanowisko")." (".pg_Result($result,$i,"c4").")</OPTION>";
      }
    ?>     
    </SELECT>
 </TD>
</TR>


<TR>
 <TH>Tytuł</TH>
 <TD><INPUT class="inputbox"  TYPE="TEXT" NAME="tytul" VALUE="<?if($prac){echo $prac->tytul;}?>">
 </TD>
</TR>

<TR>
 <TH>Pokój</TH>
 <TD><INPUT class="inputbox"  TYPE="TEXT" NAME="pokoj" VALUE="<?if($prac){echo $prac->pokoj;}?>">
 </TD>
</TR>

<TR>
 <TH>Telefon</TH>
 <TD><INPUT class="inputbox"  TYPE="TEXT" NAME="telefon_w" VALUE="<?if($prac){echo $prac->telefon_w;}?>">
 </TD>
</TR>

<TR>
 <TH>Telefon komórkowy</TH>
 <TD><INPUT class="inputbox"  TYPE="TEXT" NAME="mobile" VALUE="<?if($prac){echo $prac->mobile;}?>">
 </TD>
</TR>

<TR>
 <TH>Departament/Wydział</TH>
 <TD>
    <SELECT class="inputbox"  NAME="c3">
    <?
      //$conection=pg_Connect("user=postgres dbname=infotest");
      $conection=pg_Connect("host=10.215.109.10 dbname=infotest user=baza_pracownikow password=b@z@Prac");

      $result=pg_Exec($conection,"SELECT kod,nazw FROM DEPA WHERE kod='" . (string)$prac->c3 . "'");      
      echo "<OPTION VALUE=".pg_Result($result,0,"kod").">".pg_Result($result,0,"nazw")." (".pg_Result($result,0,"kod").")";


      $depa=pg_Exec($conection,"SELECT * FROM DEPA ORDER BY kod");
      for($i=0;$i<pg_NumRows($depa);$i++) {
        $dlugosc=pg_fieldprtlen($depa,$i,"kod");
	    if ($dlugosc==2) {
          print "<OPTION VALUE=".pg_Result($depa,$i,"kod").">".pg_Result($depa,$i,"symbol")." (".pg_Result($depa,$i,"kod").")";	
	    } else {
	      print "<OPTION VALUE=".pg_Result($depa,$i,"kod")."-->>>>>>".pg_Result($depa,$i,"nazw")." (".pg_Result($depa,$i,"kod").")";	
	    }
      }
    ?> 
    </SELECT>
 </TD>
</TR>

<TR>
 <TH>Poczta elektroniczna</TH>
 <TD><INPUT class="inputbox"  TYPE="TEXT" NAME="poczta" VALUE="<?if($prac){echo $prac->poczta;}?>">
 </TD>
</TR>

<TR>
 <TH>Bezpośredni przełożony</TH>
 <TD>
    <SELECT class="inputbox" style="width:300px;" NAME="przelozony">
        <option value="">_______</option>
        <?php 
            $sql = 'SELECT imie, nazwisko, id_prac FROM prac WHERE ';
            $sql .= 'status = 1 AND czasowy = 0 AND id_prac <> \'' . (string)$prac->id_prac. '\' ORDER BY nazwisko';
            $przelozeni = pg_query($conection, $sql);
            if(pg_num_rows($przelozeni) > 0) {
                while($przel = @pg_fetch_object($przelozeni)) {
                    $imie = trim($przel->imie);
                    $nazwisko = trim($przel->nazwisko);
                    if(!empty($imie) && !empty($nazwisko)) {
                        $html = '<option value="' . (string)$przel->id_prac . '"';
                        if($przel->id_prac == $prac->id_przelozony) {
                            $html .= ' selected="selected"';
                        }
                        $html .= '>';
                        $html .= $nazwisko . ' ' . $imie;
                        $html .= '</option>';
                        echo $html;
                    }
                }
            }
        ?>
    </SELECT>
 </TD>
</TR>

<TR>
 <TH>Na stałe/Czasowy</TH>
 <TD>
    <SELECT class="inputbox"  NAME="czasowy">
    <?
        $czasowy = (int)$prac->czasowy;
        if($czasowy == 1) {
            echo "<option value='0'>na stałe</option>";
            echo "<option value='1' selected='selected'>czasowy</option>";
        } elseif($status == 0) {
            echo "<option value='0' selected='selected'>na stałe</option>";
            echo "<option value='1'>czasowy</option>";
        } else {
            echo "<option value='0'>na stałe</option>";
            echo "<option value='1'>czasowy</option>";
        }   
    ?>
    </SELECT>
 </TD>
</TR>

<TR>
 <TH>Data rozpoczęcia pracy<br/>YYYY-MM-DD (np.: 2010-03-21)</TH>
 <TD style="vertical-align:top;">
     <INPUT class="inputbox" TYPE="text" NAME="data_rozp_pracy" VALUE="<? if($prac && (int)$prac->data_rozpoczecia_pracy > 0){echo date('Y-m-d', (int)$prac->data_rozpoczecia_pracy);}?>" maxlength="10">
 </TD>
</TR>

<TR>
 <TH>Data zakończenia pracy<br/>YYYY-MM-DD (np.: 2010-03-21)</TH>
 <TD style="vertical-align:top;">
     <INPUT class="inputbox" TYPE="text" NAME="data_zak_pracy" VALUE="<? if($prac && (int)$prac->data_zakonczenia_pracy > 0){echo date('Y-m-d', (int)$prac->data_zakonczenia_pracy);}?>" maxlength="10">
 </TD>
</TR>

<TR>
 <TH>Pracuje/Nie pracuje</TH>
 <TD>
    <SELECT class="inputbox"  NAME="status">
    <?
	    $status = (int)$prac->status;
	    if($status == 1) {
		    echo "<option value='1' selected='selected'>pracuje</option>";
		    echo "<option value='0'>nie pracuje</option>";
	    } elseif($status == 0) {
	    	echo "<option value='1'>pracuje</option>";
            echo "<option value='0' selected='selected'>nie pracuje</option>";
	    } else {
	    	echo "<option value='1'>pracuje</option>";
            echo "<option value='0'>nie pracuje</option>";
	    }	
    ?>
    </SELECT>
 </TD>
</TR>

<TR>
 <TH>Kolejność w wydziale</TH>
 <TD><INPUT class="inputbox"  TYPE="TEXT" NAME="kolejnosc" VALUE="<?if($prac){echo $prac->sortowanie;}?>" maxlength="10" >
 </TD>
</TR>

<TR>
 <TH>Zdjęcie</TH>
 <TD><INPUT TYPE="FILE" NAME="foto">
 </TD>
</TR>



<tr>
<td></select><input TYPE=SUBMIT NAME="zatwierdz" VALUE="Modyfikuj"></td>
</tr>
</table>
</form>
<?
} else {
    $wlasciciel=$user;
	$haslo=$pw;
	//$conection=pg_Connect("user=postgres dbname=infotest");
	$conection=pg_Connect("host=10.215.109.10 dbname=infotest user=baza_pracownikow password=b@z@Prac");
	
    $sql = "SELECT * FROM prac WHERE id_prac = '" . (string)$_REQUEST['idPracH'] . "' LIMIT 1";
    $pracownicy = pg_query($conection, $sql);
    $prac = null;
    if(pg_num_rows($pracownicy) > 0) {
        $prac = @pg_fetch_object($pracownicy);
    }
    
    if(!$prac) {
    	echo "<H2>Brak pracownika do modyfikacji!</H2>";
    } else {
		$result=pg_Exec($conection,"SELECT * FROM Hasla WHERE wlasciciel='$wlasciciel' AND haslo='$haslo'");
		if(pg_NumRows($result)==0) {
			echo "<H2>Złe hasło użytkownika !</H2>";
			echo "Użytkownik: $wlasciciel";
		} else {
			pg_FreeResult($result);
	        $oldoid = 0;
			if($prac->foto) {
				$oldoid = (int)$prac->foto;
			}
	  	    $fotoupd = "";
	  	    if (is_uploaded_file($_FILES['foto']['tmp_name'])) {
	  	    $name = basename($_FILES['foto']['tmp_name']);
	  	    move_uploaded_file($_FILES['foto']['tmp_name'], "thumbs/$name");
	  	    require_once('imag.php');

	  		$image = new Resize_Image;
	  		 
	  		 $image->new_width = 100;
	  		 $image->new_height = 133;
	  		  
	  		  $image->image_to_resize = "thumbs/$name"; // Full Path to the file
	  		   
	  		   $image->ratio = true; // Keep Aspect Ratio?
	  		    
	  		    // Name of the new image (optional) - If it's not set a new will be added automatically
	  		     
	  		     $image->new_image_name = $name.'up';
	  		      
	  		      /* Path where the new image should be saved. If it's not set the script will output the image without saving it */
	  		       
	  		       $image->save_folder = 'thumbs/';
	  		        
	  		        $process = $image->resize();
	  		         
	  		         if($process['result'] && $image->save_folder)
	  		         {
	  		         //echo 'The new image ('.$process['new_file_path'].') has been saved.';
	  		         }
	  		         
	  	
//	  	var_dump('thumbs/'.$name.'up.jpg');
//	  	var_dump(is_file('thumbs/'.$name.'up.jpg')); exit;
	  	    	$fp = fopen('thumbs/'.$name.'up.jpg', "r");
	  	    	pg_exec($connection,"begin");
	  	    	if ($oldoid==0) {
	  	    		$oid = pg_locreate($connection);
	  	    		$fotoupd=", foto=" . $oid;
	  	    		echo "Nowy obiekt... ";
	  	    	} else {
	  	    		$oid = $oldoid;
	  	    		echo "Modyfikacja obiektu... ";
	  	    	}
	  	    	$fd = pg_loopen ($connection, $oid, "w");
	  	    	while (!feof($fp)) {
	  	    		pg_lowrite($fd, fread($fp, 4096));
	  	    	}
	  	    	
	  	    	// czy to jest poprawne???
	  	    	pg_lowrite($fd, $fp);
	  	    	
	  	    	pg_loclose ($fd);   
	  	    	pg_exec($connection,"commit");
	  	    	fclose($fp);
	  	    	echo "Uploaded image with oid $oid";
	  	    } else {
	  	    	$oid = $prac->foto;
			    $fotoupd = "";
	            echo "Kept image with oid ( $oid )";
	  	    }
	  	    unlink('thumbs/'.$name.'up.jpg');
	  	    unlink('thumbs/'.$name);
	  	    $tsDataRozpPracy = 0;
	        if(isset($_REQUEST['data_rozp_pracy']) && !empty($_REQUEST['data_rozp_pracy'])) {
	            $dataRozpPracy = (string)$_REQUEST['data_rozp_pracy'] . ' 12:00:00';
	            $tsDataRozpPracy = strtotime($dataRozpPracy);
	            if(!$tsDataRozpPracy) {
	                $tsDataRozpPracy = 0;
	            }
	        }
	        $tsDataZakPracy = 0;
            if(isset($_REQUEST['data_zak_pracy']) && !empty($_REQUEST['data_zak_pracy'])) {
                $dataZakPracy = (string)$_REQUEST['data_zak_pracy'] . ' 12:00:00';
                $tsDataZakPracy = strtotime($dataZakPracy);
                if(!$tsDataZakPracy) {
                    $tsDataZakPracy = 0;
                }
            }
            $kolejnosc = 0;
            $pattern = '/^[0-9]{1,10}$/';
            if(preg_match($pattern, $_REQUEST['kolejnosc'])) {
            	$kolejnosc = (int)$_REQUEST['kolejnosc'];
            }
            $idHist = md5(uniqid(time(), true));
            if(!$oid) {
            	$oid = 'NULL';
            }
            $querystrHist = "INSERT INTO prac_historia (id, createdat, nazwisko, imie, tytul,
                         pokoj, telefon_w, c4, c3, poczta, foto, id_prac, status,
                         data_rozpoczecia_pracy, data_zakonczenia_pracy, sortowanie, czasowy, mobile, id_przelozony) VALUES (
                         '" . $idHist . "', " . (string)time() . ", 
                         '" . $_REQUEST['nazwisko'] . "',
                         '" . $_REQUEST['imie'] . "',
                         '" . $_REQUEST['tytul'] . "',
                         '" . $_REQUEST['pokoj'] . "',
                         '" . $_REQUEST['telefon_w'] . "',
                         '" . $_REQUEST['c4'] . "',
                         substr('" . @$_REQUEST['c3'] . "',1,3),
                         '" . $_REQUEST['poczta'] . "',
                         " . $oid . ", '" . (string)$prac->id_prac . "', " . $_REQUEST['status'] . ", " . (string)$tsDataRozpPracy . ", " . (string)$tsDataZakPracy . ", " . (string)$kolejnosc . ", " . (string)$_REQUEST['czasowy'] . ", '" . $_REQUEST['mobile'] . "', '" . $_REQUEST['przelozony'] . "')";
            $bError = false;
            pg_query($conection, 'BEGIN');
			$result = pg_query($conection,"UPDATE prac SET
			                nazwisko='" . @$_REQUEST['nazwisko'] . "',
					imie='" . @$_REQUEST['imie'] . "',
					tytul='" . @$_REQUEST['tytul'] . "',
					pokoj='" . @$_REQUEST['pokoj'] . "',
					telefon_w='" . @$_REQUEST['telefon_w'] . "',
					mobile='" . @$_REQUEST['mobile'] . "',
					id_przelozony='" . @$_REQUEST['przelozony'] . "',
					c4='" . @$_REQUEST['c4'] . "',
					c3=substr('" . @$_REQUEST['c3'] . "',1,3),
					status=" . $_REQUEST['status'] . ",
					data_rozpoczecia_pracy=" . (string)$tsDataRozpPracy . ",
					data_zakonczenia_pracy=" . (string)$tsDataZakPracy . ",
					sortowanie=" . (string)$kolejnosc . ",
					czasowy=" . (string)$_REQUEST['czasowy'] . ",
					poczta='" . @$_REQUEST['poczta'] . "'" . 
					$fotoupd .
					" WHERE id_prac = '" . (string)$prac->id_prac . "'");			
			if($result == 0) {
				$bError = true;
				echo "<H2>1-Wystąpił błąd modyfikacji.</H2>";
				pg_query($conection, 'ROLLBACK');
			}
			if(!$bError) {
				if($prac->imie != $_REQUEST['imie'] || $prac->nazwisko != $_REQUEST['nazwisko'] ||
				   $prac->tytul != $_REQUEST['tytul'] || $prac->pokoj != $_REQUEST['pokoj'] ||
				   $prac->telefon_w != $_REQUEST['telefon_w'] || $prac->poczta != $_REQUEST['poczta'] ||
				   $prac->c4 != $_REQUEST['c4'] || $prac->c3 != mb_substr($_REQUEST['c3'], 0, 3, 'UTF-8') ||
				   (int)$prac->status != (int)$_REQUEST['status'] ||
				   (int)$prac->data_rozpoczecia_pracy != $tsDataRozpPracy || (int)$prac->data_zakonczenia_pracy != $tsDataZakPracy ||
				   $prac->mobile != $_REQUEST['mobile'] || $prac->id_przelozony != $_REQUEST['przelozony']) {
				   	$resultHist = pg_query($conection, $querystrHist);
				   	if($resultHist == 0) {
				   		echo "<H2>2-Wystąpił błąd w trakcie dodawania informacji o pracowniku do historii.</H2>";
				   		$bError = true;
				   		pg_query($conection, 'ROLLBACK');
				   	}
			    }
	        }			
			
			if(!$bError) {
				pg_query($conection, 'COMMIT');
				if($prac) {
					$pocztaOld = trim((string)$prac->poczta);
					if(empty($pocztaOld) && 
					   isset($_REQUEST['poczta']) && 
					   !empty($_REQUEST['poczta'])) {
						$imie = '';
			            $nazwisko = '';
			            $poczta = '';
			            if(isset($_REQUEST['imie'])) {
			                $imie = (string)$_REQUEST['imie'];
			            }
			            if(isset($_REQUEST['nazwisko'])) {
			                $nazwisko = (string)$_REQUEST['nazwisko'];
			            }
			            if(isset($_REQUEST['poczta'])) {
			                $poczta = (string)$_REQUEST['poczta'];
			            }
			            require_once('CSendMessageMail.php');
			            $sendMessage = new CSendMessageMail();
			            $sendMessage->prepareAndSendMail($imie, $nazwisko, $poczta);
					}
				}
				echo "<H2>Informacja o pracowniku została zmodyfikowana.</H2>";
			}
		}
    }
}
?>
<hr>
<p CLASS="stopka">
<center>
[<a href="szukajz.php">szukaj</a>]&nbsp;&nbsp; 
[<a href="pokazz.php">wyświetl</a>]&nbsp;&nbsp;
[<a href="dodaj.php">dodaj</a>]&nbsp;&nbsp; </p>

<p CLASS="stopka">
<hr style="width:40%; color:#ddd; height:1px; ">
[ <a href="pokaz-depa.php">Edytuj Departamenty</a> ]&nbsp;
[ <a href="pokaz-stan.php">Edytuj Stanowiska</a> ]&nbsp;
[ <a href="exportData.php">Eksport CSV</a> ]&nbsp;
</p>

<p><i><small><font color="#3333FF">(C) Centrum Informatyczne Edukacji</font></small></i></p>

</center>
</body>
</html>
