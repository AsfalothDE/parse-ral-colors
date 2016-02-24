<?php

namespace AsfalothDe;

use PHPHtmlParser\Dom;

class RalParser
{
    public $url;

    public $mapping = array('RAL', 'RGB', 'HEX', 'DE', 'EN', 'FR', 'ES', 'IT', 'NL');

    /**
     * RalParser constructor.
     */
    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getMapping()
    {
        return $this->mapping;
    }

    /**
     * @param array $mapping
     */
    public function setMapping($mapping)
    {
        $this->mapping = $mapping;
    }

    public function parse()
    {
        $arrRal = array();

        //$objDom = new \simple_html_dom($strHtml);
        $objDom = new Dom();
        $objDom->loadFromUrl($this->url);
        $arrRows = $objDom->find('div.Section1 table.MsoNormalTable tr');
        for ($i = 12; $i < count($arrRows) - 7; $i++)
        {
            $arrCells = $arrRows[$i]->find('td');

            if (count($arrCells) && $arrCells[0]->getTag()->name() == 'td') {
                if (count($arrCells) > 7 && count($arrCells) < 12) {
                    if ($return = $this->_addColor($arrCells, $i)) {
                        $arrRal[] = $return;
                    }
                    break;
                }
            }
        }
        return $arrRal;
    }

    private function _addColor($rows, $index) {
        $data = array();
        foreach ($rows as $row) {
            $txt = strip_tags($row->innerhtml);
            $txt = trim(preg_replace('/\s+/', ' ', $txt));
            $txt = trim(str_replace('&nbsp;', ' ', $txt));
            if (!$txt) continue;
            array_push($data, $txt);
        }
        return array_combine($this->mapping, $data);
    }
}