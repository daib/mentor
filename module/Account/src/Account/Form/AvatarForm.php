<?php

namespace Account\Form;

use Zend\InputFilter;
use Zend\Form\Element;
use Zend\Form\Form;

class AvatarForm extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->addElements();
        $this->addInputFilter();
    }

    public function addElements()
    {
        // File Input
        $file = new Element\File('upload-avatar-input');
        $file->setLabel('Avatar Image Upload')
             ->setAttribute('id', 'upload-avatar-input');
        $this->add($file);
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter\InputFilter();

        // File Input
        $fileInput = new InputFilter\FileInput('upload-avatar-input');
        $fileInput->setRequired(true);
        $filter = new \Zend\Filter\File\RenameUpload('/Users/daib/prog/web/mentor/data/uploaded-files/');
        $filter->setUseUploadName(true);
        $fileInput->getFilterChain()->attach($filter);
        $inputFilter->add($fileInput);

        $this->setInputFilter($inputFilter);
    }
}
