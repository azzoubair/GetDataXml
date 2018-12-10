<?php
namespace OaiPmhHarvester\Form;

use Zend\Form\Form;

class HarvestForm extends Form
{
    protected $mappingClasses;

    public function init()
    {
		$resourceType = $this->getOption('resourceType');
        $serviceLocator = $this->getServiceLocator();
        $currentUser = $serviceLocator->get('Omeka\AuthenticationService')->getIdentity();
        $acl = $serviceLocator->get('Omeka\Acl');
        $this->setAttribute('action', 'xmlimport/map');
        $this->add([
                'name' => 'xml',
                'type' => 'file',
                'options' => [
                    'label' => 'XML file', 
                    'info' => 'The XML file to upload', 
                ],
                'attributes' => [
                    'id' => 'xml',
                    'required' => 'true',
                ],
        ]);

        $resourceTypes = array_keys($this->mappingClasses);
        $valueOptions = [];
        foreach ($resourceTypes as $resourceType) {
            $valueOptions[$resourceType] = ucfirst($resourceType);
        }
		
		$this->add([
            'name' => 'code_xml',
            'type' => 'textarea',
            'options' => [
                'label' => 'code', 
                'info' => 'Coller votre code', 
            ],
            'attributes' => [
                'id' => 'code_xml',
                'class' => 'input-body',
            ],
        ]);
		
		$this->add([
            'name' => 'comment',
            'type' => 'textarea',
            'options' => [
                'label' => 'Comment', 
                'info' => 'A note about the purpose or source of this import', 
            ],
            'attributes' => [
                'id' => 'comment',
                'class' => 'input-body',
            ],
        ]);

          
            if ($acl->userIsAllowed('Omeka\Entity\Item', 'change-owner')) {
                $this->add([
                    'name' => 'o:owner',
                    'type' => ResourceSelect::class,
                    'attributes' => [
                        'id' => 'select-owner',
                        'value' => $currentUser->getId(),
                        ],
                    'options' => [
                        'label' => 'Owner', 
                        'resource_value_options' => [
                            'resource' => 'users',
                            'query' => [],
                            'option_text_callback' => function ($user) {
                                return $user->name();
                            },
                            ],
                        ],
                ]);
            }

            
            $inputFilter = $this->getInputFilter();
            $inputFilter->add([
                'name' => 'o:resource_template[o:id]',
                'required' => false,
                ]);
            $inputFilter->add([
                'name' => 'o:resource_class[o:id]',
                'required' => false,
                ]);
            $inputFilter->add([
                'name' => 'o:item_set',
                'required' => false,
                ]);
        
    }

    public function setServiceLocator($serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
        

        $inputFilter = $this->getInputFilter();
        $inputFilter->add([
            'name' => 'xml',
            'required' => true,
        ]);
    }

    public function setMappingClasses(array $mappingClasses)
    {
        $this->mappingClasses = $mappingClasses;
    }
}