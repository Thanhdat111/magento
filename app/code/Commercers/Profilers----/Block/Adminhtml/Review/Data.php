<?php 

namespace Commercers\Profilers\Block\Adminhtml\Review;

class Data extends  \Magento\Backend\Block\Template {
    
    
   
    
    public function __construct(
            \Magento\Backend\Block\Template\Context $context,
            \Commercers\Profilers\Service\Html $htmlTree
            ) {
        
        $this->htmlTree = $htmlTree;
        parent::__construct($context);
    }


    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
    
    
    
    
    public function renderHtmlTree($data){
        return $this->htmlTree->arrayToHtml($data);
    }







    public function renderAttributeItem($key, $value, $level = 0, $parentKey = ''){

        if($value == NULL) $value = 'NULL';
        $subClass = '';
        if($level >=1) $subClass = 'sub';
        $itemHtml = "<li><span class='attr-name level-".$level." ".$subClass."'>".$key."</span>
                    <ul class='nested'>";
        
        if(!is_array($value) && !is_object($value)){
            if($parentKey){
                if($parentKey != 'item'){
                    $keyPath = $parentKey."/".$key;
                }else{
                    $keyPath = $key;
                }
            }
            else
                $keyPath = $key;
            $hintXml = '<xsl:value-of select="$sepstart" /><xsl:value-of select="'.$keyPath.'"/><xsl:value-of select="$sepend" /> ';
            $itemHtml .= "<li class='value'>".$value."<br>". htmlspecialchars($hintXml)."</li>";
        
            
        }elseif(is_array($value)){

            //$parentKey = 
            foreach($value as $valueKey => $valueItem){
                if(is_numeric($valueKey)) $valueKey = $key;
                $parentKey = $key;
                $itemHtml .= $this->renderAttributeItem($valueKey, $valueItem, ++$level, $parentKey);
                if($level == 2)
                    break;
            }

        }
        $itemHtml .= "</ul></li>";
        return $itemHtml;
    }
    
    
    
}