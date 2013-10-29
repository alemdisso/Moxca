<?php
class Moxca_Util_SetupNavigation {


    public function __construct(Zend_View_Interface $view, $path)
    {
//        $xml = simplexml_load_file(APPLICATION_PATH . '/configs/navigation.xml');
        $xml = simplexml_load_file($path);
        $sxe = new SimpleXMLElement($xml->asXML());
        $nav = $sxe->nav;
        $home = $nav->home;
        $pages = $home->pages;

//        $works = $pages->addChild('works');
//        $works->addChild('label', $view->translate("#Works"));
//        $works->addChild('uri', $view->translate('/works'));
//        $worksPages = $works->addChild('pages');



//        $works = $pages->works;
//        $pagesW = $works->pages;
//        $serie = $pagesW->addChild('serie');
//        $serie->addChild('label', 'Coleção Mico Maneco');
//        $serie->addChild('uri', '/colecao/mico-maneco');
        $config = new Zend_Config_Xml($sxe->asXML(), 'nav');
        $navigation = new Zend_Navigation($config);
        return $view->navigation($navigation);
    }


}

