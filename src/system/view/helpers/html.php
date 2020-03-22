<?php

namespace Nuclear\system\view\helpers;

class html
{
    /**
     * Imprime input select
     *
     * @param string $name
     * @param array  $options
     * @param mixed  $value
     * @param array  $tags
     * @return void
     */
    public function select(string $name, array $options, array $tags = null, $value = null)
    {
        $sValue = (isset($value) && !empty($value))? 'value="'.$value.'"': 'value=""';
        $sName  = (isset($name) && !empty($name))? 'name="'.$name.'"': null;
        $sField = (isset($name) && !empty($name))? 'id="field-'.$name.'"': null;
        if(isset($tags) && !empty($tags)){
            $sClass    = (isset($tags['class']) && !empty($tags['class']))? 'class="'.$tags['class'].'"': null;
            $sRequired = (isset($tags['required']) && !empty($tags['required']))? 'required': null;
            $sDisabled = (isset($tags['disabled']) && !empty($tags['disabled']))? 'disabled="disabled"': null;
            $sMultiple = (isset($tags['multiple']) && !empty($tags['multiple']))? 'multiple="multiple"': null;
            $sSize     = (isset($tags['size']) && !empty($tags['size']))? 'size="'.$tags['size'].'"': null;
            if(isset($tags['field']) && !empty($tags['field']))
                $sField = 'id="'.$tags['field'].'"';
        }
        $select = "<select $sName $sField $sClass $sValue $sRequired $sSize $sMultiple $sDisabled >\n";
        
        if(!isset($sRequired)){
            $select .= "<option value=\"\" >Selecionar...</option>\n";
        }

        foreach($options as $item){
            if(isset($item) && !empty($item))
                $select .= '<option value="'.$item['value'].'" >'.$item['label']."</option>\n";
        }

        return $select .= '</select>';
    }
}



