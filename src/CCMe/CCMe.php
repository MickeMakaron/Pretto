<?php
/**
 * Controller for Me-website.
 * 
 * @package PrettoCore
 */
class CCMe implements IController 
{
	private $pr = null;
	
	
	/***
	* Instantiate CPretto object and common data for Me controller.
	*/
	public function __construct()
	{
		$this->pr = CPretto::instance();
		
		
		$this->pr->data['title'] = "Min me-sida";
		
		$this->pr->data['above'] = CNav::fromDir("..", "nav-kmom");
		
		

		$logoUrl = $this->pr->request->base_url . "img/logo.gif";
		$this->pr->data['header'] = <<<EOD
		<div id="banner">
			<a href="index.php">
				<img src="{$logoUrl}" alt="logo"/>
			</a>
		</div>
EOD;
		$url = 'me/';
		$navItems = array
		(
			'index'			=> array('text'=>'Me',         	'url'=>$this->pr->request->createUrl($url)),
			'report'		=> array('text'=>'Redovisning', 'url'=>$this->pr->request->createUrl($url.'report')),
			'viewsource'	=> array('text'=>'Källkod', 	'url'=>$this->pr->request->createUrl($url.'viewsource')),
			'CCIndex'		=> array('text'=>'Index',		'url'=>$this->pr->request->createUrl())
		);
		$this->pr->data['header'] .= CNav::fromArray($navItems, "navbar");

		$this->pr->data['footer'] = <<<EOD
				<hr>
				<a href="http://validator.w3.org/check/referer">HTML5</a>
				<a href="http://jigsaw.w3.org/css-validator/check/referer">CSS</a>
				<a href="http://validator.w3.org/unicorn/check?ucn_uri=referer&amp;ucn_task=conformance">Unicorn</a>
EOD;
	}
	
	
	/**
	* Implementing interface IController. All controllers must have an index action.
	*/
	public function index() 
	{  
		$this->pr->data['title'] = "Min me-sida!";
		$baseUrl = $this->pr->request->base_url;
		$tacoUrl = $baseUrl . "img/taco.jpg";
		$cmUrl = $baseUrl . "img/me.png";
		$this->pr->data['main'] = <<<EOD
			<article>
				<h1>Om mig</h1>
				
				<figure class="right_top">
					<img src="{$tacoUrl}">
					<figcaption>
						<p>Bild: En ledsen taco.</p>
					</figcaption>
				</figure>
				

				<p>Jag är en ung kis som inte bor på södern. Utöver detta så har jag en hund, en dator och Mikael Roos att vägleda mig genom det andliga och virtuella livet. </p>
				<p>Min favoriträtt är korv med mos och jag tycker faktiskt att rosa är fint, till skillnad från vissa andra. <u>Inga namn nämns</u>. För att komma tillrätta med mig själv sätter jag mig i soffan nedför trappen, eftersom jag bor i ett tvåvånigt hus. Annars skulle jag dag ut och dag in sitta och knattra på tangentbordet eftersom jag <b>fryser</b>. Det är trots allt vinter.<p>
				<p>Det är faktiskt inte jag på bilden, tyvärr. Det där är ingen kis, utan det är nämligen en kristallmö. Sådan är inte jag. Jag är en kis.</p>
				<p>Slutligen vill jag passa på att hälsa. Dessutom borde loggan uppe i headern snarast ändras. Det är av yttersta, grava importans att det sker så snart som mäkligt. Varför? Jo, för att det när som hels<p>

				<p>Med vänliga hälsningar,</p>
				<p>Kristallkisen </p>
				
				<footer id="byline">
					<div id="bylineDiv">
						<figure id="right_top"><img src="{$cmUrl}" alt="Hej du" height="60"></figure>
						<p>/Kristallkisen</p>
						<p>"Jag skulle gå 500 mil och jag skulle gå 500 mil till. Tjalalala!"</p>
						<p>- Dalai Llama</p>
					</div>
				</footer>
			</article>
EOD;
	}
	
	public function report()
	{
		$this->pr->data['title'] = "Min redovisningssida!";
		$this->pr->data['main'] = "
			<article>
				<h1>Redovisning</h1>".
				file_get_contents("kmom02_report.php").
				file_get_contents("../kmom01/kmom01_report.php").
			"</article>";
	}
	
	public function viewsource()
	{
		$this->pr->data['title'] = "Visa källkod";

		$sourceBaseDir = dirname(__FILE__);
		$sourceNoEcho = true;
		$this->pr->data['main'] = CSource::printSource($sourceBaseDir, $sourceNoEcho, $this->pr->data['style']);
	}
}  