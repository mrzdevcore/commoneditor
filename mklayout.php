
<?php

$mailbox = 'mail.editionscimg.com:993/imap/ssl/novalidate-cert';

$login = 'cpt.rendu@editionscimg.com';
$passwd = 'opencptr6204';

$mbox = imap_open ("{".$mailbox."}", $login, $passwd);

if ($mbox === FALSE) {
	 $info = FALSE;
	 die(utf8_decode('Connection failed. Check your paremeter!'));
}

echo "\nLet's go!\n";

$result = imap_search($mbox, 'UNSEEN');

foreach ($result as $mail) {

	// save text file
    echo $mail."\n";
	$bpath = './temp/'.$mail.".txt";
	$buffer = fopen($bpath, "w") or die("Unable to open file!:"." ".$bpath);
	imap_savebody( $mbox ,  $buffer , $mail , FT_UID );
	fclose($buffer);
	$status = imap_setflag_full($mbox, $mail, "\\Seen \\Flagged", ST_UID);
}
imap_close($mbox);

echo "Mail retrieved\n";
echo "\nData Extraction finished!\n";


require('./spdom/simple_html_dom.php');

system("ls ./temp/ > list.txt");
$list = fopen("list.txt","r");
if($list == Null)
{   
    echo "Ouverture de fichier list1 echouer!\n";
}

$exit = 0;
while(($content=fgets($list))!=Null)
{
	
	$nameF= trim($content);
	$nameF= explode('.',$nameF);
	$bpath = "./temp/".trim($content);	
//fetch datatable
	$table = array();
	$html = file_get_html($bpath);
	

	foreach($html->find('tr') as $row) {
		$spec= $row->find('th',0)->plaintext;
		$value = $row->find('td',0)->plaintext;
		$table[$spec] = $value;
	}
	
	//template($table);
	$technique=explode('.',$table["Technique"],-1);
	
	$table["# ID de l'examen actuel"]=str_ireplace("/","-",$table["# ID de l'examen actuel"]);
	$table["# ID de l'examen actuel"]=str_ireplace(" ","",$table["# ID de l'examen actuel"]);
	$table["# ID de l'examen actuel"]=str_ireplace("2015","",$table["# ID de l'examen actuel"]);
	$table["# ID de l'examen actuel"]=str_ireplace("2014","",$table["# ID de l'examen actuel"]);
	$table["# ID de l'examen actuel"]=str_ireplace("-","",$table["# ID de l'examen actuel"]);	
	$list_recom = str_ireplace("-"," \\\\ -",$table["Recommandations"]);

	foreach($table as $id=>$value)
	{
		$table[$id]=str_ireplace("\n","\\\\",$table[$id]);
		$table[$id]=str_ireplace("\r\n","\\\\",$table[$id]);
	}

$texfile="
\\documentclass{templates}

\\makeatletter
\\def\\@maketitle{%
  \\newpage
  \\null
 % \\vskip 0.2em%
  \\begin{center}%
  \\let \\footnote \\thanks
	{ %\\Huge \\@title
		\\vskip -4em
 		\\includegraphics[width=1\\linewidth,height=0.08\\linewidth]{Bandeau-lnsm} \\\\
 		\\raggedleft \\footnotesize Telephone labo:+261 (0) 20 24 513 88 \\\\ Email labo: lnsm@neuromg.org \\\\
    }%
   \\end{center}%
   
	\\begin{flushleft}
	{ \\vskip -2em \\par \\footnotesize
		  \\textbf{Nom et prénoms:} 	".$table["Nom et prénoms du patient"]."  \\\\
		   \\textbf{Sexe:} ".$table["Sexe du patient"]."				\\\\
		   \\textbf{Date de naissance:} ".$table["Date de naissance du patient"]."		\\\\
		   \\textbf{Domicile:} ".$table["Domicile du patient"]."	\\\\
		   \\textbf{Numéro ID de l'examen et date:} ".$table["# ID de l'examen actuel"]."-".$table["Date de l'examen"]." \\\\
	  }
	  \\end{flushleft}
	\\begin{flushright}
	{\\vskip -2em
	 	\\footnotesize \\textbf{Médecin examinateur:}".$table["Médecin examinateur"]."	\\\\ \\vskip 0.5em 
		\\textbf{Nom et adresse du médecin demandeur:}".$table["Nom et adresse du médecin demandeur"]."\\\\
   		\\vskip 0.5em \\hrule height 2pt\\hfill \\vskip 0.5em
	}
	\\end{flushright}
}
\\makeatother

%\\onehalfspacing


%\\title{Titre de l'article}

\\begin{document}
\\maketitle
\\begin{Clinique}
\\begin{center}

{\\small \\vskip -1em \\textbf{Bilan neurologique electro-clinique}}
\\end{center}

\\section*{\\vskip -2em  Motif}
".$table["Motif de l'examen actuel"]."
\\section*{Bilan neurologique}
\\subsection*{Histoire de la maladie}
\\begin{itemize}
\\item [\\textbf{Histoire initiale de la maladie:}]".$table["Histoire initiale de la maladie"]."
\\item [\\textbf{Conclusion et recommandations du dernier examen:}]	".$table["Conclusion et recommandations du dernier examen"]."
\\item [\\textbf{Evolution depuis la dernière visite:}]".$table["Evolution depuis la dernière visite"]."
\\item[\\textbf{Traitement actuel et tolérance:}]".$table["Traitement actuel et tolérance"]."
\\end{itemize}

\\subsection*{Antecedents}
\\begin{itemize}
\\item[\\textbf{Antécédents personnels:}]".$table["Antécédents personnels"]."
\\item[\\textbf{Antécédents familiaux:}]".$table["Antécédents familiaux"]."
\\item[\\textbf{Traitements antérieurs:}]".$table["Traitements antérieurs"]."
\\end{itemize}
\\subsection*{Examen physique}
\\begin{itemize}
\\item[\\textbf{Plaintes et comportement:}]".$table["Plaintes et modifications de comportement"]."
\\item[\\textbf{Etat général:}]".$table["Etat général"]."
\\item[\\textbf{Etat neurologique:}]".$table["Etat neurologique"]."
\\item[\\textbf{Reste de l'examen:}]".$table["Reste de l'examen"]."
\\item[\\textbf{Bilan biologique et bactériologique:}]".$table["Bilan biologique et bactériologique"]."
\\end{itemize}
 
\\subsection*{Examens complémentaires}
\\begin{itemize}
\\item[\\textbf{Explorations fonctionnelles:}]".$table["Explorations fonctionnelles"]."
\\item[\\textbf{Bilan morphologique:}]".$table["Bilan morphologique"]."
\\item[\\textbf{Bilan histologique et anatomo-pathologique:}]".$table["Bilan histologique et anatomo-pathologique"]."
\\end{itemize}
\subsection*{Synthèse clinique}
".$table["Synthèse clinique"]."

\\section*{Examen électro-encéphalographique}
\\begin{itemize}
\\item[\\textbf{Technique:}]Appareil numérique, vitesse à $15\\ mm/s$ et amplitude $70\\ \mu V/cm$. ".$technique[1]."
\\item[\\textbf{Conditions d'examen:}]".$table["Etat de vigilance du malade durant l'EEG"]."
\\item[\\textbf{Tests de réactivité:}]".$table["Synthèse clinique"]."
\\item[\\textbf{Méthodes d'activation:}]".$table["Epreuves d'activation"]."
\\item[\\textbf{Artefacts:}] ".$table["Artefacts"]."
\\item[\\textbf{Activité de base:}]".$table["Activité de base"]."
\\item[\\textbf{Activité au repos:}]".$table["Activité au repos"]."
\\item[\\textbf{Activité après activation:}]".$table["Activité après épreuves  d'activation"]."
\\item[\\textbf{Autres anomalies rythmiques ou paroxystiques physiologiques:}]".$table["Autres activités rythmiques ou paroxystiques physiologiques"]."
\\item[\\textbf{Interprétation du tracé:}]".$table["Interprétation du tracé"]."
\\end{itemize}
\\section*{ Conclusion et recommandations}
\\begin{itemize}
\\item [\\textbf{Conclusion:}]".$table["Conclusion du bilan clinique et électrique"]."
\\item[\\textbf{Recommandations:}] ".$table["Recommandations"]."
\\end{itemize}
\\end{Clinique}
\\closing
\\end{document}
";
	if(strlen($table["Conclusion du bilan clinique et électrique"]) > 8 && 
			strlen($table["Recommandations"]) > 8) {
		// save tex file
		$name= "out"."_".$table["# ID de l'examen actuel"]."_".$nameF[0];
		$outpath="./out/".$name.".tex";
		$output = fopen($outpath, "w") or die("Unable to open file:"." ".$outpath);
		fwrite( $output , utf8_decode($texfile) );
		fclose($output);
		// end save tex file
	
		// run command pdflatex
		$command = 'pdflatex -output-directory ./pdf'.'  .'.$outpath.' ';
		system($command);
		// end command pdflatex
		echo "\nMise en page termine\n";
	}
	else {
		$exit = 1;		
		echo '<br/>Rapport incomptet <br/>';
	}
}

echo "Step 2 finished\n";
$command = 'sudo echo y | rm ./pdf/*.log;sudo echo y | rm ./pdf/*.aux';
system($command);

fclose($list);

// send ouput file
require './PHPMailer/PHPMailerAutoload.php';

system("ls ./pdf/ > list.txt");
$list = fopen("list.txt","r");

if($list == Null)
{   
    echo "Ouverture de fichier list2 echouer!<br/>";
}
while(($content=fgets($list))!=Null)
{
	
	$nameF= trim($content);
	$nameF= explode('.',$nameF);
	$id = explode('_',$nameF[0]);
	$bpath = "./pdf/".trim($content);

	$mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'mail.editionscimg.com';  // Specify main and backup server
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'cpt.rendu@editionscimg.com';                            // SMTP username
	$mail->Password = 'opencptr6204';                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 587; 

	$mail->From = 'cpt.rendu@editionscimg.com';
	$mail->FromName = 'CommonEditor';
	//$mail->addAddress('herrio@coroom.org', 'Rivo');  // Add a recipient
	$mail->addAddress('commoneditor@editionscimg.com', 'Compte Rendu');  // Add a recipient
	$mail->addAddress('lneurosciences@yahoo.com', 'Compte Rendu');  // Add a recipient
	//$mail->addAddress('lnsm@neuromg.org', 'lnsm');  // Add a recipient
	//$mail->addReplyTo('info@example.com', 'Information');
	//$mail->addCC('rivoherson@gmail.com');
	//$mail->addBCC('bcc@example.com');

	//$mail->addAttachment('/var/www/automate_editor/pdf/out_277_322.pdf');         // Add attachments
	$mail->addAttachment($bpath, $id[1].'.pdf');    // Optional name                               
	$mail->isHTML(true);

	$mail->Subject = 'Fiche '.$id[1];
	$mail->Body ='<html>';
	$mail->Body .='<head><meta charset="ISO-8859-1"></head><body>';
	$mail->Body    .= 'Bonjour,<br/><br/> Cher collaborateur, <br/>
			La mise en page du compte rendu de patient <strong>'.$id[1].'</strong> est terminee avec succes.<br/>';
	$mail->Body .="Si vous avez de probleme, n'hesite pas de nous contacter.<br/>Merci de votre collaboration.<br/><br/>ps: ".$id[1].".pdf";
	$mail->Body .='<p>Ne repond pas sur cette adresse car ceci est un message automatique.</p>';
	$mail->Body .='</body></html>';
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
	//$mail->Body = utf8_encode($mail->Body);
	if(!$mail->send()) {
	   echo 'Message could not be sent.';
	   echo 'Mailer Error: ' . $mail->ErrorInfo.'<br/>';
	   $exit = 1;
	}
	// end send output fil

	echo "Message has been sent\n";	

}
system("./clear.sh");
echo "Step 3 finished\n";
?>


