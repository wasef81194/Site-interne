<?php
namespace App\Controller;
use App\Entity\Client;
use App\Entity\Appareil;
use App\Form\FormClientType;
use App\Form\FormAppareilType;

use App\Entity\Editeur;
use App\Form\FormDepot;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
Use Symfony\Component\Form\Extension\Core\Type\SubmitType;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use App\Repository\EtatRepository;
use App\Repository\UserRepository;

//pdf
//Use Dompdf\Dompdf;
//use Dompdf\Options;
use Fpdf\Fpdf;
//mail
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class DepotController extends AbstractController
{
    
    /**
    * @Route("/", name="formulaire_de_depot",methods={"GET","POST"})
    */
    
    public function depot(Request $request,MailerInterface $mailer,EtatRepository $etatRepository, UserRepository $userRepository){
        
       $client = new Client();
       $appareil = new Appareil();
       $form = $this->createForm(FormDepot::class, ['client' => $client, 'appareil' => $appareil]);
        //$form = $this->createForm(FormClientType::class,$client);
        $editeur = new Editeur();
        $form -> handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $appareil->setClient($client);
                $data = $form->getData();
                $client->setDate(new \DateTime());
                $client->setNom(strtoupper($client->getNom()));
                $client->setPrenom(ucwords($client->getPrenom()));
                $client->setVille(strtoupper($client->getVille()));
                $appareil->setMarque(strtoupper($appareil->getMarque()));
                $appareil->setModele(strtoupper($appareil->getModele()));
                $appareil->setNs(strtoupper($appareil->getNs()));
                $editeur->setEtat($etatRepository->findEtatWhereIsNull('')); 
                $editeur->setUser($userRepository->findUserWhereIsNull(''));
                $editeur->setDate(new \DateTime());
                $appareil->setEditeur($editeur);

                $pdf = new \FPDF();
                //$pdf->AddPage();
                $y = $pdf->getY();
                $x = $pdf->getX();
                $x +=10;
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->SetFont('Times','',12);
                $pdf->Ln(38);
                $pdf->SetLeftMargin($x+15);
                $pdf->Image('./images/pdf.jpg',0,6,200);
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(17,10,utf8_decode('Re??u le : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(90,10,date('d-m-Y H:i:s'),'');
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(28,10,'Date de retour : ','');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(0,10,'.......................',0,4);
                $pdf->Ln(3);
                $x +=15;
                $pdf->SetLeftMargin($x);
                //Information du client 
                $pdf->SetFont('Arial','B',13);
                $pdf->Cell(0,10,'Information de contact',0,1);
                //Nom 
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(12,10,utf8_decode('Nom : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(80,10,$client->getNom(),'');
                //Prenom
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(17,10,utf8_decode('Pr??nom : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(78,10,$client->getPrenom(),0,1);
                //Email
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(13,10,utf8_decode('Email : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(78,10,$client->getMail(),'');
                //T??l??phone
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(21,10,utf8_decode('T??l??phone : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(78,10,$client->getTel(),0,1);
                //Rue
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(11,10,utf8_decode('Rue : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(81,10,$client->getRue(),'');
                //Ville
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(11,10,utf8_decode('Ville : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(80,10,$client->getVille(),0,1);
                // Code Postal
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(24,10,'Code Postal : ','');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(80,10,$client->getCp(),0,1);
                $pdf->Ln(20);
                //appareil
                $pdf->SetLeftMargin(90);
                $pdf->Cell(0,10,$appareil->getMarque(),0,1);
                $pdf->Cell(0,10,$appareil->getModele(),0,1);
                $pdf->SetLeftMargin(20);
                $pdf->Cell(0,10,$appareil->getNs(),0,1);
                $pdf->Ln(10);
                $pdf->Cell(0,8,$appareil->getMdp(),'');
                //-------------------------------------------------------------------------------Seconde Page------------------------------------------------------------------
                $pdf->AddPage(); //saut de page 
                $pdf->SetFont('Arial','B',18);
                $pdf->SetLeftMargin(35);
                $pdf->Ln(10);
                $pdf->Cell(10,10,'Condition de prise en charge chez AZERTY','');
                $pdf->SetLeftMargin($x);
                $pdf->Ln(30);
                //------------------------------------------------------------------------------gauche
                //1 paragraphe
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(50,10,'Prise en charge');
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('Nous sommes d??sol??s que votre appareil ne fonctionne pas correctement. Chez AZERTY Solution Informatique, le devis est gratuit seulement si votre appareil est r??par?? dans nos locaux. Dans le cas contraire le devis vous sera factur?? ?? 30 euros.'),0,1);
                $pdf->Ln(3);
                $pdf->MultiCell(75,3,utf8_decode('Pendant la maintenance de votre appareil :'),0,1);
                $pdf->MultiCell(75,3,utf8_decode('- Lors de la sauvegarde de vos donn??es, AZERTY Solution Informatique d??cline toute responsabilit?? en cas de perte de donn??es.'),0,1);
                $pdf->MultiCell(75,3,utf8_decode('- Il est possible qu\'AZERTY d??sactive le mot de passe de votre session ou le remplace.'));
                $pdf->MultiCell(75,3,utf8_decode('- En cas ??ch??ant, il est possible de d??sactiver les logiciels de s??curit?? tiers.'));
                $pdf->MultiCell(75,3, utf8_decode('- Si la s??curit?? de vos donn??es vous pr??occupe, faites une sauvegarde de vos donn??es sur un disque externe avant qu\'il soit pris en charge par AZERTY.'));
                //2 paragraphe
                $pdf->Ln(3);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(60,10,'Restauration du logiciel');
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('Lorsque nous restaurons votre syst??me d\'exploitation, nous installons la derni??re mise ?? jour de votre version de MacOS, Windows ou Linux. Si nous ne pouvons pas d??terminer la version sp??cifique de votre syst??me d\'exploitation, nous restaurons la derni??re mise ?? jour install?? ?? l\'origine. Il vous incombe de sauvegarder tous les programmes, logiciels et donn??es existants, ainsi que d\'effacer toutes les donn??es existantes avant toute r??paration en cas de pertes de donn??es. AZERTY Solution Informatique ne peut ??tre tenue responsable de la perte, de la r??cup??ration ou de la mise en p??ril des donn??es ou des programmes, ni de l\'impossibilit?? d\'utiliser le mat??riel du fait des r??parations effectu??es. Vous pourrez utiliser votre sauvegarde pour r??installer vos donn??es ou application. V??rifier si des mises ?? jour suppl??mentaires sont disponibles.'),0,1);
                //3  paragarphe
                $pdf->Ln(3);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(100,10,utf8_decode('D??lai de r??paration'));
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('AZERTY Solution Informatique propose une r??paration le jour m??me pour certain type de r??paration, par exemple s\'il faut remplacer une pi??ce disponible d??j?? dans nos locaux. Si les techniciens estiment une dur??e plus longue que 48h, ils vous tiendront inform??s du d??lai de r??paration.'),0,1);
                // 4 paragraphe
                $pdf->Ln(3);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(100,10,utf8_decode('Type d\'appareil'));
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('Nous prenons en charge les PC portables, les PC de bureau ainsi que les imprimantes. De nombreux utilisateurs pensent que leur PC est trop vieux pour ??tre r??par??. Ce constat est relatif. En r??alit??, l\'??ge de votre ordinateur compte moins que la qualit?? du processeur et des autres composants install??s. Il suffit souvent de proc??der ?? un bon nettoyage et ?? une optimisation de votre ordinateur pour lui rendre sa performance initiale. Dans les cas les plus s??v??res, une r??initialisation de votre PC peut s\'av??rer n??cessaire. Une maintenance d\'ordinateur reste cependant largement plus rentable qu\'un achat d\'un ordinateur neuf de qualit??.'));
                //------------------------------------------------------------------------------Droite
                //1 paragraphe
                $pdf->SetY(51);
                $pdf->SetLeftMargin($x+90);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(50,10,'Devis et Garantie');
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('Les tarifs varient pour les diff??rents services propos??s par AZERTY. Pour plus d\'information contactez-nous par courriel ?? l\'adresse contact@azertyfrance.fr, par t??l??phone au 01 34 15 52 32, ou sur demande par courrier postal ?? l\'adresse suivante: 38 Rue de la Station Interphone 8, 95130 Franconville. Les tarifs, exprim??s en EUR toutes taxes comprises, sont syst??matiquement communiqu??s au client et valid??s d\'un commun accord entre le client et AZERTY Solution Informatique avant toute intervention.'),0,1);
                $pdf->Ln(3);
                $pdf->MultiCell(75,3,utf8_decode('Dans l\'ensemble des cas d\'exclusion de garantie, AZERTY Solutions Informatique adresse au client le devis correspondant ?? la remise en ??tat du produit. Le client peut accepter ou refuser le devis. Si le client accepte le devis, il doit n??cessairement r??pondre par mail \'bon pour accord\' ou r??pondre via un lien. A r??ception, AZERTY Solutions Informatiques effectuera les r??parations conform??ment aux devis valid??s. Une fois r??par?? et pr??t, AZERTY solutions informatiques contacte le client pour venir r??cup??rer son bien. Sans aucune r??ponse de la part du client dans un d??lais de trois mois et suite ?? deux relances nous enverrons pour recyclage le bien. Le r??glement par le client b??n??ficiaire d\'une prestation chez AZERTY est fait en globalit?? imm??diatement apr??s la fin de la prestation directement au technicien par l\'un des moyens de paiement accept?? par AZERTY Solutions Informatique : carte bancaire et esp??ces. Une facture (??lectronique ou sur support papier) sera ensuite adress??e au client, dans un d??lai de sept jours. Celle-ci stipulera la nature et la dur??e de l\'intervention, ainsi que le montant ?? r??gler en fonction du taux horaire en vigueur au moment de l\'intervention. La garantie AZERTY couvre les d??fauts des pi??ces remplac??es lors de la r??paration. Les techniciens qui y travaillent vous proposent un service de qualit??, les r??parations sont effectu??es avec des pi??ces neuve ou reconditionn??es : neuve, elle est g??n??ralement garantie 1 ou 2 ans par le fournisseur. Reconditionn??e, la garantie varie entre 1 ?? 6 mois selon le fournisseur. AZERTY s\'engage ?? r??parer votre appareil en cas de panne pendant la p??riode couverte par la garantie, toutefois la garantie couvre uniquement les pi??ces et non la main-d\'oeuvre.'),0,1);
                //2 paragraphe
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(50,10,utf8_decode('Donn??es personnelles'));
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('AZERTY Solution Informatique informe le client que certaines de ses donn??es personnelles sont enregistr??es ?? des fins de gestion de la relation client, notamment dans le cadre de la communication d\'offres commerciales ??mises par AZERTY Solutions Informatiques. Ces informations sont renseign??es lors du saisie du formulaire de prise en charge par le client. AZERTY Solutions Informatiques s\'engage ?? respecter la plus stricte confidentialit?? concernant les donn??es personnelles du client et ?? ne pas divulguer, sous n\'importe quel pr??texte que ce soit, les informations auxquelles elle a acc??s au cours de ses prestations.'),0,1);
               
                $pdf->Ln();
                $pdfFilepath = '../public/pdf_clients/AZERTY-'.$client->getNom().'-'.$client->getPrenom().'-'.date('Y-m-d-H-i-s').'.pdf';
                $pdf->Output( $pdfFilepath ,'F');
                
               
                //Envoie un mail
               $data = (new TemplatedEmail())
               ->from((new Address('noreply@azertypro.fr','AZERTY Solutions Informatiques')))
               ->to(new Address($client->getMail()))
               ->bcc(new Address('contact@azertyfrance.fr'))
               ->cc('noreplyazertyfrance@gmail.com','contact@azertyfrance.fr')
               ->embedFromPath('../public/images/mail/whatsapp.png', 'whatsapp')
               ->embedFromPath('../public/images/mail/location.png', 'location')
               ->embedFromPath('../public/images/mail/phone.png', 'phone')
               //->bcc('bcc@example.com')
               //->priority(Email::PRIORITY_HIGH)
               ->replyTo('contact@azertyfrance.fr')
               ->subject('D??pot chez AZERTY Solutions Informatiques')
               ->htmlTemplate('emails/mailDepot.html.twig')
               ->context([
                    'date' => $client->getDate(),
                   'personne' => $client->getPersonne(),
                   'nom' => $client->getNom(),
                   'prenom' => $client->getPrenom(),
                   'mail' => $client->getMail(),
                   'tel' => $client->getTel(),
                   'rue' => $client->getRue(),
                   'cp' => $client->getCp(),
                   'ville' => $client->getVille(),

                   'marque' => $appareil->getMarque(),
                   'modele' => $appareil->getModele(),
                   'ns' => $appareil->getNs(),
               ])
               // Optionally add any attachments
               ->attachFromPath($pdfFilepath)
           ;
           $mailer->send($data);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($client);
                $entityManager->persist($appareil);
               
                $entityManager->persist($editeur);
                $entityManager->flush(); 
                $this->addFlash('message', 'Votre appareil ?? bien ??t?? deposer chez AZERTY Solutions Informatiques, nous vous tiendrons au courant des point d\'avencement de votre appareil.');
            }
            return $this->render('depot/index.html.twig', [ 'client'=>$client, 'appareil'=>$appareil,'form' => $form->createView()]);
        }

        /**
        * @Route("/condition", name="condition", methods={"GET"})
        */
    
        public function condition(){
            return $this->render('depot/condition.html.twig', []);
        }
   
}