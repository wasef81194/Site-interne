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
                $pdf->Cell(17,10,utf8_decode('Reçu le : '),'');
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
                $pdf->Cell(17,10,utf8_decode('Prénom : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(78,10,$client->getPrenom(),0,1);
                //Email
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(13,10,utf8_decode('Email : '),'');
                $pdf->SetFont('Times','',12);
                $pdf->Cell(78,10,$client->getMail(),'');
                //Téléphone
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(21,10,utf8_decode('Téléphone : '),'');
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
                $pdf->MultiCell(75,3,utf8_decode('Nous sommes désolés que votre appareil ne fonctionne pas correctement. Chez AZERTY Solution Informatique, le devis est gratuit seulement si votre appareil est réparé dans nos locaux. Dans le cas contraire le devis vous sera facturé à 30 euros.'),0,1);
                $pdf->Ln(3);
                $pdf->MultiCell(75,3,utf8_decode('Pendant la maintenance de votre appareil :'),0,1);
                $pdf->MultiCell(75,3,utf8_decode('- Lors de la sauvegarde de vos données, AZERTY Solution Informatique décline toute responsabilité en cas de perte de données.'),0,1);
                $pdf->MultiCell(75,3,utf8_decode('- Il est possible qu\'AZERTY désactive le mot de passe de votre session ou le remplace.'));
                $pdf->MultiCell(75,3,utf8_decode('- En cas échéant, il est possible de désactiver les logiciels de sécurité tiers.'));
                $pdf->MultiCell(75,3, utf8_decode('- Si la sécurité de vos données vous préoccupe, faites une sauvegarde de vos données sur un disque externe avant qu\'il soit pris en charge par AZERTY.'));
                //2 paragraphe
                $pdf->Ln(3);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(60,10,'Restauration du logiciel');
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('Lorsque nous restaurons votre système d\'exploitation, nous installons la dernière mise à jour de votre version de MacOS, Windows ou Linux. Si nous ne pouvons pas déterminer la version spécifique de votre système d\'exploitation, nous restaurons la dernière mise à jour installé à l\'origine. Il vous incombe de sauvegarder tous les programmes, logiciels et données existants, ainsi que d\'effacer toutes les données existantes avant toute réparation en cas de pertes de données. AZERTY Solution Informatique ne peut être tenue responsable de la perte, de la récupération ou de la mise en péril des données ou des programmes, ni de l\'impossibilité d\'utiliser le matériel du fait des réparations effectuées. Vous pourrez utiliser votre sauvegarde pour réinstaller vos données ou application. Vérifier si des mises à jour supplémentaires sont disponibles.'),0,1);
                //3  paragarphe
                $pdf->Ln(3);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(100,10,utf8_decode('Délai de réparation'));
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('AZERTY Solution Informatique propose une réparation le jour même pour certain type de réparation, par exemple s\'il faut remplacer une pièce disponible déjà dans nos locaux. Si les techniciens estiment une durée plus longue que 48h, ils vous tiendront informés du délai de réparation.'),0,1);
                // 4 paragraphe
                $pdf->Ln(3);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(100,10,utf8_decode('Type d\'appareil'));
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('Nous prenons en charge les PC portables, les PC de bureau ainsi que les imprimantes. De nombreux utilisateurs pensent que leur PC est trop vieux pour être réparé. Ce constat est relatif. En réalité, l\'âge de votre ordinateur compte moins que la qualité du processeur et des autres composants installés. Il suffit souvent de procéder à un bon nettoyage et à une optimisation de votre ordinateur pour lui rendre sa performance initiale. Dans les cas les plus sévères, une réinitialisation de votre PC peut s\'avérer nécessaire. Une maintenance d\'ordinateur reste cependant largement plus rentable qu\'un achat d\'un ordinateur neuf de qualité.'));
                //------------------------------------------------------------------------------Droite
                //1 paragraphe
                $pdf->SetY(51);
                $pdf->SetLeftMargin($x+90);
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(50,10,'Devis et Garantie');
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('Les tarifs varient pour les différents services proposés par AZERTY. Pour plus d\'information contactez-nous par courriel à l\'adresse contact@azertyfrance.fr, par téléphone au 01 34 15 52 32, ou sur demande par courrier postal à l\'adresse suivante: 38 Rue de la Station Interphone 8, 95130 Franconville. Les tarifs, exprimés en EUR toutes taxes comprises, sont systématiquement communiqués au client et validés d\'un commun accord entre le client et AZERTY Solution Informatique avant toute intervention.'),0,1);
                $pdf->Ln(3);
                $pdf->MultiCell(75,3,utf8_decode('Dans l\'ensemble des cas d\'exclusion de garantie, AZERTY Solutions Informatique adresse au client le devis correspondant à la remise en état du produit. Le client peut accepter ou refuser le devis. Si le client accepte le devis, il doit nécessairement répondre par mail \'bon pour accord\' ou répondre via un lien. A réception, AZERTY Solutions Informatiques effectuera les réparations conformément aux devis validés. Une fois réparé et prêt, AZERTY solutions informatiques contacte le client pour venir récupérer son bien. Sans aucune réponse de la part du client dans un délais de trois mois et suite à deux relances nous enverrons pour recyclage le bien. Le règlement par le client bénéficiaire d\'une prestation chez AZERTY est fait en globalité immédiatement après la fin de la prestation directement au technicien par l\'un des moyens de paiement accepté par AZERTY Solutions Informatique : carte bancaire et espèces. Une facture (électronique ou sur support papier) sera ensuite adressée au client, dans un délai de sept jours. Celle-ci stipulera la nature et la durée de l\'intervention, ainsi que le montant à régler en fonction du taux horaire en vigueur au moment de l\'intervention. La garantie AZERTY couvre les défauts des pièces remplacées lors de la réparation. Les techniciens qui y travaillent vous proposent un service de qualité, les réparations sont effectuées avec des pièces neuve ou reconditionnées : neuve, elle est généralement garantie 1 ou 2 ans par le fournisseur. Reconditionnée, la garantie varie entre 1 à 6 mois selon le fournisseur. AZERTY s\'engage à réparer votre appareil en cas de panne pendant la période couverte par la garantie, toutefois la garantie couvre uniquement les pièces et non la main-d\'oeuvre.'),0,1);
                //2 paragraphe
                $pdf->SetFont('Arial','B',10);
                $pdf->MultiCell(50,10,utf8_decode('Données personnelles'));
                $pdf->SetFont('Times','',8);
                $pdf->MultiCell(75,3,utf8_decode('AZERTY Solution Informatique informe le client que certaines de ses données personnelles sont enregistrées à des fins de gestion de la relation client, notamment dans le cadre de la communication d\'offres commerciales émises par AZERTY Solutions Informatiques. Ces informations sont renseignées lors du saisie du formulaire de prise en charge par le client. AZERTY Solutions Informatiques s\'engage à respecter la plus stricte confidentialité concernant les données personnelles du client et à ne pas divulguer, sous n\'importe quel prétexte que ce soit, les informations auxquelles elle a accès au cours de ses prestations.'),0,1);
               
                $pdf->Ln();
                $pdfFilepath = '../public/pdf_clients/AZERTY-'.$client->getNom().'-'.$client->getPrenom().'-'.date('Y-m-d-H-i-s').'.pdf';
                $pdf->Output( $pdfFilepath ,'F');
                
               
                //Envoie un mail
               $data = (new TemplatedEmail())
               ->from((new Address('noreply@azertypro.fr','AZERTY Solutions Informatiques')))
               ->to(new Address($client->getMail()))
               ->bcc(new Address('contact@azertyfrance.fr'))
               ->cc('noreplyazertyfrance@gmail.com','contact@azertyfrance.fr', 'dinformatique95@gmail.com')
               ->embedFromPath('../public/images/mail/whatsapp.png', 'whatsapp')
               ->embedFromPath('../public/images/mail/location.png', 'location')
               ->embedFromPath('../public/images/mail/phone.png', 'phone')
               //->bcc('bcc@example.com')
               //->priority(Email::PRIORITY_HIGH)
               ->replyTo('contact@azertyfrance.fr')
               ->subject('Dépot chez AZERTY Solutions Informatiques')
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
               
                // enregistre dans axonaut 
                if($form->get("entreprise")->getData()){
                    $clientForm = 'Entreprise';
                    $nom = $form->get("entreprise")->getData();
                }
                else {
                    $clientForm = 'Particulier';
                    $nom = $client->getPrenom().' '.$client->getNom();
                }

                 if ($client->getPersonne()=='Mme') {
                    $civil = 2;
                }
                else {
                    $civil = 1;
                }
                $curl2 = curl_init();
                curl_setopt_array($curl2,[
                    CURLOPT_URL => 'https://axonaut.com/api/v2/companies',
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => ['userApiKey: a4df1357607aac071de4a6b49e458398', "content-type:application/json;charset=utf-8", 'accept: application/json'],
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_POSTFIELDS => '{ "name": "'.$nom.'", "address_contact_name":"'.$client->getNom().'", "address_city":"'.$client->getRue().'", "address_country": "'.$client->getRue().' '.$client->getVille().'", "is_prospect": true, "is_customer": true, "comments":" Marque : '.$appareil->getMarque().' Modele : '.$appareil->getModele().' Numero de série : '.$appareil->getNs().'" , "custom_fields": {}, "categories": [ "'.$clientForm.'" ], "employees" :{ "firstname":"'.$client->getPrenom().'",  "lastname":"'.$client->getNom().'" , "email":"'. $client->getMail().'", "phone_number":"'.$client->getTel().'",  "cellphone_number":"'. $client->getTel().'", "job": null,  "is_billing_contact": false, "custom_fields": [] } }'
                ]);
                $data2 = curl_exec($curl2);
                if (!$data2) {
                    echo curl_error($curl2);
                }
                $explode = explode(":", $data2);
                $id = explode(",",$explode[1])[0];
                
                curl_close($curl2);
                $curl1 = curl_init();
                curl_setopt_array($curl1,[
                    CURLOPT_URL => 'https://axonaut.com/api/v2/employees',
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_HTTPHEADER => ['userApiKey: a4df1357607aac071de4a6b49e458398', "content-type:application/json;charset=utf-8", 'accept: application/json'],
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_POST => true,
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_POSTFIELDS => '{ "firstname":"'.$client->getPrenom().'",  "lastname":"'.$client->getNom().'" , "gender" : '.$civil.', "email":"'. $client->getMail().'", "phone_number":"'.$client->getTel().'",  "cellphone_number":"'. $client->getTel().'", "job": null,  "is_billing_contact": false, "company_id": '.$id.', "custom_fields": [] }'
                ]);
                
                $data1 = curl_exec($curl1);
                if (!$data1) {
                    echo curl_error($curl1);
                }
                curl_close($curl1);
                //******************* */
                $this->addFlash('message', 'Votre appareil à bien été deposer chez AZERTY Solutions Informatiques, nous vous tiendrons au courant des point d\'avencement de votre appareil.');
            }
            return $this->render('depot/index.html.twig', [ 'client'=>$client, 'appareil'=>$appareil,'form' => $form->createView()]);
        }

        /**
        * @Route("/condition", name="condition", methods={"GET"})
        */
    
        public function condition(){
            return $this->render('depot/condition.html.twig', []);
        }
        /**
        * @Route("/legal", name="mention_legal", methods={"GET"})
        */
    
        public function legal(){
            return $this->render('depot/mention_legal.html.twig', []);
        }
   
}