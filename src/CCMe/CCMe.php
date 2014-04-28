<?php
/**
 * Controller for Me-website.
 * 
 * @package PrettoCore
 */
class CCMe extends CObject implements IController 
{
	private $pr = null;
	
	
	/***
	* Instantiate CPretto object and common data for Me controller.
	*/
	public function __construct()
	{
		parent::__construct(); 

		$this->config['theme']['name'] = 'HTML5Boilerplate';
		$this->config['theme']['path'] = 'themes/HTML5Boilerplate';
		$this->config['theme']['stylesheet'] = 'themes/HTML5Boilerplate/style.css';
		$this->config['theme']['template_file'] = 'default.tpl.php';
		$this->data['theme']['parent'] = null; 
				
		$this->data['title'] = "Min me-sida";
		$this->config['theme']['data']['meta_description'] = "Meeee";
		$this->config['theme']['data']['style'] = null;

		
		$this->data['above'] = CNav::fromDir(realpath("../"), "nav-kmom");
		
		

		$logoUrl = $this->request->createUrl('img/logo.gif');
		$baseUrl = $this->request->createUrl($this->request->controller);
		
		$this->config['theme']['data']['header'] = <<<EOD
		<div id="banner">
			<a href="{$baseUrl}">
				<img src="{$logoUrl}" alt="logo"/>
			</a>
		</div>
EOD;
		$url = 'me/';
		$navItems = array
		(
			'index'			=> array('text'=>'Me',         	'url'=>$this->request->createUrl($url)),
			'report'		=> array('text'=>'Redovisning', 'url'=>$this->request->createUrl($url.'report')),
			'viewsource'	=> array('text'=>'Källkod', 	'url'=>$this->request->createUrl($url.'viewsource')),
			'CCIndex'		=> array('text'=>'Index',		'url'=>$this->request->createUrl())
		);
		$this->config['theme']['data']['header'] .= CNav::fromArray($navItems, "navbar");

		$this->config['theme']['data']['footer'] = <<<EOD
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
		$this->views->setTitle('Min me-sida!');
		$this->views->addInclude
		(
			__DIR__ . '/index.tpl.php',
			array()
		);
	}
	
	public function report()
	{
	
		$this->views->setTitle('Min redovisningssida!');
		$this->views->addInclude
		(
			__DIR__ . '/report.tpl.php',
			array()
		);
	}
	
	public function viewsource()
	{
		$this->views->setTitle('Visa källkod');
		
		$sourceBaseDir = dirname(__FILE__);
		$sourceNoEcho = true;
		$source = CSource::printSource($sourceBaseDir, $sourceNoEcho, $this->data['style']);
		$this->views->addInclude
		(
			__DIR__ . '/viewsource.tpl.php',
			array
			(
				'source' => $source,
			)
		);
		

	}
}  